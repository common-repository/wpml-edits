<?php
function we_debug($data, $clr = 'red')
{
	echo "<pre style='color:{$clr};'>"; print_r($data); echo "</pre>";
}
function wpml_edits_change_wpml_data($data)
{
	$change_code = (isset($data['old_code']) && isset($data['new_code']));
	$change_name = (isset($data['old_name']) && isset($data['new_name']));
	$error_str = array();
					
	if($change_code) {
		if(!wpml_edits_code_exists($data['old_code']))
			$error_str[] = 'The old code value <b>' . $data['old_code'] . '</b> does not exist. Please try again.';
		if(wpml_edits_code_exists($data['new_code']))
			$error_str[] = 'The new code value <b>' . $data['new_code'] . '</b> already exists. Please try again.';
	}
	if($change_name) {
		if(!wpml_edits_name_exists($data['old_name']))
			$error_str[] = 'The old name value <b>' . $data['old_name'] . '</b> does not exist. Please try again.';
		if(wpml_edits_name_exists($data['new_name']))
			$error_str[] = 'The new name value <b>' . $data['new_name'] . '</b> already exists. Please try again.';
	}
	
	if(empty($error_str)) {
		if($change_code)
			$info_code = wpml_edits_change_code($data['old_code'], $data['new_code']);
		if($change_name)
			$info_name = wpml_edits_change_name($data['old_name'], $data['new_name']);
		if($change_code && $change_name) {
			$success = ($info_code['success'] == 'true' && $info_name['success'] == 'true') ? 'true' : 'false';
			$msg = $info_code['error'] . '<br />' . $info_name['error'];
			print json_encode(array('success' => $success, 'error' => $msg));
		} else if($change_code)
			print json_encode($info_code);
		else if($change_name)
			print json_encode($info_name);
	} else {
		$errors = implode('<br />', $error_str);
		print json_encode(array('success' => 'false', 'error' => $errors));
	}
}

function wpml_edits_code_exists($code)
{
	global $wpdb;
	$sql = "SELECT * FROM {$wpdb->prefix}icl_languages WHERE code=%s";
	$data = $wpdb->get_row($wpdb->prepare($sql, $code));
	return (!empty($data));
}

function wpml_edits_name_exists($name)
{
	global $wpdb;
	$sql = "SELECT * FROM {$wpdb->prefix}icl_languages WHERE english_name=%s";
	$data = $wpdb->get_row($wpdb->prepare($sql, $name));
	return (!empty($data));
}

function wpml_edits_change_code($oldc, $newc)
{
	global $wpdb;
	$results = array();
	// Start Transaction
	@mysql_query("BEGIN", $wpdb->dbh);
	try {
		$sql1 = "UPDATE {$wpdb->prefix}icl_languages SET code=%s WHERE code=%s";
		$wpdb->query($wpdb->prepare($sql1, $newc, $oldc));
		
		$sql2 = "UPDATE {$wpdb->prefix}icl_translations SET language_code=%s WHERE language_code=%s";
		$sql3 = "UPDATE {$wpdb->prefix}icl_translations SET source_language_code=%s WHERE source_language_code=%s";
		$wpdb->query($wpdb->prepare($sql2, $newc, $oldc));
		$wpdb->query($wpdb->prepare($sql3, $newc, $oldc));
		
		$sql4 = "UPDATE {$wpdb->prefix}icl_languages_translations SET language_code=%s WHERE language_code=%s";
		$sql5 = "UPDATE {$wpdb->prefix}icl_languages_translations SET display_language_code=%s WHERE display_language_code=%s";
		$wpdb->query($wpdb->prepare($sql4, $newc, $oldc));
		$wpdb->query($wpdb->prepare($sql5, $newc, $oldc));
		
		$sql6 = "UPDATE {$wpdb->prefix}icl_strings SET language=%s WHERE language=%s";
		$wpdb->query($wpdb->prepare($sql6, $newc, $oldc));
		
		delete_option('_icl_cache');
		@mysql_query("COMMIT", $wpdb->dbh);
		$results = array('success' => 'true', 'error' => 'The code <b>' . $oldc . '</b> was successfully changed with <b>' . $newc . '</b>.');
	} catch(Exception $e) {
		@mysql_query("ROLLBACK", $wpdb->dbh);
		$results = array('success' => 'false', 'error' => 'There was an error during applying the changes. Error:' . $e->getMessage());
	}
	return $results;
}

function wpml_edits_change_name($oldn, $newn)
{
	global $wpdb;
	$results = array();
	// Start Transaction
	@mysql_query("BEGIN", $wpdb->dbh);
	try {
		$sql1 = "UPDATE {$wpdb->prefix}icl_languages SET english_name=%s WHERE english_name=%s";
		$wpdb->query($wpdb->prepare($sql1, $newn, $oldn));
		
		$sql2 = "UPDATE {$wpdb->prefix}icl_languages_translations SET name=%s WHERE name=%s";
		$wpdb->query($wpdb->prepare($sql2, $newn, $oldn));

		delete_option('_icl_cache');
		@mysql_query("COMMIT", $wpdb->dbh);
		$results = array('success' => 'true', 'error' => 'The code <b>' . $oldn . '</b> was successfully changed with <b>' . $newn . '</b>.');
	} catch(Exception $e) {
		@mysql_query("ROLLBACK", $wpdb->dbh);
		$results = array('success' => 'false', 'error' => 'There was an error during applying the changes. Error:' . $e->getMessage());
	}
	return $results;
}
?>