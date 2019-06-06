@extends('layouts.app')

@section('content')
<div class="container">

<div class="text-center">
  <p></p>
  <h1>Welcome to our test app</h1>

<div class="row">
  <div class="col-md-6">
    <h2>Request Access</h2>
    <p>If you do not have an account, you can request access here </p>
    <a href="{{ URL::to('/request') }}"><button class="btn btn-primary">Request Access</button><br></a>
    (this will link to account creation screen for myBama OR local users)
  </div>
  <div class="col-md-6">
     <h2>Login</h2>
    <p>If you have a myBama account, please login using your myBama username and password here</p>
    <a href="{{ URL::to('/login/cas') }}"><button class="btn btn-primary">myBama Login</button><br></a>
    <p>
    <a href="{{ URL::to('/login') }}"><button class="btn btn-inverse">Don't have a myBama account?</button><br></a>
  </div>

  <!-- <div class="flex-center position-ref full-height">
     @if (Route::has('login'))
         <div class="top-right links">
             @auth
                 <a href="{{ url('/home') }}">Home</a>
             @else
                 <a href="{{ route('login') }}">Login</a>

                 @if (Route::has('register'))
                     <a href="{{ route('register') }}">Register</a>
                 @endif
             @endauth
         </div>
     @endif -->


 </div>

</div><!--/.row-->
  </div>
@endsection
