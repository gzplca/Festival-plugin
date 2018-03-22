<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	$path = 'admin.php?page=ajouter-une-categorie';
	$addCategoryUrl = admin_url($path);
	$path = 'admin.php?page=categorie';
	$categoryListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	$editionsObj = $wpdb->get_results((string)$str_request);

	if (isset($_REQUEST['insert'])) {
		insert();
	}

	function goToList()
	{
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=categorie';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {		
		global $wpdb;
	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_category', 
	 		array(
	 			'edition_id' =>  $_POST['category_edition'], 
	 			'title' => $_POST['category_title'],
	 			'lang_code' => $_POST['category_lang']
	 		), 
	 		array( 
	 			'%s', 
	 			'%s', 
	 			'%s'
	 		) 
	 	);

	 	$new_category_id = $wpdb->insert_id;

	 	$category_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_category where id = $new_category_id");

			$title_formated = wp_strip_all_tags( $category_obj->title );

			$my_post = array(
				'post_title'    => $title_formated,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'movie_category',
			);

			$postid = wp_insert_post( $my_post, true );


			$wpdb->update( 
				$wpdb->prefix.'prog_category', 
				array( 
					'wp_post_id' => $postid 
				),
				array( 'id' => $new_category_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
			);

			goToList();
	}

?>
	
<div class="prog_fl_page">
	<h1>Ajouter une catégorie</h1>
	<div class="box_form_add">
		<form action="<?php echo $addCategoryUrl;?>" method="post">
			<input type="text" name ="category_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "category_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "category_title" name = "category_title"><br>	
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $categoryListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>



