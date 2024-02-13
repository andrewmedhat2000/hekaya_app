@extends('adminlte::page')
@section('title','Currencies')
@section('content_header')
<h1>Currencies</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.currencies.update',$gift->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('gift_id',$gift->id,['id'=>'gift_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Currencies Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">




                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Currency Name
                        </label>
                        {{ Form::text('name',$currency->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="code">
                            <span class="text-danger">*</span>
                            Code
                        </label>
                        {{ Form::text('code',$currency->code,['id'=>'code','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('code') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="symbol">
                            <span class="text-danger">*</span>
                            Symbol
                        </label>
                        {{ Form::text('symbol',$currency->symbol,['id'=>'symbol','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('symbol') }}</p>
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