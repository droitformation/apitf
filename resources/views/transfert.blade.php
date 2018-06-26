@extends('layouts.master')
@section('content')

    <div class="container">
        <h1>Transfert</h1>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">

                        <form action="{{ url('/dotransfert') }}" method="POST" class="mb-4">{!! csrf_field() !!}

                            <div class="form-group">
                                <label for="nom">database</label>
                                <input type="text" class="form-control" name="database" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="nom">nom</label>
                                <input type="text" class="form-control" name="nom" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="nom">url</label>
                                <input type="text" class="form-control" name="url" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="nom">logo</label>
                                <input type="text" class="form-control" name="logo" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="nom">slug</label>
                                <input type="text" class="form-control" name="slug" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="nom">prefix</label>
                                <input type="text" class="form-control" id="prefix" placeholder="">
                            </div>

                            <button class="btn btn-info btn-sm" type="submit">OK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop