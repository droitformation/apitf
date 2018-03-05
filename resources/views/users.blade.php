@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <table class="table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Cadence d'envoi</th>
                                    <th>Abo actif</th>
                                    <th>Abonnements</th>
                                </tr>
                            </thead>
                            @if(!$users->isEmpty())
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->cadence }}</td>
                                            <td>{{ $user->active_until->format('Y-m-d') }}</td>
                                            <td>
                                                @if(!$user->abonnements->isEmpty())
                                                    @foreach($user->abonnements as $categorie_id => $abo)
                                                        <div class="cart-inner">
                                                            <p><strong>Catégorie:</strong> {{ isset($categories[$categorie_id]) ? $categories[$categorie_id] : $categorie_id  }}</p>
                                                            <p><strong>Mots clés:</strong> {{ $abo['keywords']->flatten()->implode(',') }}</p>
                                                            <p>{!! $abo['published'] ? '<strong class="text-danger">Publiés</strong>' : '' !!}</p>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">

                <div class="card">
                    <div class="card-body">

                        <h3>Tester abo et date</h3>
                        <form action="{{ url('abos') }}" method="POST" class="mb-2">{!! csrf_field() !!}
                            <div class="form-row">
                                <div class="col">
                                    @if(!$users->isEmpty())
                                        <select class="custom-select" name="user_id">
                                            @foreach($users as $user)
                                                <option {{ $user_id == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control datepicker" name="date" placeholder="date">
                                </div>
                                <div class="col">
                                    <button class="btn btn-info btn-sm" type="submit">Tester</button>
                                </div>
                            </div>
                        </form>

                        {!! $alert or '' !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop