<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Cliente;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
   public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        $logo->path = Storage::url($logo->path);
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        return inertia('Admin/Clientes', [
            'clientes' => $clientes,
            'logo' => $logo
        ]);

    }
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('clientes.dashboard')->with('message', 'Cliente eliminado exitosamente');
    }
    public function toggleAutorizado(Request $request)
    {
        $cliente = Cliente::findOrFail($request->id);
        $cliente->autorizado = $request->autorizado ? 1 : 0;
        $cliente->save();

        if ($cliente->autorizado == 1) {
            return redirect()->route('clientes.dashboard')->with('message', 'Cliente autorizado exitosamente');
        } else {
            return redirect()->route('clientes.dashboard')->with('message', 'Cliente desautorizado exitosamente');
        }
    }
}
