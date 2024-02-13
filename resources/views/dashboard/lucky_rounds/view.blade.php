@extends('adminlte::page')

@section('title','Hekayah Gifts')

@section('content_header')
    <h1>Hekayah Gifts</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">  </h3>
                </div>
                    <ul class="list-group container">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Number of users played the game
                            <span class="badge bg-primary rounded-pill">{{ $players_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Number of diamond users won it in the game
                            <span class="badge bg-primary rounded-pill">{{ $diamond_win }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Number of diamond users lost it in the game
                            <span class="badge bg-primary rounded-pill">{{ $diamond_lost }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop
