@extends('adminlte::page')
@section('title','Admin')
@section('content_header')
<h1>Admins</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">



        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Admin Info</h3>
            </div>

            
            <div class="inqueries margin-bottom10">
                    {{ $users->links() }}
                </div>
            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>user_name</th>
                            <th>Email</th>
                            <th>phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>
                                <a href="{{ route('dashboard.admin.show',[$user->id,'admin_id'=>$user->id])}}">
                                    {{$user->user_name}}
                                </a>
                            </td>

                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                {{$user->phone}}
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;" href="{{ route('dashboard.admin.edit',[$user->id,'user_id'=>$user->id]) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.admin.destroy',$user->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                        <button type="submit" style="width: 74.5px;" class="btn btn-block btn-danger ">
                                            Delete
                                        </button>
                                        {{ Form::close() }}
                                    </div>
                                </div>`
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