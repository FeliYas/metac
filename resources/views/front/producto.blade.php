@extends('layouts.guest')

@section('title', __('Productos'))

@section('content')
    <div>
        <div class="overflow-hidden text-[#333] max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div class="relative h-[100px]">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span>></span>
                        <a href="{{ route('categorias') }}" class="hover:underline">{{ __('Productos') }}</a>
                        <span>></span>
                        <a href="{{ route('subcategorias', $producto->subcategoria->categoria->id) }}"
                            class="hover:underline">{{ $producto->subcategoria->categoria->titulo }}</a>
                        <span>></span>
                        <a href="{{ route('productos', ['categoria' => $producto->subcategoria->categoria->id, 'subcategoria' => $producto->subcategoria->id]) }}"
                            class="hover:underline">{{ $producto->subcategoria->titulo }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('producto', $producto->id) }}"
                            class="font-extralight hover:underline uppercase">{{ $producto->titulo }}</a>
                    </div>
                </div>
            </div>

            <div class="py-5 mb-20">
                <div class="lg:w-1/2">
                    <div>
                        <p class="font-bold text-main-color uppercase">{{ $producto->subcategoria->titulo }}
                        </p>
                        <h3 class="text-[32px] font-bold">{{ $producto->titulo }}</h3>
                        <p class="text-xl">{{ $producto->subtitulo }}</p>
                    </div>
                    <hr class="border-[#DEDFE0] my-4 mr-6">
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-1">
                    <div class="custom-summernote leading-5.5">
                        <p>{!! $producto->descripcion !!}</p>
                    </div>
                    <div class="flex flex-col gap-15">
                        <div class="flex flex-col gap-5">
                            <div>
                                <p class="font-bold">Consulta</p>
                                <p>Solicitá más información sobre este producto.</p>
                            </div>
                            <a href="{{ route('contacto') }}" class="btn-primary w-[181px]">Consultar</a>
                        </div>
                        @if ($producto->fichatecnica || $producto->fichaseguridad)
                            <div>
                                <div class="flex flex-col gap-3">
                                    <p class="font-bold">Información técnica</p>
                                    <p>Descargá los documentos para acceder a información técnica detallada.</p>
                                </div>
                                <div class="mt-6 flex flex-col gap-3">
                                    @if ($producto->fichatecnica)
                                        @if (Session::get('cliente_logueado'))
                                            <a href="{{ $producto->fichatecnica }}" target="_blank"
                                                class="btn-secondary w-[288px]">Ficha
                                                técnica</a>
                                        @else
                                            <button onclick="showLoginForDownload('Ficha técnica')"
                                                class="btn-secondary w-[288px]">Ficha
                                                técnica</button>
                                        @endif
                                    @endif
                                    @if ($producto->fichaseguridad)
                                        @if (Session::get('cliente_logueado'))
                                            <a href="{{ $producto->fichaseguridad }}" target="_blank"
                                                class="btn-secondary w-[288px]">Ficha de
                                                seguridad</a>
                                        @else
                                            <button onclick="showLoginForDownload('Ficha de seguridad')"
                                                class="btn-secondary w-[288px]">Ficha de
                                                seguridad</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoginForDownload(documentType) {
            // Usar la función global definida en el navbar
            if (window.showLoginForDownload) {
                window.showLoginForDownload(documentType);
            }
        }
    </script>

@endsection
