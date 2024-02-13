@extends('adminlte::page')
@section('title', 'Lucky Rounds')

@section('content_header')
    <h1>Lucky Rounds</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <a href="{{ route('dashboard.lucky-round.create') }}" class="btn btn-block btn-primary">
                    Add New
                </a>
                <div class="inqueries margin-bottom10">
                    {{ $luckyRounds->links() }}
                </div>
                <div class="box-body">
                    <table id="tbl-list-rounds" data-server="false" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rounds Number</th>
                                <th>Value of Rounds</th>
                                <th>Minimum Win</th>
                                <th>Maximum Win</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($luckyRounds as $key => $luckyRound)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $luckyRound->round_number }}</td>
                                    <td>{{ $luckyRound->round_value }}</td>
                                    <td>{{ $luckyRound->minimum_win }}</td>
                                    <td>{{ $luckyRound->maximum_win }}</td>
                                    <td class="d-flex flex-row">
                                        <a href="{{ route('dashboard.lucky-round.edit',[$luckyRound->id]) }}" class="btn btn-primary">Edit</a>
                                        {{ Form::open(['route' => ['dashboard.lucky-round.destroy',$luckyRound->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                        <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                            Delete
                                        </button>
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @empty
                                <h3>No rounds created yet</h3>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
