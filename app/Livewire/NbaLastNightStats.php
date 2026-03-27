<?php

namespace App\Livewire;

use App\Models\PlayerGameStat;
use Livewire\Component;

class NbaLastNightStats extends Component
{
    public ?string $date = null;
    public array $players = [];
    public array $availableDates = [];
    public ?string $previousDate = null;
    public ?string $nextDate = null;
    public string $displayDate = '';
    public string $error = '';

    public function mount(?string $date = null): void
    {
        try {
            // Get all available dates
            $availableDates = PlayerGameStat::select('game_date')
                ->distinct()
                ->orderBy('game_date')
                ->pluck('game_date')
                ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
                ->values()
                ->toArray();

            if (empty($availableDates)) {
                $this->error = 'Aucune donnee disponible.';
                return;
            }

            $this->availableDates = $availableDates;

            // Determine current date
            if ($date && in_array($date, $availableDates)) {
                $this->date = $date;
            } else {
                $this->date = end($availableDates);
            }

            $currentIndex = array_search($this->date, $availableDates);
            $this->previousDate = $currentIndex > 0 ? $availableDates[$currentIndex - 1] : null;
            $this->nextDate = $currentIndex < count($availableDates) - 1 ? $availableDates[$currentIndex + 1] : null;

            $this->displayDate = \Carbon\Carbon::parse($this->date)->translatedFormat('l j F Y');

            $this->players = PlayerGameStat::where('game_date', $this->date)
                ->orderByDesc('eval')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            $this->error = 'Impossible de charger les statistiques.';
        }
    }

    public function render()
    {
        return view('livewire.nba-last-night-stats');
    }
}
