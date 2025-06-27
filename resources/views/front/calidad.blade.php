@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', __('Calidad'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('calidad') }}" class="font-extralight hover:underline">{{ __('Calidad') }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ __('Calidad') }}</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Calidad') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-20 flex flex-col lg:flex-row gap-6">
            <div class="flex flex-col justify-between lg:w-1/2 text-black">
                <div class="flex flex-col gap-5">
                    <h2 class="font-semibold text-[32px]">{{ $calidad->titulo }}</h2>
                    <div class="custom-summernote leading-5.5 text-gray-800">
                        <p>{!! $calidad->descripcion !!}</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <a href="{{ $calidad->iso1 }}" class="btn-primary w-full" download>Descargar TÜV ISO 9001</a>
                    <a href="{{ $calidad->iso2 }}" class="btn-primary w-full" download>Descargar TÜV ISO 14001</a>
                </div>
            </div>
            <img src="{{ $calidad->path }}" alt="{{ __('Imagen de Calidad') }}" class="lg:w-1/2 h-[600px] object-cover">
        </div>
    </div>
@endsection
