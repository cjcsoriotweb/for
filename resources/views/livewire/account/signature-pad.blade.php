<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Zone de signature
            </h3>
            @if($hasSignature)
                <button wire:click="deleteSignature"
                        class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Supprimer
                </button>
            @endif
        </div>

        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
            <canvas id="signature-canvas"
                    class="w-full h-64 border border-gray-200 dark:border-gray-600 rounded bg-white"
                    style="cursor: crosshair; touch-action: none;"></canvas>
        </div>

        <div class="flex flex-wrap gap-3 mt-4">
            <button wire:click="clearSignature"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Effacer
            </button>

            <button onclick="saveSignature()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enregistrer la signature
            </button>
        </div>
    </div>

    @if($hasSignature)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Aperçu de votre signature
            </h3>
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                <img src="{{ $signatureData }}" alt="Votre signature" class="max-w-full h-auto">
            </div>
        </div>
    @endif
</div>

<script>
console.log('Script loaded - initializing signature canvas...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up canvas...');
    initSignatureCanvas();
});

document.addEventListener('livewire:loaded', function() {
    console.log('Livewire loaded, setting up canvas...');
    initSignatureCanvas();
});

function initSignatureCanvas() {
    const canvas = document.getElementById('signature-canvas');
    console.log('Canvas element found:', canvas);

    if (!canvas) {
        console.error('Canvas element not found!');
        return;
    }

    const ctx = canvas.getContext('2d');
    console.log('Canvas context:', ctx);

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Configuration du canvas
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    // Définir la taille du canvas
    function setCanvasSize() {
        const rect = canvas.getBoundingClientRect();
        const devicePixelRatio = window.devicePixelRatio || 1;

        // Définir la taille affichée
        canvas.style.width = rect.width + 'px';
        canvas.style.height = rect.height + 'px';

        // Définir la taille réelle du canvas (pour haute résolution)
        canvas.width = rect.width * devicePixelRatio;
        canvas.height = rect.height * devicePixelRatio;

        // Ajuster l'échelle du contexte
        ctx.scale(devicePixelRatio, devicePixelRatio);

        console.log('Canvas size set to:', canvas.width, 'x', canvas.height, 'display:', rect.width, 'x', rect.height);
    }

    setCanvasSize();

    // Fonctions de dessin
    function startDrawing(e) {
        console.log('Starting drawing...', e.type);
        isDrawing = true;
        const coords = getCoordinates(e);
        lastX = coords.x;
        lastY = coords.y;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
    }

    function draw(e) {
        if (!isDrawing) return;
        console.log('Drawing...', e.type);

        const coords = getCoordinates(e);
        ctx.lineTo(coords.x, coords.y);
        ctx.stroke();

        lastX = coords.x;
        lastY = coords.y;
    }

    function stopDrawing() {
        console.log('Stopping drawing...');
        isDrawing = false;
    }

    function getCoordinates(e) {
        const rect = canvas.getBoundingClientRect();
        let clientX, clientY;

        if (e.touches && e.touches[0]) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else {
            clientX = e.clientX;
            clientY = e.clientY;
        }

        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    // Gestion des événements souris
    canvas.addEventListener('mousedown', function(e) {
        console.log('Mouse down event');
        startDrawing(e);
    });

    canvas.addEventListener('mousemove', function(e) {
        if (isDrawing) {
            draw(e);
        }
    });

    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Gestion des événements tactiles
    canvas.addEventListener('touchstart', function(e) {
        console.log('Touch start event');
        e.preventDefault();
        startDrawing(e);
    });

    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        if (isDrawing) {
            draw(e);
        }
    });

    canvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        stopDrawing();
    });

    // Fonction pour effacer le canvas
    window.clearSignatureCanvas = function() {
        console.log('Clearing canvas');
        // Remettre la bonne taille du canvas avant d'effacer
        setCanvasSize();
        ctx.clearRect(0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1));
    };

    // Fonction pour obtenir les données de la signature
    window.getSignatureData = function() {
        return canvas.toDataURL('image/png');
    };

    console.log('Canvas initialization complete');
}

// Fonction appelée par le bouton de sauvegarde
function saveSignature() {
    console.log('Save signature called');
    const canvas = document.getElementById('signature-canvas');
    if (!canvas) {
        console.error('Canvas not found for saving');
        return;
    }

    // Vérifier si le canvas a du contenu
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;

    // Vérifier si le canvas n'est pas vide (chercher des pixels non transparents)
    let hasContent = false;
    for (let i = 0; i < data.length; i += 4) {
        if (data[i + 3] > 0) { // Alpha channel > 0
            hasContent = true;
            break;
        }
    }

    console.log('Canvas has content:', hasContent);

    if (!hasContent) {
        alert('Veuillez dessiner votre signature avant de l\'enregistrer.');
        return;
    }

    // Récupérer les données de la signature
    const signatureData = canvas.toDataURL('image/png');
    console.log('Signature data length:', signatureData.length);

    // Envoyer les données au composant Livewire
    const wireElement = document.querySelector('[wire\\:id]');
    if (wireElement) {
        const wireId = wireElement.getAttribute('wire:id');
        console.log('Sending to Livewire component:', wireId);
        Livewire.find(wireId).call('saveSignature', signatureData);
    } else {
        console.error('Livewire component not found');
    }
}
</script>
