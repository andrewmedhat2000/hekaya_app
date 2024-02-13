@extends('adminlte::page')
@section('title', 'Games Slider')

@section('content_header')
    <h1>Games Slider</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <a href="{{ route('dashboard.games-slider.create') }}" class="btn btn-block btn-primary">
                    Add New
                </a>
                <div class="inqueries margin-bottom10">
                    {{ $games->links() }}
                </div>
                <div class="box-body">
                    <table id="tbl-list-rounds" data-server="false" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($games as $key => $game)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $game->title ?? '-' }}</td>
                                    <td><a href="{{ $game->link }}" target="_blank">{{ $game->title ?? 'Game Link' }}</a></td>
                                    <td><img src="{{ $game->image }}" class="img-fluid img-thumbnail" width="20%" alt=""></td>
                                    <td class="d-flex flex-row">
                                        <a href="{{ route('dashboard.games-slider.edit',[$game->id]) }}" class="btn btn-primary">Edit</a>
                                        {{ Form::open(['route' => ['dashboard.games-slider.destroy',$game->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                        <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                            Delete
                                        </button>
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @empty
                                <h4>No games added it yet</h4>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
