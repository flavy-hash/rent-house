@props(['property'])

<a href="{{ route('properties.show', $property) }}"
   class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $property->image_url }}" alt="{{ $property->title }}"
             loading="lazy"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @if ($property->is_featured)
            <span class="absolute left-3 top-3 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white shadow">Featured</span>
        @endif
        <span class="absolute right-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow">{{ $property->type }}</span>
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="font-semibold text-slate-900 transition group-hover:text-brand-600">{{ $property->title }}</h3>
        <p class="mt-1 flex items-center gap-1 text-sm text-slate-500">
            <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $property->area ? $property->area.', ' : '' }}{{ $property->region }}
        </p>

        <div class="mt-3 flex items-center gap-4 text-xs font-medium text-slate-500">
            <span class="flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M4 12V7a1 1 0 011-1h5v6M20 12v6M4 18h16"/></svg>
                {{ $property->bedrooms }} bed
            </span>
            <span class="flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16v3a4 4 0 01-4 4H8a4 4 0 01-4-4v-3zM7 12V7a2 2 0 114 0"/></svg>
                {{ $property->bathrooms }} bath
            </span>
        </div>

        <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-3">
            <div>
                <span class="text-lg font-extrabold text-slate-900">{{ $property->formatted_price }}</span>
                <span class="text-sm text-slate-400">/ month</span>
            </div>
            <span class="text-sm font-semibold text-brand-600 group-hover:underline">View &rarr;</span>
        </div>
    </div>
</a>
