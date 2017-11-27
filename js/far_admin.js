var adminurl = far_admin_ajax_obj.admin_url;
var plugin_dir_loader_img_url = far_admin_ajax_obj.plugin_dir_loader_img_url;
var $ = jQuery;
$(function(){
	
	$('#submit').on('click', function(){
		var find_text = $('.find').val();
		var replace_text = $('.replace').val();
     		if(find_text != '' && replace_text != ''){
     			var x = confirm("Are you sure you want to Replace Text?");
     			if (x){	
     				//$('#submit').hide();
     				//$('.result').empty();
     				//$('.loader').html('<img src="'+plugin_dir_loader_img_url+'" alt="loader" />');
				 	$.ajax({
						url: adminurl,
						type:'GET',
						dataType: 'json',
						data : {action: 'far_admin_ajax_fun', replace_text: replace_text, find_text: find_text},
						success: function(data,textStatus, XMLHttpRequest)
						{
							 responseJson = data;
							//$('.loader').hide();
							//$('#submit').show();
							
							$("#ajax-response").html(responseJson.tablehtml)
							//$("#ajax-response").append(responseJson.table_action)
						}
					});
		  		}else{
    				return false;
  				}	
  			}
		});
});