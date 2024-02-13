@extends('adminlte::page')
@section('title','Local Channel')
@section('content_header')
<h1>Local Channel</h1>

@stop
@section('content')


{{ Form::open(['route' => 'dashboard.local.channel.store','files'=>'true']) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Local Channel Info.</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Choose user to start chat with
                        </label>
                        {{ Form::select('name',$users,null,['placeholder' => 'Pick a User...','id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
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

                    {{ Form::close() }}
                </div>


            </div>
        </div>
    </div>


</div>





@stop