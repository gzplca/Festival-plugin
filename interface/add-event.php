<?php
	$_POST = array_map( 'stripslashes_deep', $_POST ); 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-un-evenement';
	$addEventUrl = admin_url($path);
	$path = 'admin.php?page=evenement';
	$eventListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_presentator_logo";
	$presenter_logosObj = $wpdb->get_results((string)$str_request);

	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."' order by id DESC";
	$editionsObj = $wpdb->get_results((string)$str_request);	

	$str_request = "select * from ".$wpdb->prefix."prog_artist where lang_code= '".(string)$actual_lang."'";
	$artistsObj = $wpdb->get_results((string)$str_request);

	$str_request = "select * from ".$wpdb->prefix."prog_film where lang_code= '".(string)$actual_lang."' order by id DESC";
	$filmsObj = $wpdb->get_results((string)$str_request);

	$str_request = "select * from ".$wpdb->prefix."prog_venue where lang_code= '".(string)$actual_lang."'";
	$placesObj = $wpdb->get_results((string)$str_request);
	
	if ( !empty( $_POST['image-title'] ) ) {
	    $image_url = $_POST['image-title'];
	    $wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}
	if ( !empty( $_POST['image-desc'] ) ) {
	    $image_url = $_POST['image-desc'];
	    $wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}

	if (isset($_REQUEST['insert'])) {
		insert();
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

	function goToList(){
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=evenement';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		$artists = ($_POST['event_artists']) ;
		$films = ($_POST['event_films']);
		$presentator_logs = $_POST['event-presentator-logo'];

		global $wpdb;

		//$currenteid = $wpdb->get_var("select max(id) from wp_prog_event") + 1;
	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_event', 
	 		array(
	 			'edition_id' =>  $_POST['event_edition'],  
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
	 			'lang_code' => $_POST['event_lang']
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

		$new_event_id = $wpdb->insert_id;

		//print_r($new_event_id);

	 	if ($artists != '') {
	 		foreach ($artists as $artist) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_artist', 
			 		array( 
			 			'artist_id' => $artist, 
			 			'event_id' => $new_event_id
			 		), 
			 		array( 
			 			'%d', 
			 			'%d' 
			 		) 
			 	);	
			};
		}

		if ($films != '') {
	 		foreach ($films as $film) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_film', 
			 		array( 
			 			'film_id' => $film, 
			 			'event_id' => $new_event_id
			 		), 
			 		array( 
			 			'%d', 
			 			'%d' 
			 		) 
			 	);	
			};
		}

		if ($presentator_logs != '') {
	 		foreach ($presentator_logs as $presentator_log) {
	 			$wpdb->insert( 
			 		$wpdb->prefix.'prog_event_presentator_logo', 
			 		array( 
			 			'presentator_logo_id' => $presentator_log, 
			 			'event_id' => $new_event_id
			 		), 
			 		array( 
			 			'%d', 
			 			'%d' 
			 		) 
			 	);	
			};
		}

	 	$event_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_event where id = $new_event_id");

	 	//print_r($event_obj);

		$title_formated = wp_strip_all_tags( $_POST['event_title'] );

		//print_r($new_event_id);

		$my_post = array(
			'post_title'    => $title_formated,
			'post_content'  => '   ',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_parent' 	=> 0,
			'post_type'		=> 'event',
		);

		$postid = wp_insert_post( $my_post, true );

		//print_r($new_event_id);

		$wpdb->update( 
			$wpdb->prefix.'prog_event', 
			array( 
				'wp_post_id' => $postid 
			),
			array( 'id' => $new_event_id ),   
			array( 
				'%d'
			),
			array( '%d' )  
		);

		goToList();
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
	
<div class="prog_fl_page">
	<h1>Ajouter un Evenement</h1>
	<div class="box_form_add">
		<form action="<?php echo $addEventUrl;?>" method="post" accept-charset="utf-8">
			<input type="text" name ="event_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "event_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "event_title" name = "event_title"><br>
			<label>Sous-titre: </label>
			<input type="text" id = "event_subtitle" name = "event_subtitle"><br>
			<label>Subjet: </label>
			<input type="text" id = "event_subject" name = "event_subject"><br>
			<label>Trailer ID: </label>
			<input type="text" id = "event_trailer" name = "event_trailer"><br>
			<label>Video Provider: </label>
			<select name="event_video_provider" id="">
				<option value="Youtube">Youtube</option>
				<option value="Vimeo">Vimeo</option>
			</select><br>
			<label>Présenté par: </label>
			<input type="text" id = "event_presentator" name = "event_presentator"><br>
			<label>Presentator logo(s): </label>
			<select name = "event-presentator-logo[]" multiple="multiple">
				<?php 
					foreach ($presenter_logosObj as $presenatator_logoObj) {
						echo '<option value = '.$presenatator_logoObj->id.'>'.$presenatator_logoObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Type: </label>
			<select name="event_type" id="">
				<option value="Screening">Screening</option>
				<option value="Special">Special</option>
				<option value="Community Program">Community Program</option>
			</select><br>
			<label>Jour: </label>
			<input type="text" id = "event_date" name = "event_date"><br>
			<label>Heure: </label>
			<input type="text" id = "event_start_time" name = "event_start_time"><br>
			<label>Lieu: </label>
			<select name = "event-place">
				<?php 
					foreach ($placesObj as $placeObj) {
						echo '<option value = '.$placeObj->id.'>'.$placeObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Artists: </label>
			<select name = "event_artists[]" multiple="multiple">
				<?php 
					foreach ($artistsObj as $artistObj) {
						echo '<option value = '.$artistObj->id.'>'.$artistObj->artist_name.'</option>';
					} 
				?>
			</select><br>
			<label>Films: </label>
			<select name = "event_films[]" multiple="multiple">
				<?php 
					foreach ($filmsObj as $filmObj) {
						echo '<option value = '.$filmObj->id.'>'.$filmObj->title.'</option>';
					} 
				?>
			</select><br>
			<b>Maintenez le bouton Ctrl (Windows) / Commande (Mac) enfoncé pour sélectionner plusieurs options.</b><br>
			<label>Lien pour le ticket d'achat: </label>
			<input type="text" id = "event_ticket_link" name = "event_ticket_link"><br>
			<label>Description pour le ticket: </label>
			<input type="text" id = "event_ticket_desc" name = "event_ticket_desc"><br>
			<label>Lien pour le ticket 2 d'achat: </label>
			<input type="text" id = "event_ticket2_link" name = "event_ticket2_link"><br>
			<label>Description pour le ticket 2: </label>
			<input type="text" id = "event_ticket2_desc" name = "event_ticket2_desc"><br>
			<!-- <label>Description pour le ticket(plus): </label>
			<input type="text" id = "event_ticket_more_desc" name = "event_ticket_more_desc"><br> -->
			<!-- <label>Lien pour la vidéo: </label>
			<input type="text" id = "event_video_link" name = "event_video_link"><br> -->
			<label>Photo du titre (Suggérer la taille: 960 * 480)</label>
			<input id="image-title-url" type="text" name="image-title" />
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display"><br>
			<label>Photo de description (Suggérer la taille: 300 * 220)</label>
			<input id="image-desc-url" type="text" name="image-desc" />
			<input id="desc-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-desc-display"><br>
			<label>La description: </label><br>
			<?php wp_editor( '' , 'desired_id_of_textarea', $settings = array('textarea_name'=>'event_desc') ); ?> 
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $eventListUrl;?>" class="btn btn-mini btn-warning">Retournez</a></div>
		</form>
	</div>
</div>




