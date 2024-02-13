@extends('adminlte::page')
@section('title','GuessGame Shapes')
@section('content_header')
<h1>GuessGame Shapes</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.guessgame.shapes.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>




        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Shapes Info</h3>
            </div>

            <div class="inqueries margin-bottom10">
                    {{ $shapes->links() }}
                </div>
            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>name</th>
                            <th>Image</th>
                            <th>GIF</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shapes as $shape)
                        <tr>
                            <td>{{$shape->id}}</td>
                            <td>{{$shape->name}}</td>
                            <td>

                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <img src="{{$shape->image_url}}" style="max-height: 150px;max-width: 150px;">
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if(!empty($shape->gif_url))
                                    <img src="{{$shape->gif_url}}" style="max-height: 150px;max-width: 150px;">
                                @endif
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                            href="{{ route('dashboard.guessgame.shapes.edit',$shape->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.guessgame.shapes.destroy',$shape->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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
