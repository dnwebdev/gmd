jQuery(document).ready(function(){
	var interval;
	load_notif();
	
	

	jQuery(window).on('focus',function() {
        interval = setInterval(function(){
        	//console.log('focuson');
        	load_notif();
        }, 30000);
    });    

    jQuery(window).on('blur',function() {
        //console.log('blurr');
        clearInterval(interval);
    }); 

	function load_notif(){
		
		jQuery.getJSON("/company/my_notification", function(result){

			if(result.data.notification.length > 0){
				jQuery('.notif-item').remove();
				div = "";
				jQuery.each(result.data.notification, function(i, field){
					div += '<li class="notif-item">';
						div += '<a href="/company/notification/'+field.id+'"><i class="mdi-action-add-shopping-cart"></i> '+field.description+'</a>';
						div += '<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">'+field.created_at+' ago</time>';
					div += '</li>';
		            
		        });
		        jQuery('.notification-badge').remove();
		        jQuery('#notif_badge').append('<small class="notification-badge">'+result.data.notification.length+'</small>');
				jQuery('#notifications-dropdown').append(div);
			}
			else{
				jQuery('.notification-badge').remove();
			}
			/*
			jQuery(window).on('blur',function() {
		        clearInterval(interval);
		    });*/
	        
	    });

	    
	}
});

