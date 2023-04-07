
@extends('layouts.app')

@section('content')

@foreach ($libreria->lecturas as $lectura)
    {{ $lectura->estado }}
    
@endforeach




@endsection

