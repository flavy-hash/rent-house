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
     * Form for a landlord to list a new property.
     */
    public function create()
    {
        return view('properties.create', [
            'regions' => Property::REGIONS,
            'types' => Property::TYPES,
        ]);
    }

    /**
     * Persist a new listing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'region' => ['required', 'string', 'in:'.implode(',', Property::REGIONS)],
            'area' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:'.implode(',', Property::TYPES)],
            'price' => ['required', 'integer', 'min:10000', 'max:100000000'],
            'bedrooms' => ['required', 'integer', 'min:0', 'max:20'],
            'bathrooms' => ['required', 'integer', 'min:0', 'max:20'],
            'description' => ['nullable', 'string', 'max:2000'],
            'amenities' => ['nullable', 'string', 'max:500'],
            'landlord_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'max:4096'], // 4 MB
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('properties', 'public');
        }

        $validated['amenities'] = collect(explode(',', $request->input('amenities', '')))
            ->map(fn ($a) => trim($a))
            ->filter()
            ->values()
            ->all();

        $property = Property::create($validated);

        return redirect()
            ->route('properties.show', $property)
            ->with('status', 'Your property has been listed. Renters can now contact you!');
    }
}
