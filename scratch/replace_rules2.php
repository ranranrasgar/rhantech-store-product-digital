<?php
$files = [];
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers'));
foreach ($iter as $file) {
    if ($file->getExtension() === 'php') {
        $files[] = $file->getPathname();
    }
}

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Replace nullable|image|max
    $content = preg_replace('/\'nullable\|image\|max:/', '\'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:', $content);
    $content = preg_replace('/\'image\|max:/', '\'image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:', $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
