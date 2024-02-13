@extends('adminlte::page')
@section('title','Streamers')
@section('content_header')
@stop
@section('content')

{{ Form::open(['route' => 'dashboard.streamer.import','files'=>'true']) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Import Streamers</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gender">
                            <span class="text-danger">*</span>
                            <!-- Streamer Admins -->
                            الوكالة
                        </label>
                        {{ Form::select('streamer_admin_id',$streamer_admins,null,['id'=>'streamer_admin_id','required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('streamer_admin_id') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="file">
                            <span class="text-danger">*</span>
                            Excel file
                            <small>(with user ids in the first column)</small>
                        </label>
                        {{ Form::file('file',['id'=>'file','required','class' => 'form-control', 'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('file') }}</p>
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
