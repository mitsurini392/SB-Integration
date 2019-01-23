function showForgotPassword()
{
	window.location = "./forgotpassword.php";
}

function showNewAccount()
{
	window.location = "./signup/";
}

function backtoLogIn()
{
		window.location = "./index.php";
}

function checkEmail()
{
	var email1 = document.getElementById("si_emailadd").value;
	var email2 = document.getElementById("si_confirmemail").value;
	
	if(email1!="" && email2!="")
	{
		if(email1==email2)
		{
			document.getElementById("pEmailCheck").style.display = "none";
			return true;
		}
		else
		{
			document.getElementById("pEmailCheck").style.display = "block";
			return false;
		}
	}
	else
	{
		document.getElementById("pEmailCheck").style.display = "none";
	}
	
	
}

window.onload = function()
{	 
	document.getElementById("loading_img").style.display="none";
	document.getElementById("mainContainer").style.display="block";
}

function disableSearch()
{
	$(".search-form-btn").attr("disabled","disabled");
}

function enableSearch()
{
	$(".search-form-btn").removeAttr('disabled');
	$('#shout_message').val('');
}