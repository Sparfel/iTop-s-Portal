var clientMsgs = [];
 
jQuery.noConflict()

Chat = {
    doIt: function(type) {
    	jQuery.ajax({
            type: 'post',
            url: '/chat/chat/viewcustomerchat',
            data: {
                message: jQuery('#chatMessage').val(),
                sessId: jQuery('#sessId').val(),
                user:  jQuery('#user').val(),
                org_id: jQuery('#org_id').val(),
                organization: jQuery('#organization').val(),
                role: type
            },
            success: function() {
            	jQuery('#chatContent').append($('#user').val() + ' : ' + $('#chatMessage').val() + '\n');
            	jQuery('#chatMessage').val('');
            },
            error: function(err) {
                alert(err.status);
            }
        });
    },
    getMessages: function() {
    	jQuery.ajax({
            type: 'post',
            url: '/chat/chat/getmessages',
            data: {
                sessId: $('#sessId').val(),
                user: $('#user').val(),
                org_id: $('#org_id').val(),
                organization: $('#organization').val()
            },
            datatype: 'json',
            success: function(result) {
                
            	jQuery('#chatContent').text('');
                clientMsgs = jQuery.parseJSON(result);
                jQuery(clientMsgs).each(function(k, v){
                    if (v.role == 'client')
                    	jQuery('#chatContent').append(v.user +' : ' + v.message + '\n');
                    else if (v.role == 'admin')
                    	jQuery('#chatContent').append(v.user +' : ' + v.message + '\n');
                });
            }
        });
    }
};
 
jQuery(document).ready(function(){
    // once every 5 seconds
    setInterval(function(){
        // set timeout to avoid throttling
        setTimeout(function(){
            Chat.getMessages();
        }, 100)
    }, 5000);
});