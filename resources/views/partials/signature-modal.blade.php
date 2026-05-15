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
                <button type="button" onclick="clearSignature()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eraser mr-1"></i>Effacer
                </button>
                <button type="button" onclick="saveSignature()" class="bg-primary text-white px-4 py-1 rounded-lg">
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
    let canvasInitialized = false;
    
    function initSignaturePad() {
        const canvas = document.getElementById('signatureCanvas');
        if (!canvas) {
            console.error('Canvas non trouvé');
            return false;
        }
        
        // Réinitialiser le canvas
        canvas.width = 400;
        canvas.height = 200;
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        
        // Initialiser SignaturePad
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 2
        });
        
        canvasInitialized = true;
        return true;
    }
    
    function openSignatureModal(type, documentId, documentType) {
        currentSignatureType = type;
        currentDocumentId = documentId;
        currentDocumentType = documentType;
        
        const modal = document.getElementById('signatureModal');
        if (!modal) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Initialiser le canvas après ouverture du modal
        setTimeout(() => {
            initSignaturePad();
        }, 100);
    }
    
    function closeSignatureModal() {
        const modal = document.getElementById('signatureModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        if (signaturePad) {
            signaturePad.clear();
            signaturePad = null;
        }
        canvasInitialized = false;
    }
    
    function clearSignature() {
        if (signaturePad) {
            signaturePad.clear();
        } else {
            const canvas = document.getElementById('signatureCanvas');
            if (canvas) {
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            }
        }
    }
    
    function saveSignature() {
        // Vérifier que signaturePad existe et n'est pas vide
        if (!signaturePad) {
            alert('Erreur: Initialisation de la signature');
            return;
        }
        
        if (signaturePad.isEmpty()) {
            alert('Veuillez signer dans la zone prévue');
            return;
        }
        
        // Récupérer la signature en dataURL
        const signatureData = signaturePad.toDataURL('image/png');
        
        if (!signatureData || signatureData === 'data:,') {
            alert('Erreur lors de la capture de la signature');
            return;
        }
        
        // Envoyer la signature au serveur
        fetch('/admin/signatures/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
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
                closeSignatureModal();
                location.reload();
            } else {
                alert('Erreur lors de l\'enregistrement: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion au serveur');
        });
    }
</script>