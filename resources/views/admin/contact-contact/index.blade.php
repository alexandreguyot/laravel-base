@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.contactContact.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.contactContact.title') }}</h1>
        @can('contact_contact_create')
            <x-ui.button href="{{ route('contact-contacts.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.contactContact.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('contact-contact.index')
    </x-ui.card>
</div>

@endsection
