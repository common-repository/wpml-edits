var $ = jQuery.noConflict();

$(document).ready(function(){
	$('#edit_wpml_action').click(function(){
		var old_code_val = $.trim($('#old_code_both').val());
		var new_code_val = $.trim($('#new_code_both').val());
		var old_name_val = $.trim($('#old_name_both').val());
		var new_name_val = $.trim($('#new_name_both').val());
		var error_msgs = $('#wpml_error_msgs');
		var success_msgs = $('#wpml_success_msgs');
			
		var error_str = '';
		
		if(old_code_val == '' && old_name_val == '' && new_code_val == '' && new_name_val == '')
			error_str = 'Please fill in any pair of old/new code or name.';
		else if((old_code_val == '' && new_code_val != '') || (old_code_val != '' && new_code_val == ''))
			error_str = 'Please fill in both old and new code values';
		else if((old_name_val == '' && new_name_val != '') || (old_name_val != '' && new_name_val == ''))
			error_str = 'Please fill in both old and new name values.';
		else {
			$.post(we_plugin_url + "wpml-edits-ajax.php", 
				{'old_code':old_code_val,'new_code':new_code_val, 'old_name':old_name_val, 'new_name': new_name_val},
				function(data) {
					if(data.success == 'false') {
						success_msgs.fadeOut('slow');
						error_msgs.html(data.error).fadeIn('slow');
					} else if(data.success == 'true') {
						error_msgs.fadeOut('slow');
						success_msgs.html(data.error).fadeIn('slow');
					}
				},
				'json');
		}
		if(error_str != '') {
			success_msgs.fadeOut('slow');
			error_msgs.html(error_str).fadeIn('slow');
		}
	});
});