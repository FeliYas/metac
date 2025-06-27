<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        foreach ($categorias as $categoria) {
            $categoria->path = Storage::url($categoria->path);
            $categoria->banners = Storage::url($categoria->banners);
        }
        $banner = Banner::where('seccion', 'categorias')->first();
        $banner->path = Storage::url($banner->path);
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return inertia('Admin/Categorias', [
            'categorias' => $categorias,
            'banner' => $banner,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'path' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'banners' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        
        $imagePath = null;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $imagePath = $file->store('images');
        }
        $bannerPath = null;
        if ($request->hasFile('banners')) {
            $file = $request->file('banners');
            $bannerPath = $file->store('images');
        }

        $categoria = Categoria::create(array_filter([
            'orden' => $request->orden,
            'path' => $imagePath,
            'banners' => $bannerPath,
            'titulo' => $request->titulo,
        ]));

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria creada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'path' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'banners' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $categoria = Categoria::find($id);

        $imagePath = $categoria->path;
        if ($request->hasFile('path')) {
            $ruta = $categoria->path;
            $file = $request->file('path');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $imagePath = $file->store('images');
        }

        $bannerPath = $categoria->banners;
        if ($request->hasFile('banners')) {
            $ruta = $categoria->banners;
            $file = $request->file('banners');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $bannerPath = $file->store('images');
        }

        $categoria->update(array_filter([
            'orden' => $request->orden ?? $categoria->orden,
            'path' => $imagePath,
            'banners' => $bannerPath,
            'titulo' => $request->titulo ?? $categoria->titulo,
        ]));
        $categoria->save();
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria actualizada exitosamente');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        if ($categoria->path) {
            if (Storage::exists($categoria->path)) {
                Storage::delete($categoria->path);
            }
        }
        if ($categoria->banners) {
            if (Storage::exists($categoria->banners)) {
                Storage::delete($categoria->banners);
            }
        }
        $categoria->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria eliminada exitosamente');
    }
    public function toggleDestacado(Request $request)
    {
        $categoria = Categoria::findOrFail($request->id);
        $categoria->destacado = $request->destacado ? 1 : 0;
        $categoria->save();

        if ($categoria->destacado == 1) {
            return redirect()->route('categorias.dashboard')->with('message', 'Categoria destacada exitosamente');
        } else {
            return redirect()->route('categorias.dashboard')->with('message', 'Categoria eliminada de destacados');
        }
    }
}
