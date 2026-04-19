<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <div class="min-h-screen" style="background-color: #EDE4F8;">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 space-y-10">

            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                        <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Meu Perfil</h1>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                       style="background-color: #6A0DAD;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar perfil
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

                    <div class="relative h-24 w-full" style="background: linear-gradient(135deg, #6A0DAD 0%, #9333EA 100%);">
                        <div class="absolute left-1/2 -bottom-10 -translate-x-1/2 w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden" style="background-color: #EDE4F8;">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                     alt="Foto de perfil"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-purple-300">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-16 px-6 pb-7 text-center">
                        <p class="text-xl font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <div class="mt-3 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-gray-700">
                            <div class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-16 9h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                <span class="truncate">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="inline-flex items-start gap-2">
                                <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex flex-col items-start leading-tight">
                                    <span class="text-purple-500">Perfil ativo</span>
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $pendingAppointmentsCount }} pendente(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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

            <div>
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-purple-50 flex items-center justify-between gap-3">
                        <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Visitados Recentemente</p>
                    </div>

                    <div id="recent-list" class="p-4 space-y-3">
                        <!-- Preenchido via JavaScript -->
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function toDistanceLabel(km) {
            let distance;
            if (km < 1) {
                distance = Math.round(km * 1000) + ' m';
            } else {
                distance = km.toFixed(1).replace('.', ',') + ' km';
            }
            return 'Aprox. ' + distance;
        }

        function toRelativeVisitLabel(timestamp) {
            if (!timestamp) {
                return 'Visitado recentemente';
            }

            const diffMs = Date.now() - timestamp;
            const diffMinutes = Math.floor(diffMs / 60000);

            if (diffMinutes < 1) {
                return 'Visitado agora';
            }

            if (diffMinutes < 60) {
                return 'Visitado há ' + diffMinutes + ' min';
            }

            const diffHours = Math.floor(diffMinutes / 60);

            if (diffHours < 24) {
                return 'Visitado há ' + diffHours + ' h';
            }

            const diffDays = Math.floor(diffHours / 24);

            if (diffDays < 7) {
                return 'Visitado há ' + diffDays + ' dia' + (diffDays > 1 ? 's' : '');
            }

            return 'Visitado recentemente';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const recentList = document.getElementById('recent-list');
            const returnToHome = @json(route('client.home') . '#recent-list');

            if (!recentList || !window.BEAUTY_HUB_VISITOR) {
                return;
            }

            const visited = window.BEAUTY_HUB_VISITOR.getRecent(5);

            if (!visited.length) {
                recentList.innerHTML = `
                    <div class="rounded-xl border border-dashed border-purple-200 px-4 py-6 text-center">
                        <p class="text-sm font-semibold text-gray-700">Nenhum profissional visitado ainda.</p>
                        <p class="text-xs text-purple-400 mt-1">Quando você visitar um perfil, ele aparecerá aqui.</p>
                    </div>
                `;
                return;
            }

            function renderRecent(visitedList, userLat = null, userLon = null) {
                recentList.innerHTML = visitedList.map(v => {
                    const categories = Array.isArray(v.categories) ? v.categories.filter(Boolean) : [];
                    const categoriesHtml = categories.length
                        ? `<div class="mt-1 flex flex-wrap gap-1.5">
                                ${categories.map(function (category) {
                                    return `<span class="text-[10px] font-semibold px-2 py-1 rounded-full whitespace-nowrap" style="background-color: #F3EBFD; color: #6A0DAD;">${category}</span>`;
                                }).join('')}
                           </div>`
                        : '';

                    return `
                        <a href="/professional/${v.id}?return_to=${encodeURIComponent(returnToHome)}"
                           class="flex items-start gap-3 rounded-xl border border-purple-100 px-4 py-3 hover:border-purple-300 transition-colors">
                            ${v.photo
                                ? `<img src="${v.photo}" alt="${v.name}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">`
                                : `<div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-extrabold text-purple-700 flex-shrink-0" style="background-color: #E3D0F9;">
                                    ${v.name.charAt(0).toUpperCase()}
                                  </div>`
                            }
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 truncate">${v.establishmentName}</p>
                                <p class="text-xs text-purple-400 truncate">${toRelativeVisitLabel(v.timestamp)}</p>
                                ${categoriesHtml}
                            </div>
                        </a>
                    `;
                }).join('');
            }

            // Tentar pegar geolocalização
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;
                    renderRecent(visited, userLat, userLon);
                }, function (error) {
                    // Se falhar, renderiza sem distância
                    renderRecent(visited);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                });
            } else {
                // Navegador sem geolocalização
                renderRecent(visited);
            }
        });
    </script>
</x-app-layout>
