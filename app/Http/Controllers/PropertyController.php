<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Landing page — hero search + featured listings.
     */
    public function home()
    {
        $featured = Property::where('is_available', true)
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Property::where('is_available', true)->latest()->take(3)->get();
        }

        return view('home', [
            'featured' => $featured,
            'regions' => Property::REGIONS,
            'types' => Property::TYPES,
            'total' => Property::where('is_available', true)->count(),
        ]);
    }

    /**
     * Browse / search all properties with filters.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'region', 'type', 'bedrooms', 'min_price', 'max_price']);

        $properties = Property::where('is_available', true)
            ->filter($filters)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('properties.index', [
            'properties' => $properties,
            'regions' => Property::REGIONS,
            'types' => Property::TYPES,
            'filters' => $filters,
        ]);
    }

    /**
     * Single property detail page.
     */
    public function show(Property $property)
    {
        $related = Property::where('is_available', true)
            ->where('id', '!=', $property->id)
            ->where('region', $property->region)
            ->latest()
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'related'));
    }

    /**
     * The renter's saved / favourite properties page (favourites live in the browser).
     */
    public function saved()
    {
        return view('properties.saved');
    }

    /**
     * Return rendered cards for the given property ids (used by the Saved page).
     */
    public function savedCards(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids', '')))
            ->map(fn ($v) => trim($v))
            ->filter(fn ($v) => ctype_digit($v))
            ->take(100)
            ->all();

        $properties = empty($ids)
            ? collect()
            : Property::where('is_available', true)
                ->whereIn('id', $ids)
                ->get()
                ->sortBy(fn ($p) => array_search((string) $p->id, $ids))
                ->values();

        return view('partials.cards', compact('properties'));
    }
}
