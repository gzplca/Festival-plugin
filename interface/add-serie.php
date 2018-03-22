<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-une-serie';
	$addSerieUrl = admin_url($path);
	$path = 'admin.php?page=series';
	$serieListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_event where lang_code= '".(string)$actual_lang."'";
	$eventsObj = $wpdb->get_results((string)$str_request);

	

	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	$editionsObj = $wpdb->get_results((string)$str_request);
	
	if ( !empty( $_POST['image-title'] ) ) {
	    $image_url = $_POST['image-title'];
	    $wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}

	if (isset($_REQUEST['insert'])) {
		insert();
	}

	function goToList()
	{
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=series';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		$events = $_POST['serie_events'];
		//print_r($events );
		
		global $wpdb;
		
		//$serieObj = $wpdb->get_results("select * from wp_prog_serie where id = $_GET[id]");

	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_serie', 
	 		array(
	 			'edition_id' =>  $_POST['serie_edition'], 
	 			'title' => $_POST['serie_title'], 
	 			'description' => $_POST['serie_description'], 
	 			'photo'  => $_POST['image-title'],
	 			'lang_code' => $_POST['serie_lang']
	 		), 
	 		array( 
	 			'%s', 
	 			'%s',
	 			'%s',
	 			'%s',
	 			'%s'
	 		) 
	 	);

	 	$new_serie_id = $wpdb->insert_id;

	 	if ($events != '') {
	 		foreach ($events as $event) {
	 	 		$wpdb->insert( 
			  		$wpdb->prefix.'prog_event_serie', 
		  			array( 
		  				'serie_id' => $new_serie_id, 
		  				'event_id' => (int)$event
		  		), 
		  			array( 
		  				'%d', 
		  				'%d' 
		  			) 
		  		);	
			};
	 	}

	 	

	 	$serie_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_serie where id = $new_serie_id");

		//$title_formated = wp_strip_all_tags( $_POST['serie_title'] );

		$my_post = array(
			'post_title'    => $_POST['serie_title'],
			'post_content'  => '   ',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_parent' 	=> 0,
			'post_type'		=> 'serie',
		);

		$postid = wp_insert_post( $my_post, true );


		$wpdb->update( 
			$wpdb->prefix.'prog_serie', 
			array( 
				'wp_post_id' => $postid 
			),
			array( 'id' => $new_serie_id ),   
			array( 
				'%d'
			),
			array( '%d' )  
		);

		goToList();
		
	 	
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
	
<div class="prog_fl_page">
	<h1>Ajouter une série</h1>
	<div class="box_form_add">
		<form action="<?php echo $addSerieUrl;?>" method="post">
			<input type="text" name ="serie_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "serie_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "serie_title" name = "serie_title"><br>
			<label>Description: </label><br>
			<?php wp_editor( '' , 'desired_id_of_textarea', $settings = array('textarea_name'=>'serie_description') ); ?>
			<label>Événements: </label>
			<select name = "serie_events[]" multiple="multiple">
				<?php 
					foreach ($eventsObj as $eventObj) {
						echo '<option value = '.$eventObj->id.'>'.$eventObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Photo</label>
			<input id="image-title-url" type="text" name="image-title" />
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display"><br>			
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $serieListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>





