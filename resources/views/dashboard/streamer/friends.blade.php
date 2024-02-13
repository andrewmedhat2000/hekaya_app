@extends('adminlte::page')
@section('title','User')
@section('content_header')
<h1>User {{$resource->user_name}} Friends</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All User Friends Info</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friends as $friend)
                        <tr>
                            <td>{{$friend->id}}</td>

                            <td>
                                <a href="{{ route('dashboard.user.show',$friend->id)}}">
                                    {{$friend->user_name}}
                                </a>
                            </td>

                            <td>{{$friend->name}}</td>
                            <td>{{$friend->email}}</td>
                            <td>
                                {{$friend->phone}}
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