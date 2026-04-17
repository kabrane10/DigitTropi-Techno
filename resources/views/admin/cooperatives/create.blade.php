@extends('layouts.admin')

@section('title', 'Nouvelle coopérative')
@section('header', 'Créer une coopérative')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.cooperatives.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de la coopérative *</label>
                <input type="text" name="nom" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="Centrale">Centrale</option>
                    <option value="Kara">Kara</option>
                    <option value="Savanes">Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Localisation *</label>
                <input type="text" name="localisation" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de création *</label>
                <input type="date" name="date_creation" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cooperatives.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection