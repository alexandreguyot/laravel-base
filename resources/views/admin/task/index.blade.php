@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.task.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.task.title') }}</h1>
        @can('task_create')
            <x-ui.button href="{{ route('tasks.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.task.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('task.index')
    </x-ui.card>
</div>

@endsection
