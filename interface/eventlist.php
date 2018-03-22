<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-un-evenement';
	$addEventUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-un-evenement';
	$modEventUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-un-evenement';
	$delEventUrl = admin_url($path_delete);
	$path = 'admin.php?page=emplacement';
	$placeUrl = admin_url($path);
	$path = 'admin.php?page=presentator-logo';
	$presenter_logoUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_event where lang_code= '".(string)$actual_lang."'  order by id DESC";
	$eventsObj = $wpdb->get_results((string)$str_request);
	//$eventsObj = $wpdb->get_results("select * from wp_prog_event");
	
?>

	
<div class ="prog_fl_page">
	<h1 class="title_page">Liste des Événements</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addEventUrl;?>" class="btn btn-success">Ajouter un évènement</a>
		<a href="<?php echo $placeUrl;?>" class="btn btn-success">Modifier les emplacements</a>
		<a href="<?php echo $presenter_logoUrl;?>" class="btn btn-success">Modifier les presentator logos</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-1"><h3>ID</h3></div>
			<div class = "col-3"><h3>Titre</h3></div>
			<div class = "col-2"><h3>Venue</h3></div>
			<div class = "col-1"><h3>Date</h3></div>
			<div class = "col-1"><h3>Heure</h3></div>
			<div class = "col-1"><h3>Photo</h3></div>
			<div class = "col-3"><h3>Actions</h3></div>
	</div>
	<?php 
		foreach ($eventsObj as $eventObj) {
			$place = $wpdb->get_var("select title from ".$wpdb->prefix."prog_venue where id = $eventObj->venue_id");
	?>			
		<div class="row table table_body text-center">
			<div class = "col-1"><?php echo $eventObj->id;?></div>
			<div class = "col-3"><?php echo $eventObj->title;?></div>
			<div class = "col-2"><?php echo $place;?></div>
			<div class = "col-1"><?php echo $eventObj->date;?></div>
			<div class = "col-1"><?php echo $eventObj->start_time;?></div>
			<div class = "col-1"><img src = "<?php echo $eventObj->photo_big; ?>" height = "60"></div>
			<div class = "col-3"><a href="<?php echo $modEventUrl.'&id='.$eventObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delEventUrl.'&id='.$eventObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>

</div>