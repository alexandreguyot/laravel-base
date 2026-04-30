@extends('layouts.admin')
@section('content')

<div class="space-y-6">

    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold">{{ trans('global.dashboard') }}</h1>
        <p class="text-base-content/60 text-sm mt-1">
            Bonjour {{ auth()->user()->name }}, bienvenue dans votre espace d'administration.
        </p>
    </div>

    {{-- Stats --}}
    <div class="stats stats-vertical sm:stats-horizontal shadow bg-base-100 w-full">
        @feature('user_management')
        @can('user_access')
        <x-ui.stat
            title="{{ trans('cruds.user.title') }}"
            value="{{ \App\Models\User::count() }}"
            icon="users"
            variant="primary"
            desc="utilisateurs enregistrés"
        />
        @endcan
        @endfeature

        @feature('product_management')
        @can('product_access')
        <x-ui.stat
            title="{{ trans('cruds.product.title') }}"
            value="{{ \App\Models\Product::count() }}"
            icon="package"
            variant="secondary"
            desc="produits dans le catalogue"
        />
        @endcan
        @endfeature

        @feature('task_management')
        @can('task_access')
        <x-ui.stat
            title="{{ trans('cruds.task.title') }}"
            value="{{ \App\Models\Task::count() }}"
            icon="list-checks"
            variant="accent"
            desc="tâches enregistrées"
        />
        @endcan
        @endfeature

        @feature('crm')
        @can('crm_customer_access')
        <x-ui.stat
            title="{{ trans('cruds.crmCustomer.title') }}"
            value="{{ \App\Models\CrmCustomer::count() }}"
            icon="briefcase"
            desc="clients CRM"
        />
        @endcan
        @endfeature
    </div>

    {{-- Raccourcis --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @feature('product_management')
        @can('product_access')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-secondary/10 text-secondary rounded-box p-3">
                    <x-icon name="package" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">{{ trans('cruds.product.title') }}</div>
                    <div class="text-xs text-base-content/50">Gérer le catalogue</div>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endcan
        @endfeature

        @feature('crm')
        @can('crm_customer_access')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 text-primary rounded-box p-3">
                    <x-icon name="briefcase" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">CRM</div>
                    <div class="text-xs text-base-content/50">Clients & contacts</div>
                </div>
                <a href="{{ route('crm-customers.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endcan
        @endfeature

        @feature('task_management')
        @can('task_access')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-accent/10 text-accent rounded-box p-3">
                    <x-icon name="list-check" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">Tâches</div>
                    <div class="text-xs text-base-content/50">Suivi des activités</div>
                </div>
                <a href="{{ route('tasks.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endcan
        @endfeature

        @feature('messages')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-info/10 text-info rounded-box p-3">
                    <x-icon name="mail" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">{{ __('global.messages') }}</div>
                    <div class="text-xs text-base-content/50">
                        @if(!empty($unreadConversations['all']))
                            <span class="badge badge-error badge-xs">{{ $unreadConversations['all'] }} non lus</span>
                        @else
                            Aucun message non lu
                        @endif
                    </div>
                </div>
                <a href="{{ route('messages.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endfeature

        @feature('lms')
        @can('course_access')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-success/10 text-success rounded-box p-3">
                    <x-icon name="school" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">Formations</div>
                    <div class="text-xs text-base-content/50">Cours & tests</div>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endcan
        @endfeature

        @can('user_management_access')
        <x-ui.card compact>
            <div class="flex items-center gap-4">
                <div class="bg-warning/10 text-warning rounded-box p-3">
                    <x-icon name="toggle-left" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold">Feature Flags</div>
                    <div class="text-xs text-base-content/50">Activer / désactiver des modules</div>
                </div>
                <a href="{{ route('feature-flags.index') }}" class="btn btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </x-ui.card>
        @endcan

    </div>
</div>

@endsection
