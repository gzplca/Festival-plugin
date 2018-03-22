<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	
	$path_add = 'admin.php?page=ajouter-une-premiere';
	$addFirstUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-une-premiere';
	$modFirstUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-une-premiere';
	$delFirstUrl = admin_url($path_delete);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_first where lang_code= '".(string)$actual_lang."'";
	$firstsObj = $wpdb->get_results((string)$str_request);
?>

	
<div class="prog_fl_page">
	<h1 class="title_page">Liste des Premières</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addFirstUrl;?>" class="btn btn-success">Ajouter une première</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-1">ID</div>
			<div class = "col-6">Titre</div>
			<div class = "col-3">Edition</div>
			<div class = "col-2">Action</div>
	</div>
	<?php 
		foreach ($firstsObj as $firstObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-1 align-middle"><?php echo $firstObj->id;?></div>
			<div class = "col-6 align-middle"><?php echo $firstObj->title;?></div>
			<?php
				global $wpdb;
				$editionDesc = $wpdb->get_var("select descritption from ".$wpdb->prefix."prog_edition where id = ".$firstObj->edition_id);
			?>
			<div class = "col-3 align-middle"><?php echo $editionDesc;?></div>
			<div class = "col-2 align-middle"><a href="<?php echo $modFirstUrl.'&id='.$firstObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delFirstUrl.'&id='.$firstObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>
</div>
