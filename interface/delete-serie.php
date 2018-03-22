<?php
	$path = 'admin.php?page=series';
	$serieListUrl = admin_url($path);

	global $wpdb;

	$serieObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_serie where id =".$_GET['id']);

	wp_delete_post( $serieObj->wp_post_id, true );
	
	$selectEventsArr = $wpdb->get_col("select events_id from ".$wpdb->prefix."prog_event_serie where serie_id = ".$_GET[id]);

	 	
	$wpdb->delete( $wpdb->prefix.'prog_serie', array( 'id' => $_GET[id] ) );
	if ($selectEventsArr != null){
		foreach ($selectEventsArr as $eventid) {
	 			$wpdb->delete( $wpdb->prefix.'prog_event_serie', array('event_id' => $eventid, 'serie_id' => $_GET['id'] ) );
	 		}
	}
	
	redirect($serieListUrl);


	function redirect($url)
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'window.location = "' . $url . '"';
	    $string .= '</script>';

	    echo $string;
	}
	
?>