<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-une-categorie';
	$addCategoryUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-une-categorie';
	$modCategoryUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-une-categorie';
	$delCategoryUrl = admin_url($path_delete);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_category where lang_code= '".(string)$actual_lang."'";
	$categoriesObj = $wpdb->get_results((string)$str_request);
	//$categoriesObj = $wpdb->get_results("select * from wp_prog_category");
	
?>

	
<div class="prog_fl_page">
	<h1 class="title_page">Liste des Categories</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addCategoryUrl;?>" class="btn btn-success">Ajouter un catégorie</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-1">ID</div>
			<div class = "col-6">Titre</div>
			<div class = "col-3">Edition</div>
			<div class = "col-2">Action</div>
	</div>
	<?php 
		foreach ($categoriesObj as $categoryObj) {
	?>			
		<div class="row table table_body text-center">
			<div class = "col-1 align-middle"><?php echo $categoryObj->id;?></div>
			<div class = "col-6 align-middle"><?php echo $categoryObj->title;?></div>
			<?php
				global $wpdb;
				$edition = $wpdb->get_var("select title from ".$wpdb->prefix."prog_edition where id = ".$categoryObj->edition_id);
			?>
			<div class = "col-3 align-middle"><?php echo $edition;?></div>
			<div class = "col-2 align-middle"><a href="<?php echo $modCategoryUrl.'&id='.$categoryObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delCategoryUrl.'&id='.$categoryObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>
</div>