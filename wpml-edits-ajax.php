<?php 
require_once('../../../wp-load.php');
require_once('wpml-edits-functions.php');
$old_code = $_POST['old_code'];
$new_code = $_POST['new_code'];
$old_name = $_POST['old_name'];
$new_name = $_POST['new_name'];
$wpml_info = array();
if(!empty($old_code))
	$wpml_info['old_code'] = $old_code;
if(!empty($new_code))
	$wpml_info['new_code'] = $new_code;
if(!empty($old_name))
	$wpml_info['old_name'] = $old_name;
if(!empty($new_name))
	$wpml_info['new_name'] = $new_name;
// paranoid double verification
$error = array();
if(count($wpml_info) == 0) 
	$error = array('success' => 'false', 'error' => 'Please fill in any pair of old/new code or name.');
if((isset($wpml_info['old_code']) && !isset($wpml_info['new_code'])) || (!isset($wpml_info['old_code']) && isset($wpml_info['new_code'])))
	$error = array('success' => 'false', 'error' => 'Please fill in both old and new code values');
if((isset($wpml_info['old_name']) && !isset($wpml_info['new_name'])) || (!isset($wpml_info['old_name']) && isset($wpml_info['new_name'])))
	$error = array('success' => 'false', 'error' => 'Please fill in both old and new name values');

if(empty($error))
	wpml_edits_change_wpml_data($wpml_info);
else
	print json_encode($error);
	