<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-un-emplacement';
	$addPlaceUrl = admin_url($path);
	$path = 'admin.php?page=emplacement';
	$placeListUrl = admin_url($path);

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
	    $string .= "location.href='admin.php?page=emplacement';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		global $wpdb;
		$wpdb->insert( 
			$wpdb->prefix.'prog_venue', 
			array(
				'edition_id' =>  $_POST['place_edition'],  
				'title' => $_POST['place_name'], 
				'address' => $_POST['place_address'],
				'phone'  => $_POST['place-tel'],
				'website' => $_POST['place-website'],
				'map' => $_POST['place_map'],
				'lang_code' => $_POST['place_lang']
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

		$new_place_id = $wpdb->insert_id;

	 	$place_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_venue where id = $new_place_id");

			$title_formated = wp_strip_all_tags( $place_obj->title );

			$my_post = array(
				'post_title'    => $title_formated,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'place',
			);

			$postid = wp_insert_post( $my_post, true );


			$wpdb->update( 
				$wpdb->prefix.'prog_venue', 
				array( 
					'wp_post_id' => $postid 
				),
				array( 'id' => $new_place_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
			);

			goToList();
	}

?>

	
<div class="prog_fl_page">
	<h1>Ajouter un Emplacement</h1>
	<div class="">
		<form action="<?php echo $addPlaceUrl;?>" method="post">
			<input type="text" name ="place_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Edition: </label>
			<select name = "place_edition">
				<?php 
					foreach ($editionsObj as $editionObj) {
						echo '<option value = '.$editionObj->id.'>'.$editionObj->title.'</option>';
					} 
				?>
			</select><br>
			<label>Le nom de emplacement: </label>
			<input type="text" id = "place_name" name = "place_name"><br>
			<label>Le address de emplacement: </label>
			<input type="text" id = "place_address" name = "place_address"><br>
			<label>Le téléphone de emplacement: </label>
			<input type="text" id = "place-tel" name = "place-tel"><br>
			<label>Le site Internet de emplacement: </label>
			<input type="text" id = "place-website" name = "place-website"><br>
			<label>Le carte de emplacement: </label>
			<input type="text" id = "place_map" name = "place_map"><br>
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $placeListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>

<?php

	
?>
