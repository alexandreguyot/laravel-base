<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerGameStat extends Model
{
    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'game_date' => 'date',
        ];
    }
}
