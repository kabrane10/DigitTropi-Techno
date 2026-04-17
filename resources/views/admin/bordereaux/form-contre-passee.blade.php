@extends('layouts.admin')

@section('title', 'Bordereau de contre-passée')
@section('header', 'Générer un bordereau de contre-passée')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-6 p-4 bg-red-50 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <div>
                <h3 class="font-semibold text-red-800">Attention !</h3>
                <p class="text-sm text-red-600">La contre-passée annule définitivement l'opération originale. Cette action est irréversible.</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('admin.bordereaux.generer-contre-passee') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Bordereau à annuler *</label>
                <select name="bordereau_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un bordereau</option>
                    @foreach($bordereaux as $b)
                    <option value="{{ $b->id }}">
                        {{ $b->code_bordereau }} - {{ $b->type_label }} - {{ $b->date_emission->format('d/m/Y') }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Motif de l'annulation *</label>
                <textarea name="motif" required rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Expliquez la raison de cette contre-passée..."></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations complémentaires</label>
                <textarea name="observations" rows="2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.bordereaux.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                <i class="fas fa-ban mr-2"></i>Générer la contre-passée
            </button>
        </div>
    </form>
</div>
@endsection