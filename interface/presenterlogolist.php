<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-une-presentator-logo';
	$addPresenter_logoUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-une-presentator-logo';
	$modPresenter_logoUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-une-presentator-logo';
	$delPresenter_logoUrl = admin_url($path_delete);


	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_presentator_logo";
	$presenter_logosObj = $wpdb->get_results((string)$str_request);
	
?>

	
<div class ="prog_fl_page">
	<h1 class="title_page">Liste des presentator logos</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addPresenter_logoUrl;?>" class="btn btn-success">Ajouter un presentator logo</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-2"><h3>ID</h3></div>
			<div class = "col-4"><h3>Photo</h3></div>
			<div class = "col-4"><h3>Titre</h3></div>
			<div class = "col-2"><h3>Actions</h3></div>
	</div>
	<?php 
		foreach ($presenter_logosObj as $presenter_logoObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-2"><?php echo $presenter_logoObj->id;?></div>
			<div class = "col-4"><img src = "<?php echo  $presenter_logoObj->photo; ?>" height = "60"></div>
			<div class = "col-4"><?php echo  $presenter_logoObj->title;?></div>
			<div class = "col-2"><a href="<?php echo $modPresenter_logoUrl.'&id='.$presenter_logoObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delPresenter_logoUrl.'&id='.$presenter_logoObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>

</div>