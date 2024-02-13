@extends('adminlte::page')
@section('title','Badge')
@section('content_header')
<h1>Badge</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.badges.update',$resource->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{-- {{ Form::hidden('badge_id',$resource->id,['id'=>'badge_id','class' => 'form-control']) }} --}}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Badge Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Badge Title
                        </label>
                        {{ Form::text('name',$resource->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="price">
                            <span class="text-danger">*</span>
                            Diamonds
                        </label>
                        {{ Form::number('price',$resource->price,['id'=>'price','step'=>'0.01','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('price') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="days_to_expire">
                            <span class="text-danger">*</span>
                            Expires after (days)
                        </label>
                        {{ Form::number('days_to_expire',$resource->days_to_expire,['required', 'id'=>'days_to_expire','step'=>'1','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('days_to_expire') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="image">
                            <span class="text-danger">*</span>
                            Image Icon
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
