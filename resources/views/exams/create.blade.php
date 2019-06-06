@extends('layouts.app')

@section('title', 'Add Exam')

@section('content')

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Create Exam</div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('exams.store') }}">
              {{ csrf_field() }}
              <div class="row">
                @can('Exam All')
                  <div class="col-sm-4 {{ $errors->has('instructor') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="instructor" class="">Instructor</label>
                        <select id="instructor" name="instructor" class="form-control">
                          @foreach($instructors as $instructor)
                            <option value="{{$instructor->id}}"
                              @if(old('instructor'))
                                @if($instructor->id == old('instructor'))
                                  selected="selected"
                                @endif
                              @elseif($instructor->id == Auth::user()->id)
                                selected="selected"
                              @endif
                          >{{$instructor->Instructor}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                @endcan
                <div class="col-sm-4
                @cannot('Exam All')
                  col-sm-offset-2
                @endcan
                {{ $errors->has('course_name') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="course_name" class="">Course Number</label>
                      <input id="course_name" type="text" class="form-control" name="course_name" value="{{ old('course_name') }}" placeholder="BSC 999">
                    </div>
                  </div>
                </div>

                <div class="col-sm-4 {{ $errors->has('location') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="location" class="">Exam Location</label>
                      <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}" placeholder="Biology Building 127" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-4 {{ $errors->has('date') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="date" class="">Date</label>
                      <input id="date" type="text" class="form-control" name="date" value="{{ old('date') }}"  placeholder="Date" required>
                    </div>
                  </div>
                </div>

                <div class="col-sm-4 {{ $errors->has('start_time') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="start_time" class="">Start Time</label>
                      <input id="start_time" type="text" class="form-control" name="start_time" value="{{ old('start_time') }}"  placeholder="Start Time" required>
                    </div>
                  </div>
                </div>

                <div class="col-sm-4 {{ $errors->has('end_time') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="end_time" class="">End Time</label>
                      <input id="end_time" type="text" class="form-control" name="end_time" value="{{ old('end_time') }}"  placeholder="End Time" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">

                <div class="col-sm-4 {{ $errors->has('max_students') ? ' has-error' : '' }}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="max_students" class="">Max Students</label>
                      <input id="max_students" type="text" class="form-control" name="max_students" value="{{ old('max_students') }}"  placeholder="Max Students" required>
                    </div>
                  </div>
                </div>

                </div>
                <div class="row">
                  <div class="col-sm-2">
                    <a href="{{ route('exams.index') }}" class="btn btn-danger col-xs-12">
                      Back
                    </a>
                  </div>
                  <div class="col-sm-4 col-sm-offset-6">
                    <button type="submit" class="btn btn-success col-xs-12">
                      Add Exam
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        @if (count($errors) > 0)
          <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
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
