@extends('layouts.app')

@section('title', 'Whoa there!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>Whoops! Looks like you haven't been verified yet.</p>
                <p>Please wait for the admin to verify your account! </p>
            </div>
        </div>
    </div>
@endsection
