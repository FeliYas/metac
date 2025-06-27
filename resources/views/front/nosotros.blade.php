@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', __('Nosotros'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('nosotros') }}" class="font-extralight hover:underline">{{ __('Nosotros') }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ __('Nosotros') }}</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Nosotros') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-20 flex flex-col lg:flex-row gap-6">
            <img src="{{ $nosotros->path }}" alt="{{ __('Imagen de Nosotros') }}" class="lg:w-1/2 h-[600px] object-cover">
            <div class="flex flex-col gap-3.5 lg:w-1/2 text-black">
                <h2 class="font-semibold text-[32px]">{{ $nosotros->titulo }}</h2>
                <div class="custom-summernote leading-5.5 text-gray-800">
                    <p>{!! $nosotros->descripcion !!}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-100">
            <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="py-20 flex flex-col lg:flex-row gap-6">
                    <div class="flex flex-col gap-3.5 lg:w-1/2 text-black">
                        <h2 class="font-semibold text-[32px]">{{ $nosotros->titulo2 }}</h2>
                        <div class="custom-summernote leading-5.5 text-gray-800">
                            <p>{!! $nosotros->descripcion2 !!}</p>
                        </div>
                    </div>
                    <img src="{{ $nosotros->path2 }}" alt="{{ __('Imagen de Nosotros') }}"
                        class="lg:w-1/2 h-[600px] object-cover">

                </div>
                <div class="flex flex-col gap-5 pb-15">
                    <h2 class="text-black text-[32px] font-semibold">{{ __('¿Por qué elegirnos?') }}</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        @foreach ($tarjetas as $tarjeta)
                            <x-tarjeta-nosotros :tarjeta="$tarjeta" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
