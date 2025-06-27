@extends('layouts.guest')
@section('title', __('Novedades'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span>></span>
                        <a href="{{ route('novedades') }}" class="hover:underline">{{ __('Novedades') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('novedad', $novedad->id) }}" class="font-extralight hover:underline">{{ $novedad->titulo }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">Novedades</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Novedades') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-15 flex gap-15">
            <img src="{{ $novedad->path }}" alt="{{ __('Novedad') }}" class="w-1/2 h-[400px] object-cover rounded-[10px]">
            <div class="w-1/2 flex flex-col gap-5">
                <h1 class="text-[40px] font-semibold text-black leading-10">{{ $novedad->titulo }}</h1>
                <div class="custom-summernote font-light text-black leading-5 text-[17px]">
                    <p>{!! $novedad->descripcion !!}</p>
                </div>
            </div>
        </div>
    </div>

@endsection
