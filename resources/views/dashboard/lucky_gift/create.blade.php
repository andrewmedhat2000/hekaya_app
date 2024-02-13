@extends('adminlte::page')
@section('title', 'Lucky Gifts')
@section('content_header')
    <h1>Create New Lucky Gift</h1>
@stop
@section('content')
    {{ Form::open(['route' => 'dashboard.lucky-gift.store', 'files' => 'true']) }}
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
                                {{ Form::text('name', old('name'), ['id' => 'name', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    price
                                </label>
                                {{ Form::number('price', old('price'), ['id' => 'price', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Image
                                </label>
                                {{ Form::file('image', old('image'), ['id' => 'image', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('image') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    GIF
                                </label>
                                {{ Form::file('gif', old('gif'), ['id' => 'gif', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('gif') }}</p>
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
