<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

    public function portada()
    {
        //obtenemos listado de productos
        $productos = Producto::with([ 'getMarca', 'getCategoria' ])
            ->paginate(5);
        return view('portada', [ 'productos'=>$productos ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos listado de productos
        $productos = Producto::with([ 'getMarca', 'getCategoria' ])
                                ->paginate(5);
        return view('adminProductos', [ 'productos'=>$productos ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('agregarProducto',
            [
                'marcas'    =>$marcas,
                'categorias'=>$categorias
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validamos
        $this->validarForm($request);
        //subir imagen *
        $prdImagen = $this->subirImagen($request);
        //instanciamos
        $Producto = new Producto();
        //asignamos atributos
        $Producto->prdNombre = $request->prdNombre;
        $Producto->prdPrecio = $request->prdPrecio;
        $Producto->idMarca = $request->idMarca;
        $Producto->idCategoria = $request->idCategoria;
        $Producto->prdPresentacion = $request->prdPresentacion;
        $Producto->prdStock = $request->prdStock;
        $Producto->prdImagen = $prdImagen;
        //guardamos
        $Producto->save();
        //redireci??n con mensaje ok
        return redirect('adminProductos')
            ->with(['mensaje'=>'Producto: '.$request->prdNombre.' agregado correctamente.']);
    }

    private function validarForm(Request $request)
    {
        $request->validate(
            [
                'prdNombre'=>'required|min:2|max:30',
                'prdPrecio'=>'required|numeric|min:0',
                'idMarca'=>'required',
                'idCategoria'=>'required',
                'prdPresentacion'=>'required|min:3|max:150',
                'prdStock'=>'required|integer|min:0',
                'prdImagen'=>'mimes:jpg,jpeg,png,gif,svg,webp|max:2048'
            ],
            [
                'prdNombre.required'=>'El campo "Nombre del producto" es obligatorio.',
                'prdNombre.min'=>'El campo "Nombre del producto" debe tener como m??nimo 2 caract??res.',
                'prdNombre.max'=>'El campo "Nombre" debe tener 30 caract??res como m??ximo.',
                'prdPrecio.required'=>'Complete el campo Precio.',
                'prdPrecio.numeric'=>'Complete el campo Precio con un n??mero.',
                'prdPrecio.min'=>'Complete el campo Precio con un n??mero positivo.',
                'idMarca.required'=>'Seleccione una marca.',
                'idCategoria.required'=>'Seleccione una categor??a.',
                'prdPresentacion.required'=>'Complete el campo Presentaci??n.',
                'prdPresentacion.min'=>'Complete el campo Presentaci??n con al menos 3 caract??res',
                'prdPresentacion.max'=>'Complete el campo Presentaci??n con 150 caract??rescomo m??xino.',
                'prdStock.required'=>'Complete el campo Stock.',
                'prdStock.integer'=>'Complete el campo Stock con un n??mero entero.',
                'prdStock.min'=>'Complete el campo Stock con un n??mero positivo.',
                'prdImagen.mimes'=>'Debe ser una imagen.',
                'prdImagen.max'=>'Debe ser una imagen de 2MB como m??ximo.'
            ]
        );
    }

    private function subirImagen(Request $request) : string
    {
        //si no se envi?? imagen  ## default
        $prdImagen = 'noDisponible.jpg';

        //s??lo en formulario de modificar
        if( $request->has('imgActual') ){
            $prdImagen = $request->imgActual;
        }

        //si se envi?? imagen
        if( $request->file('prdImagen') ){
            //renombrar  time().extension
            $extension = $request->file('prdImagen')->extension();
            $prdImagen = time().'.'.$extension;
            //subir archivo
            $request->file('prdImagen')
                    ->move( public_path('productos/'), $prdImagen );
        }

        return $prdImagen;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //obtenemos datos de un producto
        $Producto = Producto::find( $id );
        //obtenemos listados de marcas y categor??as
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('modificarProducto',
            [
                'Producto'  => $Producto,
                'marcas'    => $marcas,
                'categorias'=>$categorias
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validamos
        $this->validarForm($request);
        //subir imagen *
        $prdImagen = $this->subirImagen($request);
        //obtenemnos datos de un producto
        $Producto = Producto::find( $request->idProducto );
        //modificamos atributos
        $Producto->prdNombre = $request->prdNombre;
        $Producto->prdPrecio = $request->prdPrecio;
        $Producto->idMarca = $request->idMarca;
        $Producto->idCategoria = $request->idCategoria;
        $Producto->prdPresentacion = $request->prdPresentacion;
        $Producto->prdStock = $request->prdStock;
        $Producto->prdImagen = $prdImagen;
        //guardamos
        $Producto->save();
        //redirecci??n con mensaje ok
        return redirect('/adminProductos')
                ->with(['mensaje'=>'Producto: '.$request->prdNombre.' modificado correctamente.']);
    }

    public function confirmarBaja( $id )
    {
        $Producto = Producto::with(['getCategoria', 'getMarca'])->find($id);
        return view('eliminarProducto', [ 'Producto'=>$Producto ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request )
    {
        Producto::destroy($request->idProducto);
        //redirecci??n con mensaje ok
        return redirect('/adminProductos')
            ->with(['mensaje'=>'Producto: '.$request->prdNombre.' eliminado correctamente.']);

    }
}
