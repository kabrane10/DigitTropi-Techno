@extends('layouts.animateur')

@section('title', 'Détails agent')
@section('header', 'Fiche agent')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4"><div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-user-circle text-primary text-5xl"></i></div><h3 class="text-xl font-bold">{{ $agent->nom_complet }}</h3><p class="text-gray-500">{{ $agent->code_agent }}</p></div>
            <div class="space-y-3"><div class="flex items-center"><i class="fas fa-envelope w-8"></i><span>{{ $agent->email }}</span></div><div class="flex items-center"><i class="fas fa-phone w-8"></i><span>{{ $agent->telephone }}</span></div><div class="flex items-center"><i class="fas fa-map-marker-alt w-8"></i><span>{{ $agent->zone_affectation }}</span></div><div class="flex items-center"><i class="fas fa-calendar w-8"></i><span>Embauché le {{ $agent->date_embauche->format('d/m/Y') }}</span></div><div class="flex items-center"><i class="fas fa-users w-8"></i><span>{{ number_format($agent->producteurs_enregistres) }} producteurs</span></div></div>
            <div class="mt-6 pt-4 border-t flex justify-between"><a href="{{ route('animateur.agents.edit', $agent) }}" class="text-green-600"><i class="fas fa-edit mr-1"></i>Modifier</a><button onclick="openResetModal({{ $agent->id }}, '{{ addslashes($agent->nom_complet) }}')" class="text-orange-600"><i class="fas fa-key mr-1"></i>Réinitialiser MDP</button></div>
        </div>
    </div>
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-lg font-semibold mb-4">Producteurs enregistrés</h3>@if($producteurs->count()>0)<div class="space-y-3">@foreach($producteurs as $p)<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg"><div><p class="font-medium">{{ $p->nom_complet }}</p><p class="text-xs text-gray-500">{{ $p->code_producteur }}</p></div><div class="text-right"><p class="text-sm">{{ number_format($p->superficie_totale,2) }} ha</p></div></div>@endforeach</div><div class="mt-4">{{ $producteurs->links() }}</div>@else<p class="text-gray-500 text-center">Aucun producteur</p>@endif</div>
    </div>
</div>
<script>function openResetModal(id,name){document.getElementById('resetModal').classList.remove('hidden');document.getElementById('resetMessage').innerHTML='Nouveau mot de passe pour <strong>'+name+'</strong>';document.getElementById('resetForm').action="/animateur/agents/"+id+"/reset-password";}function closeResetModal(){document.getElementById('resetModal').classList.add('hidden');}</script>
<div id="resetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"><div class="bg-white rounded-xl p-6 max-w-md w-full"><h3 class="text-xl font-bold mb-4">Réinitialiser mot de passe</h3><p id="resetMessage" class="text-gray-600 mb-4"></p><form id="resetForm" method="POST"><div class="mb-4"><input type="password" name="password" placeholder="Nouveau mot de passe" class="w-full px-3 py-2 border rounded-lg"></div><div class="flex justify-end space-x-3"><button type="button" onclick="closeResetModal()" class="px-4 py-2 border rounded-lg">Annuler</button><button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg">Réinitialiser</button></div></form></div></div>
@endsection