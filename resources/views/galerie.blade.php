<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - Tropi-Techno Sarl</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .album-card, .photo-card {
            transition: all 0.3s ease;
        }
        .album-card:hover, .photo-card:hover {
            transform: translateY(-5px);
        }
        .modal-img {
            animation: zoomIn 0.3s ease;
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .tab-active {
            background-color: #2d6a4f;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">

@include('partials.navbar')

<section class="relative pt-32 pb-20 bg-gradient-to-r from-primary/10 to-secondary/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-dark mb-4">Notre <span class="text-primary">Galerie</span></h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Découvrez nos activités en images
        </p>
    </div>
</section>

<!-- Onglets -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="flex justify-center gap-4 mb-8">
        <button onclick="showTab('albums')" id="tabAlbumsBtn" class="tab-btn px-6 py-2 rounded-full bg-primary text-white shadow-md transition">
            <i class="fas fa-images mr-2"></i>Albums
        </button>
        <button onclick="showTab('photos')" id="tabPhotosBtn" class="tab-btn px-6 py-2 rounded-full bg-white shadow-md hover:bg-primary hover:text-white transition">
            <i class="fas fa-image mr-2"></i>Photos individuelles
        </button>
    </div>
</div>

<!-- Contenu Albums -->
<section id="albumsTab" class="py-8 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="albumsLoading" class="text-center py-16">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-500">Chargement des albums...</p>
        </div>
        <div id="albumsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 hidden"></div>
    </div>
</section>

<!-- Contenu Photos individuelles -->
<section id="photosTab" class="py-8 pb-20 hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="photosLoading" class="text-center py-16">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-500">Chargement des photos...</p>
        </div>
        <div id="photosGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 hidden"></div>
        <div id="photosPagination" class="mt-8 flex justify-center hidden"></div>
    </div>
</section>

<!-- Modals -->
<div id="albumModal" class="fixed inset-0 bg-black/90 z-50 hidden overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="relative max-w-6xl w-full bg-white rounded-2xl overflow-hidden">
            <button onclick="closeAlbumModal()" class="absolute top-4 right-4 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div id="albumModalContent" class="p-6"></div>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="" class="modal-img w-full rounded-2xl">
        <div class="bg-white rounded-b-2xl p-6 mt-1">
            <h3 id="modalTitle" class="text-2xl font-bold text-dark mb-2"></h3>
            <p id="modalDesc" class="text-gray-600 mb-3"></p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span id="modalLieu"><i class="fas fa-map-marker-alt mr-1"></i></span>
                <span id="modalDate"><i class="fas fa-calendar mr-1"></i></span>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    let currentTab = 'albums';
    
    function showTab(tab) {
        currentTab = tab;
        
        // Mise à jour des onglets
        document.getElementById('tabAlbumsBtn').classList.toggle('bg-primary', tab === 'albums');
        document.getElementById('tabAlbumsBtn').classList.toggle('text-white', tab === 'albums');
        document.getElementById('tabAlbumsBtn').classList.toggle('bg-white', tab !== 'albums');
        document.getElementById('tabPhotosBtn').classList.toggle('bg-primary', tab === 'photos');
        document.getElementById('tabPhotosBtn').classList.toggle('text-white', tab === 'photos');
        document.getElementById('tabPhotosBtn').classList.toggle('bg-white', tab !== 'photos');
        
        // Afficher/masquer les sections
        document.getElementById('albumsTab').classList.toggle('hidden', tab !== 'albums');
        document.getElementById('photosTab').classList.toggle('hidden', tab !== 'photos');
        
        // Charger selon l'onglet
        if (tab === 'albums') {
            loadAlbums();
        } else {
            loadPhotos();
        }
    }
    
    function loadAlbums() {
        const loading = document.getElementById('albumsLoading');
        const grid = document.getElementById('albumsGrid');
        
        loading.classList.remove('hidden');
        grid.classList.add('hidden');
        
        fetch('/galerie/api/albums')
            .then(response => response.json())
            .then(data => {
                if (data.albums && data.albums.length > 0) {
                    grid.innerHTML = data.albums.map(album => `
                        <div class="album-card bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer group" onclick="openAlbum(${album.id})">
                            <div class="relative h-64 overflow-hidden">
                                <img src="${album.couverture_url}" alt="${album.titre}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="bg-primary text-white px-4 py-2 rounded-full text-sm">
                                        <i class="fas fa-images mr-2"></i>${album.images_count} photos
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-xl font-bold text-dark mb-2">${escapeHtml(album.titre)}</h3>
                                <p class="text-gray-500 text-sm mb-2">
                                    <i class="fas fa-calendar-alt mr-1"></i> ${new Date(album.date_evenement).toLocaleDateString('fr-FR')}
                                    ${album.lieu ? `<span class="ml-3"><i class="fas fa-map-marker-alt mr-1"></i> ${escapeHtml(album.lieu)}</span>` : ''}
                                </p>
                                <p class="text-gray-600 text-sm line-clamp-2">${escapeHtml(album.description || '')}</p>
                                <div class="mt-3 text-primary text-sm font-medium">Voir l'album →</div>
                            </div>
                        </div>
                    `).join('');
                    grid.classList.remove('hidden');
                } else {
                    grid.innerHTML = '<div class="col-span-3 text-center py-12"><p class="text-gray-500">Aucun album disponible</p></div>';
                    grid.classList.remove('hidden');
                }
                loading.classList.add('hidden');
            })
            .catch(error => {
                console.error('Erreur:', error);
                loading.classList.add('hidden');
                grid.innerHTML = '<div class="col-span-3 text-center py-12"><p class="text-red-500">Erreur de chargement</p></div>';
                grid.classList.remove('hidden');
            });
    }
    
    function loadPhotos(page = 1) {
        const loading = document.getElementById('photosLoading');
        const grid = document.getElementById('photosGrid');
        const pagination = document.getElementById('photosPagination');
        
        loading.classList.remove('hidden');
        grid.classList.add('hidden');
        pagination.classList.add('hidden');
        
        fetch(`/galerie/api/photos?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.photos && data.photos.data.length > 0) {
                    grid.innerHTML = data.photos.data.map(photo => `
                        <div class="photo-card bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer group" onclick="openImageModal('${photo.image_url}', '${escapeHtml(photo.titre)}', '${escapeHtml(photo.description || '')}', '${photo.lieu || 'Non spécifié'}', '${new Date(photo.date_prise).toLocaleDateString('fr-FR')}')">
                            <div class="relative h-56 overflow-hidden">
                                <img src="${photo.image_url}" alt="${escapeHtml(photo.titre)}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-dark mb-1">${escapeHtml(photo.titre)}</h3>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>${new Date(photo.date_prise).toLocaleDateString('fr-FR')}
                                </p>
                            </div>
                        </div>
                    `).join('');
                    grid.classList.remove('hidden');
                    
                    // Pagination
                    if (data.photos.last_page > 1) {
                        let paginationHtml = '<div class="flex space-x-2">';
                        for (let i = 1; i <= data.photos.last_page; i++) {
                            paginationHtml += `<button onclick="loadPhotos(${i})" class="px-3 py-1 border rounded-lg ${i === data.photos.current_page ? 'bg-primary text-white' : 'hover:bg-gray-50'}">${i}</button>`;
                        }
                        paginationHtml += '</div>';
                        pagination.innerHTML = paginationHtml;
                        pagination.classList.remove('hidden');
                    }
                } else {
                    grid.innerHTML = '<div class="col-span-4 text-center py-12"><p class="text-gray-500">Aucune photo disponible</p></div>';
                    grid.classList.remove('hidden');
                }
                loading.classList.add('hidden');
            })
            .catch(error => {
                console.error('Erreur:', error);
                loading.classList.add('hidden');
                grid.innerHTML = '<div class="col-span-4 text-center py-12"><p class="text-red-500">Erreur de chargement</p></div>';
                grid.classList.remove('hidden');
            });
    }
    
    function openImageModal(imgSrc, title, desc, lieu, date) {
        document.getElementById('modalImage').src = imgSrc;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent = desc || 'Aucune description';
        document.getElementById('modalLieu').innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i> ' + (lieu || 'Non spécifié');
        document.getElementById('modalDate').innerHTML = '<i class="fas fa-calendar mr-1"></i> ' + date;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Chargement initial
    loadAlbums();
</script>
</body>
</html>