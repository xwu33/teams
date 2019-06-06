@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div id="requestAccessForm" class="panel panel-default">
        <div class="panel-heading">Edit User: {{ $user->username }}</div>
        <div class="panel-body">
          {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
            {{ csrf_field() }}
            @if($user->is_cas ==1)
              <input type="hidden" id="myBamaSave" name="myBamaSave" value="true"></input>
            @else
              <input type="hidden" id="myBamaSave" name="myBamaSave" value="false"></input>
            @endif
            <div class="row">
              <div class="col-sm-4 {{ $errors->has('account_type') ? ' has-error ' : ''}}">
                <div class="col-sm-12">
                  <div class="form-group">
                    @foreach ($roles as $role)
                        {{ Form::checkbox('roles[]',  $role->id ) }}
                        {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 {{ $errors->has('prefix') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('prefix', 'Prefix',array('class'=>'sr-only')) }}
                    {{ Form::text('prefix', null, array('class' => 'form-control','placeholder'=>'Prefix')) }}
                  </div>
                </div>
              </div>

              <div class="col-sm-3 {{ $errors->has('first_name') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('first_name', 'First Name',array('class'=>'sr-only')) }}
                    {{ Form::text('first_name', null, array('class' => 'form-control','placeholder'=>'First Name')) }}
                  </div>
                </div>
              </div>

              <div class="col-sm-2 {{ $errors->has('middle_initial') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('middle_initial', 'Middle Initial',array('class'=>'sr-only')) }}
                    {{ Form::text('middle_initial', null, array('class' => 'form-control','placeholder'=>'Middle Initial')) }}
                  </div>
                </div>
              </div>

              <div class="col-sm-3 {{ $errors->has('last_name') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('last_name', 'Last Name',array('class'=>'sr-only')) }}
                    {{ Form::text('last_name', null, array('class' => 'form-control','placeholder'=>'Last Name')) }}
                  </div>
                </div>
              </div>

              <div class="col-sm-2 {{ $errors->has('suffix') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('suffix', 'Suffix',array('class'=>'sr-only')) }}
                    {{ Form::text('suffix', null, array('class' => 'form-control','placeholder'=>'Suffix')) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-4 {{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('email', 'Email',array('class'=>'sr-only')) }}
                    {{ Form::text('email', null, array('class' => 'form-control','placeholder'=>'Email')) }}
                  </div>
                </div>
              </div>

              <div class="col-sm-4 {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('phone_number', 'Phone Number',array('class'=>'sr-only')) }}
                    {{ Form::text('phone_number', null, array('class' => 'form-control','placeholder'=>'Phone Number')) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row localOnly">
              <div class="col-sm-4 {{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                  </div>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="password-confirm" class="sr-only">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4 col-sm-offset-4">
                <button type="submit" class="btn btn-primary col-xs-12">
                  Save Changes
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
