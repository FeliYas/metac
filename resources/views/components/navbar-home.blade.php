@props(['logos', 'contactos', 'cliente' => null])
<nav class="w-full z-50" x-data="navbarData">
    <!-- Versión móvil: Logo y menú hamburguesa -->
    <div class="bg-main-color lg:hidden">
        <div class="flex justify-between items-center h-[70px] max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset(Route::currentRouteName() == 'home' ? $logos[0]->path : $logos[1]->path) }}"
                        alt="logo" class="w-38 h-16">
                </a>
            </div>
            <div class="mt-1.5">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class=" focus:outline-none">
                    <i class="fa-solid fa-bars text-xl text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="lg:hidden bg-white shadow-lg overflow-hidden transition-all duration-300 absolute w-full z-40"
        :class="mobileMenuOpen ? 'max-h-screen' : 'max-h-0'" x-cloak>
        <div class="flex flex-col px-4 py-2 text-black">
            @if (Route::currentRouteName() == 'zonaprivada.fichatecnica' ||
                    Route::currentRouteName() == 'zonaprivada.fichaseguridad')
                <a href="{{ route('zonaprivada.fichatecnica') }}" class="py-2 border-b border-gray-200">Fichas
                    Técnicas</a>
                <a href="{{ route('zonaprivada.fichaseguridad') }}" class="py-2 border-b border-gray-200">Fichas de
                    Seguridad</a>
                <a href="{{ route('home') }}" class="py-2 border-b border-gray-200">Ir al Inicio</a>
                <form method="POST" action="{{ route('cliente.logout') }}">
                    @csrf
                    <button type="submit" class="py-2 border-b border-gray-200 w-full text-left">Cerrar Sesión</button>
                </form>
            @else
                <a href="{{ route('nosotros') }}" class="py-2 border-b border-gray-200">{{ __('Nosotros') }}</a>
                <a href="{{ route('categorias') }}" class="py-2 border-b border-gray-200">{{ __('Productos') }}</a>
                <a href="{{ route('industrias') }}" class="py-2 border-b border-gray-200">{{ __('Industria') }}</a>
                <a href="{{ route('calidad') }}" class="py-2 border-b border-gray-200">{{ __('Calidad') }}</a>
                <a href="{{ route('novedades') }}" class="py-2 border-b border-gray-200">{{ __('Novedades') }}</a>
                <a href="{{ route('contacto') }}" class="py-2 border-b border-gray-200">{{ __('Contacto') }}</a>
                <a href="#" @click.prevent="openLoginModal()" class="py-2 border-b border-gray-200">ZONA
                    PRIVADA</a>
            @endif
            <div class="flex items-center py-2">
                <i class="fa-solid fa-envelope mr-2 text-gray-600"></i>
                @foreach ($contactos as $contacto)
                    @if ($contacto->email)
                        <a href="mailto:{{ $contacto->email }}">
                            <p class="text-gray-600">{{ $contacto->email }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @if (Route::currentRouteName() == 'zonaprivada.fichatecnica' ||
            Route::currentRouteName() == 'zonaprivada.fichaseguridad')
        <div class="hidden lg:block fixed w-full h-[90px] transition-all duration-300 z-50 bg-white shadow-lg">
            <div class="max-w-[90%] lg:max-w-[1224px] mx-auto flex justify-between items-center h-full">
                <div class="max-w-[72px] h-[72px]">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset($logos[1]->path) }}" alt="logo" class="w-[72px] h-[72px]">
                    </a>
                </div>
                <div class="flex items-center gap-4 text-black">
                    <div class="flex lg:gap-3 2xl:gap-6  xl:text-base items-center">
                        @php $currentRoute = Route::currentRouteName(); @endphp
                        <a href="{{ route('zonaprivada.fichatecnica') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ $currentRoute == 'zonaprivada.fichatecnica' ? 'font-bold' : '' }}">
                            Fichas Técnicas
                        </a>
                        <a href="{{ route('zonaprivada.fichaseguridad') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ $currentRoute == 'zonaprivada.fichaseguridad' ? 'font-bold' : '' }}">
                            Fichas de Seguridad
                        </a>
                    </div>
                </div>
                <div>
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        @if ($cliente)
                            <button @click="userMenuOpen = !userMenuOpen"
                                class="btn-primary text-[15px] w-[150px] font-medium flex items-center justify-center gap-2">
                                <span>{{ $cliente->nombre }} {{ substr($cliente->apellido, 0, 1) }}.</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                    :class="userMenuOpen ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Submenú desplegable -->
                            <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95" @click.away="userMenuOpen = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#5C93CB] transition-colors">
                                    <i class="fas fa-home mr-2"></i>
                                    Ir al Inicio
                                </a>

                                <hr class="my-1 border-gray-200">

                                <form method="POST" action="{{ route('cliente.logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('cliente.logout') }}">
                                @csrf
                                <button type="submit" class="btn-primary text-[15px] w-[150px] font-medium">Cerrar
                                    Sesión</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="hidden lg:block fixed w-full h-[90px] transition-all duration-300 z-50"
            :class="'{{ Route::currentRouteName() }}'
            === 'home' ? (scrolled ? 'bg-white shadow-lg' : 'bg-transparent') : 'bg-white shadow-lg'">
            <div class="max-w-[90%] lg:max-w-[1224px] mx-auto flex justify-between items-center h-full">
                <div class="max-w-[72px] h-[72px]">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset($logos[1]->path) }}" alt="logo" class="w-[72px] h-[72px]">
                    </a>
                </div>
                <div class="flex items-center gap-4 transition-colors duration-100"
                    :class="'{{ Route::currentRouteName() }}'
                    === 'home' ? (scrolled ? 'text-black' : 'text-white') : 'text-black'">
                    <div class="flex lg:gap-3 2xl:gap-6 items-center">
                        @php $currentRoute = Route::currentRouteName(); @endphp
                        <a href="{{ route('nosotros') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ $currentRoute == 'nosotros' ? 'font-bold' : '' }}">
                            {{ __('Nosotros') }}
                        </a>
                        <a href="{{ route('categorias') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ in_array($currentRoute, ['categorias', 'subcategorias', 'productos', 'producto']) ? 'font-bold' : '' }}">
                            {{ __('Productos') }}
                        </a>
                        <a href="{{ route('industrias') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ in_array($currentRoute, ['industrias', 'industria']) ? 'font-bold' : '' }}">
                            {{ __('Industrias') }}
                        </a>
                        <a href="{{ route('calidad') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ $currentRoute == 'calidad' ? 'font-bold' : '' }}">
                            {{ __('Calidad') }}
                        </a>
                        <a href="{{ route('novedades') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ in_array($currentRoute, ['novedades', 'novedad']) ? 'font-bold' : '' }}">
                            {{ __('Novedades') }}
                        </a>
                        <a href="{{ route('contacto') }}"
                            class="relative hover:text-[#5C93CB] transition duration-200 {{ $currentRoute == 'contacto' ? 'font-bold' : '' }}">
                            {{ __('Contacto') }}
                        </a>
                    </div>
                </div>
                <div>
                    <a href="#" @click.prevent="openLoginModal()"
                        class="btn-primary text-[15px] w-[150px] font-medium">Zona
                        privada</a>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Login -->
    <div x-show="showLoginModal" x-cloak x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black opacity-50" @click.self="showLoginModal = false"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-lg relative"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.away="showLoginModal = false">

                <button @click="showLoginModal = false"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Iniciar Sesión</h2>
                    <p class="text-gray-600">Accede a tu zona privada</p>
                </div>

                <form @submit.prevent="submitLogin()">
                    <div class="space-y-4 text-black">
                        <div>
                            <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                            <input type="text" id="usuario" x-model="loginForm.usuario" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ingresa tu usuario">
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password"
                                    x-model="loginForm.password" required
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Ingresa tu contraseña">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-400"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="loginError" x-text="loginError"
                        class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg"></div>

                    <div class="mt-6 space-y-3">
                        <button type="submit" class="btn-primary w-full flex items-center justify-center"
                            :disabled="loginLoading">
                            <svg x-show="loginLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="loginLoading ? 'Iniciando sesión...' : 'Iniciar Sesión'"></span>
                        </button>

                        <div class="text-center">
                            <span class="text-gray-600">¿No tienes cuenta? </span>
                            <button type="button" @click="showLoginModal = false; showRegisterModal = true"
                                class="text-[#A2D32D] hover:underline font-medium">Regístrate aquí</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div x-show="showRegisterModal" class="fixed inset-0 z-50" x-cloak
        x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black opacity-50" @click.self="showRegisterModal = false"></div>

        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white p-8 rounded-2xl w-full max-w-2xl shadow-lg max-h-[90vh] overflow-y-auto relative"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.away="showRegisterModal = false">

                <button @click="showRegisterModal = false"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Crear Cuenta</h2>
                    <p class="text-gray-600">Regístrate para acceder a la zona privada</p>
                </div>

                <form @submit.prevent="submitRegistration()" class="text-black">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="reg_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre
                                *</label>
                            <input type="text" id="reg_nombre" x-model="registerForm.nombre" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tu nombre">
                        </div>

                        <div>
                            <label for="reg_apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido
                                *</label>
                            <input type="text" id="reg_apellido" x-model="registerForm.apellido" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="reg_usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario
                                *</label>
                            <input type="text" id="reg_usuario" x-model="registerForm.usuario" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Nombre de usuario">
                        </div>

                        <div>
                            <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                *</label>
                            <input type="email" id="reg_email" x-model="registerForm.email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="tu@email.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="reg_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono
                                *</label>
                            <input type="tel" id="reg_telefono" x-model="registerForm.telefono" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tu teléfono">
                        </div>

                        <div>
                            <label for="reg_cuit" class="block text-sm font-medium text-gray-700 mb-1">CUIT *</label>
                            <input type="text" id="reg_cuit" x-model="registerForm.cuit" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="XX-XXXXXXXX-X">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña
                                *</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="reg_password"
                                    x-model="registerForm.password" required
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Mínimo 6 caracteres">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="reg_password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" id="reg_password_confirmation"
                                    x-model="registerForm.password_confirmation" required
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Repite tu contraseña">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-400"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="registerError" x-text="registerError"
                        class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg"></div>
                    <div x-show="registerSuccess" x-text="registerSuccess"
                        class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg"></div>

                    <div class="space-y-3">
                        <button type="submit" class="btn-primary w-full flex items-center justify-center"
                            :disabled="registerLoading">
                            <svg x-show="registerLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="registerLoading ? 'Creando cuenta...' : 'Crear Cuenta'"></span>
                        </button>

                        <div class="text-center">
                            <span class="text-gray-600">¿Ya tienes cuenta? </span>
                            <button type="button" @click="showRegisterModal = false; openLoginModal()"
                                class="text-[#A2D32D] hover:underline font-medium">Inicia sesión aquí</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</nav>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            scrolled: false,
            mobileMenuOpen: false,
            showLoginModal: false,
            showRegisterModal: false,
            showPassword: false,
            showConfirmPassword: false,
            loginError: null,
            registerError: null,
            registerSuccess: null,
            loginLoading: false,
            registerLoading: false,
            loginForm: {
                usuario: '',
                password: ''
            },
            registerForm: {
                nombre: '',
                apellido: '',
                usuario: '',
                email: '',
                telefono: '',
                cuit: '',
                password: '',
                password_confirmation: ''
            },

            init() {
                this.handleScroll();
                window.addEventListener('scroll', () => this.handleScroll());

                // Hacer la función global para que pueda ser llamada desde otras páginas
                window.showLoginForDownload = (documentType) => {
                    this.loginError =
                        `Necesitas iniciar sesión para descargar ${documentType.toLowerCase()}.`;
                    this.showLoginModal = true;
                };
            },

            handleScroll() {
                this.scrolled = window.scrollY > 50;
            },

            openLoginModal() {
                this.loginError = null; // Limpiar errores previos al abrir normalmente
                this.showLoginModal = true;
            },

            submitLogin() {
                // Limpiar errores previos y activar loading
                this.loginError = null;
                this.loginLoading = true;

                // Prepare data object
                const data = {
                    usuario: this.loginForm.usuario,
                    password: this.loginForm.password
                };

                // Submit using axios
                axios.post('{{ route('cliente.login') }}', data, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Login success response:', response.data);
                        if (response.data.success) {
                            window.location.href = response.data.redirect;
                        } else {
                            this.loginError = response.data.message ||
                                'Error al iniciar sesión';
                        }
                    })
                    .catch(error => {
                        console.error('Login error:', error);
                        console.error('Error status:', error.response?.status);
                        console.error('Error data:', error.response?.data);

                        if (error.response) {
                            // Error del servidor (4xx, 5xx)
                            this.loginError = error.response.data?.message ||
                                `Error del servidor (${error.response.status}): ${error.response.statusText}`;
                        } else if (error.request) {
                            // La request se hizo pero no se recibió respuesta
                            console.error('No response received:', error.request);
                            this.loginError =
                                'No se recibió respuesta del servidor. Verifique su conexión.';
                        } else {
                            // Algo pasó al configurar la request
                            console.error('Request setup error:', error.message);
                            this.loginError = 'Error al configurar la solicitud: ' + error
                                .message;
                        }
                    })
                    .finally(() => {
                        // Desactivar loading state
                        this.loginLoading = false;
                    });
            },

            submitRegistration() {
                // Limpiar errores previos y activar loading
                this.registerError = null;
                this.registerSuccess = null;
                this.registerLoading = true;

                // Prepare data object
                const data = {
                    nombre: this.registerForm.nombre,
                    apellido: this.registerForm.apellido,
                    usuario: this.registerForm.usuario,
                    email: this.registerForm.email,
                    telefono: this.registerForm.telefono,
                    cuit: this.registerForm.cuit,
                    password: this.registerForm.password,
                    password_confirmation: this.registerForm.password_confirmation
                };

                // Submit using axios
                axios.post('{{ route('cliente.register') }}', data, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Registration success response:', response.data);
                        if (response.data.success) {
                            this.registerSuccess = response.data.message;
                            this.resetForm();
                            // Cerrar modal después de 3 segundos
                            setTimeout(() => {
                                this.showRegisterModal = false;
                                this.registerSuccess = null;
                            }, 3000);
                        } else {
                            this.registerError = response.data.message ||
                                'Error al registrarse. Intente nuevamente.';
                        }
                    })
                    .catch(error => {
                        console.error('Registration error:', error);
                        console.error('Error status:', error.response?.status);
                        console.error('Error statusText:', error.response?.statusText);
                        console.error('Error headers:', error.response?.headers);
                        console.error('Error data:', error.response?.data);
                        console.error('Full error response:', error.response);

                        if (error.response) {
                            // Error del servidor (4xx, 5xx)
                            this.registerError = error.response.data?.message ||
                                `Error del servidor (${error.response.status}): ${error.response.statusText}`;
                        } else if (error.request) {
                            // La request se hizo pero no se recibió respuesta
                            console.error('No response received:', error.request);
                            this.registerError =
                                'No se recibió respuesta del servidor. Verifique su conexión.';
                        } else {
                            // Algo pasó al configurar la request
                            console.error('Request setup error:', error.message);
                            this.registerError = 'Error al configurar la solicitud: ' + error
                                .message;
                        }
                    })
                    .finally(() => {
                        // Desactivar loading state
                        this.registerLoading = false;
                    });
            },

            resetForm() {
                this.registerForm = {
                    nombre: '',
                    apellido: '',
                    usuario: '',
                    email: '',
                    telefono: '',
                    cuit: '',
                    password: '',
                    password_confirmation: ''
                };
            }
        }));
    });
</script>
