<?php

$f = 'app/Http/Controllers/Admin/BannerController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
        foreach (['main', 'side_1', 'side_2'] as \$position) {
            \$banner = Banner::firstOrNew(['position' => \$position]);

            if (\$request->hasFile("banners.{\$position}.image")) {
                if (\$banner->image_path && Storage::disk('public')->exists(\$banner->image_path)) {
                    Storage::disk('public')->delete(\$banner->image_path);
                }
                \$path = \$request->file("banners.{\$position}.image")->store('banners', 'public');
                \$banner->image_path = \$path;
            }

            \$banner->link = \$request->input("banners.{\$position}.link");
            \$banner->save();
        }
PHP;

$newLogic = <<<PHP
        foreach (['main', 'side_1', 'side_2'] as \$position) {
            \$banner = Banner::firstOrNew(['position' => \$position]);

            // Handle Base64 cropped image upload
            if (\$request->filled("banners.{\$position}.image_base64")) {
                \$base64Image = \$request->input("banners.{\$position}.image_base64");
                
                if (preg_match('/^data:image\/(\w+);base64,/', \$base64Image, \$type)) {
                    \$base64Image = substr(\$base64Image, strpos(\$base64Image, ',') + 1);
                    \$type = strtolower(\$type[1]); // jpg, png, etc.
                    
                    if (in_array(\$type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        \$base64Image = base64_decode(str_replace(' ', '+', \$base64Image));
                        
                        if (\$base64Image !== false) {
                            if (\$banner->image_path && Storage::disk('public')->exists(\$banner->image_path)) {
                                Storage::disk('public')->delete(\$banner->image_path);
                            }
                            
                            \$fileName = 'banners/' . \$position . '_' . time() . '.' . \$type;
                            Storage::disk('public')->put(\$fileName, \$base64Image);
                            \$banner->image_path = \$fileName;
                        }
                    }
                }
            }

            // Fallback for standard file upload if JS fails
            if (\$request->hasFile("banners.{\$position}.image")) {
                if (\$banner->image_path && Storage::disk('public')->exists(\$banner->image_path)) {
                    Storage::disk('public')->delete(\$banner->image_path);
                }
                \$path = \$request->file("banners.{\$position}.image")->store('banners', 'public');
                \$banner->image_path = \$path;
            }

            \$banner->link = \$request->input("banners.{\$position}.link");
            \$banner->save();
        }
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Successfully updated BannerController.\n";
