<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Client;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::query()->count('*');
        $activeServices = Service::query()->where('is_active', '=', true, 'and')->count('*');
        $newMessages = ContactMessage::query()->whereNull('read_at', 'and', false)->count('*');
        $totalClients = Client::query()->count('*');

        // Sample data for Top Services
        $topServices = Service::take(3)->get();

        return view('admin.dashboard', compact(
            'totalProjects',
            'activeServices',
            'newMessages',
            'totalClients',
            'topServices'
        ));
    }
}
