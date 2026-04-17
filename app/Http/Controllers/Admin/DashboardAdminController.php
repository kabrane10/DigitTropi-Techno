<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Galerie;
use App\Models\Animateur;
use App\Models\Controleur;
use App\Models\AgentTerrain;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use Illuminate\Support\Facades\Schema;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Initialiser toutes les stats avec des valeurs par défaut
        $stats = [
            'total_producteurs' => 0,
            'actualites' => 0,
            'galerie' => 0,
            'animateurs' => 0,
            'controleurs' => 0,
            'agents' => 0,
            'producteurs' => 0,
            'credits_actifs' => 0,
            'collecte_mois' => 0,
            'producteurs_actifs' => 0,
            'superficie_totale' => 0,
        ];

        // Remplir les stats si les tables existent
        if (Schema::hasTable('actualites')) {
            $stats['actualites'] = Actualite::count();
        }
        
        if (Schema::hasTable('galeries')) {
            $stats['galerie'] = Galerie::count();
        }
        
        if (Schema::hasTable('animateurs')) {
            $stats['animateurs'] = Animateur::where('statut', 'actif')->count();
        }
        
        if (Schema::hasTable('controleurs')) {
            $stats['controleurs'] = Controleur::where('statut', 'actif')->count();
        }
        
        if (Schema::hasTable('agents_terrain')) {
            $stats['agents'] = AgentTerrain::where('statut', 'actif')->count();
        }
        
        if (Schema::hasTable('producteurs')) {
            $stats['producteurs'] = Producteur::count();
            $stats['total_producteurs'] = Producteur::count();
            $stats['producteurs_actifs'] = Producteur::where('statut', 'actif')->count();
            $stats['superficie_totale'] = Producteur::sum('superficie_totale');
        }
        
        if (Schema::hasTable('credits_agricoles')) {
            $stats['credits_actifs'] = CreditAgricole::where('statut', 'actif')->sum('montant_restant');
        }
        
        if (Schema::hasTable('collectes')) {
            $stats['collecte_mois'] = Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette');
        }

        $actualites_recentes = Schema::hasTable('actualites') ? Actualite::orderBy('created_at', 'desc')->limit(5)->get() : collect();
        $producteurs_recents = Schema::hasTable('producteurs') ? Producteur::orderBy('created_at', 'desc')->limit(5)->get() : collect();

        return view('admin.dashboard', compact('stats', 'actualites_recentes', 'producteurs_recents'));
    }
}