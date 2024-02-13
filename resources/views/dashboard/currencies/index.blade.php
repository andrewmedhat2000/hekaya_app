@extends('adminlte::page')
@section('title','Currencies')
@section('content_header')
<h1>Currencies</h1>

@stop
@section('content')
<div class="row">

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
                                    {{ count($currencies) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row margin-bottom10">
            {{ $currencies->links() }}
        </div>


        <div class="col-xs-12 col-md-12">

            <a href="{{ route('dashboard.currencies.create') }}" class="btn btn-block btn-primary">
                Add new
            </a>




            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">All Currencies Info</h3>
                </div>

                <div class="box-body">

                    <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>name</th>
                                <th>Code</th>
                                <th>Symbol</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currencies as $currency)
                            <tr>
                                <td>{{$currency->id}}</td>
                                <td>{{$currency->name}}</td>
                                <td>
                                    {{$currency->code}}
                                </td>
                                <td>
                                    {{$currency->symbol}}
                                </td>
                                <td>
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                                href="{{ route('dashboard.currencies.edit',$currency->id) }}">
                                                Edit
                                            </a>
                                        </div>

                                        <div class="col-md-4">

                                            {{ Form::open(['route' => ['dashboard.currencies.destroy',$currency->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
                                            <button type="submit" style="width: 74.5px;"
                                                class="btn btn-block btn-danger ">
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