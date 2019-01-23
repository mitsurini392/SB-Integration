		// 	/* outer div */
		// 	var div = document.createElement("div");
		// 	div.id = "modal-demo";
		// 	document.body.appendChild(div);
		// 	$("#modal-demo").addClass("modal fade");
		// 	$("#modal-demo").attr("data-backdrop","static");
		// 	$("#modal-demo").attr("aria-hidden","true");
		// 	$("#modal-demo").attr("role","dialog");
		// 	$("#modal-demo").attr("style","width: 100%; height: 100%; position: absolute; top: 0; left: 0;");

function create_modal()
{

	//outer div
	var div = document.createElement("div");
	div.id = "demo-modal";
	// document.body.appendChild(div);
	document.body.insertBefore(div, document.body.firstChild);
	$("#demo-modal").addClass("modal fadeInLeft animated");
	$("#demo-modal").attr("role","dialog");

	//talk-div
	var div1 = document.createElement("div");
	div1.id = "talk-div";
	document.getElementById("demo-modal").appendChild(div1);

	//talk-text
	var div2 = document.createElement("div");
	div2.id = "talk-text";
	document.getElementById("talk-div").appendChild(div2);

};

function insert_body(body)
{

	$("#talk-text").empty();
	$("#talk-text").append(body);

};

function body_position(top,left)
{

	document.getElementById("talk-div").removeAttribute("style");
	$("#talk-div").attr("style","position: absolute !important; top: " + top + "px !important; left: " + left + "px !important;");

};

function position_bubble(top,left)
{

	var x = top - 250;
	var y = left;

	body_position(x,y);

};

function speech_bubble_left()
{
	$("#talk-text").addClass("speech-bubble-left");
};

function speech_bubble_bottom()
{
	$("#talk-text").addClass("speech-bubble-bottom");
};

function speech_bubble_right()
{
	$("#talk-text").addClass("speech-bubble-right");
};

function speech_bubble_top()
{
	$("#talk-text").addClass("speech-bubble-top");
};

// balloon position with speech bubble tip

function balloon_top(target,modal)
{

	var position = $(target).position();
	var top = position.top;
	// var left = position.left;
	var left = $(target).offset().left;

	if(left == 0)
	{
		left = $(target).offset().left - 180;
	}

	if(top == 0)
	{
		top = $(target).offset().top + 10;
	}

	// var bottom = top + $(target).offset().top;
	var bottom = $(target).offset().top;
	var height = $(target).outerHeight(true);
	var newBot = bottom + height + 30;

	var id= modal + " #talk-text";
	var targ = $(id);
	targ.addClass("speech-bubble-top");

	var idd = modal + " #talk-div";
	var targetDiv = $(idd);
	targetDiv.attr("style","position: absolute !important; top: " + newBot + "px !important; left: " + left + "px !important;");

};

function balloon_right(target,modal)
{

	var position = $(target).offset();
	var top = position.top;
	var left = position.left;

	var id = modal + " #talk-text";

	var targ = $(id);

	targ.addClass("speech-bubble-right");

	var idd = modal + " #talk-div";

	var targetDiv = $(idd);

	var newTop = top - 45;
	var newLeft = left - 435;

	targetDiv.attr("style","position: absolute !important; top: " + newTop + "px !important; left: " + newLeft + "px !important;");

}

function balloon_left(target,modal)
{

	var position = $(target).offset();
	var top = position.top;
	var left = position.left;
	var newLeft = left + $(target).outerWidth() + 30;
	var newTop = top - 50;

	var id = modal + " #talk-text";
	var targ = $(id);
	targ.addClass("speech-bubble-left");

	var idd = modal + " #talk-div";
	var targetDiv = $(idd);
	targetDiv.attr("style","position: absolute !important; top: " + newTop + "px !important; left: " + newLeft + "px !important;");

}

function balloon_bottom(target,modal)
{

	var position = $(target).offset();
	var top = position.top;
	var left = position.left;

	var newLeft = left - 10;

	var id = modal + " #talk-text";
	var targ = $(id);
	targ.addClass("speech-bubble-bottom");

	var idd = modal + " #talk-div";
	var targetDiv = $(idd);

	var newTop = top - 150;

	targetDiv.attr("style","position: absolute !important; top: " + newTop + "px !important; left: " + newLeft + "px !important;");

}

function balloon_noarrow(modal)
{

	// var position = $(target).offset();
	// var top = position.top;
	// var left = position.left;
	// var newLeft = left + $(target).outerWidth() + 20;
	// var newTop = top - 25;

	var id = modal + " #talk-text";
	var targ = $(id);
	targ.addClass("speech-bubble-noarrow");

	var idd = modal + " #talk-div";
	var targetDiv = $(idd);
	targetDiv.attr("style","position: absolute !important; top: 34% !important; left: 33% !important;");
	
}

function balloon_lower_right(modal) {
	var id = modal + " #talk-text";
	var targ = $(id);
	targ.addClass("speech-bubble-noarrow");

	var idd = modal + " #talk-div";
	var targetDiv = $(idd);
	targetDiv.attr("style","position: absolute !important; bottom: 50px !important; right: 100px !important;");
}