@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.crmCustomer.title'), 'href' => route('crm-customers.index')],
        ['label' => trans('global.create')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('global.create') }} {{ trans('cruds.crmCustomer.title_singular') }}</h1>
        <x-ui.button href="{{ route('crm-customers.index') }}" variant="ghost" icon="arrow-left">
            {{ trans('global.back_to_list') }}
        </x-ui.button>
    </div>

    <x-ui.card>
        @livewire('crm-customer.create')
    </x-ui.card>
</div>

@endsection
