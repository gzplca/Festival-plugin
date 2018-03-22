<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=modifier-une-edition&id='.$_GET['id'];
	$modEditionUrl = admin_url($path);
	$path = 'admin.php?page=edition';
	$editionListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $_GET[id]");

	$the_query = new WP_Query( array( 'post_type' => 'edition', 'p' =>  $editionObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $editionObj->wp_post_id, 'element_type' => 'edition' );
	$my_edition_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_edition_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_edition_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $editionObj->wp_post_id, 'edition' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$my_translated_editions = apply_filters( 'wpml_post_duplicates', $editionObj->wp_post_id );

	$translations_editions = apply_filters( 'wpml_get_element_translations', NULL, $my_edition_language_info->trid, 'post_edition' );

	
/*
	print_r($all_languages);
	print_r($editionObj->wp_post_id); 
	print_r($the_query);
	print_r($my_edition_language_info);
	print_r($my_edition_language_code);
	var_dump($my_edition_is_translated);
*/
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

		$editionObj_to_update = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $_GET[id]");

			$wpdb->update( 
				$wpdb->prefix.'prog_edition', 
				array( 
					'title' => $_POST['edition_title'], 
					'description' => $_POST['edition_description'],
				),
				array( 'id' => $_GET['id'] ),   
				array( 
					'%s',
					'%s' 
				),
				array( '%d' )  
			);

			$my_post = array(
		      	'ID'           => $editionObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['edition_title'],
		      	'post_name' => sanitize_title($_POST['edition_title']),
		  	);
 
  			wp_update_post( $my_post );
			reload();
	}

	function duplicate(){
		//$id_post = $_GET['id'] ;
		global $wpdb;
		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $_GET[id]");
		$id_post = $editionObj->wp_post_id;
		$title_original = $editionObj->title;
		$args_wpml_original_post = array('element_id' => $editionObj->wp_post_id, 'element_type' => 'edition' );
		$original_edition_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );
		//print_r('------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
		//print_r($id_post);
		//do_action( 'wpml_admin_make_post_duplicates', $id_post);
		//do_action( 'wpml_make_post_duplicates', $id_post  );
		//$my_duplications = apply_filters( 'wpml_post_duplicates', $id_post );
		//print_r($my_duplications);
		//print_r($_POST['lang_duplicate']);
		//print_r($title_original);	

		$wpdb->insert( 
				$wpdb->prefix.'prog_edition', 
				array( 
					'title' => $editionObj->title, 
					'description' => $editionObj->description,
					'lang_code' => $_POST['lang_duplicate']				
				), 
				array( 
					'%s', 
					'%s',
					'%s' 
				) 
		);

		$new_translated_edition_id = $wpdb->insert_id;

		//print_r($new_translated_edition_id);
		//$title_formated = wp_strip_all_tags( $edition_obj->title );

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'edition',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		//print_r($translated_postid);
		//print_r($original_edition_language_info);

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_edition',
            'trid'   => $original_edition_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_edition_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );
		
		//print_r($postid);

		$wpdb->update( 
				$wpdb->prefix.'prog_edition', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_edition_id ),   
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
	<h1 class="title_page">Modifier une Edition</h1>
	<div class="box_update_form">
		<form action="<?php echo $modEditionUrl;?>" method="post">
			<label>Le titre de l'édition: </label>
			<input type="text" id = "edition_title" name = "edition_title" value = "<?php echo $editionObj->title;?>"><br>
			<label>La description de l'édition: </label>
			<input type="text" id = "edition_description" name = "edition_description" value = "<?php echo $editionObj->description;?>"><br>
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $editionListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cette Edition: <?php echo $my_edition_language_code;?></p>
		<p>Lien de cette Edition : <?php apply_filters( 'wpml_element_link', $editionObj->wp_post_id, 'edition' ); ?></p>

		<div>
			<?php 
				if($my_edition_is_translated){ ?> 
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_editions ) ) {
					    foreach( $translations_editions as $key_trans => $translated_edition ) { 
					    	if($translated_edition->language_code != $actual_lang){	
					    		//print_r($translated_edition->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_edition where wp_post_id = $translated_edition->element_id");
					    		?>
					        <li>Edition Traduction (<?php echo $translated_edition->language_code;?>) : <a href="<?php  the_permalink($translated_edition->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_edition->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-une-edition&id='.$translation.'&lang='.$translated_edition->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_edition->post_title;?></a></li>
					    
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
					<form action="<?php echo $modEditionUrl;?>" method="post">
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

