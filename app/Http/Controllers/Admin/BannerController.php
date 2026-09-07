<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all()->keyBy('position');
        return view('admin.banners.index', compact('banners'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048',
            'banners.*.link' => 'nullable|url',
        ]);

        foreach (['main', 'side_1', 'side_2'] as $position) {
            $banner = Banner::firstOrNew(['position' => $position]);

            // Handle Base64 cropped image upload
            if ($request->filled("banners.{$position}.image_base64")) {
                $base64Image = $request->input("banners.{$position}.image_base64");
                
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, etc.
                    
                    if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $base64Image = base64_decode(str_replace(' ', '+', $base64Image));
                        
                        if ($base64Image !== false) {
                            if ($banner->image_path) {
                                Storage::disk('public')->delete($banner->image_path);
                            }
                            
                            $fileName = 'banners/' . $position . '_' . time() . '.' . $type;
                            Storage::disk('public')->put($fileName, $base64Image);
                            $banner->image_path = $fileName;
                        }
                    }
                }
            }

            // Fallback for standard file upload if JS fails
            if ($request->hasFile("banners.{$position}.image")) {
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                $path = $request->file("banners.{$position}.image")->store('banners', 'public');
                $banner->image_path = $path;
            }

            $banner->link = $request->input("banners.{$position}.link");
            $banner->save();
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui!');
    }
}
