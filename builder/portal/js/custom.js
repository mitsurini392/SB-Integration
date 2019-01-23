$(document).ready(function(){
	//load footer	
	var d = new Date();
	var y = "&copy; " + d.getFullYear() + " Small Builders. All Rights Reserved.";	
	$('.c-footer-details').html(y);
	
	hideloading();
	$(".nav > li > a").focus(function(){
		this.blur();
	});


$('#loading_img').css('display','none');
$('#c-container').css('display','block');

$('.c-thumbnail').click(function (){
	$('#panel').toggle('easing');
});

$('#search-form-text').focus(function ()
{
	$('#panel').hide('slow');
});
});

function GetQueryStringParams(e) {
	var t = window.location.search.substring(1);
	var n = t.split("&");
	for (var r = 0; r < n.length; r++) {
		var i = n[r].split("=");
		if (i[0] == e) {
			return i[1]
		}
	}
}

function showloading(){
	$("#c-container").hide();
	$("#page-load").show();
}

function hideloading(){
	$("#page-load").hide();
	$("#c-container").show();
}


function gologout(){
	if(confirm("Are you sure you want to Log out?") == true) { 
		window.location="../index.php?stat=logout"; 
	} else { 
		return false;
		}
}

function faqhs(id) { $(".divAnswerContainer").hide(200); $("#dA_Q"+id).show(200); } 
$("#imgOpen").click(function() { $("#imgClose").css("display","block"); $("#cssmenu").css("display","block"); $("#imgOpen").css("display","none");});
$("#imgClose").click(function() { $("#imgOpen").css("display","block"); $("#cssmenu").css("display","none"); $("#imgClose").css("display","none"); });


function disableSearch(){
	$(".search-form-btn").attr("disabled","disabled");
}

function enableSearch(){
	$(".search-form-btn").removeAttr('disabled');
	$('#shout_message').val('');
}