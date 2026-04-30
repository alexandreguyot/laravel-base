@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.crmCustomer.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.crmCustomer.title') }}</h1>
        @can('crm_customer_create')
            <x-ui.button href="{{ route('crm-customers.create') }}" icon="plus">
                {{ trans('global.add') }} {{ trans('cruds.crmCustomer.title_singular') }}
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        @livewire('crm-customer.index')
    </x-ui.card>
</div>

@endsection
