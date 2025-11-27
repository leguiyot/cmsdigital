@echo off
REM Script de Deployment para CMS Digital (Windows)
REM Este script automatiza la preparación del proyecto para producción

echo =========================================
echo   CMS Digital - Deployment Script
echo =========================================
echo.

echo Paso 1: Limpiando caches...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
echo [OK] Caches limpiados
echo.

echo Paso 2: Instalando dependencias de produccion...
call composer install --optimize-autoloader --no-dev
echo [OK] Dependencias de Composer instaladas
echo.

echo Paso 3: Compilando assets...
call npm run build
echo [OK] Assets compilados
echo.

echo Paso 4: Optimizando para produccion...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
call php artisan event:cache
echo [OK] Optimizaciones aplicadas
echo.

echo =========================================
echo [OK] Deployment completado exitosamente!
echo =========================================
echo.
echo RECORDATORIOS IMPORTANTES:
echo 1. Crear archivo .env en el servidor con configuracion de produccion
echo 2. Ejecutar: php artisan key:generate
echo 3. Ejecutar: php artisan migrate --force
echo 4. Ejecutar: php artisan storage:link
echo 5. Crear usuario administrador
echo 6. Verificar que APP_DEBUG=false en .env
echo 7. Configurar SSL/HTTPS
echo.
pause
