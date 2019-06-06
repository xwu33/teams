@extends('layouts.app')

@section('title', 'Whoa there!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>Sorry! You don't have permission to access this resource.</p>
                <p>Please contact the site admin to request access.</p>
            </div>
        </div>
    </div>
@endsection
