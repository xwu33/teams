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



<html>
<body>
<center>

  @role('Faculty')
  <center>
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Content</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $value)
      <tr>
        <th scope="row">{{$value->id}}</th>
        <td>{{$value->title}}</td>
        <td>{{$value->content}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </center>
  @endrole



@role('Admin')
<center>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Content</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $value)
    <tr>
      <th scope="row">{{$value->id}}</th>
      <td>{{$value->title}}</td>
      <td>{{$value->content}}</td>
      <!-- <td><a href=""><button>Edit</button></a>&nbsp;<a href="/delete/{{$value->id}}"><button>Delete</button></a></td> -->
      <td><a href="/delete/{{$value->id}}"><button>Delete</button></a></td>
    </tr>
    @endforeach
  </tbody>
</table>
</center>
@endrole



</center>
</body>
</html>
@endsection
