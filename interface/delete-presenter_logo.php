<?php
	$path = 'admin.php?page=presentator-logo';
	$presentator_logoUrl = admin_url($path);

	global $wpdb;

	$wpdb->delete( $wpdb->prefix.'prog_presentator_logo', array( 'id' => $_GET['id'] ) );

	redirect($presentator_logoUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>