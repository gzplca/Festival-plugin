<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-une-presentator-logo';
	$addPresentator_logoUrl = admin_url($path);
	$path = 'admin.php?page=presentator-logo';
	$presentator_logoListUrl = admin_url($path);


	global $wpdb;
	
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
	    $string .= "location.href='admin.php?page=presentator-logo';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		
		global $wpdb;

	 	$wpdb->insert( 
	 		$wpdb->prefix.'prog_presentator_logo', 
	 		array(
	 			'title' => $_POST['presentator_logo_title'], 
	 			'photo'  => $_POST['image-title']
	 		), 
	 		array( 
	 			'%s',
	 			'%s'
	 		) 
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
	<h1>Ajouter une presentator logo</h1>
	<div class="box_form_add">
		<form action="<?php echo $addPresentator_logoUrl;?>" method="post">
			<div style = "display: none">
				<?php wp_editor( '' , 'desired_id_of_textarea', $settings = array('textarea_name'=>'serie_description') ); ?>
			</div>
			<label>Titre: </label>
			<input type="text" id = "presentator_logo_title" name = "presentator_logo_title"><br>
			<label>Photo</label>
			<input id="image-title-url" type="text" name="image-title" />
			<input id="title-upload-button" type="button" class="btn btn-mini btn-primary" value="Upload Image" /><br>
			<img id = "image-title-display"><br>			
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $presentator_logoListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>





