@extends('adminlte::page')
@section('title','Targets')
@section('content_header')
<h1>Targets</h1>

@stop
@section('content')

{{ Form::open(['route' => ['dashboard.targets.update',$target->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('target_id',$target->id,['id'=>'target_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Targets Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="title">
                            <span class="text-danger">*</span>
                            ليفل التارجيت
                        </label>
                        {{ Form::text('title',$target->title,['id'=>'title','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('title') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="quantity">
                            <span class="text-danger">*</span>
                            التارجيت المطلوب بالماسات
                        </label>
                        {{ Form::number('quantity',$target->quantity,['id'=>'quantity','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('quantity') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="currency_id">
                            <span class="text-danger">*</span>
                            العملة
                        </label>
                        {{ Form::select('currency_id',$currencies,(isset($target->currency_id) ? $target->currency_id : ''),['placeholder' => 'Pick a Currency...','id'=>'currency_id','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('currency_id') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            المكافأة الاساسية
                        </label>
                        {{ Form::number('price',$target->price,['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            مكافأة المضيف
                        </label>
                        {{ Form::number('admin_price',$target->admin_price,['id'=>'admin_price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('admin_price') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="days">
                            <span class="text-danger">*</span>
                            عدد الايام المطلوبة في الشهر
                        </label>
                        {{ Form::number('days',$target->days,['id'=>'days','step'=>'1','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('days') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="hours">
                            <span class="text-danger">*</span>
                            عدد الساعات المطلوبة في الشهر
                        </label>
                        {{ Form::number('hours',$target->mins/60,['id'=>'hours','step'=>'0.01','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('hours') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="hour_price">
                            <span class="text-danger">*</span>
                            المكافأة على الساعات
                        </label>
                        {{ Form::number('hour_price',$target->hour_price,['id'=>'hour_price','step'=>'0.01','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('hour_price') }}</p>
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
