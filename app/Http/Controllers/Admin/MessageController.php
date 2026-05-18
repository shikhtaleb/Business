<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\MessageReply;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query  = Message::orderBy('created_at', 'desc');

        if (in_array($status, ['unread', 'read', 'replied'])) {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $messages    = $query->paginate(20)->withQueryString();
        $unreadCount = Message::unreadCount();

        return view('admin.messages.index', compact('messages', 'status', 'unreadCount'));
    }

    public function show(Message $message)
    {
        $message->markRead();
        $replies = $message->replies()->with('user')->orderBy('created_at')->get();

        return view('admin.messages.show', compact('message', 'replies'));
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'body'       => 'required|string|max:5000',
            'send_email' => 'nullable|boolean',
        ]);

        $sendEmail = $request->boolean('send_email');
        $sent      = false;

        if ($sendEmail) {
            $sent = $this->sendReplyEmail($message, $request->input('body'));
        }

        MessageReply::create([
            'message_id' => $message->id,
            'user_id'    => Auth::id(),
            'body'       => $request->input('body'),
            'sent_email' => $sent,
        ]);

        $message->update([
            'status'     => 'replied',
            'replied_at' => now(),
            'replied_by' => Auth::id(),
        ]);

        ActivityLog::record("Replied to message from {$message->email}", 'messages');

        return back()->with('success', $sent
            ? __('admin.reply_sent_email')
            : __('admin.reply_saved'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        ActivityLog::record("Message from {$message->email} deleted", 'messages');
        return back()->with('success', __('admin.message_deleted'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_read,mark_unread',
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
        ]);

        $messages = Message::whereIn('id', $request->input('ids'));

        match ($request->input('action')) {
            'delete'      => $messages->delete(),
            'mark_read'   => $messages->update(['status' => 'read']),
            'mark_unread' => $messages->update(['status' => 'unread']),
        };

        return back()->with('success', __('admin.bulk_action_done'));
    }

    private function sendReplyEmail(Message $message, string $body): bool
    {
        try {
            $smtpHost     = Setting::get('smtp_host', '');
            $smtpPort     = Setting::get('smtp_port', 587);
            $smtpUser     = Setting::get('smtp_username', '');
            $smtpPass     = Setting::get('smtp_password', '');
            $smtpEncrypt  = Setting::get('smtp_encryption', 'tls');
            $fromEmail    = Setting::get('smtp_from_email', $smtpUser);
            $fromName     = Setting::get('smtp_from_name', Setting::get('site_name', config('app.name')));

            if (!$smtpHost || !$smtpUser) {
                return false;
            }

            config([
                'mail.mailers.smtp.host'       => $smtpHost,
                'mail.mailers.smtp.port'       => (int) $smtpPort,
                'mail.mailers.smtp.username'   => $smtpUser,
                'mail.mailers.smtp.password'   => $smtpPass,
                'mail.mailers.smtp.encryption' => $smtpEncrypt,
                'mail.from.address'            => $fromEmail,
                'mail.from.name'               => $fromName,
            ]);

            Mail::html($this->buildEmailHtml($message, $body, $fromName), function ($mail) use ($message, $fromEmail, $fromName) {
                $mail->to($message->email, $message->name)
                     ->from($fromEmail, $fromName)
                     ->replyTo($fromEmail, $fromName)
                     ->subject('Re: ' . ($message->subject ?: __('admin.contact_reply_subject')));
            });

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function buildEmailHtml(Message $message, string $body, string $fromName): string
    {
        $escapedBody    = nl2br(e($body));
        $escapedOriginal = nl2br(e($message->body));
        $originalDate   = $message->created_at->format('Y-m-d H:i');

        return <<<HTML
<!DOCTYPE html><html><body style="font-family:sans-serif;color:#1e293b;max-width:600px;margin:0 auto;padding:20px;">
<div style="border-bottom:3px solid #FF8528;padding-bottom:16px;margin-bottom:24px;">
  <strong style="color:#FF8528;font-size:18px;">{$fromName}</strong>
</div>
<div style="line-height:1.7;margin-bottom:32px;">{$escapedBody}</div>
<div style="border-top:1px solid #e2e8f0;padding-top:16px;color:#64748b;font-size:13px;">
  <p><strong>رسالتك الأصلية ({$originalDate}):</strong></p>
  <blockquote style="border-left:3px solid #FF8528;margin:0;padding:8px 16px;color:#475569;">{$escapedOriginal}</blockquote>
</div>
</body></html>
HTML;
    }
}
