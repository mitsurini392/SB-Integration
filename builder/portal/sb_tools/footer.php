<?php

    include("sb_tools/contact_integration.php");
    include("sb_tools/feature_selection.php");
?>
<script>
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
            user_hash: params.user_hash,
			custom_launcher_selector: '.open_intercom'
        };
    
    }
var ic_params = {
        name: "<?php echo $_user_firstname . ' ' . $_user_lastname; ?>",
        email: "<?php echo $_user_email; ?>",
        user_id: "<?php echo $dec_ui; ?>",
        client_id: "<?php echo $dec_ci; ?>",
        client_name: "<?php echo $_company_business_name; ?>",
        client_type: "<?php echo $_user_clienttype; ?>",
        user_hash: "<?php echo hash_hmac('sha256', $dec_ui, 'oVK1v9nn2TnNiWtYBDnzmy80FC4G64qHItRerVSB'); ?>"
};

//live chat function    
intercom_loadinfo(ic_params);
</script>

<!-- Begin Inspectlet Embed Code -->
<script type="text/javascript" id="inspectletjs">
window.__insp = window.__insp || [];
__insp.push(['wid', 1075637523]);
__insp.push(['identify', "<?php echo $_user_firstname ." ".$_user_lastname; ?>"]);
__insp.push(['tagSession', {email: "<?php echo $_user_email; ?>", userid: "<?php echo $dec_ui; ?>", businessname: "<?php echo $_company_business_name; ?>", clientid: "<?php echo $dec_ci; ?>", usertype: "<?php echo $_user_clienttype; ?>"}]);
(function() {
function ldinsp(){if(typeof window.__inspld != "undefined") return; window.__inspld = 1; var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); };
setTimeout(ldinsp, 500); document.readyState != "complete" ? (window.attachEvent ? window.attachEvent('onload', ldinsp) : window.addEventListener('load', ldinsp, false)) : ldinsp();
})();
</script>
<!-- End Inspectlet Embed Code -->

<!-- start demo prompt -->
<script type="text/javascript">
    function highlightElement(elementName, options) {
        this.elementName = elementName;
        this.scroll = true;
        this.zindex;
        this.position;
        this.clickable = null;

        if (options != null) {
            if (options.scroll != null) {
                this.scroll = options.scroll;
            }
            if (options.clickable != null) {
                this.clickable = options.clickable;
            }  
        }

        this.highlight = function() {
            this.zindex = $(this.elementName).css('z-index');
            this.position = $(this.elementName).css('position');

            $(this.elementName).css('position', 'relative');
            $(this.elementName).css('z-index', '992');

            elementPosition = $(this.elementName).offset();
            elementWidth = $(this.elementName).outerWidth();
            elementHeight = $(this.elementName).outerHeight();

            if (this.scroll) {
                $('html, body').animate({
                    scrollTop: elementPosition.top - elementHeight,
                }, 1000);
            }
            if (this.clickable != null) {
                clickablePosition = $(this.clickable).offset();
                clickableWidth = $(this.clickable).outerWidth();
                clickableHeight = $(this.clickable).outerHeight();

                //(($('body').width() - (clickablePosition.left + clickableWidth)) + clickableWidth)
                $('html').prepend('<div id="hideButtonFor'+this.elementName.substr(1)+'_right" style="opacity: 0; z-index: 993; width: 100%; height: '+$('body').height()+'px; position: absolute; left: '+(clickablePosition.left + clickableWidth)+'px; border-radius: 5px;"></div>');
                $('html').prepend('<div id="hideButtonFor'+this.elementName.substr(1)+'_left" style="opacity: 0; z-index: 993; width: '+clickablePosition.left+'px; height: '+$('body').height()+'px; position: absolute; border-radius: 5px;"></div>');
                $('html').prepend('<div id="hideButtonFor'+this.elementName.substr(1)+'_bottom" style="opacity: 0; z-index: 993; width: '+$('body').width()+'px; height: '+elementHeight+'px; position: absolute; top: '+(clickablePosition.top + clickableHeight)+'px; border-radius: 5px;"></div>');
                $('html').prepend('<div id="hideButtonFor'+this.elementName.substr(1)+'_top" style="opacity: 0; z-index: 993; width: '+$('body').width()+'px; height: '+clickablePosition.top+'px; position: absolute; top: 0px; border-radius: 5px;"></div>');
            }

            $('html').prepend('<div id="highlightFor'+this.elementName.substr(1)+'" style="background-color: white; width: '+(elementWidth + 20)+'px; height: '+(elementHeight + 20)+'px; z-index: 991; position: absolute; top: '+(elementPosition.top - 10)+'px; left: '+(elementPosition.left - 10)+'px; border-radius: 5px;"></div>');
            $('html').prepend('<div id="canvasFor'+this.elementName.substr(1)+'" style="height: 100%; width: 100%; position: fixed; top: 0; left: 0; z-index: 990; background-color: rgba(0, 0, 0, .5);"></div>');
        }
        this.removeHighlight = function() {
            $('#highlightFor'+this.elementName.substr(1)).remove();
            $('#canvasFor'+this.elementName.substr(1)).remove();

            $(this.elementName).css('position', this.position);
            $(this.elementName).css('z-index', this.zindex);

            if (this.clickable != null) {
                $('#hideButtonFor'+this.elementName.substr(1)+'_right').remove();
                $('#hideButtonFor'+this.elementName.substr(1)+'_left').remove();
                $('#hideButtonFor'+this.elementName.substr(1)+'_bottom').remove();
                $('#hideButtonFor'+this.elementName.substr(1)+'_top').remove();
            }
        }
        this.setScroll = function(scroll) {
            this.scroll = scroll;
        }
    }

    function demoDataCaptureFunnel(onboarding = "N/A", phase = "N/A", page = "N/A", step = "N/A", action = "N/A") {
        var formdata = {
            userid: "<?php echo base64_encode($dec_ui); ?>",
            onboarding: onboarding,
            phase: phase,
            page: page,
            step: step,
            action: action,
        };

        $.ajax({
            type: "POST",
            url: "sb_tools/ajaxDataCapture.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            },
            complete: function(data) {
                console.log(data);
            },
        });
    }

    jQuery.fn.centerCustomModal = function (modalWidth) {
        $(this).find('.modal-dialog').css("margin-top", Math.max(0, (($(window).height() - modalWidth) / 2) + $(window).scrollTop()) + "px");
        return $(this);
    }
</script>
<!-- end demo prompt -->

<?php

$showTraining_query = "Select client_type from cs_users where user_id='".$dec_ui."'";
$showTraining = mysqli_query($csportal_con, $showTraining_query);
$fetchShowTraining = mysqli_fetch_array($showTraining);
extract($fetchShowTraining);
if($client_type=="free_user")
{
    echo $show = '<link href="'.asset_host().'/theodore/formbuilder/libs/calendly/dashboard/widget.css" rel="stylesheet">
    <script src="'.asset_host().'/theodore/formbuilder/libs/calendly/dashboard/widget.js" type="text/javascript"></script>
    <script type="text/javascript">Calendly.initBadgeWidget({url: "https://calendly.com/john-delacruz/small-builders-free-account-training", text: "I Want Training", color: "#00a2ff", branding: false});</script>';
    $description = "I Want Training";
}else{
    echo $show = "";
    $description = "I Want Training";
}

if($client_type=="owner_admin")
{
    echo $showadmin = '<link href="'.asset_host().'/theodore/formbuilder/libs/calendly/dashboard/widget.css" rel="stylesheet">
    <script src="'.asset_host().'/theodore/formbuilder/libs/calendly/dashboard/widget.js" type="text/javascript"></script>
    <script type="text/javascript">Calendly.initBadgeWidget({url: "https://calendly.com/john-delacruz/small-builders-business-account-training", text: "I Want Training", color: "#00a2ff", branding: false});</script>';
}
else
{
    echo $showadmin = "";
}
?>