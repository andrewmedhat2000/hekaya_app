@extends('adminlte::page')
@section('title','Streamer Admins')
@section('content_header')
<h1>Streamer Admins</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Streamer Admins Info</h3>
            </div>

            <div class="inqueries margin-bottom10">
                    {{ $users->links() }}
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
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>

                            <td>
                                <a href="{{ route('dashboard.streameradminreporting.show',$user->id)}}">
                                    {{$user->user_name}}
                                </a>
                            </td>

                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                {{$user->phone}}
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