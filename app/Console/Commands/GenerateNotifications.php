<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Stock;
use App\Models\CreditAgricole;
use App\Models\Producteur;
use App\Models\Collecte;
use App\Models\Remboursement;

class GenerateNotifications extends Command
{
    protected $signature = 'notifications:generate';
    protected $description = 'Générer les notifications automatiquement';

    public function handle()
    {
        $this->info('Génération des notifications...');
        $count = 0;
        
        // 1. Alertes stocks critiques
        $stocksAlerte = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();
        foreach ($stocksAlerte as $stock) {
            $existing = Notification::where('type', 'stock')
                ->where('message', 'like', '%' . $stock->produit . '%')
                ->where('created_at', '>=', now()->subDays(7))
                ->first();
            
            if (!$existing) {
                Notification::create([
                    'type' => 'stock',
                    'title' => '⚠️ Stock critique',
                    'message' => "Le stock de {$stock->produit} est bas ({$stock->stock_actuel} {$stock->unite})",
                    'url' => route('admin.stocks.index', ['alerte' => 1]),
                    'is_read' => false,
                    'user_id' => $adminId,
                    'created_at' => now(),
                ]);
                $count++;
                $this->info("Notification stock: {$stock->produit}");
            }
        }
        
        // 2. Crédits en retard
        $creditsRetard = CreditAgricole::where('statut', 'actif')
            ->where('date_echeance', '<', now())
            ->get();
        foreach ($creditsRetard as $credit) {
            $existing = Notification::where('type', 'credit')
                ->where('message', 'like', '%' . $credit->code_credit . '%')
                ->where('created_at', '>=', now()->subDays(7))
                ->first();
            
            if (!$existing) {
                $joursRetard = now()->diffInDays($credit->date_echeance);
                Notification::create([
                    'type' => 'credit',
                    'title' => '⚠️ Crédit en retard',
                    'message' => "Crédit {$credit->code_credit} - Retard de {$joursRetard} jours",
                    'url' => route('admin.credits.show', $credit),
                    'is_read' => false,
                    'user_id' => $adminId,
                    'created_at' => now(),
                ]);
                $count++;
                $this->info("Notification crédit: {$credit->code_credit}");
            }
        }
        
        // 3. Nouveaux producteurs (7 derniers jours)
        $nouveauxProducteurs = Producteur::where('created_at', '>=', now()->subDays(7))->get();
        foreach ($nouveauxProducteurs as $producteur) {
            $existing = Notification::where('type', 'producteur')
                ->where('message', 'like', '%' . $producteur->nom_complet . '%')
                ->where('created_at', '>=', now()->subDays(7))
                ->first();
            
            if (!$existing) {
                Notification::create([
                    'type' => 'producteur',
                    'title' => 'Nouveau producteur',
                    'message' => "{$producteur->nom_complet} ({$producteur->code_producteur}) s'est inscrit",
                    'url' => route('admin.producteurs.show', $producteur),
                    'is_read' => false,
                    'user_id' => $adminId,
                    'created_at' => now(),
                ]);
                $count++;
                $this->info("Notification producteur: {$producteur->nom_complet}");
            }
        }
        
        // 4. Nouvelles collectes (7 derniers jours)
        $nouvellesCollectes = Collecte::where('created_at', '>=', now()->subDays(7))->get();
        foreach ($nouvellesCollectes as $collecte) {
            $existing = Notification::where('type', 'collecte')
                ->where('message', 'like', '%' . $collecte->code_collecte . '%')
                ->where('created_at', '>=', now()->subDays(7))
                ->first();
            
            if (!$existing) {
                Notification::create([
                    'type' => 'collecte',
                    'title' => 'Nouvelle collecte',
                    'message' => "Collecte de {$collecte->quantite_nette} kg de {$collecte->produit}",
                    'url' => route('admin.collectes.show', $collecte),
                    'is_read' => false,
                    'user_id' => $adminId,
                    'created_at' => now(),
                ]);
                $count++;
                $this->info("Notification collecte: {$collecte->code_collecte}");
            }
        }
        
        // 5. Nouveaux remboursements (7 derniers jours)
        $nouveauxRemboursements = Remboursement::where('created_at', '>=', now()->subDays(7))->get();
        foreach ($nouveauxRemboursements as $remboursement) {
            $credit = $remboursement->credit;
            if ($credit) {
                $existing = Notification::where('type', 'remboursement')
                    ->where('message', 'like', '%' . $credit->code_credit . '%')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->first();
                
                if (!$existing) {
                    Notification::create([
                        'type' => 'remboursement',
                        'title' => 'Remboursement effectué',
                        'message' => "Remboursement de " . number_format($remboursement->montant, 0, ',', ' ') . " CFA pour le crédit {$credit->code_credit}",
                        'url' => route('admin.credits.show', $credit),
                        'is_read' => false,
                        'user_id' => $adminId,
                        'created_at' => now(),
                    ]);
                    $count++;
                    $this->info("Notification remboursement: {$credit->code_credit}");
                }
            }
        }
        
        $this->info("$count notifications générées avec succès !");
    }
}