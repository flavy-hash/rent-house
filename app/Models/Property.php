<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'region', 'area', 'latitude', 'longitude',
        'type', 'price', 'bedrooms', 'bathrooms', 'description', 'amenities',
        'image', 'photos', 'video', 'landlord_name', 'phone', 'is_featured', 'is_available',
    ];

    protected $casts = [
        'amenities' => 'array',
        'photos' => 'array',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
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
     * The landlord who owns this listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Turn a stored path or external URL into a usable image URL.
     */
    public static function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    /**
     * Resolve the cover image (falls back to the first gallery photo, then a placeholder).
     */
    public function getImageUrlAttribute(): string
    {
        return static::resolveImageUrl($this->image)
            ?? ($this->gallery[0] ?? 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=70');
    }

    /**
     * All photo URLs for this property (cover first, then extra uploads), de-duplicated.
     *
     * @return list<string>
     */
    public function getGalleryAttribute(): array
    {
        $urls = [];

        if ($cover = static::resolveImageUrl($this->image)) {
            $urls[] = $cover;
        }

        foreach ($this->photos ?? [] as $photo) {
            if ($url = static::resolveImageUrl($photo)) {
                $urls[] = $url;
            }
        }

        return array_values(array_unique($urls));
    }

    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    /**
     * Resolve the optional video tour to a usable URL (or null if none).
     */
    public function getVideoUrlAttribute(): ?string
    {
        return static::resolveImageUrl($this->video);
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
