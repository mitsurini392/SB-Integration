<script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="//www.smallbuilders.com.au/theodore/formbuilder/libs/js/gen.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></link>
<link rel="stylesheet" href="../../css/custom.css" type="text/css"></link>

<div id="page-load">
	<div class="blackout-page-load"></div>
	<div class="page-load">
		<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Please wait...
	</div>
</div>

<div id="noOfResultsModal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12"><label>Plese enter the number of emails you want to retrieve from Gmail.<br/><br/></label></div>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="no_of_results" id="no_of_results" value="10" autofocus>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success btn-block" onclick="fetchEmailList()" data-dismiss="modal">Submit</button>
                    </div>
                    <div class="col-lg-12"><br/></div>
                </div>
            </div>
        </div>
    </div>
</div>

<button id="authorize-button" style="display: none;">Authorize</button>
<button id="signout-button" style="display: none;">Sign Out</button>
<input type="button" id="btn_no_of_results" data-toggle="modal" data-target="#noOfResultsModal" style="display: none;">
<input type="text" name="profile" id="profile" style="display: none;">

<script src="https://apis.google.com/js/api.js" ></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">

    window.onload = function() {
        handleClientLoad();
        
    };

    var authorizeButton = document.getElementById('authorize-button');
    var signoutButton = document.getElementById('signout-button');
    
    function handleClientLoad() {
        gapi.load('client:auth2', initClient);
    }
    
    function initClient() {
        gapi.client.init({
            discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest"],
            clientId: '668684967896-0j8c8b8e84271hcuvl850k1pkk7drvst.apps.googleusercontent.com',
            scope: 'https://www.googleapis.com/auth/gmail.readonly https://www.googleapis.com/auth/gmail.send' // included, separated by spaces.
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
                //intial load, 10 items
                $("#profile").val(profile.result.emailAddress);
                $("#page-load").hide();
                $("#no_of_results").select();
                $("#btn_no_of_results").click();
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
    
    function fetchEmailList(){
        var no_of_emails = $("#no_of_results").val().trim(),
            profile = $("#profile").val();
            
            if(no_of_emails == "" || no_of_emails <= 0){
                alert("Plese enter the number of emails you want to retrieve from Gmail.");
                $("#no_of_results").focus();
            } else {
                $("#page-load").show();
                listMessages(profile, no_of_emails);
            }
        
    }
    
    function listMessages(profile_id, maxresult) {
        gapi.client.gmail.users.messages.list({
            'userId': 'me',
            'maxResults': maxresult
        }).then(function(message_list) {
            
            var arr_message_ids = new Array(),
                msg_result_len = message_list.result.messages.length,
                a;
            
            for(a = 0; a < message_list.result.messages.length; a++){
                arr_message_ids.push(message_list.result.messages[a].id);
            }
            
            getEachMessage(profile_id, maxresult, arr_message_ids, msg_result_len);
        });
    }
    
    function getEachMessage(profile_id, maxresult, arr_message_ids, msg_result_len){
        
        var b, msg_len_ctr = 0, state_len_ctr = 0;
        
        for(b = 0; b < arr_message_ids.length; b++){
            
            gapi.client.gmail.users.messages.get({
                'userId': 'me',
                'id': arr_message_ids[b],
                'format': 'full'
            }).then(function(message) {
                
                /**
                console.log(message);
                console.log('-----');
                console.log(atob(message.result.raw.replace(/-/g, '+').replace(/_/g, '/')));
                console.log('-----');
                */
                
                var msg_id = message.result.id,
                    msg_thread_id = message.result.threadId, 
                    msg_microtime = message.result.internalDate, 
                    msg_snippet = message.result.snippet,
                    msg_raw_message_id = '',
                    msg_body = '', 
                    msg_body_mime = '',
                    msg_subject = '', 
                    msg_to = '', 
                    msg_from = '',
                    msg_reply_to = '',
                    msg_in_reply_to = '',
                    msg_date = '', 
                    payload_parts = '',
                    c, d, h;
                
                console.log(message.result.payload.headers);
                
                for(var c = 0; c < message.result.payload.headers.length; c++){
                    
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "message-id"){
                        msg_raw_message_id = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "subject"){
                        msg_subject = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "to"){
                        msg_to = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "from"){
                        msg_from = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "reply-to"){
                        msg_reply_to = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "in-reply-to"){
                        msg_in_reply_to = message.result.payload.headers[c].value;
                    }
                    
                    if(message.result.payload.headers[c].name.toLowerCase() == "date"){
                        msg_date = message.result.payload.headers[c].value;
                    }
                }
                
                if(message.result.payload.body.data != undefined && message.result.payload.body.data != ''){
                    msg_body = message.result.payload.body.data;
                    msg_body_mime += message.result.payload.mimeType + '; ';
                } else {
                    if(message.result.payload.parts != undefined){
                        var arrparts = new Array();
                        
                        payload_parts = message.result.payload.parts;
                        
                        for(d = 0; d < payload_parts.length; d++){
                            if(payload_parts[d].mimeType == 'text/html'){
                                arrparts.unshift(payload_parts[d]);
                            } else {
                                arrparts.push(payload_parts[d]);
                            }
                        }
                        
                        for(h=0; h < arrparts.length; h++){
                            if(msg_body == "" && arrparts[h].mimeType == 'text/html'){
                                msg_body = arrparts[h].body.data;
                            } else {
                                if(msg_body == ""){
                                    msg_body = arrparts[h].body.data;
                                }
                            }
                            msg_body_mime += arrparts[h].mimeType + '; ';
                        }
                    }
                }


                arr_msg = {
                    profile_id: profile_id,
                    msg_message_id: msg_id,
                    msg_raw_message_id: msg_raw_message_id,
                    msg_thread_id: msg_thread_id,
                    msg_body: msg_body,
                    msg_body_mime: msg_body_mime,
                    msg_subject: msg_subject,
                    msg_to: msg_to,
                    msg_from: msg_from,
                    msg_reply_to: msg_reply_to,
                    msg_in_reply_to: msg_in_reply_to,
                    msg_date: msg_date,
                    msg_microtime: msg_microtime,
                    msg_snippet: msg_snippet
                };
                
                
                $.post("save.php?ui=" + GetQueryStringParams("ui") + "&ci=" + GetQueryStringParams("ci"), JSON.stringify({data: arr_msg}), function(response) {
                
                    var response = JSON.parse(response);
                    
                    if(!response[0].result){
                        msg_len_ctr = 0;
                        onError(response[0].message);
                    } else {
                        //success
                        msg_len_ctr++;
                        
                        if(response[0].state == 1){
                            state_len_ctr++;
                        }
                        
                        getMessageAttachment(profile_id, maxresult, msg_id, payload_parts, msg_len_ctr, state_len_ctr, msg_result_len);    
                    }
                    
                    //console.log('post save.php');
                
                //chkCounts(msg_len_ctr, maxresult);    
                });
                
                
            });
        }
        
        
    }
    
    function getMessageAttachment(profile_id, maxresult, message_id, payload_parts, msg_len_ctr, state_len_ctr, msg_result_len){
        
            var atch_id = '',
                atch_u_id = '',
                atch_filename = '',
                atch_mimeType = '',
                atch_size = '',
                atch_data = '',
                f, g;
            
            for(f = 0; f < payload_parts.length; f++){
                var attachment_params = {};
                
                atch_id = payload_parts[f].body.attachmentId;
                atch_filename = payload_parts[f].filename;
                atch_mimeType = payload_parts[f].mimeType;
                atch_size = payload_parts[f].body.size;
                
                if(atch_filename != ''){
                    //console.log(atch_filename);
                    //console.log(payload_parts[f].headers);
                    for(g = 0; g < payload_parts[f].headers.length; g++){
                        
                        if(payload_parts[f].headers[g].name == 'X-Attachment-Id'){
                            atch_u_id = payload_parts[f].headers[g].value;
                            break;
                        }
                    }
                    
                    saveAttachments(atch_id, message_id, atch_u_id, atch_filename, atch_size, atch_mimeType);
                    
                }
            }
        
        chkCounts(profile_id, msg_len_ctr, maxresult, state_len_ctr, msg_result_len);
        
        
    }
    
    
    function saveAttachments(atch_id, message_id, atch_u_id, atch_filename, atch_size, atch_mimeType){
        
        gapi.client.gmail.users.messages.attachments.get({
            'userId': 'me',
            'id': atch_id,
            'messageId': message_id,
        }).then(function(attachment) {

            atch_data = attachment.result.data.replace(/-/g, '+').replace(/_/g, '/');
            
            attachment_params = {
                message_id: message_id,
                attachment_id: atch_id,
                attachment_u_id: atch_u_id,
                name: atch_filename,
                size: atch_size,
                mimetype: atch_mimeType,
                data: atch_data
            }
            
            $.post("attachments.php?ui=" + GetQueryStringParams("ui") + "&ci=" + GetQueryStringParams("ci"), JSON.stringify({data: attachment_params}), function(response) {
                var response = JSON.parse(response);
                //console.log(response);
                if(!response[0].result){
                    msg_len_ctr = 0;
                    onError(response[0].message);
                }
                
            });
            
        });
    }
    
    function chkCounts(profile_id, msg_len_ctr, maxresult, state_len_ctr, msg_result_len){
        /**
        console.log('message len: ' + msg_len_ctr);
        console.log('message_result len: ' + msg_result_len);
        console.log('max result: ' + maxresult);
        console.log('state len: ' + state_len_ctr);
        */
        
        if(state_len_ctr == maxresult){ //all items are existing
            //console.log('all items are existing. call new set.');
            
            listMessages(profile_id, maxresult + 10);
            
        } else {
            
            //loop will continue continue until not same
            
            if(msg_len_ctr == msg_result_len){
                setTimeout(function() {
                    pagereturn(profile_id);
                }, 2000);
            }
        }
        
    }
    
    function onError(msg){
        alert('Unexpected error encountered. ' + msg);
        window.location = "../../gmail_integration.php?ui=" + GetQueryStringParams('ui') + "&ci=" + GetQueryStringParams('ci') + "&error=1";
    }
    
    function pagereturn(profile_id){
        //console.log('returning...');
        window.location = "../../gmail_integration.php?ui=" + GetQueryStringParams('ui') + "&ci=" + GetQueryStringParams('ci') + "&loaded=" + jsbase64_encode(profile_id);    
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