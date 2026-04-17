@extends('layouts.agent')

@section('title', 'Nouveau producteur')
@section('header', 'Enregistrer un producteur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.producteurs.store') }}" method="POST">
        @csrf
        
        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Numéro de téléphone">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="Centrale" {{ old('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ old('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ old('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Localisation *</label>
                <input type="text" name="localisation" required value="{{ old('localisation') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Village, quartier...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Culture pratiquée *</label>
                <input type="text" name="culture_pratiquee" required value="{{ old('culture_pratiquee') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Maïs, Soja, Arachide...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie totale (ha) *</label>
                <input type="number" step="0.01" name="superficie_totale" required value="{{ old('superficie_totale') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Coopérative</label>
                <select name="cooperative_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucune</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" {{ old('cooperative_id') == $cooperative->id ? 'selected' : '' }}>
                        {{ $cooperative->nom }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.producteurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection