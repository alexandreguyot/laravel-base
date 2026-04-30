@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.questionOption.title'), 'href' => route('question-options.index')],
        ['label' => trans('global.create')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('global.create') }} {{ trans('cruds.questionOption.title_singular') }}</h1>
        <x-ui.button href="{{ route('question-options.index') }}" variant="ghost" icon="arrow-left">
            {{ trans('global.back_to_list') }}
        </x-ui.button>
    </div>

    <x-ui.card>
        @livewire('question-option.create')
    </x-ui.card>
</div>

@endsection
