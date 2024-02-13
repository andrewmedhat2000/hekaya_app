@extends('adminlte::page')
@section('title', 'Lucky Gifts')

@section('content_header')
    <h1>Lucky Gifts</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <a href="{{ route('dashboard.lucky-gift.create') }}" class="btn btn-block btn-primary">
                    Add New
                </a>
                <div class="inqueries margin-bottom10">
                    {{ $luckyGifts->links() }}
                </div>
                <div class="box-body">
                    <table id="tbl-list-rounds" data-server="false" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>GIF</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($luckyGifts as $key => $luckyGift)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $luckyGift->name }}</td>
                                    <td>{{ $luckyGift->price }}</td>
                                    <td>
                                        <img style="max-height: 150px;max-width: 150px;" src="{{ $luckyGift->image_url }}" alt="">
                                    </td>
                                    <td>
                                        <img style="max-height: 150px;max-width: 150px;" src="{{ $luckyGift->gif_url }}" alt="">
                                    </td>
                                    <td class="d-flex flex-row">
                                        <a href="{{ route('dashboard.lucky-gift.edit',[$luckyGift->id]) }}" class="btn btn-primary">Edit</a>
                                        {{ Form::open(['route' => ['dashboard.lucky-gift.destroy',$luckyGift->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                        <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                            Delete
                                        </button>
                                        {{ Form::close() }}
                                    </td>
                                </tr> 
                            @empty
                                <h3>No Lucky Gifts created yet</h3>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
