@extends('adminlte::page')
@section('title','User')
@section('content_header')
@stop
@section('content')

{{ Form::open(['route' => 'dashboard.user.toggle.status','files'=>'true']) }}
{{ Form::hidden('user_id', $resource->id) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">User {{$resource->user_name}} Deactivate (Ban User) .</h3>
            </div>

            <div class="box-body">
                <div class="row">


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gender">
                            <span class="text-danger">*</span>
                            Deactivate (Ban User)
                        </label>
                        {{ Form::select('end_time',['1'=>'Ban 1 Hours','2'=>'Ban 24 Hours','3'=>'Ban 72 Hours','4'=>'Ban 168 Hours','5'=>'Ban 720 Hours(1 Month)','6'=>'Ban For Ever'],null,['id'=>'end_time','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('end_time') }}</p>
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