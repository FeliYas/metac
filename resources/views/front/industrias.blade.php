@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', __('Industrias'))

@section('content')
    <div>
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('industrias') }}" class="font-extralight hover:underline">{{ __('Industrias') }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ __('Industrias') }}</h2>
                </div>
            </div>
            <img src="{{ $banner->path }}" alt="{{ __('Banner de Industrias') }}" class="w-full h-[180px] object-cover">
        </div>
        <div class="py-20 max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                @foreach ($industrias as $industria)
                    <a href="{{ route('industria', $industria->id) }}"
                        class="relative group bg-white border border-gray-200 hover:-translate-y-1 transform hover:shadow-xl transition-all duration-300 ease-in-out">
                        <img src="{{ $industria->portada }}" alt="industria"
                            class="w-full h-[376px] object-contain transition-all duration-300 ease-in-out">
                        <div class="absolute inset-0 bg-black opacity-50 transition-opacity duration-300 ease-in-out">
                        </div>
                        <p
                            class="absolute bottom-6 left-1/2 transform -translate-x-1/2 w-full text-center text-white text-2xl">
                            {{ ucfirst($industria->titulo) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection
