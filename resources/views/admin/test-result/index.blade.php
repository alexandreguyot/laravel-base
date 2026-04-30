@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.testResult.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.testResult.title') }}</h1>
        @can('test_result_create')
            <x-ui.button href="{{ route('test-results.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.testResult.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('test-result.index')
    </x-ui.card>
</div>

@endsection
