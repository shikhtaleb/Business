<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class RedirectController extends Controller
{
    private function clearRedirectCache(): void
    {
        Cache::forget('redirects_all');
    }

    public function index(): View
    {
        $redirects = Redirect::orderByDesc('updated_at')->paginate(30);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_path'   => 'required|string|max:500|unique:redirects,from_path',
            'to_path'     => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active'   => 'nullable|boolean',
        ]);

        Redirect::create([
            ...$validated,
            'is_active'   => $request->boolean('is_active', true),
            'from_path'   => '/' . ltrim($validated['from_path'], '/'),
            'to_path'     => $validated['to_path'],
        ]);

        $this->clearRedirectCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'تم إنشاء إعادة التوجيه بنجاح.');
    }

    public function edit(Redirect $redirect): View
    {
        return view('admin.redirects.edit', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect): RedirectResponse
    {
        $validated = $request->validate([
            'from_path'   => 'required|string|max:500|unique:redirects,from_path,' . $redirect->id,
            'to_path'     => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active'   => 'nullable|boolean',
        ]);

        $redirect->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'from_path' => '/' . ltrim($validated['from_path'], '/'),
        ]);

        $this->clearRedirectCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'تم تحديث إعادة التوجيه بنجاح.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();
        $this->clearRedirectCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'تم حذف إعادة التوجيه بنجاح.');
    }

    public function toggle(Redirect $redirect): RedirectResponse
    {
        $redirect->update(['is_active' => !$redirect->is_active]);
        $this->clearRedirectCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'تم تحديث حالة إعادة التوجيه.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file    = $request->file('csv_file');
        $handle  = fopen($file->getPathname(), 'r');
        $created = 0;
        $skipped = 0;
        $row     = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;

            // Skip header row
            if ($row === 1 && isset($data[0]) && strtolower(trim($data[0])) === 'from') {
                continue;
            }

            if (count($data) < 2) {
                $skipped++;
                continue;
            }

            $fromPath   = '/' . ltrim(trim($data[0]), '/');
            $toPath     = trim($data[1]);
            $statusCode = isset($data[2]) ? (int) trim($data[2]) : 301;

            if (empty($fromPath) || empty($toPath)) {
                $skipped++;
                continue;
            }

            if (!in_array($statusCode, [301, 302])) {
                $statusCode = 301;
            }

            try {
                Redirect::updateOrCreate(
                    ['from_path' => $fromPath],
                    [
                        'to_path'     => $toPath,
                        'status_code' => $statusCode,
                        'is_active'   => true,
                    ]
                );
                $created++;
            } catch (\Exception) {
                $skipped++;
            }
        }

        fclose($handle);
        $this->clearRedirectCache();

        return redirect()->route('admin.redirects.index')
            ->with('success', "اكتمل الاستيراد: تم استيراد {$created}، تخطي {$skipped}.");
    }
}
