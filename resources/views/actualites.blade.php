<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - Tropi-Techno Sarl</title>
    <meta name="description" content="Suivez l'actualité de Tropi-Techno">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e5e7eb;
            border-top-color: #2d6a4f;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        .skeleton-card {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 12px;
        }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="bg-gray-50">

@include('partials.navbar')

<!-- Hero Section -->
<section class="relative pt-32 pb-20 bg-gradient-to-r from-primary to-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos Actualités</h1>
        <p class="text-xl max-w-2xl mx-auto">Restez informés des dernières nouvelles</p>
    </div>
</section>

<!-- Contenu principal -->
<section class="py-12 pb-20" id="actualites-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- État de chargement -->
        <div id="loading-state" class="text-center py-16">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-500 mt-4">Chargement des actualités...</p>
        </div>
        
        <!-- État hors ligne -->
        <div id="offline-state" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-wifi-slash text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Pas de connexion internet</h3>
            <p class="text-gray-500 mb-4">Vérifiez votre connexion et réessayez</p>
            <button onclick="retryLoad()" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-sync-alt mr-2"></i>Réessayer
            </button>
        </div>
        
        <!-- État aucune actualité -->
        <div id="empty-state" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-newspaper text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune actualité disponible</h3>
            <p class="text-gray-500">Revenez plus tard pour découvrir nos dernières nouvelles</p>
        </div>
        
        <!-- Contenu -->
        <div id="content-state" class="hidden fade-in">
            <div id="campagnes-section" class="hidden mb-12"></div>
            <div id="actualites-en-avant" class="hidden mb-12"></div>
            <div id="actualites-liste" class="hidden"></div>
        </div>
    </div>
</section>

@include('partials.footer')

<script>
    let retryCount = 0;
    const maxRetries = 3;
    const MIN_LOADING_TIME = 2000;
    let currentFilter = 'all';
    let currentPage = 1;
    
    function isOnline() { return navigator.onLine; }
    
    function showLoading() {
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('offline-state').classList.add('hidden');
        document.getElementById('empty-state').classList.add('hidden');
        document.getElementById('content-state').classList.add('hidden');
    }
    
    function showOffline() {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('offline-state').classList.remove('hidden');
        document.getElementById('empty-state').classList.add('hidden');
        document.getElementById('content-state').classList.add('hidden');
    }
    
    function showEmpty() {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('offline-state').classList.add('hidden');
        document.getElementById('empty-state').classList.remove('hidden');
        document.getElementById('content-state').classList.add('hidden');
    }
    
    function showContent() {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('offline-state').classList.add('hidden');
        document.getElementById('empty-state').classList.add('hidden');
        document.getElementById('content-state').classList.remove('hidden');
    }
    
    function retryLoad() { retryCount = 0; loadActualites(); }
    
    // Invalider le cache (forcer le rechargement)
    function invalidateCache() {
        localStorage.removeItem('actualites_cache');
        localStorage.removeItem('actualites_timestamp');
    }
    
    function loadActualites() {
    if (!isOnline()) { showOffline(); return; }
    
    showLoading();
    
    // Enregistrer le temps de début
    const startTime = Date.now();
    
    // Vérifier le cache (30 minutes)
    const cached = localStorage.getItem('actualites_cache');
    const timestamp = localStorage.getItem('actualites_timestamp');
    const cacheValid = timestamp && (Date.now() - parseInt(timestamp) < 30 * 60 * 1000);
    
    if (cacheValid && cached && !window.location.search.includes('refresh')) {
        try {
            const data = JSON.parse(cached);
            if (data && data.actualites && data.actualites.data && data.actualites.data.length > 0) {
                // Calculer le temps écoulé et attendre si nécessaire
                const elapsed = Date.now() - startTime;
                const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
                
                setTimeout(() => {
                    renderActualites(data);
                    showContent();
                }, waitTime);
                return;
            }
        } catch(e) {}
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 20000);
    
    fetch('/actualites/api/data', { 
        signal: controller.signal,
        headers: { 'Cache-Control': 'no-cache' }
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error('Erreur serveur');
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Mettre en cache
            localStorage.setItem('actualites_cache', JSON.stringify(data));
            localStorage.setItem('actualites_timestamp', Date.now().toString());
            
            // Calculer le temps écoulé et attendre si nécessaire
            const elapsed = Date.now() - startTime;
            const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
            
            if (data.actualites && data.actualites.data && data.actualites.data.length > 0) {
                setTimeout(() => {
                    renderActualites(data);
                    showContent();
                }, waitTime);
            } else {
                setTimeout(() => showEmpty(), waitTime);
            }
        } else {
            const elapsed = Date.now() - startTime;
            const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
            setTimeout(() => showEmpty(), waitTime);
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Erreur:', error);
        
        const elapsed = Date.now() - startTime;
        const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
        
        if (!isOnline()) {
            setTimeout(() => showOffline(), waitTime);
        } else if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(() => loadActualites(), 2000);
        } else {
            setTimeout(() => showEmpty(), waitTime);
        }
    });
}
    
    function renderActualites(data) {
        // Campagnes
        if (data.campagnes_en_cours && data.campagnes_en_cours.length > 0) {
            document.getElementById('campagnes-section').innerHTML = `
                <div class="flex items-center mb-6"><i class="fas fa-bullhorn text-orange-500 text-2xl mr-3"></i><h2 class="text-2xl font-bold">Campagnes en cours</h2></div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    ${data.campagnes_en_cours.map(c => `
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-4 border-orange-500">
                            <div class="p-6"><div class="flex items-center mb-3"><span class="bg-orange-100 text-orange-600 text-xs px-3 py-1 rounded-full">En cours</span></div>
                            <h3 class="text-xl font-bold mb-2">${escapeHtml(c.titre)}</h3>
                            <p class="text-gray-600 text-sm mb-4">${escapeHtml(c.excerpt)}</p>
                            <a href="/actualite/${c.slug}" class="text-primary hover:underline">En savoir plus →</a></div>
                        </div>
                    `).join('')}
                </div>
            `;
            document.getElementById('campagnes-section').classList.remove('hidden');
        }
        
        // À la une
        if (data.actualites_en_avant && data.actualites_en_avant.length > 0) {
            document.getElementById('actualites-en-avant').innerHTML = `
                <div class="flex items-center mb-6"><i class="fas fa-star text-yellow-500 text-2xl mr-3"></i><h2 class="text-2xl font-bold">À la une</h2></div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                    ${data.actualites_en_avant.map((a, i) => `
                        <div class="${i===0?'lg:col-span-2':''} bg-white rounded-2xl shadow-xl overflow-hidden">
                            ${i===0 && a.image_couverture ? `<img src="/storage/${a.image_couverture}" class="w-full h-64 object-cover">` : ''}
                            <div class="p-6"><div class="flex items-center gap-2 mb-3"><span class="bg-primary/10 text-primary text-xs px-3 py-1 rounded-full">${a.categorie}</span>
                            <span class="text-gray-400 text-xs">${new Date(a.date_publication).toLocaleDateString('fr-FR')}</span></div>
                            <h3 class="text-${i===0?'2xl':'xl'} font-bold mb-3">${escapeHtml(a.titre)}</h3>
                            <p class="text-gray-600 mb-4">${escapeHtml(a.excerpt)}</p>
                            <a href="/actualite/${a.slug}" class="text-primary font-semibold hover:underline">Lire la suite →</a></div>
                        </div>
                    `).join('')}
                </div>
            `;
            document.getElementById('actualites-en-avant').classList.remove('hidden');
        }
        
        // Liste
        if (data.actualites && data.actualites.data && data.actualites.data.length > 0) {
            document.getElementById('actualites-liste').innerHTML = `
                <div class="flex items-center mb-6"><i class="fas fa-list text-primary text-2xl mr-3"></i><h2 class="text-2xl font-bold">Toutes les actualités</h2></div>
                <div class="space-y-6">
                    ${data.actualites.data.map(a => `
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="md:flex">
                                ${a.image_couverture ? `<div class="md:w-72 h-48 md:h-auto"><img src="/storage/${a.image_couverture}" class="w-full h-full object-cover"></div>` :
                                    `<div class="md:w-72 h-48 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center"><i class="fas fa-newspaper text-4xl text-primary/50"></i></div>`}
                                <div class="p-6 flex-1">
                                    <div class="flex items-center gap-2 mb-2"><span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full">${a.categorie}</span>
                                    <span class="text-gray-400 text-xs">${new Date(a.date_publication).toLocaleDateString('fr-FR')}</span></div>
                                    <h3 class="text-xl font-bold mb-2">${escapeHtml(a.titre)}</h3>
                                    <p class="text-gray-600 mb-4">${escapeHtml(a.excerpt)}</p>
                                    <a href="/actualite/${a.slug}" class="text-primary font-semibold hover:underline">Lire plus →</a>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="mt-8">${data.actualites.links ? renderPagination(data.actualites.links) : ''}</div>
            `;
            document.getElementById('actualites-liste').classList.remove('hidden');
        }
    }
    
    function renderPagination(links) {
        if (!links) return '';
        let html = '<div class="flex justify-center space-x-2">';
        links.forEach(l => {
            if (l.url) html += `<a href="${l.url}" class="px-3 py-2 border rounded-lg hover:bg-gray-50 ${l.active ? 'bg-primary text-white border-primary' : ''}">${l.label.replace('&laquo;', '«').replace('&raquo;', '»')}</a>`;
            else html += `<span class="px-3 py-2 border rounded-lg text-gray-400">${l.label.replace('&laquo;', '«').replace('&raquo;', '»')}</span>`;
        });
        html += '</div>';
        return html;
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    window.addEventListener('online', () => { invalidateCache(); loadActualites(); });
    window.addEventListener('offline', showOffline);
    
    loadActualites();
</script>
</body>
</html>