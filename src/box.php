<script src="static/js/jquery.js" type="text/javascript" language="javascript"></script>

<script language="javascript">
	//New character
	$(document).ready(function() {
		$("#username").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox").removeClass().addClass('messagebox').fadeIn("slow");
			//check the username exists or not from ajax
			$.post("user/user_availability.php", {
				user_name: $(this).val()
			}, function(data) {
				if (data == 'no') //if username not avaiable
				{
					$("#msgbox").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
						});
				} else {
					$("#msgbox").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxok').fadeTo(500, 1);
						});
				}

			});

		});
	});
</script>
<script language="javascript">
	//Validating Email
	$(document).ready(function() {
		$("#emailbox").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox1").removeClass().addClass('messagebox').fadeIn("slow");
			//check the emailbox exists or not from ajax
			$.post("user/email_availability.php", {
				email_name: $(this).val()
			}, function(data) {
				if (data == 'no') //if emailbox not avaiable
				{
					$("#msgbox1").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
						});
				} else {
					$("#msgbox1").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxok').fadeTo(500, 1);
						});
				}

			});

		});
	});
</script>

<script language="javascript">
	//Validating Email Confirmation
	$(document).ready(function() {
		$("#emailboxconf").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox2").removeClass().addClass('messagebox').fadeIn("slow");
			if ($(this).val() == $("#emailbox").val()) {
				$("#msgbox2").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxok').fadeTo(500, 1);
					});
			} else {
				$("#msgbox2").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
					});
			}
		});
	});
</script>

<script language="javascript">
	$(document).ready(function() {
		$("#conta").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox4").removeClass().addClass('messagebox').fadeIn("slow");
			//check the username exists or not from ajax
			$.post("user/acc_availability.php", {
				user_name: $(this).val()
			}, function(data) {
				if (data == 'no') //if username not avaiable
				{
					$("#msgbox4").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
						});
				} else {
					$("#msgbox4").fadeTo(200, 0.1, function() //start fading the messagebox
						{
							//add message and change the class of the box and start fading
							$(this).html('').addClass('messageboxok').fadeTo(500, 1);
						});
				}

			});

		});
	});
</script>

<script language="javascript">
	//Validating password
	$(document).ready(function() {
		$("#user_pass").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox7").removeClass().addClass('messagebox').fadeIn("slow");
			if ($(this).val() != "" && $(this).val().length > 3) {
				$("#msgbox7").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxok').fadeTo(500, 1);
					});
			} else {
				$("#msgbox7").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
					});
			}
		});
	});
</script>

<script language="javascript">
	//Validating password confirmation
	$(document).ready(function() {
		$("#conf_pass").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox8").removeClass().addClass('messagebox').fadeIn("slow");

			if ($(this).val() == $("#user_pass").val()) {
				$("#msgbox8").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxok').fadeTo(500, 1);
					});
			} else {
				$("#msgbox8").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
					});
			}
		});
	});
</script>


<Script Language=JavaScript>
	function deleteMsg(name) {
		if (name.value == "none") {
			document.getElementById('tr_confirm').style = "display:none";
			document.getElementById('txtDelete').innerHTML = "";
		} else {
			document.getElementById('tr_confirm').style = "";
			document.getElementById('txtDelete').innerHTML = `Digite <b style=\"color:red\">'${name.value}'</b> para confirmar a exclusão!`;
		}
	}
</Script>


<script language="javascript">
	//Validating password confirmation
	$(document).ready(function() {
		$("#conf_delete").blur(function() {
			//remove all the class add the messagebox classes and start fading
			$("#msgbox10").removeClass().addClass('messagebox').fadeIn("slow");
			var teste = $("#ddl_char");
			var valorSelecionado = teste.val();

			if ($(this).val() == valorSelecionado) {
				$("#msgbox10").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxok').fadeTo(500, 1);
					});
			} else {
				$("#msgbox10").fadeTo(200, 0.1, function() //start fading the messagebox
					{
						//add message and change the class of the box and start fading
						$(this).html('').addClass('messageboxerror').fadeTo(500, 1);
					});
			}
		});
	});
</script>


<style type="text/css">
	 /* .messagebox {}  // Disabling empty rule */

	.messageboxok {
		display: block;
		float: right;
		background: url(../images/ok.png);
		width: 15px;
		height: 16px;
	}

	.messageboxerror {
		display: block;
		float: right;
		background: url(../images/erro.png);
		width: 15px;
		height: 16px;
	}
</style>