@extends('layouts.app')

@section('title', 'Edit Permissions')

@section('content')
<!--<h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
<br>-->
{{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

<div class="form-group">
    {{ Form::label('name', 'Permission Name') }}
    {{ Form::text('name', null, array('class' => 'form-control')) }}
</div>
<br>
{{ Form::submit('Save Changes', array('class' => 'btn btn-success')) }}

{{ Form::close() }}
@endsection
