@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.testAnswer.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.testAnswer.title') }}</h1>
        @can('test_answer_create')
            <x-ui.button href="{{ route('test-answers.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.testAnswer.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('test-answer.index')
    </x-ui.card>
</div>

@endsection
