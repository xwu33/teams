@extends('layouts.app')

@section('title', 'Proctors')

@section('content')
@if (Session::has('flash_message'))
   <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
<hr>
@endif
<h2>Signed Up For: </h2><br>
<h3>{{$exam->course_name}} <br>On {{$exam->date_time}} <br>With {{$exam->instructor}}</h3>
@include('partials.table', ['data' => $tableData])
@endsection
