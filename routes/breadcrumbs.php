<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('inicio', function ($trail) {
    $trail->push('Inicio', route('index'));
});

Breadcrumbs::for('libro.porGenero', function ($trail, $genero) {
    $trail->parent('inicio');
    $trail->push($genero->nombre, route('genero.index', $genero->id));
});
