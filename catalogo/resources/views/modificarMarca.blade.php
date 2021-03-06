@extends('layouts.plantilla')

    @section('contenido')

        <h1>Modificación de una marca</h1>

        <div class="alert bg-light border border-white shadow round col-8 mx-auto p-4">

            <form action="/modificarMarca" method="post">
            @csrf
            @method('patch')
                <div class="form-group">
                    <label for="mkNombre">Nombre de la marca</label>
                    <input type="text" name="mkNombre"
                           value="{{ old('mkNombre', $Marca->mkNombre ) }}"
                           class="form-control" id="mkNombre">
                    <input type="hidden" name="idMarca"
                           value="{{ $Marca->idMarca }}">
                </div>
                <button class="btn btn-dark mr-3">Modificar marca</button>
                <a href="/adminMarcas" class="btn btn-outline-secondary">
                    Volver a panel
                </a>
            </form>
        </div>

        @include('layouts.msgErrorValidacion')

    @endsection

