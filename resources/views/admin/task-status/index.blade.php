@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.taskStatus.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.taskStatus.title') }}</h1>
        @can('task_status_create')
            <x-ui.button href="{{ route('task-statuses.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.taskStatus.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('task-status.index')
    </x-ui.card>
</div>

@endsection
