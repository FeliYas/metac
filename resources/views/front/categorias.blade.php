@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', __('Productos'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('categorias') }}" class="font-extralight hover:underline">{{ __('Productos') }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ __('Productos') }}</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Productos') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="py-20 max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach ($categorias as $categoria)
                    <a href="{{ route('subcategorias', $categoria->id) }}"
                        class="relative h-[392px] transition-transform duration-300 hover:shadow-2xl hover:-translate-y-1 transform">
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

@endsection
