{{-- Topbar (affiché dans le layout sidebar-gauche) --}}
<div class="navbar bg-base-100 shadow-sm sticky top-0 z-30 gap-2 px-4">

    {{-- Hamburger mobile --}}
    <label for="admin-drawer" class="btn btn-ghost btn-sm drawer-button lg:hidden">
        <x-icon name="menu" class="w-5 h-5" />
    </label>

    {{-- Search global --}}
    @feature('global_search')
        <div class="flex-1 max-w-sm hidden md:block">
            @livewire('global-search')
        </div>
    @endfeature

    <div class="flex-1"></div>

    {{-- Language switcher --}}
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
            <div tabindex="0" class="dropdown-content z-[1] card card-compact w-72 shadow bg-base-100 mt-1">
                <div class="card-body p-0">
                    <div class="px-4 py-3 border-b border-base-200 font-semibold text-sm">Notifications</div>
                    <ul class="divide-y divide-base-200 max-h-64 overflow-y-auto">
                        @forelse(auth()->user()?->alerts()->latest()->take(8)->get() ?? [] as $alert)
                            <li>
                                <a href="{{ $alert->link ?? '#' }}"
                                    onclick="fetch('/admin/user-alerts/seen')"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-base-200 text-sm
                                        {{ $alert->pivot->seen_at ? 'opacity-50' : '' }}">
                                    <x-icon name="{{ $alert->link ? 'link' : 'bell' }}" class="w-4 h-4 mt-0.5 shrink-0" />
                                    <span>{{ $alert->message }}</span>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-3 text-sm text-base-content/50">Aucune notification</li>
                        @endforelse
                    </ul>
                    <div class="p-2 border-t border-base-200">
                        <a href="{{ route('user-alerts.index') }}" class="btn btn-ghost btn-xs btn-block">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endfeature

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
            <x-icon name="chevron-down" class="w-4 h-4 opacity-50" />
        </button>
        <ul tabindex="0" class="dropdown-content z-[1] menu menu-sm shadow bg-base-100 rounded-box w-48 mt-1 p-1">
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
