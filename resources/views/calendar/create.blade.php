@extends('layouts.app')

@section('title', 'Add Event')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>
    {{ Form::open(array('url' => 'events')) }}
    @include('partials.formcreate', ['data' => $formData])
    {{ Form::reset('Clear Form', array('class' => 'btn btn-danger')) }}
    {{ Form::submit('Add', array('class' => 'btn btn-success')) }}

    {{ Form::close() }}

</div>

@endsection
