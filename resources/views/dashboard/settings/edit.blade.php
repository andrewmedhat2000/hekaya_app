@extends('adminlte::page')
@section('title', 'Settings')
@section('content_header')
    <h1>Settings</h1>
@stop
@section('content')

    {{ Form::open(['route' => ['dashboard.settings.update',$setting->id], 'method' => 'PATCH', 'files' => 'true', 'enctype'=>'multipart/form-data']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">

                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Name
                                </label>
                                {{ Form::text('name', old('name', $setting->name), ['id' => 'name', 'required' => 'required', 'class' => 'form-control', 'disabled' => true]) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Value
                                </label>
                                {{ Form::number('value', old('value', $setting->value), ['id' => 'value', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('value') }}</p>
                            </div>

                        </div>

                        <br>
                        <div class="row">

                            <div class="col-md-2 col-xs-4">
                                <button type="submit" class="btn btn-block btn-primary">
                                    Save
                                </button>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <button type="reset" class="btn btn-block btn-default">
                                    Reset
                                </button>
                            </div>

                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@stop
