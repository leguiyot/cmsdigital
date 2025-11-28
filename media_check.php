<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;

echo "=== MEDIA / STORAGE CHECK ===\n";

// Check public/storage junction
$pubStorage = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'storage';
echo "public/storage path: {$pubStorage}\n";
if (file_exists($pubStorage)) {
    echo "public/storage exists: YES\n";
    echo "is_dir: " . (is_dir($pubStorage) ? 'YES' : 'NO') . "\n";
} else {
    echo "public/storage exists: NO\n";
}

echo "\n-- Disk config (filesystems) --\n";
$diskDefault = config('filesystems.default');
$disks = config('filesystems.disks');
echo "default disk: {$diskDefault}\n";
if (isset($disks['public'])) {
    echo "public disk root: " . ($disks['public']['root'] ?? 'N/A') . "\n";
    echo "public disk url: " . ($disks['public']['url'] ?? 'N/A') . "\n";
}

echo "\n-- Count media entries --\n";
$total = Media::count();
echo "Total media rows: {$total}\n";

$collections = ['cover','gallery','videos'];
foreach ($collections as $col) {
    $count = Media::where('collection_name', $col)->count();
    echo "Collection '{$col}': {$count}\n";
}

echo "\n-- Listing first 100 media entries (collection, disk, path, exists?) --\n";
$medias = Media::orderBy('id')->take(100)->get();
foreach ($medias as $m) {
    $path = $m->getPath();
    $exists = file_exists($path) ? 'YES' : 'NO';
    $size = $exists ? filesize($path) : 0;
    $url = $m->getUrl();
    echo "[{$m->id}] {$m->file_name} | collection: {$m->collection_name} | disk: {$m->disk} | path: {$path} | exists: {$exists} | size: {$size} | url: {$url}\n";
}

// Check sample filesystem for images/videos under storage/app/public
$storagePublic = storage_path('app/public');
echo "\n-- Files under storage/app/public (first 200 entries) --\n";
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storagePublic, RecursiveDirectoryIterator::SKIP_DOTS));
$count=0;
foreach ($it as $file) {
    $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp','gif','mp4','mov','avi','webm'])) {
        echo $file->getPathname() . " | " . $file->getSize() . " bytes\n";
        $count++;
        if ($count >= 200) break;
    }
}

if ($count===0) echo "No multimedia files found under storage/app/public (or directory empty).\n";

echo "\n=== END CHECK ===\n";
