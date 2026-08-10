@extends('layouts.admin')
@section('title', 'Edit Project: ' . $project->title)
@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-4xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Project</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Update information for {{ $project->title }}.</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center" title="Back to Projects" wire:navigate>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 24px;">arrow_back</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-surface rounded-md border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] p-lg">
            <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Project Title <span class="text-error">*</span></label>
                        <input type="text" name="title" required value="{{ old('title', $project->title) }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('title')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Client</label>
                        <select name="client_id" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Select Client (Internal)</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('slug')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Category</label>
                        <select name="project_category_id" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('project_category_id', $project->project_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('project_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">{{ old('short_description', $project->short_description) }}</textarea>
                    @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Full Description</label>
                    <div id="editor-container" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-b-lg font-body-md text-body-md text-on-surface" style="min-height: 250px;"></div>
                    <input type="hidden" name="description" id="description" value="{{ old('description', $project->description) }}">
                    @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Project URL</label>
                        <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('project_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Completed Date</label>
                        <input type="date" name="completed_at" value="{{ old('completed_at', $project->completed_at ? $project->completed_at->format('Y-m-d') : '') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('completed_at')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md items-end">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Thumbnail Image</label>
                        @if($project->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="w-32 h-auto rounded border border-outline-variant ">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <p class="text-xs text-on-surface-variant mt-1">Leave blank to keep current thumbnail.</p>
                        @error('thumbnail')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block font-label-md text-on-surface mb-xs">Status</label>
                            <select name="status" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                                <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex-1">
                            <label class="block font-label-md text-on-surface mb-xs">Featured</label>
                            <label class="flex items-center gap-2 mt-2 cursor-pointer">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} class="w-5 h-5 rounded border-[#CBD5E1] text-primary focus:ring-primary">
                                <span class="font-body-md text-on-surface">Yes</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.projects.index') }}" class="px-6 py-2 border border-outline-variant rounded-lg font-label-md font-bold text-on-surface hover:bg-surface-variant transition" wire:navigate>Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:bg-primary/90 transition shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Write the full description here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        
        const oldDesc = document.getElementById('description').value;
        if (oldDesc) {
            quill.root.innerHTML = oldDesc;
        }

        // Add custom styles to match theme
        document.querySelector('.ql-toolbar').classList.add('bg-surface-container', 'border-[#CBD5E1]', 'rounded-t-lg');
        document.querySelector('.ql-container').classList.add('border-t-0', 'border-[#CBD5E1]', 'rounded-b-lg', 'bg-surface-container-low');

        document.querySelector('form').addEventListener('submit', function(e) {
            const descriptionInput = document.getElementById('description');
            if (quill.root.innerHTML === '<p><br></p>') {
                descriptionInput.value = '';
            } else {
                descriptionInput.value = quill.root.innerHTML;
            }
        });
    });
</script>
@endsection
