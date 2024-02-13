@extends('adminlte::page')
@section('title','Reports')
@section('content_header')
<h1>Users</h1>

@stop
@section('content')
<div class="row">


    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title"> Reports </h3>
            </div>

            <div class="inqueries margin-bottom10">
                    {{ $reports->links() }}
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
                            <th> Reporter  </th>
                            <th> Reported </th>
                            <th> Message </th>
                            <th> Created time </th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr>
                            <td>{{$report->id}}</td>
                            <td>{{$report->reporter ? $report->reporter->name : ' '}}</td>
                            <td>{{$report->reported ? $report->reported->name : ' '}}</td>
                            <td>{{$report->message}}</td>
                            <td>{{$report->created_at->format('Y-m-d h:i a')}}</td>
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
