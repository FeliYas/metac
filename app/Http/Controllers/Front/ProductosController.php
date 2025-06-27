<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Galeria;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        foreach ($categorias as $categoria) {
            $categoria->path = asset('storage/' . $categoria->path);
        }
        $banner = Banner::where('seccion', 'categorias')->first();
        if ($banner) {
            $banner->path = asset('storage/' . $banner->path);
        }
        $metadatos = Metadato::where('seccion', 'productos')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.categorias', compact('categorias', 'banner', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
    public function subcategorias($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->banners = asset('storage/' . $categoria->banners);
        $subcategorias = Subcategoria::where('categoria_id', $id)->orderBy('orden', 'asc')->get();
        foreach ($subcategorias as $subcategoria) {
            $subcategoria->path = asset('storage/' . $subcategoria->path);
        }
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.subcategorias', compact('categoria', 'subcategorias', 'logos', 'contactos', 'whatsapp'));
    }
    public function productos($categoria, $subcategoria)
    {
        $categoria = Categoria::findOrFail($categoria);
        $subcategoria = Subcategoria::findOrFail($subcategoria);
        $subcategoria->banners = asset('storage/' . $subcategoria->banners);
        $productos = Producto::where('subcategoria_id', $subcategoria->id)->orderBy('orden', 'asc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.productos', compact('categoria', 'subcategoria', 'productos', 'logos', 'contactos', 'whatsapp'));
    }
    public function producto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->fichatecnica = $producto->fichatecnica ? asset('storage/' . $producto->fichatecnica) : null;
        $producto->fichaseguridad = $producto->fichaseguridad ? asset('storage/' . $producto->fichaseguridad) : null;
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.producto', compact('producto', 'logos', 'contactos', 'whatsapp'));
    }

    
}
