<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actualite->titre }} - Tropi-Techno Sarl</title>
    <meta name="description" content="{{ $actualite->excerpt }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

@include('partials.navbar')

<!-- Article -->
<section class="pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Catégorie et date -->
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-primary/10 text-primary text-sm px-4 py-1 rounded-full">
                @if($actualite->categorie == 'campagne') Campagne
                @elseif($actualite->categorie == 'evenement') Événement
                @elseif($actualite->categorie == 'formation') Formation
                @else 📰 Annonce
                @endif
            </span>
            <span class="text-gray-500 text-sm"><i class="far fa-calendar mr-2"></i>{{ $actualite->date_publication->format('d F Y') }}</span>
        </div>
        
        <!-- Titre -->
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-dark mb-6">{{ $actualite->titre }}</h1>
        
        <!-- Image de couverture -->
        @if($actualite->image_couverture)
        <div class="rounded-2xl overflow-hidden shadow-xl mb-8">
            <img src="{{ asset('storage/'.$actualite->image_couverture) }}" alt="{{ $actualite->titre }}" class="w-full">
        </div>
        @endif
        
        <!-- Informations complémentaires -->
        @if($actualite->lieu || $actualite->date_fin)
        <div class="bg-gray-100 rounded-xl p-4 mb-8 flex flex-wrap gap-4">
            @if($actualite->lieu)
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-primary"></i>
                <span class="text-gray-700">{{ $actualite->lieu }}</span>
            </div>
            @endif
            @if($actualite->date_fin)
            <div class="flex items-center gap-2">
                <i class="fas fa-hourglass-half text-primary"></i>
                <span class="text-gray-700">Jusqu'au {{ $actualite->date_fin->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Contenu -->
        <div class="prose prose-lg max-w-none">
            {!! nl2br(e($actualite->contenu)) !!}
        </div>
        
        <!-- Partage -->
        <div class="border-t border-gray-200 mt-8 pt-8">
            <p class="text-gray-600 mb-3">Partager cet article :</p>
            <div class="flex gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 bg-[#1877f2] text-white rounded-full flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($actualite->titre) }}" target="_blank" class="w-10 h-10 bg-[#1da1f2] text-white rounded-full flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($actualite->titre . ' - ' . url()->current()) }}" target="_blank" class="w-10 h-10 bg-[#25d366] text-white rounded-full flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Actualités récentes -->
@if($actualites_recentes->count() > 0)
<section class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-dark mb-8">Articles récents</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($actualites_recentes as $recente)
            <a href="{{ route('actualite.show', $recente->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all group">
                @if($recente->image_couverture)
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('storage/'.$recente->image_couverture) }}" alt="{{ $recente->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-bold text-dark mb-2 group-hover:text-primary transition">{{ $recente->titre }}</h3>
                    <p class="text-gray-500 text-sm">{{ $recente->date_publication->format('d/m/Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.footer')

<style>
    .prose {
        font-family: 'Inter', sans-serif;
        color: #374151;
        line-height: 1.75;
    }
    .prose p {
        margin-bottom: 1.25rem;
    }
    .prose h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1b4332;
    }
    .prose ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
</style>
</body>
</html>