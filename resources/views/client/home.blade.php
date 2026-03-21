<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <div class="min-h-screen" style="background-color: #EDE4F8;">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 space-y-10">

            {{-- PERFIL --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Perfil</p>
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center gap-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    {{-- Avatar --}}
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 text-2xl font-extrabold text-purple-700"
                         style="background-color: #E3D0F9;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-purple-300 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-5 py-2 rounded-full text-sm font-semibold text-purple-700 border border-purple-200 hover:border-purple-400 hover:shadow-md transition-all duration-200 flex-shrink-0"
                       style="background-color: #F3EBFD;">
                        Editar perfil
                    </a>
                </div>
            </div>

            {{-- PRÓXIMO AGENDAMENTO CONFIRMADO --}}
            @if($nextAppointment)
                <div class="rounded-2xl p-6 text-white shadow-lg shadow-purple-300"
                     style="background: linear-gradient(135deg, #6A0DAD 0%, #9333EA 100%);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest opacity-70 mb-1">Próximo agendamento</p>
                            <p class="text-xl font-bold">{{ $nextAppointment->service->name }}</p>
                            <p class="text-sm opacity-75 mt-0.5">
                                com {{ $nextAppointment->professional->establishment_name ?? $nextAppointment->professional->user->name }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-4xl font-extrabold">{{ $nextAppointment->scheduled_at->format('d') }}</p>
                            <p class="text-sm opacity-70 uppercase tracking-wide">{{ $nextAppointment->scheduled_at->isoFormat('MMM') }}</p>
                            <p class="text-sm opacity-70">{{ $nextAppointment->scheduled_at->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ATALHOS --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Menu</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="{{ route('client.appointments') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Meus Agendamentos</p>
                            <p class="text-xs text-purple-400 mt-0.5">Ver histórico e próximos</p>
                        </div>
                    </a>

                    <a href="{{ route('explore') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Explorar</p>
                            <p class="text-xs text-purple-400 mt-0.5">Encontrar profissionais</p>
                        </div>
                    </a>

                    <a href="{{ route('reviews.client.index') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Minhas Avaliações</p>
                            <p class="text-xs text-purple-400 mt-0.5">Ver avaliações feitas</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- INDICAÇÕES --}}
            <div class="border-t border-purple-200 pt-8">
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Outros</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4">
                        <p class="text-sm font-semibold text-gray-800">Indicações</p>
                        <span class="text-xs text-purple-400 bg-purple-50 border border-purple-100 px-2 py-1 rounded-full ml-auto">Em breve</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
