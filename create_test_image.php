<?php

// Script para generar imagen PNG de prueba
$width = 400;
$height = 300;

// Crear imagen
$image = imagecreatetruecolor($width, $height);

// Colores
$backgroundColor = imagecolorallocate($image, 230, 230, 250); // Azul claro
$textColor = imagecolorallocate($image, 0, 0, 128); // Azul oscuro
$borderColor = imagecolorallocate($image, 0, 0, 0); // Negro

// Llenar fondo
imagefill($image, 0, 0, $backgroundColor);

// Dibujar borde
imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

// Añadir texto
$font = 2;
$text = "Test Image Server";
$textX = ($width - strlen($text) * imagefontwidth($font)) / 2;
$textY = ($height - imagefontheight($font)) / 2;
imagestring($image, $font, $textX, $textY, $text, $textColor);

// Timestamp en la esquina
$timestamp = "Created: " . date('Y-m-d H:i:s');
imagestring($image, 1, 10, $height - 20, $timestamp, $textColor);

// Guardar
$outputPath = __DIR__ . '/storage/app/public/test-image.png';
@mkdir(dirname($outputPath), 0755, true);
imagepng($image, $outputPath);
imagedestroy($image);

echo "✅ Imagen de prueba creada: {$outputPath}\n";
