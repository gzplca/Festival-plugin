<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';

	$path_update = 'admin.php?page=modifier-une-categorie&id='.$_GET['id'];
	$modCategoryUrl = admin_url($path_update);
	$path_list = 'admin.php?page=categorie';
	$categoryListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;

	$categoryObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_category where id =".$_GET['id']);
	//$editionsObj = $wpdb->get_results("select * from wp_prog_edition");

	$the_query = new WP_Query( array( 'post_type' => 'movie_category', 'p' =>  $categoryObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $categoryObj->wp_post_id, 'element_type' => 'movie_category' );
	$my_category_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_category_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_category_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $categoryObj->wp_post_id, 'movie_category' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$translations_categories = apply_filters( 'wpml_get_element_translations', NULL, $my_category_language_info->trid, 'post_movie_category' );

	if (isset($_REQUEST['update'])) {
		update();
	}

	if (isset($_REQUEST['duplicate'])) {
		duplicate();
	}

	function reload()
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'location.reload();';
	    $string .= '</script>';

	    echo $string;
	}

	function update() {
		
		global $wpdb;
		$categoryObj_to_update = $wpdb->get_results("select * from ".$wpdb->prefix."prog_category where id = $_GET[id]");

	 	$wpdb->update( 
	 		$wpdb->prefix.'prog_category', 
	 		array(
	 			'title' => $_POST['category_title']
	 		),
	 		array( 'id' => $_GET['id'] ),   
	 		array( 
	 			'%s', 
	 			'%s'
	 		),
	 		array( '%d' ) 
	 	);

	 	$my_post = array(
		      	'ID'           => $categoryObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['category_title'],
		      	'post_name' => sanitize_title($_POST['category_title']),
		);
 
  		wp_update_post( $my_post );

		reload();
	}

	function duplicate(){
		global $wpdb;
		$categoryObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_category where id = $_GET[id]");
		$id_post = $categoryObj->wp_post_id;
		$title_original = $categoryObj->title;
		$args_wpml_original_post = array('element_id' => $categoryObj->wp_post_id, 'element_type' => 'movie_category' );
		$original_category_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $categoryObj->edition_id");
		//print_r($editionObj);

		$args_wpml_original_edition_post = array('element_id' => $editionObj->wp_post_id, 'element_type' => 'edition' );
		$original_edition_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_edition_post );

		$args_wpml_original_edition_post = array('element_id' => $editionObj->wp_post_id, 'element_type' => 'edition' );
		$original_edition_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_edition_post );

		//$my_duplications_edition = apply_filters( 'wpml_post_duplicates', $editionObj->wp_post_id );

		$translations_editions = apply_filters( 'wpml_get_element_translations', NULL, $original_edition_language_info->trid, 'post_edition' );
		//print_r($translations_editions);

		$id_edition_in_select_lang = $translations_editions[$_POST['lang_duplicate']]->element_id;
		//print_r($id_edition_in_select_lang);

		$editionObj_select_lang = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where wp_post_id = $id_edition_in_select_lang");
		//print_r($editionObj_select_lang);

		$wpdb->insert( 
				$wpdb->prefix.'prog_category',
				array( 
					'title' 		=> $categoryObj->title,
					'edition_id' 		=> $editionObj_select_lang->id, 
					'lang_code' => $_POST['lang_duplicate']	
				), 
				array( 
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s' 
				)
		);

		$new_translated_category_id = $wpdb->insert_id;

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'movie_category',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_movie_category',
            'trid'   => $original_category_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_category_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_category', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_category_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
		);
		
		reload();
		
	}


?>


<?php
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();

		
?>

<div class="prog_fl_page">

	<h1 class="title_page">Modifier une catégorie</h1>

	<div class="box_update_form">

		<form action="<?php echo $modCategoryUrl;?>" method="post">
			<label>Titre: </label>
			<input type="text" id = "category_title" name = "category_title" value = "<?php echo $categoryObj->title;?>"><br>	
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $categoryListUrl;?>" class="btn btn-mini btn-warning">Retournez</a></div>
		</form>

	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cette Catégorie: <?php echo $my_category_language_code;?></p>
		<p>Lien de cette Catégorie: <?php apply_filters( 'wpml_element_link', $categoryObj->wp_post_id, 'movie_category' ); ?></p>

		<div>
			<?php 
				if($my_category_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_categories ) ) {
					    foreach( $translations_categories as $key_trans => $translated_category ) { 
					    	if($translated_category->language_code != $actual_lang){	
					    		//print_r($translated_category->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_category where wp_post_id = $translated_category->element_id");
					    		?>
					        <li>Catégorie Traduction (<?php echo $translated_category->language_code;?>) : <a href="<?php  the_permalink($translated_category->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_category->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-une-categorie&id='.$translation.'&lang='.$translated_category->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_category->post_title;?></a></li>
					    
					<?php
							}       
					    }
					}
					?>
					</ul>


			<?php	
				}
				else{ ?>
					<!--<p>hasn't translation</p>-->
					<form action="<?php echo $modCategoryUrl;?>" method="post">
						<select name="lang_duplicate">
						<?php 
							if ( !empty( $all_languages ) ) {
					        	foreach( $all_languages as $lang ) {
					            	if ( !$lang['active'] ){ ?>
					            		<option value="<?php echo $lang['code'];?>"><?php echo $lang['translated_name'];?></option>
					    
					    <?php       
					    			}
					            }
					    	}
					    ?>
						</select>
						<input type="submit" name="duplicate" value="duplicate" id = "duplicate-button" class="btn btn-mini btn-success">
					</form>
			<?php
				} 
			?>
		</div>	
	</div>	

</div>

<?php
	}
} else {
	// no posts found
}

?>


