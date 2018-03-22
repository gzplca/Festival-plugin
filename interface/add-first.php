<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	$path = 'admin.php?page=ajouter-une-premiere';
	$addSerieUrl = admin_url($path);
	$path = 'admin.php?page=premiere';
	$firstListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;

	global $wpdb;

	$str_request = "select * from ".$wpdb->prefix."prog_edition where lang_code= '".(string)$actual_lang."'";
	$editionsObj = $wpdb->get_results((string)$str_request);
	
	if (isset($_REQUEST['insert'])) {
		insert();
	}

	function goToList()
	{
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=premiere';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {		
		global $wpdb;
	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_first', 
	 		array(
	 			'edition_id' =>  $_POST['first_edition'], 
	 			'title' => $_POST['first_title'], 
	 			'lang_code' => $_POST['first_lang']
	 		), 
	 		array( 
	 			'%s', 
	 			'%s',
	 			'%s'
	 		) 
	 	);

	 	$new_first_id = $wpdb->insert_id;

	 	$first_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_first where id = $new_first_id");

		$title_formated = wp_strip_all_tags( $first_obj->title );

		$my_post = array(
			'post_title'    => $title_formated,
			'post_content'  => '   ',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_parent' 	=> 0,
			'post_type'		=> 'movie_first',
		);

		$postid = wp_insert_post( $my_post, true );


		$wpdb->update( 
			$wpdb->prefix.'prog_first', 
			array( 
				'wp_post_id' => $postid 
			),
			array( 'id' => $new_first_id ),   
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
	<h1>Ajouter une première</h1>
	<div class="box_form_add">
		<form action="<?php echo $addSerieUrl;?>" method="post">
			<input type="text" name ="first_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "first_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Titre: </label>
			<input type="text" id = "first_title" name = "first_title"><br>
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $firstListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>





