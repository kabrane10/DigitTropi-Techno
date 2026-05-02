<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Sauvegarde la base de données';

    public function handle()
    {
        $driver = DB::connection()->getDriverName();
        
        $this->info('📁 Driver détecté: ' . $driver);
        
        if ($driver === 'mysql') {
            return $this->backupMySQL();
        }
        
        if ($driver === 'sqlite') {
            return $this->backupSQLite();
        }
        
        $this->error('❌ Driver non supporté: ' . $driver);
        return 1;
    }
    
    /**
     * Sauvegarde pour MySQL
     */
    protected function backupMySQL()
    {
        $database = Config::get('database.connections.mysql.database');
        $username = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $port = Config::get('database.connections.mysql.port', 3306);
        
        $filename = 'backup-mysql-' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le dossier si nécessaire
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
            $this->info('📁 Dossier de sauvegarde créé');
        }
        
        // Commande mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s 2>&1',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );
        
        $this->info('🔄 Sauvegarde MySQL en cours...');
        $this->info('Commande: ' . $command);
        
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0 && file_exists($backupPath) && filesize($backupPath) > 0) {
            $this->info('✅ Sauvegarde MySQL effectuée: ' . $filename);
            $this->info('📊 Taille: ' . number_format(filesize($backupPath) / 1024, 2) . ' KB');
            $this->cleanOldBackups();
            return 0;
        }
        
        $this->error('❌ Erreur lors de la sauvegarde MySQL');
        $this->error('Output: ' . implode("\n", $output));
        return 1;
    }
    
    /**
     * Sauvegarde pour SQLite
     */
    protected function backupSQLite()
    {
        $databasePath = database_path('database.sqlite');
        
        if (!file_exists($databasePath)) {
            $this->error('❌ Base de données SQLite non trouvée');
            return 1;
        }
        
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        $filename = 'backup-sqlite-' . date('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (copy($databasePath, $backupPath)) {
            $this->info('✅ Sauvegarde SQLite effectuée: ' . $filename);
            $this->cleanOldBackups();
            return 0;
        }
        
        $this->error(' Erreur lors de la sauvegarde SQLite');
        return 1;
    }
    
    /**
     * Nettoyer les anciennes sauvegardes (garder 7 jours)
     */
    protected function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $deleted = 0;
        $now = time();
        
        foreach ($files as $file) {
            $filePath = storage_path('app/backups/' . $file);
            if (file_exists($filePath)) {
                $ageInDays = ($now - filemtime($filePath)) / 86400;
                if ($ageInDays > 7) {
                    unlink($filePath);
                    $deleted++;
                }
            }
        }
        
        if ($deleted > 0) {
            $this->info(" $deleted ancienne(s) sauvegarde(s) supprimée(s)");
        }
    }
}