@extends('layouts.guest')
@section('title', __('Home'))

@section('content')
    <div>
        <!-- Hero Slider Section -->
        <div class="overflow-hidden">
            <div class="slider-track flex transition-transform duration-500 ease-in-out">
                @foreach ($sliders as $slider)
                    @php $ext = pathinfo($slider->path, PATHINFO_EXTENSION); @endphp <div class="slider-item min-w-full relative h-[600px] lg:h-[768px]">
                        <div class="absolute inset-0 bg-black z-0 overflow-hidden">
                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ asset($slider->path) }}" alt="Slider Image" class="w-full h-full object-cover"
                                    data-duration="6000">
                            @elseif (in_array($ext, ['mp4', 'webm', 'ogg']))
                                <video class="w-full h-full object-cover" autoplay muted onended="nextSlide()">
                                    <source src="{{ asset($slider->path) }}" type="video/{{ $ext }}">
                                    {{ __('Tu navegador no soporta el formato de video.') }}
                                </video>
                            @endif
                        </div>
                        <div class="absolute inset-0 z-10"
                            style="background: linear-gradient(0deg, rgba(89, 148, 200, 0.30) 0%, rgba(89, 148, 200, 0.30) 100%), linear-gradient(90deg, rgba(0, 0, 0, 0.63) 0%, rgba(0, 0, 0, 0.00) 100%), linear-gradient(180deg, rgba(0, 0, 0, 0.91) 0%, rgba(0, 0, 0, 0.00) 100%);">
                        </div>
                        <div class="absolute inset-0 flex z-20 lg:max-w-[1224px] lg:mx-auto">
                            <div class="relative flex flex-col gap-4 sm:gap-6 lg:gap-23 w-full justify-center">
                                <div
                                    class="max-w-[320px] sm:max-w-[400px] lg:max-w-[480px] text-white flex flex-col gap-2 lg:gap-6">
                                    <h1
                                        class="text-lg sm:text-xl md:text-3xl lg:text-5xl font-bold leading-tight sm:leading-normal lg:leading-14 uppercase">
                                        {{ $slider->titulo }}</h1>
                                    <div class="custom-summernote lg:text-left sm:text-xl mt-1">
                                        <p>{!! $slider->descripcion !!}</p>
                                    </div>
                                </div>
                                <a href="{{route('nosotros')}}" class="btn-primary w-[185px]">Más
                                    info</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Slider Navigation Dots -->
            <div class="relative lg:max-w-[1224px] lg:mx-auto">
                <div class="absolute bottom-4 sm:bottom-6 lg:bottom-13 w-full z-30">
                    <div class="flex space-x-1 lg:space-x-2">
                        @foreach ($sliders as $i => $slider)
                            <button
                                class="cursor-pointer dot w-4 sm:w-6 lg:w-12 h-1 sm:h-1.5 rounded-none transition-colors duration-300 bg-white {{ $i === 0 ? 'opacity-90' : 'opacity-50' }}"
                                data-dot-index="{{ $i }}" onclick="goToSlide({{ $i }})"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Slider JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sliderTrack = document.querySelector('.slider-track');
                const sliderItems = document.querySelectorAll('.slider-item');
                const dots = document.querySelectorAll('.dot');
                let currentIndex = 0,
                    autoSlideTimeout, isTransitioning = false;

                window.nextSlide = () => {
                    if (isTransitioning) return;
                    clearTimeout(autoSlideTimeout);
                    currentIndex = (currentIndex + 1) % sliderItems.length;
                    updateSlider();
                };
                window.goToSlide = i => {
                    if (isTransitioning || i === currentIndex) return;
                    clearTimeout(autoSlideTimeout);
                    currentIndex = i;
                    updateSlider();
                };

                function updateSlider() {
                    isTransitioning = true;
                    sliderItems.forEach(item => item.querySelector('video')?.pause());
                    sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
                    dots.forEach((dot, i) => dot.classList.toggle('opacity-90', i === currentIndex) || dot.classList
                        .toggle('opacity-50', i !== currentIndex));
                    scheduleNextSlide();
                    setTimeout(() => isTransitioning = false, 500);
                }

                function scheduleNextSlide() {
                    clearTimeout(autoSlideTimeout);
                    const slide = sliderItems[currentIndex],
                        video = slide.querySelector('video'),
                        img = slide.querySelector('img');
                    if (video) {
                        video.currentTime = 0;
                        video.play();
                    } else autoSlideTimeout = setTimeout(window.nextSlide, img?.dataset.duration ? +img.dataset
                        .duration : 6000);
                }
                sliderItems.forEach(item => item.querySelector('video') && (item.querySelector('video').onended = window
                    .nextSlide));
                updateSlider();
            });
        </script>

        <div class="lg:max-w-[1224px] lg:mx-auto py-10 sm:py-18">
            <div class="flex flex-col gap-4">
                <h2 class="text-[32px] font-semibold text-black">Productos</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($categorias as $categoria)
                        <a href="{{ route('subcategorias', $categoria->id) }}" class="relative h-[392px] transition-transform duration-300 hover:shadow-2xl hover:-translate-y-1 transform">
                            <img src="{{ $categoria->path }}" alt="categoria" class="w-full h-[392px]">
                            <div class="flex absolute bottom-6 left-0 right-0 justify-center">
                                <div class="max-w-[263px]">
                                    <p class="text-center text-white text-[32px]">
                                        {{ $categoria->titulo }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="flex flex-col lg:flex-row gap-0 lg:gap-13 bg-white mb-20">

            <img src="{{ $contenido[0]->path }}" alt="{{ __('Contenido de la pagina') }}"
                class="w-full lg:w-[50vw] h-[400px] lg:h-[600px] object-cover opacity-0 -translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-left">
            <div
                class="w-full h-[400px] lg:h-[600px] lg:w-1/2 pl-[5%] pr-[5%] lg:pl-0 lg:pr-[calc((100vw-1224px)/2)] py-7 flex flex-col opacity-0 translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-right items-center lg:items-start text-black gap-6 lg:gap-11">
                <div class="flex flex-col gap-4 sm:gap-6 w-full">
                    <h2 class="font-bold text-xl sm:text-2xl lg:text-[32px] text-center lg:text-left">
                        {{ $contenido[0]->titulo }}</h2>
                    <div class="custom-summernote text-center lg:text-left text-sm sm:text-base">
                        <p>{!! $contenido[0]->descripcion !!}</p>
                    </div>
                </div>
                <a href="{{ route('nosotros') }}" class="btn-primary w-[132px] font-semibold">{{ __('Ver más') }}</a>
            </div>
        </div>

        <div>
            <div class="relative">
                <img src="{{ $contenido[1]->path }}" alt="{{ __('Contenido de la pagina') }}" class="w-full h-[400px]">
                <div class="absolute inset-0">
                    <div
                        class="flex flex-col gap-7 text-center items-center justify-center max-w-[90%] lg:max-w-[48%] mx-auto h-full text-white">
                        <h2 class="lg:text-[32px] font-bold">{{ $contenido[1]->titulo }}</h2>
                        <img src="{{ $contenido[1]->logo }}" alt="logo del contenido">
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:max-w-[1224px] lg:mx-auto py-20">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-3 sm:gap-0 mb-4 sm:mb-6">
                <h2 class="font-bold text-xl sm:text-2xl lg:text-[32px] text-black">
                    {{ __('Enterate de nuestras últimas novedades') }}</h2>
                <a href="{{route('novedades')}}" class="btn-secondary text-sm sm:text-base">{{ __('Ver todas') }}</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 w-full">
                @foreach ($novedades as $novedad)
                    <x-tarjeta-novedades :novedad="$novedad" />
                @endforeach
            </div>
        </div>
    </div>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;

                        // Remueve las clases de estado inicial
                        element.classList.remove('opacity-0');

                        // Remueve las clases de transformación según el tipo de fade
                        if (element.classList.contains('scroll-fade-left')) {
                            element.classList.remove('-translate-x-20');
                        }
                        if (element.classList.contains('scroll-fade-right')) {
                            element.classList.remove('translate-x-20');
                        }

                        // Opcional: para evitar re-observar elementos ya animados
                        observer.unobserve(element);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px' // Activa la animación un poco antes
            });

            // Observa todos los elementos con las clases de scroll fade
            document.querySelectorAll('.scroll-fade-right, .scroll-fade-left').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endsection
