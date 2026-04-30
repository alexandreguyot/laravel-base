@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.course.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.course.title') }}</h1>
        @can('course_create')
            <x-ui.button href="{{ route('courses.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.course.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('course.index')
    </x-ui.card>
</div>

@endsection
