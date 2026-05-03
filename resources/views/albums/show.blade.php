@extends('layouts.app')

@section('title', $album->titre)
@section('description', $album->description)

@section('content')
<div class="container px-4 py-12 mx-auto">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-dark mb-4">{{ $album->titre }}</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $album->description }}</p>
        <div class="mt-4 text-gray-500 text-sm">
            <i class="fas fa-calendar-alt mr-2"></i>{{ $album->date_evenement->format('d F Y') }}
            @if($album->lieu)
                <span class="mx-2">|</span>
                <i class="fas fa-map-marker-alt mr-2"></i>{{ $album->lieu }}
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($album->images as $image)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                <div class="relative">
                    <img src="{{ $image->image }}" alt="{{ $image->titre }}" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                         <button onclick="openImageModal('{{ $image->image }}', '{{ e($image->titre) }}', '{{ e($image->description) }}')" class="text-white text-4xl">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>
                @if($image->titre)
                <div class="p-4">
                    <h3 class="font-bold text-lg text-dark truncate">{{ $image->titre }}</h3>
                    @if($image->description)
                    <p class="text-gray-600 text-sm mt-1 truncate">{{ $image->description }}</p>
                    @endif
                </div>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <p class="text-gray-500 text-lg">Aucune image dans cet album pour le moment.</p>
            </div>
        @endforelse
    </div>

    <div class="text-center mt-12">
        <a href="{{ route('galerie') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-secondary transition transform hover:scale-105">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à la galerie
        </a>
    </div>
</div>

<!-- Modal pour l'image -->
<div id="imageModal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-3xl hover:text-gray-300">
            &times;
        </button>
        <img id="modalImage" src="" alt="" class="modal-img w-full h-full object-contain rounded-lg">
        <div id="modalCaption" class="text-center text-white mt-2 p-4"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openImageModal(src, title, description) {
        document.getElementById('modalImage').src = src;
        let caption = `<div class="font-bold text-xl">${title}</div>`;
        if (description) {
            caption += `<div class="text-base mt-1">${description}</div>`;
        }
        document.getElementById('modalCaption').innerHTML = caption;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
