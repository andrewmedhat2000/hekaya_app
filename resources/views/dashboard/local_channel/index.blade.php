@extends('adminlte::page')
@section('title','Channels')
@section('content_header')
<h1>Channels</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.local.channel.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>




        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Channels Info</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($channels as $channel)
                        <tr>
                            <td>{{$channel->id}}</td>
                            <td>
                                <a href="{{ route('dashboard.local.channel.get',['channel_id' => $channel->id])}}">
                                    {{$channel->name}}
                                </a>
                                </td>
                            <td>
                                @if($channel->status == 1)
                                New Messages
                                @else
                                Read
                                @endif
                            </td>
                            <td>
                                <div class="col-md-12">
                            
                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.local.channel.destroy',$channel->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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