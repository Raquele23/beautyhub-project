<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    nav, nav * { font-family: 'Poppins', sans-serif !important; }
</style>

<nav x-data="{ open: false }" style="background-color: #EDE4F8;" class="relative border-b-2 border-purple-300 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            @php $unread = auth()->check() ? auth()->user()->unreadNotificationsCount() : 0; @endphp

            {{-- ── Logo ── --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/img/Beauty_Hub.png') }}" alt="Beauty Hub" class="h-16 w-auto object-contain">
                    </a>
                </div>

                {{-- ── Links desktop ── --}}
                <div class="hidden space-x-1 sm:ms-8 sm:flex">

                    @if(auth()->check() && auth()->user()->isProfessional() && auth()->user()->professional)
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

                    @if(auth()->check() && auth()->user()->isClient())
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
                        <x-nav-link :href="route('client.calendar')" :active="request()->routeIs('client.calendar')"
                            class="!text-purple-800 !text-sm !font-medium hover:!text-purple-600 !px-3 !py-2 !rounded-lg hover:!bg-purple-100 transition-all duration-150">
                            {{ __('Calendário') }}
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
                    {{-- Sino desktop --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="relative p-2 rounded-xl text-purple-600 hover:bg-purple-200 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{-- Badge: data-notif-badge permite atualização via JS --}}
                            <span data-notif-badge
                                  class="absolute top-1 right-1 h-4 w-4 bg-purple-700 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none"
                                  {{ $unread === 0 ? 'style=display:none' : '' }}>
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
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

                            <div class="max-h-80 overflow-y-auto divide-y divide-purple-50" data-notif-content>
                                @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
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
                                            @elseif(in_array($notification->type, ['review_received', 'review_reply_received']))
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.519-4.674z" />
                                                    </svg>
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
                                <span>{{ auth()->user()->name }}</span>
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
            <div class="-me-1 flex items-center gap-1 sm:hidden">
                @auth
                    {{-- Sino mobile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="relative inline-flex items-center justify-center p-2 rounded-xl text-purple-600 hover:bg-purple-200 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{-- Badge: data-notif-badge permite atualização via JS --}}
                            <span data-notif-badge
                                  class="absolute top-1 right-1 h-4 w-4 bg-purple-700 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none"
                                  {{ $unread === 0 ? 'style=display:none' : '' }}>
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-1rem)] bg-white rounded-2xl shadow-xl border border-purple-100 z-50 overflow-hidden"
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

                            <div class="max-h-80 overflow-y-auto divide-y divide-purple-50" data-notif-content>
                                @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
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
                                            @elseif(in_array($notification->type, ['review_received', 'review_reply_received']))
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.519-4.674z" />
                                                    </svg>
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
                @endauth

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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div
            x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
            class="absolute right-3 top-full z-50 mt-2 w-64 max-w-[calc(100vw-1.5rem)] origin-top-right max-h-[calc(100vh-5.5rem)] overflow-y-auto rounded-2xl border border-purple-100 bg-white/95 p-3 shadow-2xl backdrop-blur-md"
            style="display: none;"
        >
            <div class="space-y-1">
                @if(auth()->check() && auth()->user()->isProfessional() && auth()->user()->professional)
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

                @if(auth()->check() && auth()->user()->isClient())
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
                    <x-responsive-nav-link :href="route('client.calendar')" :active="request()->routeIs('client.calendar')"
                        class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                        {{ __('Calendário') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reviews.client.index')" :active="request()->routeIs('reviews.client.index')"
                        class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                        {{ __('Minhas Avaliações') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            @auth
                <div class="pt-3 pb-3 border-t border-purple-200">
                    <div class="space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')"
                            class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                <span>{{ __('Minha conta') }}</span>
                            </span>
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="!text-purple-800 !font-medium hover:!bg-purple-100 !rounded-xl !px-3">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </span>
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
    </div>
</nav>

{{-- ── Polling de notificações + som ─────────────────────────────────────── --}}
@auth
<script>
(function () {
    const POLL_INTERVAL = 15000;
    const POLL_URL      = '{{ route('notifications.poll') }}';
    const LIST_URL      = '{{ route('notifications.list') }}';

    let lastKnownId  = null;
    let initialized  = false;
    let audioCtx     = null;
    let audioReady   = false;
    let pendingSound = false;

    const SHOULD_RELOAD = {{ request()->routeIs('professional.appointments', 'client.appointments') ? 'true' : 'false' }};

    async function playNotificationSound() {
        try {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }

            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            audioReady = true;
            pendingSound = false;

            [0, 0.18].forEach(function (delay) {
                const osc  = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.type            = 'sine';
                osc.frequency.value = 880;

                const t = audioCtx.currentTime + delay;
                gain.gain.setValueAtTime(0, t);
                gain.gain.linearRampToValueAtTime(0.55, t + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.001, t + 0.25);

                osc.start(t);
                osc.stop(t + 0.25);
            });
        } catch (e) {}
    }

    async function ensureAudioReady() {
        try {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }

            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            audioReady = audioCtx.state === 'running';

            if (audioReady && pendingSound) {
                await playNotificationSound();
            }
        } catch (e) {}
    }

    function updateBadges(count) {
        document.querySelectorAll('[data-notif-badge]').forEach(function (el) {
            if (count > 0) {
                el.textContent  = count > 9 ? '9+' : count;
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function getIconHtml(iconType) {
        let bgClass, textClass, svgContent;

        switch (iconType) {
            case 'confirmed':
                bgClass = 'bg-green-100';
                textClass = 'text-green-600';
                svgContent = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                break;
            case 'cancelled':
                bgClass = 'bg-red-100';
                textClass = 'text-red-500';
                svgContent = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                break;
            case 'completed':
                bgClass = 'bg-blue-100';
                textClass = 'text-blue-600';
                svgContent = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                break;
            case 'review':
                bgClass = 'bg-purple-100';
                textClass = 'text-purple-600';
                svgContent = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.519-4.674z"/></svg>';
                break;
            default:
                bgClass = 'bg-purple-100';
                textClass = 'text-purple-600';
                svgContent = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        }

        return '<span class="flex h-7 w-7 items-center justify-center rounded-full ' + bgClass + ' ' + textClass + '">' + svgContent + '</span>';
    }

    function renderNotificationItem(notification) {
        const bgClass = notification.is_unread ? 'bg-purple-50' : '';
        const dotHtml = notification.is_unread ? '<span class="mt-1.5 flex-shrink-0 h-2 w-2 rounded-full bg-purple-500"></span>' : '';

        return `
            <a href="${notification.url}" onclick="event.preventDefault(); window.location.href='${notification.url}'" 
               class="flex items-start gap-3 px-4 py-3 ${bgClass} hover:bg-purple-50 transition">
                <div class="mt-0.5 flex-shrink-0">
                    ${getIconHtml(notification.icon_type)}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-700 leading-snug">${notification.message}</p>
                    <p class="text-[11px] text-purple-300 mt-1">${notification.created_at}</p>
                </div>
                ${dotHtml}
            </a>
        `;
    }

    async function updateNotificationDropdown() {
        try {
            const res = await fetch(LIST_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });

            if (!res.ok) return;

            const data = await res.json();

            updateBadges(data.unread_count);

            const contentDivs = document.querySelectorAll('[data-notif-content]');
            contentDivs.forEach(function (div) {
                if (data.notifications.length === 0) {
                    div.innerHTML = '<div class="px-4 py-8 text-center text-sm text-purple-300">Nenhuma notificação.</div>';
                } else {
                    div.innerHTML = data.notifications.map(renderNotificationItem).join('');
                }
            });

        } catch (e) {}
    }

    async function poll() {
        try {
            const res = await fetch(POLL_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });

            if (!res.ok) return;

            const data = await res.json();

            if (!initialized) {
                lastKnownId = data.latest_id;
                initialized = true;
            } else {
                if (data.latest_id && data.latest_id !== lastKnownId) {
                    if (audioReady) {
                        playNotificationSound();
                    } else {
                        pendingSound = true;
                        ensureAudioReady();
                    }
                    lastKnownId = data.latest_id;

                    updateNotificationDropdown();

                    if (SHOULD_RELOAD) {
                        setTimeout(function () { location.reload(); }, 300);
                        return;
                    }
                }
            }

            updateBadges(data.unread_count);

        } catch (e) {}
    }

    ['pointerdown', 'touchstart', 'keydown', 'click'].forEach(function (eventName) {
        document.addEventListener(eventName, ensureAudioReady, { once: true, passive: true });
    });

    poll();
    setInterval(poll, POLL_INTERVAL);
})();
</script>
@endauth