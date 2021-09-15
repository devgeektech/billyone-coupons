/* jQuery(window).load(function($){




}); */


function showVideo(vid){

	//console.log("Hello Krishna "+ vid);
		
	jQuery('#show_coupon').modal('show');


	jQuery('#validate_coupon').on('click', function(){


		var coupon_code_input = jQuery('#coupon_code').val();
        
        
        jQuery.ajax({          
          url: my_ajax_url,
          data: { 'action': 'validate_coupon_code', 'coupon_code_input': coupon_code_input, 'video_id':vid}, 
          type:'POST',
          dataType: 'json',         
          success:function(data){

            jQuery("#show_coupon .text-danger").remove();
            
            //console.log(data['success']);
             if(data['success']){
              location.reload(true);
             }else if(data['error']){
                jQuery("#coupon_code").after('<p class="text-danger">'+data['error']+'</p>');
             }
          }

        });










	});

	


}