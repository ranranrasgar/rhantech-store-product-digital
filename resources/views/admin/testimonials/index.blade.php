@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Testimonials</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage client reviews and testimonials.</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="bg-[#06B6D4] hover:bg-[#06B6D4]/90 text-white font-label-md text-label-md py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">add</span>
                Add New Testimonial
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Testimonials</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($totalTestimonials ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">reviews</span>
                </div>
            </div>
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Active</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($activeTestimonials ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7]">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
            </div>
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Avg Rating</p>
                    <div class="flex items-center gap-1 mt-1">
                        <p class="font-headline-lg text-headline-lg text-on-surface">{{ number_format($averageRating ?? 0, 1) }}</p>
                        <span class="material-symbols-outlined text-[#EAB308] text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#FEF08A] flex items-center justify-center text-[#A16207]">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star_half</span>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Client</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Quote / Rating</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50 bg-surface">
                        @forelse($testimonials ?? [] as $testimonial)
                        <tr class="hover:bg-[#F8FAFC] transition-colors group">
                            <td class="p-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($testimonial->client && $testimonial->client->logo)
                                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $testimonial->client->logo) }}" alt="{{ $testimonial->client->name }}"/>
                                        @else
                                            <span class="material-symbols-outlined text-outline-variant">person</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md font-bold text-on-surface group-hover:text-[#06B6D4] transition-colors">{{ $testimonial->client->name ?? 'Unknown Client' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-md">
                                <p class="font-body-md text-body-md text-on-surface italic truncate max-w-md">"{{ Str::limit($testimonial->quote, 80) }}"</p>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <span class="material-symbols-outlined text-[#EAB308] text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        @else
                                            <span class="material-symbols-outlined text-outline-variant text-[16px]" style="font-variation-settings: 'FILL' 0;">star</span>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="p-md">
                                @if($testimonial->is_active)
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-[#ECFEFF] text-[#0891B2]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0891B2]"></span>
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                    Inactive
                                </span>
                                @endif
                            </td>
                            <td class="p-md text-right relative">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-on-surface-variant hover:text-[#06B6D4] p-1 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">edit</span>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-on-surface-variant hover:text-error p-1 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-md text-center text-on-surface-variant">No testimonials found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!empty($testimonials) && method_exists($testimonials, 'links') && $testimonials->hasPages())
            <div class="border-t border-outline-variant bg-surface p-md">
                {{ $testimonials->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
