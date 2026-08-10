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
            
            @if(isset($company) && ($company->facebook_url || $company->instagram_url || $company->linkedin_url || $company->twitter_url))
            <div class="mt-xl pt-lg border-t border-outline-variant/30">
                <h4 class="font-label-md font-bold text-on-background dark:text-white mb-4">Follow Us</h4>
                <div class="flex gap-4">
                    @if($company->facebook_url)
                    <a href="{{ $company->facebook_url }}" target="_blank" class="p-2 bg-surface-container-high rounded-full text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">public</span>
                    </a>
                    @endif
                    @if($company->instagram_url)
                    <a href="{{ $company->instagram_url }}" target="_blank" class="p-2 bg-surface-container-high rounded-full text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">photo_camera</span>
                    </a>
                    @endif
                    @if($company->linkedin_url)
                    <a href="{{ $company->linkedin_url }}" target="_blank" class="p-2 bg-surface-container-high rounded-full text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">work</span>
                    </a>
                    @endif
                    @if($company->twitter_url)
                    <a href="{{ $company->twitter_url }}" target="_blank" class="p-2 bg-surface-container-high rounded-full text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">alternate_email</span>
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
