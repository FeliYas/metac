@extends('layouts.guest')

@section('title', __('Fichas de Seguridad'))

@section('content')
    <div>
        <div class="h-[180px] bg-main-color">
            <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto text-white">
                <div class="absolute top-6 left-0">
                    <div class="flex gap-1 text-xs">
                        <a href="{{ route('home') }}" class="hover:underline">{{ __('Inicio') }}</a>
                        <span class="font-extralight">></span>
                        <a href="{{ route('zonaprivada.fichaseguridad') }}"
                            class="font-extralight hover:underline">{{ __('Fichas de seguridad') }}</a>
                    </div>
                </div>
                <div class="absolute top-20 flex gap-6 w-full">
                    <div class="flex flex-col gap-2 w-1/4">
                        <h3 class="font-bold">Categoria</h3>
                        <div>
                            <select id="categoria"
                                class="w-full px-4 py-2  border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent">
                                <option value="">Seleccionar categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option class="text-black" value="{{ $categoria->id }}">{{ $categoria->titulo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 w-1/4">
                        <h3 class="font-bold">Subcategoría</h3>
                        <div>
                            <select id="subcategoria"
                                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent">
                                <option value="">Seleccionar subcategoría</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 w-1/4">
                        <h3 class="font-bold">Nombre de producto</h3>
                        <div>
                            <select id="producto"
                                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent">
                                <option value="">Seleccionar producto</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-1/4">
                        <button id="limpiar" class="btn-secondary w-full mt-7">Limpiar filtros</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-20 min-h-[500px]">
            <div id="tabla-fichas" class="hidden">
                <div class="bg-white">
                    <!-- Header de la tabla -->
                    <div
                        class="grid grid-cols-[80px_270px_240px_300px_1fr] gap-4 pl-1 py-3 border-b border-gray-200 font-bold text-black">
                        <div></div>
                        <div>Nombre de producto</div>
                        <div>Fecha</div>
                        <div>Formato</div>
                        <div></div>
                    </div>

                    <!-- Contenido de la tabla -->
                    <div id="contenido-tabla">
                        <!-- Los resultados se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>

            <!-- Mensaje cuando no hay resultados -->
            <div id="sin-resultados" class="hidden text-center py-8">
                <p class="text-black">No se encontraron fichas de seguridad con los criterios seleccionados.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSelect = document.getElementById('categoria');
            const subcategoriaSelect = document.getElementById('subcategoria');
            const productoSelect = document.getElementById('producto');
            const limpiarBtn = document.getElementById('limpiar');
            const tablaFichas = document.getElementById('tabla-fichas');
            const contenidoTabla = document.getElementById('contenido-tabla');
            const sinResultados = document.getElementById('sin-resultados');

            // Datos pasados desde el controlador
            const subcategorias = @json($subcategorias);
            const productos = @json($productos);

            // Debug: verificar datos
            console.log('Subcategorías:', subcategorias);
            console.log('Productos:', productos);

            // Mostrar todas las fichas de seguridad al cargar la página
            function mostrarTodasLasFichas() {
                const todasLasFichas = productos.filter(producto => {
                    return producto.fichaseguridad && producto.fichaseguridad.trim() !== '';
                });
                mostrarResultados(todasLasFichas);
            }

            // Inicializar mostrando todas las fichas y cargar todas las opciones
            mostrarTodasLasFichas();
            cargarTodasLasSubcategorias();
            cargarTodosLosProductos();

            // Función para cargar todas las subcategorías
            function cargarTodasLasSubcategorias() {
                subcategoriaSelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
                subcategoriaSelect.disabled = false;

                subcategorias.forEach(subcategoria => {
                    const option = document.createElement('option');
                    option.value = subcategoria.id;
                    option.textContent = subcategoria.titulo;
                    option.className = 'text-black';
                    subcategoriaSelect.appendChild(option);
                });
            }

            // Función para cargar todos los productos con ficha de seguridad
            function cargarTodosLosProductos() {
                productoSelect.innerHTML = '<option value="">Seleccionar producto</option>';
                productoSelect.disabled = false;

                const productosConFicha = productos.filter(producto =>
                    producto.fichaseguridad && producto.fichaseguridad.trim() !== ''
                );

                productosConFicha.forEach(producto => {
                    const option = document.createElement('option');
                    option.value = producto.id;
                    option.textContent = producto.titulo;
                    option.className = 'text-black';
                    productoSelect.appendChild(option);
                });
            }

            // Función para actualizar subcategorías
            categoriaSelect.addEventListener('change', function() {
                const categoriaId = this.value;

                if (categoriaId) {
                    // Si hay categoría seleccionada, filtrar subcategorías y productos
                    subcategoriaSelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
                    productoSelect.innerHTML = '<option value="">Seleccionar producto</option>';

                    const subcategoriasFiltradas = subcategorias.filter(sub => sub.categoria_id ==
                        categoriaId);
                    subcategoriasFiltradas.forEach(subcategoria => {
                        const option = document.createElement('option');
                        option.value = subcategoria.id;
                        option.textContent = subcategoria.titulo;
                        option.className = 'text-black';
                        subcategoriaSelect.appendChild(option);
                    });

                    // Filtrar productos que pertenezcan a esta categoría
                    const productosDeCategoria = productos.filter(producto =>
                        producto.subcategoria &&
                        producto.subcategoria.categoria_id == categoriaId &&
                        producto.fichaseguridad &&
                        producto.fichaseguridad.trim() !== ''
                    );

                    productosDeCategoria.forEach(producto => {
                        const option = document.createElement('option');
                        option.value = producto.id;
                        option.textContent = producto.titulo;
                        option.className = 'text-black';
                        productoSelect.appendChild(option);
                    });
                } else {
                    // Si no hay categoría seleccionada, mostrar todas las opciones
                    cargarTodasLasSubcategorias();
                    cargarTodosLosProductos();
                }

                // Buscar automáticamente después de cambiar la categoría
                buscarAutomaticamente();
            });

            // Función para actualizar productos cuando cambia la subcategoría
            subcategoriaSelect.addEventListener('change', function() {
                const subcategoriaId = this.value;
                const categoriaId = categoriaSelect.value;

                if (subcategoriaId) {
                    // Si hay subcategoría seleccionada, filtrar productos de esa subcategoría
                    productoSelect.innerHTML = '<option value="">Seleccionar producto</option>';

                    const productosFiltrados = productos.filter(producto =>
                        producto.subcategoria_id == subcategoriaId &&
                        producto.fichaseguridad &&
                        producto.fichaseguridad.trim() !== ''
                    );

                    productosFiltrados.forEach(producto => {
                        const option = document.createElement('option');
                        option.value = producto.id;
                        option.textContent = producto.titulo;
                        option.className = 'text-black';
                        productoSelect.appendChild(option);
                    });
                } else {
                    // Si no hay subcategoría seleccionada, mostrar productos según la categoría
                    if (categoriaId) {
                        // Si hay categoría, mostrar productos de esa categoría
                        productoSelect.innerHTML = '<option value="">Seleccionar producto</option>';
                        const productosDeCategoria = productos.filter(producto =>
                            producto.subcategoria &&
                            producto.subcategoria.categoria_id == categoriaId &&
                            producto.fichaseguridad &&
                            producto.fichaseguridad.trim() !== ''
                        );

                        productosDeCategoria.forEach(producto => {
                            const option = document.createElement('option');
                            option.value = producto.id;
                            option.textContent = producto.titulo;
                            option.className = 'text-black';
                            productoSelect.appendChild(option);
                        });
                    } else {
                        // Si no hay ni categoría ni subcategoría, mostrar todos los productos
                        cargarTodosLosProductos();
                    }
                }

                // Buscar automáticamente después de cambiar la subcategoría
                buscarAutomaticamente();
            });

            // Función para buscar automáticamente cuando se selecciona cualquier filtro
            function buscarAutomaticamente() {
                const categoriaId = categoriaSelect.value;
                const subcategoriaId = subcategoriaSelect.value;
                const productoId = productoSelect.value;

                // Filtrar productos con ficha de seguridad
                let productosFiltrados = productos.filter(producto => {
                    return producto.fichaseguridad && producto.fichaseguridad.trim() !== '';
                });

                // Aplicar filtros de manera independiente
                if (categoriaId) {
                    productosFiltrados = productosFiltrados.filter(producto =>
                        producto.subcategoria && producto.subcategoria.categoria_id == categoriaId
                    );
                }

                if (subcategoriaId) {
                    productosFiltrados = productosFiltrados.filter(producto =>
                        producto.subcategoria_id == subcategoriaId
                    );
                }

                if (productoId) {
                    productosFiltrados = productosFiltrados.filter(producto =>
                        producto.id == productoId
                    );
                }

                // Mostrar resultados
                mostrarResultados(productosFiltrados);
            }

            // Event listener para el producto
            productoSelect.addEventListener('change', function() {
                buscarAutomaticamente();
            });


            // Función para limpiar filtros
            limpiarBtn.addEventListener('click', function() {
                categoriaSelect.value = '';
                subcategoriaSelect.value = '';
                productoSelect.value = '';

                // Recargar todas las opciones
                cargarTodasLasSubcategorias();
                cargarTodosLosProductos();

                // Mostrar todas las fichas de seguridad
                mostrarTodasLasFichas();
            });

            function mostrarResultados(productos) {
                contenidoTabla.innerHTML = '';

                if (productos.length === 0) {
                    tablaFichas.classList.add('hidden');
                    sinResultados.classList.remove('hidden');
                    return;
                }

                sinResultados.classList.add('hidden');
                tablaFichas.classList.remove('hidden');

                productos.forEach(producto => {
                    const fila = document.createElement('div');
                    fila.className =
                        'grid grid-cols-[80px_270px_240px_300px_1fr] gap-4 pl-1 py-2.5  items-center border-b border-gray-200 hover:bg-gray-50';

                    // Formatear fecha
                    const fecha = new Date(producto.created_at);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    fila.innerHTML = `
                        <div class="flex items-center justify-center">
                            <div class="flex-shrink-0 bg-gray-50 h-[80px] p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 44 44" fill="none">
                                    <path d="M25.6663 3.66663V11C25.6663 11.9724 26.0526 12.9051 26.7402 13.5927C27.4279 14.2803 28.3605 14.6666 29.333 14.6666H36.6663M4.22547 28.4716L5.91763 27.7713M5.91763 23.562L4.22363 22.8598M8.57413 39.4166C9.23926 40.0032 10.0945 40.3289 10.9813 40.3333H32.9996C33.9721 40.3333 34.9047 39.947 35.5924 39.2594C36.28 38.5717 36.6663 37.6391 36.6663 36.6666V12.8333L27.4996 3.66663H10.9996C10.0272 3.66663 9.09454 4.05293 8.40691 4.74057C7.71927 5.4282 7.33297 6.36083 7.33297 7.33329V11.9166M8.89497 20.5846L8.1928 18.8925M8.89497 30.7486L8.1928 32.4426M13.1043 20.5846L13.8065 18.8925M13.8046 32.4426L13.1043 30.7486M16.0816 23.562L17.7738 22.8598M16.0816 27.7713L17.7738 28.4735M16.4996 25.6666C16.4996 28.7042 14.0372 31.1666 10.9996 31.1666C7.96207 31.1666 5.49963 28.7042 5.49963 25.6666C5.49963 22.6291 7.96207 20.1666 10.9996 20.1666C14.0372 20.1666 16.4996 22.6291 16.4996 25.6666Z" stroke="#5994C8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="text-[#333]">Ficha de Seguridad ${producto.titulo}</div>
                        </div>
                        <div class="text-[#333]">${fechaFormateada}</div>
                        <div class="text-[#333]">PDF</div>
                        <div class="flex justify-end">
                            <a href="${producto.fichaseguridad}" 
                               target="_blank" 
                               class="btn-primary w-[184px] font-medium">
                                Descargar
                            </a>
                        </div>
                    `;

                    contenidoTabla.appendChild(fila);
                });
            }
        });
    </script>
@endsection
