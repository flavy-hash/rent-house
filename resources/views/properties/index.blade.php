@extends('layouts.app')

@section('title', 'Browse houses for rent in Tanzania — Nyumbani')

@section('content')

    <section class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-brand-700 to-brand-500 px-6 py-10 sm:px-10">
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Browse houses to rent</h1>
            <p class="mt-2 text-brand-100">Filter by region, type and budget to find your next home.</p>
            <div class="mt-6">
                @include('partials.search-bar', ['regions' => $regions, 'types' => $types, 'filters' => $filters])
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-500">
                <span class="font-semibold text-slate-900">{{ $properties->total() }}</span>
                {{ Str::plural('property', $properties->total()) }} found
                @if (array_filter($filters))
                    <a href="{{ route('properties.index') }}" class="ml-2 font-semibold text-brand-600 hover:underline">Clear filters</a>
                @endif
            </p>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Map toggle --}}
                <button type="button" id="map-toggle"
                        class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-slate-200 transition hover:ring-brand-300">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span id="map-toggle-label">Show map</span>
                </button>

                {{-- Quick type chips --}}
                @foreach ($types as $type)
                    <a href="{{ route('properties.index', array_merge($filters, ['type' => $type])) }}"
                       class="rounded-full px-3 py-1 text-xs font-semibold ring-1 transition
                              {{ ($filters['type'] ?? '') === $type ? 'bg-brand-600 text-white ring-brand-600' : 'bg-white text-slate-600 ring-slate-200 hover:ring-brand-300' }}">
                        {{ $type }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Map panel (hidden by default) --}}
        <div id="map-panel" class="mt-6 hidden">
            <div id="browse-map" class="h-96 w-full overflow-hidden rounded-2xl ring-1 ring-slate-200" style="z-index:0;"></div>
        </div>

        @if ($properties->count())
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($properties as $property)
                    @include('partials.property-card', ['property' => $property])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $properties->links() }}
            </div>
        @else
            <div class="mt-10 rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand-50 text-brand-600">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">No houses match your search</h3>
                <p class="mt-1 text-sm text-slate-500">Try a different region or a higher budget.</p>
                <a href="{{ route('properties.index') }}" class="mt-4 inline-block rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Reset search</a>
            </div>
        @endif
    </section>

    @php
        $mapMarkers = $properties->getCollection()
            ->filter(fn ($p) => $p->hasCoordinates())
            ->map(fn ($p) => [
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'title' => $p->title,
                'price' => $p->formatted_price,
                'url' => route('properties.show', $p),
                'img' => $p->image_url,
            ])
            ->values();
    @endphp

    @push('scripts')
    <script>
        (function () {
            const markers = @json($mapMarkers);

            const toggle = document.getElementById('map-toggle');
            const panel = document.getElementById('map-panel');
            const label = document.getElementById('map-toggle-label');
            let map = null;

            function initMap() {
                map = L.map('browse-map').setView([-6.0, 35.0], 6); // roughly centre of Tanzania
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(map);

                if (markers.length) {
                    const group = L.featureGroup(
                        markers.map((m) =>
                            L.marker([m.lat, m.lng]).bindPopup(
                                `<a href="${m.url}" style="display:block;width:180px;text-decoration:none;color:#0f172a">
                                    <img src="${m.img}" style="width:100%;height:96px;object-fit:cover;border-radius:8px"/>
                                    <strong style="display:block;margin-top:6px;font-size:13px">${m.title}</strong>
                                    <span style="color:#5b41e0;font-weight:700;font-size:13px">${m.price}/mo</span>
                                </a>`
                            )
                        )
                    ).addTo(map);
                    map.fitBounds(group.getBounds().pad(0.2));
                }
            }

            toggle.addEventListener('click', function () {
                const hidden = panel.classList.toggle('hidden');
                label.textContent = hidden ? 'Show map' : 'Hide map';
                if (!hidden && !map) {
                    initMap();
                } else if (!hidden && map) {
                    setTimeout(() => map.invalidateSize(), 50);
                }
            });
        })();
    </script>
    @endpush

@endsection
