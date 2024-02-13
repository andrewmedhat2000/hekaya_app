@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Deletion Request Status</div>
                    <div class="card-body">
                        @if($request->status == 1)
                            <p>Your data has been successfully deleted.</p>
                        @else
                            <p>Your data has not been deleted yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
