<div>
    <button
        wire:click="toggle"
        wire:loading.attr="disabled"
        class="btn btn-sm gap-2 transition-all
            {{ $enabled
                ? 'btn-success hover:btn-error'
                : 'btn-ghost text-base-content/30 hover:btn-success' }}">

        <span wire:loading.remove>
            <x-icon name="{{ $enabled ? 'toggle-right' : 'toggle-left' }}" class="w-5 h-5" />
        </span>
        <span wire:loading>
            <span class="loading loading-spinner loading-xs"></span>
        </span>

        {{ $enabled ? 'Activé' : 'Désactivé' }}
    </button>
</div>
