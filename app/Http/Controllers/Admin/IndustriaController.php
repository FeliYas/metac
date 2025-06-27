<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Industria;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IndustriaController extends Controller
{
    public function index()
    {
        $industrias = Industria::withCount('productos')->orderBy('orden', 'asc')->get();
        foreach ($industrias as $industria) {
            $industria->banners = Storage::url($industria->banners);
            $industria->path = Storage::url($industria->path);
            $industria->portada = Storage::url($industria->portada);
        }
        $banner = Banner::where('seccion', 'industrias')->first();
        $banner->path = Storage::url($banner->path);
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return inertia('Admin/Industrias', [
            'industrias' => $industrias,
            'banner' => $banner,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'banners' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'path' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'portada' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        
        $imagePath = null;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $imagePath = $file->store('images');
        }

        $portadaPath = null;
        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $portadaPath = $file->store('images');
        }

        $bannerPath = null;
        if ($request->hasFile('banners')) {
            $file = $request->file('banners');
            $bannerPath = $file->store('images');
        }

        Industria::create([
            'orden' => $request->orden,
            'banners' => $bannerPath,
            'path' => $imagePath,
            'portada' => $portadaPath,
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'descripcion' => $request->descripcion,
        ]);


        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('industrias.dashboard')->with('message', 'Industria creada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'nullable|string|max:255',
            'banners' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'path' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'portada' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo' => 'nullable|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $industria = Industria::find($id);

        // Inicializar variables para evitar null
        $imagePath = $industria->path;

        if ($request->hasFile('path')) {
            $ruta = $industria->path;
            $file = $request->file('path');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $imagePath = $file->store('images');
        }

        $portadaPath = $industria->portada;
        if ($request->hasFile('portada')) {
            $ruta = $industria->portada;
            $file = $request->file('portada');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $portadaPath = $file->store('images');
        }

        $bannerPath = $industria->banners;
        if ($request->hasFile('banners')) {
            $ruta = $industria->banners;
            $file = $request->file('banners');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $bannerPath = $file->store('images');
        }

        $industria->update(array_filter([
            'orden' => $request->orden ?? $industria->orden,
            'path' => $imagePath,
            'portada' => $portadaPath,
            'banners' => $bannerPath,
            'titulo' => $request->titulo ?? $industria->titulo,
            'subtitulo' => $request->subtitulo ?? $industria->subtitulo,
            'descripcion' => $request->descripcion ?? $industria->descripcion,
        ]));

        $industria->save();
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('industrias.dashboard')->with('message', 'Industria actualizada exitosamente');
    }

    public function destroy($id)
    {
        $industria = Industria::findOrFail($id);
        if ($industria->path) {
            if (Storage::exists($industria->path)) {
                Storage::delete($industria->path);
            }
        }
        if ($industria->portada) {
            if (Storage::exists($industria->portada)) {
                Storage::delete($industria->portada);
            }
        }
        if ($industria->banners) {
            if (Storage::exists($industria->banners)) {
                Storage::delete($industria->banners);
            }
        }
        
        $industria->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('industrias.dashboard')->with('message', 'Industria eliminada exitosamente');
    }
}
