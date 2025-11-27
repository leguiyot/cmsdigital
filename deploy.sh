#!/bin/bash

# Script de Deployment para CMS Digital
# Este script automatiza la preparación del proyecto para producción

echo "========================================="
echo "  CMS Digital - Deployment Script"
echo "========================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para mostrar mensajes
function success() {
    echo -e "${GREEN}✓${NC} $1"
}

function warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

function error() {
    echo -e "${RED}✗${NC} $1"
}

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    error "Error: Este script debe ejecutarse desde el directorio raíz del proyecto Laravel"
    exit 1
fi

echo "Paso 1: Limpiando cachés..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
success "Cachés limpiados"

echo ""
echo "Paso 2: Instalando dependencias de producción..."
composer install --optimize-autoloader --no-dev
success "Dependencias de Composer instaladas"

echo ""
echo "Paso 3: Compilando assets..."
npm run build
success "Assets compilados"

echo ""
echo "Paso 4: Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
success "Optimizaciones aplicadas"

echo ""
echo "Paso 5: Verificando permisos..."
chmod -R 775 storage bootstrap/cache
success "Permisos configurados"

echo ""
echo "========================================="
success "Deployment completado exitosamente!"
echo "========================================="
echo ""
warning "RECORDATORIOS IMPORTANTES:"
echo "1. Crear archivo .env en el servidor con configuración de producción"
echo "2. Ejecutar: php artisan key:generate"
echo "3. Ejecutar: php artisan migrate --force"
echo "4. Ejecutar: php artisan storage:link"
echo "5. Crear usuario administrador"
echo "6. Verificar que APP_DEBUG=false en .env"
echo "7. Configurar SSL/HTTPS"
echo ""
