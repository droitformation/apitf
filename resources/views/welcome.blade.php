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
                                    <form action="{{ url('date/update') }}" method="POST" class="col-sm text-right">{!! csrf_field() !!}
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
                                    <form action="{{ url('date/delete') }}" method="POST" class="col-sm text-right">{!! csrf_field() !!}
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
                <form action="{{ url('date/update') }}" method="POST" class="col-sm text-right">{!! csrf_field() !!}
                    <div class="form-group">
                        <label for="newdate">Insérer date</label>
                        <input type="text" class="form-control datepicker" id="newdate" name="date" placeholder="">
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>

        <div class="row">

            @if(!$total->isEmpty())
                @foreach($total as $year => $dates)
                    <div class="col-md">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h3>Archives {{ $year }}</h3>
                                @foreach($dates as $date => $count)
                                    <div class="row">
                                        <div class="col-sm"><p>{{ $date }}</p></div>
                                        <div class="col-sm text-right"><p><strong>{{ $count }}</strong></p></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </div>
@stop