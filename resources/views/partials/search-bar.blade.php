@props(['regions', 'types', 'filters' => [], 'compact' => false])

{{-- Reusable search / filter bar used on the hero and browse page --}}
<form action="{{ route('properties.index') }}" method="GET"
      class="grid gap-3 rounded-2xl bg-white p-3 shadow-lg ring-1 ring-slate-100 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_auto]">

    <div class="flex items-center gap-2 rounded-xl px-3 py-2 ring-1 ring-slate-200 focus-within:ring-brand-400">
        <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <select name="region" class="w-full border-0 bg-transparent text-sm text-slate-700 focus:outline-none">
            <option value="">Any location</option>
            @foreach ($regions as $region)
                <option value="{{ $region }}" @selected(($filters['region'] ?? '') === $region)>{{ $region }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2 rounded-xl px-3 py-2 ring-1 ring-slate-200 focus-within:ring-brand-400">
        <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10"/></svg>
        <select name="type" class="w-full border-0 bg-transparent text-sm text-slate-700 focus:outline-none">
            <option value="">Any type</option>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2 rounded-xl px-3 py-2 ring-1 ring-slate-200 focus-within:ring-brand-400">
        <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-8c1.11 0 2.08.402 2.599 1M12 8v8"/></svg>
        <select name="max_price" class="w-full border-0 bg-transparent text-sm text-slate-700 focus:outline-none">
            <option value="">Any budget</option>
            <option value="150000" @selected(($filters['max_price'] ?? '') == '150000')>Up to TZS 150,000</option>
            <option value="350000" @selected(($filters['max_price'] ?? '') == '350000')>Up to TZS 350,000</option>
            <option value="700000" @selected(($filters['max_price'] ?? '') == '700000')>Up to TZS 700,000</option>
            <option value="1500000" @selected(($filters['max_price'] ?? '') == '1500000')>Up to TZS 1,500,000</option>
            <option value="100000000" @selected(($filters['max_price'] ?? '') == '100000000')>TZS 1,500,000+</option>
        </select>
    </div>

    <button type="submit"
            class="flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-700">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Search
    </button>
</form>
