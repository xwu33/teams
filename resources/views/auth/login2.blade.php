@extends('layouts.app')
@section('title','Login to BioProctor')
@section('content')
<div class="row">
    @if (session('flash_message'))
    <div class="col-sm-12">
        <div class="alert alert-success text-center">
            {{ session('flash_message') }}
        </div>
    </div>
    @endif
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Request Access</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <p class="col-sm-12">
                                If you have a mybama account but do not have access to the website, you can request access here:
                            </p>
                        </div>
                        <div class="row">
                            <a href="{{ URL::to('request') }}" class="btn btn-primary col-xs-8 col-xs-offset-2">Request Access</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">MyBama Login</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <p class="col-sm-12">
                                If you have a mybama account, please login using your myBama username and password here:
                            </p>
                        </div>
                        <div class="row">
                            <a href="{{ URL::to('login/cas') }}" class="btn btn-primary col-xs-4 col-xs-offset-4">Login</a>
                        </div>
                        <button type="button" class="row btn btn-link" data-toggle="modal"
                        data-target="#loginModal">
                            Dont have a myBama account?
                        </button>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
<div class="modal fade" id="loginModal"
tabindex="-1" role="dialog"
aria-labelledby="loginModalLabel" style="top: 30%">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <div id="titleGroup">
                    <h4 class="modal-title" id="viewTitle">Login</h4>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-2 {{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="username" class="sr-only">Username</label>
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 {{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="password" class="sr-only">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <label class="checkbox-inline col-sm-12">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary col-xs-2 col-xs-offset-5">
                            Login
                        </button>
                    </div>
                    <div class="row text-center">
                        <a class="btn btn-link col-sm-12" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="row">
    @if (count($errors) > 0)
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
