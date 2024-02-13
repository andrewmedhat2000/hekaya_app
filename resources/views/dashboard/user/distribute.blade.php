@extends('adminlte::page')
@section('title','User')
@section('content_header')
{{-- <h1>Users</h1> --}}

@stop
@section('content')
<div class="row">

    <div class="col-xs-12 col-md-12">

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">Balance: {{auth()->user()->diamonds}}</h3>
            </div>

            @if (session('alert'))
            <div class="alert alert-warning">
                {{ session('alert') }}
            </div>
            @endif

            <div class="box-body table-responsive">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Diamonds sent</th>
                            <th></th>
                        </tr>
                    </thead>

                </table>
            </div>

            {{Form::open(['route' => 'send', 'id' => 'form'])}}
                {{Form::hidden('user_id', null, ['id' => 'user_id'])}}
                {{Form::hidden('diamonds', null, ['id' => 'diamonds'])}}
            {{Form::close()}}

        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(function () {
        $('#tbl-list-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('distribute') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'diamonds_received', name: 'diamonds_received', searchable: false},
                {data: 'send', name: 'send', sortable: false, searchable: false}
            ],
        });
    });
    $(document).on('click', '.send', function () {
        let diamonds = $(this).siblings('input').first().val();
        if(diamonds) {
            $('#diamonds').val(diamonds);
            $('#user_id').val($(this).attr('user_id'));
            $('#form').submit();
        }
    });
</script>
@stop
