function navigate($value, $param ){
		//window.location.href='';
		$.ajax({
            url: './scripts/navigate.php',
            type      : 'post',
			  dataType : 'html',
            data: { 'action' :  $value,
            		'param' : $param},
            success: function(data) {
          	  //$('.bottom').append('<PRE>'+data+'</PRE>')
            },
            complete: function(data) {
            	window.location.href='';
            }
           });
	} 

function cancel(){
		//window.location.href='';
		$.ajax({
            url: './scripts/cancel.php',
            type      : 'post',
			  dataType : 'html',
            data: { 'action' :  'cancel' },
            success: function(data) {
          	  //$('.bottom').append('<PRE>'+data+'</PRE>')
            },
            complete: function(data) {
            	window.location.href='';
            }
           });
	} 