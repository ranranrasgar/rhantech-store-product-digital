<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        return view('tenant.appearance.index', compact('store'));
    }
    public function update(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'components'        => 'present|array',
            'header_banner'     => 'nullable|string',
            'store_mode'        => 'nullable|in:store,profile,hybrid',
            'profile_links'     => 'nullable|array',
            'voucher_placement' => 'nullable|array',
        ]);

        $rawComponents = $request->input('components', []);
        $cleanComponents = [];
        if (is_array($rawComponents)) {
            foreach ($rawComponents as $c) {
                if (is_array($c) && !empty($c['type'])) {
                    $cleanComponents[] = [
                        'id'   => (string)($c['id'] ?? uniqid()),
                        'type' => (string)$c['type'],
                        'data' => is_array($c['data'] ?? null) ? $c['data'] : [],
                    ];
                }
            }
        }

        // Preserve or update voucher placement in appearance_data
        $currentAppearance = is_array($store->appearance_data) ? $store->appearance_data : [];
        if ($request->has('voucher_placement')) {
            $cleanComponents['voucher_placement'] = $request->input('voucher_placement');
        } elseif (isset($currentAppearance['voucher_placement'])) {
            $cleanComponents['voucher_placement'] = $currentAppearance['voucher_placement'];
        }

        $store->appearance_data = $cleanComponents;

        if ($request->has('header_banner')) {
            $bannerVal = trim($request->input('header_banner') ?? '');
            $store->banner = !empty($bannerVal) ? $bannerVal : null;
        }

        if ($request->has('store_mode')) {
            $store->store_mode = $request->input('store_mode');
        }

        if ($request->has('profile_links')) {
            $store->profile_links = $this->sanitizeProfileLinks($request->input('profile_links'));
        }

        $store->save();

        return response()->json([
            'success' => true,
            'message' => 'Dekorasi dan pengaturan etalase berhasil disimpan.'
        ]);
    }

    /**
     * Ganti mode tampilan toko secara instan (store, profile, hybrid)
     */
    public function updateMode(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'store_mode' => 'required|in:store,profile,hybrid',
        ]);

        $store->store_mode = $request->store_mode;
        $store->save();

        $modeNames = [
            'store'   => 'Toko Digital (Katalog E-Commerce)',
            'profile' => 'Bio Link (Linktree / Lynk.id)',
            'hybrid'  => 'Hybrid (Bio Link + Toko)',
        ];

        return response()->json([
            'success'    => true,
            'store_mode' => $store->store_mode,
            'message'    => 'Mode tampilan toko berhasil diubah ke ' . ($modeNames[$store->store_mode] ?? $store->store_mode) . '.'
        ]);
    }

    /**
     * Simpan perubahan daftar profile links (tombol tautan bio)
     */
    public function saveProfileLinks(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'profile_links' => 'nullable|array',
        ]);

        $store->profile_links = $this->sanitizeProfileLinks($request->input('profile_links'));
        $store->save();

        return response()->json([
            'success'       => true,
            'profile_links' => $store->profile_links,
            'message'       => 'Tombol bio link berhasil disimpan.'
        ]);
    }

    /**
     * Helper sanitasi daftar link bio
     *
     * @param array|null $links
     * @return array
     */
    private function sanitizeProfileLinks(?array $links = null): array
    {
        $profileLinks = [];
        if (is_array($links)) {
            foreach ($links as $index => $link) {
                if (is_array($link) && !empty(trim($link['url'] ?? ''))) {
                    $url = trim($link['url']);
                    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $url = 'https://' . $url;
                    }
                    $layout = $link['layout'] ?? 'list';
                    if (!in_array($layout, ['list', 'grid', 'card'])) {
                        $layout = 'list';
                    }
                    $title = trim($link['title'] ?? 'Link');
                    $slug = !empty($link['slug']) 
                        ? \Illuminate\Support\Str::slug($link['slug']) 
                        : (\Illuminate\Support\Str::slug($title) ?: 'item-' . ($index + 1));
                    $id = !empty($link['id']) ? trim($link['id']) : $slug;

                    $profileLinks[] = [
                        'id'          => $id,
                        'slug'        => $slug,
                        'title'       => $title,
                        'subtitle'    => !empty(trim($link['subtitle'] ?? '')) ? trim($link['subtitle']) : null,
                        'description' => !empty(trim($link['description'] ?? '')) ? trim($link['description']) : null,
                        'button_text' => !empty(trim($link['button_text'] ?? '')) ? trim($link['button_text']) : 'Buka Tautan / Pesan',
                        'has_detail'  => isset($link['has_detail']) ? (bool)$link['has_detail'] : true,
                        'url'         => $url,
                        'image'       => !empty(trim($link['image'] ?? '')) ? trim($link['image']) : null,
                        'icon'        => trim($link['icon'] ?? 'link'),
                        'color'       => trim($link['color'] ?? '#0284c7'),
                        'layout'      => $layout,
                        'badge'       => !empty(trim($link['badge'] ?? '')) ? trim($link['badge']) : null,
                        'is_active'   => isset($link['is_active']) ? (bool)$link['is_active'] : true,
                    ];
                }
            }
        }
        return $profileLinks;
    }

    public function uploadImage(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('store_appearance', 'public');
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah gambar.'], 400);
    }

    /**
     * Simpan konfigurasi penempatan voucher/diskon di appearance_data
     */
    public function saveVoucherPlacement(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'voucher_placement'              => 'nullable|array',
            'voucher_placement.header'       => 'nullable|string',
            'voucher_placement.product_page' => 'nullable|string',
            'voucher_placement.checkout'     => 'nullable|string',
        ]);

        // Merge ke dalam appearance_data yang ada — jangan timpa widget lainnya
        $current = is_array($store->appearance_data) ? $store->appearance_data : [];
        $current['voucher_placement'] = $request->input('voucher_placement', []);
        $store->appearance_data = $current;
        $store->save();

        return response()->json(['success' => true, 'message' => 'Penempatan kupon berhasil disimpan.']);
    }
}
