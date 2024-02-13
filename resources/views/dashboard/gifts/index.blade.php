@extends('adminlte::page')
@section('title','Gifts')
@section('content_header')
<h1>Gifts</h1>

@stop
@section('content')
<div class="row">


    <div class="col-xs-12 col-md-12">
        <a href="{{ route('dashboard.gifts.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>
        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Gifts Info</h3>
            </div>

            <div class="inqueries margin-bottom10">
                    {{ $gifts->links() }}
                </div>
            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Diamonds</th>
                            <th>Image</th>
                            <th>GIF</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gifts as $gift)
                        <tr>
                            <td>{{$gift->id}}</td>
                            <td>{{$gift->name}}</td>
                            <td>
                                {{$gift->price}}
                            </td>
                            <td>

                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <img src="{{$gift->image_url}}" style="max-height: 150px;max-width: 150px;">
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if(!empty($gift->gif_url))
                                    <img src="{{$gift->gif_url}}" style="max-height: 150px;max-width: 150px;">
                                @endif
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                            href="{{ route('dashboard.gifts.edit',$gift->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.gifts.destroy',$gift->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                        <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                            Delete
                                        </button>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

@stop

@section('js')
{{--<script>--}}
{{--$(function () {--}}
{{--$('#tbl-list-users').DataTable()--}}
{{--})--}}
{{--</script>--}}
@stop
