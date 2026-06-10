<div class="signatures-section mt-8">
    <h3 class="text-lg font-bold mb-4 border-t-2 pt-4">Signatures</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">

        @foreach ($signatures_config as $config)
            <div class="signature-box border-t-2 pt-2">
                <p class="font-semibold text-sm mb-2">{{ $config['label'] }}</p>
                
                @if (isset($signatures_existantes[$config['type']]))
                    @php
                        $signature = $signatures_existantes[$config['type']];
                    @endphp
                    <div class="border rounded-lg p-2 bg-gray-50">
                        <img src="{{ $signature['signature_data'] }}" alt="Signature" class="w-full h-24 object-contain">
                        <p class="text-xs text-gray-600 mt-2">Signé par : <strong>{{ $signature['signataire_nom'] }}</strong></p>
                        <p class="text-xs text-gray-500">Le : {{ $signature['signed_at'] }}</p>
                        @if(isset($signature['hash']))
                        <p class="text-xs text-gray-400 break-all">Hash: {{ $signature['hash'] }}</p>
                        @endif
                    </div>
                @else
                    <div class="border-dashed border-2 rounded-lg p-4 h-36 flex items-center justify-center">
                        <p class="text-gray-400 text-sm">Signature requise</p>
                    </div>
                @endif
            </div>
        @endforeach

    </div>
</div>

<div class="qr-code-section text-center mt-8 pt-4 border-t-2">
    @if(isset($qrCode) && $qrCode)
        <div class="inline-block p-4 border rounded-lg">
            <p class="text-sm font-semibold mb-2">Vérifiez l'authenticité de ce document</p>
            {!! $qrCode !!}
            <p class="text-xs text-gray-600 mt-2">Scannez ce QR code avec votre téléphone</p>
        </div>
    @endif
</div>
