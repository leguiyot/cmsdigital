# Servidor de Imágenes Ligero (Image Server)

Sistema PHP puro para almacenar y servir imágenes/videos del CMS sin depender de un symlink o terminal en el hosting.

## Características

- ✅ Sin dependencias externas (PHP puro)
- ✅ Validación de MIME type y tamaño (40MB máx)
- ✅ Protección por API Key
- ✅ Almacenamiento organizado por año/mes
- ✅ Fácil de desplegar por FTP
- ✅ Log de uploads y errores
- ✅ CORS habilitado (opcional)

## Archivos

- `imageserver_upload.php` — Servidor de imágenes (copiar a hosting como `upload.php`)
- `app/Services/ImageServerClient.php` — Cliente Laravel para subir archivos
- `IMAGESERVER_SETUP.md` — Este archivo (instrucciones de despliegue)

## Despliegue por FTP

### 1. Preparar estructura en el hosting

En tu hosting (por FTP o el administrador de archivos cPanel), crea esta estructura:

```
/home/usuario/public_html/
  ├── imageserver/               ← Crear esta carpeta
  │   ├── upload.php             ← Subir imageserver_upload.php aquí como upload.php
  │   ├── uploads/               ← Crear carpeta (permisos 755)
  │   └── upload_log.txt         ← Se creará automáticamente
  └── ...resto del CMS
```

### 2. Subir archivos por FTP

1. Crea carpeta `/imageserver` en la raíz del sitio.
2. Sube `imageserver_upload.php` **dentro de esa carpeta** y renómbralo a `upload.php`.
3. Crea carpeta `uploads` dentro de `/imageserver` con permisos `755`.
4. Asegúrate que la carpeta `/imageserver` tiene permisos `755`.

### 3. Configurar .env del CMS

En el archivo `.env` del CMS principal, añade:

```env
# Image Server configuration
IMAGE_SERVER_URL=https://tudominio.com/imageserver
IMAGE_SERVER_API_KEY=una_clave_segura_aleatoria_cambiar_esto
```

**Importante:** Cambiar `IMAGE_SERVER_API_KEY` a una cadena segura y aleatoria. Por ejemplo:
```bash
# Linux/Mac:
php -r "echo bin2hex(random_bytes(16));"

# O manualmente: algo como: 7f8a9b2c5d3e1a4f9b6c2e5a8d1f3b7c
```

También actualiza el valor en `imageserver_upload.php` si no usas .env en el servidor (aunque se recomienda usar .env o variables de entorno).

### 4. Configurar DocumentRoot (si usas subdominio — opcional)

Si quieres que `https://images.tudominio.com` sea una URL limpia:

- Crear subdominio `images` en cPanel/panel.
- Apuntar DocumentRoot a `/home/usuario/public_html/imageserver/public` (si cambias la estructura) o a `/home/usuario/public_html/imageserver` directamente.
- Luego la URL sería más limpia.

Si no puedes cambiar DocumentRoot, la URL será `https://tudominio.com/imageserver/upload.php` y está bien.

## Uso

### Desde el CMS (Laravel)

```php
use App\Services\ImageServerClient;

// En controlador o servicio:
$imageServer = new ImageServerClient();

try {
    $response = $imageServer->upload($request->file('image'));
    
    // $response contiene:
    // [
    //   'url' => 'https://tudominio.com/imageserver/uploads/2025/11/abc123def456.jpg',
    //   'path' => 'uploads/2025/11/abc123def456.jpg',
    //   'mime' => 'image/jpeg',
    //   'size' => 123456,
    //   'filename' => 'abc123def456.jpg'
    // ]
    
    // Guardar $response['url'] en la BD o usar con Spatie Media Library
    
} catch (\Exception $e) {
    return back()->with('error', 'Upload failed: ' . $e->getMessage());
}
```

### Probar manualmente (cURL)

```bash
# GET — información del servidor
curl https://tudominio.com/imageserver/upload.php

# POST — subir archivo
curl -X POST https://tudominio.com/imageserver/upload.php \
  -H "X-API-Key: tu_clave_api" \
  -F "file=@/ruta/a/imagen.jpg"

# Respuesta exitosa (201):
# {"status":"success","url":"https://...","path":"uploads/2025/11/...","size":123456}
```

### Descargar archivo (GET)

Las imágenes se sirven estáticas (el servidor web las sirve directamente):

```
https://tudominio.com/imageserver/uploads/2025/11/abc123def456.jpg
```

O vía PHP (si no se pueden servir estáticas):

```
https://tudominio.com/imageserver/upload.php?file=uploads/2025/11/abc123def456.jpg
```

## Seguridad

- **API Key:** Cambiar `IMAGE_SERVER_API_KEY` a valor seguro y aleatorio. Solo quien tenga esta clave puede subir.
- **Tipos permitidos:** Solo jpg, png, webp, gif (imágenes) y mp4, webm, mov, avi (videos). Editar `ALLOWED_MIMES` para cambiar.
- **Tamaño máximo:** 40MB por archivo (modificar `MAX_FILE_SIZE`).
- **HTTPS:** Usar HTTPS en producción (reemplazar `http://` con `https://` en `IMAGE_SERVER_URL`).
- **Permisos:** Carpeta `uploads` debe tener `755` (lectura/escritura para servidor, lectura para otros).

## Logs

Los uploads se registran en `/imageserver/upload_log.txt`:

```
2025-11-27 14:32:01 - UPLOAD_SUCCESS - {"file":"imagen.jpg","size":123456,"path":"uploads/2025/11/...","ip":"192.168.1.1"}
2025-11-27 14:32:05 - UPLOAD_DENIED - {"ip":"192.168.1.2","reason":"Invalid API key"}
```

Puedes usar esto para auditoría o debugging.

## Migración de archivos existentes

Para copiar archivos de `storage/app/public` al image server:

```php
// Script migracion_media.php en la raíz del CMS
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Services\ImageServerClient;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

$imageServer = new ImageServerClient();

$medias = Media::limit(100)->get(); // Ejemplo: primeros 100
foreach ($medias as $m) {
    $localPath = $m->getPath();
    if (!file_exists($localPath)) continue;
    
    try {
        // Simular UploadedFile
        $file = new Illuminate\Http\UploadedFile(
            $localPath,
            $m->file_name,
            $m->mime_type
        );
        
        $response = $imageServer->upload($file);
        echo "✓ Migrado: " . $m->file_name . " → " . $response['url'] . PHP_EOL;
        
        // Opcional: actualizar URL en BD si guardas la URL
        // $m->update(['url' => $response['url']]);
        
    } catch (\Exception $e) {
        echo "✗ Error: " . $m->file_name . " - " . $e->getMessage() . PHP_EOL;
    }
}
```

Ejecutar: `php migracion_media.php`

## Troubleshooting

| Problema | Solución |
|----------|----------|
| 401 Unauthorized | Verificar que `X-API-Key` es correcta (en `.env` del CMS y `upload.php`) |
| 413 Payload Too Large | El archivo excede 40MB o el limite de PHP (`upload_max_filesize`). Ajustar en php.ini o `MAX_FILE_SIZE` en script. |
| 415 Unsupported Media Type | Tipo MIME no permitido. Editar `ALLOWED_MIMES` en `upload.php`. |
| No se guarda archivo | Verificar permisos de carpeta `uploads` (debe ser 755). Revisar `upload_log.txt`. |
| cURL error en cliente | Verificar que `SSL_VERIFYPEER` sea `false` en desarrollo o certificados válidos en producción. |

## Próximos pasos

1. ✅ Desplegar `upload.php` en el hosting por FTP.
2. ✅ Configurar `.env` con `IMAGE_SERVER_URL` e `IMAGE_SERVER_API_KEY`.
3. ✅ Probar upload con cURL.
4. ✅ Integrar `ImageServerClient` en `ArticleController` o donde subes archivos.
5. ✅ Opcional: migrar archivos existentes con script.
6. ✅ Opcional: añadir resize/transformación con Intervention/Image o Glide.

## Opciones futuras

- Procesar imágenes (resize, quality) en el servidor de imágenes.
- Integrar con Cloudflare Image Resizing.
- Mover a S3/Spaces cuando escale.
- Dashboard de logs y estadísticas.
