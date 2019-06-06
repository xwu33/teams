@extends('layouts.app')
@section('title','404 Resource Not Found')
@section('content')
  <h1>We were not able to find the page your were looking for.</h1>
  <h2>{{ $exception->getMessage() }}</h2>
@endsection
