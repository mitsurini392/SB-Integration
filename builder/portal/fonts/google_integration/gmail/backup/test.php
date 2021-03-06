<!doctype html>
<html>
<head>
    <title>Gmail Integration</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <style>
    iframe { width: 100%; border: 0; min-height: 80%; height: 600px; display: flex; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gmail Integration</h1>
        <a href="#compose-modal" data-toggle="modal" id="compose-button" class="btn btn-primary pull-right hidden">Compose</a>
        <button id="authorize-button" class="btn btn-primary hidden">Authorize</button>

        <table class="table table-striped table-inbox hidden">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

<!-- Modal Compose Message

<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<h4 class="modal-title">Compose</h4>
</div>
<form onsubmit="return sendEmail();">
<div class="modal-body">
<div class="form-group">
<input type="email" class="form-control" id="compose-to" placeholder="To" required />
</div>

<div class="form-group">
<input type="text" class="form-control" id="compose-subject" placeholder="Subject" required />
</div>

<div class="form-group">
<textarea class="form-control" id="compose-message" placeholder="Message" rows="10" required></textarea>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" id="send-button" class="btn btn-primary">Send</button>
</div>
</form>
</div>
</div>
</div>
-->

    <div class="modal fade" id="reply-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form onsubmit="return sendReply();">
                <input type="hidden" id="reply-message-id" />
                <input type="hidden" id="thread-id" />
                <input type="hidden" id="in_reply_to" />
                <input type="hidden" id="references" />
                
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reply</h4>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="reply-to" disabled />
                    </div>
                    
                    <div class="form-group">
                        <input type="text" class="form-control disabled" id="reply-subject" disabled />
                    </div>
                    
                    <div class="form-group">
                        <textarea class="form-control" id="reply-message" placeholder="Message" rows="10" required></textarea>
                    </div>
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="reply-button" class="btn btn-primary">Send</button>
                </div>
                
                </form>
            </div>
        </div>
    </div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    var clientId = '668684967896-0j8c8b8e84271hcuvl850k1pkk7drvst.apps.googleusercontent.com',
        apiKey = 'AIzaSyCIzE-nxVDUZC8JURdDoMzp-jRd9a8gAGM',
        scopes = 'https://www.googleapis.com/auth/gmail.readonly ' + 'https://www.googleapis.com/auth/gmail.send';

    function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth, 1);
    }

    function checkAuth() {
        gapi.auth.authorize({
            client_id: clientId,
            scope: scopes,
            immediate: true
        }, handleAuthResult);
    }

    function handleAuthClick() {
        gapi.auth.authorize({
            client_id: clientId,
            scope: scopes,
            immediate: false
        }, handleAuthResult);
        return false;
    }

    function handleAuthResult(authResult) {
        if(authResult && !authResult.error) {
            loadGmailApi();
            $('#authorize-button').remove();
            $('.table-inbox').removeClass("hidden");
            $('#compose-button').removeClass("hidden");
        } else {
            $('#authorize-button').removeClass("hidden");
            $('#authorize-button').on('click', function(){
                handleAuthClick();
            });
        }
    }

    function loadGmailApi() {
        gapi.client.load('gmail', 'v1', displayInbox);
    }

    function displayInbox() {
        var request = gapi.client.gmail.users.messages.list({
            'userId': 'me',
            'labelIds': 'INBOX',
            'maxResults': 10
        });
        
        request.execute(function(response) {
            $.each(response.messages, function() {
                var messageRequest = gapi.client.gmail.users.messages.get({
                    'userId': 'me',
                    'id': this.id
                });
                messageRequest.execute(appendMessageRow);
            });
        });
    }

    function appendMessageRow(message) {
        console.log(message);
        
        var from = getHeader(message.payload.headers, 'From'),
            subject = getHeader(message.payload.headers, 'Subject'),
            date = getHeader(message.payload.headers, 'Date'),
            in_reply_to = message.id,
            references = getHeader(message.payload.headers, 'References'),
            thread_id = message.threadId;
            

        $('.table-inbox tbody').append(
        '<tr>\
        <td>'+ from +'</td>\
        <td>\
        <a href="#message-modal-' + message.id + '" data-toggle="modal" id="message-link-' + message.id+'">' + subject + '</a>\
        </td>\
        <td>'+ date +'</td>\
        </tr>');
        
        var reply_to = (getHeader(message.payload.headers, 'Reply-to') !== '' ? getHeader(message.payload.headers, 'Reply-to') : getHeader(message.payload.headers, 'From')).replace(/\"/g, '&quot;');
        var reply_subject = getHeader(message.payload.headers, 'Subject').replace(/\"/g, '&quot;');
        
        $('body').append(
        '<div class="modal fade" id="message-modal-' + message.id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
        <div class="modal-dialog modal-lg">\
        <div class="modal-content">\
        <div class="modal-header">\
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
        <h4 class="modal-title" id="myModalLabel">' + getHeader(message.payload.headers, 'Subject') + '</h4>\
        </div>\
        <div class="modal-body">\
        <iframe id="message-iframe-'+message.id+'" srcdoc="<p>Loading...</p>">\
        </iframe>\
        </div>\
        <div class="modal-footer">\
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
        <button type="button" class="btn btn-primary reply-button" data-dismiss="modal" data-toggle="modal" data-target="#reply-modal"\
        onclick="fillInReply(\
        \''+reply_to+'\', \
        \''+reply_subject+'\', \
        \''+getHeader(message.payload.headers, 'Message-ID')+'\', \
        \''+thread_id+'\', \
        \''+in_reply_to+'\', \
        \''+references+'\' \
        );"\
        >Reply</button>\
        </div>\
        </div>\
        </div>\
        </div>');
        
        $('#message-link-'+message.id).on('click', function(){
            var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
            $('body', ifrm).html(getBody(message.payload));
        });
    }

    function sendEmail() {
        $('#send-button').addClass('disabled');
        
        sendMessage({
            'To': $('#compose-to').val(),
            'Subject': $('#compose-subject').val()
        }, $('#compose-message').val(), composeTidy);
        
        return false;
    }

    function composeTidy(){
        $('#compose-modal').modal('hide');
        $('#compose-to').val('');
        $('#compose-subject').val('');
        $('#compose-message').val('');
        $('#send-button').removeClass('disabled');
    }

    function sendReply(){
        $('#reply-button').addClass('disabled');
    
        sendMessage({
            'To': $('#reply-to').val(),
            'Subject': $('#reply-subject').val(),
            'In-Reply-To': $('#in_reply_to').val(),
            'References' : $('#references').val(),
            'From' : "adrian.silva@lophils.com",
            'threadId' : $('#thread-id').val()
        }, $('#reply-message').val(), replyTidy);
        
        return false;
    }

    function replyTidy(){
        $('#reply-modal').modal('hide');
        $('#reply-message').val('');
        $('#reply-button').removeClass('disabled');
    }

    function fillInReply(to, subject, message_id, thread_id, in_reply_to, references){
        $('#reply-to').val(to);
        $('#reply-subject').val(subject);
        $('#reply-message-id').val(message_id);
        $('#thread-id').val(thread_id);
        $('#in_reply_to').val(message_id);
        $('#references').val(references);
        
    }

    function sendMessage(headers_obj, message, callback){
        var email = "Content-Type: text/plain; charset=\"UTF-8\"\r\n" +
          "MIME-Version: 1.0\r\n" +
          "Content-Transfer-Encoding: 7bit\r\n";
    
        for(var header in headers_obj)
            email += header += ": "+headers_obj[header]+"\r\n";
            email += "\r\n" + message;
    
        console.log(email);
        var sendRequest = gapi.client.gmail.users.messages.send({
            'userId': 'me',
            'resource': {
                'raw': window.btoa(email).replace(/\+/g, '-').replace(/\//g, '_')
            }
        });
    
        return sendRequest.execute(callback);
    }

    function getHeader(headers, index) {
        var header = '';
        $.each(headers, function(){
            if(this.name.toLowerCase() === index.toLowerCase()){
                header = this.value;
            }
        });
        return header;
    }

    function getBody(message) {
        var encodedBody = '';
        if(typeof message.parts === 'undefined'){
            encodedBody = message.body.data;
        } else {
            encodedBody = getHTMLPart(message.parts);
        }
        
        encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
        return decodeURIComponent(escape(window.atob(encodedBody)));
    }

    function getHTMLPart(arr) {
        for(var x = 0; x <= arr.length; x++){
            if(typeof arr[x].parts === 'undefined'){
                if(arr[x].mimeType === 'text/html'){
                    return arr[x].body.data;
                }
            } else {
                return getHTMLPart(arr[x].parts);
            }
        }
        return '';
    }
    
</script>
<script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
</body>
</html>