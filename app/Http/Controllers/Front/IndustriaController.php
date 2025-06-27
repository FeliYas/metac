<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Contacto;
use App\Models\Industria;
use App\Models\Logo;
use App\Models\Metadato;
use Illuminate\Http\Request;

class IndustriaController extends Controller
{
    public function index()
    {
        $industrias = Industria::orderBy('orden', 'asc')->get();
        foreach ($industrias as $industria) {
            $industria->path = asset('storage/' . $industria->path);
            $industria->portada = asset('storage/' . $industria->portada);
        }
        $banner = Banner::where('seccion', 'industrias')->first();
        if ($banner) {
            $banner->path = asset('storage/' . $banner->path);
        }
        $metadatos = Metadato::where('seccion', 'industrias')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.industrias', compact('industrias', 'banner', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
    public function show($id)
    {
        $industria = Industria::findOrFail($id);
        $industria->banners = asset('storage/' . $industria->banners);
        $industria->path = asset('storage/' . $industria->path);
        $industria->portada = asset('storage/' . $industria->portada);
        $productos = $industria->productos()->orderBy('orden', 'asc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        foreach ($logos as $logo) {
            $logo->path = asset('storage/' . $logo->path);
        }
        $contactos = Contacto::select('direccion', 'email', 'telefono', 'facebook', 'instagram', 'whatsapp')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.industria', compact('industria', 'productos', 'logos', 'contactos', 'whatsapp'));
    }
}
