<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bordereau;
use App\Models\Collecte;
use App\Models\Achat;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BordereauController extends Controller
{
    /**
     * Liste des bordereaux
     */
    public function index(Request $request)
    {
        $query = Bordereau::orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $bordereaux = $query->paginate(15);
        return view('admin.bordereaux.index', compact('bordereaux'));
    }

    /**
     * Formulaire de chargement
     */
    public function formChargement()
    {
        return view('admin.bordereaux.form-chargement');
    }

    /**
     * Formulaire de livraison
     */
    public function formLivraison()
    {
        return view('admin.bordereaux.form-livraison');
    }

    /**
     * Formulaire d'achat direct
     */
    public function formAchat()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        return view('admin.bordereaux.form-achat', compact('producteurs'));
    }

    public function formCollecte()
{
    $producteurs = Producteur::where('statut', 'actif')->get();
    return view('admin.bordereaux.form-collecte', compact('producteurs'));
}

public function genererCollecteDirect(Request $request)
{
    $collecte = Collecte::with('producteur')->findOrFail($collecteId);
        
    $contenu = [
        'numero_collecte' => $collecte->code_collecte,
        'date_collecte' => $collecte->date_collecte->format('d/m/Y'),
        'producteur' => [
            'nom' => $collecte->producteur->nom_complet,
            'code' => $collecte->producteur->code_producteur,
            'contact' => $collecte->producteur->contact,
            'localisation' => $collecte->producteur->localisation
        ],
        'produits' => [
            [
                'nom' => $collecte->produit,
                'quantite_brute' => $collecte->quantite_brute,
                'quantite_nette' => $collecte->quantite_nette,
                'prix_unitaire' => $collecte->prix_unitaire,
                'montant' => $collecte->montant_total
            ]
        ],
        'total_quantite' => $collecte->quantite_nette,
        'montant_total' => $collecte->montant_total,
        'montant_deduict' => $collecte->montant_deduict,
        'montant_a_payer' => $collecte->montant_a_payer,
        'zone_collecte' => $collecte->zone_collecte,
        'observations' => $collecte->observations
    ];

    $bordereau = Bordereau::create([
        'code_bordereau' => 'BRD-CLT-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
        'type' => 'collecte',
        'reference_id' => $collecte->id,
        'reference_type' => Collecte::class,
        'date_emission' => now(),
        'contenu' => $contenu,
        'emetteur' => Auth::guard('admin')->user()->nom ?? 'Administrateur',
        'destinataire' => $collecte->producteur->nom_complet,
        'statut' => 'valide'
    ]);

    return redirect()->route('admin.bordereaux.show', $bordereau)
        ->with('success', 'Bordereau de collecte généré avec succès');
    
}

/**
 * Formulaire de contre-passée
 */
public function formContrePassee()
{
    // Récupérer les bordereaux récents pour annulation
    $bordereaux = Bordereau::whereIn('type', ['collecte', 'achat', 'chargement', 'livraison'])
        ->where('statut', 'valide')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get();
    
    return view('admin.bordereaux.form-contre-passee', compact('bordereaux'));
}

/**
 * Générer un bordereau de contre-passée
 */
public function genererContrePassee(Request $request)
{
    $validated = $request->validate([
        'bordereau_id' => 'required|exists:bordereaux,id',
        'motif' => 'required|string|min:10',
        'observations' => 'nullable|string'
    ]);
    
    $bordereauOriginal = Bordereau::findOrFail($validated['bordereau_id']);
    
    // Vérifier que le bordereau n'a pas déjà été contre-passé
    $existing = Bordereau::where('reference_id', $bordereauOriginal->id)
        ->where('type', 'contre_passee')
        ->first();
    
    if ($existing) {
        return back()->with('error', 'Ce bordereau a déjà été contre-passé.');
    }
    
    // Créer le contenu du bordereau de contre-passée
    $contenu = [
        'bordereau_original' => [
            'code' => $bordereauOriginal->code_bordereau,
            'type' => $bordereauOriginal->type,
            'date_emission' => $bordereauOriginal->date_emission->format('d/m/Y H:i'),
            'emetteur' => $bordereauOriginal->emetteur,
            'destinataire' => $bordereauOriginal->destinataire,
            'contenu' => $bordereauOriginal->contenu
        ],
        'motif' => $validated['motif'],
        'observations' => $validated['observations'] ?? null,
        'date_annulation' => now()->format('d/m/Y H:i')
    ];
    
    // Créer le bordereau de contre-passée
    $bordereau = Bordereau::create([
        'code_bordereau' => 'BRD-ANN-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
        'type' => 'contre_passee',
        'reference_id' => $bordereauOriginal->id,
        'reference_type' => Bordereau::class,
        'date_emission' => now(),
        'contenu' => $contenu,
        'emetteur' => auth()->guard('admin')->user()->nom ?? 'Administrateur',
        'destinataire' => $bordereauOriginal->destinataire,
        'statut' => 'valide'
    ]);
    
    // Annuler le bordereau original
    $bordereauOriginal->update(['statut' => 'annule']);
    
    // Si c'est une collecte, restaurer le stock
    if ($bordereauOriginal->type == 'collecte' && isset($bordereauOriginal->contenu['produits'])) {
        foreach ($bordereauOriginal->contenu['produits'] as $produit) {
            $stock = Stock::where('produit', $produit['nom'])
                ->where('zone', $bordereauOriginal->contenu['zone_collecte'] ?? 'Centrale')
                ->first();
            if ($stock) {
                $stock->stock_actuel -= $produit['quantite_nette'];
                $stock->quantite_sortie += $produit['quantite_nette'];
                $stock->save();
            }
        }
    }
    
    return redirect()->route('admin.bordereaux.show', $bordereau)
        ->with('success', 'Bordereau de contre-passée généré avec succès. Le bordereau original a été annulé.');
}
    /**
     * Générer un bordereau de collecte
     */
    public function genererCollecte($collecteId)
    {
        $collecte = Collecte::with('producteur')->findOrFail($collecteId);
        
        $contenu = [
            'numero_collecte' => $collecte->code_collecte,
            'date_collecte' => $collecte->date_collecte->format('d/m/Y'),
            'producteur' => [
                'nom' => $collecte->producteur->nom_complet,
                'code' => $collecte->producteur->code_producteur,
                'contact' => $collecte->producteur->contact,
                'localisation' => $collecte->producteur->localisation
            ],
            'produits' => [
                [
                    'nom' => $collecte->produit,
                    'quantite_brute' => $collecte->quantite_brute,
                    'quantite_nette' => $collecte->quantite_nette,
                    'prix_unitaire' => $collecte->prix_unitaire,
                    'montant' => $collecte->montant_total
                ]
            ],
            'total_quantite' => $collecte->quantite_nette,
            'montant_total' => $collecte->montant_total,
            'montant_deduict' => $collecte->montant_deduict,
            'montant_a_payer' => $collecte->montant_a_payer,
            'zone_collecte' => $collecte->zone_collecte,
            'observations' => $collecte->observations
        ];

        $bordereau = Bordereau::create([
            'code_bordereau' => 'BRD-CLT-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
            'type' => 'collecte',
            'reference_id' => $collecte->id,
            'reference_type' => Collecte::class,
            'date_emission' => now(),
            'contenu' => $contenu,
            'emetteur' => Auth::guard('admin')->user()->nom ?? 'Administrateur',
            'destinataire' => $collecte->producteur->nom_complet,
            'statut' => 'valide'
        ]);

        return redirect()->route('admin.bordereaux.show', $bordereau)
            ->with('success', 'Bordereau de collecte généré avec succès');
    }

    /**
     * Générer un bordereau d'achat (depuis un achat existant)
     */
    public function genererAchat($achatId)
    {
        $achat = Achat::with('collecte.producteur')->findOrFail($achatId);
        
        $contenu = [
            'numero_achat' => $achat->code_achat,
            'date_achat' => $achat->date_achat->format('d/m/Y'),
            'acheteur' => $achat->acheteur,
            'mode_paiement' => $achat->mode_paiement,
            'reference_facture' => $achat->reference_facture,
            'statut' => $achat->statut,
            'fournisseur' => [
                'nom' => $achat->collecte->producteur->nom_complet,
                'code' => $achat->collecte->producteur->code_producteur
            ],
            'produits' => [
                [
                    'nom' => $achat->collecte->produit,
                    'quantite' => $achat->quantite,
                    'prix_unitaire' => $achat->prix_achat,
                    'montant' => $achat->montant_total
                ]
            ],
            'montant_total' => $achat->montant_total,
            'observations' => $achat->notes
        ];

        $bordereau = Bordereau::create([
            'code_bordereau' => 'BRD-ACH-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
            'type' => 'achat',
            'reference_id' => $achat->id,
            'reference_type' => Achat::class,
            'date_emission' => now(),
            'contenu' => $contenu,
            'emetteur' => Auth::guard('admin')->user()->nom ?? 'Administrateur',
            'destinataire' => $achat->collecte->producteur->nom_complet,
            'statut' => 'valide'
        ]);

        return redirect()->route('admin.bordereaux.show', $bordereau)
            ->with('success', 'Bordereau d\'achat généré avec succès');
    }

    /**
 * Générer un bordereau de vente (Tropi-Techno vend au producteur)
 */
public function genererAchatDirect(Request $request)
{
    $validated = $request->validate([
        'producteur_id' => 'required|exists:producteurs,id',
        'date_achat' => 'required|date',
        'produit' => 'required|string',
        'quantite' => 'required|numeric|min:0.01',
        'prix_unitaire' => 'required|numeric|min:0',
        'mode_paiement' => 'required|in:especes,virement,cheque,mobile_money',
        'reference_facture' => 'nullable|string',
        'observations' => 'nullable|string'
    ]);
    
    $producteur = Producteur::find($validated['producteur_id']);
    $montant_total = $validated['quantite'] * $validated['prix_unitaire'];
    
    $contenu = [
        'numero_vente' => 'FAC-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
        'date_vente' => $validated['date_achat'],
        'vendeur' => [
            'nom' => 'Tropi-Techno Sarl',
            'adresse' => 'RN:17, Bamabodolo, Sokodé-Togo',
            'tel' => '+228 25 50 63 12',
            'email' => 'tropitechno@admin.com'
        ],
        'acheteur' => [
            'nom' => $producteur->nom_complet,
            'code' => $producteur->code_producteur,
            'contact' => $producteur->contact,
            'localisation' => $producteur->localisation
        ],
        'produits' => [
            [
                'nom' => $validated['produit'],
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'montant' => $montant_total
            ]
        ],
        'montant_total' => $montant_total,
        'mode_paiement' => $validated['mode_paiement'],
        'reference_facture' => $validated['reference_facture'],
        'observations' => $validated['observations']
    ];

    $bordereau = Bordereau::create([
        'code_bordereau' => 'BRD-VENTE-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
        'type' => 'achat',
        'reference_id' => 0,
        'reference_type' => 'Manuel',
        'date_emission' => now(),
        'contenu' => $contenu,
        'emetteur' => Auth::guard('admin')->user()->nom ?? 'Tropi-Techno',
        'destinataire' => $producteur->nom_complet,
        'statut' => 'valide'
    ]);

    return redirect()->route('admin.bordereaux.show', $bordereau)
        ->with('success', 'Facture de vente générée avec succès');
}
    /**
     * Générer un bordereau de chargement
     */
    public function genererChargement(Request $request)
    {
        $validated = $request->validate([
            'produit' => 'required|string',
            'quantite' => 'required|numeric|min:1',
            'destination' => 'required|string',
            'date_depart' => 'required|date',
            'transporteur' => 'required|string',
            'immatriculation' => 'required|string',
            'conducteur' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $contenu = [
            'produit' => $validated['produit'],
            'quantite' => $validated['quantite'],
            'destination' => $validated['destination'],
            'date_depart' => $validated['date_depart'],
            'transporteur' => $validated['transporteur'],
            'immatriculation' => $validated['immatriculation'],
            'conducteur' => $validated['conducteur'],
            'observations' => $validated['observations'] ?? null
        ];

        $bordereau = Bordereau::create([
            'code_bordereau' => 'BRD-CHG-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
            'type' => 'chargement',
            'reference_id' => 0,
            'reference_type' => 'Manuel',
            'date_emission' => now(),
            'contenu' => $contenu,
            'emetteur' => Auth::guard('admin')->user()->nom ?? 'Administrateur',
            'destinataire' => $validated['destination'],
            'statut' => 'valide'
        ]);

        return redirect()->route('admin.bordereaux.show', $bordereau)
            ->with('success', 'Bordereau de chargement généré avec succès');
    }

    /**
     * Générer un bordereau de livraison
     */
    public function genererLivraison(Request $request)
    {
        $validated = $request->validate([
            'destinataire' => 'required|string',
            'adresse_livraison' => 'required|string',
            'date_livraison_prevue' => 'required|date',
            'transporteur' => 'required|string',
            'produits' => 'required|array|min:1',
            'produits.*.nom' => 'required|string',
            'produits.*.quantite' => 'required|numeric|min:0.01',
            'produits.*.unite' => 'nullable|string',
            'observations' => 'nullable|string'
        ]);

        $contenu = [
            'destinataire' => $validated['destinataire'],
            'adresse_livraison' => $validated['adresse_livraison'],
            'date_livraison_prevue' => $validated['date_livraison_prevue'],
            'transporteur' => $validated['transporteur'],
            'produits' => $validated['produits'],
            'observations' => $validated['observations'] ?? null
        ];

        $bordereau = Bordereau::create([
            'code_bordereau' => 'BRD-LIV-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
            'type' => 'livraison',
            'reference_id' => 0,
            'reference_type' => 'Manuel',
            'date_emission' => now(),
            'contenu' => $contenu,
            'emetteur' => Auth::guard('admin')->user()->nom ?? 'Administrateur',
            'destinataire' => $validated['destinataire'],
            'statut' => 'valide'
        ]);

        return redirect()->route('admin.bordereaux.show', $bordereau)
            ->with('success', 'Bordereau de livraison généré avec succès');
    }

    /**
     * Afficher un bordereau
     */
    public function show($id)
    {
        $bordereau = Bordereau::findOrFail($id);
        return view('admin.bordereaux.show', compact('bordereau'));
    }

    /**
     * Imprimer un bordereau
     */
    public function print($id)
    {
        $bordereau = Bordereau::findOrFail($id);
        return view('admin.bordereaux.print', compact('bordereau'));
    }

    /**
     * Annuler un bordereau
     */
    public function annuler($id)
    {
        $bordereau = Bordereau::findOrFail($id);
        $bordereau->update(['statut' => 'annule']);
        
        return back()->with('success', 'Bordereau annulé avec succès');
    }
}