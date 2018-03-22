<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';

	$path_update = 'admin.php?page=modifier-un-evenement&id='.$_GET['id'];
	$modEventUrl = admin_url($path_update);
	$path_list = 'admin.php?page=evenement';
	$eventListUrl = admin_url($path_list);

	$actual_lang = ICL_LANGUAGE_CODE;
	//print_r($actual_lang);

	global $wpdb;

	$str_request = "select * from ".$wpdb->prefix."prog_presentator_logo";
	$presenter_logosObj = $wpdb->get_results($str_request);
	//print_r($presenter_logosObj);
	//print_r($_GET[id]);
	$eventObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_event where id = $_GET[id]");
	//print_r($eventObj);

	$str_request_artist = "select * from ".$wpdb->prefix."prog_artist where lang_code= '".(string)$actual_lang."'";
	$artistsObj = $wpdb->get_results((string)$str_request_artist);
	//$artistsObj = $wpdb->get_results("select * from wp_prog_artist where lang_code = ".$actual_lang);
	//print_r($artistsObj);

	$str_request_film = "select * from ".$wpdb->prefix."prog_film where lang_code= '".(string)$actual_lang."'";
	$filmsObj = $wpdb->get_results((string)$str_request_film);


	$selectartistsObj = $wpdb->get_col("select artist_id from ".$wpdb->prefix."prog_event_artist where event_id = ".$_GET['id']);


	$selectPresLogosObj = $wpdb->get_col("select presentator_logo_id from ".$wpdb->prefix."prog_event_presentator_logo where event_id = ".$_GET['id']);
	//print_r($selectPresLogosObj);

	$selectFilmsObj = $wpdb->get_col("select film_id from ".$wpdb->prefix."prog_event_film where event_id = ".$_GET['id']);
	
	$str_request_place = "select * from ".$wpdb->prefix."prog_venue where lang_code= '".(string)$actual_lang."'";
	$placesObj = $wpdb->get_results((string)$str_request_place);
	//$placesObj = $wpdb->get_results("select * from wp_prog_venue where lang_code = ".$actual_lang);
	//print_r($placesObj);

	$the_query = new WP_Query( array( 'post_type' => 'event', 'p' =>  $eventObj->wp_post_id) );

	$args_request_wpml = array('element_id' => $eventObj->wp_post_id, 'element_type' => 'event' );
	$my_event_language_info = apply_filters( 'wpml_element_language_details', null, $args_request_wpml );
	$my_event_language_code = apply_filters( 'wpml_element_language_code', null, $args_request_wpml );
	$my_event_is_translated = apply_filters( 'wpml_element_has_translations', NULL, $eventObj->wp_post_id, 'event' );

	$all_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );

	
	$translations_events = apply_filters( 'wpml_get_element_translations', NULL, $my_event_language_info->trid, 'post_event' );


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

	function displaydate($date){
			$wholetime = strtotime($date);
			$month = date('F', $wholetime);
			$day = date('d', $wholetime);

			$monthdisplay = str_replace(
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
			    array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juilet', 'Août', 'Stembre', 'Octobre', 'Novembre', 'Décembre'),
			    $month
			);
			return $day.' '.$monthdisplay;
		}

	function reload()
	{
	    $string = '<script type="text/javascript">';
	    $string .= 'location.reload();';
	    $string .= '</script>';

	    echo $string;
	}


	function update() {

		$artists = $_POST['event_artists'] ;
		$logos = $_POST['event_presentator_logo'] ;
		$films = $_POST['event_films'] ;
		
		//print_r($films);
		

		global $wpdb;


		$eventObj_to_update = $wpdb->get_results("select * from ".$wpdb->prefix."prog_event where id = $_GET[id]");

	 	$wpdb->update( 
	 		$wpdb->prefix.'prog_event', 
	 		array( 
	 			'title' => $_POST['event_title'], 
	 			'date' => date("Y-m-d", strtotime($_POST['event_date'])),
	 			'venue_id'  => $_POST['event-place'],
	 			'start_time'  => $_POST['event_start_time'],
	 			'description' => $_POST['event_desc'],
	 			'ticket_link' => $_POST['event_ticket_link'], 
	 			'ticket_desc' => $_POST['event_ticket_desc'],
	 			'ticket2_link' => $_POST['event_ticket2_link'], 
	 			'ticket2_desc' => $_POST['event_ticket2_desc'],  
	 			'photo_small' => $_POST['image-desc'],
	 			'photo_big'  => $_POST['image-title'],
	 			'trailer' => $_POST['event_trailer'], 
	 			'video_provider'  => $_POST['event_video_provider'],
	 			'sub_title'  => $_POST['event_subtitle'],
	 			'subject' => $_POST['event_subject'],
	 			'type' => $_POST['event_type'],
	 			'presentator' => $_POST['event_presentator'],
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
	 			'%s',
	 			'%s',
	 			'%s' 
	 		),
	 		array( '%d' )  
	 	);

	 	$my_post = array(
		      	'ID'           => $eventObj_to_update->wp_post_id,
		      	'post_title'   => $_POST['event_title'],
		      	'post_name' => sanitize_title($_POST['event_title']),
		);
 
  		wp_update_post( $my_post );


	 	$selectartistsObj = $wpdb->get_col("select artist_id from ".$wpdb->prefix."prog_event_artist where event_id = ".$_GET['id']);

	 	foreach ($selectartistsObj as $artistid) {
	 			$wpdb->delete( $wpdb->prefix.'prog_event_artist', array('artist_id' => $artistid, 'event_id' => $_GET['id'] ) );
	 	}


	 	if ($artists != '') {	
	 		foreach ($artists as $artist) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_artist', 
			 		array( 
			 			'artist_id' => $artist, 
			 			'event_id' => $_GET['id']
			 		), 
			 		array( 
			 			'%d', 
			 			'%d' 
			 		) 
			 	);	
			};
	 	}

	 	$selectLogosObj = $wpdb->get_col("select presentator_logo_id from ".$wpdb->prefix."prog_event_presentator_logo where event_id = ".$_GET['id']);

	 	foreach ($selectLogosObj as $logoid) {
	 			$wpdb->delete( $wpdb->prefix.'prog_event_presentator_logo', array('presentator_logo_id' => (int)$logoid, 'event_id' => $_GET['id'] ) );
	 	}


	 	if ($logos != '') {	
	 		foreach ($logos as $logo) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_presentator_logo', 
			 		array( 
			 			'presentator_logo_id' => (int)$logo, 
			 			'event_id' => $_GET['id']
			 		), 
			 		array( 
			 			'%d', 
			 			'%d' 
			 		) 
			 	);	
			};
	 	}

	 	$selectFilmsObj = $wpdb->get_col("select film_id from ".$wpdb->prefix."prog_event_film where event_id = ".$_GET['id']);

	 	foreach ($selectFilmsObj as $filmid) {
	 			$wpdb->delete( $wpdb->prefix.'prog_event_film', array('film_id' => (int)$filmid, 'event_id' => $_GET['id'] ) );
	 	}


	 	if ($films != '') {	
	 		foreach ($films as $film) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_film', 
			 		array( 
			 			'film_id' => (int)$film, 
			 			'event_id' => $_GET['id']
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
		$eventObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_event where id = $_GET[id]");
		//print_r($eventObj);
		$id_post = $eventObj->wp_post_id;
		$title_original = $eventObj->title;
		//print_r($title_original);
		$args_wpml_original_post = array('element_id' => $eventObj->wp_post_id, 'element_type' => 'event' );
		$original_event_language_info = apply_filters( 'wpml_element_language_details', null, $args_wpml_original_post );

		$editionObj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $eventObj->edition_id");
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
				$wpdb->prefix.'prog_event',
				array( 
		 			'title' => $eventObj->title, 
		 			'date' => $eventObj->date,
		 			'start_time'  => $eventObj->start_time,
		 			'description' => $eventObj->description,
		 			'ticket_link' => $eventObj->ticket_link, 
		 			'ticket_desc' => $eventObj->ticket_desc,
		 			'ticket2_link' => $eventObj->ticket2_link, 
		 			'ticket2_desc' => $eventObj->ticket2_desc, 
		 			'photo_small' => $eventObj->photo_small,
		 			'photo_big'  => $eventObj->photo_big,
		 			'video_address' => $eventObj->video_address,
		 			'video_provider' => $eventObj->video_provider,
		 			'trailer' => $eventObj->trailer,
		 			'type' => $eventObj->type,
		 			'presentator' => $eventObj->presentator,
					'edition_id' 		=> $editionObj_select_lang->id, 
					'lang_code' => $_POST['lang_duplicate'],
					'sub_title'  => $eventObj->sub_title,
					'subject'  => $eventObj->subject,	
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
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s' 
				)
		);

		$new_translated_event_id = $wpdb->insert_id;
		//print_r($new_translated_event_id);

		$my_translated_post = array(
				'post_title'    => $title_original,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'event',
		);

		$translated_postid = wp_insert_post( $my_translated_post, true );

		$set_translated_language_args = array(
            'element_id'    => $translated_postid,
            'element_type'  => 'post_event',
            'trid'   => $original_event_language_info->trid,
            'language_code'   => $_POST['lang_duplicate'],
            'source_language_code' => $original_event_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_translated_language_args );

		$wpdb->update( 
				$wpdb->prefix.'prog_event', 
				array( 
					'wp_post_id' => $translated_postid 
				),
				array( 'id' => $new_translated_event_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
		);
		
		reload();
		
	}

	
?>

<script>
		jQuery( function() {
			jQuery( "#event_date" ).datepicker();
		} );
</script>

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

	<h1 class="title_page">Modifier un Evenements</h1>

	<div class="box_update_form">

		<form action="<?php echo $modEventUrl;?>" method="post">

			<label>Titre: </label>
			<input type="text" id = "event_title" name = "event_title" value="<?php echo $eventObj->title;?>"><br>
			<label>Sous-titre: </label>
			<input type="text" id = "event_subtitle" name = "event_subtitle" value = "<?php echo $eventObj->sub_title;?>"><br>
			<label>Subjet: </label>
			<input type="text" id = "event_subject" name = "event_subject" value = "<?php echo $eventObj->subject;?>"><br>
			<label>Trailer ID: </label>
			<input type="text" id="event_trailer" name="event_trailer" value="<?php echo $eventObj->trailer;?>"><br>
			<label>Video Provider: </label>
			<select name="event_video_provider" id="">
				<option value="Youtube" <?php if($eventObj->video_provider == 'Youtube'){ echo 'selected';} ?> >Youtube</option>
				<option value="Vimeo" <?php if($eventObj->video_provider == 'Vimeo'){ echo 'selected';} ?>>Vimeo</option>
			</select><br>
			<label>Présenté par: </label>
			<input type="text" id = "event_presentator" name = "event_presentator" value="<?php echo $eventObj->presentator;?>"><br>
			<label>Presentator logo(s): </label>
			<select name = "event_presentator_logo[]" multiple="multiple">
				<?php 
					foreach ($presenter_logosObj as $presenter_logoObj) {
						$select = '';
						foreach ($selectPresLogosObj as $presentator_logo_id) {
							if ($presenter_logoObj->id == $presentator_logo_id){
								$select = 'selected';
							}
						}
						echo '<option value = '.$presenter_logoObj->id.' '.$select.'>'.$presenter_logoObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Type: </label>
			<select name="event_type" id="">
				<option value="Screening" <?php if($eventObj->type == 'Screening'){ echo 'selected';} ?> >Screening</option>
				<option value="Special" <?php if($eventObj->type == 'Special'){ echo 'selected';} ?> >Special</option>
				<option value="Community Program" <?php if($eventObj->type == 'Community Program'){ echo 'selected';} ?> >Community Program</option>
			</select><br>

			<label>Jour: </label>
			<input type="text" id = "event_date" name = "event_date" value = "<?php echo $eventObj->date;?>"><br>
			<label>Heure: </label>
			<input type="text" id = "event_start_time" name = "event_start_time" value = "<?php echo $eventObj->start_time;?>"
			><br>
			<label>Lieu: </label>
			<select name = "event-place" >
				<?php 
					foreach ($placesObj as $placeObj) {
						$select = '';
						if ($placeObj->id == $eventObj->venue_id){
							$select = 'selected';
						}
						echo '<option value = '.$placeObj->id.' '.$select.' >'.$placeObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Artists: </label>
			<select name = "event_artists[]" multiple="multiple">
				<?php 
					foreach ($artistsObj as $artistObj) {
						$select = '';
						foreach ($selectartistsObj as $artistid) {
							if ($artistObj->id == $artistid){
								$select = 'selected';
							}
						}
						echo '<option value = '.$artistObj->id.' '.$select.'>'.$artistObj->artist_name.'</option>';
					} 
				?>
			</select><br>
			<label>Films: </label>
			<select name = "event_films[]" multiple="multiple">
				<?php 
					foreach ($filmsObj as $filmObj) {
						$select = '';
						foreach ($selectFilmsObj as $filmid) {
							if ($filmObj->id == $filmid){
								$select = 'selected';
							}
						}
						echo '<option value = '.$filmObj->id.' '.$select.'>'.$filmObj->title.'</option>';
					} 
				?>
			</select><br>
			<b>Maintenez le bouton Ctrl (Windows) / Commande (Mac) enfoncé pour sélectionner plusieurs options.</b><br>
			<label>Lien pour le ticket d'achat: </label>
			<input type="text" id = "event_ticket_link" name = "event_ticket_link" value = "<?php echo $eventObj->ticket_link;?>"><br>
			<label>Description pour le ticket: </label>
			<input type="text" id = "event_ticket_desc" name = "event_ticket_desc" value = "<?php echo $eventObj->ticket_desc;?>"><br>
			<label>Lien pour le ticket 2 d'achat: </label>
			<input type="text" id = "event_ticket2_link" name = "event_ticket2_link" value = "<?php echo $eventObj->ticket2_link;?>"><br>
			<label>Description pour le ticket 2: </label>
			<input type="text" id = "event_ticket2_desc" name = "event_ticket2_desc" value = "<?php echo $eventObj->ticket2_desc;?>"><br>
			<!-- <label>Description pour le ticket(plus): </label>
			<input type="text" id = "event_ticket_more_desc" name = "event_ticket_more_desc" value = "<?php echo $eventObj->sub_title;?>"><br> -->
			<label>Lien pour la vidéo: </label>
			<input type="text" id = "event_video_link" name = "event_video_link" value = '<?php echo $eventObj->video_address;?>'><br>
			<label>Photo du titre (Suggérer la taille: 960 * 480)</label>
			<input id="image-title-url" type="text" name="image-title" value = "<?php echo $eventObj->photo_big;?>"/>
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display" src = "<?php echo $eventObj->photo_big;?>"><br>
			<label>Photo de description (Suggérer la taille: 300 * 220)</label>
			<input id="image-desc-url" type="text" name="image-desc" value = "<?php echo $eventObj->photo_small;?>"/>
			<input id="desc-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-desc-display" src = "<?php echo $eventObj->photo_small;?>"><br>
			<label>La description: </label><br>
			<?php wp_editor( $eventObj->description, 'desired_id_of_textarea', $settings = array('textarea_name'=>'event_desc') ); ?> 
			<input type="submit" name="update" value="Modifier" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $eventListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>

		</form>

	</div>

	<div id="lang_box_prog_fl">
		<h3>Language</h3>
		<p>Langue de cet Evénement: <?php echo $my_event_language_code;?></p>
		<p>Lien de cet Evénement: <?php apply_filters( 'wpml_element_link', $eventObj->wp_post_id, 'event' ); ?></p>

		<div>
			<?php 
				if($my_event_is_translated){ ?>  
					<!--<p>has translation</p>-->
					<ul>
					<?php 
					if ( !empty( $translations_events ) ) {
					    foreach( $translations_events as $key_trans => $translated_event ) { 
					    	if($translated_event->language_code != $actual_lang){	
					    		//print_r($translated_event->element_id);
					    		//$translation = 5;
					    		$translation = $wpdb->get_var("select id from ".$wpdb->prefix."prog_event where wp_post_id = $translated_event->element_id");
					    		?>
					        <li>Evénement Traduction (<?php echo $translated_event->language_code;?>) : <a href="<?php  the_permalink($translated_event->element_id);?>" target="_blank" class="btn btn-mini btn-info"> Voir <?php echo $translated_event->post_title;?></a> <a href="<?php  echo '/wp-admin/admin.php?page=modifier-un-evenement&id='.$translation.'&lang='.$translated_event->language_code.'&admin_bar=1' ;?>" class="btn btn-mini btn-primary"> Modifier <?php echo $translated_event->post_title;?></a></li>
					    
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
					<form action="<?php echo $modEventUrl;?>" method="post">
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
