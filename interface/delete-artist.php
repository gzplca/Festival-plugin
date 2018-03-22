<?php
	$path = 'admin.php?page=artiste';
	$artistlistUrl = admin_url($path);

	global $wpdb;

	$artistObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_artist where id =".$_GET['id']);

	wp_delete_post( $artistObj->wp_post_id, true );
	
	$wpdb->delete( $wpdb->prefix.'prog_artist', array( 'id' => $_GET['id'] ) );
	redirect($artistlistUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>