<?php
	$path = 'admin.php?page=premiere';
	$firstUrl = admin_url($path);

	global $wpdb;
	$firstObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_first where id =".$_GET['id']);

	wp_delete_post( $firstObj->wp_post_id, true );

	$wpdb->delete( $wpdb->prefix.'prog_first', array( 'id' => $_GET['id'] ) );

	redirect($firstUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>