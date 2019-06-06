$( document ).ready(function () {
	// alert("hello");
	$("#date").datepicker();
	$("#from_date").datepicker();
	$("#to_date").datepicker();
	$("#start_time").timepicker({
		minTime: "7:00am",
		maxTime: "9:00pm"
	});
	$("#end_time").timepicker({
		minTime: "7:00am",
		maxTime: "9:00pm"
	});
});
