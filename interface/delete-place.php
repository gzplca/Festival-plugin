<?php
	$path = 'admin.php?page=emplacement';
	$placelistUrl = admin_url($path);

	global $wpdb;

	$placeObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_venue where id =".$_GET['id']);

	wp_delete_post( $placeObj->wp_post_id, true );

	$wpdb->delete( $wpdb->prefix.'prog_venue', array( 'id' => $_GET['id'] ) );
	redirect($placelistUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>