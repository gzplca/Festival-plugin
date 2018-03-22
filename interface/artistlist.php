<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-un-artiste';
	$addArtistUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-un-artiste';
	$modArtistUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-un-artiste';
	$delArtistUrl = admin_url($path_delete);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;

	$str_request = "select * from ".$wpdb->prefix."prog_artist where lang_code= '".(string)$actual_lang."'";
	$artistsObj = $wpdb->get_results((string)$str_request);
	//$artistsObj = $wpdb->get_results("select * from wp_prog_artist");
	
?>

	
<div class="prog_fl_page">
	<h1 class="title_page">Liste des Artistes</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addArtistUrl;?>" class="btn btn-success">Ajouter un artiste</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-4">Nom</div>
			<div class = "col-4">Photo</div>
			<div class = "col-4">Actions</div>
	</div>
	<?php 
		foreach ($artistsObj as $artistObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-4 align-middle"><?php echo $artistObj->artist_name;?></div>
			<div class = "col-4 align-middle"><img src = "<?php echo $artistObj->photo_desc;?>" width = "60"></div>
			<div class = "col-4 align-middle"><a href="<?php echo $modArtistUrl.'&id='.$artistObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delArtistUrl.'&id='.$artistObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>

</div>