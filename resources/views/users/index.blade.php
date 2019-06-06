@extends('layouts.app')

@section('title')
  @role('Admin')
  User Administration
@else
  User Dashboard
  @endrole
@endsection
@section('content')
@if (Session::has('flash_message'))
   <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
<hr>
@endif
@if($unverifiedCount > 0)
    @role('Admin')
    <a href="{{ route('users.showUnverified') }}" class="btn btn-danger">
        {{
             $unverifiedCount." user".
             (($unverifiedCount == 1)?"":"s")." need".
             (($unverifiedCount == 1)?"s":"")
         }} to be verified!
    </a>
    @endrole
@endif

  @include('partials.searchBar.basic', ['cols' => $tableData['cols'], 'route' => 'users.index'])
  <h2>List of Users and Their Roles</h2>
  @include('partials.table', ['data' => $tableData])


  @role('Admin')
  <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
  {{-- <a href="{{ URL::to('users/All/excel')}}" class="btn btn-default">Download Excel</a>
  <a href="{{ URL::to('users/All/pdf')}}" class="btn btn-default">Download PDF</a> --}}
  @endrole
@endsection
