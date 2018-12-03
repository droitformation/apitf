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
                                <input type="text" class="form-control" name="database" value="{{ old('database') }}">
                            </div>

                            <div class="form-group">
                                <label for="nom">nom</label>
                                <input type="text" class="form-control" name="nom" value="{{ old('nom') }}">
                            </div>

                            <div class="form-group">
                                <label for="nom">url</label>
                                <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                            </div>

                            <div class="form-group">
                                <label for="nom">logo</label>
                                <input type="text" class="form-control" name="logo" value="{{ old('logo') }}">
                            </div>

                            <div class="form-group">
                                <label for="nom">slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}">
                            </div>

                            <div class="form-group">
                                <label for="nom">prefix</label>
                                <input type="text" class="form-control" name="prefix" value="{{ old('prefix') }}">
                            </div>

                            <button class="btn btn-info btn-sm" type="submit">OK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop