<div class="modal fade" id="assignModal"
tabindex="-1" role="dialog"
aria-labelledby="assignModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close"
        data-dismiss="modal"
        aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Assign Students</h4>
      </div>
      <div class="modal-body container-fluid">
        <div id="assignContent" class="col-sm-12" title="Assign Student">
          <div class="col-sm-6">
            <div class="input-group add-on">
                <input id="assignInput" class="form-control" placeholder="Student Name" type="text">
                <div class="input-group-btn">
                    <label for="addStudentBtn" class="sr-only">Add</label>
                    <button id="addStudentBtn" class="btn btn-success" name="addStudentBtn" data-locked="true">Add</button>
                </div>

            </div>

            <table id="studentTable" class="table-hover table-bordered col-sm-12">
              <tbody>
                @isset($studentList)
                  @foreach ($studentList as $student)
                    <tr>
                      <td data-studentid={{ $student->id }}>{{ $student->name }}</td>
                    </tr>
                  @endforeach
                @endisset
              </tbody>
            </table>
          </div>
          <div class="col-sm-6">
            <table id="assigneeTable" class="table-striped col-sm-12">
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <span class="pull-right">
          <form id="assignForm" action="{{ route('signups.signupOthers','SetInJS') }}" method="POST">
            {{ csrf_field() }}
            <div id="assigneeList"></div>
          </form>
          <button id="assignSubmitBtn" class="btn btn-primary">
            Assign Students
          </button>
        </span>
      </div>
    </div>
  </div>
</div>
