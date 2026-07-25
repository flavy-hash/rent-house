@extends('layouts.app')

@section('title', 'Nyumbani — Find a house to rent in Tanzania')

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="relative mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl">
            <img src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=70"
                 alt="Modern home in Tanzania" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/85 via-slate-900/60 to-slate-900/20"></div>

            <div class="relative px-6 py-16 sm:px-12 sm:py-24 lg:py-28">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-sm font-medium text-white ring-1 ring-white/25 backdrop-blur">
                        🇹🇿 Trusted by renters across Tanzania
                    </span>
                    <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Discover a place<br>that feels like <span class="text-brand-300">home</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-lg text-slate-200">
                        Finding a house to rent in Arusha, Dar es Salaam or Mwanza shouldn't be a struggle.
                        Browse verified listings and contact landlords directly — by call or WhatsApp.
                    </p>
                </div>

                <div class="mt-8 max-w-4xl">
                    @include('partials.search-bar', ['regions' => $regions, 'types' => $types])
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-x-8 gap-y-2 text-sm text-slate-200">
                    <span><span class="font-bold text-white">{{ $total }}+</span> houses listed</span>
                    <span><span class="font-bold text-white">10</span> regions covered</span>
                    <span><span class="font-bold text-white">Free</span> to browse &amp; contact</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TRUST FEATURES ============ --}}
    <section class="mx-auto mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $features = [
                    ['Verified listings', 'Every property is reviewed before it goes live.', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Direct contact', 'Call or WhatsApp the landlord — no middlemen fees.', 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11 11 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                    ['Local knowledge', 'Listings organised by region and neighbourhood.', 'M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z'],
                    ['Fair prices', 'Rent shown clearly in Tanzanian Shillings.', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2'],
                ];
            @endphp
            @foreach ($features as [$title, $desc, $icon])
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                    <h3 class="mt-4 font-semibold text-slate-900">{{ $title }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ WHY NYUMBANI ============ --}}
    <section id="why" class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Why Nyumbani</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">More than just a house</h2>
                <p class="mt-4 text-slate-600">
                    Many Tanzanians spend weeks walking street to street, relying on dalali (brokers)
                    and word of mouth to find a home. Nyumbani brings those houses online so you can
                    search from your phone and move in faster.
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach ([
                        'Search by region, neighbourhood, price and rooms',
                        'See photos and full details before you visit',
                        'Talk to the landlord directly on WhatsApp',
                        'List your own house for free in minutes',
                    ] as $point)
                        <li class="flex items-start gap-3 text-slate-700">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('properties.index') }}" class="rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700">Browse houses</a>
                    <a href="{{ url('/admin') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">List your property</a>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=70"
                     alt="Cosy living room" class="w-full rounded-3xl object-cover shadow-lg">
            </div>
        </div>
    </section>

    {{-- ============ FEATURED PROPERTIES ============ --}}
    <section id="featured" class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Handpicked</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Featured properties</h2>
            </div>
            <a href="{{ route('properties.index') }}" class="hidden items-center gap-1 text-sm font-semibold text-brand-600 hover:underline sm:flex">
                View all properties &rarr;
            </a>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($featured as $property)
                @include('partials.property-card', ['property' => $property])
            @empty
                <p class="text-slate-500">No properties yet. <a href="{{ url('/admin') }}" class="font-semibold text-brand-600 hover:underline">Be the first to list one.</a></p>
            @endforelse
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl bg-brand-600 px-6 py-12 text-center sm:px-12 sm:py-16">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Are you a landlord?</h2>
            <p class="mx-auto mt-3 max-w-xl text-brand-100">Create a free landlord account, then list your house and reach thousands of renters looking for a home right now.</p>
            <a href="{{ url('/admin') }}" class="mt-6 inline-block rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">List your property &rarr;</a>
        </div>
    </section>

@endsection
