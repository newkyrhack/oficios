<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class OficioController extends Controller
{
    public function actas(Request $request){
        //echo $request->tipo;
        $templates = DB::table('templates')
        ->join('oficios', 'templates.idOficio', '=', 'oficios.id')
        ->join('secciones_oficios', 'templates.idSeccion', '=', 'secciones_oficios.id')
        ->where('oficios.nombre', $request->tipo)
        ->get();
        return response()->json($templates);
    }

    public function prueba(){
        return view("prueba");
    }
}
