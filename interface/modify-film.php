<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';

	$path_update = 'admin.php?page=modifier-un-film&id='.$_GET['id'];
	$modFilmUrl = admin_url($path_update);
	$path_list = 'admin.php?page=film';
	$filmListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$filmObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_film where id =".$_GET['id']);

	$str_request_cat = "select * from ".$wpdb->prefix."prog_category where lang_code= '".(string)$actual_lang."'";
	$categoriesObj = $wpdb->get_results((string)$str_request_cat);
	//$categoriesObj = $wpdb->get_results("select * from wp_prog_category where lang_code =".$actual_lang);
	//print_r($categoriesObj);

	$str_request_first = "select * from ".$wpdb->prefix."prog_first where lang_code= '".(string)$actual_lang."'";
	$firstsObj = $wpdb->get_results((string)$str_request_first);
	//$firstsObj = $wpdb->get_results("select * from wp_prog_first where lang_code =".$actual_lang);

	$str_request_edition = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	$editionsObj = $wpdb->get_results((string)$str_request_edition);
	//$editionsObj = $wpdb->get_results("select * from wp_prog_edition where lang_code =".$actual_lang);


	$the_query = new WP_Query( array( 'post_type' => 'movie', 'p' =>  $filmObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $filmObj->wp_post_id, 'element_type' => 'movie' );
	$my_film_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_film_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_film_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $filmObj->wp_post_id, 'movie' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$translations_movies = apply_filters( 'wpml_get_element_translations', NULL, $my_film_language_info->trid, 'post_movie' );
	
	if ( !empty( $_POST['image-title'] ) ) {
	    $image_url = $_POST['image-title'];
	    $wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}

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
		$filmObj_to_update = $wpdb->get_results("select * from ".$wpdb->prefix."prog_film where id = $_GET[id]");


	 	$wpdb->update( 
	 		$wpdb->prefix.'prog_film', 
	 		array(
	 			'category_id' =>  $_POST['film_category'],
	 			'first_id' =>  $_POST['film_first'], 
	 			'title' => $_POST['film_title'], 
	 			'trailer' => $_POST['film_trailer'], 
	 			'video_provider'  => $_POST['film_video_provider'],
	 			'director'  => $_POST['film_director'],
	 			'actors'  => $_POST['film_actors'],
	 			'country' => $_POST['film_country'],
	 			'year' => $_POST['film_year'],
	 			'duration' => $_POST['film_duration'],  
	 			'language' => $_POST['film_language'],
	 			'photo'  => $_POST['image-title'],
	 			'presence' => $_POST['film_presence'],
	 			'synopsis'  => $_POST['film_synopsis']
	 		), 
	 		array( 'id' => $_GET['id'] ),  
	 		array( 
	 			'%s',
	 			'%s',
	 			'%s', 
	 			'%s',
	 			'%s',
	 			'%s', 
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s' 
	 		),
	 		array( '%d' )   
	 	);

	 	$my_post = array(
		      	'ID'           => $filmObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['film_title'],
		      	'post_name' => sanitize_title($_POST['film_title']),
		);
 
  		wp_update_post( $my_post );

		reload();
	}

	function duplicate(){
		global $wpdb;
		$filmObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_film where id = $_GET[id]");
		$id_post = $filmObj->wp_post_id;
		$title_original = $filmObj->title;
		$args_wpml_original_post = array('element_id' => $filmObj->wp_post_id, 'element_type' => 'movie' );
		$original_film_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $filmObj->edition_id");
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
				$wpdb->prefix.'prog_film',
				array(
		 			'edition_id' =>  $editionObj_select_lang->id, 
					'lang_code' => $_POST['lang_duplicate'],
		 			'title' => $filmObj->title, 
		 			'trailer' => $filmObj->trailer,
		 			'actors'  => $filmObj->actors, 
		 			'video_provider'  => $filmObj->video_provider,
		 			'director'  => $filmObj->director,
		 			'country' => $filmObj->country,
		 			'year' => $filmObj->year,
		 			'duration' => $filmObj->duration,  
		 			'language' => $filmObj->language,
		 			'photo'  => $filmObj->photo,
		 			'presence' => $filmObj->presence,
		 			'synopsis'  => $filmObj->synopsis
		 		),  
				array( 
					'%s',
					'%s', 
					'%s',
					'%s', 
					'%s',
					'%s', 
					'%s',
					'%s', 
					'%s',
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s' 
				)
		);

		$new_translated_film_id = $wpdb->insert_id;

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'movie',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_movie',
            'trid'   => $original_film_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_film_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_film', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_film_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
		);
		
		reload();
		
	}



?>

<script type="text/javascript">
		jQuery(document).ready(function($){
			var mediaUploader;
			$('#title-upload-button').click(function(e) {
				e.preventDefault();
				// If the uploader object has already been created, reopen the dialog
				  if (mediaUploader) {
				  mediaUploader.open();
				  return;
				}
				// Extend the wp.media object
				mediaUploader = wp.media.frames.file_frame = wp.media({
				  title: 'Choose Image',
				  button: {
				  text: 'Choose Image'
				}, multiple: false });

				// When a file is selected, grab the URL and set it as the text field's value
				mediaUploader.on('select', function() {
				  var attachment = mediaUploader.state().get('selection').first().toJSON();
				  $('#image-title-url').val(attachment.url);
				  $('#image-title-display').attr({src: attachment.url});
				});
				// Open the uploader dialog
				mediaUploader.open();
			});
		// 	var mediaUploader1;
		// 	$('#desc-upload-button').click(function(e) {
		// 		e.preventDefault();
		// 		// If the uploader object has already been created, reopen the dialog
		// 		  if (mediaUploader1) {
		// 		  mediaUploader1.open();
		// 		  return;
		// 		}
		// 		// Extend the wp.media object
		// 		mediaUploader1 = wp.media.frames.file_frame = wp.media({
		// 		  title: 'Choose Image',
		// 		  button: {
		// 		  text: 'Choose Image'
		// 		}, multiple: false });

		// 		// When a file is selected, grab the URL and set it as the text field's value
		// 		mediaUploader1.on('select', function() {
		// 		  var attachment = mediaUploader1.state().get('selection').first().toJSON();
		// 		  $('#image-desc-url').val(attachment.url);
		// 		  $('#image-desc-display').attr({src: attachment.url});
		// 		});
		// 		// Open the uploader dialog
		// 		mediaUploader1.open();
		// 	});
		});
</script>

<?php
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();

		
?>
	
<div class="prog_fl_page">

	<h1 class="title_page">Modifier un Film</h1>

	<div class="box_update_form">

		<form action="<?php echo $modFilmUrl;?>" method="post">
			<label>Catégorie: </label>
			<select name = "film_category">
				<?php 
					foreach ($categoriesObj as $categoryObj) {
						$select = '';
						if ($categoryObj->id == $filmObj->category_id){
							$select = 'selected';
						}
						echo '<option value = '.$categoryObj->id.' '.$select.'>'.$categoryObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Première du film: </label>
			<select name = "film_first">
				<?php 
					foreach ($firstsObj as $firstObj) {
						$select = '';
						if ($firstObj->id == $filmObj->first_id){
							$select = 'selected';
						}
						echo '<option value = '.$firstObj->id.' '.$select.'>'.$firstObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "film_title" name = "film_title" value = "<?php echo $filmObj->title;?>"><br>
			<label>Trailer ID: </label>
			<input type="text" id = "film_trailer" name = "film_trailer" value = "<?php echo $filmObj->trailer;?>"><br>
			<label>Video Provider: </label>
			<select name="film_video_provider" id="">
				<?php 
					if ($filmObj->video_provider == 'youtube'){
						echo '<option value="youtube" selected>Youtube</option>
						<option value="vimeo">Vimeo</option>';
					}
					else {
						echo '<option value="youtube">Youtube</option>
						<option value="vimeo" selected>Vimeo</option>';
					}
				?>
			</select><br>
			<label>Directeur: </label>
			<input type="text" id = "film_director" name = "film_director" value = "<?php echo $filmObj->director;?>"><br>
			<label>Pays: </label>
			<input type="text" id = "film_country" name = "film_country" value = "<?php echo $filmObj->country;?>"><br>
			<label>Année: </label>
			<input type="text" id = "film_year" name = "film_year" value = "<?php echo $filmObj->year;?>"><br>
			<label>Durée: </label>
			<input type="text" id = "film_duration" name = "film_duration" value = "<?php echo $filmObj->duration;?>"><br>
			<label>Langue: </label>
			<input type="text" id = "film_language" name = "film_language" value = "<?php echo $filmObj->language;?>"><br>
			<label>Acteurs: </label>
			<input type="text" id = "film_actors" name = "film_actors" value = "<?php echo $filmObj->actors;?>"><br>
			<label>En présence de: </label>
			<input type="text" id = "film_presence" name = "film_presence" value = "<?php echo $filmObj->presence;?>"><br>
			<label>Synopsis: </label><br>
			<?php wp_editor( $filmObj->synopsis, 'desired_id_of_textarea', $settings = array('textarea_name'=>'film_synopsis') ); ?> 
			<label>Photo</label>
			<input id="image-title-url" type="text" name="image-title" value = "<?php echo $filmObj->photo;?>"/>
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display" src = "<?php echo $filmObj->photo;?>"><br>			
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $filmListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>

		</form>

	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de ce Film: <?php echo $my_film_language_code;?></p>
		<p>Lien de ce Film : <?php apply_filters( 'wpml_element_link', $filmObj->wp_post_id, 'movie' ); ?></p>

		<div>
			<?php 
				if($my_film_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_movies ) ) {
					    foreach( $translations_movies as $key_trans => $translated_movie ) { 
					    	if($translated_movie->language_code != $actual_lang){	
					    		//print_r($translated_movie->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_film where wp_post_id = $translated_movie->element_id");
					    		?>
					        <li>Film Traduction (<?php echo $translated_movie->language_code;?>) : <a href="<?php  the_permalink($translated_movie->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_movie->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-un-film&id='.$translation.'&lang='.$translated_movie->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_movie->post_title;?></a></li>
					    
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
					<form action="<?php echo $modFilmUrl;?>" method="post">
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

