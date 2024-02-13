@extends('adminlte::page')
@section('title','Channel')
@section('content_header')
<h1>Channel Messages</h1>


@stop
@section('content')
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
                                {{ count($messages) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-bottom10">
        {{ $messages->links() }}
    </div>

<div class="row">
    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Channel Messages</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Message</th>
                            <th>Attachments</th>
                            <th>Media</th>
                            <th>Date Created</th>

                            {{--  <th>Action</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                        <tr>
                            <td>{{$message['from']}}</td>
                            <td>{{$message['message']}}</td>
                            <td style="max-width: 450px; word-wrap: break-word;">
                            @if(!$message['media'])
                                @foreach ($message['attributes'] as $key => $attribute)

                                    <img src="{{preg_replace('/\\\\/', '', $attribute)}}" style="max-height: 150px;max-width: 150px;">
                                    <br></br>
                                    <a href="{{preg_replace('/\\\\/', '', $attribute)}}" style="max-width: 450px; word-wrap: break-word;">
                                    {{preg_replace('/\\\\/', '', $attribute)}}
                                    </a>
                                <br/>
                                @endforeach
                            @endif
                            </td>

                            <td>  
                            <a href="{{ route('dashboard.channels.media', ['sid' => optional($message['media'])->sid]) }}">
                                @if($message['media'])
                                @foreach ($message['attributes'] as $key => $attribute)
                                @if($key == 'voiceDuration')
                                    {{'voiceDuration:'.$attribute}}
                                @endif

                                <br/>
                                @endforeach  
                                    
                                @endif
                                </a>
                            </td>
                            <td>{{$message['dateCreated']}}</td>

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