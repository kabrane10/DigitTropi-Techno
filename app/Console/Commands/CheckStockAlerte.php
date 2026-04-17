<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Models\Notification;
use App\Models\Admin;

class CheckStockAlerte extends Command
{
    protected $signature = 'stock:check-alertes';
    protected $description = 'Vérifie les stocks critiques et crée des notifications';

    public function handle()
    {
        $stocks = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();
        $admin = Admin::first();
        $adminId = $admin ? $admin->id : 1;
        $count = 0;
        
        foreach ($stocks as $stock) {
            $existing = Notification::where('type', 'stock')
                ->where('message', 'like', '%' . $stock->produit . '%')
                ->where('created_at', '>=', now()->subHours(24))
                ->first();
            
            if (!$existing) {
                Notification::create([
                    'type' => 'stock',
                    'title' => '⚠️ Stock critique',
                    'message' => "Le stock de {$stock->produit} est bas ({$stock->stock_actuel} {$stock->unite})",
                    'url' => route('admin.stocks.index', ['alerte' => 1]),
                    'is_read' => false,
                    'user_id' => $adminId,
                    'created_at' => now()
                ]);
                $count++;
            }
        }
        
        $this->info("✅ $count notifications de stock critique créées");
    }
}