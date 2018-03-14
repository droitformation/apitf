@extends('layouts.master')
@section('content')

    <div class="container">

        <div class="row">
            <div class="col-lg">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3>Missing</h3>
                        @if(!$liste->isEmpty())
                            @foreach($liste as $date)
                                <div class="row">
                                    <div class="col-sm"><p>{{ $date }}</p></div>
                                    <form action="{{ url('praticien/date/update') }}" method="POST" class="col-sm text-right">{!! csrf_field() !!}
                                        <input name="date" value="{{ $date }}" type="hidden">
                                        <button class="btn btn-info btn-sm">Update</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3>Exist</h3>
                        @if(!$exist->isEmpty())
                            @foreach($exist as $date => $count)
                                <div class="row">
                                    <div class="col-sm"><p><span class="badge badge-info">{{ $count }}</span></p></div>
                                    <div class="col-sm"><p>{{ $date }}</p></div>
                                    <form action="{{ url('praticien/date/delete') }}" method="POST" class="col-sm text-right">{!! csrf_field() !!}
                                        <input name="date" value="{{ $date }}" type="hidden">
                                        <button class="btn btn-danger btn-sm">X</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('praticien/date/update') }}" method="POST">{!! csrf_field() !!}
                            <div class="form-group">
                                <label for="newdate">Insérer date</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control datepicker" id="newdate" name="date" placeholder="">
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-primary">Envoyer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('praticien/date/update') }}" method="POST">{!! csrf_field() !!}
                            <label for="newdate1">Insérer période</label>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="range1" name="range[0]" placeholder="">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="range2" name="range[1]" placeholder="">
                            </div>
                            <button type="submit" class="btn btn-info">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


            @if(!$total->isEmpty())
                @foreach($total as $year => $dates)

                    <div class="card">
                        <div class="card-body">
                            <h3>Courant {{ $year }}</h3>

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

                        </div>
                    </div>

                @endforeach
            @endif



    </div>
@stop