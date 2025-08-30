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
    ];

    public function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => 'Rp ' . number_format($this->price ?? 0, 0, ',', '.'),
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
}
