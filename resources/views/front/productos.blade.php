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
                        <span>></span>
                        <a href="{{ route('subcategorias', $categoria->id) }}"
                            class="hover:underline">{{ $categoria->titulo }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('productos', ['categoria' => $categoria->id, 'subcategoria' => $subcategoria->id]) }}"
                            class="font-extralight hover:underline">{{ $subcategoria->titulo }}</a>
                    </div>
                </div>
                <div class="absolute top-23">
                    <h2 class="font-bold text-[40px]">{{ $subcategoria->titulo }}</h2>
                </div>
            </div>
            <img src="{{ $subcategoria->banners }}" alt="{{ __('Banner de Productos') }}"
                class="w-full h-[180px] object-cover">
        </div>
        @if ($productos->isEmpty())
            <div class="py-20 mb-10 max-w-[90%] lg:max-w-[1224px] mx-auto flex justify-center items-center">
                <div class="bg-main-color bg-opacity-80 rounded-lg p-8 w-full h-80 flex items-center justify-center">
                    <p class="text-white text-2xl font-semibold text-center w-full">
                        {{ __('No hay productos disponibles en este momento.') }}
                    </p>
                </div>
            </div>
        @else
            <div class="py-20 mb-10 max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="grid grid-cols-4 gap-6">
                    @foreach ($productos as $item)
                        <a href="{{ route('producto', $item->id) }}"
                            class="transition-transform duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 transform border border-gray-200 p-6 h-[220px] flex flex-col justify-end text-black">
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

@endsection
