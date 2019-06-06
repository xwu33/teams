$( document ).ready(function () {

	// console.log("hello !!!!");
	// alert("heloo");
	//handle if myBama account for error reloads
	if($("#myBamaSave").val() != "") {
		formSwitch($("#myBamaSave").val(),true);
	}

	$("#ismyBama button").click(function(e) {
		e.preventDefault();
		formSwitch($(this).text());
	});

	$("#account_type_mybama").change(function(){

		var accountTypeSelect = document.getElementById('account_type_mybama');
		var accountType = accountTypeSelect[accountTypeSelect.selectedIndex].value;

		 $("#casService").val('https://'+window.location.hostname+'/login/cas?request=true&account_type='+accountType);

	});

});

function formSwitch(ismyBama,quickHide = false) {
	if(ismyBama == "Yes") {
		//window.location="https://login.ua.edu/cas/login?service=" + encodeURIComponent("https://"+window.location.hostname+"/login/cas?request=true");
		//$("#myBamaSave").val("Yes");
		//$("#requestAccessForm #username").removeAttr('required');
		//$("#requestAccessForm #password").removeAttr('required');
		//$("#requestAccessForm #password-confirm").removeAttr('required');
		//if(quickHide) {
		//$(".localOnly").hide();
		//}
		//else {
		//$(".localOnly").fadeOut();
		// $("#casService").val('https://'+window.location.hostname+'/login/cas?request=true');
		$("#ismyBama").fadeToggle();
		$("#mybamaRequestForm").fadeToggle();
		//}
		return;
	}
	else {
		$("#myBamaSave").val("No");
		$("#requestAccessForm #bamaId").removeAttr('required');
		if(quickHide) {
			$(".myBamaOnly").hide();
		}
		else {
			$(".myBamaOnly").fadeOut();
		}
	}
	if(quickHide) {
		$("#ismyBama").hide();
		$("#requestAccessForm").show();
	}
	else {
		$("#ismyBama").fadeToggle(function() {
			$("#requestAccessForm").fadeToggle();
		});
	}
}
