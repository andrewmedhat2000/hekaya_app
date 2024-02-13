@extends('adminlte::page')

@section('title','Lucky Gifts')

@section('content_header')
    <h1>Lucky Gifts</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Total</h3>
                </div>
                <div>
                    <ul class="list-group container">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total app won
                            <span class="badge bg-primary rounded-pill">{{ $diamond_lost }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total app lost
                            <span class="badge bg-primary rounded-pill">{{ $diamond_win }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total users played the game
                            <span class="badge bg-primary rounded-pill">{{ $players_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total rounds Number
                            <span class="badge bg-primary rounded-pill">{{ $rounds_number }}</span>
                        </li>
                    </ul>
                </div>
                <div class="p-5">
                    <div class="form-group d-flex flex-row">
                        <form action="{{ route('dashboard.lucky-gift.view') }}" method="GET">
                            @csrf
                            <select class="custom-select col-6" name="date">
                                <option selected>Open this select menu</option>
                                <option value="day">Day</option>
                                <option value="month">Month</option>
                            </select>
                            <button type="submit" class="btn btn-primary ml-3">Filter</button>
                        </form>
                        <a class="btn btn-danger ml-3" href="{{ route('dashboard.lucky-gift.reset') }}">Reset</a>    
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Total app Profit</th>
                            <th scope="col">Total app won</th>
                            <th scope="col">Total app lost</th>
                            <th scope="col">Total users played the game</th>
                            <th scope="col">Total rounds Number</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $items)
                            <tr>
                                <td>{{ $items->sum('round_value') - $items->sum('points') }}</td>
                                <td>{{ $items->sum('round_value') }}</td>
                                <td>{{ $items->sum('points') }}</td>
                                <td>{{ $items->count() }}</td>
                                <td>{{ $items->sum('rounds_number') }}</td>
                                @if ($filter_date == "day") 
                                    <td>{{ \Carbon\Carbon::parse($items->first()->created_at)->format('Y - m - d') }}</td>
                                @else
                                    <td>{{ \Carbon\Carbon::parse($items->first()->created_at)->format('Y - m') }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop
