@extends('layouts.admin')

@section('title', 'Créer un album')
@section('header', 'Créer un nouvel album photo')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    .dropzone-area {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        background: #f8fafc;
        min-height: 180px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .dropzone-area:hover, .dropzone-area.drag-over {
        border-color: #2d6a4f;
        background: #f0fdf4;
    }
    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .image-preview-item .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: transform 0.2s;
    }
    .image-preview-item .remove-image:hover {
        transform: scale(1.1);
    }
</style>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.albums.store') }}" method="POST" enctype="multipart/form-data" id="albumForm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading text-primary mr-2"></i>Titre de l'album *
                </label>
                <input type="text" name="titre" id="titre" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                       placeholder="Ex: Formation des producteurs à Sokodé - Mai 2025">
                @error('titre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-folder text-primary mr-2"></i>Catégorie *
                </label>
                <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="terrain">Terrain</option>
                    <option value="formation">Formation</option>
                    <option value="recolte">Récolte</option>
                    <option value="producteurs">Producteurs</option>
                    <option value="evenement">Événement</option>
                </select>
                @error('categorie')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt text-primary mr-2"></i>Date de l'événement *
                </label>
                <input type="date" name="date_evenement" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                @error('date_evenement')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Lieu -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>Lieu
                </label>
                <input type="text" name="lieu" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Sokodé, Région Centrale">
                @error('lieu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-primary mr-2"></i>Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Décrivez cet événement..."></textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image de couverture -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-primary mr-2"></i>Image de couverture *
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition cursor-pointer"
                     onclick="document.getElementById('couverture').click()">
                    <input type="file" name="couverture" id="couverture" accept="image/*" class="hidden" required>
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Cliquez pour choisir l'image de couverture</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 5MB)</p>
                    <div id="couverturePreview" class="mt-3 hidden">
                        <img id="couvertureImg" src="#" alt="Prévisualisation" class="max-h-32 mx-auto rounded-lg">
                    </div>
                </div>
                @error('couverture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Upload multiples images -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-images text-primary mr-2"></i>Photos de l'événement *
                </label>
                
                <div id="dropzoneArea" class="dropzone-area p-6 text-center">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                    <p class="text-xs text-gray-400 mt-1">Vous pouvez sélectionner plusieurs images (JPG, PNG, jusqu'à 5MB chacune)</p>
                </div>
                
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="hidden">
                
                <div id="selectedImagesPreview" class="mt-4 hidden">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Images sélectionnées :</p>
                    <div id="previewList" class="flex flex-wrap gap-2"></div>
                </div>
                @error('images')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.albums.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Créer l'album
            </button>
        </div>
    </form>
</div>

<script>
    let selectedFiles = [];
    const dropzoneArea = document.getElementById('dropzoneArea');
    const imageInput = document.getElementById('imageInput');
    const previewList = document.getElementById('previewList');
    const previewContainer = document.getElementById('selectedImagesPreview');
    
    // Couverture preview
    document.getElementById('couverture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('couvertureImg').src = e.target.result;
                document.getElementById('couverturePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Click sur la zone de drop
    dropzoneArea.addEventListener('click', () => imageInput.click());
    
    // Drag & drop
    dropzoneArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzoneArea.classList.add('drag-over');
    });
    
    dropzoneArea.addEventListener('dragleave', () => {
        dropzoneArea.classList.remove('drag-over');
    });
    
    dropzoneArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzoneArea.classList.remove('drag-over');
        const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
        addFiles(files);
    });
    
    // Sélection de fichiers
    imageInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        addFiles(files);
        imageInput.value = '';
    });
    
    function addFiles(newFiles) {
        newFiles.forEach(file => {
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });
        updatePreview();
        updateFormInput();
    }
    
    function updatePreview() {
        if (selectedFiles.length === 0) {
            previewContainer.classList.add('hidden');
            return;
        }
        
        previewContainer.classList.remove('hidden');
        previewList.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'image-preview-item relative group';
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover">
                <div class="remove-image" onclick="removeFile(${index})">
                    <i class="fas fa-times text-xs"></i>
                </div>
            `;
            previewList.appendChild(div);
            
            // Nettoyer l'URL après chargement
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        });
    }
    
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateFormInput();
    }
    
    function updateFormInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }
</script>
@endsection