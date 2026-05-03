@extends('layouts.admin')

@section('title', 'Gestion des actualités')
@section('header', 'Actualités')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">Liste des actualités</h2>
        <a href="{{ route('admin.actualites.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvelle actualité
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($actualites as $actualite)
                <tr>
                    <td class="px-6 py-4">
                        @if($actualite->image_couverture)
                        {{-- J'ai modifié la ligne ci-dessous pour afficher l'image depuis Cloudinary.
                         Anciennement : <img src="{{ asset('storage/' . $actualite->image_couverture) }}" ...>
                         Maintenant, j'utilise directement l'URL de l'image qui est maintenant stockée dans la base de données. --}}
                            <img src="{{ $actualite->image_couverture }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium">{{ Str::limit($actualite->titre, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $actualite->categorie }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $actualite->date_publication->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $actualite->est_publie ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $actualite->est_publie ? 'Publié' : 'Brouillon' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.actualites.edit', $actualite) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.actualites.destroy', $actualite) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $actualites->links() }}
    </div>
</div>
@endsection