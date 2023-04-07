<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('Inicio', function ($trail) {
    $trail->push('Inicio', route('index'));
});

Breadcrumbs::for('libros.index', function ($trail) {
    $trail->parent('Inicio');
    $trail->push('Todos', route('libros.index'));
});

Breadcrumbs::for('libros.novedades', function ($trail) {
    $trail->parent('Inicio');
    $trail->push('Novedades', route('libros.novedades'));
});

Breadcrumbs::for('libros.recomendaciones', function ($trail) {
    $trail->parent('Inicio');
    $trail->push('Recomendaciones', route('libros.recomendaciones'));
});

Breadcrumbs::for('libro.porGenero', function ($trail, $genero) {
    $trail->parent('Inicio');
    $trail->push('Todos', route('libros.index'));
    $trail->push($genero->nombre, route('genero.index', $genero->id));
});
