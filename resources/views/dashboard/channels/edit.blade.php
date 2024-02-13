@extends('adminlte::page')
@section('title','Users')
@section('content_header')
<h1>Users</h1>

@stop
@section('content')


{{ Form::open(['route' => ['dashboard.user.update',$resource->id],'method' => 'PATCH','files'=>'true','enctype'=>'multipart/form-data']) }}
{{ Form::hidden('resource_id',$resource->id,['id'=>'resource_id','class' => 'form-control']) }}

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">User Info .</h3>
            </div>

            <div class="box-body">
                <div class="row">



                        <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="user_name">
                                    <span class="text-danger">*</span>
                                    User Name
                                </label>
                                {{ Form::text('user_name',$resource->user_name,['id'=>'user_name','required' => 'required','class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('user_name') }}</p>
                            </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="name">
                            <span class="text-danger">*</span>
                            Name
                        </label>
                        {{ Form::text('name',$resource->name,['id'=>'name','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('name') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="email">
                            <span class="text-danger">*</span>
                            Email
                        </label>
                        {{ Form::email('email',$resource->email,['id'=>'email','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('email') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                            <label class="control-label" for="phone">
                                <span class="text-danger">*</span>
                                Phone
                            </label>
                            {{ Form::text('phone',$resource->phone,['id'=>'phone','required' => 'required','class' => 'form-control']) }}
                            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('phone') }}</p>
                        </div>

                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="password">
                            <span class="text-danger">*</span>
                            Password
                        </label>
                        {{ Form::password('password',['id'=>'password','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('password') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="password_confirmation">
                            <span class="text-danger">*</span>
                            Confirm-password
                        </label>
                        {{ Form::password('password_confirmation',['id'=>'password_confirmation','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('password_confirmation') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="age">
                            <span class="text-danger">*</span>
                            Age
                        </label>
                        {{ Form::number('age',$resource->age,['id'=>'age','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('age') }}</p>
                    </div>

                    
                    <div class="form-group margin-bottom20 col-md-6">
                        <label class="control-label" for="gender">
                            <span class="text-danger">*</span>
                            Gener
                        </label>
                        {{ Form::select('gender',['0'=>'male','1'=>'female'],$resource->gender,['id'=>'gender','required' => 'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('gender') }}</p>
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
