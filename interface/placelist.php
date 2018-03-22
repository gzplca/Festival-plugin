<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-un-emplacement';
	$addPlaceUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-un-emplacement';
	$modPlaceUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-un-emplacement';
	$delPlaceUrl = admin_url($path_delete);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_venue where lang_code= '".(string)$actual_lang."'";
	$placesObj = $wpdb->get_results((string)$str_request);
	//$placesObj = $wpdb->get_results("select * from wp_prog_venue");
	
?>

	
<div class="prog_fl_page">
	<h1 class="title_page">Liste des Emplacements</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addPlaceUrl;?>" class="btn btn-success">Ajouter un emplacement</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-3">Titre</div>
			<div class = "col-3">Adresse</div>
			<div class = "col-1">Téléphone</div>
			<div class = "col-1">Site web</div>
			<div class = "col-1">Carte</div>
			<div class = "col-3">Actions</div>
	</div>
	<?php 
		foreach ($placesObj as $placeObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-3"><?php echo $placeObj->title;?></div>
			<div class = "col-3"><?php echo $placeObj->address;?></div>
			<div class = "col-1"><?php echo $placeObj->phone;?></div>
			<div class = "col-1"><?php echo $placeObj->website;?></div>
			<div class = "col-1"><?php echo $placeObj->map;?></div>
			<div class = "col-3"><a href="<?php echo $modPlaceUrl.'&id='.$placeObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delPlaceUrl.'&id='.$placeObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>


</div>