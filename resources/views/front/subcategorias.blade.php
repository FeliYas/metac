@extends('layouts.guest')

@section('title', __('Productos'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span>></span>
                        <a href="{{ route('categorias') }}" class="hover:underline">{{ __('Productos') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('subcategorias', $categoria->id) }}" class="font-extralight hover:underline">{{ $categoria->titulo }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ $categoria->titulo }}</h2>
                </div>
            </div>
            <img src="{{ $categoria->banners }}" alt="{{ __('Banner de Productos') }}" class="w-full h-[180px] object-cover">
        </div>
        @if ($subcategorias->isEmpty())
            <div class="py-20 max-w-[90%] lg:max-w-[1224px] mx-auto flex justify-center items-center">
                <div class="bg-main-color bg-opacity-80 rounded-lg p-8 w-full h-80 flex items-center justify-center">
                    <p class="text-white text-2xl font-semibold text-center w-full">
                        {{ __('No hay subcategorías disponibles en este momento.') }}
                    </p>
                </div>
            </div>
        @else
            <div class="py-20 max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    @foreach ($subcategorias as $subcategoria)
                        <a href="{{ route('productos', ['categoria' => $categoria->id, 'subcategoria' => $subcategoria->id]) }}"
                            class="relative h-[288px] transition-transform duration-300 hover:shadow-2xl hover:-translate-y-1 transform">
                            <img src="{{ $subcategoria->path }}" alt="categoria" class="w-full h-[288px]">
                            <div class="flex absolute bottom-6 left-0 right-0 justify-center">
                                <div class="max-w-[263px]">
                                    <p class="text-center text-white text-2xl">
                                        {{ $subcategoria->titulo }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection
