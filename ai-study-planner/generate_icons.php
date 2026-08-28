<?php
$src = __DIR__ . '/public/favicon.jpg';
if (file_exists($src)) {
    $img = imagecreatefromjpeg($src);
    if ($img !== false) {
        $resized192 = imagescale($img, 192, 192);
        imagepng($resized192, __DIR__ . '/public/icon-192.png');
        
        $resized512 = imagescale($img, 512, 512);
        imagepng($resized512, __DIR__ . '/public/icon-512.png');
        
        echo "Icons generated successfully.\n";
    } else {
        echo "Failed to load JPEG.\n";
    }
} else {
    echo "Source not found.\n";
}
