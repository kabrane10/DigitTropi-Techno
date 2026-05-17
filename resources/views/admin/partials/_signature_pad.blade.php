{{-- resources/views/admin/partials/_signature_pad.blade.php --}}

<div class="signature-section mb-6" id="signature-section-{{ $type }}">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-semibold text-gray-700">
            <i class="fas fa-pen-fancy text-primary mr-1"></i> 
            Signature numérique - {{ $label }}
        </label>
        <div class="flex gap-2">
            <button type="button" class="clear-signature text-xs text-red-500 hover:text-red-700" data-target="{{ $type }}">
                <i class="fas fa-eraser mr-1"></i>Effacer
            </button>
        </div>
    </div>
    
    <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-white">
        <canvas id="signature-canvas-{{ $type }}" 
                class="signature-canvas w-full"
                width="600" 
                height="200"
                style="width: 100%; height: 200px; touch-action: none; cursor: crosshair;">
        </canvas>
    </div>
    
    <div class="flex justify-between items-center mt-2">
        <p class="text-xs text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Dessinez votre signature avec la souris ou le doigt
        </p>
        <div class="flex gap-2">
            <button type="button" class="validate-signature text-xs bg-primary text-white px-3 py-1 rounded-lg hover:bg-secondary transition" data-target="{{ $type }}">
                <i class="fas fa-check-circle mr-1"></i>Valider
            </button>
            <input type="hidden" name="signature_{{ $type }}" id="signature-data-{{ $type }}">
            <input type="hidden" name="signature_{{ $type }}_commentaire" id="signature-commentaire-{{ $type }}">
        </div>
    </div>
    
    <div id="signature-preview-{{ $type }}" class="mt-3 hidden">
        <div class="bg-green-50 rounded-lg p-3 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-2"></i>
                <div>
                    <p class="text-sm font-semibold text-green-700">Signature enregistrée</p>
                    <p class="text-xs text-green-600" id="signature-date-{{ $type }}"></p>
                </div>
            </div>
            <button type="button" class="re-signature text-sm text-primary hover:underline" data-target="{{ $type }}">
                <i class="fas fa-redo-alt mr-1"></i>Re-signer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des pads de signature
    const signaturePads = {};
    
    @foreach($signatures_config ?? [] as $config)
    const canvas{{ $loop->index }} = document.getElementById('signature-canvas-{{ $config['type'] }}');
    if (canvas{{ $loop->index }}) {
        signaturePads['{{ $config['type'] }}'] = new SignaturePad(canvas{{ $loop->index }}, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 51, 102)',
            velocityFilterWeight: 0.7,
            minWidth: 1,
            maxWidth: 3
        });
        
        // Ajuster la taille du canvas
        function resizeCanvas{{ $loop->index }}() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const canvas = canvas{{ $loop->index }};
            if (canvas) {
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePads['{{ $config['type'] }}'].clear();
            }
        }
        
        resizeCanvas{{ $loop->index }}();
        window.addEventListener('resize', resizeCanvas{{ $loop->index }});
        
        // Bouton effacer
        document.querySelector(`.clear-signature[data-target="{{ $config['type'] }}"]`)?.addEventListener('click', () => {
            signaturePads['{{ $config['type'] }}'].clear();
            document.getElementById('signature-preview-{{ $config['type'] }}')?.classList.add('hidden');
            document.getElementById('signature-data-{{ $config['type'] }}').value = '';
        });
        
        // Bouton valider
        document.querySelector(`.validate-signature[data-target="{{ $config['type'] }}"]`)?.addEventListener('click', () => {
            if (!signaturePads['{{ $config['type'] }}'].isEmpty()) {
                const signatureData = signaturePads['{{ $config['type'] }}'].toDataURL();
                document.getElementById('signature-data-{{ $config['type'] }}').value = signatureData;
                document.getElementById('signature-date-{{ $config['type'] }}').innerHTML = new Date().toLocaleString('fr-FR');
                document.getElementById('signature-preview-{{ $config['type'] }}')?.classList.remove('hidden');
                
                // Afficher un toast de confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Signature enregistrée',
                    text: 'La signature a été enregistrée avec succès',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Signature vide',
                    text: 'Veuillez dessiner votre signature avant de valider',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        });
        
        // Bouton re-signer
        document.querySelector(`.re-signature[data-target="{{ $config['type'] }}"]`)?.addEventListener('click', () => {
            signaturePads['{{ $config['type'] }}'].clear();
            document.getElementById('signature-preview-{{ $config['type'] }}').classList.add('hidden');
            document.getElementById('signature-data-{{ $config['type'] }}').value = '';
        });
        
        // Si signature déjà existante (mode édition)
        @if(isset($signatures_existantes[$config['type']]))
            const existingSig = @json($signatures_existantes[$config['type']]);
            if (existingSig) {
                document.getElementById('signature-preview-{{ $config['type'] }}')?.classList.remove('hidden');
                document.getElementById('signature-date-{{ $config['type'] }}').innerHTML = existingSig.signed_at;
                document.getElementById('signature-data-{{ $config['type'] }}').value = existingSig.signature_data;
            }
        @endif
    }
    @endforeach
});
</script>
@endpush

<style>
.signature-canvas {
    border: none;
    background: white;
    touch-action: none;
}
.signature-canvas:focus {
    outline: none;
}
</style>