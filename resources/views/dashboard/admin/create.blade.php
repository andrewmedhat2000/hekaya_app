@extends('adminlte::page')
@section('title','Admins')
@section('content_header')
<h1>Admins</h1>

@stop
@section('content')


{{ Form::open(['route' => 'dashboard.admin.store','files'=>'true']) }}
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Admin Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="user_name">
                            <span class="text-danger">*</span>
                            User Name
                        </label>
                        {{ Form::text('user_name',old('user_name'),['id'=>'user_name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('user_name') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Name
                        </label>
                        {{ Form::text('name',old('name'),['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="user_name">
                            <span class="text-danger">*</span>
                            User Name
                        </label>
                        {{ Form::text('user_name',old('user_name'),['id'=>'user_name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('user_name') }}</p>
                    </div>



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="email">
                            <span class="text-danger">*</span>
                            Email
                        </label>
                        {{ Form::email('email',old('email'),['id'=>'email','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('email') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="phone">
                            <span class="text-danger">*</span>
                            Phone
                        </label>
                        {{ Form::text('phone',old('phone'),['id'=>'phone','required' => 'required','class' => 'form-control only-numeric']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('phone') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="age">
                            <span class="text-danger">*</span>
                            Age
                        </label>
                        {{ Form::number('age',old('age'),['id'=>'age','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('age') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gender">
                            <span class="text-danger">*</span>
                            Gener
                        </label>
                        {{ Form::select('gender',['0'=>'male','1'=>'female'],null,['id'=>'gender','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('gender') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label" for="description">
                            <span class="text-danger">*</span>
                            Description
                        </label>
                        {{ Form::textarea('description',old('description'),['id'=>'description','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('description') }}</p>
                    </div>



                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="job">
                            <span class="text-danger">*</span>
                            Job
                        </label>
                        {{ Form::text('job',old('job'),['id'=>'job','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('job') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="profile_pic">
                            profile Picture
                        </label>
                        {{ Form::file('profile_pic',['id'=>'profile_pic','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('profile_pic') }}</p>
                    </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="password">
                            <span class="text-danger">*</span>
                            Password
                        </label>
                        {{ Form::password('password',['id'=>'password','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('password') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="password_confirmation">
                            <span class="text-danger">*</span>
                            Confirm-password
                        </label>
                        {{ Form::password('password_confirmation',['id'=>'password_confirmation','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('password_confirmation') }}</p>
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

@section('js')
<script type="text/javascript">
    $(document).ready(function() {

        $(".only-numeric").bind("keypress", function(e) {
            var keyCode = e.which ? e.which : e.keyCode

            if (!(keyCode >= 48 && keyCode <= 57)) {
                $(".error").css("display", "inline");
                return false;
            } else {
                $(".error").css("display", "none");
            }
        });

    });
</script>
@stop