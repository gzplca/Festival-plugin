<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';

	$path_update = 'admin.php?page=modifier-un-artiste&id='.$_GET['id'];
	$modArtistUrl = admin_url($path_update);
	$path_list = 'admin.php?page=artiste';
	$artistListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$artistObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_artist where id = $_GET[id]");

	$the_query = new WP_Query( array( 'post_type' => 'artist', 'p' =>  $artistObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $artistObj->wp_post_id, 'element_type' => 'artist' );
	$my_artist_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_artist_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_artist_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $artistObj->wp_post_id, 'artist' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$translations_artists = apply_filters( 'wpml_get_element_translations', NULL, $my_artist_language_info->trid, 'post_artist' );


	if ( !empty( $_POST['image-title'] ) ) {
	    $image_url = $_POST['image-title'];
	    $wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}
	if ( !empty( $_POST['image-desc'] ) ) {
	    $image_url = $_POST['image-desc'];
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

		$artistObj_to_update = $wpdb->get_row("select * from ".$wpdb->prefix."prog_artist where id = $_GET[id]");

			$wpdb->update( 
				$wpdb->prefix.'prog_artist', 
				array( 
					'description' => $_POST['artist_desc'], 
					'photo_title' => $_POST['image-title'],
					'trailer' => $_POST['artist_trailer'], 
	 				'video_provider'  => $_POST['artist_video_provider'],
					'photo_desc'  => $_POST['image-desc'],
					'artist_name' => $_POST['artist_name']
				),
				array( 'id' => $_GET['id'] ),  
				array( 
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
		      	'ID'           => $artistObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['artist_name'],
		      	'post_name' => sanitize_title($_POST['artist_name']),
		  	);
 
  			wp_update_post( $my_post );

			reload();
	}

	function duplicate(){
		global $wpdb;
		$artistObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_artist where id = $_GET[id]");
		$id_post = $artistObj->wp_post_id;
		$title_original = $artistObj->artist_name;
		$args_wpml_original_post = array('element_id' => $artistObj->wp_post_id, 'element_type' => 'artist' );
		$original_artist_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $artistObj->edition_id");
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
				$wpdb->prefix.'prog_artist',
				array( 
					'description' 		=> $artistObj->description, 
					'photo_title' 		=> $artistObj->photo_title,
					'trailer'			=> $artistObj->trailer,
					'video_provider'	=> $artistObj->video_provider,
					'photo_desc'  		=> $artistObj->photo_desc,
					'artist_name' 		=> $artistObj->artist_name,
					'edition_id' 		=> $editionObj_select_lang->id, 
					'lang_code' => $_POST['lang_duplicate']	
				), 
				array( 
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s' 
				)
		);

		$new_translated_artist_id = $wpdb->insert_id;

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'artist',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_artist',
            'trid'   => $original_artist_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_artist_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_artist', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_artist_id ),   
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
			var mediaUploader1;
			$('#desc-upload-button').click(function(e) {
				e.preventDefault();
				// If the uploader object has already been created, reopen the dialog
				  if (mediaUploader1) {
				  mediaUploader1.open();
				  return;
				}
				// Extend the wp.media object
				mediaUploader1 = wp.media.frames.file_frame = wp.media({
				  title: 'Choose Image',
				  button: {
				  text: 'Choose Image'
				}, multiple: false });

				// When a file is selected, grab the URL and set it as the text field's value
				mediaUploader1.on('select', function() {
				  var attachment = mediaUploader1.state().get('selection').first().toJSON();
				  $('#image-desc-url').val(attachment.url);
				  $('#image-desc-display').attr({src: attachment.url});
				});
				// Open the uploader dialog
				mediaUploader1.open();
			});
		});
</script>


<?php
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();

		
?>

<div class="prog_fl_page">

	<h1 class="title_page">Modifier un Artiste</h1>

	<div class="box_update_form">

		<form action="<?php echo $modArtistUrl;?>" method="post">

			<label>Le nom de artiste: </label>
			<input type="text" id = "artist_name" name = "artist_name" value = "<?php echo $artistObj->artist_name;?>"><br>
			<label>Photo du titre (Suggérer la taille: 960 * 480)</label>
			<input id="image-title-url" type="text" name="image-title" value = "<?php echo $artistObj->photo_title;?>"/>
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display" src = "<?php echo $artistObj->photo_title;?>"><br>
			<label>Photo de description (Suggérer la taille: 200 * 300)</label>
			<input id="image-desc-url" type="text" name="image-desc" value = "<?php echo $artistObj->photo_desc;?>"/>
			<input id="desc-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-desc-display" src = "<?php echo $artistObj->photo_desc;?>"><br>
			<?php wp_editor( $artistObj->description, 'desired_id_of_textarea', $settings = array('textarea_name'=>'artist_desc') ); ?> 
			<label>Trailer ID: </label>
			<input type="text" id = "artist_trailer" name = "artist_trailer" value = "<?php echo $artistObj->trailer;?>"><br>
			<label>Video Provider: </label>
			<select name="artist_video_provider" id="">
				<option value="Youtube" <?php if($artistObj->video_provider == 'Youtube'){ echo 'selected';} ?> >Youtube</option>
				<option value="Vimeo" <?php if($artistObj->video_provider == 'Vimeo'){ echo 'selected';} ?>>Vimeo</option>
			</select><br>
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $artistListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>

		</form>

	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cet Artiste: <?php echo $my_artist_language_code;?></p>
		<p>Lien de cet Artiste: <?php apply_filters( 'wpml_element_link', $artistObj->wp_post_id, 'artist' ); ?></p>

		<div>
			<?php 
				if($my_artist_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_artists ) ) {
					    foreach( $translations_artists as $key_trans => $translated_artist ) { 
					    	if($translated_artist->language_code != $actual_lang){	
					    		//print_r($translated_artist->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_artist where wp_post_id = $translated_artist->element_id");
					    		?>
					        <li>Artiste Traduction (<?php echo $translated_artist->language_code;?>) : <a href="<?php  the_permalink($translated_artist->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_artist->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-un-artiste&id='.$translation.'&lang='.$translated_artist->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_artist->post_title;?></a></li>
					    
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
					<form action="<?php echo $modArtistUrl;?>" method="post">
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


