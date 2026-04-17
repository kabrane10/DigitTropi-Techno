<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\Admin;

trait NotifiableTrait
{
    /**
     * Créer une notification
     */
    protected function createNotification($type, $title, $message, $url)
    {
        $admin = Admin::first();
        $adminId = $admin ? $admin->id : 1;
        
        Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'is_read' => false,
            'user_id' => $adminId,
            'created_at' => now()
        ]);
    }
    
    /**
     * Notifier un nouveau producteur
     */
    protected function notifyNewProducteur($producteur)
    {
        $this->createNotification(
            'producteur',
            '✅ Nouveau producteur',
            "{$producteur->nom_complet} ({$producteur->code_producteur}) s'est inscrit",
            route('admin.producteurs.show', $producteur)
        );
    }
    
    /**
     * Notifier une nouvelle collecte
     */
    protected function notifyNewCollecte($collecte)
    {
        $this->createNotification(
            'collecte',
            '📦 Nouvelle collecte',
            "Collecte de {$collecte->quantite_nette} kg de {$collecte->produit} par {$collecte->producteur->nom_complet}",
            route('admin.collectes.show', $collecte)
        );
    }
    
    /**
     * Notifier un nouveau crédit
     */
    protected function notifyNewCredit($credit)
    {
        $this->createNotification(
            'credit',
            '💰 Nouveau crédit',
            "Crédit de " . number_format($credit->montant_total, 0, ',', ' ') . " CFA accordé à {$credit->producteur->nom_complet}",
            route('admin.credits.show', $credit)
        );
    }
    
    /**
     * Notifier un remboursement
     */
    protected function notifyRemboursement($remboursement)
    {
        $credit = $remboursement->credit;
        $this->createNotification(
            'remboursement',
            '💵 Remboursement effectué',
            "Remboursement de " . number_format($remboursement->montant, 0, ',', ' ') . " CFA pour le crédit {$credit->code_credit}",
            route('admin.credits.show', $credit)
        );
    }
    
    /**
     * Notifier un stock critique
     */
    protected function notifyStockCritique($stock)
    {
        $this->createNotification(
            'stock',
            '⚠️ Stock critique',
            "Le stock de {$stock->produit} est bas ({$stock->stock_actuel} {$stock->unite})",
            route('admin.stocks.index', ['alerte' => 1])
        );
    }
}