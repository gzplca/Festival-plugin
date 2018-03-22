<?php
	$path = 'admin.php?page=edition';
	$editionUrl = admin_url($path);

	global $wpdb;
	$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id =".$_GET['id']);

	wp_delete_post( $editionObj->wp_post_id, true );

	$wpdb->delete( $wpdb->prefix.'prog_edition', array( 'id' => $_GET['id'] ) );

	redirect($editionUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>