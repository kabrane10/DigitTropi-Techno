<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdvancedBackup extends Command
{
    protected $signature = 'backup:advanced';
    protected $description = 'Sauvegarde avancée avec destinations multiples';

    public function handle()
    {
        $databasePath = database_path('database.sqlite');
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sqlite';
        $localPath = storage_path('app/backups/' . $filename);
        
        // 1. Copie locale
        copy($databasePath, $localPath);
        $this->info('✅ Sauvegarde locale: ' . $filename);
        
        // 2. Compression
        $zipPath = storage_path('app/backups/' . str_replace('.sqlite', '.zip', $filename));
        $this->createZip($localPath, $zipPath);
        $this->info('✅ Compression effectuée');
        
        // 3. Chiffrement (optionnel)
        $encryptedPath = $this->encryptBackup($zipPath);
        if ($encryptedPath) {
            $this->info('✅ Sauvegarde chiffrée');
        }
        
        // 4. Envoi par email (optionnel)
        $this->sendBackupByEmail($encryptedPath ?? $zipPath);
        
        // 5. Nettoyage des anciennes sauvegardes
        $this->cleanOldBackups();
        
        // 6. Notification
        $this->sendNotification();
        
        return 0;
    }
    
    private function createZip($source, $destination)
    {
        $zip = new \ZipArchive();
        if ($zip->open($destination, \ZipArchive::CREATE) === TRUE) {
            $zip->addFile($source, basename($source));
            $zip->close();
        }
    }
    
    private function encryptBackup($filePath)
    {
        // À implémenter avec OpenSSL
        $encryptedPath = $filePath . '.enc';
        $key = env('BACKUP_ENCRYPTION_KEY', 'default-key-32-chars-long-string!');
        
        $content = file_get_contents($filePath);
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
        
        file_put_contents($encryptedPath, $iv . $encrypted);
        return $encryptedPath;
    }
    
    private function sendBackupByEmail($filePath)
    {
        $adminEmail = env('BACKUP_EMAIL', 'admin@tropitechno.com');
        
        Mail::raw('Sauvegarde automatique de Tropi-Techno', function($message) use ($filePath, $adminEmail) {
            $message->to($adminEmail)
                    ->subject('Sauvegarde Tropi-Techno - ' . date('d/m/Y'))
                    ->attach($filePath);
        });
    }
    
    private function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $keepDays = config('backup.keep_days', 30);
        
        foreach ($files as $file) {
            if (Storage::lastModified($file) < now()->subDays($keepDays)->timestamp) {
                Storage::delete($file);
                $this->info("🗑️ Ancienne sauvegarde supprimée: " . basename($file));
            }
        }
    }
    
    private function sendNotification()
    {
        // Envoyer une notification Slack ou Telegram
        // À implémenter selon les besoins
    }
}