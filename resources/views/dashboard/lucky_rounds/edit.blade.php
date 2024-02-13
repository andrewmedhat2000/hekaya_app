@extends('adminlte::page')
@section('title', 'Lucky Rounds')
@section('content_header')
    <h1>Edit Lucky Rounds</h1>
@stop
@section('content')

    {{ Form::open(['route' => ['dashboard.lucky-round.update',$luckyRound->id], 'method' => 'PATCH', 'files' => 'true', 'enctype'=>'multipart/form-data']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">

                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Number of rounds
                                </label>
                                {{ Form::number('round_number', old('round_number', $luckyRound->round_number), ['id' => 'round_number', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('round_number') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Number of diamonds for rounds
                                </label>
                                {{ Form::number('round_value', old('round_value', $luckyRound->round_value), ['id' => 'round_value', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('round_value') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Minimum number of diamond will win it
                                </label>
                                {{ Form::number('minimum_win', old('minimum_win', $luckyRound->minimum_win), ['id' => 'minimum_win', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('minimum_win') }}</p>
                            </div>
                            <div class="form-group margin-bottom20 col-md-6">
                                <label class="control-label" for="name">
                                    <span class="text-danger">*</span>
                                    Maximum number of diamond will win it
                                </label>
                                {{ Form::number('maximum_win', old('maximum_win', $luckyRound->maximum_win), ['id' => 'maximum_win', 'required' => 'required', 'class' => 'form-control']) }}
                                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('maximum_win') }}</p>
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

                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@stop
