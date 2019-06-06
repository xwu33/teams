@extends('layouts.app')

@section('title', 'Whoa there!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>Whoops! Looks like this account is a local account instead of a myBama one.</p>
                <p>Please contact the site administrator if you believe this is not correct.</p>
            </div>
        </div>
    </div>
@endsection
