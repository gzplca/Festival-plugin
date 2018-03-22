<?php
	$path = 'admin.php?page=film';
	$filmUrl = admin_url($path);

	global $wpdb;

	$filmObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_film where id =".$_GET['id']);

	wp_delete_post( $filmObj->wp_post_id, true );
	
	$wpdb->delete( $wpdb->prefix.'prog_film', array( 'id' => $_GET['id'] ) );
	redirect($filmUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>