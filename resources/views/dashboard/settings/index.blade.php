@extends('adminlte::page')
@section('title','Settings')
@section('content_header')
<div class="d-flex px-3 pb-5">
    <h1>Settings</h1>
</div>

@stop
@section('content')
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="box">
            <div class="inqueries margin-bottom10">
                {{ $settings->links() }}
            </div>
            <div class="box-body">
                <table id="tbl-list-rounds" data-server="false" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($settings as $key => $setting)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            @if ($setting->name == "app_profit")
                                <td>ربح التطبيق النهائي</td>
                            @elseif ($setting->name == "turns_number_to_show_prize_moon_gift") 
                                <td>الرقم المحدد لأظهار الشريط</td>
                            @else
                                <td>{{ $setting->name }}</td>
                            @endif
                            <td>{{ $setting->value }}</td>
                            @if ($setting->name != "app_profit")
                                <td class="d-flex flex-row">
                                    <a href="{{ route('dashboard.settings.edit',[$setting->id]) }}" class="btn btn-primary">Edit</a>
                                </td>
                            @endif
                        </tr>
                        @empty
                            <h3>No Settings created yet</h3>                                
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

