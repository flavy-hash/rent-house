@extends('layouts.app')

@section('title', $property->title.' — Nyumbani')

@section('content')

    <section class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
        <nav class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span class="mx-1.5">/</span>
            <a href="{{ route('properties.index') }}" class="hover:text-brand-600">Browse</a>
            <span class="mx-1.5">/</span>
            <a href="{{ route('properties.index', ['region' => $property->region]) }}" class="hover:text-brand-600">{{ $property->region }}</a>
        </nav>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Main --}}
            <div class="lg:col-span-2">
                @php $gallery = $property->gallery; @endphp
                <div class="overflow-hidden rounded-3xl shadow-sm">
                    <img id="main-photo" src="{{ $gallery[0] ?? $property->image_url }}" alt="{{ $property->title }}" class="aspect-[16/10] w-full object-cover">
                </div>

                @if (count($gallery) > 1)
                    <div class="mt-3 grid grid-cols-4 gap-3 sm:grid-cols-5">
                        @foreach ($gallery as $photo)
                            <button type="button"
                                    onclick="document.getElementById('main-photo').src = this.dataset.src"
                                    data-src="{{ $photo }}"
                                    class="overflow-hidden rounded-xl ring-1 ring-slate-200 transition hover:ring-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <img src="{{ $photo }}" alt="Photo of {{ $property->title }}" class="aspect-square w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">{{ $property->type }}</span>
                            @if ($property->is_featured)
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Featured</span>
                            @endif
                        </div>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">{{ $property->title }}</h1>
                        <p class="mt-1 flex items-center gap-1.5 text-slate-500">
                            <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $property->area ? $property->area.', ' : '' }}{{ $property->region }}
                        </p>
                    </div>

                    <button type="button" data-fav-btn data-fav-id="{{ $property->id }}"
                            class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-rose-300 hover:text-rose-500 [&.is-fav]:border-rose-300 [&.is-fav]:text-rose-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path class="fill-current opacity-0 [.is-fav_&]:opacity-100" stroke="none" d="M12 21s-6.716-4.297-9.428-7.01A5.5 5.5 0 0112 6.343a5.5 5.5 0 019.428 7.647C18.716 16.703 12 21 12 21z"/>
                            <path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 10-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z"/>
                        </svg>
                        <span class="[.is-fav_&]:hidden">Save</span>
                        <span class="hidden [.is-fav_&]:inline">Saved</span>
                    </button>
                </div>

                {{-- Key facts --}}
                <div class="mt-6 grid grid-cols-3 gap-3 sm:max-w-md">
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-slate-900">{{ $property->bedrooms }}</p>
                        <p class="text-xs font-medium text-slate-500">Bedrooms</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-slate-900">{{ $property->bathrooms }}</p>
                        <p class="text-xs font-medium text-slate-500">Bathrooms</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-slate-900">{{ $property->type }}</p>
                        <p class="text-xs font-medium text-slate-500">Type</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-slate-900">About this property</h2>
                    <p class="mt-2 whitespace-pre-line leading-relaxed text-slate-600">{{ $property->description ?: 'No description provided.' }}</p>
                </div>

                {{-- Amenities --}}
                @if (!empty($property->amenities))
                    <div class="mt-8">
                        <h2 class="text-lg font-bold text-slate-900">Amenities</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($property->amenities as $amenity)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700">
                                    <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    {{ $amenity }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Video tour --}}
                @if ($property->video_url)
                    <div class="mt-8">
                        <h2 class="text-lg font-bold text-slate-900">Video tour</h2>
                        <div class="mt-3 overflow-hidden rounded-2xl bg-black ring-1 ring-slate-200">
                            <video controls preload="metadata" playsinline
                                   poster="{{ $property->image_url }}"
                                   class="aspect-video w-full">
                                <source src="{{ $property->video_url }}">
                                Your browser doesn't support embedded video.
                            </video>
                        </div>
                    </div>
                @endif

                {{-- Map --}}
                @if ($property->hasCoordinates())
                    <div class="mt-8">
                        <h2 class="text-lg font-bold text-slate-900">Location</h2>
                        <p class="mt-1 text-sm text-slate-500">Approximate location in {{ $property->area ? $property->area.', ' : '' }}{{ $property->region }}.</p>
                        <div id="property-map" class="mt-3 h-72 w-full overflow-hidden rounded-2xl ring-1 ring-slate-200" style="z-index:0;"></div>
                    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const lat = {{ $property->latitude }}, lng = {{ $property->longitude }};
                            const map = L.map('property-map', { scrollWheelZoom: false }).setView([lat, lng], 14);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; OpenStreetMap contributors',
                            }).addTo(map);
                            L.marker([lat, lng]).addTo(map)
                                .bindPopup(@json($property->title)).openPopup();
                        });
                    </script>
                    @endpush
                @endif
            </div>

            {{-- Sidebar: price + contact --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-sm text-slate-500">Monthly rent</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $property->formatted_price }}
                        <span class="text-base font-medium text-slate-400">/ month</span></p>

                    <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Listed by</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $property->landlord_name }}</p>
                        <p class="text-sm text-slate-500">{{ $property->phone }}</p>
                    </div>

                    <a href="{{ $property->whatsapp_url }}" target="_blank" rel="noopener"
                       class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Message on WhatsApp
                    </a>
                    <a href="tel:{{ $property->phone }}"
                       class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11 11 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Call landlord
                    </a>

                    <p class="mt-4 text-center text-xs text-slate-400">Always visit the property in person before making any payment.</p>
                </div>
            </aside>
        </div>

        {{-- Related --}}
        @if ($related->count())
            <div class="mt-16">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">More in {{ $property->region }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @include('partials.property-card', ['property' => $item])
                    @endforeach
                </div>
            </div>
        @endif
    </section>

@endsection
