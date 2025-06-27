<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Logo;
use App\Models\Nosotros;
use App\Models\Tarjeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NosotrosController extends Controller
{
    public function index()
    {
        $nosotros = Nosotros::first();
        $nosotros->path = Storage::url($nosotros->path);
        $nosotros->path2 = Storage::url($nosotros->path2);
        $banner = Banner::where('seccion', 'nosotros')->first();
        $banner->path = asset('storage/' . $banner->path);
        $tarjetas = Tarjeta::all();
        foreach ($tarjetas as $tarjeta) {
            $tarjeta->path = Storage::url($tarjeta->path);
        }
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return inertia('Admin/Nosotros', [
            'nosotros' => $nosotros,
            'banner' => $banner,
            'tarjetas' => $tarjetas,
            'logo' => $logo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov,webp|max:100000',
            'path2' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov,webp|max:100000',
            'titulo' => 'nullable|string|max:255',
            'titulo2' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'descripcion2' => 'nullable|string',
            'banner' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:100000',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator->messages()->first());
        }
        
        $nosotros = Nosotros::findOrFail($id);
        $banner = Banner::where('seccion', 'nosotros')->first();

        // Validar y procesar la imagen si se proporciona
        $imagePath = $nosotros->path;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            if (Storage::exists($nosotros->path)) {
                Storage::delete($nosotros->path);
            }
            $imagePath = $file->store('images');
        }

        // Validar y procesar la segunda imagen si se proporciona
        $imagePath2 = $nosotros->path2;
        if ($request->hasFile('path2')) {
            $file = $request->file('path2');
            if ($imagePath2 && Storage::exists($imagePath2)) {
                Storage::delete($imagePath2);
            }
            $imagePath2 = $file->store('images');
        }

        // Validar y procesar el banner si se proporciona
        $bannerPath = $banner->path;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            if ($bannerPath && Storage::exists($bannerPath)) {
                Storage::delete($bannerPath);
            }
            $bannerPath = $file->store('images');
            $banner->update(['path' => $bannerPath]);
        }

        $nosotros->update([
            'titulo' => $request->titulo,
            'titulo2' => $request->titulo2,
            'descripcion' => $request->descripcion,
            'descripcion2' => $request->descripcion2,
            'path' => $imagePath,
            'path2' => $imagePath2,
        ]);

        return redirect()->route('nosotros.dashboard')->with('message', 'Nosotros actualizado exitosamente');
    }

    public function updateTarjeta(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->messages()->first());
        }

        $tarjeta = Tarjeta::findOrFail($id);
        
        $tarjeta->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('nosotros.dashboard')->with('message', 'Tarjeta actualizada exitosamente');
    }
}