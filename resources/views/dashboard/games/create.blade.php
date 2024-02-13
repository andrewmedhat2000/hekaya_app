@extends('adminlte::page')
@section('title', 'Tags')
@section('content_header')
    <h1>Create New Game Link</h1>
@stop
@section('content')
    {{ Form::open(['route' => 'dashboard.games-slider.store', 'files' => 'true']) }}
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
                                    Title
                                </label>
                                {{ Form::text('title', old('title'), ['id' => 'title', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('title') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Game Link
                                </label>
                                {{ Form::text('link', old('link'), ['id' => 'round_value', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('link') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Image
                                </label>
                                {{ Form::file('image', old('image'), ['id' => 'image', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('image') }}</p>
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
