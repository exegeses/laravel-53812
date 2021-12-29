<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos listado de marcas
        $marcas = Marca::paginate(7);
        return view('adminMarcas', [ 'marcas'=>$marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarMarca');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //validación
        $this->validarForm( $request );
        //instanciamos objeto
        $Marca = new Marca;
        //asignamos atributos
        $Marca->mkNombre = $mkNombre = $request->mkNombre;
        //guardar en tabla marcas
        $Marca->save();
        //redirección con mensaje de ok
        return redirect('/adminMarcas')
                ->with([ 'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.' ]);
    }


    /**
     * Método para validar formulario
     * @param Request $request
     */
    private function validarForm(Request $request ) : void
    {
        $request->validate(
            [   'mkNombre'=>'required|min:2|max:50|unique:App\Models\Marca' ],
            [
                'mkNombre.required'=>'El campo "Nombre de la marca" es obligatorio.',
                'mkNombre.min'=>'El campo "Nombre de la marca" debe tener al menos 2 caractéres.',
                'mkNombre.max'=>'El campo "Nombre de la marca" debe tener 50 caractéres como máximo.',
                'mkNombre.unique'=>'El "Nombre de la marca" debe ser único, no debe haber duplicados.'
            ]
        );
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
        //obtenemos datos de una marca
        $Marca = Marca::find($id);
        return view('modificarMarca', [ 'Marca'=>$Marca ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validamos
        $this->validarForm($request);
        //obtenemos datos de marca
        $Marca = Marca::find($request->idMarca);
        //modificar atributo
        $Marca->mkNombre = $mkNombre = $request->mkNombre;
        //guardamos
        $Marca->save();
        //redirección con mensaje de ok
        return redirect('/adminMarcas')
            ->with([ 'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.' ]);
    }


    /**
     * Método para chequear si hay productos de una marca
     * @param $idMarca
     * @return Producto $producto | null
     */
    private function productoPorMarca($idMarca)
    {
        //$check = Producto::where('idMarca', $idMarca)->first();
        $check = Producto::firstWhere('idMarca', $idMarca);
        return $check;
    }

    public function confirmarBaja($id)
    {
        //obtenemos datos de una marca
        $Marca = Marca::find($id);

        //si NO hay productos de esa marca
        if ( !$this->productoPorMarca($id) ){
            //retornamos vista de confirmación
            return view('eliminarMarca', [ 'Marca'=>$Marca ]);
        }
        //redirección a admin con mensaje que no se puede borrar
        return redirect('/adminMarcas')
                    ->with([
                        'mensaje'=>'No se puede eliminar la marca '.$Marca->mkNombre.' porque tiene productos relacionados.',
                        'alert' => 'danger'
                    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Marca::destroy($request->idMarca);
        //redirección con mensaje ok
        return redirect('/adminMarcas')
            ->with(
                [
                    'mensaje'=>'Marca: '.$request->mkNombre.' eliminada correctamente.',
                    'alert'=>'info'
                ]);
    }
}
