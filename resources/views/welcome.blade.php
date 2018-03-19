@extends('layouts.master')
@section('content')

        <div class="container">
            <h1>Admin</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">

                            <h2>IP reputation</h2>
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
                        <div class="col">
                            <h2>Uptime Robot</h2>
                            @if(isset($logs))
                                @foreach($logs as $name => $row)
                                    <p><strong>{{ $name }}</strong></p>

                                    <ul class="list-group">
                                        @foreach($row as $log)
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col">
                                                        @if($log['type'] == 1)
                                                            <span class="badge badge-warning">{{ $log['reason']['detail'] }}</span>
                                                        @else
                                                            <span class="badge badge-success">{{ $log['reason']['detail'] }}</span>
                                                        @endif

                                                    </div>
                                                    <div class="col text-center">
                                                        {{ $log['type'] == 1 ? secondToMinutes($log['duration']) : secondToHour($log['duration'])}}
                                                    </div>
                                                    <div class="col text-right">
                                                        {{ \Carbon\Carbon::createFromTimestamp($log['datetime'])->toDateString() }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                @endforeach
                            @endif
                        </div>
                    </div>

            </div>
        </div>
    </div>

@stop