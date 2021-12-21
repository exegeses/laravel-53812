<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::metodo('peticion', acción );
Route::get('/saludo', function ()
{
    return 'hola mundo desde laravel';
});
Route::get('/test', function ()
{
    return view('prueba');
});
Route::get('/imprimir', function ()
{
    //generamos variables
    $nombre = 'marcos';
    $marcas = [ 'Ford', 'Audi', 'Peugeot', 'Renault', 'Aston Martin', 'Fiat' ];
    //retornamos vista
    return view('imprimir',
                    [
                        'nombre'=>$nombre,
                        'marcas'=>$marcas
                    ]
            );
});
##### extendiendo la plantilla
Route::get('/inicio', function ()
{
    return view('inicio');
});
###################################################
##### CRUD de regiones
Route::get('/adminRegiones', function ()
{
    //obtenemos listado de regiones
    /*$regiones = DB::select('SELECT idRegion, regNombre FROM regiones');*/
    $regiones = DB::table('regiones')->get();  //fetchAll()
    //retornar la vista del admin
    return view('adminRegiones', [ 'regiones'=>$regiones ]);
});
Route::get('/agregarRegion', function ()
{
    return view('agregarRegion');
});
Route::post('/agregarRegion', function ()
{
    //capturamos dato enviado por el form
    $regNombre = $_POST['regNombre'];
    //insertamos dato en tabla regiones
    /*DB::insert('INSERT INTO regiones
                            ( regNombre )
                        VALUE
                            ( :regNombre )',
                            [ $regNombre ]);*/
    DB::table('regiones')->insert([ 'regNombre' => $regNombre ]);
    //redirección con reporte ok
    return redirect('/adminRegiones')
                ->with(['mensaje'=>'Region: '.$regNombre.' agregada correctamente.']);
});
Route::get('/modificarRegion/{id}', function ($idRegion)
{
    //obtenemos datos de la región
    /*$region = DB::select('
                SELECT * FROM
                    regiones
                    WHERE idRegion = :id', [$idRegion] );*/
    $region = DB::table('regiones')
                    ->where('idRegion', $idRegion)
                    ->first();  // fetch()

    return view('modificarRegion',
                [ 'region'=>$region ]
            );
});
Route::post('/modificarRegion', function ()
{
    $regNombre = $_POST['regNombre'];
    $idRegion = $_POST['idRegion'];
    /*DB::update('UPDATE regiones
                    SET regNombre = :regNombre
                  WHERE idRegion  = :idRegion',
                    [ $regNombre, $idRegion ]);*/
    DB::table('regiones')
            ->where( 'idRegion', $idRegion )
            ->update( [ 'regNombre' => $regNombre ] );
    //redirección con reporte ok
    return redirect('/adminRegiones')
        ->with(['mensaje'=>'Region: '.$regNombre.' agregada correctamente.']);
});
