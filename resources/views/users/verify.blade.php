@extends('layouts.app')

@section('title', 'Verify Users')

@section('content')
@if (Session::has('flash_message'))
   <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
<hr>
@endif
@include('partials.table', ['data' => $tableData])
@endsection
