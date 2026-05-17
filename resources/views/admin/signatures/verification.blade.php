{{-- resources/views/admin/signatures/verification.blade.php --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de signature - Tropi-Techno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 text-white text-center">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <h1 class="text-xl font-bold">Signature Authentique</h1>
            <p class="text-xs opacity-80">Document certifié par Tropi-Techno Sarl</p>
        </div>
        
        <!-- Contenu -->
        <div class="p-6">
            <!-- Badge de validation -->
            <div class="bg-green-50 rounded-lg p-3 mb-4 text-center">
                <i class="fas fa-shield-alt text-green-600 text-2xl mb-1"></i>
                <p class="text-green-700 font-semibold">Signature vérifiée et authentique</p>
                <p class="text-xs text-green-600">Empreinte cryptographique valide</p>
            </div>
            
            <!-- Informations -->
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500 text-sm">Document</span>
                    <span class="font-semibold text-sm">{{ ucfirst($signature->document_type) }} #{{ $signature->document_id }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500 text-sm">Signataire</span>
                    <span class="font-semibold text-sm">{{ $signature->signataire_nom }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500 text-sm">Date de signature</span>
                    <span class="font-semibold text-sm">{{ $signature->signed_at->format('d/m/Y à H:i:s') }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500 text-sm">Adresse IP</span>
                    <span class="font-mono text-xs">{{ $signature->ip_address }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500 text-sm">Hash unique</span>
                    <span class="font-mono text-xs">{{ substr($signature->hash_unique, 0, 20) }}...</span>
                </div>
            </div>
            
            <!-- Signature visuelle -->
            <div class="mt-6 border rounded-lg p-4 bg-gray-50">
                <p class="text-xs text-gray-500 mb-2">Signature apposée :</p>
                @if(str_starts_with($signature->signature_data, 'data:image'))
                    <img src="{{ $signature->signature_data }}" class="max-h-24 mx-auto border rounded bg-white">
                @else
                    <img src="{{ Storage::url($signature->signature_data) }}" class="max-h-24 mx-auto border rounded bg-white">
                @endif
            </div>
            
            <!-- Commentaire -->
            @if($signature->commentaire)
            <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                <p class="text-xs text-yellow-700"><i class="fas fa-comment mr-1"></i> {{ $signature->commentaire }}</p>
            </div>
            @endif
            
            <!-- QR Code de vérification -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500 mb-2">Scannez ce QR code pour vérifier à nouveau</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('admin.signatures.verifier', $signature->hash_unique)) }}" class="mx-auto">
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 p-3 text-center text-xs text-gray-500 border-t">
            <i class="fas fa-lock mr-1"></i> Document certifié électroniquement
        </div>
    </div>
</body>
</html>