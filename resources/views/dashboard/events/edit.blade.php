@extends('adminlte::page')
@section('section-title')
<div class="row">
    <div class="col-md-4 col-sm-12">
        <h3 class="section-title">Update Event</h3>
    </div>
</div>
@stop

@section('content')
{{ Form::open(['route' => ['dashboard.events.update',$resource->id],'method' => 'PATCH','files'=>'true']) }}
<input type="hidden" name="resource_id" value="{{ $resource->id }}">
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Event Info.</h3>
            </div>

            <div class="box-body">
                <div class="row">


                    <div class="form-group margin-bottom20 col-md-6" id="title">
                        <label class="control-label" for="title">
                            <span class="text-danger">*</span>
                            Title
                        </label>
                        {{ Form::text('title',$resource->title,['id'=>'title','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('title') }}</p>
                    </div>

                    
                    

                    <div class="form-group margin-bottom20 col-md-12" id="title">
                        <label class="control-label" for="description">
                            <span class="text-danger">*</span>
                            Description
                        </label>
                        {{ Form::textarea('description',$resource->description,['id'=>'description','required'=>'required','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('description') }}</p>
                    </div>


                    <div class="form-group margin-bottom20 col-md-12">
                        <label class="control-label" for="logo">
                            Logo
                        </label>
                        {{ Form::file('logo',['id'=>'logo','class' => 'form-control']) }}
                        <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('logo') }}</p>
                        <img src="{{$resource->logo_url}}" style="max-width: 200px;max-height: 200px;">
                    </div>


                    <div class="row">
                        <div class="form-group margin-bottom20 col-md-12">
                            <label class="control-label" for="images">
                                Image
                            </label>
                            {{ Form::file('images[]',['id'=>'images','class' => 'form-control']) }}
                            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('images') }}</p>
                        </div>
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


                @if($resource->images()->exists())
                <div>
                    <h3 class="control-label">Certificates And Achievements</h3>
                </div>
                @foreach ($resource->images()->get() as $image)
                <div class="row">
                    <div class="form-group margin-bottom20 col-md-6" id="file{{$image->id}}">
                        <a href="{{ $image->image_url }}">
                            <img src="{{$image->image_url}}" style="max-width: 200px;max-height: 200px;">

                        </a>

                        <form action="{{route('dashboard.events.image.delete')}}" method="post"
                            class="deleteFile-form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$image->id}}">
                            <input type="submit" value="Delete" class="btn btn-danger">
                        </form>

                    </div>
                    <br />
                </div>
                @endforeach
                @endif
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


        $('.deleteFile-form').submit(function(e) {
            e.preventDefault();
            var form = jQuery(this);
            var dataString = new FormData(this);
            var formAction = form.attr('action');
            $.ajax({
                type: "POST",
                url: formAction,
                data: dataString,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#file' + data).remove();
                },
                error: function(data) {}
            }), "JSON";
        });

</script>


<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ ENV('AIzaSyBStZxbq24IfQ_3zIPJ6EMfFf4zzma4a_o') }}&callback=initMap">
</script>

<script>
    var marker ,latlng = undefined;
function initMap() {
    var myLatlng = {
        lat: $('#lat').val() ? parseFloat($('#lat').val()) : 30.1197986,
        lng: $('#lng').val() ? parseFloat($('#lng').val()) : 31.5370003
    };

    var map = new google.maps.Map($('#map')[0], {
        zoom: 11,
        center: myLatlng
    });


    var marker = null;

    if ($('#lat').val() && $('#lng').val()) {
        latlng = {lat: parseFloat($('#lat').val()), lng: parseFloat($('#lng').val())};
        marker = new google.maps.Marker({
            position: latlng,
            map: map
        });
        map.panTo(marker.getPosition());
    }

    google.maps.event.addListener(map, 'click', function( event ){
        latlng = {lat: event.latLng.lat(), lng: event.latLng.lng()};

        $('#lat').val(event.latLng.lat());
        $('#lng').val(event.latLng.lng());

        if (marker) {
            marker.setMap(null);
        }

        marker = new google.maps.Marker({
            position: latlng,
            map: map
        });
        map.panTo(marker.getPosition());
    });
}

</script>
@stop