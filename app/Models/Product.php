<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'campus_id',
        'title',
        'slug',
        'description',
        'price',
        'condition',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'product';
        $slug = $base;
        $i = 2;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    // ===== Relationships =====

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images->first();
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::ACTIVE;
    }

    public function isPurchasable(): bool
    {
        return $this->status === ProductStatus::ACTIVE;
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->where('status', 'published')->avg('rating'), 1);
    }

    // ===== Scopes =====

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::ACTIVE);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
