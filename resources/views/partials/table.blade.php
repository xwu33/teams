@isset($data)
  @if(!isset($options))
    @php($options = [])
  @endif
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach ($data['cols'] as $column)
                        <th>
                          @if($column['searchable']==false)
                              {{$column['displayName']}}
                          @else
                            <a href="{{ route(Route::current()->getName(), array_merge($options,[
                                'search' => app('request')->input('search'),
                                'column' => $column['varName'],
                                'dir' => 'asc'
                              ],Route::current()->parameters())
                                ) }}">
                                {{$column['displayName']}}
                              </a>
                              @if(app('request')->input('dir') !== '')
                                @if (app('request')->input('column') == $column['varName'])
                                  @if(app('request')->input('dir') == 'asc')
                                        <a href="{{ route(Route::current()->getName(), array_merge($options,[
                                            'search' => app('request')->input('search'),
                                            'column' => $column['varName'],
                                            'dir' => 'desc'
                                        ],Route::current()->parameters())
                                        ) }}">
                                            <!--{{$column['displayName']}}--> &#x25B2;
                                        </a>
                                    @else
                                        <a href="{{ route(Route::current()->getName(), array_merge($options,[
                                            'search' => app('request')->input('search'),
                                            'column' => $column['varName'],
                                            'dir' => 'asc'
                                        ],Route::current()->parameters())
                                        ) }}">
                                            <!--{{$column['displayName']}}--> &#x25BC;
                                        </a>
                                    @endif
                                @endif
                            @endif
                          @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
              @foreach ($data['data'] as $row)
                  <tr>
                  @foreach ($data['cols'] as $column)
                    @php ($colName = $column['varName'])
                    @if(property_exists($row,$colName))
                      @if(is_array($row->$colName))
                        <td>
                          <!--YIELD TO PARTIAL TABLE BUTTONS HERE-->
                          @include ('partials.button',['buttonData' => $row->$colName])
                        </td>
                      @else
                        <td> {!! $row->$colName !!} </td>
                      @endif
                    @else
                      <td></td>
                    @endif
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
          @if(method_exists($data['data'], 'render'))
            {{ $data['data']
              ->appends(['column' => app('request')->input('column'),
              'search' => app('request')->input('search'),
              'dir' => app('request')->input('dir')])
              ->links() }}
            @endif
          </div>
        @endisset
