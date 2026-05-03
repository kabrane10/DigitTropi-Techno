@extends('layouts.admin')

@section('title', 'Albums photos')
@section('header', 'Gestion des albums photos')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Tous les albums</h2>
        <a href="{{ route('admin.albums.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
            <i class="fas fa-plus mr-2"></i>Nouvel album
        </a>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="categorie" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Toutes catégories</option>
                <option value="terrain" {{ request('categorie') == 'terrain' ? 'selected' : '' }}> Terrain</option>
                <option value="formation" {{ request('categorie') == 'formation' ? 'selected' : '' }}>Formation</option>
                <option value="recolte" {{ request('categorie') == 'recolte' ? 'selected' : '' }}>Récolte</option>
                <option value="producteurs" {{ request('categorie') == 'producteurs' ? 'selected' : '' }}>👨Producteurs</option>
                <option value="evenement" {{ request('categorie') == 'evenement' ? 'selected' : '' }}>Événement</option>
            </select>
            <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous statuts</option>
                <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
        @forelse($albums as $album)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border">
            <!-- Image de couverture -->
            <div class="relative h-48 overflow-hidden">
                @if($album->couverture)
                    {{-- J'ai modifié la ligne ci-dessous pour afficher l'image depuis Cloudinary.
                         Anciennement : <img src="{{ asset('storage/' . $album->couverture) }}" ...>
                         Maintenant, j'utilise directement l'URL de l'image qui est maintenant stockée dans la base de données. --}}
                    <img src="{{ $album->couverture }}" alt="{{ $album->titre }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                        <i class="fas fa-images text-4xl text-primary/50"></i>
                    </div>
                @endif
                
                <!-- Badge nombre de photos -->
                <div class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                    <i class="fas fa-image mr-1"></i> {{ $album->images_count }} photo(s)
                </div>
                
                <!-- Badge statut -->
                <div class="absolute top-2 left-2">
                    @if($album->est_publie)
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full"> Publié</span>
                    @else
                        <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full"> Brouillon</span>
                    @endif
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="font-bold text-lg text-dark mb-1 line-clamp-1">{{ $album->titre }}</h3>
                
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                    <span class="px-2 py-0.5 rounded-full bg-gray-100
                    @if($album->categorie == 'terrain') bg-green-100 text-green-700
                     @elseif($album->categorie == 'formation') bg-blue-100 text-blue-700
                     @elseif($album->categorie == 'recolte') bg-yellow-100 text-yellow-700
                     @elseif($album->categorie == 'producteurs') bg-purple-100 text-purple-700
                     @else bg-gray-100 text-gray-700
                     @endif">
                        @if($album->categorie == 'terrain')  Terrain
                        @elseif($album->categorie == 'formation') Formation
                        @elseif($album->categorie == 'recolte') Récolte
                        @elseif($album->categorie == 'producteurs') Producteurs
                        @else  Événement
                        @endif
                    </span>
                </div>
                
                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                    <span><i class="far fa-calendar mr-1"></i>{{ $album->date_evenement->format('d/m/Y') }}</span>
                    <span class="mx-1">•</span>
                    @if($album->lieu)
                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($album->lieu, 20) }}</span>
                    @endif
                </div>
                
                <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($album->description ?? 'Aucune description', 80) }}</p>
                
                <div class="flex justify-between items-center pt-3 border-t">
                    <a href="{{ route('admin.albums.show', $album) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye mr-1"></i>Voir l'album
                    </a>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.albums.edit', $album) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.albums.destroy', $album) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer" onclick="return confirm('Supprimer définitivement cet album ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucun album pour le moment</p>
            <a href="{{ route('admin.albums.create') }}" class="inline-block mt-4 text-primary hover:underline">
                <i class="fas fa-plus mr-1"></i>Créer votre premier album
            </a>
        </div>
        @endforelse
    </div>
    
    <div class="p-6 border-t">
        {{ $albums->links() }}
    </div>
</div>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection