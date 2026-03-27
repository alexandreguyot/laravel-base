<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyChallenge extends Model
{
    protected $fillable = [
        'date',
        'game_date',
        'player_api_id',
        'player_name',
        'team',
        'team_abbreviation',
        'team_logo',
        'conference',
        'division',
        'position',
        'country',
        'height',
        'headshot',
        'jersey_number',
        'pts',
        'reb',
        'ast',
        'eval',
        'stl',
        'blk',
        'min',
        'fgm',
        'fga',
        'fg3m',
        'fg3a',
        'ftm',
        'fta',
        'turnover',
        'is_active',
        'max_attempts',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'game_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForToday($query)
    {
        return $query->where('date', now()->toDateString());
    }
}
