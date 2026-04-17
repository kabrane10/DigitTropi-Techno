@extends('layouts.admin')

@section('title', 'Modifier coopérative')
@section('header', 'Modifier une coopérative')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.cooperatives.update', $cooperative) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de la coopérative *</label>
                <input type="text" name="nom" required value="{{ old('nom', $cooperative->nom) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Code coopérative</label>
                <input type="text" value="{{ $cooperative->code_cooperative }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact', $cooperative->contact) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $cooperative->email) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ $cooperative->region == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ $cooperative->region == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ $cooperative->region == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Localisation *</label>
                <input type="text" name="localisation" required value="{{ old('localisation', $cooperative->localisation) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de création *</label>
                <input type="date" name="date_creation" required value="{{ old('date_creation', $cooperative->date_creation->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="active" {{ $cooperative->statut == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspendue" {{ $cooperative->statut == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('description', $cooperative->description) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cooperatives.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection