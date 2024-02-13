@extends('adminlte::page')
@section('title','Targets')
@section('content_header')
<h1>Targets</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.targets.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>




        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All Targets Info</h3>
            </div>


            {{-- <div class="inqueries margin-bottom10">
                    {{ $targets->links() }}
                </div> --}}
            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <!-- <th>ID</th>
                            <th>Title</th>
                            <th>Gold Beans Requirement</th>
                            <th>Basic Salary</th>
                            <th>Awards For Agency</th>
                            <th>Currency</th>
                            <th>hours</th>
                            <th>Hourly Wage</th>
                            <th>Action</th> -->

                            <th>ID</th>
                            <th>ليفل التارجت</th>
                            <th> التارجيت المطلوب بالماسات </th>
                            <th>المكافأة الاساسية</th>
                            <th>مكافأة المضيف</th>
                            <th>العملة</th>
                            <th>عدد الايام المطلوبة في الشهر</th>
                            <th>عدد الساعات المطلوبة في الشهر</th>
                            <th>المكافأة على الساعات</th>
                            <th>أوامر</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($targets as $target)
                        <tr>
                            <td>{{$target->id}}</td>
                            <td>{{$target->title}}</td>
                            <td>
                                {{$target->quantity}}
                            </td>
                            <td>
                                {{$target->price}}
                            </td>
                            <td>
                                {{$target->admin_price}}
                            </td>
                            <td>
                                {{$target->currency->name}}
                            </td>
                            <td>
                                {{$target->days}}
                            </td>
                            <td>
                                {{$target->mins/60}}
                            </td>
                            <td>
                                {{$target->hour_price}}
                            </td>
                            <td>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary " style="width: 74.5px;" href="{{ route('dashboard.targets.edit',$target->id) }}">
                                            Edit
                                        </a>
                                    </div>

                                    <div class="col-md-4">

                                        {{ Form::open(['route' => ['dashboard.targets.destroy',$target->id] ,'method' => 'DELETE' ,'class' => 'delete-form']) }}
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
<script>
    $(function () {
        $('#tbl-list-users').DataTable()
    })
</script>
@stop
