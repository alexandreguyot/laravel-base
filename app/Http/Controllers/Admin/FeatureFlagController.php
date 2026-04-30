<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use App\Models\FeatureFlagAudit;
use App\Services\FeatureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureFlagController extends Controller
{
    public function __construct(protected FeatureService $features) {}

    public function index(): View
    {
        $configFlags = config('features', []);
        $dbFlags     = FeatureFlag::with('changedBy')->get()->keyBy('key');

        $flags = collect($configFlags)->map(function ($def, $key) use ($dbFlags) {
            $db = $dbFlags->get($key);
            return [
                'key'             => $key,
                'label'           => $db?->label       ?? $def['label']       ?? $key,
                'description'     => $db?->description ?? $def['description'] ?? '',
                'group'           => $db?->group       ?? $def['group']       ?? 'Autre',
                'enabled'         => $db ? $db->enabled : ($def['enabled'] ?? true),
                'in_db'           => (bool) $db,
                'last_changed_by' => $db?->changedBy?->name,
                'last_changed_at' => $db?->last_changed_at,
            ];
        })->groupBy('group')->sortKeys();

        return view('admin.feature-flags.index', compact('flags'));
    }

    public function toggle(Request $request, string $key): RedirectResponse
    {
        abort_unless(array_key_exists($key, config('features', [])), 404);

        $newState = $this->features->toggle($key);
        $label    = config("features.{$key}.label", $key);

        return back()->with('status', "Feature « {$label} » " . ($newState ? 'activée' : 'désactivée') . '.');
    }

    public function sync(): RedirectResponse
    {
        $this->features->syncFromConfig();

        return back()->with('status', 'Synchronisation depuis config/features.php effectuée.');
    }

    public function audit(): View
    {
        $audits = FeatureFlagAudit::latest()->paginate(50);

        return view('admin.feature-flags.audit', compact('audits'));
    }
}
