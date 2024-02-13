@extends('adminlte::page')
@section('title','Messages')
@section('content_header')
<h1>User {{$channel->name}} Messages</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All {{$channel->name}} Messages Info</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>From</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Time</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                        <tr>
                            <td>{{$message->id}}</td>

                            <td>
                                @if($message->user()->first())
                                    @if( $message->user()->first()->hasrole('super_admin'))
                                    Super Admin
                                    @elseif(Auth::user()->id == $message->user()->first()->id)
                                    YOU
                                    @else
                                    <a href="{{ route('dashboard.user.show',$message->user()->first()->id)}}">
                                        {{$message->user()->first()->user_name}}
                                    </a>
                                    @endif
                                @endif

                            </td>
                            <td>{{$message->message}}</td>
                            <td>
                                {{$message->created_at->ToDateString()}}
                            </td>
                            <td>
                                {{$message->created_at->ToTimeString()}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>



            <div class="col-md-12">

                {{ Form::open(['route' => 'dashboard.local.channel.send','files'=>'true']) }}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reply To User</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">


                            <div class="form-group margin-bottom20 col-md-12">
                                <label class="control-label" for="message">
                                    <span class="text-danger">*</span>
                                    Message
                                </label>
                                {{ Form::textarea('message',old('message'),['id'=>'message','required' => 'required','class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('message') }}</p>
                            </div>
                            {{ Form::hidden('user_id', Auth::user()->id) }}
                            {{ Form::hidden('channel_id', $channel->id) }}




                        </div>

                        <br>
                        <div class="row">

                            <div class="col-md-2 col-xs-4">
                                <button type="submit" class="btn btn-block btn-primary">
                                    Send
                                </button>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <button type="reset" class="btn btn-block btn-default">
                                    Reset
                                </button>
                            </div>

                            {{ Form::close() }}
                        </div>


                    </div>
                </div>
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