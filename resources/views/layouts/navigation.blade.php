<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('professional.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::check() && Auth::user()->isProfessional() && Auth::user()->professional)
                        <x-nav-link :href="route('professional.show')" :active="request()->routeIs('professional.show') || request()->routeIs('professional.edit') || request()->routeIs('professional.portfolio.*')">
                            {{ __('Meu Perfil') }}
                        </x-nav-link>
                        <x-nav-link :href="route('services.index')" :active="request()->routeIs('services*')">
                            {{ __('Meus Serviços') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.availability')" :active="request()->routeIs('professional.availability')">
                            {{ __('Disponibilidade') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.appointments')" :active="request()->routeIs('professional.appointments')">
                            {{ __('Agendamentos') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::check() && Auth::user()->isClient())
                        <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                            {{ __('Meus Agendamentos') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- Sino de notificações --}}
                @auth
                    @php $unread = Auth::user()->unreadNotificationsCount(); @endphp
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unread > 0)
                                <span class="absolute top-1 right-1 h-4 w-4 bg-purple-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown de notificações --}}
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                             style="display: none;">

                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Notifications</span>
                                @if($unread > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-purple-600 hover:text-purple-700 font-medium transition">
                                           Marcar todas como lidas
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                    <a href="{{ route('notifications.open', $notification->id) }}"
                                       @click.stop
                                       class="flex items-start gap-3 px-4 py-3 {{ $notification->isUnread() ? 'bg-purple-50 dark:bg-purple-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                                        <div class="mt-0.5 flex-shrink-0">
                                            @if($notification->type === 'appointment_confirmed')
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                            @elseif($notification->type === 'appointment_cancelled')
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </span>
                                            @else
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">{{ $notification->message }}</p>
                                            <p class="text-[11px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>

                                        @if($notification->isUnread())
                                            <span class="mt-1.5 flex-shrink-0 h-2 w-2 rounded-full bg-purple-500"></span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                        Nenhuma notificação.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Settings Dropdown --}}
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                            Cadastrar
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('professional.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::check() && Auth::user()->isProfessional() && Auth::user()->professional)
                <x-responsive-nav-link :href="route('professional.show')" :active="request()->routeIs('professional.show') || request()->routeIs('professional.edit') || request()->routeIs('professional.portfolio.*')">
                    {{ __('Meu Perfil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services*')">
                    {{ __('Meus Serviços') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.availability')" :active="request()->routeIs('professional.availability')">
                    {{ __('Disponibilidade') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.appointments')" :active="request()->routeIs('professional.appointments')">
                    {{ __('Agendamentos') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::check() && Auth::user()->isClient())
                <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                    {{ __('Meus Agendamentos') }}
                </x-responsive-nav-link>
            @endif
        </div>

        {{-- Notificações no mobile --}}
        @auth
        <div class="pt-2 pb-3 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4 mb-2 flex items-center justify-between">
                <span class="text-sm font-bold text-gray-800 dark:text-gray-200">Notifications</span>
                @if($unread > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs text-purple-600 font-medium">Marcar todas como lidas</button>
                    </form>
                @endif
            </div>
            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                <a href="{{ route('notifications.open', $notification->id) }}"
                   class="block px-4 py-2 {{ $notification->isUnread() ? 'bg-purple-50 dark:bg-purple-900/10' : '' }}">
                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ $notification->message }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="px-4 py-2 text-xs text-gray-400">No notifications.</div>
            @endforelse
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="mt-3 space-y-1 px-4">
                <x-responsive-nav-link :href="route('login')">{{ __('Entrar') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">{{ __('Cadastrar') }}</x-responsive-nav-link>
            </div>
        </div>
        @endauth
    </div>
</nav>