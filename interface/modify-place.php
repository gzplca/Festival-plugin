<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_update = 'admin.php?page=modifier-un-emplacement&id='.$_GET['id'];
	$modPlaceUrl = admin_url($path_update);
	$path_list = 'admin.php?page=emplacement';
	$placeListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;

	
	global $wpdb;
	$placeObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_venue where id = $_GET[id]");

	$the_query = new WP_Query( array( 'post_type' => 'place', 'p' =>  $placeObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $placeObj->wp_post_id, 'element_type' => 'place' );
	$my_place_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_place_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_place_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $placeObj->wp_post_id, 'place' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$translations_places = apply_filters( 'wpml_get_element_translations', NULL, $my_place_language_info->trid, 'post_place' );


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

		$placeObj_to_update = $wpdb->get_row("select * from ".$wpdb->prefix."prog_venue where id = $_GET[id]");

			$wpdb->update( 
				$wpdb->prefix.'prog_venue', 
				array( 
					'title' => $_POST['place_name'], 
					'address' => $_POST['place_address'],
					'phone'  => $_POST['place-tel'],
					'website' => $_POST['place-website'],
					'map' => $_POST['place_map']
				),
				array( 'id' => $_GET['id'] ),   
				array( 
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s' 
				),
				array( '%d' )  
			);

			$my_post = array(
		      	'ID'           => $placeObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['place_name'],
		      	'post_name' => sanitize_title($_POST['place_name']),
		  	);
 
  			wp_update_post( $my_post );
			reload();
	}


	function duplicate(){
		global $wpdb;
		$placeObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_venue where id = $_GET[id]");
		$id_post = $placeObj->wp_post_id;
		$title_original = $placeObj->title;
		$args_wpml_original_post = array('element_id' => $placeObj->wp_post_id, 'element_type' => 'place' );
		$original_place_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $placeObj->edition_id");
		//print_r($editionObj);

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
				$wpdb->prefix.'prog_venue',
				array( 
					'title' => $placeObj->title, 
					'address' => $placeObj->address,
					'phone'  => $placeObj->phone,
					'website' => $placeObj->website,
					'map' => $placeObj->map,
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

		$new_translated_place_id = $wpdb->insert_id;

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'place',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_place',
            'trid'   => $original_place_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_place_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_venue', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_place_id ),   
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

	<h1 class="title_page">Modifier un Emplacement</h1>

	<div class="box_update_form">
		<form action="<?php echo $modPlaceUrl;?>" method="post">
			<label>Le nom de emplacement: </label>
			<input type="text" id = "place_name" name = "place_name" value = "<?php echo $placeObj->title;?>"><br>
			<label>Le address de emplacement: </label>
			<input type="text" id = "place_address" name = "place_address" value = "<?php echo $placeObj->address;?>"><br>
			<label>Le téléphone de emplacement: </label>
			<input type="text" id = "place-tel" name = "place-tel" value = "<?php echo $placeObj->phone;?>"><br>
			<label>Le site Internet de emplacement: </label>
			<input type="text" id = "place-website" name = "place-website" value = "<?php echo $placeObj->website;?>"><br>
			<label>Le carte de emplacement: </label>
			<input type="text" id = "place_map" name = "place_map" value = "<?php echo $placeObj->map;?>" ><br>
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $placeListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cette Place: <?php echo $my_place_language_code;?></p>
		<p>Lien de cette Place: <?php apply_filters( 'wpml_element_link', $placeObj->wp_post_id, 'place' ); ?></p>

		<div>
			<?php 
				if($my_place_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_places ) ) {
					    foreach( $translations_places as $key_trans => $translated_place ) { 
					    	if($translated_place->language_code != $actual_lang){	
					    		//print_r($translated_edition->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_venue where wp_post_id = $translated_place->element_id");
					    		?>
					        <li>Place Traduction (<?php echo $translated_place->language_code;?>) : <a href="<?php  the_permalink($translated_place->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_place->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-un-emplacement&id='.$translation.'&lang='.$translated_place->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_place->post_title;?></a></li>
					    
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
					<form action="<?php echo $modPlaceUrl;?>" method="post">
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
