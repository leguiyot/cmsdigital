<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestImageServer extends Command
{
    protected $signature = 'imageserver:test {file?}';
    protected $description = 'Test uploading a file to the image server (local or remote)';

    public function handle()
    {
        $testFile = $this->argument('file') ?: base_path('storage/app/public/test-image.png');

        if (!file_exists($testFile)) {
            $this->error("Archivo no encontrado: {$testFile}");
            return 1;
        }

        $this->info("🔍 Testeando Image Server");
        $this->info("Archivo: {$testFile}");
        $this->info("URL base: " . env('IMAGE_SERVER_URL', 'no configurada'));
        $this->info("API Key: " . (env('IMAGE_SERVER_API_KEY') ? '***' : 'no configurada'));

        try {
            $this->info("\n📤 Enviando archivo al servidor...");
            
            // Usar cURL directamente sin UploadedFile
            $ch = curl_init();
            
            $cfile = curl_file_create(
                $testFile,
                mime_content_type($testFile) ?: 'application/octet-stream',
                basename($testFile)
            );

            $headers = ['X-API-Key: ' . env('IMAGE_SERVER_API_KEY', '')];

            // URL del endpoint PHP real (no la ruta de Laravel)
            $url = 'http://127.0.0.1:8000/imageserver/upload.php';

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ['file' => $cfile],
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL error: ' . $error);
            }

            $data = json_decode($response, true);
            
            if ($httpCode !== 201) {
                $msg = isset($data['error']) ? $data['error'] : 'Upload failed with HTTP ' . $httpCode;
                throw new \Exception($msg);
            }

            $this->info("\n✅ Upload exitoso!");
            $this->line("URL pública: " . $data['url']);
            $this->line("Ruta relativa: " . $data['path']);
            $this->line("Tamaño: " . ($data['size'] / 1024) . " KB");
            $this->line("MIME: " . $data['mime']);

            return 0;

        } catch (\Exception $e) {
            $this->error("\n❌ Error en upload: " . $e->getMessage());
            return 1;
        }
    }
}
