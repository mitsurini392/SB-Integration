(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/meot4r7k';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()

// Live Chat
function intercom_loadinfo(params){
    
    window.intercomSettings = {
        app_id: "meot4r7k",
        name: params.name,
        email: params.email,
        user_id: params.user_id,
        "client_id": params.client_id,
        "client_name": params.client_name,
        "client_type": params.client_type,
        user_hash: params.user_hash
    };

}