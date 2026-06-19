<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN           = 'admin';
    public const ROLE_PRODUCT_MANAGER = 'product_manager';
    public const ROLE_CUSTOMER        = 'customer';

    public const ROLES = [
        self::ROLE_ADMIN           => 'Admin',
        self::ROLE_PRODUCT_MANAGER => 'Product Manager',
        self::ROLE_CUSTOMER        => 'Customer',
    ];

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool           { return $this->role === self::ROLE_ADMIN; }
    public function isProductManager(): bool  { return $this->role === self::ROLE_PRODUCT_MANAGER; }
    public function isCustomer(): bool        { return $this->role === self::ROLE_CUSTOMER; }

    public function canManageProducts(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PRODUCT_MANAGER], true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
