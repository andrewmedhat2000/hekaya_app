@extends('adminlte::page')
@section('title','Channel')
@section('content_header')
<h1>Channel members</h1>

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
                                {{ count($members) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-bottom10">
        {{ $members->links() }}
    </div>
    <div class="col-xs-12 col-md-12">


        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Channel members</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Identity</th>

                            {{--  <th>Action</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                        <tr>
                            <td>{{$member['identity']}}</td>
                            
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