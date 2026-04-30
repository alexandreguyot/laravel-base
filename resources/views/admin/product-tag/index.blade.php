@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.productTag.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.productTag.title') }}</h1>
        @can('product_tag_create')
            <x-ui.button href="{{ route('product-tags.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.productTag.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('product-tag.index')
    </x-ui.card>
</div>

@endsection
