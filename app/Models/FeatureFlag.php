<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureFlag extends Model
{
    protected $fillable = [
        'key', 'enabled', 'label', 'description', 'group',
        'last_changed_by', 'last_changed_at',
    ];

    protected $casts = [
        'enabled'         => 'boolean',
        'last_changed_at' => 'datetime',
    ];

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_changed_by');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(FeatureFlagAudit::class, 'feature_key', 'key');
    }

    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?? config("features.{$this->key}.label", $this->key);
    }

    public function getDisplayDescriptionAttribute(): string
    {
        return $this->description ?? config("features.{$this->key}.description", '');
    }

    public function getDisplayGroupAttribute(): string
    {
        return $this->group ?? config("features.{$this->key}.group", 'Autre');
    }
}
