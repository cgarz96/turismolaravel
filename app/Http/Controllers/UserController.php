<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!is_null($user) && Hash::check($request->password, $user->password)) {
            $user->api_token=Str::random(100);
            $user->save();
            return response()->json(['res' => true,
             'token' => $user->api_token,
              'message' => "Bienvenido al sistema de turismo"],200);
        } else
            return response()->json(['res' => false, 'message' => "Cuenta o password incorrectos"],200);
    }


    public function logout()
    {
        $user = auth()->user();
        $user->api_token=null;
        $user->save();
        return response()->json(['res' => true, 'message' => "Adios"],200);
    }


    public function getPackages()
    {
         $paquetes = DB::table('paqueteturistico')
            ->join('paqueteturisticoproducto', 'paqueteturisticoproducto.id_paquete_turistico', '=', 'paqueteturistico.id_paquete_turistico')
            ->join('producto', 'producto.id_producto', '=', 'paqueteturisticoproducto.id_producto')
            ->select('*')
            ->get();

            return $paquetes;
    }
}
