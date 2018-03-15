@extends('layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <h1>Admin</h1>
                        <form action="{{ url('/') }}" method="POST" class="mb-4">{!! csrf_field() !!}
                            <input name="verify" value="1" type="hidden">
                            <button class="btn btn-info btn-sm" type="submit">Vérifier IP</button>
                        </form>
                        <ul class="list-group">
                        @if(!empty($results))
                            @foreach($results as $name => $result)
                                @if(isset($result['ip']))
                                    <li class="list-group-item">{{ $name }} - {{ $result['ip'] }} <strong class="text-success">{{ $result['status'] }}</strong></li>
                                @else
                                    <li class="list-group-item">{{ $name }} - {{ $result['addr'] }}
                                        @if(isset($result['sources']) && !empty($result['sources']))
                                            @foreach($result['sources'] as $source)
                                                <br/><strong class="text-danger">{{ $source }}</strong>
                                            @endforeach
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop