@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.userAlert.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.userAlert.title') }}</h1>
        @can('user_alert_create')
            <x-ui.button href="{{ route('user-alerts.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.userAlert.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('user-alert.index')
    </x-ui.card>
</div>

@endsection
