@extends('layouts.app')

@section('title', 'My Signups')

@section('content')
@if (Session::has('flash_message'))
  <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
  <hr>
@endif
@if (Session::has('error_message'))
  <div class="alert alert-danger">{{Session::get('error_message')}}</div>
  <hr>
@endif
<h2>Signed Up Exams</h2>
@include('partials.table', ['data' => $tableData])
@endsection
