@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.contentPage.title'), 'href' => route('content-pages.index')],
        ['label' => trans('global.edit')],
    ]" />

    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="text-2xl font-bold">
            {{ trans('global.edit') }} {{ trans('cruds.contentPage.title_singular') }}
            <span class="text-base-content/40 font-normal text-lg">#{{ $contentPage->id }}</span>
        </h1>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('page-builder.edit', $contentPage) }}" icon="layout-sidebar" variant="secondary">
                {{ $contentPage->use_builder ? 'Ouvrir le builder' : 'Créer avec le builder' }}
            </x-ui.button>
            @if($contentPage->use_builder && $contentPage->slug)
            <x-ui.button href="{{ route('page.show', $contentPage->slug) }}" icon="external-link" variant="ghost" target="_blank">
                Voir la page
            </x-ui.button>
            @endif
            <x-ui.button href="{{ route('content-pages.index') }}" variant="ghost" icon="arrow-left">
                {{ trans('global.back_to_list') }}
            </x-ui.button>
        </div>
    </div>

    @if($contentPage->use_builder)
    <x-ui.alert variant="info" class="text-sm">
        Cette page utilise le <strong>Page Builder visuel</strong>. Le contenu HTML est géré dans le builder. Vous pouvez toujours modifier les métadonnées (titre, catégories, tags) via le formulaire ci-dessous.
    </x-ui.alert>
    @endif

    <x-ui.card>
        @livewire('content-page.edit', [$contentPage])
    </x-ui.card>
</div>

@endsection
