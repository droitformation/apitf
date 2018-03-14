@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <h1>Admin</h1>
                        <form action="{{ url('/') }}" method="POST" class="col-sm">{!! csrf_field() !!}
                            <input name="verify" value="1" type="hidden">
                            <button class="btn btn-info btn-sm" type="submit">Vérifier IP</button>
                        </form>
                        @if(!empty($results))
                            <?php
                            echo '<pre>';
                            print_r($results);
                            echo '</pre>';
                            ?>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop