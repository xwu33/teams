@extends('layouts.app')

@section('title', 'Exams')

@section('content')
  @if (Session::has('flash_message'))
    <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
    <hr>
  @endif
  @if (Session::has('error_message'))
    <div class="alert alert-danger">{{Session::get('error_message')}}</div>
    <hr>
  @endif
  <div class="row">
    @include('partials.searchBar.basic', ['cols' => $tableData['cols'], 'route' => 'exams.index','options' => $options])
  </div>
  <div class="row">
    @include('partials.filter', ['route' => 'exams.index', 'options' => $options,'currentSemesterEndDate' => $currentSemesterEndDate])
  </div>
  <div class="row">
    <h2>List of Exams</h2>
    @include('partials.table', ['data' => $tableData, 'options' => $options])
    @if(Auth::user()->can('Exam Self') || Auth::user()->can('Exam All'))
      <a href="{{ route('exams.create') }}" class="btn btn-success">Add Exam</a>
    @endif
    @if(Auth::user()->can('Signup All'))
      @include ('partials.assign',  ['studentList' => $studentList])
    @endif
  </div>
@endsection
