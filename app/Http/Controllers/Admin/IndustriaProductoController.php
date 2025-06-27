<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industria;
use App\Models\IndustriaProducto;
use App\Models\Logo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndustriaProductoController extends Controller
{
    public function index($id)
    {
        // Primero encontramos la industria por su ID
        $industria = Industria::findOrFail($id);
        $industria->path = Storage::url($industria->path);
        // Obtenemos todos los productos relacionados con esta industria
        $productosRelacionados = $industria->productos()->with('subcategoria')->get();
        $productos = Producto::orderby('orden' , 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);

        return inertia('Admin/Relacionados', [
            'industria' => $industria,
            'productosRelacionados' => $productosRelacionados,
            'productos' => $productos,
            'logo' => $logo
        ]);
    }
    /**
     * Almacenar nuevas relaciones entre una industria y múltiples productos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'industria_id' => 'required|exists:industrias,id',
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
        ]);

        $industria = Industria::findOrFail($validated['industria_id']);
        
        // Obtener los IDs de productos ya relacionados
        $productosActuales = $industria->productos()->pluck('productos.id')->toArray();
        
        // Obtener los productos seleccionados
        $nuevoProductosIds = $validated['productos'];
        
        // Combinar productos actuales con los nuevos (sin duplicar)
        $todosProductosIds = array_unique(array_merge($productosActuales, $nuevoProductosIds));
        
        // Sincronizar productos a la industria (mantiene los existentes y añade los nuevos)
        $industria->productos()->sync($todosProductosIds);
        
        return redirect()->route('relacionados.dashboard', $industria->id)
            ->with('success', 'Productos agregados exitosamente a la industria.');
    }

    /**
     * Eliminar una relación entre industria y producto.
     *
     * @param  int  $productoId  El ID del producto a desrelacionar
     * @param  int  $industriaId  El ID de la industria (viene de la ruta)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($industriaId, $productoId)
    {
        // Buscamos la industria
        $industria = Industria::findOrFail($industriaId);
        
        // Verificamos que la relación existe
        $relacion = IndustriaProducto::where('industria_id', $industriaId)
                                    ->where('producto_id', $productoId)
                                    ->first();
        
        if (!$relacion) {
            return redirect()->route('relacionados.dashboard', $industriaId)
                ->with('error', 'La relación entre la industria y el producto no existe.');
        }
        
        // Eliminamos la relación
        $relacion->delete();
        
        return redirect()->route('relacionados.dashboard', $industriaId)
            ->with('success', 'Producto eliminado exitosamente de esta industria.');
    }
}
