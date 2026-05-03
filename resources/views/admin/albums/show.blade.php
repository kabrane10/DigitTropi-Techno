@extends('layouts.admin')

@section('title', 'Détails album')
@section('header', 'Album : ' . $album->titre)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Informations album -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Image couverture -->
            <div class="md:w-64 h-48 rounded-lg overflow-hidden bg-gray-100">
                @if($album->couverture)
                    <img src="{{ asset('storage/' . $album->couverture) }}" alt="{{ $album->titre }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-images text-4xl text-gray-300"></i>
                    </div>
                @endif
            </div>
            
            <!-- Infos -->
            <div class="flex-1">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-dark">{{ $album->titre }}</h2>
                        <div class="flex flex-wrap gap-3 mt-2">
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                                @if($album->categorie == 'terrain') Terrain
                                @elseif($album->categorie == 'formation') Formation
                                @elseif($album->categorie == 'recolte') Récolte
                                @elseif($album->categorie == 'producteurs') Producteurs
                                @else 🎉 Événement
                                @endif
                            </span>
                            <span class="text-gray-500 text-sm"><i class="far fa-calendar mr-1"></i>{{ $album->date_evenement->format('d/m/Y') }}</span>
                            @if($album->lieu)
                            <span class="text-gray-500 text-sm"><i class="fas fa-map-marker-alt mr-1"></i>{{ $album->lieu }}</span>
                            @endif
                            <span class="text-gray-500 text-sm"><i class="fas fa-images mr-1"></i>{{ $album->images->count() }} photos</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.albums.edit', $album) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        <form action="{{ route('admin.albums.destroy', $album) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                @if($album->description)
                <p class="text-gray-600 mt-4">{{ $album->description }}</p>
                @endif
                <div class="mt-4 pt-3 border-t">
                    <p class="text-sm text-gray-500">Créé le {{ $album->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Galerie d'images -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
            <h3 class="text-lg font-semibold">Photos de l'album ({{ $album->images->count() }})</h3>
            <button onclick="openAddImagesModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Ajouter des photos
            </button>
        </div>
        
        @if($album->images->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($album->images as $image)
            <div class="relative group">
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->titre }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                </div>
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                    <button onclick="viewImage('{{ asset('storage/' . $image->image) }}', '{{ $image->titre }}')" class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-eye"></i>
                    </button>
                    <form action="{{ route('admin.galerie.destroy', $image) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600" onclick="return confirm('Supprimer cette image ?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-images text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucune photo dans cet album</p>
            <button onclick="openAddImagesModal()" class="mt-3 text-primary hover:underline">Ajouter des photos</button>
        </div>
        @endif
    </div>
</div>

<!-- Modal ajout d'images -->
<div id="addImagesModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-lg w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Ajouter des photos</h3>
            <button onclick="closeAddImagesModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.albums.add-images', $album) }}" method="POST" enctype="multipart/form-data" id="addImagesForm">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition cursor-pointer" onclick="document.getElementById('addImagesInput').click()">
                <input type="file" name="new_images[]" id="addImagesInput" multiple accept="image/*" class="hidden" required>
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Cliquez pour sélectionner des images</p>
                <p class="text-xs text-gray-400 mt-1">Vous pouvez sélectionner plusieurs images</p>
            </div>
            <div id="addImagesPreview" class="mt-4 hidden">
                <p class="text-sm font-semibold mb-2">Images sélectionnées :</p>
                <div id="addImagesPreviewList" class="flex flex-wrap gap-2"></div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeAddImagesModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal visualisation image -->
<div id="imageViewModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center" onclick="closeImageView()">
    <div class="relative max-w-4xl max-h-screen p-4" onclick="event.stopPropagation()">
        <button onclick="closeImageView()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="viewImageSrc" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl">
        <p id="viewImageTitle" class="text-center text-white mt-3"></p>
    </div>
</div>

<script>
    // Variables pour l'ajout d'images
    let addSelectedFiles = [];
    
    document.getElementById('addImagesInput').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        addSelectedFiles = files;
        updateAddImagesPreview();
    });
    
    function updateAddImagesPreview() {
        const previewContainer = document.getElementById('addImagesPreview');
        const previewList = document.getElementById('addImagesPreviewList');
        
        if (addSelectedFiles.length === 0) {
            previewContainer.classList.add('hidden');
            return;
        }
        
        previewContainer.classList.remove('hidden');
        previewList.innerHTML = '';
        
        addSelectedFiles.forEach((file, index) => {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'relative w-16 h-16 rounded-lg overflow-hidden';
            div.innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
            previewList.appendChild(div);
        });
    }
    
    function openAddImagesModal() {
        document.getElementById('addImagesModal').classList.remove('hidden');
        document.getElementById('addImagesModal').classList.add('flex');
    }
    
    function closeAddImagesModal() {
        document.getElementById('addImagesModal').classList.add('hidden');
        document.getElementById('addImagesModal').classList.remove('flex');
        addSelectedFiles = [];
        document.getElementById('addImagesInput').value = '';
        document.getElementById('addImagesPreview').classList.add('hidden');
    }
    
    function viewImage(src, title) {
        document.getElementById('viewImageSrc').src = src;
        document.getElementById('viewImageTitle').textContent = title;
        document.getElementById('imageViewModal').classList.remove('hidden');
        document.getElementById('imageViewModal').classList.add('flex');
    }
    
    function closeImageView() {
        document.getElementById('imageViewModal').classList.add('hidden');
        document.getElementById('imageViewModal').classList.remove('flex');
    }
</script>
@endsection