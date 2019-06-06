$( document ).ready(function () {
	$('#loginModal').on('shown.bs.modal', function () {
    $('#username').focus();
	});
});
