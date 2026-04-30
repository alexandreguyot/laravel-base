<?php

namespace App\Livewire;

use App\Services\FeatureService;
use Livewire\Component;

class FeatureFlagToggle extends Component
{
    public string $featureKey;
    public bool   $enabled;
    public string $label;

    public function mount(string $featureKey): void
    {
        $this->featureKey = $featureKey;
        $this->enabled    = app(FeatureService::class)->isEnabled($featureKey);
        $this->label      = config("features.{$featureKey}.label", $featureKey);
    }

    public function toggle(): void
    {
        $this->enabled = app(FeatureService::class)->toggle($this->featureKey);

        $state = $this->enabled ? 'activée' : 'désactivée';
        $this->dispatch('feature-toggled', key: $this->featureKey, enabled: $this->enabled);
        session()->flash('status', "Feature « {$this->label} » {$state}.");
    }

    public function render()
    {
        return view('livewire.feature-flag-toggle');
    }
}
