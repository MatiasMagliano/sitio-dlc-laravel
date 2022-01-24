<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roles.index', ['roles' => Rol::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // ES UN MODAL, POR ELLOS NO SE MUESTRA ACÁ EL MÉTODO CREAR...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre' => 'required|max:50', //dos formas: con pipe para acumular reglas o con un array
            'descripcion' => 'required|max:500',
        ]);

        $rol = Rol::create($datosValidados);
        $request->session()->flash('success', 'Se ha creado el rol con éxito.');

        return redirect(route('admin.roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.roles.edit', ['rol' => Rol::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);

        $datosValidados = $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'required|max:500'
        ]);

        if(!$rol){
            $request->session()->flash('error', 'Ocurrió un error al intentar borrar el rol.');
            return redirect(route('admin.roles.index'));
        }

        $rol->update($datosValidados);
        $request->session()->flash('success', 'Se ha actualizado el rol con éxito.');

        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        Rol::destroy($id);
        $request->session()->flash('success', 'Se ha borrado el rol con éxito.');
        return redirect(route('admin.roles.index'));
    }
}
