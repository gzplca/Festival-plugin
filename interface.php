
<?php

add_action('admin_menu', 'programmation_plugin_setup_menu');
 
function programmation_plugin_setup_menu(){
	$parent_slug = 'programmation-fl-plugin';
	$capability = 'manage_options';
	$event = 'Évènement';
	$film = 'Film';
	$artist = "Artiste";
	$series = 'Séries';
	$place = 'Emplacement';
	$edition = 'Édition';
  $category = 'Catégorie';
  $first = 'Première';
  $presenter_logo = 'Presentator logo';
	$add_artist = 'Ajouter un artiste';
	$mod_artist = 'Modifier un artiste';
	$del_artist = 'Supprimer un artiste';
	$add_edition = 'Ajouter une édition';
	$mod_edition = 'Modifier une édition';
	$del_edition = 'Supprimer une édition';
	$add_film = 'Ajouter un film';
	$mod_film = 'Modifier un film';
	$del_film = 'Supprimer un film';
	$add_serie = 'Ajouter une série';
	$mod_serie = 'Modifier une série';
	$del_serie = 'Supprimer une série';
	$add_place = 'Ajouter un emplacement';
	$mod_place = 'Modifier un emplacement';
	$del_place = 'Supprimer un emplacement';
	$add_event = 'Ajouter un évènement';
	$mod_event = 'Modifier un évènement';
	$del_event = 'Supprimer un évènement';
  $add_category = 'Ajouter une catégorie';
  $mod_category = 'Modifier une catégorie';
  $del_category = 'Supprimer une catégorie';
  $add_first = 'Ajouter une première';
  $mod_first = 'Modifier une première';
  $del_first = 'Supprimer une première';
  $add_presenter_logo = 'Ajouter une presentator logo';
  $mod_presenter_logo = 'Modifier une presentator logo';
  $del_presenter_logo = 'Supprimer une presentator logo';


    add_menu_page( 'Dashboard Programmation', 'Programmation', $capability, $parent_slug, 'programmation_init', 'dashicons-tickets-alt','55');
    add_submenu_page($parent_slug, $edition, $edition, $capability, sanitize_title($edition), 'programmation_edition');
    add_submenu_page($parent_slug, $film, $film, $capability, sanitize_title($film), 'programmation_film');
    add_submenu_page($parent_slug, $artist, $artist, $capability, sanitize_title($artist), 'programmation_artist');
    add_submenu_page($parent_slug, $event, $event, $capability, sanitize_title($event), 'programmation_event');
    add_submenu_page($parent_slug, $series, $series, $capability, sanitize_title($series), 'programmation_series');
    
    add_submenu_page($event, $place, $place, $capability, sanitize_title($place), 'programmation_place');
    add_submenu_page($event, $presenter_logo, $presenter_logo, $capability, sanitize_title($presenter_logo), 'programmation_presenter_logo');
    add_submenu_page($film, $category, $category, $capability, sanitize_title($category), 'programmation_category');
    add_submenu_page($film, $first, $first, $capability, sanitize_title($first), 'programmation_first');

    add_submenu_page($artist, $add_artist, $add_artist, $capability, sanitize_title($add_artist), 'programmation_add_artist');
    add_submenu_page($artist, $mod_artist, $mod_artist, $capability, sanitize_title($mod_artist), 'programmation_mod_artist');
    add_submenu_page($artist, $del_artist, $del_artist, $capability, sanitize_title($del_artist), 'programmation_del_artist');
    add_submenu_page($place, $add_place, $add_place, $capability, sanitize_title($add_place), 'programmation_add_place');
    add_submenu_page($place, $mod_place, $mod_place, $capability, sanitize_title($mod_place), 'programmation_mod_place');
    add_submenu_page($place, $del_place, $del_place, $capability, sanitize_title($del_place), 'programmation_del_place');
    add_submenu_page($event, $add_event, $add_event, $capability, sanitize_title($add_event), 'programmation_add_event');
    add_submenu_page($event, $mod_event, $mod_event, $capability, sanitize_title($mod_event), 'programmation_mod_event');
    add_submenu_page($event, $del_event, $del_event, $capability, sanitize_title($del_event), 'programmation_del_event');
    add_submenu_page($serie, $add_serie, $add_serie, $capability, sanitize_title($add_serie), 'programmation_add_serie');
    add_submenu_page($serie, $mod_serie, $mod_serie, $capability, sanitize_title($mod_serie), 'programmation_mod_serie');
    add_submenu_page($serie, $del_serie, $del_serie, $capability, sanitize_title($del_serie), 'programmation_del_serie');
    add_submenu_page($film, $add_film, $add_film, $capability, sanitize_title($add_film), 'programmation_add_film');
    add_submenu_page($film, $mod_film, $mod_film, $capability, sanitize_title($mod_film), 'programmation_mod_film');
    add_submenu_page($film, $del_film, $del_film, $capability, sanitize_title($del_film), 'programmation_del_film');
    add_submenu_page($edition, $add_edition, $add_edition, $capability, sanitize_title($add_edition), 'programmation_add_edition');
    add_submenu_page($edition, $mod_edition, $mod_edition, $capability, sanitize_title($mod_edition), 'programmation_mod_edition');
    add_submenu_page($edition, $del_edition, $del_edition, $capability, sanitize_title($del_edition), 'programmation_del_edition');
    add_submenu_page($category, $add_category, $add_category, $capability, sanitize_title($add_category), 'programmation_add_category');
    add_submenu_page($category, $mod_category, $mod_category, $capability, sanitize_title($mod_category), 'programmation_mod_category');
    add_submenu_page($category, $del_category, $del_category, $capability, sanitize_title($del_category), 'programmation_del_category');
    add_submenu_page($first, $add_first, $add_first, $capability, sanitize_title($add_first), 'programmation_add_first');
    add_submenu_page($first, $mod_first, $mod_first, $capability, sanitize_title($mod_first), 'programmation_mod_first');
    add_submenu_page($first, $del_first, $del_first, $capability, sanitize_title($del_first), 'programmation_del_first');
    add_submenu_page($presenter_logo, $add_presenter_logo, $add_presenter_logo, $capability, sanitize_title($add_presenter_logo), 'programmation_add_presenter_logo');
    add_submenu_page($presenter_logo, $mod_presenter_logo, $mod_presenter_logo, $capability, sanitize_title($mod_presenter_logo), 'programmation_mod_presenter_logo');
    add_submenu_page($presenter_logo, $del_presenter_logo, $del_presenter_logo, $capability, sanitize_title($del_presenter_logo), 'programmation_del_presenter_logo');
}
 
function programmation_init(){
       require plugin_dir_path( __FILE__ ).'/interface/main.php';
}

function programmation_artist(){
       require plugin_dir_path( __FILE__ ).'/interface/artistlist.php';
}

function programmation_event(){
       require plugin_dir_path( __FILE__ ).'/interface/eventlist.php';
}

function programmation_place(){
       require plugin_dir_path( __FILE__ ).'/interface/placelist.php';
}

function programmation_film(){
       require plugin_dir_path( __FILE__ ).'/interface/filmlist.php';
}

function programmation_series(){
       require plugin_dir_path( __FILE__ ).'/interface/serielist.php';
}

function programmation_edition(){
       require plugin_dir_path( __FILE__ ).'/interface/editionlist.php';
}

function programmation_category(){
       require plugin_dir_path( __FILE__ ).'/interface/categorylist.php';
}

function programmation_presenter_logo(){
       require plugin_dir_path( __FILE__ ).'/interface/presenterlogolist.php';
}

function programmation_first(){
       require plugin_dir_path( __FILE__ ).'/interface/firstlist.php';
}

function programmation_add_artist(){
       require plugin_dir_path( __FILE__ ).'/interface/add-artist.php';
}

function programmation_mod_artist(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-artist.php';
}

function programmation_del_artist(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-artist.php';
}

function programmation_add_place(){
       require plugin_dir_path( __FILE__ ).'/interface/add-place.php';
}

function programmation_mod_place(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-place.php';
}

function programmation_del_place(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-place.php';
}

function programmation_add_event(){
       require plugin_dir_path( __FILE__ ).'/interface/add-event.php';
}

function programmation_mod_event(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-event.php';
}

function programmation_del_event(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-event.php';
}

function programmation_add_film(){
       require plugin_dir_path( __FILE__ ).'/interface/add-film.php';
}

function programmation_mod_film(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-film.php';
}

function programmation_del_film(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-film.php';
}

function programmation_add_serie(){
       require plugin_dir_path( __FILE__ ).'/interface/add-serie.php';
}

function programmation_mod_serie(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-serie.php';
}

function programmation_del_serie(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-serie.php';
}

function programmation_add_edition(){
       require plugin_dir_path( __FILE__ ).'/interface/add-edition.php';
}

function programmation_mod_edition(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-edition.php';
}

function programmation_del_edition(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-edition.php';
}

function programmation_add_category(){
       require plugin_dir_path( __FILE__ ).'/interface/add-category.php';
}

function programmation_mod_category(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-category.php';
}

function programmation_del_category(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-category.php';
}

function programmation_add_first(){
       require plugin_dir_path( __FILE__ ).'/interface/add-first.php';
}

function programmation_mod_first(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-first.php';
}

function programmation_del_first(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-first.php';
}
function programmation_add_presenter_logo(){
       require plugin_dir_path( __FILE__ ).'/interface/add-presenter_logo.php';
}

function programmation_mod_presenter_logo(){
       require plugin_dir_path( __FILE__ ).'/interface/modify-presenter_logo.php';
}

function programmation_del_presenter_logo(){
       require plugin_dir_path( __FILE__ ).'/interface/delete-presenter_logo.php';
}


function my_scripts() {
  wp_enqueue_style( 'prefix-style', plugins_url('/css/mystyle.css', __FILE__));
  wp_enqueue_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
  wp_enqueue_style('prefix_ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
  wp_enqueue_script('prefix_ui', '//code.jquery.com/ui/1.12.1/jquery-ui.js');
  wp_enqueue_script('prefix_thether', '//cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js');
  wp_enqueue_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js');
  wp_enqueue_script('newscript',plugins_url( '/js/myscript.js' , __FILE__ ),array( 'jquery' )); //replace myscript.js with your script file name
}

add_action('admin_enqueue_scripts','my_scripts');


add_action( 'init', 'fl_create_cpt_edition' );

function fl_create_cpt_edition() {
  $name_Plur = 'Editions';
  $name_Single = 'Edition';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'edition',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'edition', $args);
}

add_action( 'init', 'fl_create_cpt_movie' );

function fl_create_cpt_movie() {
  $name_Plur = 'Movies';
  $name_Single = 'Movie';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'movie',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'movie', $args);
}

add_action( 'init', 'fl_create_cpt_serie' );

function fl_create_cpt_serie() {
  $name_Plur = 'Series';
  $name_Single = 'Serie';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'serie',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'serie', $args);
}

add_action( 'init', 'fl_create_cpt_event' );

function fl_create_cpt_event() {
  $name_Plur = 'Events';
  $name_Single = 'Event';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'event',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'event', $args);
}



add_action( 'init', 'fl_create_cpt_artist' );

function fl_create_cpt_artist() {
  $name_Plur = 'Artists';
  $name_Single = 'Artist';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'artist',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'artist', $args);
}

add_action( 'init', 'fl_create_cpt_place' );

function fl_create_cpt_place() {
  $name_Plur = 'Places';
  $name_Single = 'Place';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'place',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'place', $args);
}

add_action( 'init', 'fl_create_cpt_movie_first' );

function fl_create_cpt_movie_first() {
  $name_Plur = 'Movie Firsts';
  $name_Single = 'Movie First';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'movie_first',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'movie_first', $args);
}

add_action( 'init', 'fl_create_cpt_movie_category' );

function fl_create_cpt_movie_category() {
  $name_Plur = 'Movie Categories';
  $name_Single = 'Movie Category';
  $labels = array(
                'name' => $name_Plur,
                'singular_name' => $name_Single,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$name_Single,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$name_Single,
                'new_item' => 'New '.$name_Single,
                'view' => 'View',
                'view_item' => 'View '.$name_Single,
                'search_items' => 'Search '.$name_Plur,
                'not_found' => 'No '.$name_Plur.' found',
                'not_found_in_trash' => 'No '.$name_Plur.' found in Trash',
                'parent' => 'Parent '.$name_Single
        );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus'  => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'show_in_admin_bar'  => false,
      'menu_position'    => 15,
      'menu_icon'      => 'dashicons-welcome-learn-more',
      'can_export'     => true,
      'delete_with_usr'  => false,
      'query_var'          => true,
      'rewrite'            => array( 
        'slug' => 'movie_category',
        'with_front' => true,
        'pages'    => true,
        'feeds'    => true,
         ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'movie_category', $args);
}

