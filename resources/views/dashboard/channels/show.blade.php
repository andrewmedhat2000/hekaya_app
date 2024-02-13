@extends('adminlte::page')
@section('title','Channel')
@section('content_header')
<h1>Channel ({{$channel['friendlyName']}}) Messages</h1>


<div class="col-md-12">
    <div class="row">


        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D1
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                    @if($channel['_d0'])
                        @if ($channel['_d0'] == '-')
                        Locked
                        @else
                        {{$channel['_d0']}}
                        @endif
                    @endif
                </label>
            </div>
        </div>
        
        
      
        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D2
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                    @if($channel['_d1'])
                        @if ($channel['_d1'] == '-')
                        Locked
                        @else
                        {{$channel['_d1']}}
                        @endif
                    @endif
                </label>
            </div>
        </div>


        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D3
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                @if($channel['_d2'])
                    @if ($channel['_d2'] == '-')
                    Locked
                    @else
                    {{$channel['_d2']}}
                    @endif
                @endif
                </label>
            </div>
        </div>



        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D4
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                    @if($channel['_d3'])
                        @if ($channel['_d3'] == '-')
                        Locked
                        @else
                        {{$channel['_d3']}}
                        @endif
                    @endif
                </label>
            </div>
        </div>



        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D5
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                    @if($channel['_d4'])
                        @if ($channel['_d4'] == '-')
                        Locked
                        @else
                        {{$channel['_d4']}}
                        @endif
                    @endif
                </label>
            </div>
        </div>



        <div class="form-group margin-bottom20 col-md-4">
            <label class="control-label" for="user">
                <span class="text-danger">*</span>
                D6
            </label>
            <div class="form-group margin-bottom20 col-md-6">
                <label class="control-label">
                    @if($channel['_d5'])
                        @if ($channel['_d5'] == '-')
                        Locked
                        @else
                        {{$channel['_d5']}}
                        @endif
                    @endif
                </label>
            </div>
        </div>
        
    </div>
</div>

@stop
@section('content')
<div class="row">
    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">
                    <a href="{{ route('dashboard.channels.messages', ['sid' => $channel['sid']]) }}">
                        Channel Messages
                    </a>
                </h3>
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