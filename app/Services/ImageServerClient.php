<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * Cliente para subir archivos al servidor de imágenes remoto (imageserver_upload.php)
 * 
 * Uso:
 * $imageServer = new ImageServerClient(env('IMAGE_SERVER_URL'), env('IMAGE_SERVER_API_KEY'));
 * $response = $imageServer->upload($file);
 * // response: ['url' => '...', 'path' => 'uploads/2025/11/...', 'size' => 123456]
 */
class ImageServerClient
{
    private $baseUrl;
    private $apiKey;

    public function __construct($baseUrl = null, $apiKey = null)
    {
        $this->baseUrl = $baseUrl ?: env('IMAGE_SERVER_URL', 'http://images.local/imageserver');
        $this->apiKey = $apiKey ?: env('IMAGE_SERVER_API_KEY', '');
    }

    /**
     * Subir archivo al servidor de imágenes
     * 
     * @param UploadedFile $file
     * @return array|false Response o false si falla
     * @throws \Exception
     */
    public function upload(UploadedFile $file)
    {
        if (!$this->apiKey) {
            throw new \Exception('IMAGE_SERVER_API_KEY no configurada');
        }

        $url = rtrim($this->baseUrl, '/') . '/upload.php';

        // Usar cURL para enviar archivo
        $ch = curl_init();
        
        $cfile = curl_file_create(
            $file->getRealPath(),
            $file->getMimeType(),
            $file->getClientOriginalName()
        );

        $headers = ['X-API-Key: ' . $this->apiKey];
        
        // En desarrollo, añadir CSRF token si está disponible (solo para ruta local)
        if (config('app.env') === 'local' && function_exists('csrf_token')) {
            $headers[] = 'X-CSRF-TOKEN: ' . csrf_token();
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['file' => $cfile],
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false, // Ajusta según certificados (producción: true)
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
            $msg = $data['error'] ?? 'Upload failed with HTTP ' . $httpCode;
            throw new \Exception($msg);
        }

        return $data;
    }

    /**
     * Obtener URL completa de un archivo
     */
    public function getUrl($path)
    {
        $base = rtrim($this->baseUrl, '/');
        return $base . '/' . ltrim($path, '/');
    }
}
