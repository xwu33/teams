@extends('layouts.app')
@section('title','Request Access')
@section('content')
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div id="ismyBama" class="col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="text-center">
              <p>
                Do you have a myBama account?
              </p>
            </div>
            <div class="row">
              <button type="button" class="col-xs-4 col-xs-offset-2 btn btn-success">Yes</button>
              <button type="button" class="col-xs-4 btn btn-danger">No</button>
            </div>
          </div>
        </div>
      </div>
      <div id="mybamaRequestForm" class="panel panel-default">
        <div class="panel-heading">Request Access</div>
        <div class="panel-body">
          <form role="form" method="GET" action="https://login.ua.edu/cas/login">
            <input type="hidden" name="service" id="casService" value="" />
            <div class="row">
              <div class="col-sm-4">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="account_type" class="sr-only">Account Type</label>
                    <select class="form-control" name="account_type" id="account_type_mybama" required autofocus>
                      <option disabled selected value="">Select Account Type</option>
                      @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                          @if(old('account_type') == $role->id)
                            selected="selected"
                          @endif
                          >{{ $role->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary col-xs-12">
                        Request myBama Link
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="requestAccessForm" class="panel panel-default">
          <div class="panel-heading">Request Access</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('request') }}">
              {{ csrf_field() }}
              <input type="hidden" id="myBamaSave" name="myBamaSave" value="{{ old('myBamaSave') }}"></input>
              <div class="row">
                <div class="col-sm-4 {{ $errors->has('account_type') ? ' has-error ' : ''}}">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="account_type" class="sr-only">Account Type</label>
                      <select class="form-control" name="account_type" id="account_type" required autofocus>
                        <option disabled selected value="">Select Account Type</option>
                        @foreach ($roles as $role)
                          <option value="{{ $role->id }}"
                            @if(old('account_type') == $role->id)
                              selected="selected"
                            @endif
                            >{{ $role->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-2 {{ $errors->has('prefix') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="prefix" class="sr-only">Prefix</label>
                        <input id="prefix" type="text" class="form-control" name="prefix" value="{{ old('prefix') }}" placeholder="Prefix">
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-3 {{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="first_name" class="sr-only">First Name</label>
                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-2 {{ $errors->has('middle_initial') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="middle_initial" class="sr-only">Middle Initial</label>
                        <input id="middle_initial" type="text" class="form-control" name="middle_initial" value="{{ old('middle_initial') }}" placeholder="MI">
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-3 {{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="last_name" class="sr-only">Last Name</label>
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-2 {{ $errors->has('suffix') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="middsuffixle_initial" class="sr-only">Suffix</label>
                        <input id="suffix" type="text" class="form-control" name="suffix" value="{{ old('suffix') }}" placeholder="Suffix">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-4 myBamaOnly {{ $errors->has('bamaId') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="bamaId" class="sr-only">myBama ID</label>
                        <input id="bamaId" type="text" class="form-control" name="bamaId" value="{{ old('bamaId') }}"  placeholder="myBama ID" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4 localOnly {{ $errors->has('username') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  placeholder="Username" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4 {{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="email" class="sr-only">E-Mail Address</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="phone_number" class="sr-only">Phone Number</label>
                        <input id="phone_number" type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number" required>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row localOnly">
                  <div class="col-sm-4 {{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="password-confirm" class="sr-only">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="form-group">
                    <div class="g-recaptcha col-sm-6 col-sm-offset-3" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-4 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary col-xs-12">
                      Submit Request
                    </button>
                  </div>
                </div>
              </form>
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
