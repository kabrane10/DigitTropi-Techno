<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        
        return $this->backupSQLite();
    }
    
    /**
     * Sauvegarde pour MySQL
     */
    private function backupMySQL()
    {
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le dossier si nécessaire
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
            $this->info('📁 Dossier de sauvegarde créé');
        }
        
        try {
            // Récupérer toutes les tables
            $tables = DB::select('SHOW TABLES');
            $sql = "-- --------------------------------------------------------\n";
            $sql .= "-- DATABASE BACKUP - Tropi-Techno\n";
            $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- --------------------------------------------------------\n\n";
            
            foreach ($tables as $table) {
                $tableName = reset($table);
                $this->info("📋 Sauvegarde de la table: {$tableName}");
                
                // 1. Structure de la table
                $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                $sql .= "-- Table structure for `{$tableName}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // 2. Données de la table
                $rows = DB::table($tableName)->get();
                if (count($rows) > 0) {
                    $sql .= "-- Data for `{$tableName}`\n";
                    
                    $columns = array_keys((array)$rows[0]);
                    
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($columns as $col) {
                            $value = $row->$col;
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            // Écrire le fichier
            file_put_contents($backupPath, $sql);
            
            $size = filesize($backupPath);
            $this->info('✅ Sauvegarde effectuée: ' . $filename);
            $this->info('📊 Taille: ' . number_format($size / 1024, 2) . ' KB');
            
            // Nettoyer les anciennes sauvegardes
            $this->cleanOldBackups();
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Sauvegarde pour SQLite
     */
    private function backupSQLite()
    {
        $databasePath = database_path('database.sqlite');
        
        if (!file_exists($databasePath)) {
            $this->error('❌ Base de données non trouvée: ' . $databasePath);
            return 1;
        }
        
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le dossier si nécessaire
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
            $this->info('📁 Dossier de sauvegarde créé');
        }
        
        if (copy($databasePath, $backupPath)) {
            $size = filesize($backupPath);
            $this->info('✅ Sauvegarde SQLite effectuée: ' . $filename);
            $this->info('📊 Taille: ' . number_format($size / 1024, 2) . ' KB');
            
            // Nettoyer les anciennes sauvegardes
            $this->cleanOldBackups();
            
            return 0;
        }
        
        $this->error('❌ Erreur lors de la copie de la base de données');
        return 1;
    }
    
    /**
     * Nettoyer les anciennes sauvegardes (garder 7 jours)
     */
    private function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $deleted = 0;
        $keepDays = 7;
        
        foreach ($files as $file) {
            $ageInDays = (time() - Storage::lastModified($file)) / 86400;
            if ($ageInDays > $keepDays) {
                Storage::delete($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("🗑️ $deleted ancienne(s) sauvegarde(s) supprimée(s)");
        }
    }
}