<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('inicio', function ($trail) {
    $trail->push('Inicio', route('index'));
});

Breadcrumbs::for('libros.index', function ($trail) {
    $trail->parent('inicio');
    $trail->push('Todos', route('libros.index'));
});

Breadcrumbs::for('libros.novedades', function ($trail) {
    $trail->parent('inicio');
    $trail->push('Novedades', route('libros.novedades'));
});

Breadcrumbs::for('libro.porGenero', function ($trail, $genero) {
    $trail->parent('inicio');
    $trail->push('Todos', route('libros.index'));
    $trail->push($genero->nombre, route('genero.index', $genero->id));
});
