@props(['logos', 'contactos'])

<footer class="text-white">
    <div class="bg-[#333333]">
        <div
            class="flex flex-col lg:flex-row gap-10 lg:gap-0 justify-between max-w-[80%] xl:max-w-[1224px] mx-auto pb-16 pt-23">
            <div class="flex justify-center lg:justify-start">
                <img src="{{ asset($logos[0]->path) }}" alt="logo" class="object-contain h-[117px] ">
            </div>
            <div class="lg:w-1/4 text-center lg:text-left ml-20">
                <h3 class="font-bold text-xl text-white">{{ __('Secciones') }}</h3>
                <div class="flex flex-row mt-4 font-light gap-25 lg:justify-none items-center lg:items-left">
                    <div class="flex flex-col gap-y-2">
                        <a href="{{ route('nosotros') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Nosotros') }}</a>
                        <a href="{{ route('categorias') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Productos') }}</a>
                        <a href="{{ route('industrias') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Industria') }}</a>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <a href="{{ route('calidad') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Calidad') }}</a>
                        <a href="{{ route('novedades') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Novedades') }}</a>
                        <a href="{{ route('contacto') }}"
                            class="hover:text-[#5C93CB] transition-colors duration-300">{{ __('Contacto') }}</a>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/4 flex flex-col items-start text-center lg:text-start gap-8">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-bold text-xl text-white w-[260px]">{{ __('Suscribite al Newsletter') }}
                        </h3>
                    </div>
                    <form id="newsletterForm" class="w-full h-[45px] flex flex-col items-center">
                        @csrf
                        <div
                            class="w-[289px] h-[45px] border border-white flex justify-between text-white placeholder:text-white">
                            <input type="email" name="email" placeholder="{{ __('Email') }}"
                                class="bg-transparent border-none outline-none text-sm p-3 w-full" required>
                            <button type="submit"
                                class="flex items-center justify-center rounded-r-[20px] px-3 cursor-pointer transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <path d="M7.99999 12H16M16 12L12 16M16 12L12 8" stroke="#A2D32D" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div id="newsletterMessage" class="text-xs mt-2"></div>
                    </form>
                </div>
                @php
                    $hasSocial = false;
                    foreach ($contactos as $contacto) {
                        if ($contacto->facebook || $contacto->instagram) {
                            $hasSocial = true;
                            break;
                        }
                    }
                @endphp
                @if ($hasSocial)
                    <div class="flex flex-col items-center gap-2">
                        <div>
                            <h3 class="font-bold text-xl text-white w-[260px]">{{ __('Seguinos en') }}</h3>
                        </div>
                        <div class="flex gap-3 justify-center lg:justify-start w-full">
                            @foreach ($contactos as $contacto)
                                @if ($contacto->facebook)
                                    <a href="{{ $contacto->facebook }}"
                                        class="group hover:text-[#5C93CB] transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path
                                                d="M14.9997 1.66675H12.4997C11.3946 1.66675 10.3348 2.10573 9.5534 2.88714C8.772 3.66854 8.33301 4.72835 8.33301 5.83341V8.33341H5.83301V11.6667H8.33301V18.3334H11.6663V11.6667H14.1663L14.9997 8.33341H11.6663V5.83341C11.6663 5.6124 11.7541 5.40044 11.9104 5.24416C12.0667 5.08788 12.2787 5.00008 12.4997 5.00008H14.9997V1.66675Z"
                                                fill="currentColor" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($contacto->instagram)
                                    <a href="{{ $contacto->instagram }}"
                                        class="group hover:text-[#5C93CB] transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <g clip-path="url(#clip0_9778_455)">
                                                <path
                                                    d="M14.5837 5.41675H14.592M5.83366 1.66675H14.167C16.4682 1.66675 18.3337 3.53223 18.3337 5.83341V14.1667C18.3337 16.4679 16.4682 18.3334 14.167 18.3334H5.83366C3.53247 18.3334 1.66699 16.4679 1.66699 14.1667V5.83341C1.66699 3.53223 3.53247 1.66675 5.83366 1.66675ZM13.3337 9.47508C13.4365 10.1686 13.318 10.8769 12.9951 11.4993C12.6722 12.1216 12.1613 12.6263 11.535 12.9415C10.9087 13.2567 10.199 13.3664 9.50681 13.255C8.8146 13.1436 8.17513 12.8168 7.67936 12.321C7.18359 11.8253 6.85677 11.1858 6.74538 10.4936C6.63399 9.80137 6.74371 9.09166 7.05893 8.46539C7.37415 7.83913 7.87881 7.3282 8.50115 7.00528C9.12348 6.68237 9.83179 6.5639 10.5253 6.66675C11.2328 6.77165 11.8877 7.1013 12.3934 7.607C12.8991 8.1127 13.2288 8.76764 13.3337 9.47508Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_9778_455">
                                                    <rect width="20" height="20" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="lg:w-1/4 flex flex-col items-center">
                <div class="text-left w-full">
                    <h3 class="font-bold text-xl text-white text-center lg:text-left">{{ __('Contacto') }}
                    </h3>
                </div>
                <div
                    class="flex flex-col gap-4 items-center lg:items-start justify-center mt-4 text-center lg:text-left text-sm lg:text-base">
                    @foreach ($contactos as $contacto)
                        @if ($contacto->direccion)
                            <a href="https://maps.google.com/?q={{ urlencode($contacto->direccion) }}" target="_blank"
                                class="block no-underline text-inherit hover:text-main-color transition-colors duration-300">
                                <div class="flex gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 20 20" fill="none">
                                        <path
                                            d="M16.6667 8.33329C16.6667 13.3333 10 18.3333 10 18.3333C10 18.3333 3.33333 13.3333 3.33333 8.33329C3.33333 6.56518 4.03571 4.86949 5.28595 3.61925C6.5362 2.36901 8.23189 1.66663 10 1.66663C11.7681 1.66663 13.4638 2.36901 14.714 3.61925C15.9643 4.86949 16.6667 6.56518 16.6667 8.33329Z"
                                            stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M10 10.8334C11.3807 10.8334 12.5 9.71409 12.5 8.33337C12.5 6.95266 11.3807 5.83337 10 5.83337C8.61929 5.83337 7.5 6.95266 7.5 8.33337C7.5 9.71409 8.61929 10.8334 10 10.8334Z"
                                            stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <p class="hover:text-[#5C93CB] transition-colors duration-300">
                                        {{ $contacto->direccion }}
                                    </p>
                                </div>
                            </a>
                        @endif
                        @if ($contacto->telefono)
                            <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefono) }}"
                                class="block no-underline text-inherit hover:text-main-color transition-colors duration-300">
                                <div class="flex gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_4143_362)">
                                            <path
                                                d="M18.3333 14.1V16.6C18.3343 16.8321 18.2867 17.0618 18.1938 17.2745C18.1008 17.4871 17.9644 17.678 17.7934 17.8349C17.6224 17.9918 17.4205 18.1113 17.2006 18.1856C16.9808 18.26 16.7478 18.2876 16.5167 18.2667C13.9524 17.9881 11.4892 17.1118 9.325 15.7084C7.31152 14.4289 5.60445 12.7219 4.325 10.7084C2.91665 8.53438 2.0402 6.0592 1.76667 3.48337C1.74584 3.25293 1.77323 3.02067 1.84708 2.80139C1.92094 2.58211 2.03964 2.38061 2.19564 2.20972C2.35163 2.03883 2.5415 1.9023 2.75316 1.80881C2.96481 1.71532 3.19362 1.66692 3.425 1.66671H5.925C6.32942 1.66273 6.72149 1.80594 7.02813 2.06965C7.33478 2.33336 7.53506 2.69958 7.59167 3.10004C7.69719 3.9001 7.89287 4.68565 8.175 5.44171C8.28712 5.73998 8.31138 6.06414 8.24492 6.37577C8.17846 6.68741 8.02405 6.97347 7.8 7.20004L6.74167 8.25837C7.92796 10.3447 9.65538 12.0721 11.7417 13.2584L12.8 12.2C13.0266 11.976 13.3126 11.8216 13.6243 11.7551C13.9359 11.6887 14.2601 11.7129 14.5583 11.825C15.3144 12.1072 16.0999 12.3029 16.9 12.4084C17.3048 12.4655 17.6745 12.6694 17.9388 12.9813C18.2031 13.2932 18.3435 13.6914 18.3333 14.1Z"
                                                stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4143_362">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <p class="hover:text-[#5C93CB] transition-colors duration-300">
                                        {{ $contacto->telefono }}
                                    </p>
                                </div>
                            </a>
                        @endif
                        @if ($contacto->email)
                            <a href="mailto:{{ $contacto->email }}"
                                class="block no-underline text-inherit hover:text-main-color transition-colors duration-300">
                                <div class="flex gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <path
                                            d="M16.6667 3.33337H3.33333C2.41286 3.33337 1.66667 4.07957 1.66667 5.00004V15C1.66667 15.9205 2.41286 16.6667 3.33333 16.6667H16.6667C17.5871 16.6667 18.3333 15.9205 18.3333 15V5.00004C18.3333 4.07957 17.5871 3.33337 16.6667 3.33337Z"
                                            stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.3333 5.83337L10.8583 10.5834C10.6011 10.7446 10.3036 10.83 10 10.83C9.6964 10.83 9.39894 10.7446 9.14167 10.5834L1.66667 5.83337"
                                            stroke="#A2D32D" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <p class="hover:text-[#5C93CB] transition-colors duration-300">
                                        {{ $contacto->email }}
                                    </p>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="bg-[#2E2E2E]">
        <div
            class="flex flex-row justify-between items-center max-w-[90%] lg:max-w-[1224px] mx-auto py-3 text-xs lg:text-sm text-white">
            <p>{{ __('© Copyright 2025 Metac S.A. Todos los derechos reservados') }}</p>
            <p>{{ __('By') }}
                <a href="https://osole.com.ar/#"
                    class="font-bold hover:text-[#5C93CB] transition-colors duration-300">
                    Osole
                </a>
            </p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('newsletterForm');
            const messageDiv = document.getElementById('newsletterMessage');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const email = this.querySelector('input[name="email"]').value;
                    messageDiv.innerHTML = '<span class="text-blue-300">{{ __('Enviando...') }}</span>';
                    axios.post('{{ route('newsletter.store') }}', {
                            email,
                            _token: token
                        })
                        .then(function() {
                            messageDiv.innerHTML =
                                '<span class="text-green-500">{{ __('Suscripción exitosa') }}</span>';
                            form.reset();
                            setTimeout(() => {
                                messageDiv.innerHTML = '';
                            }, 3000);
                        })
                        .catch(function(error) {
                            let msg = '<span class="text-red-500">';
                            if (error.response?.data?.message) msg += error.response.data.message;
                            else if (error.request) msg += 'No se recibió respuesta del servidor';
                            else msg += 'Error al enviar la solicitud';
                            msg += '</span>';
                            messageDiv.innerHTML = msg;
                            setTimeout(() => {
                                messageDiv.innerHTML = '';
                            }, 3000);
                        });
                });
            }
        });
    </script>
</footer>
