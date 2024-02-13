@extends('adminlte::page')
@section('title','Login Signs')
@section('content_header')
<h1>Login Signs</h1>

@stop
@section('content')


    {{ Form::open(['route' => 'dashboard.loginsigns.store','files'=>'true']) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Login Signs Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Title
                        </label>
                        {{ Form::text('name',old('name'),['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Diamonds
                        </label>
                        {{ Form::number('price',old('price'),['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="image">
                            <span class="text-danger">*</span>
                            Image Icon
                        </label>
                        {{ Form::file('image',['id'=>'image','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('image') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="days_to_expire">
                            <span class="text-danger">*</span>
                            Expires after (days)
                        </label>
                        {{ Form::number('days_to_expire',old('days_to_expire'),['required', 'id'=>'days_to_expire','step'=>'1','min' => 1,'class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('days_to_expire') }}</p>
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
