@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.contactCompany.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.contactCompany.title') }}</h1>
        @can('contact_company_create')
            <x-ui.button href="{{ route('contact-companies.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.contactCompany.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('contact-company.index')
    </x-ui.card>
</div>

@endsection
