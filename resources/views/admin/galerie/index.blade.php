@extends('layouts.admin')

@section('title', 'Galerie')
@section('header', 'Gestion de la galerie')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">Photos de la galerie</h2>
        <a href="{{ route('admin.galerie.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
            <i class="fas fa-plus mr-2"></i>Ajouter une photo
        </a>
    </div>
    
    <div class="p-6">
        @if($images->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($images as $image)
            <div class="group relative bg-gray-100 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('storage/'.$image->image) }}" 
                         alt="{{ $image->titre }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-4 bg-white">
                    <h3 class="font-semibold text-dark mb-1">{{ Str::limit($image->titre, 30) }}</h3>
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="fas fa-calendar mr-1"></i>{{ $image->date_prise->format('d/m/Y') }}
                        <span class="mx-1">•</span>
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $image->lieu ?? 'Non spécifié' }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs px-2 py-1 rounded-full 
                            @if($image->categorie == 'terrain') bg-green-100 text-green-700
                            @elseif($image->categorie == 'formation') bg-blue-100 text-blue-700
                            @elseif($image->categorie == 'recolte') bg-yellow-100 text-yellow-700
                            @elseif($image->categorie == 'producteurs') bg-purple-100 text-purple-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            @if($image->categorie == 'terrain')Terrain
                            @elseif($image->categorie == 'formation')Formation
                            @elseif($image->categorie == 'recolte')Récolte
                            @elseif($image->categorie == 'producteurs')Producteurs
                            @elseif($image->categorie == 'evenement')Événement
                            @else {{ $image->categorie }}
                            @endif
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.galerie.edit', $image) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.galerie.destroy', $image) }}" method="POST" class="inline delete-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="absolute top-2 right-2">
                    @if($image->est_publie)
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Publié</span>
                    @else
                        <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full">Brouillon</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $images->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune image dans la galerie</p>
            <a href="{{ route('admin.galerie.create') }}" class="inline-block mt-4 text-primary hover:underline">
                Ajouter votre première photo
            </a>
        </div>
        @endif
    </div>
</div>
@endsection