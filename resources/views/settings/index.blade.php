@extends('layouts.app')

@section('title', 'Control Panel')
@section('content')
@if (Session::has('flash_message'))
   <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
<hr>
@endif
  @role('Admin')
  @include('partials.searchBar.basic', ['cols' => $tableData['cols'], 'route' => 'settings.index'])
  <h2>List of Options</h2>
  @include('partials.table', ['data' => $tableData])








  <div class="modal fade" id="editOptionModal"
  tabindex="-1" role="dialog"
  aria-labelledby="editOptionModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form role="form" method="POST" id="editOptionForm" action="{{ route('settings.update', 'SetInTheJS') }}">
        <input name="_method" type="hidden" value="PUT">
        {{ csrf_field() }}
        <div class="modal-header">
          <button type="button" class="close"
          data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="title"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group {{ $errors->has('optionInput') ? ' has-error' : '' }}">
              <input id="optionName" type="hidden" name="option">
              <label id="optionLabel" for="optionInput" class="control-label sr-only">Option</label>
              <div class="col-sm-offset-3 col-sm-6">
                <input id="optionInput" type="text" class="form-control" name="value">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div id="editOptionButton">
            <button class="btn btn-success pull-right" type="submit">
              Change
            </button>
            </form>
          </div>
          <div id="cancelEditOptionButton">
            <button class="btn btn-danger pull-left">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>










  @endrole
@endsection
