<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function cartItemsCount()
    {
        return $this->carts()->count();
    }

    public function cartTotal()
    {
        return $this->carts()->sum('price');
    }

    public function totalOrders()
    {
        return $this->orders()->count();
    }

    public function totalSpent()
    {
        return $this->orders()->where('status', 'paid')->sum('total_amount');
    }

    /**
     * Get user's review for specific product
     */
    public function getReviewForProduct($productId)
    {
        return $this->reviews()->where('product_id', $productId)->first();
    }

    /**
     * Check if user has reviewed a product
     */
    public function hasReviewedProduct($productId)
    {
        return $this->reviews()->where('product_id', $productId)->exists();
    }

    /**
     * Check if user has purchased a specific product
     */
    public function hasPurchasedProduct($productId)
    {
        return $this->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }
    /**
     * Get user's average rating given
     */
    public function getAverageRatingGivenAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get user's total reviews count
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Relationship with user products (purchased products)
     */
    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }

    /**
     * Relationship with owned products (alias untuk userProducts)
     */
    public function ownedProducts()
    {
        return $this->userProducts();
    }
}
