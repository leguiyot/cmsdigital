<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TestMediaShow extends Command
{
    protected $signature = 'media:test-show {id}';
    protected $description = 'Test media show functionality for specific ID';

    public function handle()
    {
        $id = $this->argument('id');
        
        try {
            $media = Media::findOrFail($id);
            
            $this->info("=== TESTING MEDIA SHOW FOR ID: {$id} ===");
            
            $this->line("File Name: {$media->file_name}");
            $this->line("Mime Type: {$media->mime_type}");
            $this->line("Disk: " . ($media->disk ?: 'NULL'));
            $this->line("Collection: {$media->collection_name}");
            $this->line("Created: {$media->created_at}");
            
            // Test URLs
            $this->info("\n=== TESTING URLs ===");
            
            try {
                $spatieUrl = $media->getUrl();
                $this->line("✓ Spatie getUrl(): {$spatieUrl}");
            } catch (\Exception $e) {
                $this->error("✗ Spatie getUrl() failed: " . $e->getMessage());
            }
            
            // Manual URL
            $manualUrl = url('/uploads/articles/' . $media->collection_name . '/' . $media->created_at->format('Y/m') . '/' . $media->file_name);
            $this->line("Manual URL: {$manualUrl}");
            
            // Check file existence
            $filePath = public_path('uploads/articles/' . $media->collection_name . '/' . $media->created_at->format('Y/m') . '/' . $media->file_name);
            $exists = file_exists($filePath);
            $this->line("File Path: {$filePath}");
            $this->line("File Exists: " . ($exists ? 'YES' : 'NO'));
            
            if (!$exists) {
                $this->warn("File does not exist at expected location!");
                
                // Try to find the file
                $this->info("Searching for file...");
                $searchPath = public_path('uploads');
                if (is_dir($searchPath)) {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($searchPath));
                    foreach ($iterator as $file) {
                        if ($file->getFilename() === $media->file_name) {
                            $this->line("Found at: " . $file->getPathname());
                        }
                    }
                }
            }
            
            // Test controller access
            $this->info("\n=== TESTING CONTROLLER ===");
            $controller = new \App\Http\Controllers\MediaGalleryController();
            $request = new \Illuminate\Http\Request();
            
            try {
                $response = $controller->show($media);
                $this->line("✓ Controller show() works");
            } catch (\Exception $e) {
                $this->error("✗ Controller show() failed: " . $e->getMessage());
            }
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
