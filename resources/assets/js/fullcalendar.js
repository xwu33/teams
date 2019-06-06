$( document ).ready(function () {
	window.eventClicked = function(ev) {

		$("#eventModal #listSignups").attr('action', '/signups/' + ev.id + '/list');
		$("#eventModal .assignBtn").data('examid', ev.id);

		if(ev.can_edit) {
			$("#editingButtons").removeClass('hidden');
			$("#eventModal #editEvent").attr('action', '/exams/' + ev.id + '/edit');
			$("#eventModal #deleteEvent").attr('action', '/exams/' + ev.id);
		}
		else {
			$("#editingButtons").addClass('hidden');
		}

		if(ev.can_signup) {
			if(ev.is_signed_up) {
				$("#signupButton").addClass('hidden');
				$("#cancelSignupButton").removeClass('hidden');
				$("#eventModal #cancelSignupEvent").attr('action', '/signups/' + ev.self_signup_id);
				$("#assignModal #cancelSignupEvent").attr('action', '/signups/' + ev.user_id);
			}
			else if(ev.max_proctors > ev.signup_count) {
				$("#signupButton").removeClass('hidden');
				$("#cancelSignupButton").addClass('hidden');
				$("#eventModal #signupEvent").attr('action', '/signups/' + ev.id + '/signup');
				$("#assignModal #signupEvent").attr('action', '/signups/' + ev.id + '/signup');
			}
		}

		$("#eventModal #title").html(ev.title);
		$("#eventModal #startTime").html(moment(ev.start).format('MMM Do h:mm A'));
		$("#eventModal #endTime").html(moment(ev.end).format('MMM Do h:mm A'));
		$("#eventModal #proctors").html(ev.signup_count+"/"+ev.max_proctors);
		$("#eventModal #students").html(ev.max_students);
		//$("#eventModal #desc").html(ev.description);

		$('#eventModal').modal();
	}

	window.dayClicked = function(date, ev, view) {
		$("#addExamModal #title").html(`Add Exam for ${date.format('L')}`);
		$("#addExamModal #date").val(date.format('L'));
		if(date.format('hh:mma') != '12:00am') {
			$("#addExamModal #start_time").val(date.format('hh:mma'));
		}
		$("#addExamModal #calView").val(view.name);
		$('#addExamModal').modal();
	}

	$('#addExamModal').on('hidden.bs.modal', function (e) {
	  $(this)
	    .find("input[type!='hidden'],textarea")
	       .val('')
	       .end();
	})

	$("#deleteEvent").submit(function(e){
    if (confirm("Are you sure you want to delete?")) {
    	return true;
    } else {
      e.preventDefault();
      return false;
    }
  });

	$("#cancelAddExamButton").click(function(e){
    e.preventDefault();
    $('#addExamModal').modal('hide');
  });
});
