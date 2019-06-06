@extends('layouts.app')

@section('title', 'Calendar')


@section('content')

<div class="col-lg-10 col-lg-offset-1">
    @if (Session::has('flash_message'))
       <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
       <hr>
    @endif
    @if (Session::has('error_message'))
       <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
       <hr>
    @endif
    {!! $calendar->calendar() !!}
    @if(Auth::user()->can('Signup All'))
      @include ('partials.assign',  ['studentList' => $studentList])
    @endif
    <div class="modal fade" id="eventModal"
    tabindex="-1" role="dialog"
    aria-labelledby="eventModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <div id="eventContent" title="Event Details">
                        Start: <span id="startTime"></span><br>
                        End: <span id="endTime"></span><br><br>
                        Max Students: <span id="students"></span><br>
                        Proctors: <span id="proctors"></span><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="signupButton" class="hidden">
                      <form id="signupEvent" role="form" method="GET" action="{{ route('signups.signupSelf', 'thisIsSetInTheJS') }}">
                        {{ csrf_field() }}
                        <button class="btn btn-success pull-left button-sp-right" type="submit">
                          Sign Up
                        </button>
                      </form>
                    </div>
                    @if(Auth::user()->can('Signup All'))
                      <button class="btn btn-success pull-left button-sp-right assignBtn">
                        Assign Students
                      </button>
                    @endif
                    <div id="listSignupButton">
                      <form id="listSignups" role="form" method="GET" action="{{ route('signups.listExamSignups', 'thisIsSetInTheJS') }}">
                        {{ csrf_field() }}
                        <button class="btn btn-success pull-left button-sp-right" type="submit">
                            List Proctors
                        </button>
                      </form>
                    </div>
                    <div id="cancelSignupButton" class="hidden">
                      <form id="cancelSignupEvent" role="form" method="POST" action="{{ route('signups.destroy', 'thisIsSetInTheJS') }}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-danger pull-left button-sp-right" type="submit">
                          Cancel Sign Up
                        </button>
                      </form>
                    </div>
                    <div id="editingButtons" class="hidden">
                      <form id="deleteEvent" role="form" method="POST" action="{{ route('exams.destroy', 'thisIsSetInTheJS') }}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-danger pull-right button-sp-right" type="submit">
                          Delete
                        </button>
                      </form>
                      <form id="editEvent" role="form" method="GET" action="{{ route('exams.edit', 'thisIsSetInTheJS') }}">
                        <button class="btn btn-warning pull-right button-sp-right" type="submit">
                          Edit
                        </button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addExamModal"
    tabindex="-1" role="dialog"
    aria-labelledby="addExamModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form class="form-horizontal" role="form" method="POST" action="{{ route('exams.store') }}">
                {{ csrf_field() }}
                <input type="hidden" id="incalendar" name="incalendar" value="incalendar">
                <input type="hidden" id="calView" name="calView" value="calView">
                <div class="modal-header">
                    <button type="button" class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <div id="examContent" title="Exam Details">
                        <div class="row">
                          <div class="col-sm-4 col-sm-offset-2 {{ $errors->has('course_name') ? ' has-error' : '' }}">
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
                          <div class="col-sm-4 {{ $errors->has('max_proctors') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="max_proctors" class="">Max Proctors</label>
                                <input id="max_proctors" type="text" class="form-control" name="max_proctors" value="{{ old('max_proctors') }}"  placeholder="Max Proctors" required>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-4 {{ $errors->has('max_students') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="max_students" class="">Max Students</label>
                                <input id="max_students" type="text" class="form-control" name="max_students" value="{{ old('max_students') }}"  placeholder="Max Students" required>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-4 {{ $errors->has('school_year') ? ' has-error' : '' }}">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="school_year" class="">School Year</label>
                                <select id="school_year" class="form-control" name="school_year">
                                  @foreach($years as $year)
                                    <option value="{{$year->school_year}}"
                                    @if($year->school_year == old('school_year'))
                                      selected="selected"
                                    @endif
                                    >{{$year->school_year . "-" . ($year->school_year+1-2000)}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="row">

                          <input id="date" type="hidden"  name="date">

                          <div class="col-sm-4 col-sm-offset-2 {{ $errors->has('start_time') ? ' has-error' : '' }}">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="addExamButton">
                        <button class="btn btn-success pull-right" type="submit">
                          Add
                        </button>
                        </form>
                    </div>
                    <div id="cancelAddExamButton">
                      <button class="btn btn-danger pull-left">
                        Cancel
                      </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->can('Signup All'))
      @include ('partials.assign',  ['studentList' => $studentList])
    @endif
</div>
<div class="col-lg-10 col-lg-offset-1">
		<div class="sidebar-wrapper">
				<h4 id="CalendarKey">Calendar Key</h4>
				<div>
					<ul style="margin: 0; margin-bottom: 5px; padding:0; float: left; list-style:none;">
						<li style="font-size: 80%;list-style: none;margin-left: 0; line-height: 18px; margin-bottom: 2px"><span style="display: block;float: left; height: 16px;width: 30px;margin-right: 5px;margin-left: 0;border: 1px solid #999;background:#7297e6;"></span> Active Tests</li>
						<li style="font-size: 80%;list-style: none;margin-left: 0; line-height: 18px; margin-bottom: 2px"><span style="display: block;float: left; height: 16px;width: 30px;margin-right: 5px;margin-left: 0;border: 1px solid #999;background:red;"></span> Full Tests</li>
						<li style="font-size: 80%;list-style: none;margin-left: 0; line-height: 18px; margin-bottom: 2px"><span style="display: block;float: left; height: 16px;width: 30px;margin-right: 5px;margin-left: 0;border: 1px solid #999;background:#7297e6;opacity:0.2;"></span> Past Tests</li>
						<li style="font-size: 80%;list-style: none;margin-left: 0; line-height: 18px; margin-bottom: 2px"><span style="display: block;float: left; height: 16px;width: 30px;margin-right: 5px;margin-left: 0;border: 1px solid #999;background:green;"></span> Your Tests</li>
					</ul>
				</div>
		</div>
	</div>




@endsection

@section('scripts')
    {!! $calendar->script() !!}
@endsection
