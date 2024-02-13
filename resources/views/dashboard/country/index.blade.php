@extends('adminlte::page')
@section('title','Countries')
@section('content_header')
<h1>Countries</h1>

@stop
@section('content')
<div class="row">

    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.country.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">All Country Info</h3>
            </div>


            <div class="inqueries margin-bottom10">
                    {{ $countries->links() }}
                </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>phone Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                        <tr>
                            <td>{{$country->id}}</td>
                            <td>{{$country->name}}</td>
                            <td>{{$country->code}}</td>
                            <td>
                                {{$country->phone_code}}
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <img src="{{$country->image_url}}" style="max-height: 80px;max-width: 150px;">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                            href="{{ route('dashboard.country.edit',$country->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.country.destroy',$country->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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
