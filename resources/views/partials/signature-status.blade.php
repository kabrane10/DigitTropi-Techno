@props(['document', 'type' => 'credit'])

<div class="signature-status">
    @php
        $signatureProd = $document->getSignatureFrom('producteur');
        $signatureAgent = $document->getSignatureFrom('agent');
        $allSigned = $document->areAllSignaturesPresent(['producteur', 'agent']);
    @endphp
    
    <div class="flex items-center gap-4">
        <div class="signature-badge {{ $signatureProd ? 'signed' : 'missing' }}">
            <i class="fas {{ $signatureProd ? 'fa-check-circle' : 'fa-clock' }}"></i>
            <span>Producteur {{ $signatureProd ? 'signé' : 'non signé' }}</span>
        </div>
        <div class="signature-badge {{ $signatureAgent ? 'signed' : 'missing' }}">
            <i class="fas {{ $signatureAgent ? 'fa-check-circle' : 'fa-clock' }}"></i>
            <span>Agent {{ $signatureAgent ? 'signé' : 'non signé' }}</span>
        </div>
        @if($allSigned)
            <div class="signature-badge signed">
                <i class="fas fa-check-double"></i>
                <span>Document complet</span>
            </div>
        @endif
    </div>
</div>