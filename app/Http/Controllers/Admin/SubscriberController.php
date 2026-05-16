<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        $lang   = $request->get('lang');
        $search = $request->get('search');

        $query = Subscriber::orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($lang) {
            $query->where('lang', $lang);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->paginate(25)->withQueryString();

        $totalCount        = Subscriber::count();
        $activeCount       = Subscriber::where('status', 'active')->count();
        $unsubscribedCount = Subscriber::where('status', 'unsubscribed')->count();
        $bouncedCount      = Subscriber::where('status', 'bounced')->count();

        return view('admin.subscribers.index', compact(
            'subscribers', 'status', 'lang', 'search',
            'totalCount', 'activeCount', 'unsubscribedCount', 'bouncedCount'
        ));
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,unsubscribe',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:subscribers,id',
        ]);

        $ids    = $request->input('ids');
        $action = $request->input('action');

        if ($action === 'delete') {
            Subscriber::whereIn('id', $ids)->delete();
            $message = count($ids) . ' subscriber(s) deleted.';
        } else {
            Subscriber::whereIn('id', $ids)->update([
                'status'           => 'unsubscribed',
                'unsubscribed_at'  => now(),
            ]);
            $message = count($ids) . ' subscriber(s) unsubscribed.';
        }

        return back()->with('success', $message);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file   = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');

        $header  = fgetcsv($handle); // skip header row
        $inserted = 0;
        $skipped  = 0;

        $batch = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row[0])) {
                continue;
            }

            $email = trim($row[0]);
            $name  = isset($row[1]) ? trim($row[1]) : null;
            $lang  = isset($row[2]) ? trim($row[2]) : 'ar';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            if (Subscriber::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            $batch[] = [
                'email'          => $email,
                'name'           => $name ?: null,
                'lang'           => in_array($lang, ['ar', 'en', 'nl', 'de']) ? $lang : 'ar',
                'status'         => 'active',
                'source'         => 'import',
                'token'          => Str::random(64),
                'subscribed_at'  => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ];

            $inserted++;

            if (count($batch) >= 100) {
                Subscriber::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Subscriber::insert($batch);
        }

        fclose($handle);

        return back()->with('success', "Import complete: {$inserted} imported, {$skipped} skipped.");
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers_' . date('Y-m-d') . '.csv"',
        ];

        $columns = ['Email', 'Name', 'Lang', 'Status', 'Source', 'Subscribed At'];

        return response()->stream(function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            Subscriber::where('status', 'active')->orderBy('id')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $sub) {
                    fputcsv($handle, [
                        $sub->email,
                        $sub->name ?? '',
                        $sub->lang,
                        $sub->status,
                        $sub->source,
                        $sub->subscribed_at?->format('Y-m-d') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
