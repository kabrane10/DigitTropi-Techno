<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception;

class TestGoogleDriveConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-google-drive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie la connexion à Google Drive et la capacité à uploader/supprimer un fichier.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🚀 Lancement du test de connexion à Google Drive...");

        try {
            $disk = Storage::disk('google');
            $fileName = 'test-connexion-' . now()->format('Y-m-d_H-i-s') . '.txt';
            $content = 'Si vous voyez ce fichier, la connexion à Google Drive fonctionne !';

            // 1. Upload du fichier
            $this->info("   1/3 - Tentative d'upload du fichier : {$fileName}");
            $disk->put($fileName, $content);
            $this->info("   ✅ Fichier envoyé.");

            // 2. Vérification de l'existence
            $this->info("   2/3 - Vérification de l'existence du fichier sur le Drive...");
            if ($disk->exists($fileName)) {
                $this->info("   ✅ Fichier bien présent sur le Drive.");
            } else {
                $this->error("   ❌ Le fichier n'a pas été trouvé après l'upload. Problème potentiel de permissions ou de latence.");
                return Command::FAILURE;
            }

            // 3. Suppression du fichier
            $this->info("   3/3 - Nettoyage : suppression du fichier de test...");
            $disk->delete($fileName);
            $this->info("   ✅ Fichier supprimé.");
            
            $this->info("
🎉 TEST TERMINÉ AVEC SUCCÈS ! La connexion à Google Drive est fonctionnelle.");

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("
❌ Le test a échoué.");
            $this->error("   Message d'erreur : " . $e->getMessage());
            $this->comment("
   💡 Pistes de solution :");
            $this->comment("      - Vérifiez les identifiants GOOGLE_DRIVE_* dans votre fichier .env.");
            $this->comment("      - Assurez-vous que l'API Google Drive est bien activée.");
            $this->comment("      - Confirmez que votre email est bien ajouté comme 'Test User' dans l'écran de consentement OAuth.");
            $this->comment("      - Vérifiez que le GOOGLE_DRIVE_FOLDER_ID est correct et que vous avez les droits d'accès à ce dossier.");
            return Command::FAILURE;
        }
    }
}
