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
                                <div class="row">
                                    <div class="col-sm">
                                        <p><a href="{{ url('decision/'.$date.'/'.$decision->id) }}">{{ $decision->numero }}</a></p>
                                    </div>
                                    <div class="col-sm text-right">
                                        <form action="{{ url('decision/update') }}" method="POST" class="">{!! csrf_field() !!}
                                            <input name="id" value="{{ $decision->id }}" type="hidden">
                                            <input name="numero" value="{{ $decision->numero }}" type="hidden">
                                            <input name="publication_at" value="{{ $decision->publication_at->format('Y-m-d') }}" type="hidden">
                                            <input name="decision_at" value="{{ $decision->decision_at->format('Y-m-d') }}" type="hidden">
                                            <input name="categorie" value="{{ $decision->categorie_id }}" type="hidden">
                                            <button class="btn btn-primary btn-sm btn-small"><i class="fa fa-sync"></i></button>
                                        </form>
                                    </div>
                                </div>
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