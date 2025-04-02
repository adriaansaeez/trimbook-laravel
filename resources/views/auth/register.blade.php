<body class="font-sans text-gray-900 antialiased bg-[#FDFDFC]">
    <div class="min-h-screen flex items-center justify-center px-4">

        {{-- Card de login --}}
        <div class="w-full max-w-md bg-white rounded-lg p-6 shadow">

            {{-- Logo y título juntos --}}
            <div class="mb-6 text-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" alt="TrimBook Logo" class="h-10 mx-auto mb-2">
                </a>
                <h1 class="text-3xl font-bold text-blue-500">Iniciar sesión</h1>
            </div>

            {{-- Formulario --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Remember Me --}}
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-blue-500 shadow-sm focus:ring-blue-400" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-orange-400 hover:bg-orange-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</body>
