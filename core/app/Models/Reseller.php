<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reseller extends Model
{
    protected $fillable = [
        'user_id',
        'api_key',
        'admin_discount_percent',
        'status',
        'business_name',
        'contact_email',
        'settlement_account',
        'api_key_revoked_at',
    ];

    protected $casts = [
        'api_key_revoked_at' => 'datetime',
        'admin_discount_percent' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE)->whereNull('api_key_revoked_at');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', Status::DISABLE);
    }

    public function isActive(): bool
    {
        return (int) $this->status === Status::ENABLE && $this->api_key_revoked_at === null;
    }

    public function revokeApiKey(): void
    {
        $this->update(['api_key_revoked_at' => now()]);
    }

    public function regenerateApiKey(): string
    {
        $key = 'rsl_' . Str::random(48);
        $this->update([
            'api_key' => $key,
            'api_key_revoked_at' => null,
        ]);
        return $key;
    }

    public static function generateApiKey(): string
    {
        return 'rsl_' . Str::random(48);
    }

    /**
     * Reseller's cost for a product (base price minus admin discount %).
     */
    public function resellerPrice(float $basePrice): float
    {
        $discount = (float) $this->admin_discount_percent;
        return round($basePrice * (1 - $discount / 100), 2);
    }
}
