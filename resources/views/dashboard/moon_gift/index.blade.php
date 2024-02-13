@extends('adminlte::page')
@section('title', 'Hekayah Gifts')

@section('content_header')
    <h1>Hekayah Gifts</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <a href="{{ route('dashboard.moon-gift.create') }}" class="btn btn-block btn-primary">
                    Add New
                </a>
                <div class="inqueries margin-bottom10">
                    {{ $moonGifts->links() }}
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
                            @forelse ($moonGifts as $key => $moonGift)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $moonGift->round_number }}</td>
                                <td>{{ $moonGift->round_value }}</td>
                                <td>{{ $moonGift->minimum_win }}</td>
                                <td>{{ $moonGift->maximum_win }}</td>
                                <td class="d-flex flex-row">
                                    <a href="{{ route('dashboard.moon-gift.edit',[$moonGift->id]) }}" class="btn btn-primary">Edit</a>
                                    {{ Form::open(['route' => ['dashboard.moon-gift.destroy',$moonGift->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                    <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                        Delete
                                    </button>
                                    {{ Form::close() }}
                                </td>
                            </tr>
                            @empty
                                <h3>No Hekayah Gifts created yet</h3>                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
