<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SystemHealthController extends Controller
{
    public function index(): View
    {
        $checks = [];

        // PHP version
        $checks['php'] = [
            'label'  => 'PHP Version',
            'value'  => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.1', '>=') ? 'ok' : 'warning',
            'note'   => PHP_VERSION,
        ];

        // Database connectivity
        try {
            DB::select('SELECT 1');
            $dbVersion = DB::select('SELECT VERSION() as v')[0]->v ?? 'Unknown';
            $checks['database'] = ['label' => 'Database', 'value' => $dbVersion, 'status' => 'ok', 'note' => 'Connected'];
        } catch (\Throwable $e) {
            $checks['database'] = ['label' => 'Database', 'value' => '—', 'status' => 'error', 'note' => $e->getMessage()];
        }

        // Storage writable
        $storageOk = is_writable(storage_path());
        $checks['storage'] = [
            'label'  => 'Storage Writable',
            'value'  => $storageOk ? 'Writable' : 'Not Writable',
            'status' => $storageOk ? 'ok' : 'error',
            'note'   => storage_path(),
        ];

        // Cache
        try {
            Cache::put('_health_check', true, 5);
            Cache::forget('_health_check');
            $checks['cache'] = ['label' => 'Cache', 'value' => config('cache.default'), 'status' => 'ok', 'note' => 'Working'];
        } catch (\Throwable $e) {
            $checks['cache'] = ['label' => 'Cache', 'value' => '—', 'status' => 'error', 'note' => $e->getMessage()];
        }

        // Queue driver
        $queueDriver = config('queue.default');
        $checks['queue'] = [
            'label'  => 'Queue Driver',
            'value'  => $queueDriver,
            'status' => in_array($queueDriver, ['database', 'redis', 'sqs']) ? 'ok' : 'warning',
            'note'   => $queueDriver === 'sync' ? 'Jobs run synchronously (no background workers)' : 'Background queue configured',
        ];

        // Mail driver
        $mailDriver = config('mail.default');
        $checks['mail'] = [
            'label'  => 'Mail Driver',
            'value'  => $mailDriver,
            'status' => $mailDriver !== 'log' ? 'ok' : 'warning',
            'note'   => $mailDriver === 'log' ? 'Emails are only logged, not sent' : 'Mail configured',
        ];

        // ENV mode
        $env = app()->environment();
        $checks['env'] = [
            'label'  => 'Environment',
            'value'  => $env,
            'status' => $env === 'production' ? 'ok' : 'warning',
            'note'   => $env !== 'production' ? 'Running in ' . $env . ' mode' : 'Production mode',
        ];

        // Debug mode
        $debug = config('app.debug');
        $checks['debug'] = [
            'label'  => 'Debug Mode',
            'value'  => $debug ? 'ON' : 'OFF',
            'status' => $debug ? 'warning' : 'ok',
            'note'   => $debug ? 'Disable debug in production for security' : 'Debug disabled',
        ];

        // Disk space
        $free  = disk_free_space('/');
        $total = disk_total_space('/');
        $usedPct = $total > 0 ? round((($total - $free) / $total) * 100) : 0;
        $checks['disk'] = [
            'label'  => 'Disk Space',
            'value'  => $this->formatBytes($free) . ' free',
            'status' => $usedPct > 90 ? 'error' : ($usedPct > 75 ? 'warning' : 'ok'),
            'note'   => "{$usedPct}% used of " . $this->formatBytes($total),
        ];

        // Installed PHP extensions
        $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'zip'];
        $missingExts = array_filter($requiredExtensions, fn($ext) => !extension_loaded($ext));
        $checks['extensions'] = [
            'label'  => 'PHP Extensions',
            'value'  => count($missingExts) === 0 ? 'All required loaded' : implode(', ', $missingExts) . ' missing',
            'status' => count($missingExts) === 0 ? 'ok' : 'error',
            'note'   => implode(', ', $requiredExtensions),
        ];

        // Summary counts
        $summary = [
            'ok'      => count(array_filter($checks, fn($c) => $c['status'] === 'ok')),
            'warning' => count(array_filter($checks, fn($c) => $c['status'] === 'warning')),
            'error'   => count(array_filter($checks, fn($c) => $c['status'] === 'error')),
        ];

        return view('admin.system.health', compact('checks', 'summary'));
    }

    private function formatBytes(int|float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i     = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }
}
