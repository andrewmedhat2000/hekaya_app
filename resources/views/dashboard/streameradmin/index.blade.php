@extends('adminlte::page')
@section('title','User')
@section('content_header')
<h1>Users</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.streameradmin.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>




        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All User Info</h3>
            </div>


            <div class="inqueries margin-bottom10">
                    {{ $users->links() }}
                </div>
            @if (session('alert'))
            <div class="alert alert-warning">
                {{ session('alert') }}
            </div>
            @endif
            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>phone</th>
                            <th>Profile</th>
                            <th>User Banned</th>
                            <th>Device Banned</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>

                            <td>
                                <a href="{{ route('dashboard.streameradmin.streamers',['admin_id' => $user->id])}}">
                                    {{$user->user_name}}
                                </a>
                            </td>

                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                {{$user->phone}}
                            </td>
                            <td>
                                <img src="{{$user->profile_pic_url}}" style="max-height: 150px;max-width: 150px;">

                            </td>
                            <td>
                                @if (!$user->active)
                                @if($user->active_end_time->diffInYears(Carbon\Carbon::now()) < 100) Will End in: {{$user->active_end_time->diffInHours(Carbon\Carbon::now())}} Hours @else User is banned forever @endif @else No @endif </td> <td>
                                    @if ($user->bannedUsers->first())
                                    @if($user->bannedUsers->first()->end_time->diffInYears(Carbon\Carbon::now()) < 100) Will End in: {{$user->bannedUsers->first()->end_time->diffInHours(Carbon\Carbon::now())}} Hours @else User is banned forever @endif @else NO @endif </td> <td>
                                        <div class="col-md-12">
                                            <div class="col-md-4">
                                                <a class="btn btn-block btn-primary " style="width: 74.5px;" href="{{ route('dashboard.streameradmin.edit',$user->id) }}">
                                                    Edit
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                @if($user->active == 1)
                                                {{ Form::open(['route' => ['dashboard.user.statusview'] ,'method' => 'POST']) }}
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                {!! Form::hidden('redirects_to', Request::fullUrl()) !!}
                                                <button type="submit" class="btn btn-block btn-primary " style="width: 100px;">
                                                    Deactivate
                                                </button>
                                                {{ Form::close() }}
                                                @else
                                                {{ Form::open(['route' => ['dashboard.user.activate'] ,'method' => 'POST']) }}
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                {!! Form::hidden('redirects_to', Request::fullUrl()) !!}
                                                <button type="submit" class="btn btn-block btn-primary " style="width: 74.5px;">
                                                    Activate
                                                </button>
                                                {{ Form::close() }}
                                                @endif
                                            </div>


                                            <div class="col-md-4">

                                                @if($user->bannedUsers()->first())
                                                {{ Form::open(['route' => ['dashboard.user.unban'] ,'method' => 'POST']) }}
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                {!! Form::hidden('redirects_to', Request::fullUrl()) !!}
                                                <button type="submit" class="btn btn-block btn-primary " style="width: 74.5px;">
                                                    UnBan
                                                </button>
                                                {{ Form::close() }}
                                                @else
                                                {{ Form::open(['route' => ['dashboard.user.banview'] ,'method' => 'POST']) }}
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                {!! Form::hidden('redirects_to', Request::fullUrl()) !!}
                                                <button type="submit" class="btn btn-block btn-primary " style="width: 74.5px;">
                                                    Ban
                                                </button>
                                                {{ Form::close() }}
                                                @endif
                                            </div>


                                            <div class="col-md-4">

                                                {{ Form::open(['route' => ['dashboard.user.destroy',$user->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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