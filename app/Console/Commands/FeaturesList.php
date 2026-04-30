<?php

namespace App\Console\Commands;

use App\Models\FeatureFlag;
use App\Services\FeatureService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class FeaturesList extends Command
{
    protected $signature   = 'features:list {--group= : Filtrer par groupe}';
    protected $description = 'Liste tous les feature flags avec leur état actuel';

    public function handle(FeatureService $features): int
    {
        $configFlags = config('features', []);

        if (empty($configFlags)) {
            $this->warn('Aucun feature flag trouvé dans config/features.php');
            return self::FAILURE;
        }

        $dbFlags = Schema::hasTable('feature_flags')
            ? FeatureFlag::all()->keyBy('key')
            : collect();

        $rows = [];

        foreach ($configFlags as $key => $def) {
            $group = $def['group'] ?? 'Autre';

            if ($this->option('group') && strtolower($group) !== strtolower($this->option('group'))) {
                continue;
            }

            $db      = $dbFlags->get($key);
            $enabled = $features->isEnabled($key);
            $source  = $db ? '<fg=blue>DB</>' : '<fg=gray>config</>';

            $rows[] = [
                $key,
                $def['label'] ?? $key,
                $group,
                $enabled ? '<fg=green>✓ activé</>' : '<fg=red>✗ désactivé</>',
                $source,
            ];
        }

        $this->table(
            ['Clé', 'Label', 'Groupe', 'État', 'Source'],
            $rows
        );

        $total   = count($rows);
        $enabled = count(array_filter($rows, fn ($r) => str_contains($r[3], 'activé')));
        $this->line('');
        $this->line("<fg=gray>{$enabled}/{$total} features activées</>");

        return self::SUCCESS;
    }
}
