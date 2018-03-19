@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2>Transfert to archive</h2>
                <form action="{{ url('praticien/transfert') }}" method="POST" class="col-sm text-right transfert">{!! csrf_field() !!}
                    <?php $currentYear = date('Y') - 1; ?>
                    <div class="input-group">
                        <select class="custom-select" name="year">
                            @foreach(range(2012, $currentYear) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-info btn-sm" type="submit">Transfert</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        @if(!$total->isEmpty())
            @foreach($total as $year => $dates)

                    <div class="card">
                        <div class="card-body">
                            <h3>Archives {{ $year }}</h3>

                            <?php $years = $dates->chunk(6); ?>
                            @foreach($years as $dates)
                            <div class="row">
                                @foreach($dates as $month => $days)
                                    <div class="col-md">
                                        <?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>
                                        <p><strong>{{ strftime("%B",  mktime(0, 0, 0, $month, 10)) }}</strong></p>
                                        @foreach($days as $day)
                                            <div class="row list-dates">
                                                <div class="col-sm">
                                                    <p><a href="{{ url('praticien/archives/'.$year.'/'.$day['date']) }}">{{ $day['date'] }}</a></p>
                                                </div>
                                                <div class="col-sm text-right"><p><strong>{{ $day['count'] }}</strong></p></div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>

            @endforeach
        @endif


        <div class="row">
            <div class="col-md">
                @if(!empty($tables))
                    <div class="card">
                        <div class="card-body">
                            <h3>Connexion Sqlite Tables</h3>
                            <p>
                            @foreach($tables as $table)
                                {{ $table->name }},
                            @endforeach
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

@stop