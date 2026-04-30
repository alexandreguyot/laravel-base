@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.contentCategory.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.contentCategory.title') }}</h1>
        @can('content_category_create')
            <x-ui.button href="{{ route('content-categories.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.contentCategory.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('content-category.index')
    </x-ui.card>
</div>

@endsection
