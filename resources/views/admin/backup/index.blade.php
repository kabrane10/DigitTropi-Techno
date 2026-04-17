@extends('layouts.admin')

@section('title', 'Sauvegardes')
@section('header', 'Gestion des sauvegardes')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-semibold">Sauvegardes de la base de données</h2>
            <p class="text-sm text-gray-500 mt-1">Gérez vos sauvegardes automatiques et manuelles</p>
        </div>
        <form action="{{ route('admin.backup.run') }}" method="POST">
            @csrf
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-database mr-2"></i>Lancer une sauvegarde
            </button>
        </form>
    </div>
    
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
            <div>
                <p class="font-semibold text-blue-800">Planification automatique</p>
                <p class="text-sm text-blue-700">Une sauvegarde automatique est effectuée tous les jours à 02h00. Conservation des 7 dernières sauvegardes.</p>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Nom du fichier</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date de création</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Taille</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($backups as $backup)
                <tr>
                    <td class="px-4 py-3 text-sm font-mono">{{ $backup['name'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $backup['date'] }}</td>
                    <td class="px-4 py-3 text-right text-sm">{{ $backup['size'] }}</td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <a href="{{ route('admin.backup.download', $backup['name']) }}" class="text-green-600 hover:text-green-800" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </a>
                        <form action="{{ route('admin.backup.delete', $backup['name']) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-database text-4xl mb-2 opacity-50"></i>
                        <p>Aucune sauvegarde disponible</p>
                        <p class="text-sm mt-1">Cliquez sur "Lancer une sauvegarde" pour créer votre première sauvegarde</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection