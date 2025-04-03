<x-guest-layout>
    <div class="min-h-screen bg-[#FDFDFC] flex items-center justify-center">
        <div class="flex flex-col lg:flex-row bg-white rounded-lg max-w-5xl w-full overflow-hidden">

            {{-- Columna izquierda: Frases --}}
            <div class="hidden lg:flex flex-col justify-center w-2/5 px-10 py-10 space-y-6 bg-white">
                <div>
                    <h2 class="text-2xl font-semibold text-blue-500">Optimiza tu barbería</h2>
                    <p class="text-gray-600 text-sm">Con TrimBook, olvídate del caos de agendas.</p>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-blue-500">Control total del equipo</h2>
                    <p class="text-gray-600 text-sm">Gestiona turnos, horarios y rendimiento fácilmente.</p>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-blue-500">Cifras que importan</h2>
                    <p class="text-gray-600 text-sm">Visualiza tus ingresos y estadísticas al instante.</p>
                </div>
            </div>

            {{-- Columna derecha: Formulario --}}
            <div class="w-full lg:w-3/5 p-10">
                <div class="mb-6 text-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" class="h-10 mx-auto" alt="TrimBook Logo">
                    </a>
                    <h1 class="text-2xl font-bold text-blue-500 mt-4">Crea tu cuenta</h1>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                            :value="old('username')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password"
                            name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4">
                        <a class="text-sm text-blue-500 hover:underline" href="{{ route('login') }}">
                            ¿Ya tienes cuenta?
                        </a>
                        <x-primary-button class="bg-orange-400 hover:bg-orange-500">
                            {{ __('Registrarse') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
