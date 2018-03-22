<?php
	$path = 'admin.php?page=categorie';
	$categoryUrl = admin_url($path);

	global $wpdb;
	$categoryObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_category where id =".$_GET['id']);

	wp_delete_post( $categoryObj->wp_post_id, true );

	$wpdb->delete( $wpdb->prefix.'prog_category', array( 'id' => $_GET['id'] ) );

	redirect($categoryUrl);

	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>