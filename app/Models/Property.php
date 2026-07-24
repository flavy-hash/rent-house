<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'region', 'area', 'type', 'price',
        'bedrooms', 'bathrooms', 'description', 'amenities', 'image',
        'landlord_name', 'phone', 'is_featured', 'is_available',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'integer',
    ];

    /**
     * Regions available on the platform (Tanzania).
     */
    public const REGIONS = [
        'Arusha', 'Dar es Salaam', 'Dodoma', 'Mwanza', 'Moshi',
        'Zanzibar', 'Mbeya', 'Morogoro', 'Tanga', 'Iringa',
    ];

    public const TYPES = ['House', 'Apartment', 'Villa', 'Studio', 'Room'];

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::uniqueSlug($property->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'property';
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Price formatted as Tanzanian Shillings, e.g. "TZS 450,000".
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'TZS '.number_format($this->price);
    }

    /**
     * WhatsApp link using an international phone number (drops leading 0, adds 255).
     */
    public function getWhatsappUrlAttribute(): string
    {
        $digits = preg_replace('/\D/', '', $this->phone);
        $digits = preg_replace('/^0/', '255', $digits);
        $message = rawurlencode("Habari, nina nia ya kupanga: {$this->title} ({$this->region}).");

        return "https://wa.me/{$digits}?text={$message}";
    }

    /**
     * Resolve the image to a usable URL (external URL or stored upload).
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=70';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    // --- Query scopes for filtering -------------------------------------

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($q, $term) {
            $q->where(function ($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });

        $query->when($filters['region'] ?? null, fn ($q, $v) => $q->where('region', $v));
        $query->when($filters['type'] ?? null, fn ($q, $v) => $q->where('type', $v));
        $query->when($filters['bedrooms'] ?? null, fn ($q, $v) => $q->where('bedrooms', '>=', (int) $v));
        $query->when($filters['min_price'] ?? null, fn ($q, $v) => $q->where('price', '>=', (int) $v));
        $query->when($filters['max_price'] ?? null, fn ($q, $v) => $q->where('price', '<=', (int) $v));

        return $query;
    }
}
