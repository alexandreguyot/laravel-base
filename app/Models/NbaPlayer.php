<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NbaPlayer extends Model
{
    protected $table = 'nba_players';

    protected $fillable = [
        'api_id',
        'espn_id',
        'full_name',
        'first_name',
        'last_name',
        'headshot_url',
        'team_abbreviation',
        'team_full_name',
        'team_logo',
        'position',
        'country',
        'conference',
    ];

    /**
     * Retourne l'URL du headshot ESPN si pas encore en base
     */
    public function getHeadshotAttribute(): ?string
    {
        if ($this->headshot_url) {
            return $this->headshot_url;
        }
        if ($this->espn_id) {
            return "https://a.espncdn.com/i/headshots/nba/players/full/{$this->espn_id}.png";
        }
        return null;
    }
}
