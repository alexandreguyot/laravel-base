@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.lesson.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.lesson.title') }}</h1>
        @can('lesson_create')
            <x-ui.button href="{{ route('lessons.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.lesson.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('lesson.index')
    </x-ui.card>
</div>

@endsection
