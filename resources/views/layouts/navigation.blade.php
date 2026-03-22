<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    nav, nav * { font-family: 'Poppins', sans-serif !important; }
</style>

<nav x-data="{ open: false }" style="background-color: #EDE4F8;" class="border-b-2 border-purple-300 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ── Logo ── --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/img/Beauty_Hub.png') }}" alt="Beauty Hub" class="h-16 w-auto object-contain">
                    </a>
                </div>

                {{-- ── Links desktop ── --}}
                <div class="hidden space-x-1 sm:ms-8 sm:flex">

                    @if(Auth::check() && Auth::user()->isProfessional() && Auth::user()->professional)
                        <x-nav-link :href="route('professional.dashboard')" :active="request()->routeIs('professional.dashboard')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.show')" :active="request()->routeIs('professional.show') || request()->routeIs('professional.edit') || request()->routeIs('professional.portfolio.*')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Meu Perfil') }}
                        </x-nav-link>
                        <x-nav-link :href="route('services.index')" :active="request()->routeIs('services*')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Meus Serviços') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.availability')" :active="request()->routeIs('professional.availability')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Disponibilidade') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.appointments')" :active="request()->routeIs('professional.appointments')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Agendamentos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professional.calendar')" :active="request()->routeIs('professional.calendar')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Calendário') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reviews.professional.index')" :active="request()->routeIs('reviews.professional.index')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Avaliações') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::check() && Auth::user()->isClient())
                        <x-nav-link :href="route('client.home')" :active="request()->routeIs('client.home')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Início') }}
                        </x-nav-link>
                        <x-nav-link :href="route('explore')" :active="request()->routeIs('explore')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Explorar') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.appointments')" :active="request()->routeIs('client.appointments')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Meus Agendamentos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reviews.client.index')" :active="request()->routeIs('reviews.client.index')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Minhas Avaliações') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            {{-- ── Direita: notificações + user ── --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">

                @auth
                    @php $unread = Auth::user()->unreadNotificationsCount(); @endphp

                    {{-- Sino --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="relative p-2 rounded-xl text-purple-600 hover:bg-purple-200 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unread > 0)
                                <span class="absolute top-1 right-1 h-4 w-4 bg-purple-700 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-purple-100 z-50 overflow-hidden"
                             style="display: none;">

                            <div class="flex items-center justify-between px-4 py-3 border-b border-purple-100">
                                <span class="text-sm font-bold text-purple-900">Notificações</span>
                                @if($unread > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-purple-600 hover:text-purple-800 font-semibold transition">
                                            Marcar todas como lidas
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-purple-50">
                                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                    <a href="{{ route('notifications.open', $notification->id) }}"
                                       @click.stop
                                       class="flex items-start gap-3 px-4 py-3 {{ $notification->isUnread() ? 'bg-purple-50' : '' }} hover:bg-purple-50 transition">
                                        <div class="mt-0.5 flex-shrink-0">
                                            @if($notification->type === 'appointment_confirmed')
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-green-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                            @elseif($notification->type === 'appointment_cancelled')
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100 text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </span>
                                            @elseif($notification->type === 'appointment_completed')
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </span>
                                            @else
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-700 leading-snug">{{ $notification->message }}</p>
                                            <p class="text-[11px] text-purple-300 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if($notification->isUnread())
                                            <span class="mt-1.5 flex-shrink-0 h-2 w-2 rounded-full bg-purple-500"></span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center text-sm text-purple-300">
                                        Nenhuma notificação.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- ── User dropdown ── --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-purple-800 bg-purple-100 hover:bg-purple-200 transition-all duration-150">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
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
                           class="text-sm font-semibold text-purple-700 hover:text-purple-900 transition">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-purple-700 text-white text-sm font-semibold rounded-full hover:bg-purple-800 shadow-md shadow-purple-300 transition-all duration-150">
                            Cadastrar
                        </a>
                    </div>
                @endauth
            </div>

            {{-- ── Hamburger ── --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-purple-600 hover:bg-purple-200 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Responsive menu ── --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-purple-200">
        <div class="pt-2 pb-3 space-y-1 px-3">

            @if(Auth::check() && Auth::user()->isProfessional() && Auth::user()->professional)
                <x-responsive-nav-link :href="route('professional.dashboard')" :active="request()->routeIs('professional.dashboard')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.show')" :active="request()->routeIs('professional.show') || request()->routeIs('professional.edit')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Meu Perfil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services*')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Meus Serviços') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.availability')" :active="request()->routeIs('professional.availability')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Disponibilidade') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.appointments')" :active="request()->routeIs('professional.appointments')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Agendamentos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professional.calendar')" :active="request()->routeIs('professional.calendar')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Calendário') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reviews.professional.index')" :active="request()->routeIs('reviews.professional.index')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Avaliações') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::check() && Auth::user()->isClient())
                <x-responsive-nav-link :href="route('client.home')" :active="request()->routeIs('client.home')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Início') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('explore')" :active="request()->routeIs('explore')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Explorar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.appointments')" :active="request()->routeIs('client.appointments')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Meus Agendamentos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reviews.client.index')" :active="request()->routeIs('reviews.client.index')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Minhas Avaliações') }}
                </x-responsive-nav-link>
            @endif

        </div>

        @auth
        <div class="pt-2 pb-3 border-t border-purple-200 px-3">
            <div class="px-2 mb-2 flex items-center justify-between">
                <span class="text-sm font-bold text-purple-900">Notificações</span>
                @if($unread > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs text-purple-600 font-semibold">Marcar todas como lidas</button>
                    </form>
                @endif
            </div>
            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                <a href="{{ route('notifications.open', $notification->id) }}"
                   class="block px-3 py-2 rounded-xl mb-1 {{ $notification->isUnread() ? 'bg-purple-100' : 'hover:bg-purple-50' }} transition">
                    <p class="text-xs text-purple-900 font-medium">{{ $notification->message }}</p>
                    <p class="text-[11px] text-purple-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="px-3 py-2 text-xs text-purple-400">Nenhuma notificação.</div>
            @endforelse
        </div>

        <div class="pt-3 pb-3 border-t border-purple-200 px-3">
            {{-- Avatar + info no menu mobile --}}
            <div class="px-2 mb-2 flex items-center gap-3">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                         alt="Foto"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-purple-300">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold ring-2 ring-purple-300"
                         style="background: linear-gradient(135deg, #6A0DAD, #A675D6);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <div class="font-semibold text-sm text-purple-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-purple-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-3 pb-3 border-t border-purple-200 px-5 space-y-2">
            <x-responsive-nav-link :href="route('login')"
                class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                {{ __('Entrar') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')"
                class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                {{ __('Cadastrar') }}
            </x-responsive-nav-link>
        </div>
        @endauth
    </div>
</nav>