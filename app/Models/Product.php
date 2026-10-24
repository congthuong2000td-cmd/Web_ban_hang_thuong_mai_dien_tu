<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'original_price',
        'stock_quantity',
        'thumbnail',
        'is_active',
        'is_featured',
        'review_count',
        'rating_avg',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'integer',
            'original_price' => 'integer',
            'stock_quantity' => 'integer',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'review_count'   => 'integer',
            'rating_avg'     => 'decimal:2',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_visible', true);
    }

    public function allReviews()
    {
        return $this->hasMany(Review::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Accessors ────────────────────────────────────────────────────────
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        return $this->original_price
            ? number_format($this->original_price, 0, ',', '.') . ' ₫'
            : null;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    // ── Helpers ──────────────────────────────────────────────────────────
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }
}
