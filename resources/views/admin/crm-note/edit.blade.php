@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.crmNote.title'), 'href' => route('crm-notes.index')],
        ['label' => trans('global.edit')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">
            {{ trans('global.edit') }} {{ trans('cruds.crmNote.title_singular') }}
            <span class="text-base-content/40 font-normal text-lg">#{{ $crmNote->id }}</span>
        </h1>
        <x-ui.button href="{{ route('crm-notes.index') }}" variant="ghost" icon="arrow-left">
            {{ trans('global.back_to_list') }}
        </x-ui.button>
    </div>

    <x-ui.card>
        @livewire('crm-note.edit', [$crmNote])
    </x-ui.card>
</div>

@endsection
