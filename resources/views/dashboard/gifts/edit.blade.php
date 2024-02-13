@extends('adminlte::page')
@section('title','Gifts')
@section('content_header')
<h1>Gifts</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.gifts.update',$gift->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('gift_id',$gift->id,['id'=>'gift_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Gift Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Gift Title
                        </label>
                        {{ Form::text('name',$gift->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Diamonds
                        </label>
                        {{ Form::number('price',$gift->price,['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
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
                            <img src="{{$gift->image_url}}" style="max-height: 150px;max-width: 150px;">
                        </div>


                        @if(!empty($gift->gif_url))
                        <div class="margin-bottom20 col-md-6">
                            <img src="{{$gift->gif_url}}" style="max-height: 150px;max-width: 150px;">
                        </div>
                        @endif
                    {{-- </div> --}}

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Type
                        </label>
                        {{ Form::select('type', ['expensive' => 'Expensive', 'regular' => 'Regular', 'full' => 'Full'] ,old('type', $gift->type),
                            ['id'=>'type','required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>

                    {{-- <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="display_time">
                            GIF display time in seconds (optional)
                        </label>
                        {{ Form::number('display_time',$gift->display_time ?? '',['id'=>'display_time','step'=>'0.01','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('display_time') }}</p>
                    </div> --}}

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
