<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>Google Calendar</title>
	<script type="text/javascript" src="//www.smallbuilders.com.au/theodore/formbuilder/libs/js/jquery.js"></script>
	<link rel="stylesheet" href="//www.smallbuilders.com.au/builder/portal/css/custom.css" type="text/css"></link>
	<link rel="stylesheet" href="//www.smallbuilders.com.au/builder/portal/css/bootstrap.min.css" type="text/css"></link>
</head>

<body>
	<div id="page-load" style="display:none">
		<div class="blackout-page-load"></div>
		<div class="page-load">
			<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Please wait...
		</div>
	</div>
	
	
<script type="text/javascript">	
	var CLIENT_ID = '668684967896-pc2lgfk60sqf21h0m5se3rqjd5k3m6a6.apps.googleusercontent.com';
	var SCOPES = ["https://www.googleapis.com/auth/calendar"];

	function checkAuth() {
		gapi.auth.authorize({
			'client_id': CLIENT_ID,
			'scope': SCOPES.join(' '),
			'immediate': true
		}, handleAuthResult);
	}

	function handleAuthResult(authResult) {
		if (authResult && !authResult.error) {// Hide auth UI, then load client library.
			loadCalendarApi();
		} else {
				// Show auth UI, allowing the user to initiate authorization by
				// clicking authorize button.
				handleAuthClick();
			}
	}

	function handleAuthClick() {
		gapi.auth.authorize({
			client_id: CLIENT_ID, scope: SCOPES, immediate: false
		}, handleAuthResult);
		return false;
	}

	function loadCalendarApi() {
		gapi.client.load('calendar', 'v3', newEvents);
	}

	function newEvents() {
	
		var pages = GetQueryStringParams('page');
		if(pages==1){
			url = 'https://www.smallbuilders.com.au/builder/portal/_m.index_notes.php';
		}else if(pages==2){
			url = 'https://www.smallbuilders.com.au/builder/portal/demo.index_notes.php';
		}else if(pages==3){
			url = 'https://www.smallbuilders.com.au/builder/portal/demo.m.index_notes.php';
		}else{
			url = 'https://www.smallbuilders.com.au/builder/portal/index_notes.php';
		}
		
		var submission_id = GetQueryStringParams('id');
		$.get("getNote.php?id=" + submission_id, function(data, status){
			var obj = JSON.parse(data),
				submitted_by = obj[0].submitted_by,
				project_name = obj[0].project_name,
				notes = obj[0].notes,
				due_date = obj[0].due_date,
				persons = obj[0].person_responsible,
				location = obj[0].location,
				stime = obj[0].start_time,
				etime = obj[0].end_time;
				
			if(stime != '' && etime != ''){
				s_due_date = {'dateTime': due_date + 'T' + stime, 'timeZone': 'Australia/Sydney'};
				e_due_date = {'dateTime': due_date + 'T' + etime, 'timeZone': 'Australia/Sydney'};
			} else {
				s_due_date = {'date': due_date};
				e_due_date = {'date': due_date};
				}
				
			//console.log(obj);	
			var event = {
				'summary': project_name + ' - ' + notes.substring(0,20) + '...',
				'description': notes,
				'location': location,
				'start': s_due_date,
				'end': e_due_date,
				'sendNotifications':true,
				//'recurrence': ['RRULE:FREQ=DAILY;COUNT=2'],
				'attendees': persons,
				'reminders': {
					'useDefault': false,
					'overrides': [
						{'method': 'email', 'minutes': 10}
					]
				}
			};

			var request = gapi.client.calendar.events.insert({
				'calendarId': 'primary',
				'resource': event
			});

			//document.write(JSON.stringify(event));
			request.execute(function(event){
			var ret = 0,
				urlsub = url;
				if(event.htmlLink !="" && event.htmlLink != null && event.htmlLink != undefined){
					ret = 1;
				}
			console.log(event);
			window.location = urlsub + '?ui='+ GetQueryStringParams('ui') +'&ci='+ GetQueryStringParams('ci') +'&s=' + ret;	
			});
		});

	}

	function appendPre(message) {
		var pre = document.getElementById('output');
		var textContent = document.createTextNode(message + '\n');
		pre.appendChild(textContent);
	}

	/**
	var request = gapi.client.calendar.events.list({
		'calendarId': 'primary',
		'timeMin': (new Date()).toISOString(),
		'showDeleted': false,
		'singleEvents': true,
		'maxResults': 10,
		'orderBy': 'startTime'
	});

	request.execute(function(resp) {
		var events = resp.items;
		appendPre('Upcoming events:');

		if (events.length > 0) {
			for (i = 0; i < events.length; i++) {
				var event = events[i];
				var when = event.start.dateTime;
				if (!when) {
					when = event.start.date;
				}
				appendPre(event.summary + ' (' + when + ')')
			}
		} else {
			appendPre('No upcoming events found.');
		}
		
	});
	*/
	
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
</script>

<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>



<!--
<div id="authorize-div" style="display: none">
<span>Authorize access to Google Calendar API</span>
<button id="authorize-button" onclick="">
Authorize
</button>
</div>
<pre id="output"></pre>
-->
</body>
</html>