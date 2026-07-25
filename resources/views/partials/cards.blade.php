{{-- Renders a grid of property cards for the given $properties collection. --}}
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($properties as $property)
        @include('partials.property-card', ['property' => $property])
    @endforeach
</div>
