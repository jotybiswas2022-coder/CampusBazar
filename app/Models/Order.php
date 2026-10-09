<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'recipient_name',
        'phone',
        'address',
        'district',
        'upazila',
        'delivery_instructions',
        'subtotal',
        'delivery_charge',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'payment_collected_at',
        'payment_confirmed_by',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'total' => 'decimal:2',
            'payment_collected_at' => 'datetime',
        ];
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'CB-' . now()->format('ymd') . '-' . strtoupper(Str::random(6));
        } while (static::withTrashed()->where('order_number', $number)->exists());

        return $number;
    }

    // ===== Relationships =====

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public function paymentConfirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatus::PAID;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            OrderStatus::PENDING_CONFIRMATION,
            OrderStatus::CONFIRMED,
            OrderStatus::PROCESSING,
        ], true);
    }
}
