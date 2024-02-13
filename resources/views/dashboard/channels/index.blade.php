@extends('adminlte::page')
@section('title','Channel')
@section('content_header')
<h1>Channels</h1>

@stop
@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-5 margin-bottom10 margin-top20">
                    <div class="total-customer-col pad5 pad-bottom5 col-md-12">
                        <div class="col-md-9 customer-stat-col-pad">
                            <h5 class="customer-stat-text pad5">Total count</h5>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5 class="customer-stat-num pad5">
                                {{ count($channels) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-bottom10">
        {{ $channels->links() }}
    </div>
    <div class="col-xs-12 col-md-12">


        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Channels Info</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cover</th>
                            <th>Unique Name</th>
                            <th>Friendly Name</th>
                            <th>type</th>
                            <th>Create By</th>
                            <th>Members Count</th>
                            <th>Messages Count</th>
                            <th>url</th>

                            {{--  <th>Action</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($channels as $channel)
                        <tr>
                            <td>
                                <a href="{{ route('dashboard.channels.show', ['sid' => $channel['sid']]) }}">
                                    {{$channel['sid']}}
                                </a>
                            </td>
                            <td>
                                <img src="{{optional($channel['attributes'])->cover}}" class="img-circle" style="max-height: 150px;max-width: 150px;">
                            </td>
                            

                            <td>{{$channel['uniqueName']}}</td>
                            <td>{{$channel['friendlyName']}}</td>
                            <td>{{$channel['type']}}</td>
                            <td>
                                {{$channel['createdBy']}}
                            </td>
                            <td>
                                <a href="{{ route('dashboard.channels.members', ['sid' => $channel['sid']]) }}">
                                    {{$channel['membersCount']}}
                                </a>
                            </td>
                            <td>
                                {{$channel['messagesCount']}}
                            </td>
                            <td>
                                {{$channel['url']}}
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