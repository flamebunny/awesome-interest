/*	
	Copyright 2014 dbudhia@gmail.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/

jQuery(document).ready(function(){	
	showhideform();
	submitform();		
});

function showhideform(){
	var check_wedding 		= jQuery('#'+check_wedding_id).is(':checked');
	var check_reception 	= jQuery('#'+check_reception_id).is(':checked');	
	
	if(jQuery('#'+check_wedding_id).attr("checked") || jQuery('#'+check_reception_id).attr("checked")) {
		jQuery("#guests").show();
	}
	jQuery('#'+check_wedding_id).change(function () {				
		if(jQuery('#'+check_wedding_id).attr("checked") || jQuery('#'+check_reception_id).attr("checked")) {
			jQuery("#guests").slideDown("slow")
		}else{
			jQuery("#guests").slideUp("slow")
		}
	});
	jQuery('#'+check_reception_id).change(function () {					
		if(jQuery('#'+check_wedding_id).attr("checked") || jQuery('#'+check_reception_id).attr("checked")) {
			jQuery("#guests").slideDown("slow")
		}else{
			jQuery("#guests").slideUp("slow")
		}
	});    
}

function submitform(){
	jQuery('#awesome_interest_form').submit(function(){		
		// gather and set
		var check_wedding 		= jQuery('#'+check_wedding_id).is(':checked');
		var check_reception 	= jQuery('#'+check_reception_id).is(':checked');
		var guestname 			= jQuery('#'+guestname_id).val();
		var guestcount 			= jQuery('#'+guestcount_id).val();		
		
		//alert('['+check_wedding+']['+check_reception+']['+guestname+']['+guestcount+']');
		
		// validate
		if(!check_wedding && !check_reception) {
			alert('Please select an event');
			return false;
		}		
		
		jQuery(".error1").removeClass("error1");
		
		if (guestname == "" || guestname === jQuery('#'+guestname_id).attr("placeholder")) {		
		//	jQuery('#'+guestname_id).focus(); 
			jQuery('#'+guestname_id).addClass('error1');
			return false;
		}		
		if (guestcount == "" || guestcount == 0 || !isNumeric(guestcount)  || guestcount === jQuery('#'+guestcount_id).attr("placeholder")) {				
		//	jQuery('#'+guestcount_id).focus(); 
			jQuery('#'+guestcount_id).addClass('error1');
			return false; 
		}				
		
		var data_string = "action=awesome_interest_submit&check_wedding="+check_wedding+"&check_reception="+check_reception+"&guestname="+guestname+"&guestcount="+guestcount;
		//alert(data_string);
		
		jQuery.ajax({
			type: 'POST',
			url: ajax_loc.ajaxurl,
			data: data_string,
			success: function(dataCheck){
				//alert(dataCheck);
				jQuery('#awesome_interest_form_container')
					.html("<div id='awesome_interest_form_container_success'><p>"+success_text+"</p></div>")				
					.hide()  
					.fadeIn(500, function() {  
						jQuery('#awesome_interest_form_container_success');
					});
			}
		});
		
		return false;
	});	
}

function isNumeric(num){
    return !isNaN(num)
}