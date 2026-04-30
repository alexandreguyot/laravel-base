@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.faqQuestion.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.faqQuestion.title') }}</h1>
        @can('faq_question_create')
            <x-ui.button href="{{ route('faq-questions.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.faqQuestion.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('faq-question.index')
    </x-ui.card>
</div>

@endsection
