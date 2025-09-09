<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'discount_price',
        'description',
        'category_id',
        'is_active',
        'type',
        'featured_image',
        'gallery_images',
        'digital_file_path',
        'stock',
        'is_featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'gallery_images' => 'array', // Otomatis konversi JSON ke array dan sebaliknya
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2'
    ];

    public function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Rp ' . number_format($this->price ?? 0, 0, ',', '.'),
        );
    }
    public function formattedDiscountPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Rp ' . number_format($this->discount_price ?? 0, 0, ',', '.'),
        );
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all of the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all user purchases for this product.
     */
    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }

    /**
     * Check if the product is of digital type.
     */
    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    public function isPhysical(): bool
    {
        return $this->type === 'fisik';
    }
    /**
     * Get average rating for this product
     */
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get formatted average rating
     */
    public function getFormattedAverageRatingAttribute()
    {
        return number_format($this->average_rating, 1);
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistributionAttribute()
    {
        return [
            5 => $this->reviews()->where('rating', 5)->count(),
            4 => $this->reviews()->where('rating', 4)->count(),
            3 => $this->reviews()->where('rating', 3)->count(),
            2 => $this->reviews()->where('rating', 2)->count(),
            1 => $this->reviews()->where('rating', 1)->count(),
        ];
    }

    /**
     * Get stars HTML for average rating
     */
    public function getStarsHtmlAttribute()
    {
        $average = $this->average_rating;
        $stars = '';

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($average)) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } elseif ($i - 0.5 <= $average) {
                $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }

        return $stars;
    }

    /**
     * Check if user can review this product
     */
    public function canBeReviewedBy($userId)
    {
        // User must own the product
        $ownsProduct = $this->userProducts()->where('user_id', $userId)->exists();

        // User hasn't reviewed yet
        $hasReviewed = $this->reviews()->where('user_id', $userId)->exists();

        return $ownsProduct && !$hasReviewed;
    }
}
