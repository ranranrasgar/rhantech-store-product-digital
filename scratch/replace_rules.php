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
    
    // Replace image rules
    $content = preg_replace('/\'image\|max:/', '\'image|mimes:jpeg,png,jpg,webp,gif|max:', $content);
    // Replace file rules
    $content = preg_replace('/\'nullable\|file\|max:102400/', '\'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx|max:102400', $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
