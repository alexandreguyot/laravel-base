@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.contentCategory.title'), 'href' => route('content-categories.index')],
        ['label' => trans('global.edit')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">
            {{ trans('global.edit') }} {{ trans('cruds.contentCategory.title_singular') }}
            <span class="text-base-content/40 font-normal text-lg">#{{ $contentCategory->id }}</span>
        </h1>
        <x-ui.button href="{{ route('content-categories.index') }}" variant="ghost" icon="arrow-left">
            {{ trans('global.back_to_list') }}
        </x-ui.button>
    </div>

    <x-ui.card>
        @livewire('content-category.edit', [$contentCategory])
    </x-ui.card>
</div>

@endsection
