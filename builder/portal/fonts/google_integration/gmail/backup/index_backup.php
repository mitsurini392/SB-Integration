<script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="//www.smallbuilders.com.au/theodore/formbuilder/libs/js/gen.js"></script>

<link rel="stylesheet" href="../../css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="../../css/custom.css" type="text/css"></link>

<div id="page-load">
	<div class="blackout-page-load"></div>
	<div class="page-load">
		<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Please wait...
	</div>
</div>

<button id="authorize-button" style="display: none;">Authorize</button>
<button id="signout-button" style="display: none;">Sign Out</button>

<script async defer src="https://apis.google.com/js/api.js?onload=handleClientLoad" ></script>

<script type="text/javascript">
    
    var authorizeButton = document.getElementById('authorize-button');
    var signoutButton = document.getElementById('signout-button');
    var ctr = 0;
    
    function handleClientLoad() {
        gapi.load('client:auth2', initClient);
    }
    
    function initClient() {
        gapi.client.init({
            discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest"],
            clientId: '668684967896-0j8c8b8e84271hcuvl850k1pkk7drvst.apps.googleusercontent.com',
            scope: 'https://www.googleapis.com/auth/gmail.readonly' // included, separated by spaces.
        }).then(function () {
            // Listen for sign-in state changes.
            gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
            
            // Handle the initial sign-in state.
            updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
            authorizeButton.onclick = handleAuthClick;
            signoutButton.onclick = handleSignoutClick;
        });
    }
    
    function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
            gapi.client.gmail.users.getProfile({
                'userId': 'me'
            }).then(function(profile) {
                listMessages(profile.result.emailAddress, 10);    
            });
            
            
        } else {
          handleAuthClick();
        }
    }
    
    function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
    }
    
    function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
    }
    
    function listMessages(profile_id, maxresult) {
        
        var thread_id = new Array(),
            allmessages = new Array(),
            c;
    
        gapi.client.gmail.users.messages.list({
            'userId': 'me',
            'maxResults': maxresult
        }).then(function(response) {
            
            for(c = 0; c < response.result.messages.length; c++){
                
                var body = '', subject = '', to = '', from = '', date = '', microtime = '', snippet = '',
                    message_id = response.result.messages[c].id;
                
                gapi.client.gmail.users.messages.get({
                    'userId': 'me',
                    'id': message_id,
                    'format': 'full'
                }).then(function(x) {
                    
                    if(x.result.payload.body.data != undefined && x.result.payload.body.data != ''){
                        body = x.result.payload.body.data;
                    }
                    
                    if(x.result.payload.parts != undefined){
                        for(var t = 0; t < x.result.payload.parts.length; t++){
                            
                            var attachment_id = x.result.payload.parts[t].body.attachmentId,
                                attachment_filename = x.result.payload.parts[t].filename,
                                attachment_mimeType = x.result.payload.parts[t].mimeType,
                                attachment_size = x.result.payload.parts[t].body.size,
                                attachment_data = '';
                            
                            if(attachment_mimeType=='text/html' || attachment_mimeType=='text/plain'){
                                if(body == ''){
                                    body = x.result.payload.parts[t].body.data;
                                }
                                
                            } else {
                                
                                if(attachment_filename != ""){ //if attachment
                                    
                                    gapi.client.gmail.users.messages.attachments.get({
                                        'userId': 'me',
                                        'id': attachment_id,
                                        'messageId': message_id,
                                    }).then(function(attachment) {
                                        
                                        attachment_data = attachment.result.data.replace(/-/g, '+').replace(/_/g, '/');
                                        
                                        var attachment_params = {
                                            message_id: message_id,
                                            attachment_id: attachment_id,
                                            name: attachment_filename,
                                            size: attachment_size,
                                            mimetype: attachment_mimeType,
                                            data: attachment_data
                                        }
                                        
                                        $.post("attachments.php?ui=" + GetQueryStringParams("ui") + "&ci=" + GetQueryStringParams("ci"), JSON.stringify({data: attachment_params}), function(response) {
                                            
                                            var response = JSON.parse(response);
                                            //console.log(response);
                                            
                                            if(!response[0].result){
                                                pagereturn(profile_id, false);
                                            }
                                            
                                            console.log('post attachments.php');
                                        });
                                        
                                        //var link = 'data:' + mimeType + ';base64,' + attachment.result.data.replace(/-/g, '+').replace(/_/g, '/'); 
                                        //$('.page-load').append('<img src="' + link + '" style="display: block">');
                                        
                                    });
                                    
                                }
                            }
                        }
                        
                        console.log('end for loop x.result.payload.parts.length');
                        
                    }
                    
                    for(var u = 0; u < x.result.payload.headers.length; u++){
                        
                        if(x.result.payload.headers[u].name == "Subject"){
                            subject = x.result.payload.headers[u].value;
                        }
                        
                        if(x.result.payload.headers[u].name == "To"){
                            to = x.result.payload.headers[u].value;
                        }
                        
                        if(x.result.payload.headers[u].name == "From"){
                            from = x.result.payload.headers[u].value;
                        }
                        
                        if(x.result.payload.headers[u].name == "Date"){
                            date = x.result.payload.headers[u].value;
                        }
                    }
                    
                    console.log('end for loop x.result.payload.headers.length');
                    
                    thread_id = x.result.threadId;
                    microtime = x.result.internalDate;
                    snippet = x.result.snippet;
                    
                    arr_msg = {
                        profile_id: profile_id,
                        msg_thread_id: thread_id,
                        msg_message_id: x.result.id,
                        msg_body: body,
                        msg_subject: subject,
                        msg_to: to,
                        msg_from: from,
                        msg_date: date,
                        msg_microtime: microtime,
                        msg_snippet: snippet
                    };
                
                    $.post("save.php?ui=" + GetQueryStringParams("ui") + "&ci=" + GetQueryStringParams("ci"), JSON.stringify({data: arr_msg}), function(response) {
                        //console.log(response);
                        var response = JSON.parse(response);
                        
                        if(!response[0].result){
                            pagereturn(profile_id, false);
                        }
                        
                        console.log('post save.php');
                    });
                });
                
            }
            
            console.log('end for loop response.result.messages.length');
        });
   
    }
    
    function pagereturn(profile_id, status){
        if(status!= false){
            //console.log('No error');
            //window.location = "../../gmail_integration.php?ui=" + GetQueryStringParams('ui') + "&ci=" + GetQueryStringParams('ci') + "&loaded=" + jsbase64_encode(profile_id);    
        } else {
            //alert('Unexpected error encountered.');
            //window.location = "../../gmail_integration.php?ui=" + GetQueryStringParams('ui') + "&ci=" + GetQueryStringParams('ci') + "&error=1";
        }
    }
    
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