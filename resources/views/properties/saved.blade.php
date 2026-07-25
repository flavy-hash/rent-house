@extends('layouts.app')

@section('title', 'Saved properties — Nyumbani')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-rose-50 text-rose-500">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-6.716-4.297-9.428-7.01A5.5 5.5 0 0112 6.343a5.5 5.5 0 019.428 7.647C18.716 16.703 12 21 12 21z"/></svg>
            </span>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Saved properties</h1>
                <p class="text-slate-500">Houses you've saved on this device. Tap the heart on any listing to save it.</p>
            </div>
        </div>

        {{-- Loading state --}}
        <div id="saved-loading" class="mt-10 text-center text-slate-400">Loading your saved properties…</div>

        {{-- Empty state --}}
        <div id="saved-empty" class="mt-10 hidden rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-rose-50 text-rose-400">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 10-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z"/></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">No saved properties yet</h3>
            <p class="mt-1 text-sm text-slate-500">Browse houses and tap the heart to keep them here.</p>
            <a href="{{ route('properties.index') }}" class="mt-4 inline-block rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Browse houses</a>
        </div>

        {{-- Results injected here --}}
        <div id="saved-results" class="mt-8"></div>
    </section>

    @push('scripts')
    <script>
        (function () {
            const loading = document.getElementById('saved-loading');
            const empty = document.getElementById('saved-empty');
            const results = document.getElementById('saved-results');

            async function render() {
                const ids = window.NyumbaniFavorites.getFavorites();
                if (!ids.length) {
                    loading.classList.add('hidden');
                    results.innerHTML = '';
                    empty.classList.remove('hidden');
                    return;
                }
                try {
                    const res = await fetch(`{{ route('saved.cards') }}?ids=${encodeURIComponent(ids.join(','))}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    results.innerHTML = await res.text();
                    loading.classList.add('hidden');
                    empty.classList.toggle('hidden', results.querySelector('[data-fav-btn]') !== null);
                    window.NyumbaniFavorites.wireButtons(results);
                } catch (e) {
                    loading.textContent = 'Could not load saved properties. Please refresh.';
                }
            }

            document.addEventListener('DOMContentLoaded', render);
            // Re-render if the user un-saves something from this page.
            document.addEventListener('favorites:changed', render);
        })();
    </script>
    @endpush

@endsection
