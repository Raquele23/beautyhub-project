<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Meu Perfil Profissional') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('professional.edit') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                    {{ __('Editar Perfil') }}
                </a>
                <a href="{{ route('services.index') }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700">
                    {{ __('Meus Serviços') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        <p><strong>{{ __('Nome') }}:</strong> {{ Auth::user()->name }}</p>
                        @if($professional->establishment_name)
                            <p><strong>{{ __('Estabelecimento') }}:</strong> {{ $professional->establishment_name }}</p>
                        @endif
                        <p><strong>{{ __('Descrição') }}:</strong> {{ $professional->description }}</p>
                        <p><strong>{{ __('Telefone') }}:</strong> {{ $professional->phone }}</p>
                        <p><strong>{{ __('Endereço') }}:</strong> {{ $professional->full_address }}</p>
                        @if($professional->instagram)
                            <p><strong>{{ __('Instagram') }}:</strong> <a target="_blank" href="https://instagram.com/{{ ltrim($professional->instagram, '@') }}">{{ $professional->instagram }}</a></p>
                        @endif
                        @if($professional->profile_photo)
                            <div>
                                <strong>{{ __('Foto de Perfil') }}:</strong><br>
                                <img src="{{ Storage::url($professional->profile_photo) }}" alt="Perfil" class="h-32 w-32 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- portfólio mostra aqui apenas leitura --}}
            @if($professional->portfolioPhotos->count() > 0)
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Portfólio') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($professional->portfolioPhotos as $photo)
                            <div>
                                <img src="{{ Storage::url($photo->photo) }}" alt="" class="w-full h-48 object-cover rounded-lg">
                                @if($photo->description)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $photo->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
