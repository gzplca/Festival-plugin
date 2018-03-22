<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path_update = 'admin.php?page=modifier-une-serie&id='.$_GET['id'];
	$modSerieUrl = admin_url($path_update);
	$path_list = 'admin.php?page=series';
	$serieListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;

	//$serieObj = $wpdb->get_row("select * from wp_prog_serie where id =".$_GET['id']);
	$serieObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_serie where id = $_GET[id]");

	//print_r($serieObj);

	$str_request_event = "select * from ".$wpdb->prefix."prog_event where lang_code= '".(string)$actual_lang."'";
	$eventsObj = $wpdb->get_results((string)$str_request_event);
	//$eventsObj = $wpdb->get_results("select * from wp_prog_event where lang_code =".$actual_lang);
	//print_r($eventsObj);

	//$editionsObj = $wpdb->get_results("select * from wp_prog_edition");

	$selectEventsArr = $wpdb->get_col("select event_id from ".$wpdb->prefix."prog_event_serie where serie_id = ".$_GET['id']);
	//print_r($selectEventsArr);

	$the_query = new WP_Query( array( 'post_type' => 'serie', 'p' =>  $serieObj->wp_post_id) );

	//print_r($the_query );

	$args_request_wpml = array('element_id' => $serieObj->wp_post_id, 'element_type' => 'serie' );
	$my_serie_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_serie_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_serie_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $serieObj->wp_post_id, 'serie' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );


	$translations_series = apply_filters( 'wpml_get_element_translations', NULL, $my_serie_language_info->trid, 'post_serie' );
	
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
		$events = $_POST['serie_events'];

		//print_r($events );
		
		
		global $wpdb;


		$serieObj_to_update = $wpdb->get_row("select * from ".$wpdb->prefix."prog_serie where id = $_GET[id]");

	 	$wpdb->update( 
	 		$wpdb->prefix.'prog_serie', 
	 		array(
	 			'title' => $_POST['serie_title'], 
	 			'description' => $_POST['serie_description'], 
	 			'photo'  => $_POST['image-title']
	 		),
	 		array( 'id' => $_GET['id'] ),  
	 		array( 
	 			'%s',
	 			'%s',
	 			'%s'
	 		),
	 		array( '%d' ) 
	 	);

	 	$my_post = array(
		      	'ID'           => $serieObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['serie_title'],
		      	'post_name' => sanitize_title($_POST['serie_title']),
		);
 
  		wp_update_post( $my_post );


	 	$selectEventsArr = $wpdb->get_results("select * from ".$wpdb->prefix."prog_event_serie where serie_id = ".$_GET['id']);
		//print_r($selectEventsArr);

	 	foreach ($selectEventsArr as $event) {
	 			//print_r($event);
	 			$wpdb->delete( $wpdb->prefix.'prog_event_serie', array('event_id' => (int)$event->event_id, 'serie_id' => $_GET['id'] ) );
	 		}


	 	if ($events != '') {
	 		foreach ($events as $event) {
	 			//print_r($event);
	 			//print_r($serieObj_to_update->id);
	 			
	 	 		$wpdb->insert( 
			  		$wpdb->prefix.'prog_event_serie', 
		  			array( 
		  				'serie_id' => $serieObj_to_update->id, 
		  				'event_id' => (int)$event
		  		), 
		  			array( 
		  				'%d', 
		  				'%d' 
		  			) 
		  		);	
		  		
			};
	 	}

	 	reload();
	 	
	 	
	}

	function duplicate(){
		global $wpdb;
		$serieObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_serie where id = ".$_GET['id']);
		$id_post = $serieObj->wp_post_id;
		$title_original = $serieObj->title;
		$args_wpml_original_post = array('element_id' => $serieObj->wp_post_id, 'element_type' => 'serie' );
		$original_serie_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $serieObj->edition_id");
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
				$wpdb->prefix.'wp_prog_serie',
				array( 
					'title' 		=> $serieObj->title,
		 			'description' => $serieObj->description, 
		 			'photo'  => $serieObj->photo,
					'edition_id' 		=> $editionObj_select_lang->id, 
					'lang_code' => $_POST['lang_duplicate']	
				), 
				array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s' 
				)
		);

		$new_translated_serie_id = $wpdb->insert_id;

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'serie',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_serie',
            'trid'   => $original_serie_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_serie_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_serie', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_serie_id ),   
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
		});
</script>


<?php
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();

		
?>


<div class="prog_fl_page">

	<h1 class="title_page">Modifier une série</h1>

	<div class="box_update_form">

		<form action="<?php echo $modSerieUrl;?>" method="post">
			<label>Titre: </label>
			<input type="text" id = "serie_title" name = "serie_title" value = "<?php echo $serieObj->title;?>"><br>
			<label>Description: </label><br>
			<?php wp_editor( $serieObj->description , 'desired_id_of_textarea', $settings = array('textarea_name'=>'serie_description') ); ?>
			<label>Événements: </label>
			<select name = "serie_events[]" multiple="multiple">
				<?php 
					foreach ($eventsObj as $eventObj) {
						$select = '';
						foreach ($selectEventsArr as $eventid) {
							if ($eventObj->id == $eventid){
								$select = 'selected';
							}
						}
						echo '<option value = '.$eventObj->id.' '.$select.'>'.$eventObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Photo</label>
			<input id="image-title-url" type="text" name="image-title" value = "<?php echo $serieObj->photo;?>"/>
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display" src ="<?php echo $serieObj->photo;?>"><br>			
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $serieListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>

		</form>

	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cette Série: <?php echo $my_serie_language_code;?></p>
		<p>Lien de cette Série: <?php apply_filters( 'wpml_element_link', $serieObj->wp_post_id, 'serie' ); ?></p>

		<div>
			<?php 
				if($my_serie_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_series ) ) {
					    foreach( $translations_series as $key_trans => $translated_serie ) { 
					    	if($translated_serie->language_code != $actual_lang){	
					    		//print_r($translated_serie->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_serie where wp_post_id = $translated_serie->element_id");
					    		?>
					        <li>Série Traduction (<?php echo $translated_serie->language_code;?>) : <a href="<?php  the_permalink($translated_serie->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_serie->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-une-serie&id='.$translation.'&lang='.$translated_serie->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_serie->post_title;?></a></li>
					    
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
					<form action="<?php echo $modSerieUrl;?>" method="post">
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




