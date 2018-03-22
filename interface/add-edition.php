<?php 
	$plugin_path = plugin_dir_path( __FILE__ ).'/interface/';
	//echo $plugin_path;
	$path = 'admin.php?page=ajouter-une-edition';
	$addEditionUrl = admin_url($path);
	$path = 'admin.php?page=edition';
	$editionListUrl = admin_url($path);

	$actual_lang = ICL_LANGUAGE_CODE;
	

	if (isset($_REQUEST['insert'])) {
		insert();
	}

	//$templates = get_page_templates();

	//print_r($templates);

	function goToList()
	{
	    $string = '<script type="text/javascript">';
	    $string .= "location.href='admin.php?page=edition';";
	    $string .= '</script>';

	    echo $string;
	}

	function insert() {
		global $wpdb;
			$wpdb->insert( 
				$wpdb->prefix.'prog_edition', 
				array( 
					'title' => $_POST['edition_title'], 
					'description' => $_POST['edition_description'],
					'lang_code' => $_POST['edition_lang']
				),
				array( 
					'%s', 
					'%s',
					'%s' 
				) 
			);

			$new_edition_id = $wpdb->insert_id;

			//print_r($new_edition_id);

			$edition_obj = $wpdb->get_row("select * from ".$wpdb->prefix."prog_edition where id = $new_edition_id");

			$title_formated = wp_strip_all_tags( $edition_obj->title );

			$my_post = array(
				'post_title'    => $title_formated,
				'post_content'  => '   ',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_parent' 	=> 0,
				'post_type'		=> 'edition',
			);

			$postid = wp_insert_post( $my_post, true );

			//print_r($postid);


			$wpdb->update( 
				$wpdb->prefix.'prog_edition', 
				array( 
					'wp_post_id' => $postid 
				),
				array( 'id' => $new_edition_id ),   
				array( 
					'%d'
				),
				array( '%d' )  
			);

			goToList();
	}

	
?>

	
<div class="prog_fl_page">
	<h1 class="title_page">Ajouter un edition</h1>
	<div class="box_form_add">
		<form action="<?php echo $addEditionUrl;?>" method="post">
			<input type="text" name ="edition_lang" value="<?php echo $actual_lang;?>" hidden><br>
			<label>Le titre de edition: </label>
			<input type="text" id = "edition_title" name = "edition_title"><br>
			<label>Le description de edition: </label>
			<input type="text" id = "edition_description" name = "edition_description"><br>
			<input type="submit" name="insert" value="Ajouter" id = "submit-button" class="btn btn-mini btn-success">
			<a href="<?php echo $editionListUrl;?>" class="btn btn-mini btn-warning">Retour</a></div>
		</form>
	</div>
</div>


