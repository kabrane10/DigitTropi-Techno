<?php

namespace App\Console\Commands;

use App\Models\CreditAgricole;
use Illuminate\Console\Command;

class FixCreditStatus extends Command
{
    protected $signature = 'credits:fix-status';
    protected $description = 'Corrige le statut des crédits entièrement remboursés';

    public function handle()
    {
        $credits = CreditAgricole::where('montant_restant', '<=', 0)
            ->where('statut', 'actif')
            ->get();
        
        foreach ($credits as $credit) {
            $credit->statut = 'rembourse';
            $credit->save();
            $this->info("✅ {$credit->code_credit} -> rembourse");
        }
        
        $this->info("\n📊 Total corrigés : " . $credits->count());
    }
}