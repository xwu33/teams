@extends('layouts.app')

@section('title', 'Whoa there!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>Access Request already submitted.</p>
                <p>Please wait for an Administrator to approve your request</p>
            </div>
        </div>
    </div>
@endsection
