@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.product.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.product.title') }}</h1>
        @can('product_create')
            <x-ui.button href="{{ route('products.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('product.index')
    </x-ui.card>
</div>

@endsection
