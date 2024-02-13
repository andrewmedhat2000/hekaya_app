@extends('adminlte::page')
@section('title','Terms')
@section('content_header')
<h1>Terms</h1>

@stop
@section('content')
<div class="row">

    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.terms.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">All Term Info</h3>
            </div>


            <div class="inqueries margin-bottom10">
                    {{ $terms->links() }}
                </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $term)
                        <tr>
                            <td>{{$term->id}}</td>
                            <td>{{$term->name}}</td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;"
                                            href="{{ route('dashboard.terms.edit',$term->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.terms.destroy',$term->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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