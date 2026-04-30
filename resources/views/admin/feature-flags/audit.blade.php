@extends('layouts.admin')
@section('content')

<div class="space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('feature-flags.index') }}" class="btn btn-ghost btn-sm btn-circle">
            <x-icon name="arrow-left" class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold">Historique des features</h1>
            <p class="text-base-content/60 text-sm">Tous les changements d'état des feature flags.</p>
        </div>
    </div>

    <x-ui.card>
        <x-ui.table :headers="['Feature', 'Action', 'Par', 'Source', 'Date']">
            @forelse($audits as $audit)
            <tr>
                <td>
                    <div class="font-medium text-sm">{{ $audit->feature_label }}</div>
                    <code class="text-xs text-base-content/40">{{ $audit->feature_key }}</code>
                </td>
                <td>
                    <x-ui.badge :variant="$audit->new_value ? 'success' : 'error'" size="sm">
                        <x-icon name="{{ $audit->new_value ? 'toggle-right' : 'toggle-left' }}" class="w-3 h-3" />
                        {{ $audit->action_label }}
                    </x-ui.badge>
                </td>
                <td class="text-sm">{{ $audit->user_name ?? '—' }}</td>
                <td>
                    <x-ui.badge variant="ghost" size="xs">{{ $audit->source }}</x-ui.badge>
                </td>
                <td class="text-sm text-base-content/60">
                    <span title="{{ $audit->created_at->format('d/m/Y H:i:s') }}">
                        {{ $audit->created_at->diffForHumans() }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8 text-base-content/40">
                    <x-icon name="history" class="w-8 h-8 mx-auto mb-2" />
                    Aucun historique pour le moment.
                </td>
            </tr>
            @endforelse
        </x-ui.table>

        <div class="mt-4">
            {{ $audits->links('components.ui.pagination') }}
        </div>
    </x-ui.card>

</div>
@endsection
