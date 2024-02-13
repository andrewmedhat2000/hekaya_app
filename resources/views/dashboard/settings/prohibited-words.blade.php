@extends('adminlte::page')
@section('title','Prohibited words')
@section('content_header')
<h1>Prohibited words</h1>

@stop
@section('content')

{{ Form::open(['route' => 'dashboard.settings.prohibited-words.update' ,'method' => 'POST','enctype'=>'multipart/form-data']) }}

<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-12">
                        {{ Form::select('list[]',$words, $words,['id'=>'list', 'multiple','required','class' => 'form-control select2 w-100']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('list') }}</p>
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

@stop
@section('css')
<style>
    .select2-container{
        width: 100%
    }
</style>
@stop
@section('js')
<script>
    $('.select2').select2({
        tags: true
    });
</script>
@stop
