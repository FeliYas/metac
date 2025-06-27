<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ClienteAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Buscar cliente por usuario
        $cliente = Cliente::where('usuario', $request->usuario)->first();

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $cliente->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Verificar si está autorizado
        if (!$cliente->autorizado) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está pendiente de autorización. Contacta al administrador.'
            ], 403);
        }

        // Iniciar sesión del cliente
        Session::put('cliente_id', $cliente->id);
        Session::put('cliente_logueado', true);

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'redirect' => route('zonaprivada.fichatecnica')
        ]);
    }

    public function logout(Request $request)
    {
        Session::forget(['cliente_id', 'cliente_logueado']);
        
        return redirect()->route('home')->with('message', 'Sesión cerrada exitosamente');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:clientes,usuario',
            'email' => 'required|email|max:255|unique:clientes,email',
            'telefono' => 'required|string|max:255',
            'cuit' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'usuario.unique' => 'Este usuario ya está registrado',
            'email.unique' => 'Este email ya está registrado',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cliente = Cliente::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'usuario' => $request->usuario,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'cuit' => $request->cuit,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso. Tu cuenta está pendiente de autorización por parte del administrador.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cuenta. Inténtalo de nuevo.'
            ], 500);
        }
    }
}
