<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Range");
header("Access-Control-Expose-Headers: Cache-Control, Content-Encoding, Content-Range");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Choose file from Google Drive</title>
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
	var developerKey = 'AIzaSyCIzE-nxVDUZC8JURdDoMzp-jRd9a8gAGM',
		clientId = "668684967896-p0ccaojil2hm11v08lgvubssemmurdm7.apps.googleusercontent.com",
		appId = "668684967896",
		scope = ['https://www.googleapis.com/auth/drive'],
		pickerApiLoaded = false,
		oauthToken;

	function loadPicker() { // Use the Google API Loader script to load the google.picker script.
		gapi.load('auth', {'callback': onAuthApiLoad});
		gapi.load('picker', {'callback': onPickerApiLoad});
	}

	function onAuthApiLoad() {
		window.gapi.auth.authorize({
			'client_id': clientId,
			'scope': scope,
			'immediate': false
		},
		handleAuthResult);	
	}

	function onPickerApiLoad() {
		pickerApiLoaded = true;
		createPicker();
	}

	function handleAuthResult(authResult) {
		if (authResult && !authResult.error) {
			oauthToken = authResult.access_token;
			createPicker();
		}
	}

	function createPicker() { // Create and render a Picker object for searching images.
		if (pickerApiLoaded && oauthToken) {
			var view = new google.picker.View(google.picker.ViewId.DOCS),
				mime_img = "image/png, image/jpeg,",
				mime_txt = "text/plain, text/csv, application/pdf,",
				mime_folder = "application/vnd.google-apps.folder,",
				mime_ms_doc = "application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword,",
				mime_ms_exl = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,",
				mime_ms_ppt = "application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation";
				
			//view.setMimeTypes(mime_img + mime_txt + mime_folder + mime_ms_doc + mime_ms_exl + mime_ms_ppt);
			var picker = new google.picker.PickerBuilder()
				.enableFeature(google.picker.Feature.NAV_HIDDEN)
				.enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
				.setAppId(appId)
				.setOAuthToken(oauthToken)
				.addView(view)
				.addView(new google.picker.DocsUploadView())
				.setDeveloperKey(developerKey)
				.setCallback(pickerCallback)
				.build();
			 picker.setVisible(true);
		}
	}
	
	
	function pickerCallback(data) { // A simple callback implementation.
		if (data.action == google.picker.Action.PICKED) {
		$("#page-load").show();

		var google_data = [];
		for(var x=0; x < data.docs.length; x++){

			google_data.push({
				f_id: data.docs[x].id,
				f_name: data.docs[x].name,
				f_type: data.docs[x].mimeType,
				f_size: bytesToSize(data.docs[x].sizeBytes),
				f_url: data.docs[x].url,
				f_token: oauthToken
			});
		}
		
			var page = "https://www.smallbuilders.com.au/builder/portal/google_integration/drive/multiple_savefile/transferfile.php";
		
			$.ajax({
				type: "POST",
				url: page + "?ui=" + GetQueryStringParams('ui'),
				headers: {'Access-Control-Allow-Origin': '*'},
				data: JSON.stringify(google_data),
				success: function(successdata){
					
					if(successdata!=''){
						successdata = successdata.replace('[','',successdata);
						successdata = successdata.replace(']','',successdata);
						// var d = window.opener.document.getElementById('tr1_' + GetQueryStringParams('fni')).rowIndex;
						// d = d - 1;
						// window.opener.document.getElementById('body_table1').deleteRow(d);
						window.opener.document.getElementById('gdrivefiles').value = successdata;
						window.opener.document.getElementById('btnSaveGDrivefiles').click();
						window.close();
					} else {
						alert("Unable to transfer file to Google Drive.");
						window.close();
						}
				},
				error: function(errdata){
					console.log(errdata);
				}
			});
		} else if (data.action == google.picker.Action.CANCEL) {
			window.close();
		}
	}
	
</script>
<!-- The Google API Loader script. -->
<script type="text/javascript" src="https://apis.google.com/js/api.js?onload=loadPicker"></script>
<script type="text/javascript">
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
	
	function bytesToSize(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return '0 Byte';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
</script>
</body>
</html>