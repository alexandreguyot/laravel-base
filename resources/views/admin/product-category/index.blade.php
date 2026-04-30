@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.productCategory.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.productCategory.title') }}</h1>
        @can('product_category_create')
            <x-ui.button href="{{ route('product-categories.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.productCategory.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('product-category.index')
    </x-ui.card>
</div>

@endsection
