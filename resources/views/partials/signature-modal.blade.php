<!-- Modal pour signature numérique -->
<div id="signatureModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold">Signature numérique</h3>
            <button onclick="closeSignatureModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <p class="text-gray-600 mb-4">Signez dans la zone ci-dessous :</p>
            <canvas id="signatureCanvas" width="400" height="200" style="border: 2px solid #ddd; border-radius: 8px; width: 100%; height: auto; background: white;"></canvas>
            <div class="flex justify-between mt-3">
                <button onclick="clearSignature()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eraser mr-1"></i>Effacer
                </button>
                <button onclick="saveSignature()" class="bg-primary text-white px-4 py-1 rounded-lg">
                    Valider la signature
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad = null;
    let currentSignatureType = null;
    let currentDocumentId = null;
    let currentDocumentType = null;
    
    function openSignatureModal(type, documentId, documentType) {
        currentSignatureType = type;
        currentDocumentId = documentId;
        currentDocumentType = documentType;
        
        const modal = document.getElementById('signatureModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Initialiser SignaturePad
        const canvas = document.getElementById('signatureCanvas');
        canvas.width = 400;
        canvas.height = 200;
        signaturePad = new SignaturePad(canvas);
    }
    
    function closeSignatureModal() {
        const modal = document.getElementById('signatureModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (signaturePad) signaturePad.clear();
    }
    
    function clearSignature() {
        if (signaturePad) signaturePad.clear();
    }
    
    function saveSignature() {
        if (signaturePad && !signaturePad.isEmpty()) {
            const signatureData = signaturePad.toDataURL();
            
            fetch('/admin/signatures/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    document_type: currentDocumentType,
                    document_id: currentDocumentId,
                    signataire_type: currentSignatureType,
                    signature_data: signatureData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de l\'enregistrement de la signature');
                }
            });
        } else {
            alert('Veuillez signer dans la zone prévue');
        }
    }
</script>