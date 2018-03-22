<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-un-artiste';
	$addArtistUrl = admin_url($path);
	$path = 'admin.php?page=artiste';
	$artistListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	
	global $wpdb;

	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	$editionsObj = $wpdb->get_results((string)$str_request);
	

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

	function goToList()
	{
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=artiste';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		global $wpdb;
		$wpdb->insert( 
			$wpdb->prefix.'prog_artist', 
			array(
				'edition_id' =>  $_POST['artist_edition'],  
				'description' => $_POST['artist_desc'], 
				'photo_title' => $_POST['image-title'],
				'photo_desc'  => $_POST['image-desc'],
				'trailer' => $_POST['artist_trailer'], 
	 			'video_provider'  => $_POST['artist_video_provider'],
				'artist_name' => $_POST['artist_name'],
 				'lang_code' => $_POST['artist_lang']
			), 
			array( 
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

		$new_artist_id = $wpdb->insert_id;

 		$artist_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_artist where id = $new_artist_id");

		$title_formated = wp_strip_all_tags( $artist_obj->artist_name );

		$my_post = array(
			'post_title'    => $title_formated,
			'post_content'  => '   ',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_parent' 	=> 0,
			'post_type'		=> 'artist',
		);

		$postid = wp_insert_post( $my_post, true );


		$wpdb->update( 
			$wpdb->prefix.'prog_artist', 
			array( 
				'wp_post_id' => $postid 
			),
			array( 'id' => $new_artist_id ),   
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
	<h1>Ajouter un Artiste</h1>
	<div class="">
		<form action="<?php echo $addArtistUrl;?>" method="post">
			<input type="text" name ="artist_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "artist_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Le nom de artiste: </label>
			<input type="text" id = "artist_name" name = "artist_name"><br>
			<label>Photo du titre (Suggérer la taille: 960 * 480)</label>
			<input id="image-title-url" type="text" name="image-title" />
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display"><br>
			<label>Photo de description (Suggérer la taille: 200 * 300)</label>
			<input id="image-desc-url" type="text" name="image-desc" />
			<input id="desc-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-desc-display"><br>
			<label>La description: </label><br>
			<?php wp_editor( '' , 'desired_id_of_textarea', $settings = array('textarea_name'=>'artist_desc') ); ?>
			<label>Trailer ID: </label>
			<input type="text" id = "artist_trailer" name = "artist_trailer"><br>
			<label>Video Provider: </label>
			<select name="artist_video_provider" id="">
				<option value="Youtube">Youtube</option>
				<option value="Vimeo">Vimeo</option>
			</select><br>
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $artistListUrl;?>" class="btn btn-mini btn-warning">RetourRetournez</a></div>
		</form>
	</div>
</div>


