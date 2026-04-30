@extends('layouts.admin')
@section('content')

<div class="space-y-4">
    <x-ui.breadcrumb :items="[
        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],
        ['label' => trans('cruds.user.title')],
    ]" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ trans('cruds.user.title') }}</h1>
        
    </div>

    <x-ui.card>
        @livewire('user.index')
    </x-ui.card>
</div>

@endsection
