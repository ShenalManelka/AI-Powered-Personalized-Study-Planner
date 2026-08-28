<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmartAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
        'action_url',
        'related_model_type',
        'related_model_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the user that owns the alert.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (Assignment, Exam, Prediction, etc).
     */
    public function relatedModel(): MorphTo
    {
        return $this->morphTo();
    }
}
