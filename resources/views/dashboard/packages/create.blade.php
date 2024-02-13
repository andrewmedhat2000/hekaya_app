@extends('adminlte::page')
@section('title','Packages')
@section('content_header')
<h1>Packages</h1>

@stop
@section('content')


{{ Form::open(['route' => 'dashboard.packages.store','files'=>'true']) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Packages Info.</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="title">
                            <span class="text-danger">*</span>
                            Package Title
                        </label>
                        {{ Form::text('title',old('title'),['id'=>'title','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('title') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="quantity">
                            <span class="text-danger">*</span>
                            Diamonds
                        </label>
                        {{ Form::number('quantity',old('quantity'),['id'=>'quantity','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('quantity') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="currency_id">
                            <span class="text-danger">*</span>
                            Currency
                        </label>
                        {{ Form::select('currency_id',$currencies,null,['placeholder' => 'Pick a Currency...','id'=>'currency_id','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('currency_id') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Price
                        </label>
                        {{ Form::number('price',old('price'),['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                            <label class="control-label" for="image">
                              Image Icon
                            </label>
                            {{ Form::file('image',['id'=>'image','class' => 'form-control']) }}
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

                    {{ Form::close() }}
                </div>


            </div>
        </div>
    </div>


</div>





@stop