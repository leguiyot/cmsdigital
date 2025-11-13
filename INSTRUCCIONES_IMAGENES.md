# 📸 GUÍA COMPLETA PARA ORGANIZAR IMÁGENES DEL CMS

## 🎯 Resumen
Tu CMS necesita 9 imágenes específicas colocadas en carpetas exactas para funcionar correctamente.

## 📁 Estructura de Carpetas Requerida
```
public/uploads/articles/
├── gallery/
│   └── 2025/
│       └── 11/
│           ├── admin-ajax.png
│           ├── conversions/
│           │   └── admin-ajax-thumb.png
│           └── responsive/
└── cover/
    └── 2025/
        └── 11/
            ├── 89039.jpg
            ├── logo.png  
            ├── logotwiX.jpg
            ├── 17752.jpg
            ├── 2149426530.jpg
            ├── 104200.jpg
            ├── 84.jpg
            ├── 42503.jpg
            ├── conversions/
            │   ├── [archivo]-thumb.jpg (miniaturas)
            └── responsive/
                └── [archivo]-responsive.jpg
```

## 🏷️ Mapeo de Tus Imágenes

| # | Descripción | Nombre Final | Ubicación | Formato |
|---|-------------|--------------|-----------|---------|
| 1 | Imagen médica (doctor) | `admin-ajax.png` | `/gallery/2025/11/` | PNG |
| 2 | Interior del vehículo | `89039.jpg` | `/cover/2025/11/` | JPG |
| 3 | Gráficos financieros | `104200.jpg` | `/cover/2025/11/` | JPG |
| 4 | Globo conectado | `2149426530.jpg` | `/cover/2025/11/` | JPG |
| 5 | Persona estudiando | `17752.jpg` | `/cover/2025/11/` | JPG |
| 6 | Mano robótica AI | `84.jpg` | `/cover/2025/11/` | JPG |
| 7 | Logo "ndi" | `logo.png` | `/cover/2025/11/` | PNG |
| 8 | Cosecha de uvas | `42503.jpg` | `/cover/2025/11/` | JPG |
| 9 | Imagen adicional | `logotwiX.jpg` | `/cover/2025/11/` | JPG |

## 🚀 Pasos para Implementar

### 1. Descargar tus imágenes
- Guarda cada imagen desde los adjuntos que enviaste

### 2. Renombrar archivos
- Renombra cada imagen con el nombre exacto de la tabla de arriba
- **Importante**: Usa exactamente los nombres mostrados (mayúsculas/minúsculas)

### 3. Crear estructura de carpetas
En tu hosting o servidor local, crea:
```bash
mkdir -p public/uploads/articles/gallery/2025/11/conversions/
mkdir -p public/uploads/articles/gallery/2025/11/responsive/
mkdir -p public/uploads/articles/cover/2025/11/conversions/
mkdir -p public/uploads/articles/cover/2025/11/responsive/
```

### 4. Subir archivos
- Coloca cada imagen en su carpeta correspondiente según la tabla

### 5. Verificar permisos
```bash
chmod 755 public/uploads/articles/
chmod 755 public/uploads/articles/*/
chmod 644 public/uploads/articles/*/*.jpg
chmod 644 public/uploads/articles/*/*.png
```

## 🔍 Verificación

### Desde el servidor
```bash
php verify_media_files.php
```

### Desde el navegador
- `https://tudominio.com/uploads/articles/cover/2025/11/89039.jpg`
- `https://tudominio.com/uploads/articles/gallery/2025/11/admin-ajax.png`

## 🌐 Para Hosting en Producción

### 1. Subir vía FTP/SFTP
- Usar FileZilla, WinSCP o similar
- Navegar a `public_html/uploads/articles/`
- Crear la estructura de carpetas
- Subir las imágenes renombradas

### 2. Verificar URLs
- Todas las imágenes deben ser accesibles vía HTTP
- Sin errores 404

### 3. Configurar .htaccess (si es necesario)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^uploads/(.*)$ public/uploads/$1 [L]
</IfModule>
```

## ✅ Resultado Final
- Las imágenes aparecerán automáticamente en tu CMS
- Se crearán thumbnails automáticamente cuando se necesiten
- Las URLs serán accesibles públicamente
- El sistema de medios funcionará correctamente

## 🆘 Solución de Problemas

### Imagen no aparece
1. Verificar nombre exacto del archivo
2. Verificar ruta correcta
3. Verificar permisos de archivo/carpeta
4. Comprobar que la imagen sea accesible vía HTTP

### Error de permisos
```bash
chmod -R 755 public/uploads/
chmod -R 644 public/uploads/articles/*/*.jpg
chmod -R 644 public/uploads/articles/*/*.png
```

### Verificar configuración Laravel
```bash
php artisan config:clear
php artisan cache:clear
```

---
**Fecha de creación**: 7 de noviembre de 2025  
**Sistema**: CMS Digital Laravel