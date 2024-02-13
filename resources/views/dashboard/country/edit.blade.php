@extends('adminlte::page')
@section('title','Counrties')
@section('content_header')
<h1>Countries</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.country.update',$resource->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('resource_id',$resource->id,['id'=>'resource_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Country Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Name
                        </label>
                        {{ Form::text('name',$resource->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="code">
                            <span class="text-danger">*</span>
                            Code
                        </label>
                        {{ Form::text('code',$resource->code,['id'=>'code','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('code') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="phone_code">
                            <span class="text-danger">*</span>
                            Phone Code
                        </label>
                        {{ Form::text('phone_code',$resource->phone_code,['id'=>'phone_code','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('phone_code') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="image">
                            Image Icon
                        </label>
                        {{ Form::file('image',['id'=>'image','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('image') }}</p>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group margin-bottom20 col-md-6">
                            <img src="{{$resource->image_url}}" style="max-height: 150px;max-width: 150px;">
                        </div>
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
