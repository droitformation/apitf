@extends('layouts.notification')
@section('content')

    <h3 style="font-size:20px;">Erreur avec la récupération des arrêts</h3>
    <p style="color: #000;margin-top: 5px;margin-bottom: 0px;">
        {{ $error }}
    </p>

@stop