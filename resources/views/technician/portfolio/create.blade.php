@extends('layouts.technician')
@section('title', 'Upload Work Photos')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Back Link --}}
    <a href="{{ route('technician.portfolio.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-emerald-600 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Portfolio
    </a>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900">Upload Work Photos</h1>
            <p class="text-sm text-slate-400 mt-1">Show customers what you are capable of by sharing images of your project</p>
        </div>

        <form method="POST" action="{{ route('technician.portfolio.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Image Upload Section --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Work Photos <span class="text-red-500">*</span>
                </label>

                {{-- Drop Zone --}}
                <div id="drop-zone"
                     class="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center cursor-pointer hover:border-emerald-500 hover:bg-emerald-50/20 transition-all"
                     onclick="document.getElementById('img-input').click()">
                    
                    <input type="file" id="img-input" name="images[]" accept="image/*" class="sr-only" required multiple onchange="previewImages(event)">

                    {{-- Upload Placeholder --}}
                    <div id="upload-placeholder" class="py-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Click or drag photos to upload</p>
                        <p class="text-xs text-slate-400 mt-1">You can select multiple images (JPG, PNG, WEBP up to 5MB each)</p>
                    </div>

                    {{-- Dynamic Grid Preview Area --}}
                    <div id="img-preview" class="hidden">
                        <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-1">
                            {{-- Previews dynamically injected here --}}
                        </div>
                        <p class="text-xs text-emerald-600 mt-4 font-medium">Photos selected — click anywhere in the box to change selection</p>
                    </div>
                </div>

                @error('images')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Job Title Field --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Job Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder='e.g., Full AC installation and piping'
                       required maxlength="100"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                @error('title')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description Field --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Description <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <textarea name="description" rows="4" maxlength="500"
                          placeholder="Describe the job — what was the problem and how did you fix it..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl text-sm flex items-center justify-center gap-2 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload Project Post
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImages(event) {
    const files = event.target.files;
    const previewGrid = document.getElementById('preview-grid');
    const placeholder = document.getElementById('upload-placeholder');
    const previewArea = document.getElementById('img-preview');
    
    previewGrid.innerHTML = '';
    
    if (files.length === 0) {
        placeholder.classList.remove('hidden');
        previewArea.classList.add('hidden');
        return;
    }

    placeholder.classList.add('hidden');
    previewArea.classList.remove('hidden');

    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        
        const reader = new FileReader();
        reader.onload = e => {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'relative aspect-video rounded-xl overflow-hidden bg-slate-100 shadow-sm border border-slate-200';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover';
            
            imgContainer.appendChild(img);
            previewGrid.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

// Drag and drop
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('border-emerald-500','bg-emerald-50/10'); });
dz.addEventListener('dragleave', () => { dz.classList.remove('border-emerald-500','bg-emerald-50/10'); });
dz.addEventListener('drop', e => {
    e.preventDefault();
    dz.classList.remove('border-emerald-500','bg-emerald-50/10');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const inputElement = document.getElementById('img-input');
        inputElement.files = files;
        previewImages({ target: inputElement });
    }
});
</script>
@endpush
@endsection