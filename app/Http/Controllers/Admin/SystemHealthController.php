<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SystemHealthController extends Controller
{
    public function index(): View
    {
        $checks = [];

        // PHP version
        $checks['php'] = [
            'label'  => 'إصدار PHP',
            'value'  => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.1', '>=') ? 'ok' : 'warning',
            'note'   => version_compare(PHP_VERSION, '8.1', '>=') ? 'الإصدار مناسب' : 'يُنصح بـ PHP 8.1 أو أعلى',
        ];

        // Database connectivity
        try {
            DB::select('SELECT 1');
            $driver    = config('database.default');
            $dbVersion = match ($driver) {
                'sqlite' => DB::select('SELECT sqlite_version() as v')[0]->v ?? '—',
                default  => DB::select('SELECT VERSION() as v')[0]->v ?? '—',
            };
            $checks['database'] = [
                'label'  => 'قاعدة البيانات',
                'value'  => strtoupper($driver) . ' ' . $dbVersion,
                'status' => 'ok',
                'note'   => 'متصلة وتعمل',
            ];
        } catch (\Throwable $e) {
            $checks['database'] = [
                'label'  => 'قاعدة البيانات',
                'value'  => '—',
                'status' => 'error',
                'note'   => $e->getMessage(),
            ];
        }

        // Storage writable
        $storageOk = is_writable(storage_path());
        $checks['storage'] = [
            'label'  => 'مجلد التخزين',
            'value'  => $storageOk ? 'قابل للكتابة' : 'غير قابل للكتابة',
            'status' => $storageOk ? 'ok' : 'error',
            'note'   => storage_path(),
        ];

        // Cache
        try {
            Cache::put('_health_check', true, 5);
            Cache::forget('_health_check');
            $checks['cache'] = [
                'label'  => 'نظام الكاش',
                'value'  => config('cache.default'),
                'status' => 'ok',
                'note'   => 'يعمل بشكل صحيح',
            ];
        } catch (\Throwable $e) {
            $checks['cache'] = [
                'label'  => 'نظام الكاش',
                'value'  => '—',
                'status' => 'error',
                'note'   => $e->getMessage(),
            ];
        }

        // Queue driver
        $queueDriver = config('queue.default');
        $checks['queue'] = [
            'label'  => 'نظام الطوابير',
            'value'  => $queueDriver,
            'status' => in_array($queueDriver, ['database', 'redis', 'sqs']) ? 'ok' : 'warning',
            'note'   => $queueDriver === 'sync'
                ? 'المهام تُنفَّذ فورياً بدون خادم خلفي'
                : 'طابور خلفي مُعدّ',
        ];

        // Mail driver
        $mailDriver = config('mail.default');
        $checks['mail'] = [
            'label'  => 'نظام البريد',
            'value'  => $mailDriver,
            'status' => $mailDriver !== 'log' ? 'ok' : 'warning',
            'note'   => $mailDriver === 'log'
                ? 'الرسائل مسجَّلة فقط ولا تُرسَل'
                : 'البريد مُعدّ',
        ];

        // ENV mode
        $env = app()->environment();
        $checks['env'] = [
            'label'  => 'بيئة التشغيل',
            'value'  => $env,
            'status' => $env === 'production' ? 'ok' : 'warning',
            'note'   => $env !== 'production'
                ? 'النظام في وضع ' . $env
                : 'وضع الإنتاج مفعّل',
        ];

        // Debug mode
        $debug = config('app.debug');
        $checks['debug'] = [
            'label'  => 'وضع التصحيح (Debug)',
            'value'  => $debug ? 'مفعّل' : 'معطّل',
            'status' => $debug ? 'warning' : 'ok',
            'note'   => $debug
                ? 'عطّل Debug في الإنتاج لأسباب أمنية'
                : 'Debug معطّل',
        ];

        // Disk space
        $free    = disk_free_space('/');
        $total   = disk_total_space('/');
        $usedPct = $total > 0 ? round((($total - $free) / $total) * 100) : 0;
        $checks['disk'] = [
            'label'  => 'مساحة القرص',
            'value'  => $this->formatBytes($free) . ' متاحة',
            'status' => $usedPct > 90 ? 'error' : ($usedPct > 75 ? 'warning' : 'ok'),
            'note'   => "{$usedPct}٪ مستخدمة من أصل " . $this->formatBytes($total),
        ];

        // PHP extensions
        $required   = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'zip'];
        $missingExts = array_filter($required, fn($e) => !extension_loaded($e));
        $checks['extensions'] = [
            'label'  => 'إضافات PHP',
            'value'  => count($missingExts) === 0
                ? 'جميع الإضافات المطلوبة مثبّتة'
                : implode(', ', $missingExts) . ' — مفقودة',
            'status' => count($missingExts) === 0 ? 'ok' : 'error',
            'note'   => implode(', ', $required),
        ];

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
