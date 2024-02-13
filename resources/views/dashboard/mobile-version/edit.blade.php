@extends('adminlte::page')
@section('title','Packages')
@section('content_header')
<h1>Packages</h1>

@stop
@section('content')



{{ Form::open(['route' => 'dashboard.mobile-version.update', 'method' => 'post']) }}


<div class="px-4 mt--5 pb-5">

    <div class="bg-white col-xl-9 px-0" style="border-radius: 5px; box-shadow: 0 0 .5rem rgba(0,0,0,.2)">

        <h4 class="px-3 pt-3 pb-2">Android Version</h4>

        <div class="bg-secondary px-3 py-4">

            <div class="form-group margin-bottom20 col-md-12">
                {{ Form::text('Android Version',$android->version ?? '',['id'=>'android-version','required' => 'required','class' => 'form-control']) }}
                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('text') }}</p>
            </div>

            <br>

            <div class="form-group col-md-6">
                            <label class="control-label" for="is_imported">
                                Required to updatee
                            </label>
                            {{ Form::checkbox('Android Required to update',true,$android->update_required?? '',['class' => 'form-control my-auto', 'style' => 'width:1rem']) }}
                            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('is_imported') }}</p>

            </div>

            <br>

        </div>


    </div>

    <div class="bg-white col-xl-9 px-0" style="border-radius: 5px; box-shadow: 0 0 .5rem rgba(0,0,0,.2)">

        <h4 class="px-3 pt-3 pb-2">IOS Version</h4>

        <div class="bg-secondary px-3 py-4">

            <div class="form-group margin-bottom20 col-md-12">
            {{ Form::text('IOS Version',$ios->version ?? '',['required' => 'required','class' => 'form-control']) }}
            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('text') }}</p>
        </div>

        <br>

        <div class="form-group col-md-6">
            <label class="control-label" for="is_imported">
                Required to update
            </label>
            {{ Form::checkbox('IOS Required to update',true,$ios->update_required?? '',['class' => 'form-control my-auto', 'style' => 'width:1rem']) }}
            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('is_imported') }}</p>

        </div>

        <br>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </div>

        {{ Form::close() }}

    </div>


    </div>


</div>
@stop


@section('js')

    {{-- @if(Session::has('success')) --}}
        <!-- <script>
            swal.fire({
                icon: 'success', position: 'top-end',
                title: '{{Session::get("success")}}', toast: true,
            })
        </script> -->
        {{-- @endif --}}

@stop
