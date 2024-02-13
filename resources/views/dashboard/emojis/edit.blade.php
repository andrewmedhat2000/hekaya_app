@extends('adminlte::page')
@section('title','Emojis')
@section('content_header')
<h1>emojis</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.emojis.update',$emoji->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('emoji_id',$emoji->id,['id'=>'emoji_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Emoji Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Emoji Title
                        </label>
                        {{ Form::text('name',$emoji->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="image">
                            Image Icon
                        </label>
                        {{ Form::file('image',['id'=>'image','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('image') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gif">
                          GIF
                        </label>
                        {{ Form::file('gif',['id'=>'gif','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('gif') }}</p>
                    </div>

                      {{-- <div class="row"> --}}
                        <div class="margin-bottom20 col-md-6">
                            <img src="{{$emoji->image_url}}" style="max-height: 150px;max-width: 150px;">
                        </div>


                        @if(!empty($emoji->gif_url))
                        <div class="margin-bottom20 col-md-6">
                            <img src="{{$emoji->gif_url}}" style="max-height: 150px;max-width: 150px;">
                        </div>
                        @endif
                    {{-- </div> --}}

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
