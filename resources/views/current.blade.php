@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">

            @if(!empty($tables))
                <div class="col-md">
                    <h2>Connexion MySql</h2>
                    <div class="card">
                        <div class="card-body">
                            <h3>Tables</h3>
                            @foreach($tables as $table)
                                <p>{{ $table }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@stop