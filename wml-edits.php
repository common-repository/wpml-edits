<?php
/*
Plugin Name: WPML Edits
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: On admin side, it changes the name of a language defined by WPML plugin.
Version: 1.0
Author: Simona Idaho	
Author URI: http://elfdreamer.blogspot.com
License: GPL2

Copyright 2011 Ilie Simona Elena  (email : sysyfina@yahoo.com)

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


/**
 * CONSTANTS SECTION
 */
if(!defined('PLUGIN_DIR')) define('PLUGIN_DIR', 'wpml-edits');
if(!defined('CODE_AND_NAME')) define('CODE_AND_NAME', 1);
if(!defined('CODE_ONLY')) define('CODE_ONLY', 2);
if(!defined('NAME_ONLY')) define('NAME_ONLY', 3);
/**
 * incluse javascript scripts
 */
function wpml_edits_scripts_method() {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
	wp_enqueue_script( 'jquery' );
}    

add_action('wp_enqueue_scripts', 'wpml_edits_scripts_method');
wp_enqueue_script('wpml-edits-js-scripts', plugins_url() . '/' . PLUGIN_DIR . '/js/scripts.js');
/**
 * include css file
 */
 wp_enqueue_style('wpml-edits-style', plugins_url() . '/' . PLUGIN_DIR . '/css/style.css');
/**
 * include a submenu option under Settings Dashboard 
 */
add_action( 'admin_menu', 'wpml_edits_admin_menus');

function wpml_edits_admin_menus()
{
	add_submenu_page('options-general.php', __('WPML Edits'), __('WPML Edits'), 'manage_options', 'wpml-edits', wpml_edits_inner_box);
}

/**
 * Draw options form
 */
function wpml_edits_inner_box()
{
	if(is_plugin_active('sitepress-multilingual-cms/sitepress.php')) :
?>
<script type='text/javascript'>
	var we_plugin_url = '<?php echo plugins_url() . '/' . PLUGIN_DIR . '/';?>';
</script>
<h2>WPML Edits Options</h2>
<div id="wpml_error_msgs"><!-- --></div>
<div id="wpml_success_msgs"><!-- --></div>
<div id="wpml_code_and_name_holder">
	<table border="0" cellspacing="8" cellpadding="12">
		<tr id="old_code_row"><td>Old Code: </td><td><input type="text" name="old_code_both" id="old_code_both" /></td></tr>
		<tr id="new_code_row"><td>New Code: </td><td><input type="text" name="new_code_both" id="new_code_both" /></td></tr>
		<tr id="old_name_row"><td>Old Name: </td><td><input type="text" name="old_name_both" id="old_name_both" /></td></tr>
		<tr id="new_name_row"><td>New Name: </td><td><input type="text" name="new_name_both" id="new_name_both" /></td></tr>
	</table>
</div>
<input type="button" id="edit_wpml_action" value="Apply Changes" style='cursor:pointer;' />
<div id="wpml_edits_info">
	<p>Please be aware of the risks you take using this plugin:</p>
	<ul>
		<li>It was not tested in a large number of contexts. If you encounter issues using it, please <a href='mailto:sysyfina@yahoo.com'>email me</a>. 
		(Please write a suggestive email subject and describe your issue in details).</li>
		<li>To make the changes visible it deletes the cache option of WPML plugin. If you already made many translations the next site loading might take a considerable amount of time.</li>
	</ul>
</div>
<?php 
else: ?>
<h2>WPML Edits</h2>
<div id="wpml_error_msgs" style="display:block;">The WPML Edits is based on WPML plugin. It must be activated before using WPML Edits.</div>	
<?php 
endif;
}
?>
