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

class PublicController extends Controller
{
    public function home()
    {
        $services = Service::query()->where('is_active', true)->get();
        $projects = Project::query()->with('projectCategory')->where('status', 'published')->latest()->take(3)->get();
        $clients = Client::query()->where('is_active', true)->get();
        $testimonials = Testimonial::query()->with('client')->where('is_active', true)->latest()->get();
        $popupAd = \App\Models\PopupAd::query()->where('is_active', true)->latest()->first();

        // Aplikasi / produk digital yang sering dilihat calon pembeli
        $popularProducts = Product::query()
            ->with(['images', 'category', 'type', 'store', 'reviews'])
            ->published()
            ->orderByDesc('views')
            ->orderByDesc('sales_count')
            ->take(4)
            ->get();

        return view('welcome', compact('services', 'projects', 'clients', 'testimonials', 'popupAd', 'popularProducts'));
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
