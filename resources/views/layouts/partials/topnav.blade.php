{{-- Top navigation — layout horizontal --}}
<div class="navbar bg-base-100 shadow-sm sticky top-0 z-30 px-4 gap-2">

    {{-- Logo --}}
    <div class="navbar-start gap-2">
        <a href="{{ route('home') }}" class="btn btn-ghost gap-2 font-bold text-primary">
            <x-icon name="layout-dashboard" class="w-5 h-5" />
            <span class="hidden sm:inline">{{ trans('panel.site_title') }}</span>
        </a>
    </div>

    {{-- Menu principal (desktop) --}}
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal menu-sm gap-1 px-0">

            <li>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <x-icon name="layout-dashboard" class="w-4 h-4" />
                    {{ trans('global.dashboard') }}
                </a>
            </li>

            @feature('product_management')@can('product_management_access')
            <li>
                <details>
                    <summary><x-icon name="shopping-cart" class="w-4 h-4" /> {{ trans('cruds.productManagement.title') }}</summary>
                    <ul class="bg-base-100 rounded-box shadow-lg z-50 w-48">
                        @can('product_access')<li><a href="{{ route('products.index') }}">{{ trans('cruds.product.title') }}</a></li>@endcan
                        @can('product_category_access')<li><a href="{{ route('product-categories.index') }}">{{ trans('cruds.productCategory.title') }}</a></li>@endcan
                        @can('product_tag_access')<li><a href="{{ route('product-tags.index') }}">{{ trans('cruds.productTag.title') }}</a></li>@endcan
                    </ul>
                </details>
            </li>
            @endcan@endfeature

            @feature('crm')@can('basic_c_r_m_access')
            <li>
                <details>
                    <summary><x-icon name="briefcase" class="w-4 h-4" /> CRM</summary>
                    <ul class="bg-base-100 rounded-box shadow-lg z-50 w-48">
                        @can('crm_customer_access')<li><a href="{{ route('crm-customers.index') }}">{{ trans('cruds.crmCustomer.title') }}</a></li>@endcan
                        @can('crm_note_access')<li><a href="{{ route('crm-notes.index') }}">{{ trans('cruds.crmNote.title') }}</a></li>@endcan
                        @can('crm_status_access')<li><a href="{{ route('crm-statuses.index') }}">{{ trans('cruds.crmStatus.title') }}</a></li>@endcan
                    </ul>
                </details>
            </li>
            @endcan@endfeature

            @feature('task_management')@can('task_management_access')
            <li>
                <details>
                    <summary><x-icon name="list-check" class="w-4 h-4" /> Tâches</summary>
                    <ul class="bg-base-100 rounded-box shadow-lg z-50 w-48">
                        @can('task_access')<li><a href="{{ route('tasks.index') }}">{{ trans('cruds.task.title') }}</a></li>@endcan
                        @can('task_status_access')<li><a href="{{ route('task-statuses.index') }}">{{ trans('cruds.taskStatus.title') }}</a></li>@endcan
                    </ul>
                </details>
            </li>
            @endcan@endfeature

            @feature('lms')
            <li>
                <details>
                    <summary><x-icon name="school" class="w-4 h-4" /> Formation</summary>
                    <ul class="bg-base-100 rounded-box shadow-lg z-50 w-48">
                        @can('course_access')<li><a href="{{ route('courses.index') }}">{{ trans('cruds.course.title') }}</a></li>@endcan
                        @can('lesson_access')<li><a href="{{ route('lessons.index') }}">{{ trans('cruds.lesson.title') }}</a></li>@endcan
                        @can('test_access')<li><a href="{{ route('tests.index') }}">{{ trans('cruds.test.title') }}</a></li>@endcan
                    </ul>
                </details>
            </li>
            @endfeature

            @feature('messages')
            <li>
                <a href="{{ route('messages.index') }}" class="relative {{ request()->is('*/messages*') ? 'active' : '' }}">
                    <x-icon name="mail" class="w-4 h-4" />
                    {{ __('global.messages') }}
                    @if(!empty($unreadConversations['all']))
                        <span class="badge badge-error badge-xs absolute -top-1 -right-1">{{ $unreadConversations['all'] }}</span>
                    @endif
                </a>
            </li>
            @endfeature

            @can('user_management_access')
            <li>
                <details>
                    <summary><x-icon name="users" class="w-4 h-4" /> Admin</summary>
                    <ul class="bg-base-100 rounded-box shadow-lg z-50 w-48">
                        @can('user_access')<li><a href="{{ route('users.index') }}">{{ trans('cruds.user.title') }}</a></li>@endcan
                        @can('role_access')<li><a href="{{ route('roles.index') }}">{{ trans('cruds.role.title') }}</a></li>@endcan
                        @can('permission_access')<li><a href="{{ route('permissions.index') }}">{{ trans('cruds.permission.title') }}</a></li>@endcan
                        <li class="border-t border-base-200 mt-1"><a href="{{ route('feature-flags.index') }}">Features</a></li>
                        <li><a href="{{ route('settings.index') }}">Paramètres</a></li>
                    </ul>
                </details>
            </li>
            @endcan

        </ul>
    </div>

    {{-- Droite --}}
    <div class="navbar-end gap-2">
        {{-- Search --}}
        @feature('global_search')
        <div class="hidden md:block">
            @livewire('global-search')
        </div>
        @endfeature

        {{-- Language --}}
        @feature('language_switcher')
        @if(file_exists(app_path('Livewire/LanguageSwitcher.php')))
            <livewire:language-switcher />
        @endif
        @endfeature

        {{-- Notifications --}}
        @feature('user_alerts')
        <div class="dropdown dropdown-end">
            <button tabindex="0" class="btn btn-ghost btn-circle relative">
                <x-icon name="bell" class="w-5 h-5" />
                @php $alertCount = auth()->user()?->alerts()->wherePivot('seen_at', null)->count() ?? 0; @endphp
                @if($alertCount)
                    <span class="badge badge-error badge-xs absolute top-1 right-1">{{ $alertCount }}</span>
                @endif
            </button>
            <div tabindex="0" class="dropdown-content z-50 card card-compact w-72 shadow bg-base-100 mt-1">
                <div class="card-body p-0 divide-y divide-base-200">
                    @forelse(auth()->user()?->alerts()->latest()->take(6)->get() ?? [] as $alert)
                        <a href="{{ $alert->link ?? '#' }}" onclick="fetch('/admin/user-alerts/seen')"
                            class="flex items-start gap-3 px-4 py-3 hover:bg-base-200 text-sm
                                {{ $alert->pivot->seen_at ? 'opacity-50' : '' }}">
                            <x-icon name="bell" class="w-4 h-4 mt-0.5 shrink-0" />
                            {{ $alert->message }}
                        </a>
                    @empty
                        <p class="px-4 py-3 text-sm text-base-content/50">Aucune notification</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endfeature

        {{-- Mobile hamburger --}}
        <div class="dropdown dropdown-end lg:hidden">
            <button tabindex="0" class="btn btn-ghost btn-circle">
                <x-icon name="menu" class="w-5 h-5" />
            </button>
            <ul tabindex="0" class="dropdown-content z-50 menu menu-sm bg-base-100 rounded-box shadow-lg w-56 mt-1 p-2">
                <li><a href="{{ route('home') }}"><x-icon name="layout-dashboard" class="w-4 h-4" />Dashboard</a></li>
                @feature('product_management')@can('product_access')<li><a href="{{ route('products.index') }}">Produits</a></li>@endcan@endfeature
                @feature('crm')@can('crm_customer_access')<li><a href="{{ route('crm-customers.index') }}">CRM</a></li>@endcan@endfeature
                @feature('task_management')@can('task_access')<li><a href="{{ route('tasks.index') }}">Tâches</a></li>@endcan@endfeature
                @feature('messages')<li><a href="{{ route('messages.index') }}">Messages</a></li>@endfeature
            </ul>
        </div>

        {{-- User menu --}}
        <div class="dropdown dropdown-end">
            <button tabindex="0" class="btn btn-ghost gap-2 px-2">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-8">
                        <span class="text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 2)) }}
                        </span>
                    </div>
                </div>
                <span class="hidden md:block text-sm font-medium max-w-[120px] truncate">
                    {{ auth()->user()?->name }}
                </span>
            </button>
            <ul tabindex="0" class="dropdown-content z-50 menu menu-sm shadow bg-base-100 rounded-box w-48 mt-1 p-1">
                @if(file_exists(app_path('Http/Controllers/Auth/UserProfileController.php')))
                    @can('auth_profile_edit')
                    <li><a href="{{ route('profile.show') }}">
                        <x-icon name="user" class="w-4 h-4" /> Mon profil
                    </a></li>
                    @endcan
                @endif
                <li class="border-t border-base-200 mt-1 pt-1">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
                        class="text-error">
                        <x-icon name="logout" class="w-4 h-4" /> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
