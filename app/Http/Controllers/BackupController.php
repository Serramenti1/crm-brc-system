<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class BackupController extends Controller
{
    public function index()
    {
        return view('impostazioni.backup');
    }

    public function creaBackupCompleto()
    {
        $nomeFile = 'backup_crm_brc_' . date('Y_m_d_H_i_s') . '.zip';

        $cartellaBackup = storage_path('app/backups');

        if (!file_exists($cartellaBackup)) {
            mkdir($cartellaBackup, 0777, true);
        }

        $percorsoZip = $cartellaBackup . '/' . $nomeFile;
        $percorsoSql = $cartellaBackup . '/backup.sql';

        $database = config('database.connections.mysql.database');
$username = config('database.connections.mysql.username');
$password = config('database.connections.mysql.password');
$host = config('database.connections.mysql.host');

$mysqldump = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe';

if ($password) {
    $comando = '"' . $mysqldump . '" -h ' . $host . ' -u ' . $username . ' -p' . $password . ' ' . $database . ' > "' . $percorsoSql . '"';
} else {
    $comando = '"' . $mysqldump . '" -h ' . $host . ' -u ' . $username . ' ' . $database . ' > "' . $percorsoSql . '"';
}

exec($comando . ' 2>&1', $output, $resultCode);

if ($resultCode !== 0) {
    return back()->with('error', 'Errore backup database: ' . implode("\n", $output));
}

        $zip = new ZipArchive();

        if ($zip->open($percorsoZip, ZipArchive::CREATE) === true) {

            if (file_exists($percorsoSql)) {
                $zip->addFile($percorsoSql, 'backup.sql');
            }

            if (file_exists(base_path('.env'))) {
                $zip->addFile(base_path('.env'), '.env');
            }

            $cartellaStorage = storage_path('app/public');

            if (file_exists($cartellaStorage)) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($cartellaStorage),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $percorsoFile = $file->getRealPath();

                        $percorsoRelativo = 'storage_app_public/' .
                            substr($percorsoFile, strlen($cartellaStorage) + 1);

                        $zip->addFile($percorsoFile, $percorsoRelativo);
                    }
                }
            }

            $zip->close();
        }

        if (file_exists($percorsoSql)) {
            unlink($percorsoSql);
        }

        return response()->download($percorsoZip);
    }
}