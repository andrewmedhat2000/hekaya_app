@extends('adminlte::page')
@section('title','Cities')
@section('content_header')
<h1>Cities</h1>

@stop
@section('content')
<div class="row">

    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.city.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">All City Info</h3>
            </div>


            <div class="inqueries margin-bottom10">
                    {{ $cities->links() }}
                </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cities as $city)
                        <tr>
                            <td>{{$city->id}}</td>
                            <td>{{$city->name}}</td>
                            <td>{{isset($city->country)?$city->country->name:''}}</td>
                            
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                            href="{{ route('dashboard.city.edit',$city->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.city.destroy',$city->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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