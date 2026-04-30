@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.questionOption.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.questionOption.title') }}</h1>
        @can('question_option_create')
            <x-ui.button href="{{ route('question-options.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.questionOption.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('question-option.index')
    </x-ui.card>
</div>

@endsection
