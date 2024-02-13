@extends('adminlte::page')
@section('title','User')
@section('content_header')
<h1>User {{$resource->user_name}} Info</h1>

@stop
@section('content')
<div class="row">



    <div class="col-xs-12 col-md-12">

        <a href="{{ route('dashboard.streameradmin.create') }}" class="btn btn-block btn-primary">
            Add new
        </a>
        <a href="{{ route('dashboard.streameradmin.edit',$resource->id) }}" class="btn btn-block btn-primary">
            Edit User
        </a>




        <div class="box">

            <div class="box-header">
                <h3 class="box-title">All User Info</h3>
            </div>

            <div class="box-body">

                <table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
                    <tbody>

                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Name
                                </span>
                            </th>
                            <td>
                                {{$resource->name}}
                            </td>
                        </tr>


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        User Name
                                    </span>
                                </th>
                                <td>
                                    {{$resource->user_name}}
                                </td>
                            </tr>


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Email
                                    </span>
                                </th>
                                <td>
                                    {{$resource->email}}
                                </td>
                            </tr>


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Age
                                    </span>
                                </th>
                                <td>
                                    {{$resource->age}}
                                </td>
                            </tr>


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Phone
                                    </span>
                                </th>
                                <td>
                                    {{$resource->phone}}
                                </td>
                            </tr>


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Gender
                                    </span>
                                </th>
                                <td>
                                    @if($resource->gender == 0)
                                    Male
                                    @else
                                    Female
                                    @endif
                                </td>
                            </tr>


{{--
                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Status
                                    </span>
                                </th>
                                <td>
                                    @if ($resource->active)
                                    Active
                                    @else
                                    Not Active
                                    @endif
                                </td>
                            </tr>  --}}


                        <tr>
                                <th class="">
                                    <span style="margin-left:30px;">
                                        Diamonds
                                    </span>
                                </th>
                                <td>
                                    {{$resource->diamonds}}
                                </td>
                            </tr>


                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Description
                                </span>
                            </th>
                            <td>
                                {{$resource->description}}
                            </td>
                        </tr>


                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Job
                                </span>
                            </th>
                            <td>
                                {{$resource->job}}
                            </td>
                        </tr>

                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Profile Picture
                                </span>
                            </th>
                            <td>
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <img src="{{$resource->profile_pic_url}}" style="max-height: 150px;max-width: 150px;">
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Friends
                                </span>
                            </th>
                            <td>
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <a href="{{ route('dashboard.streameradmin.friends',['user_id' => $resource->id])}}">
                                            ({{$resource->friends_count}})Friends
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Followers
                                </span>
                            </th>
                            <td>
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <a href="{{ route('dashboard.streameradmin.followers',['user_id' => $resource->id])}}">
                                            ({{$resource->followers}})Followers
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <th class="">
                                <span style="margin-left:30px;">
                                    Followings
                                </span>
                            </th>
                            <td>
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom20 col-md-6">
                                        <a href="{{ route('dashboard.streameradmin.following',['user_id' => $resource->id])}}">
                                            ({{$resource->following}})Followings
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>


                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

@stop

@section('js')
{{--<script>--}}
{{--$(function () {--}}
{{--$('#tbl-list-users').DataTable()--}}
{{--})--}}
{{--</script>--}}
@stop
