# Directorio de Uploads del CMS Digital

Este directorio almacena todas las imágenes y archivos multimedia subidos al CMS, organizados de manera similar a WordPress.

## Estructura de directorios

```
uploads/
├── articles/
│   ├── cover/          # Imágenes destacadas de artículos
│   │   ├── 2025/
│   │   │   ├── 01/     # Enero 2025
│   │   │   ├── 02/     # Febrero 2025
│   │   │   └── ...
│   │   └── conversions/ # Versiones redimensionadas
│   │       └── thumb/   # Miniaturas
│   │       └── medium/  # Tamaño medio
│   └── gallery/        # Imágenes de galería
│       ├── 2025/
│       └── conversions/
└── thumbnails/         # Miniaturas generadas automáticamente
```

## Características

- **Organización temporal**: Los archivos se organizan por año/mes automáticamente
- **Separación por tipo**: Cada tipo de imagen (destacada, galería) tiene su propia carpeta
- **Conversiones automáticas**: Se generan automáticamente thumbnails y versiones optimizadas
- **URLs directas**: Las imágenes son accesibles directamente vía `/uploads/...`
- **Gestión automática**: El sistema maneja automáticamente la creación y eliminación de archivos

## Acceso

- **URL base**: `https://tudominio.com/uploads/`
- **Ejemplo**: `https://tudominio.com/uploads/articles/cover/2025/11/imagen.jpg`

## Configuración

La configuración se maneja a través de:
- `config/filesystems.php` - Disco 'uploads'
- `config/media-library.php` - Configuración de Spatie Media Library
- `app/Support/MediaLibrary/CustomPathGenerator.php` - Lógica de organización de carpetas
