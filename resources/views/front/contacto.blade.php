@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', __('Contacto'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('contacto') }}" class="font-extralight hover:underline">{{ __('Contacto') }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ __('Contacto') }}</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Contacto') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-16 flex flex-col gap-10 lg:gap-12">
            <!-- Mensajes de feedback -->
            @if (session('success'))
                <div id="successMessage"
                    class="fixed top-6 right-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 shadow-lg transition-opacity duration-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ __(session('success')) }}</span>
                    <button type="button" class="ml-auto" onclick="document.getElementById('successMessage').remove()">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <script>
                    setTimeout(function() {
                        const message = document.getElementById('successMessage');
                        if (message) {
                            message.style.opacity = '0';
                            setTimeout(() => message.remove(), 500);
                        }
                    }, 5000);
                </script>
            @endif
            @if (session('error'))
                <div id="errorMessage"
                    class="fixed top-6 right-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-lg transition-opacity duration-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ __(session('error')) }}</span>
                    <button type="button" class="ml-auto" onclick="document.getElementById('errorMessage').remove()">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <script>
                    setTimeout(function() {
                        const message = document.getElementById('errorMessage');
                        if (message) {
                            message.style.opacity = '0';
                            setTimeout(() => message.remove(), 500);
                        }
                    }, 5000);
                </script>
            @endif
            @if ($errors->any())
                <div id="validationErrors"
                    class="fixed top-6 right-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-lg transition-opacity duration-500">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold">{{ __('Por favor corrija los siguientes errores:') }}</span>
                        <button type="button" class="ml-auto"
                            onclick="document.getElementById('validationErrors').remove()">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ __($error) }}</li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    setTimeout(function() {
                        const message = document.getElementById('validationErrors');
                        if (message) {
                            message.style.opacity = '0';
                            setTimeout(() => message.remove(), 500);
                        }
                    }, 7000);
                </script>
            @endif
            <div class="flex flex-col lg:flex-row gap-8 text-[#303030]">
                <div class="lg:w-1/3 flex flex-col gap-6">
                    @foreach ($contactos as $contacto)
                        @if ($contacto->direccion)
                            <a href="https://maps.google.com/?q={{ urlencode($contacto->direccion) }}" target="_blank"
                                class="no-underline text-inherit hover:text-main-color flex gap-3 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 22 22"
                                    fill="none">
                                    <path
                                        d="M18.3337 9.16671C18.3337 14.6667 11.0003 20.1667 11.0003 20.1667C11.0003 20.1667 3.66699 14.6667 3.66699 9.16671C3.66699 7.22179 4.43961 5.35652 5.81488 3.98126C7.19014 2.60599 9.0554 1.83337 11.0003 1.83337C12.9452 1.83337 14.8105 2.60599 16.1858 3.98126C17.561 5.35652 18.3337 7.22179 18.3337 9.16671Z"
                                        stroke="#A2D32D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M11 11.9166C12.5188 11.9166 13.75 10.6854 13.75 9.16663C13.75 7.64784 12.5188 6.41663 11 6.41663C9.48122 6.41663 8.25 7.64784 8.25 9.16663C8.25 10.6854 9.48122 11.9166 11 11.9166Z"
                                        stroke="#A2D32D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p>
                                    {{ $contacto->direccion }}
                                </p>
                            </a>
                        @endif
                        @if ($contacto->telefono)
                            <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefono) }}"
                                class="no-underline text-inherit hover:text-main-color flex gap-3 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23"
                                    fill="none">
                                    <path
                                        d="M20.1663 16.0428V18.7928C20.1674 19.0481 20.1151 19.3008 20.0128 19.5347C19.9105 19.7687 19.7605 19.9786 19.5724 20.1512C19.3843 20.3238 19.1622 20.4552 18.9203 20.537C18.6785 20.6188 18.4222 20.6492 18.168 20.6262C15.3473 20.3197 12.6377 19.3558 10.2572 17.812C8.04233 16.4046 6.16455 14.5268 4.75715 12.312C3.20797 9.92061 2.24388 7.19792 1.94299 4.36451C1.92008 4.11102 1.95021 3.85554 2.03145 3.61433C2.11269 3.37313 2.24326 3.15148 2.41486 2.9635C2.58645 2.77552 2.79531 2.62533 3.02813 2.52249C3.26095 2.41965 3.51263 2.36642 3.76715 2.36618H6.51715C6.96202 2.3618 7.3933 2.51933 7.7306 2.80942C8.06791 3.0995 8.28822 3.50234 8.35049 3.94284C8.46656 4.8229 8.68182 5.68701 8.99215 6.51868C9.11549 6.84677 9.14218 7.20335 9.06907 7.54615C8.99596 7.88895 8.82611 8.20361 8.57965 8.45284L7.41549 9.61701C8.72041 11.9119 10.6206 13.8121 12.9155 15.117L14.0797 13.9528C14.3289 13.7064 14.6435 13.5365 14.9863 13.4634C15.3291 13.3903 15.6857 13.417 16.0138 13.5403C16.8455 13.8507 17.7096 14.0659 18.5897 14.182C19.0349 14.2448 19.4416 14.4691 19.7323 14.8122C20.023 15.1553 20.1775 15.5933 20.1663 16.0428Z"
                                        stroke="#A2D32D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p>
                                    {{ $contacto->telefono }}
                                </p>
                            </a>
                        @endif
                        @if ($contacto->email)
                            <a href="mailto:{{ $contacto->email }}"
                                class="no-underline text-inherit hover:text-main-color flex gap-2 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23"
                                    fill="none">
                                    <path
                                        d="M18.333 3.83337H3.66634C2.65382 3.83337 1.83301 4.69149 1.83301 5.75004V17.25C1.83301 18.3086 2.65382 19.1667 3.66634 19.1667H18.333C19.3455 19.1667 20.1663 18.3086 20.1663 17.25V5.75004C20.1663 4.69149 19.3455 3.83337 18.333 3.83337Z"
                                        stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M20.1663 6.70837L11.9438 12.1709C11.6608 12.3562 11.3336 12.4546 10.9997 12.4546C10.6657 12.4546 10.3385 12.3562 10.0555 12.1709L1.83301 6.70837"
                                        stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <p class="ml-1">
                                    {{ $contacto->email }}
                                </p>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="lg:w-2/3">
                    <form action="{{ route('contacto.enviar') }}" method="POST" class="w-full space-y-6"
                        id="contactForm">
                        @csrf
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="w-full relative">
                                <label for="nombre" class="block mb-1">{{ __('Nombre y apellido') }}*</label>
                                <input type="text" name="nombre" id="nombre" required
                                    class="border border-[#E7E7E7] w-full h-12 px-4 focus:border-main-color focus:outline-none transition-colors">
                            </div>
                            <div class="w-full relative">
                                <label for="email" class="block mb-1">{{ __('E-mail') }}*</label>
                                <input type="text" name="email" id="email" required
                                    class="border border-[#E7E7E7] w-full h-12 px-4 focus:border-main-color focus:outline-none transition-colors">
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="w-full relative">
                                <label for="telefono" class="block mb-1">{{ __('Telefono') }}*</label>
                                <input type="text" name="telefono" id="telefono" required
                                    class="border border-[#E7E7E7] w-full h-12 px-4 focus:border-main-color focus:outline-none transition-colors">
                            </div>
                            <div class="w-full relative">
                                <label for="empresa" class="block mb-1">{{ __('Empresa') }}</label>
                                <input type="text" name="empresa" id="empresa"
                                    class="border border-[#E7E7E7] w-full h-12 px-4 focus:border-main-color focus:outline-none transition-colors">
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-6">
                            <div class="w-full py-2 relative">
                                <label for="mensaje" class="block mb-1">{{ __('Mensaje') }}*</label>
                                <textarea name="mensaje" id="mensaje" cols="30" rows="10" required
                                    class="border border-[#E7E7E7] w-full px-4 py-2 h-[158px] focus:border-main-color focus:outline-none transition-colors"></textarea>
                            </div>
                            <div class="w-full flex items-end justify-between gap-10 lg:mb-3">
                                <span class="mb-2.5 text-[15px]">*Datos obligatorios</span>
                                <div class="mt-auto flex flex-col items-center justify-center ">
                                    <!-- Agregamos campo oculto para almacenar el token de reCAPTCHA -->
                                    <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                                    <button type="button" id="submitBtn"
                                        class="btn-primary w-full lg:w-[184px] relative text-lg text-[15px] font-medium">
                                        <span id="submitText" class="inline-block">{{ __('Enviar consulta') }}</span>
                                        <span id="loadingIndicator"
                                            class="hidden absolute inset-0 items-center justify-center">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span class="ml-2">{{ __('Enviando...') }}</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto mb-33">
            <div class="w-full h-[570px]">
                {!! preg_replace(['/width="[^"]*"/', '/height="[^"]*"/'], ['width="100%"', 'height="100%"'], $mapa) !!}
            </div>
        </div>
    </div>
    <!-- Script de reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=6Le9oW4rAAAAAPABHBcb_pH7yEk8pqtURfFDL24z"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar evento al botón de envío
            document.getElementById('submitBtn').addEventListener('click', handleSubmit);

            function handleSubmit(e) {
                e.preventDefault();

                // Mostrar indicador de carga
                const submitText = document.getElementById('submitText');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const submitBtn = document.getElementById('submitBtn');

                // Desactivar el botón y mostrar el indicador de carga
                submitBtn.disabled = true;
                submitText.classList.add('opacity-0');
                loadingIndicator.classList.remove('hidden');
                loadingIndicator.classList.add('flex');

                // Activar reCAPTCHA
                grecaptcha.ready(function() {
                    grecaptcha.execute('6Le9oW4rAAAAAPABHBcb_pH7yEk8pqtURfFDL24z', {
                        action: 'submit_contact'
                    }).then(function(token) {
                        // Guardar el token en el campo oculto
                        document.getElementById('recaptchaResponse').value = token;

                        // Enviar el formulario
                        document.getElementById('contactForm').submit();
                    }).catch(function(error) {
                        // Restaurar el botón en caso de error
                        submitBtn.disabled = false;
                        submitText.classList.remove('opacity-0');
                        loadingIndicator.classList.add('hidden');
                        loadingIndicator.classList.remove('flex');

                        console.error('Error de reCAPTCHA:', error);
                    });
                });
            }
        });
    </script>

    <style>
        /* Estilo para el placeholder */
        ::placeholder {
            color: #666;
            opacity: 1;
            transition: opacity 0.2s;
        }

        /* Cuando el input está enfocado, hacemos que el placeholder sea más transparente */
        input:focus::placeholder,
        textarea:focus::placeholder {
            opacity: 0.5;
        }

        /* Animación al enfocar los campos */
        input:focus,
        textarea:focus {
            border-color: #c87800 !important;
            box-shadow: 0 0 0 1px rgba(225, 35, 40, 0.2);
        }
    </style>
@endsection
