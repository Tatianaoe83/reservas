<x-guest-layout>
    <div class="h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 via-cyan-500 to-teal-500 p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob" style="animation-delay: 4s;"></div>
        </div>

        <!-- Grid de 2 columnas -->
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 xl:gap-12 items-center relative z-10">
            <!-- Columna Izquierda: Branding -->
            <div class="flex flex-col items-center lg:items-start justify-center text-center lg:text-left transform transition-all duration-500 py-4">
                <div class="flex items-center justify-center h-16 w-16 lg:h-20 lg:w-20 xl:h-24 xl:w-24 rounded-full bg-white shadow-2xl mb-4 lg:mb-5 transform transition-all duration-300 hover:rotate-12 hover:shadow-blue-300">
                    <svg class="h-10 w-10 lg:h-12 lg:w-12 xl:h-14 xl:w-14 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h2 class="text-3xl lg:text-4xl xl:text-5xl font-black text-white mb-3 lg:mb-4 drop-shadow-lg leading-tight">
                    Sistema de Reservas
                </h2>
                <div class="inline-flex items-center justify-center px-3 py-1.5 lg:px-4 lg:py-2 bg-white/20 backdrop-blur-sm rounded-full mb-3 lg:mb-4">
                    <svg class="h-4 w-4 lg:h-5 lg:w-5 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm lg:text-base xl:text-lg text-white font-bold">
                        Venta de Hielo
                    </p>
                </div>
                <p class="text-xs lg:text-sm xl:text-base text-white/90 font-medium max-w-md">
                    Inicia sesión para gestionar tus reservas de manera eficiente y rápida.
                </p>
            </div>

            <!-- Columna Derecha: Formulario -->
            <div class="w-full max-w-md mx-auto lg:max-w-lg">
                <!-- Card del Formulario con efecto glassmorphism -->
                <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl p-5 lg:p-6 xl:p-8 border border-white/20 transform transition-all duration-300 hover:shadow-3xl">
        <x-validation-errors class="mb-4" />

        @session('status')
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm font-semibold">{{ $value }}</p>
                        </div>
            </div>
        @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-4 lg:space-y-5">
            @csrf

                    <!-- Campo Email -->
            <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                Correo Electrónico
                            </span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 lg:h-5 lg:w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="admin@reservas.com"
                                class="block w-full pl-10 lg:pl-12 pr-3 lg:pr-4 py-3 lg:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 ease-in-out text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white hover:border-gray-300 text-sm lg:text-base"
                            />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
            </div>

                    <!-- Campo Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Contraseña
                            </span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 lg:pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 lg:h-5 lg:w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="block w-full pl-10 lg:pl-12 pr-3 lg:pr-4 py-3 lg:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 ease-in-out text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white hover:border-gray-300 text-sm lg:text-base"
                            />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
            </div>

                    <!-- Recordarme y Olvidé contraseña -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center group">
                            <input 
                                id="remember_me" 
                                name="remember" 
                                type="checkbox" 
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-150 cursor-pointer"
                            />
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 font-semibold cursor-pointer group-hover:text-gray-900 transition-colors">
                                Recordarme
                </label>
            </div>

                @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-bold text-blue-600 hover:text-blue-700 transition duration-150 ease-in-out hover:underline">
                                    ¿Olvidaste tu contraseña?
                    </a>
                            </div>
                @endif
                    </div>

                    <!-- Botón de Login -->
                    <div class="pt-1">
                        <button 
                            type="submit" 
                            class="group relative w-full flex justify-center items-center py-3 lg:py-3.5 px-4 border border-transparent text-sm lg:text-base font-extrabold rounded-xl text-white bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600 hover:from-blue-700 hover:via-cyan-700 hover:to-teal-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-300 ease-in-out shadow-xl hover:shadow-2xl transform hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <svg class="h-6 w-6 text-white/80 group-hover:text-white group-hover:animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="tracking-wide">INICIAR SESIÓN</span>
                            <span class="absolute right-0 inset-y-0 flex items-center pr-4">
                                <svg class="h-5 w-5 text-white/80 group-hover:text-white group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                    <!-- Información adicional -->
                    <div class="mt-4 lg:mt-5 pt-3 lg:pt-4 border-t-2 border-gray-100">
                        <div class="flex items-center justify-center space-x-2 text-xs lg:text-sm">
                            <div class="flex items-center justify-center h-7 w-7 lg:h-8 lg:w-8 rounded-full bg-blue-100">
                                <svg class="h-3 w-3 lg:h-4 lg:w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-gray-600 font-semibold">Acceso solo para administradores</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 lg:hidden">
                    <p class="text-sm text-white/80 font-medium">
                        Sistema de Gestión de Reservas © {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer para pantallas grandes (fuera del grid) -->
        <div class="hidden lg:block absolute bottom-4 left-1/2 transform -translate-x-1/2 text-center">
            <p class="text-sm text-white/80 font-medium">
                Sistema de Gestión de Reservas © {{ date('Y') }}
            </p>
        </div>
    </div>

</x-guest-layout>
