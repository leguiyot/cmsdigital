<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controlador para simular/servir como Image Server local en desarrollo
 * Permite testear la integración sin desplegar a hosting externo
 */
class ImageServerSimulatorController extends Controller
{
    /**
     * POST /imageserver/upload - recibir uploads
     */
    public function upload(Request $request)
    {
        try {
            // Validar API key
            $apiKey = $request->header('X-API-Key');
            if ($apiKey !== env('IMAGE_SERVER_API_KEY')) {
                return response()->json(['error' => 'Unauthorized: Invalid API key'], 401);
            }

            // Validar archivo
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');

            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file'], 400);
            }

            // Crear directorio público/uploads
            $year = date('Y');
            $month = date('m');
            $subdir = public_path('uploads/' . $year . '/' . $month);

            if (!is_dir($subdir)) {
                @mkdir($subdir, 0755, true);
            }

            // Generar nombre único
            $randomName = bin2hex(random_bytes(8));
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = $randomName . '.' . $ext;

            // Guardar archivo
            $path = $file->move($subdir, $filename);
            @chmod($path, 0644);

            // Construir URL pública
            $relativePath = 'uploads/' . $year . '/' . $month . '/' . $filename;
            $publicUrl = url($relativePath);

            return response()->json([
                'status' => 'success',
                'url' => $publicUrl,
                'path' => $relativePath,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'filename' => $filename,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /imageserver - información del servidor
     */
    public function info()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Image Server Simulator (Local Dev)',
            'version' => '1.0',
            'max_file_size' => '40 MB',
            'allowed_types' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm', 'mov', 'avi'],
            'environment' => config('app.env'),
        ]);
    }
}
