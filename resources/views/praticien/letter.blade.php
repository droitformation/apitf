@extends('layouts.master')
@section('content')

    <div class="container">
        <h2>Newsletter</h2>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('praticien/letter') }}" method="POST">{!! csrf_field() !!}
                            <label for="newdate1">Choisir la date dans une semaine</label>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="date" name="date" placeholder="">
                            </div>
                            <button type="submit" class="btn btn-info">Envoyer</button>
                        </form>
                    </div>
                    @if(isset($html))
                        {!! $html !!}
                    @endif
                </div>
            </div>
        </div>

    </div>
@stop