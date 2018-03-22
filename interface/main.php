<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=emplacements';
	$url = admin_url($path);
	$link = "<a href='{$url}'>Edit</a>";

	$big_title_page = (ICL_LANGUAGE_CODE == 'fr')?"Ca marche":"It's working";

	global $wpdb;
	$eventsObj = $wpdb->get_results("select * from ".$wpdb->prefix."prog_event");
	
?>

	
<div class="prog_fl_page">
	<?php echo $big_title_page; ?>

	<?php 
		foreach ($eventsObj as $eventObj) {
			$place = $wpdb->get_var("select title from ".$wpdb->prefix."prog_venue where id = $eventObj->venue_id");
	?>			
		<div class = "row text-center">
			<div class = "col-1"><?php echo $eventObj->id;?></div>
			<div class = "col-3"><?php echo $eventObj->title;?></div>
			<div class = "col-2"><?php echo $place;?></div>
			<div class = "col-1"><?php echo $eventObj->date;?></div>
			<div class = "col-1"><?php echo $eventObj->start_time;?></div>
			<div class = "col-1"><img src = "<?php echo $eventObj->photo_small; ?>" height = "60"></div>			
		</div>
	<?php } ?>

</div>