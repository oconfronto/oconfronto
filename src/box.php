<script src="js/jquery.js" type="text/javascript" language="javascript"></script>

<script language="javascript">

$(document).ready(function()
{
	$("#username").blur(function()
	{
		//remove all the class add the messagebox classes and start fading
		$("#msgbox").removeClass().addClass('messagebox').fadeIn("slow");
		//check the username exists or not from ajax
		$.post("user/user_availability.php",{ user_name:$(this).val() } ,function(data)
        {
		  if(data=='no') //if username not avaiable
		  {
		  	$("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxerror').fadeTo(500,1);
			});		
        	  }
		  else
		  {
		  	$("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxok').fadeTo(500,1);	
			});
		  }
				
        });
 
	});
});
</script>
<script language="javascript">

$(document).ready(function()
{
	$("#emailbox").blur(function()
	{
		//remove all the class add the messagebox classes and start fading
		$("#msgbox2").removeClass().addClass('messagebox').fadeIn("slow");
		//check the emailbox exists or not from ajax
		$.post("user/email_availability.php",{ email_name:$(this).val() } ,function(data)
        {
		  if(data=='no') //if emailbox not avaiable
		  {
		  	$("#msgbox2").fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxerror').fadeTo(500,1);
			});		
         	 }
		  else
		  {
		  	$("#msgbox2").fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxok').fadeTo(500,1);	
			});
		  }
				
        });
 
	});
});
</script>

<script language="javascript">

$(document).ready(function()
{
	$("#conta").blur(function()
	{
		//remove all the class add the messagebox classes and start fading
		$("#msgbox4").removeClass().addClass('messagebox').fadeIn("slow");
		//check the username exists or not from ajax
		$.post("user/acc_availability.php",{ user_name:$(this).val() } ,function(data)
        {
		  if(data=='no') //if username not avaiable
		  {
		  	$("#msgbox4").fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxerror').fadeTo(500,1);
			});		
        	  }
		  else
		  {
		  	$("#msgbox4").fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('').addClass('messageboxok').fadeTo(500,1);	
			});
		  }
				
        });
 
	});
});
</script>

<style type="text/css">
.messagebox{

}
.messageboxok{
display:block;float:right;background:url(../images/ok.png);width:15px;height:16px;
}
.messageboxerror{
display:block;float:right;background:url(../images/erro.png);width:15px;height:16px;
}

</style>
