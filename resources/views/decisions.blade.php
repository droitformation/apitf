@extends('layouts.master')
@section('content')

    <div class="container">
        <p><a href="{{ url('archive') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i>Retour</a></p>
        <div class="row">

            <div class="col">
                <div class="card">
                    <div class="card-body list-dates">
                        <h5><strong>{{ $date }}</strong></h5>
                        @if(!$decisions->isEmpty())
                            @foreach($decisions as $decision)
                                <p><a href="{{ url('decisions/'.$date.'/'.$decision->id) }}">{{ $decision->numero }}</a></p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        @if($arret)
                            <p>{{ $arret->numero }} | {{ $arret->publication_at->format('Y-m-d') }} | {{ $arret->lang }}</p>
                            <p>Date de décision: {{ $arret->decision_at->format('Y-m-d') }}</p>
                            <div>{!! $arret->texte !!}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop