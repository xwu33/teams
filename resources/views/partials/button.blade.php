{!! Form::open(['method' => $buttonData['method'], 'route' => $buttonData['route'] ]) !!}
@php ($buttonArgs = ['type' => 'submit', 'class' => 'btn btn-'.$buttonData['class']])

@if ($buttonData['method'] == 'DELETE')
  @php ($buttonArgs['onclick'] = 'if (!confirm("Are you sure?")) return false')
@endif
@if(isset($buttonData['icon']))
  {{Form::button('<i class="glyphicon glyphicon-'.$buttonData['icon'].'"></i>', $buttonArgs)}}
@else
  {!! Form::button($buttonData['name'], $buttonArgs) !!}
@endif
{!! Form::close() !!}
