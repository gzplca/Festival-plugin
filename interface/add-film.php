<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-un-film';
	$addFilmUrl = admin_url($path);
	$path = 'admin.php?page=film';
	$filmListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;
	$str_request = "select * from ".$wpdb->prefix."prog_category where lang_code= '".(string)$actual_lang."'";
	$categoriesObj = $wpdb->get_results((string)$str_request);
	
	$str_request = "select * from ".$wpdb->prefix."prog_first where lang_code= '".(string)$actual_lang."'";
	$firstsObj = $wpdb->get_results((string)$str_request);

	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."' order by id DESC";
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
	    $string .= "location.href='admin.php?page=film';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {		
		global $wpdb;
	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_film', 
	 		array(
	 			'edition_id' =>  $_POST['film_edition'], 
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
	 			'synopsis'  => $_POST['film_synopsis'],
	 			'lang_code' => $_POST['film_lang']
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
	 			'%s' 
	 		) 
	 	);

	 	$new_film_id = $wpdb->insert_id;

	 	$film_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_film where id = $new_film_id");

			$title_formated = wp_strip_all_tags( $film_obj->title );

			$my_post = array(
				'post_title'    => $title_formated,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'movie',
			);

			$postid = wp_insert_post( $my_post, true );


			$wpdb->update( 
				$wpdb->prefix.'prog_film', 
				array( 
					'wp_post_id' => $postid 
				),
				array( 'id' => $new_film_id ),   
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
	<h1>Ajouter un Film</h1>
	<div class="box_form_add">
		<form action="<?php echo $addFilmUrl;?>" method="post">
			<input type="text" name ="film_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "film_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Catégorie: </label>
			<select name = "film_category">
				<?php 
					foreach ($categoriesObj as $categoryObj) {
						echo '<option value = '.$categoryObj->id.'>'.$categoryObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Première du film: </label>
			<select name = "film_first">
				<?php 
					foreach ($firstsObj as $firstObj) {
						echo '<option value = '.$firstObj->id.'>'.$firstObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "film_title" name = "film_title"><br>
			<label>Trailer ID: </label>
			<input type="text" id = "film_trailer" name = "film_trailer"><br>
			<label>Video Provider: </label>
			<select name="film_video_provider" id="">
				<option value="youtube">Youtube</option>
				<option value="vimeo">Vimeo</option>
			</select><br>
			<label>Directeur: </label>
			<input type="text" id = "film_director" name = "film_director"><br>
			<label>Pays: </label>
			<input type="text" id = "film_country" name = "film_country"><br>
			<label>Année: </label>
			<input type="text" id = "film_year" name = "film_year"><br>
			<label>Durée: </label>
			<input type="text" id = "film_duration" name = "film_duration"><br>
			<label>Langue: </label>
			<input type="text" id = "film_language" name = "film_language"><br>
			<label>Acteurs: </label>
			<input type="text" id = "film_actors" name = "film_actors"><br>
			<label>En présence de: </label>
			<input type="text" id = "film_presence" name = "film_presence"><br>
			<label>Synopsis: </label><br>
			<?php wp_editor( '' , 'desired_id_of_textarea', $settings = array('textarea_name'=>'film_synopsis') ); ?> 
			<label>Photo</label>
			<input id="image-title-url" type="text" name="image-title" />
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display"><br>			
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $filmListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>




