<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Novedad;
use Illuminate\Http\Request;

class NovedadesController extends Controller
{
    public function index()
    {
        $novedades = Novedad::orderBy('orden', 'asc')->get();
        $novedades->each(function ($novedad) {
            $novedad->path = asset('storage/' . $novedad->path);
        });
        $banner = Banner::where('seccion', 'novedades')->first();
        if ($banner) {
            $banner->path = asset('storage/' . $banner->path);
        }
        $metadatos = Metadato::where('seccion', 'novedades')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.novedades', compact('novedades', 'banner', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
    public function show($id)
    {
        $novedad = Novedad::findOrFail($id);
        $novedad->path = asset('storage/' . $novedad->path);
        $banner = Banner::where('seccion', 'novedades')->first();
        if ($banner) {
            $banner->path = asset('storage/' . $banner->path);
        }
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.novedad', compact('novedad', 'banner', 'logos', 'contactos', 'whatsapp'));
    }
}
