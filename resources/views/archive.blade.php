@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md">
                <h2>Connexion Sqlite</h2>
                @if(!empty($tables))
                    <div class="card">
                        <div class="card-body">
                            <h3>Tables</h3>
                            @foreach($tables as $table)
                                <p>{{ $table->name }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md">
                <h2>Transfert to archive</h2>
                <form action="{{ url('archive/transfert') }}" method="POST" class="col-sm text-right transfert">{!! csrf_field() !!}
                    <?php $currentYear = date('Y') - 1; ?>
                    <div class="row">
                        <div class="col-md">
                            <select class="form-control" name="year">
                                @foreach(range(2012, $currentYear) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <button class="btn btn-info btn-sm">Transfert</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@stop