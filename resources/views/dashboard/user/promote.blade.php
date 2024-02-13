@extends('adminlte::page')
@section('title','User')
@section('content_header')
@stop
@section('content')

{{ Form::open(['route' => 'dashboard.user.promote','files'=>'true']) }}
{{ Form::hidden('user_id', $resource->id) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">User {{$resource->user_name}}</h3>
            </div>

            <div class="box-body">
                <div class="row">


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gender">
                            <span class="text-danger">*</span>
                            <!-- Streamer Admins -->

                            الوكالات
                        </label>
                        {{ Form::select('stream_admin_id',$streamer_admins,null,['id'=>'stream_admin_id','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('stream_admin_id') }}</p>
                    </div>



                    <!-- <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="cost_per_hour">
                            <span class="text-danger">*</span>
                            Cost Per Hour
                        </label>
                        {{-- Form::number('cost_per_hour',old('cost_per_hour'),['id'=>'cost_per_hour','required' => 'required','class' => 'form-control']) --}}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('cost_per_hour') }}</p>
                    </div> -->

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
