<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class StorageProxyController extends BaseController
{
    /**
     * Serve or publish a file from storage/app/public.
     * Strategy:
     *  - Try to copy the requested file to public/uploads/{path} the first time it's requested.
     *  - If copy succeeds, redirect to the static URL /uploads/{path} so the webserver serves it.
     *  - If copy fails (permissions), serve the file directly via PHP response.
     *
     * This approach avoids needing `php artisan storage:link` or terminal access.
     */
    public function show($path)
    {
        // Basic sanitization to avoid traversal
        $path = str_replace(["..", "~"], '', $path);

        $base = realpath(storage_path('app/public'));
        if ($base === false) {
            abort(500, 'Storage base path not available');
        }

        $full = realpath($base . DIRECTORY_SEPARATOR . $path);

        if ($full === false || strpos($full, $base) !== 0) {
            abort(404);
        }

        if (!is_file($full) || !is_readable($full)) {
            abort(404);
        }

        // Destination in public/uploads
        $publicUploadsBase = public_path('uploads');
        $targetFull = $publicUploadsBase . DIRECTORY_SEPARATOR . $path;

        // If file already published, redirect to static URL
        if (file_exists($targetFull)) {
            $publicUrl = url('uploads/' . ltrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/'));
            return redirect($publicUrl, 302);
        }

        // Ensure target directory exists
        $targetDir = dirname($targetFull);
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        // Try copy
        $copied = false;
        try {
            if (@copy($full, $targetFull)) {
                @chmod($targetFull, 0644);
                $copied = true;
            }
        } catch (\Throwable $e) {
            $copied = false;
        }

        if ($copied) {
            $publicUrl = url('uploads/' . ltrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/'));
            return redirect($publicUrl, 302);
        }

        // Fallback: serve directly via PHP
        $mime = @mime_content_type($full) ?: 'application/octet-stream';
        return response()->file($full, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
