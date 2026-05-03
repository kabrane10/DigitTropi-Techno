<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Animateur\AuthController as AnimateurAuthController;
use App\Http\Controllers\Animateur\DashboardController as AnimateurDashboardController;
use App\Http\Controllers\Animateur\AgentController as AnimateurAgentController;
use App\Http\Controllers\Animateur\ProducteurController as AnimateurProducteurController;
use App\Http\Controllers\Animateur\SuiviController as AnimateurSuiviController;
use App\Http\Controllers\Animateur\RapportController as AnimateurRapportController;
use App\Http\Controllers\Agent\AuthController as AgentAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ProducteurController as AgentProducteurController;
use App\Http\Controllers\Agent\CollecteController as AgentCollecteController;
use App\Http\Controllers\Agent\SuiviController as AgentSuiviController;
use App\Http\Controllers\Controleur\AuthController as ControleurAuthController;
use App\Http\Controllers\Controleur\DashboardController as ControleurDashboardController;
use App\Http\Controllers\Controleur\ProducteurController as ControleurProducteurController;
use App\Http\Controllers\Controleur\CreditController as ControleurCreditController;
use App\Http\Controllers\Controleur\CollecteController as ControleurCollecteController;
use App\Http\Controllers\Controleur\RapportController as ControleurRapportController;
use App\Http\Controllers\Controleur\StockController as ControleurStockController;
use App\Http\Controllers\Controleur\SuiviController as ControleurSuiviController;
use App\Http\Controllers\Admin\BordereauController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\SuiviController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\GalerieController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\ActualiteAdminController;
use App\Http\Controllers\Admin\GalerieAdminController;
use App\Http\Controllers\Admin\AlbumAdminController;
use App\Http\Controllers\Admin\ProducteurController;
use App\Http\Controllers\Admin\CooperativeController;
use App\Http\Controllers\Admin\DistributionSemenceController;
use App\Http\Controllers\Admin\SemenceController;
use App\Http\Controllers\Admin\CollecteController;
use App\Http\Controllers\Admin\AchatController;
use App\Http\Controllers\Admin\SuiviParcellaireController;
use App\Http\Controllers\Admin\AnimateurController;
use App\Http\Controllers\Admin\ControleurController;
use App\Http\Controllers\Admin\AgentTerrainController;
use App\Http\Controllers\Admin\CreditController;

// ========== ROUTES PUBLIQUES ==========
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/galerie', [GalerieController::class, 'index'])->name('galerie');
Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('albums.show');
Route::get('/galerie/categorie/{categorie}', [GalerieController::class, 'filter'])->name('galerie.filter');

Route::get('/actualites', [ActualiteController::class, 'index'])->name('actualites');
Route::get('/actualite/{slug}', [ActualiteController::class, 'show'])->name('actualite.show');

// Page de choix de rôle
Route::get('/administration', function() {
    return view('auth.role-selection');
})->name('role.selection');


// ========== ROUTES API POUR LE CHARGEMENT AJAX ==========
Route::get('/actualites/api/data', [ActualiteController::class, 'apiData'])->name('actualites.api');
Route::get('/galerie/api/photos', [GalerieController::class, 'getPhotos'])->name('galerie.api.photos');
Route::get('/galerie/api/data', [GalerieController::class, 'apiData'])->name('galerie.api');

// API pour la galerie publique
Route::get('/galerie/api/albums', [GalerieController::class, 'getAlbums']);
Route::get('/galerie/api/albums/{id}', [GalerieController::class, 'getAlbumImages']);

// --- ROUTES PUBLIQUES (Accessibles à tous)

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Authentification
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    //================================================================//
    //                    Routes protégées                            //
    //================================================================//
    Route::middleware('auth:admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
        
        // Producteurs
        Route::resource('producteurs', ProducteurController::class);
        Route::get('/producteurs-export', [ProducteurController::class, 'export'])->name('producteurs.export');
        
        // Agents terrain
        Route::resource('agents', AgentTerrainController::class);
        Route::post('/agents/{id}/reset-password', [AgentTerrainController::class, 'resetPassword'])->name('agents.reset-password');
        Route::post('/agents/{id}/toggle-status', [AgentTerrainController::class, 'toggleStatus'])->name('agents.toggle-status');
        Route::get('/agents-export', [AgentTerrainController::class, 'export'])->name('agents.export');
        
        // Actualités
        Route::resource('actualites', ActualiteAdminController::class);
        
        // Galerie
        Route::resource('galerie', GalerieAdminController::class);
        Route::post('/galerie/reorder', [GalerieAdminController::class, 'reorder'])->name('galerie.reorder');
        
        // Admin - Albums
        Route::resource('albums', AlbumAdminController::class);
        Route::post('/albums/{album}/add-images', [AlbumAdminController::class, 'addImages'])->name('albums.add-images');

        // Animateurs
        Route::resource('animateurs', AnimateurController::class);
        Route::post('/animateurs/{id}/reset-password', [AnimateurController::class, 'resetPassword'])->name('animateurs.reset-password');
        Route::post('/animateurs/{id}/toggle-status', [AnimateurController::class, 'toggleStatus'])->name('animateurs.toggle-status');
        Route::get('/animateurs-export', [AnimateurController::class, 'export'])->name('animateurs.export');
        
        // Contrôleurs
        Route::resource('controleurs', ControleurController::class);
        Route::post('/controleurs/{id}/reset-password', [ControleurController::class, 'resetPassword'])->name('controleurs.reset-password');
        Route::post('/controleurs/{id}/toggle-status', [ControleurController::class, 'toggleStatus'])->name('controleurs.toggle-status');
        Route::get('/controleurs-export', [ControleurController::class, 'export'])->name('controleurs.export');
        
        // Coopératives
        Route::resource('cooperatives', CooperativeController::class);
        Route::get('/cooperatives-export', [CooperativeController::class, 'export'])->name('cooperatives.export');
        
        // Distributions
        Route::resource('distributions', DistributionSemenceController::class);
        Route::get('/distributions-dashboard', [DistributionSemenceController::class, 'dashboard'])->name('distributions.dashboard');

        // Semences
        Route::resource('semences', SemenceController::class);
        Route::post('/semences/{id}/ajouter-stock', [SemenceController::class, 'ajouterStock'])->name('semences.ajouter-stock');
        
        // Collectes
        Route::get('/collectes', [CollecteController::class, 'index'])->name('collectes.index');
        Route::get('/collectes/dashboard', [CollecteController::class, 'dashboard'])->name('collectes.dashboard'); 
        Route::get('/collectes/create', [CollecteController::class, 'create'])->name('collectes.create');
        Route::post('/collectes', [CollecteController::class, 'store'])->name('collectes.store');
        Route::get('/collectes/{id}', [CollecteController::class, 'show'])->name('collectes.show');
        Route::get('/collectes/{id}/edit', [CollecteController::class, 'edit'])->name('collectes.edit');
        Route::put('/collectes/{id}', [CollecteController::class, 'update'])->name('collectes.update');
        Route::delete('/collectes/{id}', [CollecteController::class, 'destroy'])->name('collectes.destroy');
        Route::get('/collectes/export', [CollecteController::class, 'export'])->name('collectes.export');
        

        // Achats
        Route::resource('achats', AchatController::class);
        Route::get('/achats-dashboard', [AchatController::class, 'dashboard'])->name('achats.dashboard');
        Route::post('/achats/{id}/valider', [AchatController::class, 'valider'])->name('achats.valider');
        Route::get('/achats-export', [AchatController::class, 'export'])->name('achats.export');
        
        // Crédits
        Route::resource('credits', CreditController::class);
        Route::post('/credits/{id}/remboursement', [CreditController::class, 'remboursement'])->name('credits.remboursement');
        Route::post('/credits/{id}/statut', [CreditController::class, 'updateStatut'])->name('credits.update-statut');
        Route::get('/credits-dashboard', [CreditController::class, 'dashboard'])->name('credits.dashboard');
        Route::get('/credits/{id}/print', [CreditController::class, 'print'])->name('credits.print');
        Route::get('/admin/credits/fix-status', [CreditController::class, 'checkAndFixStatus'])->name('credits.fix-status');

        // Stocks
        Route::resource('stocks', StockController::class);
        Route::post('/stocks/sortie', [StockController::class, 'sortie'])->name('stocks.sortie');
        Route::post('/stocks/{id}/seuil', [StockController::class, 'updateSeuil'])->name('stocks.seuil');
        Route::get('/stocks/{id}/mouvements', [StockController::class, 'mouvements'])->name('stocks.mouvements');
        Route::get('/stocks-dashboard', [StockController::class, 'dashboard'])->name('stocks.dashboard');
        Route::get('/stocks-export', [StockController::class, 'export'])->name('stocks.export');
        
        // Bordereaux
        Route::get('/bordereaux', [BordereauController::class, 'index'])->name('bordereaux.index');
        Route::get('/bordereaux/{id}', [BordereauController::class, 'show'])->name('bordereaux.show');
        Route::get('/bordereaux/{id}/print', [BordereauController::class, 'print'])->name('bordereaux.print');
        Route::post('/bordereaux/{id}/annuler', [BordereauController::class, 'annuler'])->name('bordereaux.annuler');
        
        // Formulaires bordereaux
        Route::get('/bordereaux/form/chargement', [BordereauController::class, 'formChargement'])->name('bordereaux.form-chargement');
        Route::post('/bordereaux/chargement', [BordereauController::class, 'genererChargement'])->name('bordereaux.generer-chargement');
        Route::get('/bordereaux/form/livraison', [BordereauController::class, 'formLivraison'])->name('bordereaux.form-livraison');
        Route::post('/bordereaux/livraison', [BordereauController::class, 'genererLivraison'])->name('bordereaux.generer-livraison');
        Route::get('/bordereaux/form/achat', [BordereauController::class, 'formAchat'])->name('bordereaux.form-achat');
        Route::post('/bordereaux/achat-direct', [BordereauController::class, 'genererAchatDirect'])->name('bordereaux.generer-achat-direct');
        Route::get('/bordereaux/form/collecte', [BordereauController::class, 'formCollecte'])->name('bordereaux.form-collecte');
        Route::post('/bordereaux/collecte-direct', [BordereauController::class, 'genererCollecteDirect'])->name('bordereaux.generer-collecte-direct');
        Route::get('/bordereaux/form/contre-passee', [BordereauController::class, 'formContrePassee'])->name('bordereaux.form-contre-passee');
        Route::post('/bordereaux/contre-passee', [BordereauController::class, 'genererContrePassee'])->name('bordereaux.generer-contre-passee');
        
        // Génération depuis collecte/achat
        Route::post('/bordereaux/collecte/{collecteId}', [BordereauController::class, 'genererCollecte'])->name('bordereaux.generer-collecte');
        Route::post('/bordereaux/achat/{achatId}', [BordereauController::class, 'genererAchat'])->name('bordereaux.generer-achat');
        
        // Suivi parcellaire
        Route::resource('suivi-parcellaire', SuiviParcellaireController::class);
        Route::get('/suivi-parcellaire/producteur/{producteurId}', [SuiviParcellaireController::class, 'byProducteur'])->name('suivi-parcellaire.by-producteur');
        Route::get('/suivi-parcellaire-export', [SuiviParcellaireController::class, 'export'])->name('suivi-parcellaire.export');
        
        // Suivi (alias)
        Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
        Route::get('/suivi/liste', [SuiviController::class, 'liste'])->name('suivi.liste');
        Route::get('/suivi/create', [SuiviController::class, 'create'])->name('suivi.create');
        Route::post('/suivi', [SuiviController::class, 'store'])->name('suivi.store');
        Route::get('/suivi/{id}', [SuiviController::class, 'show'])->name('suivi.show');
        Route::get('/suivi/{id}/edit', [SuiviController::class, 'edit'])->name('suivi.edit');
        Route::get('/suivi/{id}/print', [SuiviController::class, 'print'])->name('suivi.print');
        Route::put('/suivi/{id}', [SuiviController::class, 'update'])->name('suivi.update');
        Route::delete('/suivi/{id}', [SuiviController::class, 'destroy'])->name('suivi.destroy');
        Route::get('/suivi/producteur/{id}', [SuiviController::class, 'producteur'])->name('suivi.producteur');
        Route::get('/suivi-export', [SuiviController::class, 'export'])->name('suivi.export');
        Route::get('/suivi/distributions/{producteurId}', [SuiviController::class, 'getDistributions'])->name('suivi.distributions');
        
        // Rapports
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/financier', [RapportController::class, 'financier'])->name('rapports.financier');
        Route::get('/rapports/production', [RapportController::class, 'production'])->name('rapports.production');
        Route::get('/rapports/credits', [RapportController::class, 'credits'])->name('rapports.credits');
        Route::get('/rapports/producteurs', [RapportController::class, 'producteurs'])->name('rapports.producteurs');
        Route::get('/rapports/stocks', [RapportController::class, 'stocks'])->name('rapports.stocks');
        Route::get('/rapports/export/{type}', [RapportController::class, 'exportExcel'])->name('rapports.export');
        Route::get('/rapports/export-excel/{type}', [RapportController::class, 'exportExcel'])->name('rapports.export-excel');
        Route::get('/rapports/export/collectes', [RapportController::class, 'exportCollectes'])->name('rapports.export-collectes');
        Route::get('/rapports/export/credits', [RapportController::class, 'exportCredits'])->name('rapports.export-credits');
        Route::get('/rapports/export/achats', [RapportController::class, 'exportAchats'])->name('rapports.export-achats');

        // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');

    //Routes de sauvegarde
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/run', [BackupController::class, 'run'])->name('backup.run');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [BackupController::class, 'delete'])->name('backup.delete');
    });
});

// ========== ESPACE ANIMATEUR ==========
Route::prefix('animateur')->name('animateur.')->group(function () {
    // Authentification
    Route::get('/login', [AnimateurAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AnimateurAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AnimateurAuthController::class, 'logout'])->name('logout');
    
    // Routes protégées
    Route::middleware('auth:animateur')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AnimateurDashboardController::class, 'index'])->name('dashboard');
        
        // Agents
        Route::get('/agents', [AnimateurAgentController::class, 'index'])->name('agents.index');
        Route::get('/agents/create', [AnimateurAgentController::class, 'create'])->name('agents.create');
        Route::post('/agents', [AnimateurAgentController::class, 'store'])->name('agents.store');
        Route::get('/agents/{id}', [AnimateurAgentController::class, 'show'])->name('agents.show');
        Route::get('/agents/{id}/edit', [AnimateurAgentController::class, 'edit'])->name('agents.edit');
        Route::put('/agents/{id}', [AnimateurAgentController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{id}', [AnimateurAgentController::class, 'destroy'])->name('agents.destroy');
        Route::post('/agents/{id}/reset-password', [AnimateurAgentController::class, 'resetPassword'])->name('agents.reset-password');
        
        // Producteurs
        Route::get('/producteurs', [AnimateurProducteurController::class, 'index'])->name('producteurs.index');
        Route::get('/producteurs/{id}', [AnimateurProducteurController::class, 'show'])->name('producteurs.show');
        
        // Suivi terrain
        Route::get('/suivi', [AnimateurSuiviController::class, 'index'])->name('suivi.index');
        Route::get('/suivi/{id}', [AnimateurSuiviController::class, 'show'])->name('suivi.show');
        
        // Rapports
        Route::get('/rapports', [AnimateurRapportController::class, 'index'])->name('rapports.index');
    });
});

// ========== ESPACE AGENT TERRAIN ==========
Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/login', [AgentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AgentAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AgentAuthController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:agent')->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
        Route::resource('producteurs', AgentProducteurController::class);
        Route::resource('collectes', AgentCollecteController::class);
        Route::resource('suivi', AgentSuiviController::class);
    });
});

// ========== ESPACE CONTRÔLEUR ==========
Route::prefix('controleur')->name('controleur.')->group(function () {
    Route::get('/login', [ControleurAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ControleurAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ControleurAuthController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:controleur')->group(function () {
        Route::get('/dashboard', [ControleurDashboardController::class, 'index'])->name('dashboard');
        Route::resource('producteurs', ControleurProducteurController::class);
        Route::resource('credits', ControleurCreditController::class);
        Route::resource('collectes', ControleurCollecteController::class);
         // Stocks (lecture seule)
         Route::get('/stocks', [ControleurStockController::class, 'index'])->name('stocks.index');
         Route::get('/stocks/{id}', [ControleurStockController::class, 'show'])->name('stocks.show');
         
         // Suivi (lecture seule)
         Route::get('/suivi', [ControleurSuiviController::class, 'index'])->name('suivi.index');
         Route::get('/suivi/{id}', [ControleurSuiviController::class, 'show'])->name('suivi.show');
         Route::get('/suivi/producteur/{producteurId}', [ControleurSuiviController::class, 'byProducteur'])->name('suivi.by-producteur');
        Route::get('/rapports', [ControleurRapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/export-pdf', [ControleurRapportController::class, 'exportPdf'])->name('rapports.export-pdf'); 
    });
});