<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureFlagAudit extends Model
{
    protected $fillable = [
        'feature_key',
        'old_value',
        'new_value',
        'source',
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'old_value' => 'boolean',
        'new_value' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return $this->new_value ? 'Activé' : 'Désactivé';
    }

    public function getFeatureLabelAttribute(): string
    {
        return config("features.{$this->feature_key}.label", $this->feature_key);
    }
}
