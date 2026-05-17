<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Setting;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Campaign $campaign)
    {
    }

    public function handle(): void
    {
        try {
            $this->configureMail();

            $query = Subscriber::where('status', 'subscribed');

            if ($this->campaign->target_lang) {
                $query->where('lang', $this->campaign->target_lang);
            }

            $subscribers = $query->get();
            $sentCount   = 0;

            foreach ($subscribers as $subscriber) {
                $lang    = $subscriber->lang ?? 'ar';
                $subject = $lang === 'ar'
                    ? ($this->campaign->subject_ar ?? $this->campaign->subject_en)
                    : ($this->campaign->subject_en ?? $this->campaign->subject_ar);
                $body    = $lang === 'ar'
                    ? ($this->campaign->body_ar ?? $this->campaign->body_en)
                    : ($this->campaign->body_en ?? $this->campaign->body_ar);

                if (!$subject || !$body) {
                    continue;
                }

                try {
                    Mail::html($body, function ($mail) use ($subscriber, $subject) {
                        $mail->to($subscriber->email, $subscriber->name ?? '')
                             ->subject($subject);
                    });

                    $this->campaign->subscribers()->syncWithoutDetaching([
                        $subscriber->id => ['sent_at' => now()],
                    ]);

                    $sentCount++;
                } catch (\Throwable) {
                    // Continue sending to other subscribers even if one fails
                }
            }

            $this->campaign->update([
                'status'     => 'sent',
                'sent_at'    => now(),
                'sent_count' => $sentCount,
            ]);
        } catch (\Throwable $e) {
            $this->campaign->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function configureMail(): void
    {
        $host    = Setting::get('smtp_host', '');
        $user    = Setting::get('smtp_username', '');
        $pass    = Setting::get('smtp_password', '');
        $port    = Setting::get('smtp_port', 587);
        $encrypt = Setting::get('smtp_encryption', 'tls');
        $from    = Setting::get('smtp_from_email', $user);
        $name    = Setting::get('smtp_from_name', Setting::get('site_name', config('app.name')));

        if (!$host || !$user) {
            throw new \RuntimeException('SMTP غير مُعدّ. تعذّر إرسال الحملة.');
        }

        config([
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => (int) $port,
            'mail.mailers.smtp.username'   => $user,
            'mail.mailers.smtp.password'   => $pass,
            'mail.mailers.smtp.encryption' => $encrypt === 'none' ? null : $encrypt,
            'mail.from.address'            => $from,
            'mail.from.name'               => $name,
        ]);
    }
}
