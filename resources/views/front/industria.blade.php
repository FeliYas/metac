@extends('layouts.guest')

@section('title', __('Industrias'))

@section('content')
    <div class="cursor-auto">
        <div class="overflow-hidden text-white">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span>></span>
                        <a href="{{ route('industrias') }}" class="hover:underline">{{ __('Industrias') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('industria', $industria->id) }}" class="font-extralight hover:underline">
                            {{ ucfirst($industria->titulo) }}
                        </a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ ucfirst($industria->titulo) }}</h2>
                </div>
            </div>
            <img src="{{ $industria->banners }}" alt="{{ __('Banner de Industrias') }}"
                class="w-full h-[180px] object-cover">
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto ">
            <div class="py-20 flex flex-col lg:flex-row gap-6">
                <img src="{{ $industria->path }}" alt="{{ __('Imagen de industria') }}"
                    class="lg:w-1/2 h-[600px] object-cover">
                <div class="flex flex-col gap-3.5 lg:w-1/2 text-black">
                    <h2 class="font-bold text-[32px]">{{ $industria->subtitulo }}</h2>
                    <div class="custom-summernote leading-5.5">
                        <p>{!! $industria->descripcion !!}</p>
                    </div>
                </div>
            </div>
            @if ($productos->count() > 0)
                <div class="mb-20 flex flex-col gap-6 text-black">
                    <h2 class="font-bold text-[32px]">Productos que aplican a la Industria Automotriz</h2>
                    <div class="grid grid-cols-4 gap-6">
                        @foreach ($productos as $item)
                            <a href=""
                                class="transition-transform duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 transform border border-gray-200 p-6 h-[220px] flex flex-col justify-end">
                                <p class="text-xs font-bold text-main-color uppercase">{{ $item->subcategoria->titulo }}
                                </p>
                                <h3 class="text-xl font-bold">{{ $item->titulo }}</h3>
                                <p>{{ $item->subtitulo }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
