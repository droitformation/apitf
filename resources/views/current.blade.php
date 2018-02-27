@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">

            @if(!empty($tables))
                <div class="col-md">
                    <div class="card">
                        <div class="card-body">
                            <h3>Connexion MySql Tables</h3>
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