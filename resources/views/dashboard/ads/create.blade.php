@extends('adminlte::page')
@section('section-title')
<div class="row">
    <div class="col-md-4 col-sm-12">
        <h3 class="section-title">Create new Ad</h3>
    </div>
</div>
@stop
@section('content')
{{ Form::open(['route' => 'dashboard.ads.store','files'=>'true']) }}
<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ad Info.</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group margin-bottom20 col-md-6" id="title">
                        <label class="control-label" for="title">
                            <span class="text-danger">*</span>
                            Title
                        </label>
                        {{ Form::text('title',old('title'),['id'=>'title','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('title') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-12" id="title">
                        <label class="control-label" for="description">
                            <span class="text-danger">*</span>
                            Description
                        </label>
                        {{ Form::textarea('description',old('description'),['id'=>'description','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('description') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label" for="logo">
                            Logo
                        </label>
                        {{ Form::file('logo',['id'=>'logo','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('logo') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label" for="images">
                            Image
                        </label>
                        {{ Form::file('images[]',['id'=>'images','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('images') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label">
                            Add Images
                            <button type="button" onclick="add_file()" class="btn btn-primary btn-sm">+ Add
                                More</button>
                        </label>
                    </div>
                    <div id="addfileDiv">
                    </div>




                    <div id="addfileDivTemplate" style="display:none;">
                        <div class="row">
                            <div class="form-group margin-bottom20 col-md-12">
                                <label class="control-label" for="images">
                                    Image
                                </label>
                                {{ Form::file('images[]',['id'=>'images','class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('images') }}</p>
                            </div>

                        </div>
                    </div>


                </div>



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



@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

@stop
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $('.tags').select2({
            placeholder: 'select',
        });
</script>

<script type="text/javascript">
    function add_file() {
            var template = $('#addfileDivTemplate').html();
            $('#addfileDiv').append(template);
        }
      
</script>

@stop