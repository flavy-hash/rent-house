<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nyumbani — Find a house to rent in Tanzania')</title>
    <meta name="description" content="Nyumbani helps residents in Arusha, Dar es Salaam, Mwanza and across Tanzania find and rent houses easily.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Leaflet (open-source maps, no API key needed) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-40 border-b border-slate-100 bg-white/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-600 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10"/>
                    </svg>
                </span>
                <span class="leading-tight">
                    <span class="block text-lg font-extrabold tracking-tight text-slate-900">Nyumbani</span>
                    <span class="block text-[11px] font-medium text-brand-600">Rent smarter in Tanzania</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-brand-600">Home</a>
                <a href="{{ route('properties.index') }}" class="transition hover:text-brand-600">Browse</a>
                <a href="{{ route('home') }}#why" class="transition hover:text-brand-600">Why Nyumbani</a>
                <a href="{{ route('home') }}#featured" class="transition hover:text-brand-600">Featured</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('saved') }}" class="relative flex items-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-rose-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 10-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z"/></svg>
                    <span class="hidden sm:inline">Saved</span>
                    <span data-fav-count class="hidden min-w-5 rounded-full bg-rose-500 px-1.5 text-center text-xs font-bold leading-5 text-white">0</span>
                </a>
                <a href="{{ url('/admin') }}"
                   class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    List your property
                </a>
            </div>
        </nav>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-600 text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10"/>
                            </svg>
                        </span>
                        <span class="text-lg font-extrabold text-slate-900">Nyumbani</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Helping Tanzanians find a place to call home — from Arusha to Zanzibar.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Explore</h4>
                    <ul class="mt-3 space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('properties.index') }}" class="hover:text-brand-600">All properties</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'Apartment']) }}" class="hover:text-brand-600">Apartments</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'House']) }}" class="hover:text-brand-600">Houses</a></li>
                        <li><a href="{{ url('/admin') }}" class="hover:text-brand-600">List a property</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Top regions</h4>
                    <ul class="mt-3 space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('properties.index', ['region' => 'Arusha']) }}" class="hover:text-brand-600">Arusha</a></li>
                        <li><a href="{{ route('properties.index', ['region' => 'Dar es Salaam']) }}" class="hover:text-brand-600">Dar es Salaam</a></li>
                        <li><a href="{{ route('properties.index', ['region' => 'Mwanza']) }}" class="hover:text-brand-600">Mwanza</a></li>
                        <li><a href="{{ route('properties.index', ['region' => 'Zanzibar']) }}" class="hover:text-brand-600">Zanzibar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Get in touch</h4>
                    <p class="mt-3 text-sm text-slate-500">Questions? Reach the Nyumbani team.</p>
                    <a href="mailto:hello@nyumbani.co.tz" class="mt-2 inline-block text-sm font-medium text-brand-600 hover:underline">hello@nyumbani.co.tz</a>
                </div>
            </div>
            <div class="mt-10 border-t border-slate-100 pt-6 text-center text-sm text-slate-400">
                &copy; {{ date('Y') }} Nyumbani. Built for the people of Tanzania.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @stack('scripts')
</body>
</html>
