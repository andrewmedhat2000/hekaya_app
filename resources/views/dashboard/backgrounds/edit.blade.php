@extends('adminlte::page')
@section('title','Background')
@section('content_header')
<h1>Background</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.backgrounds.update',$resource->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{-- {{ Form::hidden('badge_id',$resource->id,['id'=>'badge_id','class' => 'form-control']) }} --}}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Background Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Background Title
                        </label>
                        {{ Form::text('name',$resource->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="image">
                            Image
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
