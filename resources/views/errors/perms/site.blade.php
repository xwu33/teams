@extends('layouts.app')

@section('title', 'Uh Oh!')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="alert alert-danger text-center">
                <p>It looks like your account does not have permission to access this site.</p>
                <p><a href="{{route('request')}}" class="btn btn-link">Request Access</a></p>
            </div>
        </div>
    </div>
@endsection
