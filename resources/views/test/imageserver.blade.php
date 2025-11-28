<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Image Server</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-12 px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">🖼️ Test Image Server</h1>

            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                <h2 class="font-semibold text-blue-900 mb-2">Configuración actual:</h2>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li><strong>IMAGE_SERVER_URL:</strong> {{ env('IMAGE_SERVER_URL', 'no configurada') }}</li>
                    <li><strong>IMAGE_SERVER_API_KEY:</strong> {{ env('IMAGE_SERVER_API_KEY') ? '✓ Configurada' : '✗ No configurada' }}</li>
                    <li><strong>Entorno:</strong> {{ config('app.env') }}</li>
                </ul>
            </div>

            <form id="uploadForm" class="space-y-4">
                @csrf

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer"
                     onclick="document.getElementById('fileInput').click()">
                    <input type="file" id="fileInput" name="file" class="hidden" accept="image/*,video/*" />
                    <div class="text-gray-600">
                        <p class="text-lg font-semibold mb-2">Arrastra un archivo aquí o haz clic</p>
                        <p class="text-sm">Soportados: JPG, PNG, WebP, GIF, MP4, WebM</p>
                    </div>
                </div>

                <div id="filePreview" class="hidden">
                    <p class="text-sm text-gray-600">
                        Archivo seleccionado: <strong id="fileName"></strong>
                        (<span id="fileSize"></span> MB)
                    </p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                        id="submitBtn">
                    📤 Subir archivo
                </button>
            </form>

            <div id="resultContainer" class="hidden mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Resultado:</h2>

                <div id="successResult" class="hidden p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h3 class="font-semibold text-green-900 mb-3">✅ Upload exitoso!</h3>
                    <div class="space-y-2 text-sm text-green-800">
                        <p><strong>URL pública:</strong></p>
                        <input type="text" id="resultUrl" readonly class="w-full p-2 bg-white border border-green-300 rounded text-xs" />

                        <p class="mt-2"><strong>Información:</strong></p>
                        <pre id="resultJson" class="p-2 bg-white border border-green-300 rounded text-xs overflow-auto max-h-40"></pre>
                    </div>
                </div>

                <div id="errorResult" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="font-semibold text-red-900 mb-2">❌ Error en upload:</h3>
                    <p id="errorMessage" class="text-sm text-red-800"></p>
                </div>

                <div id="loadingResult" class="hidden p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">⏳ Subiendo archivo...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('uploadForm');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const resultContainer = document.getElementById('resultContainer');
        const submitBtn = document.getElementById('submitBtn');

        // Mostrar archivo seleccionado
        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                const file = this.files[0];
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = (file.size / (1024 * 1024)).toFixed(2);
                filePreview.classList.remove('hidden');
            }
        });

        // Enviar formulario
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                alert('Por favor selecciona un archivo');
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            showLoading();

            try {
                const response = await fetch('{{ route("test.imageserver.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    },
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data.data);
                } else {
                    showError(data.error || 'Error desconocido');
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            }
        });

        function showLoading() {
            resultContainer.classList.remove('hidden');
            document.getElementById('loadingResult').classList.remove('hidden');
            document.getElementById('successResult').classList.add('hidden');
            document.getElementById('errorResult').classList.add('hidden');
            submitBtn.disabled = true;
        }

        function showSuccess(data) {
            resultContainer.classList.remove('hidden');
            document.getElementById('loadingResult').classList.add('hidden');
            document.getElementById('errorResult').classList.add('hidden');
            document.getElementById('successResult').classList.remove('hidden');

            document.getElementById('resultUrl').value = data.url;
            document.getElementById('resultJson').textContent = JSON.stringify(data, null, 2);

            submitBtn.disabled = false;
            form.reset();
            filePreview.classList.add('hidden');
        }

        function showError(message) {
            resultContainer.classList.remove('hidden');
            document.getElementById('loadingResult').classList.add('hidden');
            document.getElementById('successResult').classList.add('hidden');
            document.getElementById('errorResult').classList.remove('hidden');

            document.getElementById('errorMessage').textContent = message;
            submitBtn.disabled = false;
        }
    </script>
</body>
</html>
