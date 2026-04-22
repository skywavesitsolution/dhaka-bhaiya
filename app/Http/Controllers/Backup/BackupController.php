<?php

namespace App\Http\Controllers\Backup;

use ZipArchive;
use Illuminate\Http\Request;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('adminPanel.backup.index');
    }

    public function download()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $backupFile = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $zipFile = 'backup-' . now()->format('Y-m-d_H-i-s') . '.zip';

        try {
            $dump = new Mysqldump("mysql:host=$host;dbname=$database", $username, $password);
            $dump->start(storage_path('app/' . $backupFile));

            $zip = new ZipArchive();
            if ($zip->open(storage_path('app/' . $zipFile), ZipArchive::CREATE) !== true) {
                throw new \Exception('Failed to create ZIP file.');
            }
            $zip->addFile(storage_path('app/' . $backupFile), $backupFile);
            $zip->close();

            return response()->download(storage_path('app/' . $zipFile), $zipFile, [
                'Content-Type' => 'application/octet-stream',
                'X-Backup-File' => $backupFile,
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            if (file_exists(storage_path('app/' . $backupFile))) {
                Storage::disk('local')->delete($backupFile);
            }
            if (file_exists(storage_path('app/' . $zipFile))) {
                Storage::disk('local')->delete($zipFile);
            }
            Log::error('Backup download failed: ' . $e->getMessage());
            return response()->json(['error' => 'Backup failed: ' . $e->getMessage()], 500);
        }
    }

    public function pushOnline(Request $request)
    {
        $backupFile = null;
        try {
            $backupFile = $request->input('fileName');
            if (!$backupFile || !Storage::disk('local')->exists($backupFile)) {
                Log::error('Backup file not found: ' . $backupFile);
                return response()->json(['error' => 'Backup file not found. Please try downloading again.'], 400);
            }

            try {
                config([
                    'database.connections.online' => [
                        'driver' => env('ONLINE_DB_CONNECTION', 'mysql'),
                        'host' => env('ONLINE_DB_HOST'),
                        'port' => env('ONLINE_DB_PORT', '3306'),
                        'database' => env('ONLINE_DB_DATABASE'),
                        'username' => env('ONLINE_DB_USERNAME'),
                        'password' => env('ONLINE_DB_PASSWORD'),
                    ],
                ]);
                DB::connection('online')->getPdo();
            } catch (\PDOException $e) {
                Log::error('Online database connection failed: ' . $e->getMessage());
                return response()->json(['error' => str_contains($e->getMessage(), 'Access denied')
                    ? 'Invalid online database credentials.'
                    : 'Network issue: Could not connect to online database.'], 500);
            }

            try {
                DB::connection('online')->statement('SET FOREIGN_KEY_CHECKS=0;');
                $tables = DB::connection('online')->select('SHOW TABLES');
                foreach ($tables as $table) {
                    $tableName = reset($table);
                    DB::connection('online')->statement("DROP TABLE IF EXISTS `$tableName`");
                }
                DB::connection('online')->statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $e) {
                Log::error('Failed to drop online database tables: ' . $e->getMessage());
                return response()->json(['error' => 'Could not clear online database.'], 500);
            }

            try {
                $sqlContent = file_get_contents(storage_path('app/' . $backupFile));
                if ($sqlContent === false) {
                    throw new \Exception('Failed to read backup file.');
                }
                DB::connection('online')->unprepared($sqlContent);
            } catch (\Exception $e) {
                Log::error('Failed to import backup: ' . $e->getMessage());
                return response()->json(['error' => 'Could not import backup.'], 500);
            }

            Storage::disk('local')->delete($backupFile);

            return response()->json(['message' => 'Database pushed to online successfully']);
        } catch (\Exception $e) {
            if (isset($backupFile)) {
                Storage::disk('local')->delete($backupFile);
            }
            Log::error('Unexpected error during push online: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('sql_file')) {
                $file = $request->file('sql_file');
                Log::info('Uploaded file details:', [
                    'originalName' => $file->getClientOriginalName(),
                    'mimeType' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                ]);
            } else {
                Log::error('No sql_file uploaded');
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            Log::info('File validated successfully: ' . $request->file('sql_file')->getClientOriginalName());

            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');

            // Store the uploaded .sql file
            $sqlFile = $request->file('sql_file')->store('temp', 'local');
            $sqlPath = storage_path('app/' . $sqlFile);
            Log::info('File stored at: ' . $sqlPath);

            $sqlContent = file_get_contents($sqlPath);
            if (empty($sqlContent) || !str_contains($sqlContent, 'CREATE TABLE') || !str_contains($sqlContent, 'INSERT INTO')) {
                throw new \Exception('Invalid SQL file: Missing CREATE TABLE or INSERT statements.');
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableName = reset($table);
                Log::info('Dropping table: ' . $tableName);
                DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Log::info('SQL file size: ' . strlen($sqlContent) . ' bytes');
            DB::unprepared($sqlContent);

            Storage::disk('local')->delete($sqlFile);
            Log::info('Temporary file deleted: ' . $sqlFile);

            return response()->json(['message' => 'Database restored successfully']);
        } catch (\Exception $e) {
            if (isset($sqlFile)) {
                Storage::disk('local')->delete($sqlFile);
            }
            Log::error('Restore failed: ' . $e->getMessage());
            return response()->json(['error' => 'Restore failed: ' . $e->getMessage()], 500);
        }
    }

    public function pushOnlineSeperate()
    {
        $backupFile = null;
        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $backupFile = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';

            try {
                $dump = new Mysqldump("mysql:host=$host;dbname=$database", $username, $password);
                $dump->start(storage_path('app/' . $backupFile));
            } catch (\Exception $e) {
                Log::error('Failed to generate local backup: ' . $e->getMessage());
                return response()->json(['error' => 'Could not generate backup. Please check local database credentials or disk space.'], 500);
            }

            if (!Storage::disk('local')->exists($backupFile)) {
                Log::error('Backup file not found: ' . $backupFile);
                return response()->json(['error' => 'Backup file creation failed. Please try again.'], 500);
            }

            try {
                config([
                    'database.connections.online' => [
                        'driver' => env('ONLINE_DB_CONNECTION', 'mysql'),
                        'host' => env('ONLINE_DB_HOST'),
                        'port' => env('ONLINE_DB_PORT', '3306'),
                        'database' => env('ONLINE_DB_DATABASE'),
                        'username' => env('ONLINE_DB_USERNAME'),
                        'password' => env('ONLINE_DB_PASSWORD'),
                    ],
                ]);

                DB::connection('online')->getPdo();
            } catch (\PDOException $e) {
                Log::error('Online database connection failed: ' . $e->getMessage());
                Storage::disk('local')->delete($backupFile);
                if (str_contains($e->getMessage(), 'Access denied')) {
                    return response()->json(['error' => 'Invalid online database credentials. Please check ONLINE_DB_USERNAME and ONLINE_DB_PASSWORD.'], 500);
                } elseif (str_contains($e->getMessage(), 'Connection refused') || str_contains($e->getMessage(), 'No route to host')) {
                    return response()->json(['error' => 'Network issue: Could not connect to online database. Check ONLINE_DB_HOST or your internet connection.'], 500);
                }
                return response()->json(['error' => 'Failed to connect to online database: ' . $e->getMessage()], 500);
            }

            try {
                DB::connection('online')->statement('SET FOREIGN_KEY_CHECKS=0;');
                $tables = DB::connection('online')->select('SHOW TABLES');
                foreach ($tables as $table) {
                    $tableName = reset($table);
                    DB::connection('online')->statement("DROP TABLE IF EXISTS `$tableName`");
                }
                DB::connection('online')->statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $e) {
                Log::error('Failed to drop online database tables: ' . $e->getMessage());
                Storage::disk('local')->delete($backupFile);
                return response()->json(['error' => 'Could not clear online database. Check database permissions or table locks.'], 500);
            }

            try {
                $sqlContent = file_get_contents(storage_path('app/' . $backupFile));
                if ($sqlContent === false) {
                    throw new \Exception('Failed to read backup file.');
                }
                DB::connection('online')->unprepared($sqlContent);
            } catch (\Exception $e) {
                Log::error('Failed to import backup to online database: ' . $e->getMessage());
                Storage::disk('local')->delete($backupFile);
                return response()->json(['error' => 'Could not import backup. The .sql file may be corrupt or incompatible.'], 500);
            }

            Storage::disk('local')->delete($backupFile);

            return response()->json(['message' => 'Database pushed to online successfully']);
        } catch (\Exception $e) {
            if (isset($backupFile)) {
                Storage::disk('local')->delete($backupFile);
            }
            Log::error('Unexpected error during push online: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}
