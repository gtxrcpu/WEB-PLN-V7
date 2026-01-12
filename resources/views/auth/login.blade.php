<x-guest-layout>
    {{-- TOAST di luar kartu --}}
    <x-alert-toast :message="session('auth_error')" type="error" :ms="4500" />

    <div class="min-h-screen flex items-center justify-center relative py-12 px-4 sm:px-6 lg:px-8">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/background.jpeg') }}" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-purple-900/60 to-indigo-900/70"></div>
        </div>

        {{-- Login Card --}}
        <div class="max-w-md w-full relative z-10">
            <div
                class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 space-y-6 transform hover:shadow-3xl transition-all duration-300 border border-white/20">
                <div class="text-center">
                    <div class="mx-auto mb-6">
                        <img src="{{ asset('/images/logoo.png') }}" alt="Logo"
                            class="h-16 w-auto mx-auto drop-shadow-lg">
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Welcome Back</h2>
                    <p class="mt-2 text-sm text-gray-600">Sign in to continue to your account</p>
                </div>

                <x-auth-session-status class="mb-2" :status="session('status')" />

                {{-- Session Expired Error --}}
                @if($errors->has('session'))
                    <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700 font-medium">{{ $errors->first('session') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                                placeholder="you@example.com">
                        </div>
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePwd()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none">
                                <span id="eyeIconWrapper">
                                    <!-- Eye icon (default) -->
                                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <script>
                        function togglePwd() {
                            const pwd = document.getElementById('password');
                            const wrapper = document.getElementById('eyeIconWrapper');

                            if (pwd.type === 'password') {
                                pwd.type = 'text';
                                wrapper.innerHTML = '<svg class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';
                            } else {
                                pwd.type = 'password';
                                wrapper.innerHTML = '<svg class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
                            }
                        }

                        function showForgotPasswordModal() {
                            const modal = document.getElementById('forgotPasswordModal');
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }

                        function closeForgotPasswordModal() {
                            const modal = document.getElementById('forgotPasswordModal');
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }

                        // Hide browser password icons
                        const s = document.createElement('style');
                        s.textContent = 'input::-ms-reveal { display: none !important; }';
                        document.head.appendChild(s);
                    </script>

                    {{-- Forgot Password Modal --}}
                    <div id="forgotPasswordModal"
                        class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm"
                        onclick="if(event.target === this) closeForgotPasswordModal()">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
                            <div class="text-center">
                                <div
                                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Lupa Password?</h3>
                                <p class="text-gray-600 mb-6">Silakan hubungi Administrator untuk melakukan reset
                                    password akun Anda.</p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <p class="text-sm text-blue-800 font-medium">💡 Hubungi admin melalui:</p>
                                    <p class="text-sm text-blue-700 mt-1">Email atau kontak internal perusahaan</p>
                                </div>
                                <button onclick="closeForgotPasswordModal()"
                                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Mengerti
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200">
                            <span class="ml-2 block text-sm text-gray-700">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <button type="button" onclick="showForgotPasswordModal()"
                                class="text-sm font-medium text-blue-600 hover:text-blue-500 transition cursor-pointer">
                                Forgot password?
                            </button>
                        @endif
                    </div>

                    <button type="submit"
                        class="relative w-full inline-flex items-center justify-center px-12 py-3 overflow-hidden text-lg font-medium text-indigo-600 border-2 border-indigo-600 rounded-full hover:text-white group hover:bg-gray-50">
                        <span
                            class="absolute left-0 block w-full h-0 transition-all bg-indigo-600 opacity-100 group-hover:h-full top-1/2 group-hover:top-0 duration-400 ease"></span>
                        <span
                            class="absolute right-0 flex items-center justify-start w-10 h-10 duration-300 transform translate-x-full group-hover:translate-x-0 ease">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        <span class="relative">Sign In</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>