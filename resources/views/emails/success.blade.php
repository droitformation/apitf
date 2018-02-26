@extends('layouts.notification')
@section('content')

    <h3 style="font-size:20px;">Job terminé</h3>
    <p style="color: #000;margin-top: 5px;margin-bottom: 0px;">
        {{ $notification }}
    </p>

@stop