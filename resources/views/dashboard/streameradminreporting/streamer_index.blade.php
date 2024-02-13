@extends('adminlte::page')
@section('title','Streamers')
@section('content_header')
<h1>Streamers</h1>

@stop
@section('content')
<div class="row">

    <div class="col-xs-12 col-md-12">
        <a href="{{ route('dashboard.streameradminreporting.export_all_mapping', request()->only('month', 'admin_id')) }}" class="btn btn-dark btn-block">
            Download Excel
        </a>
        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Streamer Info</h3>
            </div>

            {{ Form::open(['route' => 'dashboard.streameradminreporting.index', 'method' => 'GET', 'id' => 'filters']) }}
            <div class="row">
                {{ Form::select('month', $months, request()->input('month'), ['placeholder' => 'Month','class' => 'filter form-control select2 col-lg-2 col-md-5'])}}
                {{ Form::select('admin_id', $admins, request()->input('admin_id'), ['placeholder' => 'Agent','class' => 'filter form-control select2 col-lg-2 col-md-5'])}}
            </div>
            {{ Form::close()}}

            {{-- <div class="inqueries margin-bottom10">
                    {{ $users->links() }}
                </div> --}}

            <div class="box-body table-responsive">

                @include('dashboard.streameradminreporting.partials.streamers_table')

            </div>

        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(function () {
        $('#tbl-list-users').DataTable()
    })
    $('.filter').change(function() {
        $('#filters').submit();
    });
</script>
@stop
