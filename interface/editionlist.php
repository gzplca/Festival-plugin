<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-une-edition';
	$addEditionUrl = admin_url($path);
	$path = 'admin.php?page=modifier-une-edition';
	$modEditionUrl = admin_url($path);
	$path = 'admin.php?page=supprimer-une-edition';
	$delEditionUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;

	//print_r($actual_lang);

	global $wpdb;
	//$str_request = "select * from wp_prog_edition where lang_code=".(string)$actual_lang;
	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	//print_r($str_request);

	//$editionsObj = $wpdb->get_results("select * from wp_prog_edition WHERE lang_code = 'fr'");
	//$editionsObj = $wpdb->get_results("select * from wp_prog_edition WHERE lang_code = ".$actual_lang);
	$editionsObj = $wpdb->get_results((string)$str_request);
	//print_r($editionsObj);
?>

	
<div class="prog_fl_page">

	<h1 class="title_page">Liste des Editions</h1>
	<div class="box_btn_action">

		<a href="<?php echo $addEditionUrl;?>" class="btn btn-success">Ajouter un edition</a>
	</div>

	<div class="row table table_header text-center">
			<div class = "col-4">Titre</div>
			<div class = "col-4">Descritpion</div>
			<div class = "col-4">Action</div>
	</div>
	<?php 
		foreach ($editionsObj as $editionObj) {
	?>			
		<div class="row table table_body text-center">
			<div class= "col-4 align-middle"><?php echo $editionObj->title;?></div>
			<div class= "col-4 align-middle"><?php echo $editionObj->description;?></div>
			<div class= "col-4 align-middle"><a href="<?php echo $modEditionUrl.'&id='.$editionObj->id;?>" class="btn btn-mini btn-primary">Modifier</a>
  			<a href="<?php echo $delEditionUrl.'&id='.$editionObj->id;?>" class="btn btn-mini btn-danger">Supprimer</a></div>				
		</div>
	<?php } ?>
</div>
