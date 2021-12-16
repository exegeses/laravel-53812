<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estructuras de control</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/prueba.css">
</head>
<body>
    <h1>Estructuras de control a impresión de datos</h1>

    {{-- comentario --}}
    @if( $nombre == 'marcos' )
        Bienvenido {{ $nombre }}
    @else
        Usuario desconocido
    @endif

    <br>

    <ul>
    @foreach( $marcas as $marca )
        <li>{{ $marca }}</li>
    @endforeach
    </ul>


</body>
</html>
