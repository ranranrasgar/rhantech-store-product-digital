<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Client;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    public function home()
    {
        $services = Service::query()->where('is_active', true)->get();
        $projects = Project::query()->where('status', 'published')->latest()->take(3)->get();
        $clients = Client::query()->where('is_active', true)->get();
        $testimonials = Testimonial::query()->with('client')->where('is_active', true)->latest()->get();
        $popupAd = \App\Models\PopupAd::query()->where('is_active', true)->latest()->first();

        return view('welcome', compact('services', 'projects', 'clients', 'testimonials', 'popupAd'));
    }

    public function projects()
    {
        $projects = Project::query()->where('status', 'published')->latest()->paginate(9);
        return view('projects.index', compact('projects'));
    }

    public function projectDetail($slug)
    {
        $project = Project::query()->with(['client', 'images'])->where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('projects.show', compact('project'));
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
