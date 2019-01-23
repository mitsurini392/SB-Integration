var secret = '6LdTtR0TAAAAANuHSJbkihvkC5sKJXebyBRLS1ZW';
var response = '6LdTtR0TAAAAAKUNfqy9bKx4Mr0jklTbDpCqrYJY';

$(document).ready(function() {

if($(".sigPad").length !== 0){
var options = {
	        defaultAction: 'drawIt',
        	drawOnly: false,
	        lineTop: 170,
        	lineMargin: 30,
	        penColour: '#999999'
	};
	$('.sigPad').signaturePad(options);
}
});

$('.number').bind('keypress', function(event) {
    var regex = new RegExp("^[0-9\-\.\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    var charCode = event.which;
    if (charCode === 0) {
        return;
    } else if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
$('.telephone').bind('keypress', function(event) {
    var regex = new RegExp("[0-9\-\(\)\+\ ]+");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    var charCode = event.which;
    if (charCode === 0) {
        return;
    } else if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
if ($("#trig").css("display") == "block" || $("#trig").css("display") == "inline-block") {
    $(".pad").removeAttr("width");
    $(".pad").removeAttr("height");
}

//Start - Date & Time Format
if($(".datetime_picker").length !== 0){
	$('.datetime_picker').datetimepicker({format: 'd-m-Y H:i', step:1, validateOnBlur:false});
	$('.datetime_picker').off('mousewheel.disableScroll');
  
}

if($(".date_picker").length !== 0){
	$('.date_picker').datetimepicker({timepicker: false, format: 'd-m-Y', scrollMonth:false, scrollInput:false, validateOnBlur:false});
}


if($(".time_picker").length !== 0){	
	$('.time_picker').datetimepicker({datepicker: false,format: 'H:i', step:30, validateOnBlur:false});
	$('.time_picker').off('mousewheel.disableScroll'); 
}
//End - Date & Time Format


function jsbase64_encode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, ac = 0, enc = '', tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function moneytrim(obj){
	var trim_obj = obj.trim();
	trim_obj = trim_obj.split("$").join("");
	trim_obj = trim_obj.split(",").join("");
	return trim_obj;
}

function formatDate(date) {
  var monthNames = [
    "Jan", "Feb", "Mar",
    "Apr", "May", "Jun", "Jul",
    "Aug", "Sep", "Oct",
    "Nov", "Dec"
  ];

  var day = date.getDate();
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}


function formatDateTime(date) {
  var monthNames = [
    "Jan", "Feb", "Mar",
    "Apr", "May", "Jun", "Jul",
    "Aug", "Sep", "Oct",
    "Nov", "Dec"
  ];

  var day = date.getDate();
  var monthIndex = date.getMonth();
  var year = date.getFullYear();
    var h = date.getHours();
    var m = date.getMinutes();

  return day + ' ' + monthNames[monthIndex] + ' ' + year + ' ' + formatTime(date);
}

function formatTime(date){
    
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
    

  minutes = minutes < 10 ? '0' + minutes : minutes;
    hours = hours < 10 ? '0' + hours : hours;     
    
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return strTime;

}

function isBase64(str) {
    try {
        return btoa(atob(str)) == str;
    } catch (err) {
        return false;
    }
}



Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 
 function _formSearch(){
$("#srch-term").animate({width: "100%"});
}
function _formSearchBlur(){
$("#srch-term").animate({width: "50%"});
}