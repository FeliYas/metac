<?php

use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\Front\ZonaPrivadaController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CalidadController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ColocacionController;
use App\Http\Controllers\Admin\ContactoController;
use App\Http\Controllers\Admin\ContenidoController;
use App\Http\Controllers\Admin\GaleriaController;
use App\Http\Controllers\Admin\IndustriaController;
use App\Http\Controllers\Admin\IndustriaProductoController;
use App\Http\Controllers\Admin\InternacionalController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\MetadatoController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\NosotrosController;
use App\Http\Controllers\Admin\NovedadesController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ProductoImgController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubcategoriaController;
use App\Http\Controllers\Admin\TrabajosController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Front\CalidadController as FrontCalidadController;
use App\Http\Controllers\Front\ClienteController as FrontClienteController;
use App\Http\Controllers\Front\ContactoController as FrontContactoController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\IndustriaController as FrontIndustriaController;
use App\Http\Controllers\Front\InternacionalController as FrontInternacionalController;
use App\Http\Controllers\Front\NosotrosController as FrontNosotrosController;
use App\Http\Controllers\Front\NovedadesController as FrontNovedadesController;
use App\Http\Controllers\Front\PresupuestoController;
use App\Http\Controllers\Front\ProductosController;
use App\Http\Controllers\Front\ServicioController as FrontServicioController;
use App\Http\Controllers\Front\TrabajosController as FrontTrabajosController;
use App\Models\Logo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/nosotros', [FrontNosotrosController::class, 'index'])->name('nosotros');
Route::get('/productos', [ProductosController::class, 'index'])->name('categorias');
Route::get('/productos/{id}', [ProductosController::class, 'subcategorias'])->name('subcategorias');
Route::get('/productos/{categoria}/{subcategoria}', [ProductosController::class, 'productos'])->name('productos');
Route::get('/producto/{id}', [ProductosController::class, 'producto'])->name('producto');
Route::get('/industrias', [FrontIndustriaController::class, 'index'])->name('industrias');
Route::get('/industrias/{id}', [FrontIndustriaController::class, 'show'])->name('industria');
Route::get('/calidad', [FrontCalidadController::class, 'index'])->name('calidad');
Route::get('/novedades', [FrontNovedadesController::class, 'index'])->name('novedades');
Route::get('/novedad/{id}', [FrontNovedadesController::class, 'show'])->name('novedad');
Route::get('/contacto', [FrontContactoController::class, 'index'])->name('contacto');
Route::post('/contacto/enviar', [FrontContactoController::class, 'enviar'])->name('contacto.enviar');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.store');

// Rutas de autenticación de clientes
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login');
Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');
Route::post('/cliente/register', [ClienteAuthController::class, 'register'])->name('cliente.register');

// Rutas de zona privada (requieren middleware cliente)
Route::middleware(['cliente'])->group(function () {
    Route::get('/zona-privada/fichas-tecnicas', [ZonaPrivadaController::class, 'fichaTecnica'])->name('zonaprivada.fichatecnica');
    Route::get('/zona-privada/fichas-seguridad', [ZonaPrivadaController::class, 'fichaSeguridad'])->name('zonaprivada.fichaseguridad');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adm', function () {
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return Inertia::render('Admin/Dashboard', [
            'logo' => $logo,
        ]);
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/admin/banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');
    
    Route::get('/admin/home/slider', [SliderController::class, 'index'])->name('slider.dashboard');
    Route::post('/admin/home/slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::post('/admin/home/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/admin/home/slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    
    //rutas de los contenidos del dashboard
    Route::get('/admin/home/contenido', [ContenidoController::class, 'index'])->name('contenido.dashboard');
    Route::post('/admin/home/contenido/update/{id}', [ContenidoController::class, 'update'])->name('contenido.update');

    //rutas de las nosotros del dashboard// Rutas para el controlador de Nosotros
    Route::get('/admin/nosotros', [NosotrosController::class, 'index'])->name('nosotros.dashboard');
    Route::post('/admin/nosotros/update/{id}', [NosotrosController::class, 'update'])->name('nosotros.update');
    Route::put('/admin/nosotros/tarjeta/update/{id}', [NosotrosController::class, 'updateTarjeta'])->name('tarjeta.update');

    

    //rutas de los productos del dashboard
    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('categorias.dashboard');
    Route::post('/admin/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/admin/categorias/update/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/admin/categorias/delete/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::post('/admin/categorias/destacado', [CategoriaController::class, 'toggleDestacado'])->name('categorias.toggleDestacado');
    Route::get('/admin/subcategorias', [SubcategoriaController::class, 'index'])->name('subcategorias.dashboard');
    Route::post('/admin/subcategorias/store', [SubcategoriaController::class, 'store'])->name('subcategorias.store');
    Route::put('/admin/subcategorias/update/{id}', [SubcategoriaController::class, 'update'])->name('subcategorias.update');
    Route::delete('/admin/subcategorias/delete/{id}', [SubcategoriaController::class, 'destroy'])->name('subcategorias.destroy');
    Route::get('/admin/productos', [ProductoController::class, 'index'])->name('productos.dashboard');
    Route::post('/admin/productos/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/admin/productos/update/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/admin/productos/delete/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    //rutas de industrias del dashboard
    Route::get('/admin/industrias', [IndustriaController::class, 'index'])->name('industrias.dashboard');
    Route::post('/admin/industrias/store', [IndustriaController::class, 'store'])->name('industrias.store');
    Route::put('/admin/industrias/update/{id}', [IndustriaController::class, 'update'])->name('industrias.update');
    Route::delete('/admin/industrias/delete/{id}', [IndustriaController::class, 'destroy'])->name('industrias.destroy');
    Route::get('/admin/industrias/relacionados/{id}', [IndustriaProductoController::class, 'index'])->name('relacionados.dashboard'); 
    Route::post('/admin/industrias/relacionados/store', [IndustriaProductoController::class, 'store'])->name('relacionados.store');
    Route::delete('/admin/industrias/relacionados/delete/{industria}/{producto}', [IndustriaProductoController::class, 'destroy'])->name('relacionados.destroy');
    
    //rutas de calidad del dashboard
    Route::get('/admin/calidad', [CalidadController::class, 'index'])->name('calidad.dashboard');
    Route::post('/admin/calidad/update/{id}', [CalidadController::class, 'update'])->name('calidad.update');

    //rutas de las novedades del dashboard
    Route::get('/admin/novedades', [NovedadesController::class, 'index'])->name('novedades.dashboard');
    Route::post('/admin/novedades/store', [NovedadesController::class, 'store'])->name('novedades.store');
    Route::put('/admin/novedades/update/{id}', [NovedadesController::class, 'update'])->name('novedades.update');
    Route::delete('/admin/novedades/destroy/{id}', [NovedadesController::class, 'destroy'])->name('novedades.destroy');

    //rutas del contacto del dashboard
    Route::get('/admin/contacto', [ContactoController::class, 'index'])->name('contacto.dashboard');
    Route::post('/admin/contacto/update/{id}', [ContactoController::class, 'update'])->name('contacto.update');
    
    //rutas de los clientes del dashboard
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('clientes.dashboard');
    Route::post('/admin/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
    Route::delete('/admin/clientes/delete/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy'); 
    Route::post('/admin/clientes/autorizado', [ClienteController::class, 'toggleAutorizado'])->name('clientes.toggleAutorizado');
    
    //rutas de los logos del dashboard
    Route::get('/admin/logos', [LogoController::class, 'index'])->name('logos.dashboard');
    Route::put('/admin/logos/update/{id}', [LogoController::class, 'update'])->name('logos.update');

    //rutas del newsletter del dashboard
    Route::get('/admin/newsletter', [NewsletterController::class, 'index'])->name('newsletter.dashboard');
    Route::delete('/admin/newsletter/destroy/{id}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    //rutas de usuarios del dashboard
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.dashboard');
    Route::post('/admin/usuarios/create', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/edit/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/delete/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    //rutas de los metadatos
    Route::get('/admin/metadatos', [MetadatoController::class, 'index'])->name('metadatos.dashboard');
    Route::put('/admin/metadatos/update/{id}', [MetadatoController::class, 'update'])->name('metadatos.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
