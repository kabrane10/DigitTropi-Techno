<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - Tropi-Techno Sarl</title>
    <meta name="description" content="Découvrez nos activités agricoles">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes shimmer { 0% { background-position: -1000px 0; } 100% { background-position: 1000px 0; } }
        .spinner { width: 50px; height: 50px; border: 4px solid #e5e7eb; border-top-color: #2d6a4f; border-radius: 50%; animation: spin 0.8s linear infinite; }
        .skeleton-card { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 1000px 100%; animation: shimmer 1.5s infinite; border-radius: 12px; }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .gallery-item:hover { transform: translateY(-5px); }
        .gallery-item { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50">

@include('partials.navbar')

<section class="relative pt-32 pb-20 bg-gradient-to-r from-primary/10 to-secondary/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-dark mb-4">Notre <span class="text-primary">Galerie</span></h1>
        <p class="text-xl text-gray-600">Découvrez nos activités sur le terrain</p>
    </div>
</section>

<section class="py-8 pb-20" id="galerie-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div id="filters-skeleton" class="flex flex-wrap justify-center gap-3 mb-12">
            <div class="skeleton-card w-20 h-10 rounded-full"></div>
            <div class="skeleton-card w-24 h-10 rounded-full"></div>
            <div class="skeleton-card w-28 h-10 rounded-full"></div>
        </div>
        
        <div id="loading-state" class="text-center py-16">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-500 mt-4">Chargement des images...</p>
        </div>
        
        <div id="offline-state" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-wifi-slash text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Pas de connexion internet</h3>
            <button onclick="retryLoad()" class="bg-primary text-white px-6 py-2 rounded-lg mt-4">Réessayer</button>
        </div>
        
        <div id="empty-state" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-images text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune image disponible</h3>
            <p class="text-gray-500">Revenez plus tard pour découvrir nos activités</p>
        </div>
        
        <div id="content-state" class="hidden fade-in">
            <div id="filters-container" class="flex flex-wrap justify-center gap-3 mb-12"></div>
            <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"></div>
            <div id="pagination-container" class="mt-12 hidden"></div>
        </div>
    </div>
</section>

<div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeModal()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300"><i class="fas fa-times"></i></button>
        <img id="modalImage" src="" class="w-full rounded-2xl">
        <div class="bg-white rounded-b-2xl p-6 mt-1">
            <h3 id="modalTitle" class="text-2xl font-bold text-dark mb-2"></h3>
            <p id="modalDesc" class="text-gray-600 mb-3"></p>
            <div class="flex gap-4 text-sm text-gray-500"><span id="modalLieu"></span><span id="modalDate"></span></div>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    let retryCount = 0;
    const maxRetries = 3;
    const MIN_LOADING_TIME = 2000
    let currentFilter = 'all';
    let currentPage = 1;
    
;
    
    function isOnline() { return navigator.onLine; }
    
    function invalidateCache() {
        localStorage.removeItem('galerie_cache');
        localStorage.removeItem('galerie_timestamp');
    }
    
    function loadGalerie() {
    if (!isOnline()) {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('offline-state').classList.remove('hidden');
        return;
    }
    
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('offline-state').classList.add('hidden');
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('content-state').classList.add('hidden');
    document.getElementById('filters-skeleton').classList.remove('hidden');
    
    const startTime = Date.now();
    
    // Vérifier le cache (30 minutes)
    const cached = localStorage.getItem('galerie_cache');
    const timestamp = localStorage.getItem('galerie_timestamp');
    const cacheValid = timestamp && (Date.now() - parseInt(timestamp) < 30 * 60 * 1000);
    
    if (cacheValid && cached && !window.location.search.includes('refresh')) {
        try {
            const data = JSON.parse(cached);
            if (data.images && data.images.data && data.images.data.length > 0) {
                const elapsed = Date.now() - startTime;
                const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
                
                setTimeout(() => {
                    renderGalerie(data);
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('content-state').classList.remove('hidden');
                    document.getElementById('filters-skeleton').classList.add('hidden');
                }, waitTime);
                return;
            }
        } catch(e) {}
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('/galerie/api/data', { 
        signal: controller.signal, 
        headers: { 'Cache-Control': 'no-cache' } 
    })
    .then(response => { 
        clearTimeout(timeoutId); 
        if (!response.ok) throw new Error(); 
        return response.json(); 
    })
    .then(data => {
        if (data.status === 'success') {
            localStorage.setItem('galerie_cache', JSON.stringify(data));
            localStorage.setItem('galerie_timestamp', Date.now().toString());
            
            const elapsed = Date.now() - startTime;
            const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
            
            if (data.images && data.images.data && data.images.data.length > 0) {
                setTimeout(() => {
                    renderGalerie(data);
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('content-state').classList.remove('hidden');
                }, waitTime);
            } else {
                setTimeout(() => {
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('empty-state').classList.remove('hidden');
                }, waitTime);
            }
            document.getElementById('filters-skeleton').classList.add('hidden');
        } else {
            throw new Error();
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Erreur:', error);
        
        const elapsed = Date.now() - startTime;
        const waitTime = Math.max(0, MIN_LOADING_TIME - elapsed);
        
        if (!isOnline()) {
            setTimeout(() => {
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('offline-state').classList.remove('hidden');
            }, waitTime);
        } else if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(() => loadGalerie(), 2000);
        } else {
            setTimeout(() => {
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('empty-state').classList.remove('hidden');
            }, waitTime);
        }
        document.getElementById('filters-skeleton').classList.add('hidden');
    });
}
    function renderGalerie(data) {
        const categories = data.categories || [];
        document.getElementById('filters-container').innerHTML = `
            <button class="filter-btn px-6 py-2 rounded-full bg-primary text-white" data-filter="all">Tous</button>
            ${categories.map(c => `<button class="filter-btn px-6 py-2 rounded-full bg-white shadow-md hover:bg-primary hover:text-white transition" data-filter="${c}">${getCategorieIcon(c)} ${getCategorieName(c)}</button>`).join('')}
        `;
        
        document.getElementById('gallery-grid').innerHTML = data.images.data.map(img => `
            <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer" data-category="${img.categorie}" onclick="openModal('${img.image_url}', '${escapeHtml(img.titre)}', '${escapeHtml(img.description || '')}', '${img.lieu || 'Non spécifié'}', '${new Date(img.date_prise).toLocaleDateString('fr-FR')}')">
                <div class="relative overflow-hidden h-64"><img src="${img.image_url}" alt="${escapeHtml(img.titre)}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"><div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center"><i class="fas fa-search-plus text-white text-3xl"></i></div></div>
                <div class="p-4"><h3 class="font-semibold text-lg text-dark mb-2">${escapeHtml(img.titre)}</h3><p class="text-gray-600 text-sm line-clamp-2">${escapeHtml(img.description || '')}</p><div class="flex justify-between mt-3 text-xs text-gray-500"><span><i class="fas fa-map-marker-alt mr-1"></i> ${img.lieu || 'Non spécifié'}</span><span><i class="fas fa-calendar mr-1"></i> ${new Date(img.date_prise).toLocaleDateString('fr-FR')}</span></div></div>
            </div>
        `).join('');
        
        if (data.images.links) {
            document.getElementById('pagination-container').innerHTML = renderPagination(data.images.links);
            document.getElementById('pagination-container').classList.remove('hidden');
        }
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                document.querySelectorAll('.filter-btn').forEach(b => { b.classList.remove('bg-primary', 'text-white'); b.classList.add('bg-white', 'text-gray-700'); });
                this.classList.add('bg-primary', 'text-white');
                document.querySelectorAll('.gallery-item').forEach(item => { item.style.display = (filter === 'all' || item.dataset.category === filter) ? 'block' : 'none'; });
            });
        });
    }
    
    function renderPagination(links, currentPath = '/galerie') {
    if (!links) return '';
    
    let html = '<div class="flex justify-center space-x-2 flex-wrap gap-2 my-8">';
    
    links.forEach(link => {
        if (link.url) {
            // Extraire le numéro de page de l'URL de l'API
            let pageMatch = link.url.match(/[?&]page=(\d+)/);
            let pageNum = pageMatch ? pageMatch[1] : '1';
            
            // Créer un lien vers la page normale (pas l'API)
            let normalUrl = `${currentPath}?page=${pageNum}`;
            
            html += `<a href="${normalUrl}" 
                       class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition ${link.active ? 'bg-primary text-white border-primary' : ''}">
                        ${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}
                    </a>`;
        } else {
            html += `<span class="px-4 py-2 border rounded-lg text-gray-400">${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</span>`;
        }
    });
    
    html += '</div>';
    return html;
}
    
    function getCategorieIcon(c) { const icons = { 'terrain':'🌾','formation':'📚','recolte':'🌽','producteurs':'👨‍🌾','evenement':'🎉' }; return icons[c] || '📷'; }
    function getCategorieName(c) { const names = { 'terrain':'Terrain','formation':'Formation','recolte':'Récolte','producteurs':'Producteurs','evenement':'Événement' }; return names[c] || c; }
    function escapeHtml(t) { if (!t) return ''; const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
    function openModal(img,title,desc,lieu,date) { document.getElementById('modalImage').src = img; document.getElementById('modalTitle').textContent = title; document.getElementById('modalDesc').textContent = desc; document.getElementById('modalLieu').innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i> ' + lieu; document.getElementById('modalDate').innerHTML = '<i class="fas fa-calendar mr-1"></i> ' + date; document.getElementById('imageModal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('imageModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
    function retryLoad() { retryCount = 0; loadGalerie(); }
    
    window.addEventListener('online', () => { invalidateCache(); loadGalerie(); });
    window.addEventListener('offline', () => { document.getElementById('loading-state').classList.add('hidden'); document.getElementById('offline-state').classList.remove('hidden'); });
    
    loadGalerie();
</script>
</body>
</html>