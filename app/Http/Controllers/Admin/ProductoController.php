<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Logo;
use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('orden', 'asc')->get();
        foreach ($productos as $producto) {
            if ($producto->fichatecnica) {
            $producto->fichatecnica = Storage::url($producto->fichatecnica);
            }
            if ($producto->fichaseguridad) {
            $producto->fichaseguridad = Storage::url($producto->fichaseguridad);
            }
        }
        $subcategorias = Subcategoria::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        return inertia('Admin/Productos', [
            'productos' => $productos,
            'subcategorias' => $subcategorias,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'fichatecnica' => 'nullable|mimes:pdf|max:2048',
            'fichaseguridad' => 'nullable|mimes:pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        
        $fichaPath = null;
        if ($request->hasFile('fichatecnica')) {
            $file = $request->file('fichatecnica');
            $fichaPath = $file->store('fichas');
        }
        $fichaseguridadPath = null;
        if ($request->hasFile('fichaseguridad')) {
            $file = $request->file('fichaseguridad');
            $fichaseguridadPath = $file->store('fichas');
        }

        $producto = Producto::create(array_filter([
            'orden' => $request->orden,
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'descripcion' => $request->descripcion,
            'subcategoria_id' => $request->subcategoria_id,
            'fichatecnica' => $fichaPath,
            'fichaseguridad' => $fichaseguridadPath,
        ]));

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('productos.dashboard')->with('message', 'Producto creado exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'fichatecnica' => 'nullable|mimes:pdf|max:2048',
            'fichaseguridad' => 'nullable|mimes:pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $producto = Producto::find($id);

        
        $fichaPath = $producto->fichatecnica;
        if ($request->hasFile('fichatecnica')) {
            $ruta = $producto->fichatecnica;
            $file = $request->file('fichatecnica');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $fichaPath = $file->store('fichas');
        }
        $fichaseguridadPath = $producto->fichaseguridad;
        if ($request->hasFile('fichaseguridad')) {
            $ruta = $producto->fichaseguridad;
            $file = $request->file('fichaseguridad');
            if ($ruta && Storage::exists($ruta)) {
                Storage::delete($ruta);
            }
            $fichaseguridadPath = $file->store('fichas');
        }

        $producto->update(array_filter([
            'orden' => $request->orden ?? $producto->orden,
            'titulo' => $request->titulo ?? $producto->titulo,
            'subtitulo' => $request->subtitulo ?? $producto->subtitulo,
            'descripcion' => $request->descripcion ?? $producto->descripcion,
            'subcategoria_id' => $request->subcategoria_id ?? $producto->subcategoria_id,
            'fichatecnica' => $fichaPath,
            'fichaseguridad' => $fichaseguridadPath,
            
        ]));
        $producto->save();
        
        return redirect()->route('productos.dashboard')->with('message', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        if ($producto->fichatecnica) {
            if (Storage::exists($producto->fichatecnica)) {
                Storage::delete($producto->fichatecnica);
            }
        }
        if ($producto->fichaseguridad) {
            if (Storage::exists($producto->fichaseguridad)) {
                Storage::delete($producto->fichaseguridad);
            }
        }
        $producto->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('productos.dashboard')->with('message', 'Producto eliminado exitosamente');
    }
}
