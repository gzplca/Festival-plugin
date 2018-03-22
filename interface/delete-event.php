<?php
	$path = 'admin.php?page=evenement';
	$eventListUrl = admin_url($path);

	global $wpdb;

	$eventObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_event where id =".$_GET['id']);

	wp_delete_post( $eventObj->wp_post_id, true );
	
	$postid = $wpdb->get_var("select post_id from ".$wpdb->prefix."prog_event where id =".$_GET['id']);
	$selectartistsObj = $wpdb->get_col("select artist_id from ".$wpdb->prefix."prog_event_artist where event_id = ".$_GET['id']);
	$wpdb->delete( $wpdb->prefix.'prog_event', array( 'id' => $_GET['id'] ) );
	if ($selectartistsObj != null){
		foreach ($selectartistsObj as $artistid) {
	   			$wpdb->delete( $wpdb->prefix.'prog_event_artist', array('artist_id' => $artistid, 'event_id' => $_GET['id'] ) );
	   		}
	}
	
	redirect($eventListUrl);


	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>