<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Logo;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubcategoriaController extends Controller
{
    public function index()
    {
        $subcategorias = Subcategoria::orderBy('orden', 'asc')->get();
        foreach ($subcategorias as $subcategoria) {
            $subcategoria->path = Storage::url($subcategoria->path);
            $subcategoria->banners = Storage::url($subcategoria->banners);
        }
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return inertia('Admin/Subcategorias', [
            'subcategorias' => $subcategorias,
            'categorias' => $categorias,
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
            'categoria_id' => 'required|exists:categorias,id',
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

        $subcategoria = Subcategoria::create(array_filter([
            'orden' => $request->orden,
            'path' => $imagePath,
            'banners' => $bannerPath,
            'titulo' => $request->titulo,
            'categoria_id' => $request->categoria_id,
        ]));

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('subcategorias.dashboard')->with('message', 'Subcategoria creada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'path' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'banners' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $subcategoria = Subcategoria::find($id);

        $imagePath = $subcategoria->path;
        if ($request->hasFile('path')) {
            $ruta = $subcategoria->path;
            $file = $request->file('path');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $imagePath = $file->store('images');
        }

        $bannerPath = $subcategoria->banners;
        if ($request->hasFile('banners')) {
            $ruta = $subcategoria->banners;
            $file = $request->file('banners');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $bannerPath = $file->store('images');
        }

        $subcategoria->update(array_filter([
            'orden' => $request->orden ?? $subcategoria->orden,
            'path' => $imagePath,
            'banners' => $bannerPath,
            'titulo' => $request->titulo ?? $subcategoria->titulo,
            'categoria_id' => $request->categoria_id ?? $subcategoria->categoria_id,
        ]));
        $subcategoria->save();
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('subcategorias.dashboard')->with('message', 'Subcategoria actualizada exitosamente');
    }
    public function destroy($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        if ($subcategoria->path) {
            if (Storage::exists($subcategoria->path)) {
                Storage::delete($subcategoria->path);
            }
        }
        if ($subcategoria->banners) {
            if (Storage::exists($subcategoria->banners)) {
                Storage::delete($subcategoria->banners);
            }
        }
        $subcategoria->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('subcategorias.dashboard')->with('message', 'Subcategoria eliminada exitosamente');
    }
}
