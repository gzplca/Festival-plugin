<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-une-serie';
	$addSerieUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-une-serie';
	$modSerieUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-une-serie';
	$delSerieUrl = admin_url($path_delete);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_serie where lang_code= '".(string)$actual_lang."'";
	$seriesObj = $wpdb->get_results((string)$str_request);
	//$seriesObj = $wpdb->get_results("select * from wp_prog_serie");
	
?>

	
<div class ="prog_fl_page">
	<h1 class="title_page">Liste des séries</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addSerieUrl;?>" class="btn btn-success">Ajouter un série</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-1"><h3>ID</h3></div>
			<div class = "col-2"><h3>Photo</h3></div>
			<div class = "col-4"><h3>Titre</h3></div>
			<div class = "col-3"><h3>Événement(s)</h3></div>
			<div class = "col-2"><h3>Actions</h3></div>
	</div>
	<?php 
		foreach ($seriesObj as $serieObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-1"><?php echo $serieObj->id;?></div>
			<div class = "col-2"><img src = "<?php echo $serieObj->photo; ?>" height = "60"></div>
			<div class = "col-4"><?php echo $serieObj->title;?></div>
			<?php
				global $wpdb;
				$eventids = $wpdb->get_col("select event_id from ".$wpdb->prefix."prog_event_serie where serie_id =".$serieObj->id);
				foreach ($eventids as $eventid) {
					$eventTitle = $wpdb->get_var("select title from ".$wpdb->prefix."prog_event where id =".$eventid);
					$eventDisplay .= '<div>'.$eventTitle.'</div>';
				}
			?>
			<div class = "col-3"><?php echo $eventDisplay; $eventDisplay = '';?></div>
			<div class = "col-2"><a href="<?php echo $modSerieUrl.'&id='.$serieObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delSerieUrl.'&id='.$serieObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>

</div>