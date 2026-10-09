<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar_path',
        'bio',
        'campus_id',
        'role',
        'is_admin',
        'status',
        'verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    // ===== Role / verification helpers =====

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN || (bool) $this->is_admin;
    }

    public function isStudent(): bool
    {
        return ! $this->isAdmin();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function verificationStatus(): string
    {
        $request = $this->latestVerificationRequest;

        if (! $request) {
            return VerificationStatus::NOT_SUBMITTED;
        }

        if ($request->status === VerificationStatus::APPROVED && $this->verified_at) {
            return VerificationStatus::APPROVED;
        }

        return $request->status;
    }

    public function hasActiveVerificationRequest(): bool
    {
        return $this->verificationRequests()
            ->whereIn('status', [VerificationStatus::PENDING, VerificationStatus::APPROVED])
            ->exists();
    }

    public function canResubmitVerification(): bool
    {
        return in_array($this->verificationStatus(), [
            VerificationStatus::NOT_SUBMITTED,
            VerificationStatus::REJECTED,
            VerificationStatus::RESUBMISSION_REQUIRED,
        ], true);
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name));

        return strtoupper(substr($parts[0] ?? '?', 0, 1) . substr($parts[1] ?? '', 0, 1));
    }

    // ===== Relationships =====

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function latestVerificationRequest(): HasOne
    {
        return $this->hasOne(VerificationRequest::class)->latestOfMany();
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function buyerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sellerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }
}
