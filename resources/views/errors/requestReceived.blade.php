@extends('layouts.app')

@section('title', 'Request Received!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>Access Request Received. You should receive an email once an Administrator has approved the request.</p>
            </div>
        </div>
    </div>
@endsection
