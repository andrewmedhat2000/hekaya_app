@extends('adminlte::page')
@section('stylesheets')
<style>
    body {
        background-color: #edeff9;
    }
</style>
@stop
@section('section-title')
<div class="row">
    <div class="col-md-4 col-xs-12">
        <h3 class="section-title contacts-section-title">
            ({{ $resource->title }})'s data.
        </h3>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="row">
            <div class="col-md-4 sort-col col-xs-4">
            </div>
            <div class="col-md-3 contact-edit-col col-xs-4">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-md-offset-1 text-right col-xs-11">
        <a href="{{ route('dashboard.ads.create') }}" class="btn btn-primary margin-left-10">
            <span>+ </span>Add Ads
        </a>
        <a href="{{ route('dashboard.ads.edit',$resource->id) }}" class="btn btn-warning margin-left-10">
            Edit Ads
        </a>
    </div>
</div>
@stop
@section('content')
<div class="row margin-top15">
    <div class="col-md-12">
        <div class="row margin-bottom10 contacts-list-view-card pad15">
            <table class="table table-borderless table-responsive" style="">
                <tbody>
                    <tr>
                        <th class="">
                            <span style="margin-left:30px;">
                                Property
                            </span>
                        </th>
                        <th>
                            Value
                        </th>
                    </tr>
                    <tr>
                        <td class="">
                            <span style="margin-left:30px;">
                                ID
                            </span>
                        </td>
                        <td>
                            {{ $resource->id }}
                        </td>
                    </tr>

                    <tr>
                        <td class="">
                            <span style="margin-left:30px;">
                                Title
                            </span>
                        </td>
                        <td>
                            {{ $resource->title }}
                        </td>
                    </tr>

                    <tr>
                        <td class="">
                            <span style="margin-left:30px;">
                                Description
                            </span>
                        </td>
                        <td>
                            {{ $resource->description }}
                        </td>
                    </tr>

                    <tr>
                        <td class="">
                            <span style="margin-left:30px;">
                                Logo
                            </span>
                        </td>
                        <td>

                            <div class="row">
                            <img src="{{$resource->logo_url}}" style="max-width: 200px;max-height: 200px;">
                            </div>
                            <br />
                        </td>
                    </tr>

                    <tr>
                        <td class="">
                            <span style="margin-left:30px;">
                                Images
                            </span>
                        </td>
                        <td>

                            @foreach ($resource->images()->get() as $image)
                            <div class="row">


                            <img src="{{$image->image_url}}" style="max-width: 200px;max-height: 200px;">

                            </div>
                            @endforeach
                            <br />
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop