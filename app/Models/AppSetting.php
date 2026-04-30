<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Retourne la valeur castée selon le type.
     */
    public function getTypedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            'json'    => json_decode($this->value, true),
            default   => $this->value,
        };
    }
}
