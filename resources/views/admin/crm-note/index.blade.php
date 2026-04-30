@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.crmNote.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.crmNote.title') }}</h1>
        @can('crm_note_create')
            <x-ui.button href="{{ route('crm-notes.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.crmNote.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('crm-note.index')
    </x-ui.card>
</div>

@endsection
