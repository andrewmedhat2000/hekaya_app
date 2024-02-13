@extends('adminlte::page')
@section('title','Currency Worth')
@section('content_header')
<h1>Currency Worth</h1>

@stop
@section('content')

{{ Form::open(['route' => ['dashboard.currency_worth.update',$resource->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('resource_id',$resource->id,['id'=>'resource_id','class' => 'form-control']) }}

<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Dollars
                        </label>
                        {{ Form::number('price',$resource->price,['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Diamond
                        </label>
                        {{ Form::number('diamond',$resource->diamond,['id'=>'diamond','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('diamond') }}</p>
                    </div>



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Charisma
                        </label>
                        {{ Form::number('charisma',$resource->charisma,['id'=>'charisma','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('charisma') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Gem
                        </label>
                        {{ Form::number('gem',$resource->gem,['id'=>'gem','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('gem') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="contribution">
                            <span class="text-danger">*</span>
                            Contribution
                        </label>
                        {{ Form::number('contribution',$resource->contribution,['id'=>'contribution','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('contribution') }}</p>
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
