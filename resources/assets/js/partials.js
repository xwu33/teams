$( document ).ready(function () {

  // partials.assign
  $(".assignBtn").click(function(e){
    e.preventDefault();
    $("#assignModal #assignForm").attr('action', '/signups/' + $(this).data('examid') + '/assign')
    $('#assignModal').modal();
  });

  $('#assignModal').on('hidden.bs.modal', function (e) {
	  $(this)
	    .find("input[type!='hidden'],textarea")
	       .val('')
	       .end();
    $('#assigneeTable tbody').html('');
	});

  $("#assignInput").on('input', function(){
    var input = $(this).val();
    $("#addStudentBtn").data('locked', true);
    if(input != ""){
      $('#studentTable tr td:containsi("' + input +'")').show();
    } else {
      $('#studentTable tr td').hide();
    }
    $('#studentTable tr td:not(:containsi("' + input +'"))').hide();
  });

  $("#studentTable tr td").click(function(){
    $('#assignInput').val($(this).text());
    $('#studentTable tr td').hide();
    $("#addStudentBtn").data('locked', false);
  });

  $("#addStudentBtn").click(function(e){
    e.preventDefault();
    if($(this).data('locked') == false) {
      if(!$('#assigneeTable tr td:contains("'+ $('#assignInput').val() +'")').length) {
        $('#assigneeTable tbody').append('\
        <tr>\
          <td data-studentid="'+$('#studentTable tr td:contains("' + $('#assignInput').val() + '")').data('studentid')+'">'
            + $('#assignInput').val() +
          '</td>\
          <td>\
            <button class="btn btn-danger pull-right" onclick="$(this).parent().parent().remove()">\
              <i class="glyphicon glyphicon-trash"></i>\
            </button>\
          </td>\
        </tr>');
      }
    }
    $('#assignInput').val("");
    $('#studentTable tr td').hide();
    $(this).data('locked', true);
  });

  $("#assignSubmitBtn").click(function(e){
    e.preventDefault();
    //add table names to hidden inputs
    $('#assigneeTable tr td:first-child').each( function() {
      $('#assigneeList').append('\
      <input type="hidden" name="students['+ $(this).text() +']" value="'+ $(this).data('studentid') +'" />');
    });

    $('#assignForm').submit();
  });

});
