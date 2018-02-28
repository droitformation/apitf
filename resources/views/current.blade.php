@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('current') }}" method="POST">{!! csrf_field() !!}
                            <label for="newdate1">Rechercher période</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="terms" name="terms" placeholder="Mots clés">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="range1" name="period[0]" placeholder="Début">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="range2" name="period[1]" placeholder="Fin">
                            </div>
                            <div class="form-group">
                                <select class="custom-select" name="categorie_id">
                                    <option value="">Choix catégorie</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="published" value="1" id="published">
                                <label class="form-check-label" for="published">Publié</label>
                            </div><br/>
                            <button type="submit" class="btn btn-info">OK</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <h3>Résultats</h3>
                        @if(!empty($search))
                            <p class="lead">
                                {!! !empty($search['terms']) ? '<strong>Recherche: </strong>'.$search['terms'] : '' !!}
                                {{ !empty(array_filter($search['period'])) ? ', de '.$search['period'][0].' à '.$search['period'][1] : '' }}
                            </p>
                            <p class="lead">
                               {!! (isset($search['categorie_id']) && $categories->contains('id',$search['categorie_id'])) ?
                                   '<strong>Catégorie :</strong> '.$categories->find($search['categorie_id'])->name : '' !!}
                            </p>
                        @endif
                        @if(isset($results) && !$results->isEmpty())
                            <div class="list-dates">
                                @foreach($results as $result)
                                    <p>{{ \Carbon\Carbon::parse($result->publication_at)->format('Y-m-d') }} | {{ $result->numero }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @if(!empty($tables))
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <h3>Connexion MySql Tables</h3>
                        <p>
                        @foreach($tables as $table)
                            {{ $table }},
                        @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    </div>
@stop