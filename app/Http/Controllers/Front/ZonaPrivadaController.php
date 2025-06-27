<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ZonaPrivadaController extends Controller
{
    public function fichaTecnica()
    {
        $cliente_id = Session::get('cliente_id');
        $cliente = Cliente::find($cliente_id);
        
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $subcategorias = Subcategoria::orderBy('orden', 'asc')->get();
        
        // Solo obtener productos que tengan ficha técnica
        $productos = Producto::with(['subcategoria.categoria'])
                            ->whereNotNull('fichatecnica')
                            ->where('fichatecnica', '!=', '')
                            ->orderBy('orden', 'asc')
                            ->get();
        
        foreach ($productos as $producto) {
            $producto->fichatecnica = asset('storage/' . $producto->fichatecnica);
        }
        
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;

        return view('front.zona-privada.fichatecnica', compact('cliente', 'categorias', 'subcategorias', 'productos', 'logos', 'contactos', 'whatsapp'));
    }
    public function fichaSeguridad()
    {
        $cliente_id = Session::get('cliente_id');
        $cliente = Cliente::find($cliente_id);
        
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $subcategorias = Subcategoria::orderBy('orden', 'asc')->get();
        
        // Solo obtener productos que tengan ficha de seguridad
        $productos = Producto::with(['subcategoria.categoria'])
                            ->whereNotNull('fichaseguridad')
                            ->where('fichaseguridad', '!=', '')
                            ->orderBy('orden', 'asc')
                            ->get();
        
        foreach ($productos as $producto) {
            $producto->fichaseguridad = asset('storage/' . $producto->fichaseguridad);
        }
        
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;

        return view('front.zona-privada.fichaseguridad', compact('cliente', 'categorias', 'subcategorias', 'productos', 'logos', 'contactos', 'whatsapp'));
    }
}
