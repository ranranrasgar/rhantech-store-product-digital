<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Client;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\Product;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    public function home()
    {
        $services = Service::query()->where('is_active', true)->get();
        $projects = Project::query()->with('projectCategory')->where('status', 'published')->latest()->take(3)->get();
        $clients = Client::query()->where('is_active', true)->get();
        $testimonials = Testimonial::query()->with('client')->where('is_active', true)->latest()->get();
        $popupAd = \App\Models\PopupAd::getActiveForCurrentUser();

        // Aplikasi / produk digital rekomendasi: Adil antar toko, rating terbaik, dan acak/random setiap refresh
        $candidateProducts = Product::query()
            ->with(['images', 'category', 'type', 'store', 'reviews'])
            ->published()
            ->get();

        // Urutkan berdasarkan rating efektif tertinggi terlebih dahulu
        $sortedByRating = $candidateProducts->sortByDesc(function ($product) {
            return $product->effective_rating;
        });

        // Agar adil bagi semua toko/tenant (termasuk official store), kelompokkan per toko
        // lalu ambil produk terbaik dari masing-masing toko terlebih dahulu
        $byStore = $sortedByRating->groupBy(function ($product) {
            return $product->store_id ? (string)$product->store_id : 'official';
        });

        // Ambil 1-2 perwakilan produk terbaik dari tiap toko secara acak jika toko punya beberapa produk berating tinggi
        $fairPool = collect();
        foreach ($byStore as $storeProducts) {
            // Ambil produk berating tertinggi dari toko ini
            $topRating = $storeProducts->max(fn($p) => $p->effective_rating);
            $bestFromStore = $storeProducts->filter(fn($p) => $p->effective_rating >= ($topRating - 0.5));
            $fairPool->push($bestFromStore->random());
        }

        // Jika jumlah perwakilan toko masih kurang dari 4, lengkapi dari sisa produk berating tertinggi lainnya
        if ($fairPool->count() < 4) {
            $poolIds = $fairPool->pluck('id')->all();
            $remaining = $sortedByRating->reject(fn($p) => in_array($p->id, $poolIds));
            $topRemaining = $remaining->take(8);
            if ($topRemaining->isNotEmpty()) {
                $needed = 4 - $fairPool->count();
                $fairPool = $fairPool->concat($topRemaining->shuffle()->take($needed));
            }
        }

        // Acak urutan tampilan setiap kali refresh halaman dan ambil 4 item
        $popularProducts = $fairPool->shuffle()->take(4);

        // 10 Toko Terfavorit & Terlaris (Berdasarkan total penjualan berhasil / produk)
        $topStores = \App\Models\Store::query()
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->with(['products' => function ($q) {
                $q->published()->with('images');
            }])
            ->get()
            ->map(function ($store) {
                $productIds = $store->products->pluck('id');
                
                // Total order berhasil untuk toko ini
                $store->sales_count = \App\Models\Order::whereIn('product_id', $productIds)
                    ->whereIn('status', ['paid', 'downloaded'])
                    ->count();

                // Rating toko
                $avgRating = \App\Models\ProductReview::whereIn('product_id', $productIds)
                    ->where('is_visible', true)
                    ->avg('rating');

                $store->rating = $avgRating ? round((float)$avgRating, 1) : 4.9;

                return $store;
            })
            ->sortByDesc('sales_count')
            ->values()
            ->take(10);

        // Kategori produk aktif untuk navigasi cepat mobile
        $categories = \App\Models\ProductCategory::select(['id', 'name'])
            ->whereHas('products', function ($q) {
                $q->published();
            })
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->orderBy('name', 'asc')
            ->take(12)
            ->get();

        // 12 Produk terbaru untuk katalog jelajah mobile
        $latestProducts = Product::query()
            ->with(['images', 'category', 'type', 'store', 'reviews'])
            ->published()
            ->latest()
            ->take(12)
            ->get();

        return view('welcome', compact('services', 'projects', 'clients', 'testimonials', 'popupAd', 'popularProducts', 'topStores', 'categories', 'latestProducts'));
    }

    public function projects(Request $request)
    {
        $query = Project::query()
            ->with(['clients', 'client', 'projectCategory', 'projectType'])
            ->where('status', 'published');

        // Filter kategori project (berdasarkan slug atau id)
        if ($request->filled('category')) {
            $categoryParam = $request->query('category');
            $query->whereHas('projectCategory', function ($q) use ($categoryParam) {
                $q->where('slug', $categoryParam)->orWhere('id', $categoryParam);
            });
        }

        // Filter tipe project (berdasarkan slug atau id)
        if ($request->filled('type')) {
            $typeParam = $request->query('type');
            $query->whereHas('projectType', function ($q) use ($typeParam) {
                $q->where('slug', $typeParam)->orWhere('id', $typeParam);
            });
        }

        // Pencarian nama project atau nama client
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('clients', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->select([
                'id', 'client_id', 'project_category_id', 'project_type_id', 'title', 
                'slug', 'short_description', 'description', 'thumbnail'
            ])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // Jika request AJAX (fetch), hanya kembalikan partial HTML project grid
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->boolean('ajax')) {
            return view('projects._list', compact('projects'))->render();
        }

        $categories = \App\Models\ProjectCategory::select(['id', 'name', 'slug'])
            ->whereHas('projects', function ($q) {
                $q->where('status', 'published');
            })
            ->withCount(['projects' => function ($q) {
                $q->where('status', 'published');
            }])
            ->orderBy('name', 'asc')
            ->get();

        $types = \App\Models\ProjectType::select(['id', 'name', 'slug'])
            ->whereHas('projects', function ($q) {
                $q->where('status', 'published');
            })
            ->withCount(['projects' => function ($q) {
                $q->where('status', 'published');
            }])
            ->orderBy('name', 'asc')
            ->get();

        return view('projects.index', compact('projects', 'categories', 'types'));
    }

    public function projectDetail(string $slug)
    {
        $project = Project::query()->with(['clients', 'client', 'projectCategory', 'projectType', 'images'])->where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('projects.show', compact('project'));
    }

    public function downloadBrochure(string $slug)
    {
        $project = Project::query()->with(['clients', 'client', 'projectCategory', 'projectType', 'images'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Jika ada file brosur custom yang diunggah, utamakan unduh file tersebut jika diminta direct
        if (!empty($project->brochure_file) && request()->query('format') === 'file' && Storage::disk('public')->exists($project->brochure_file)) {
            $extension = pathinfo($project->brochure_file, PATHINFO_EXTENSION) ?: 'pdf';
            $downloadName = 'Brosur-' . Str::slug($project->title) . '.' . $extension;
            return response()->download(Storage::disk('public')->path($project->brochure_file), $downloadName);
        }

        $company = \App\Models\CompanyProfile::first(['*']);

        return view('projects.brochure', compact('project', 'company'));
    }

    public function clients()
    {
        $clients = Client::query()->where('is_active', true)->get();
        $testimonials = Testimonial::query()->with('client')->where('is_active', true)->latest()->get();
        return view('clients.index', compact('clients', 'testimonials'));
    }

    public function about()
    {
        $company = \App\Models\CompanyProfile::first(['*']);
        $services = Service::query()->where('is_active', true)->take(6)->get();
        $testimonials = Testimonial::query()->with('client')->where('is_active', true)->latest()->take(3)->get();
        $totalProducts = Product::published()->count();
        $totalProjects = Project::query()->where('status', 'published')->count();
        $totalStores = \App\Models\Store::count();

        return view('about', compact('company', 'services', 'testimonials', 'totalProducts', 'totalProjects', 'totalStores'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Send auto-reply email to the sender
        try {
            \Illuminate\Support\Facades\Mail::to($contactMessage->email)->send(new \App\Mail\ContactMessageNotification($contactMessage));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send contact auto-reply email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }
}
