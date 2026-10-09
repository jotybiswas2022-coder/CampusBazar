<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'institution_name',
        'department',
        'student_id_number',
        'academic_year',
        'front_image_path',
        'back_image_path',
        'status',
        'reviewer_id',
        'review_notes',
        'rejection_reason',
        'resubmission_reason',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function maskedIdNumber(): string
    {
        $id = (string) $this->student_id_number;
        if (strlen($id) <= 4) {
            return str_repeat('*', strlen($id));
        }

        return str_repeat('*', strlen($id) - 4) . substr($id, -4);
    }
}
