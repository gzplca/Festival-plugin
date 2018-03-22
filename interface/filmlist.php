<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_add = 'admin.php?page=ajouter-un-film';
	$addFilmUrl = admin_url($path_add);
	$path_update = 'admin.php?page=modifier-un-film';
	$modFilmUrl = admin_url($path_update);
	$path_delete = 'admin.php?page=supprimer-un-film';
	$delFilmUrl = admin_url($path_delete);
	$path = 'admin.php?page=categorie';
	$categoryUrl = admin_url($path);
	$path = 'admin.php?page=premiere';
	$firstUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_film where lang_code= '".(string)$actual_lang."' order by id DESC";
	$filmsObj = $wpdb->get_results((string)$str_request);
	//$filmsObj = $wpdb->get_results("select * from wp_prog_film");
	
?>

	
<div class ="prog_fl_page">
	<h1 class="title_page">Liste des Films</h1>
	<div class="box_btn_action">
		<a href="<?php echo $addFilmUrl;?>" class="btn btn-success">Ajouter un film</a>
		<a href="<?php echo $categoryUrl;?>" class="btn btn-success">Modifier les catégories de film</a>
		<a href="<?php echo $firstUrl;?>" class="btn btn-success">Modifier les premières des films</a>
	</div>
	<div class="row table table_header text-center">
			<div class = "col-1"><h3>ID</h3></div>
			<div class = "col-2"><h3>Photo</h3></div>
			<div class = "col-4"><h3>Titre</h3></div>
			<div class = "col-3"><h3>Category</h3></div>
			<div class = "col-2"><h3>Actions</h3></div>
	</div>
	<?php 
		foreach ($filmsObj as $filmObj) {
			$category = $wpdb->get_var("select title from ".$wpdb->prefix."prog_category where id = $filmObj->category_id");
	?>			
		<div class="row table table_body text-center">
			<div class = "col-1"><?php echo $filmObj->id;?></div>
			<div class = "col-2"><img src = "<?php echo $filmObj->photo; ?>" height = "60"></div>
			<div class = "col-4"><?php echo $filmObj->title;?></div>
			<div class = "col-3"><?php echo $category;?></div>
			<div class = "col-2"><a href="<?php echo $modFilmUrl.'&id='.$filmObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delFilmUrl.'&id='.$filmObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>

</div>