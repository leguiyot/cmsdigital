<?php
/**
 * Servidor ligero de imágenes/archivos en PHP puro (sin dependencias)
 * 
 * Uso:
 * - POST /imageserver/upload.php con multipart/form-data
 * - GET /imageserver/uploads/{año}/{mes}/{archivo}
 * 
 * Seguridad:
 * - Requiere header X-API-Key válida (configurar en env o .env)
 * - Valida extensiones y tamaño de archivo
 * - Sanitiza nombres de archivo
 * - Bloquea acceso a archivos fuera de uploads/
 * 
 * Pasos para desplegar por FTP:
 * 1. Crea carpeta /imageserver en la raíz del hosting (p. ej. /home/usuario/public_html/imageserver)
 * 2. Sube este archivo como upload.php dentro de /imageserver
 * 3. Crea carpeta /imageserver/uploads con permisos 755
 * 4. Configura .env (ver abajo)
 * 5. Prueba accediendo a http://tudominio.com/imageserver/upload.php (GET devuelve info)
 */

// Cargar variables de entorno desde .env
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\"'");
            putenv("$name=$value");
        }
    }
}

// Configuración
define('API_KEY', getenv('IMAGE_SERVER_API_KEY') ?: 'cambiar_esta_clave_segura');
define('MAX_FILE_SIZE', 40 * 1024 * 1024); // 40MB
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('ALLOWED_MIMES', [
    'image/jpeg' => ['jpg', 'jpeg'],
    'image/png' => ['png'],
    'image/webp' => ['webp'],
    'image/gif' => ['gif'],
    'video/mp4' => ['mp4'],
    'video/webm' => ['webm'],
    'video/quicktime' => ['mov'],
    'video/x-msvideo' => ['avi'],
]);

// Crear directorio si no existe
if (!is_dir(UPLOAD_DIR)) {
    @mkdir(UPLOAD_DIR, 0755, true);
}

// CORS headers (opcional, comenta si no quieres)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');

// Handle OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Logging helper
function logAction($action, $details = []) {
    $log = __DIR__ . '/upload_log.txt';
    $message = date('Y-m-d H:i:s') . ' - ' . $action;
    if (!empty($details)) {
        $message .= ' - ' . json_encode($details);
    }
    @file_put_contents($log, $message . PHP_EOL, FILE_APPEND);
}

// JSON response helper
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// GET - información del servidor o descarga de archivo
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Si no hay path, devolver info
    if (empty($_GET['file'])) {
        jsonResponse([
            'status' => 'ok',
            'service' => 'Image Server',
            'version' => '1.0',
            'max_file_size' => MAX_FILE_SIZE / (1024 * 1024) . ' MB',
            'allowed_types' => array_keys(ALLOWED_MIMES),
            'upload_url' => 'POST /imageserver/upload.php',
        ]);
    }

    // Servir archivo (GET /imageserver/upload.php?file=uploads/2025/11/imagen.jpg)
    $filePath = $_GET['file'];
    
    // Sanitizar y prevenir traversal
    $filePath = str_replace(['..', '~', "\0"], '', $filePath);
    $fullPath = realpath(UPLOAD_DIR . '/../' . $filePath);
    $uploadBase = realpath(UPLOAD_DIR);

    if ($fullPath === false || strpos($fullPath, $uploadBase) !== 0) {
        jsonResponse(['error' => 'File not found or access denied'], 404);
    }

    if (!is_file($fullPath) || !is_readable($fullPath)) {
        jsonResponse(['error' => 'File not found'], 404);
    }

    $mime = @mime_content_type($fullPath) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=31536000, immutable');
    readfile($fullPath);
    exit;
}

// POST - upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Autenticación por API key
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($apiKey !== API_KEY) {
        logAction('UPLOAD_DENIED', ['ip' => $_SERVER['REMOTE_ADDR'], 'reason' => 'Invalid API key']);
        jsonResponse(['error' => 'Unauthorized: Invalid API key'], 401);
    }

    // Validar file presente
    if (empty($_FILES['file'])) {
        jsonResponse(['error' => 'No file uploaded'], 400);
    }

    $file = $_FILES['file'];

    // Validar upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'Partial upload',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temp directory',
            UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
            UPLOAD_ERR_EXTENSION => 'PHP extension blocked',
        ];
        $errMsg = $errors[$file['error']] ?? 'Unknown upload error';
        logAction('UPLOAD_ERROR', ['error' => $errMsg, 'file' => $file['name']]);
        jsonResponse(['error' => $errMsg], 400);
    }

    // Validar tamaño
    if ($file['size'] > MAX_FILE_SIZE) {
        logAction('UPLOAD_TOO_LARGE', ['file' => $file['name'], 'size' => $file['size']]);
        jsonResponse(['error' => 'File too large (max ' . (MAX_FILE_SIZE / (1024 * 1024)) . ' MB)'], 413);
    }

    // Validar MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(ALLOWED_MIMES[$mime])) {
        logAction('UPLOAD_INVALID_MIME', ['file' => $file['name'], 'mime' => $mime]);
        jsonResponse(['error' => 'File type not allowed: ' . $mime], 415);
    }

    // Obtener extensión desde MIME permitida (no confiar en client)
    $allowedExts = ALLOWED_MIMES[$mime];
    $clientExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $ext = in_array($clientExt, $allowedExts) ? $clientExt : $allowedExts[0];

    // Generar nombre único
    $year = date('Y');
    $month = date('m');
    $randomName = bin2hex(random_bytes(8));
    $filename = $randomName . '.' . $ext;

    // Crear directorio anual/mensual
    $subdir = UPLOAD_DIR . '/' . $year . '/' . $month;
    if (!is_dir($subdir)) {
        if (!@mkdir($subdir, 0755, true)) {
            logAction('MKDIR_FAILED', ['path' => $subdir]);
            jsonResponse(['error' => 'Cannot create upload directory'], 500);
        }
    }

    $targetPath = $subdir . '/' . $filename;

    // Mover archivo temporal a destino
    if (!@move_uploaded_file($file['tmp_name'], $targetPath)) {
        logAction('UPLOAD_MOVE_FAILED', ['file' => $file['name'], 'target' => $targetPath]);
        jsonResponse(['error' => 'Failed to save file'], 500);
    }

    // Asegurar permisos
    @chmod($targetPath, 0644);

    // Generar URL pública
    // Opción 1 (si este script está en raíz de tu dominio):
    // $publicUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/imageserver/uploads/' . $year . '/' . $month . '/' . $filename;
    
    // Opción 2 (desarrollo local - HTTP sin HTTPS):
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $publicUrl = $protocol . $_SERVER['HTTP_HOST'] . '/imageserver/uploads/' . $year . '/' . $month . '/' . $filename;
    
    // Opción 3 (si es un subdominio):
    // $publicUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $year . '/' . $month . '/' . $filename;
    
    // Opción 4 (si quieres ruta relativa):
    // $publicUrl = '/imageserver/uploads/' . $year . '/' . $month . '/' . $filename;

    // Log éxito
    $relativePath = 'uploads/' . $year . '/' . $month . '/' . $filename;
    logAction('UPLOAD_SUCCESS', ['file' => $file['name'], 'size' => $file['size'], 'path' => $relativePath, 'ip' => $_SERVER['REMOTE_ADDR']]);

    jsonResponse([
        'status' => 'success',
        'url' => $publicUrl,
        'path' => $relativePath,
        'mime' => $mime,
        'size' => $file['size'],
        'filename' => $filename,
    ], 201);
}

// Método no permitido
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
?>
