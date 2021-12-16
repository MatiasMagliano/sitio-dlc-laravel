<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Devuelve una vista y le pasa un array--> lleno con los datos de User::all(), como argumento
        return view(
            'admin.users.index',
            ['usuarios' => User::all()],
            ['roles' => Rol::all()]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // se deben validar los datos que vienen de la página (modal crear usuario) y tienen un tratamiento
        // diferente, ya que se valida por medio del Controller y dej js.ajax que viene de la página
        $datosValidados = $request->validate([
            'name' => 'required|max:50', //dos formas: con pipe para acumular reglas o con un array
            'email' => 'required|max:255|unique:users',
            'password' => 'required|min:8|max:50'
        ]);

        // Se crea una copia del modelo para guardar en la base de datos. En este caso User -> $request->except(['_token', 'roles'])
        // pero antes, se le pasa los datos validados
        $usuario = User::create($datosValidados);

        // ahora es necesario guardar las relaciones. En este caso con la tabla rols
        // hay dos métodos: attach('id') que permite sólo un id y sync([array]) que permite varios
        $usuario->roles()->sync($request->roles);

        // se pasa el argumento para el sweetalert2
        $request->session()->flash('success', 'Se ha creado el usuario con éxito.');

        return redirect(route('admin.users.index'));
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
        //
        return view('admin.users.edit', 
            [
                'roles' => Rol::all(),
                'editusuario'  => User::find($id)
            ],

        );
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
        // se busca el usuario en cuestión ->id. Si falla, se pasa el argumento a SweetAlert2
        $usuario = User::find($id);

        $datosValidados = $request->validate([
            'name' => 'required|max:50', //dos formas: con pipe para acumular reglas o con un array
            'email' => 'required|max:255'
        ]);

        if(!$usuario){
            // se pasa el argumento para el sweetalert2 con error
            $request->session()->flash('error', 'Ocurrió un error al intentar borrar el usuario.');
            return redirect(route('admin.users.index'));
        }

        // se llama al método update del modelo y se le pasa el request, excepto el token de Laravel y los roles
        $usuario->update($datosValidados);

        // se sincronizan los roles
        $usuario->roles()->sync($request->roles);

        // se pasa el argumento para el sweetalert2 con success
        $request->session()->flash('success', 'Se ha actualizado el usuario con éxito.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // Simplemente, se usa el método del modelo destroy('id')
        User::destroy($id);

        // se pasa el argumento para el sweetalert2
        $request->session()->flash('success', 'Se ha borrado el usuario con éxito.');

        // ...y se redirige a la página deseada (en este, caso a la lista de usuarios: admin.users.index)
        return redirect(route('admin.users.index'));
    }
}