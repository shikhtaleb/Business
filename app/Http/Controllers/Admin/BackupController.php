<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Backup;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('creator')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.backups.index', compact('backups'));
    }

    public function create(Request $request)
    {
        $type = $request->input('type', 'database');

        $backup = Backup::create([
            'filename'   => '',
            'disk'       => 'local',
            'path'       => '',
            'size'       => 0,
            'type'       => $type,
            'status'     => 'running',
            'created_by' => Auth::id(),
        ]);

        try {
            if ($type === 'database') {
                $result = $this->backupDatabase($backup);
            } else {
                $result = $this->backupFiles($backup);
            }

            ActivityLog::record("Backup created: {$backup->filename} ({$backup->formattedSize()})", 'system');

            return back()->with('success', "تم إنشاء النسخة الاحتياطية: {$backup->filename} ({$backup->formattedSize()})");
        } catch (\Throwable $e) {
            $backup->update(['status' => 'failed', 'notes' => $e->getMessage()]);
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    public function download(Backup $backup)
    {
        if (!Storage::disk('local')->exists($backup->path)) {
            return back()->with('error', 'ملف النسخة الاحتياطية غير موجود.');
        }

        return Storage::disk('local')->download($backup->path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        if (Storage::disk('local')->exists($backup->path)) {
            Storage::disk('local')->delete($backup->path);
        }
        $backup->delete();
        ActivityLog::record("Backup deleted: {$backup->filename}", 'system');
        return back()->with('success', 'تم حذف النسخة الاحتياطية.');
    }

    private function backupDatabase(Backup $backup): void
    {
        $config   = config('database.connections.' . config('database.default'));
        $driver   = $config['driver'] ?? 'sqlite';
        $date     = now()->format('Y-m-d_H-i-s');
        $dir      = storage_path('app/backups');

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        if ($driver === 'sqlite') {
            // SQLite: copy the DB file directly
            $dbPath   = $config['database'];
            $filename = "backup_db_{$date}.sqlite";
            $filepath = "{$dir}/{$filename}";

            if (!file_exists($dbPath)) {
                throw new \RuntimeException("SQLite database file not found: {$dbPath}");
            }

            copy($dbPath, $filepath);
        } else {
            // MySQL/MariaDB: dump via PDO
            $db       = $config['database'];
            $filename = "backup_db_{$db}_{$date}.sql";
            $filepath = "{$dir}/{$filename}";
            $pdo      = DB::connection()->getPdo();
            $tables   = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
            $siteName = Setting::get('site_name', config('app.name'));
            $sql      = "-- {$siteName} DB Backup\n-- Generated: " . now() . "\n-- Database: {$db}\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $sql   .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql   .= array_values($create)[1] . ";\n\n";

                $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $cols   = implode('`, `', array_keys($rows[0]));
                    $sql   .= "INSERT INTO `{$table}` (`{$cols}`) VALUES\n";
                    $chunks = array_chunk($rows, 100);
                    foreach ($chunks as $ci => $chunk) {
                        foreach ($chunk as $ri => $row) {
                            $vals = implode(', ', array_map(fn($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), $row));
                            $sql .= "  ({$vals})";
                            $isLast = ($ci === count($chunks) - 1) && ($ri === count($chunk) - 1);
                            $sql .= $isLast ? ";\n" : ",\n";
                        }
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($filepath, $sql);
        }

        $size = filesize($filepath);
        $backup->update([
            'filename' => $filename,
            'path'     => "backups/{$filename}",
            'size'     => $size,
            'status'   => 'completed',
        ]);
    }

    private function backupFiles(Backup $backup): void
    {
        $date     = now()->format('Y-m-d_H-i-s');
        $filename = "backup_files_{$date}.zip";
        $dir      = storage_path('app/backups');

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filepath = "{$dir}/{$filename}";
        $zip      = new \ZipArchive();

        if ($zip->open($filepath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create ZIP file');
        }

        // Add public/storage folder
        $storagePublic = public_path('storage');
        if (is_dir($storagePublic)) {
            $this->addDirToZip($zip, $storagePublic, 'storage');
        }

        $zip->close();

        $size = filesize($filepath);
        $backup->update([
            'filename' => $filename,
            'path'     => "backups/{$filename}",
            'size'     => $size,
            'status'   => 'completed',
        ]);
    }

    private function addDirToZip(\ZipArchive $zip, string $dir, string $prefix): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath  = $file->getRealPath();
                $relativePath = $prefix . '/' . substr($filePath, strlen($dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}
