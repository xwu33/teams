
<form class="navbar-form" role="search" method="GET" action="{{ route($route) }}" >
    <h2>Search: </h2>
    <div class="input-group add-on">
        <div class="input-group-btn">
            <label for="advancedSearchBtn" class="sr-only">Advanced Search Button</label><button type="button" id="advancedSearchBtn" name="advancedSearchBtn" class="btn btn-default" data-toggle="modal"
            data-target="#searchModal">
                <i class="glyphicon glyphicon-resize-full"></i>
            </button>
        </div>
        <input class="form-control" placeholder="Search" name="search" id="srch-term" type="text" value="{{ app('request')->input('search') }}">
        <div class="input-group-btn">
            <label for="searchBtn" class="sr-only">Search Button</label><button class="btn btn-default" type="submit" id="searchBtn" name="searchBtn"><i class="glyphicon glyphicon-search"></i></button>
        </div>

    </div>
    <a href="{{route($route)}}" class="btn btn-danger">Clear Search</a>

    <div class="modal fade" id="searchModal"
    tabindex="-1" role="dialog"
    aria-labelledby="searchModalLabel" style="top: 30%">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                    id="searchModalLabel">Advanced Search</h4>
                </div>
                <div class="modal-body">
                    <ul id="searchCols" class="hidden">

                      @foreach($cols as $col)
                          <li __searchable='{{ ($col['searchable'] ? 'true' : 'false') }}'>{{ $col['displayName'] }}</li>
                      @endforeach

                    </ul>
                    <table class="table">
                        <tbody id="searchRows">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <span class="pull-left">
                        <button type="button" class="btn btn-primary add-searchrow"><i class="glyphicon glyphicon-plus"></i></button>
                    </span>
                    <span class="pull-right">
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
