@extends('layouts.app')

@section('content')
            <div class="panel panel-default">
                <div class="panel-heading text-center">Create Comments</div>

                <div class="panel-body">
                    <form action="{{ route('discussions.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" name="title" class="form-control">
                        </div>


                        <div class="form-group">
                              <label for="content">Make a comment</label>
                              <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                              <button class="btn btn-success pull-right" type="submit">Create comments</button>
                        </div>
                    </form>
                </div>
            </div>

@endsection
