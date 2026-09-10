@extends('layouts.public')
@section('title', 'Contact Us - ' . ($company->name ?? 'rhantech'))

@section('content')
<section class="max-w-container-max mx-auto px-lg py-2xl">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2xl">
        
        <!-- Contact Info -->
        <div>
            <span class="inline-block py-1 px-3 rounded-full bg-surface-container text-on-surface font-label-md text-label-md mb-6 border border-outline-variant/30">
                Get In Touch
            </span>
            <h1 class="font-display-lg-mobile md:font-headline-xl text-on-background dark:text-white mb-md">Let's talk about your next project.</h1>
            <p class="font-body-lg text-on-surface-variant mb-xl">
                Whether you have a question, a project idea, or just want to say hi, we're always open to discussing new opportunities.
            </p>

            <div class="space-y-lg">
                @if(isset($company) && $company->email)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-secondary">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Email</h4>
                        <a href="mailto:{{ $company->email }}" class="font-body-md text-secondary hover:underline">{{ $company->email }}</a>
                    </div>
                </div>
                @endif
                
                @if(isset($company) && $company->phone)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-secondary">
                        <span class="material-symbols-outlined">call</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Phone</h4>
                        <p class="font-body-md text-on-surface">{{ $company->phone }}</p>
                    </div>
                </div>
                @endif

                @if(isset($company) && $company->address)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-secondary">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Address</h4>
                        <p class="font-body-md text-on-surface whitespace-pre-wrap">{{ $company->address }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            @php
                $fbUrl = $company->facebook ?? $company->facebook_url ?? null;
                $igUrl = $company->instagram ?? $company->instagram_url ?? null;
                $ytUrl = $company->youtube ?? null;
                $liUrl = $company->linkedin ?? $company->linkedin_url ?? null;
                $twUrl = $company->twitter_url ?? null;
            @endphp
            @if(isset($company) && ($fbUrl || $igUrl || $ytUrl || $liUrl || $twUrl))
            <div class="mt-xl pt-lg border-t border-outline-variant/30">
                <h4 class="font-label-md font-bold text-on-background dark:text-white mb-4">Ikuti Kami</h4>
                <div class="flex items-center gap-3">
                    @if($fbUrl)
                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer" title="Facebook" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-[#1877f2] hover:bg-surface-container-highest transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    @if($igUrl)
                    <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-[#e4405f] hover:bg-surface-container-highest transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($ytUrl)
                    <a href="{{ $ytUrl }}" target="_blank" rel="noopener noreferrer" title="YouTube" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-[#ff0000] hover:bg-surface-container-highest transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    @endif
                    @if($liUrl)
                    <a href="{{ $liUrl }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-[#0a66c2] hover:bg-surface-container-highest transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    @endif
                    @if($twUrl)
                    <a href="{{ $twUrl }}" target="_blank" rel="noopener noreferrer" title="X / Twitter" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest transition-colors">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Contact Form -->
        <div class="bg-surface rounded-lg border border-outline-variant shadow-lg p-lg md:p-xl">
            <h3 class="font-headline-lg text-on-background dark:text-white mb-md">Send us a message</h3>
            
            @if(session('success'))
            <div class="mb-lg p-md bg-[#E0F2FE] border border-[#BAE6FD] text-[#0369A1] rounded-lg font-label-md">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-md">
                @csrf
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Your Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        @error('phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Subject *</label>
                    <input type="text" name="subject" required value="{{ old('subject') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('subject')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Message *</label>
                    <textarea name="message" required rows="5" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('message') }}</textarea>
                    @error('message')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="pt-sm border-t border-outline-variant/30 mt-sm">
                    <button type="submit" class="w-full px-lg py-4 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow text-lg">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
