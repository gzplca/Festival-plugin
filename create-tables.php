<?php

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$own_prefix = 'prog_';

	$table_edition = $wpdb->prefix .$own_prefix. 'edition';
	$table_serie = $wpdb->prefix .$own_prefix. 'serie';
	$table_category = $wpdb->prefix .$own_prefix. 'category';
	$table_first = $wpdb->prefix .$own_prefix. 'first';
	$table_venue = $wpdb->prefix .$own_prefix. 'venue';
	$table_event = $wpdb->prefix .$own_prefix. 'event';
	$table_film = $wpdb->prefix .$own_prefix. 'film';
	$table_artist = $wpdb->prefix .$own_prefix. 'artist';
	$table_event_film = $wpdb->prefix .$own_prefix. 'event_film';
	$table_event_artist = $wpdb->prefix .$own_prefix. 'event_artist';
	$table_event_serie = $wpdb->prefix .$own_prefix. 'event_serie';
	$table_presentator_logo = $wpdb->prefix .$own_prefix. 'presentator_logo';
	$table_event_presentator_logo = $wpdb->prefix .$own_prefix. 'event_presentator_logo';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_edition (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		description text COLLATE utf8mb4_unicode_ci,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $table_serie (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		photo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		description text COLLATE utf8mb4_unicode_ci,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_category (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_first (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $table_venue (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		address varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		phone varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		website varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		map varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_event (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		sub_title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		subject varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		type varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		date date DEFAULT NULL,
		start_time varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		venue_id int(11) unsigned DEFAULT NULL,
		description text COLLATE utf8_unicode_ci,
		program text COLLATE utf8_unicode_ci,
		presentator varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		ticket_desc varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		ticket_link varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		ticket2_desc varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		ticket2_link varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		photo_small varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		photo_big varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		serie_id int(11) unsigned DEFAULT NULL,
		trailer varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		video_address varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		video_provider varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT cons_fk_event_venue_id_id FOREIGN KEY (venue_id) REFERENCES $table_venue(id) ON DELETE SET NULL ON UPDATE SET NULL,
		CONSTRAINT event_ibfk_1 FOREIGN KEY (serie_id) REFERENCES $table_serie(id) ON DELETE SET NULL ON UPDATE SET NULL
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_film (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		realisator varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		country varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		year varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		duration varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		language varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		actors varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		director varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		synopsis text COLLATE utf8_unicode_ci,
		category_id int(11) unsigned DEFAULT NULL,
		trailer varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		realisator_on tinyint(3) unsigned DEFAULT NULL,
		video_provider varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		photo varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		first_id int(11) unsigned DEFAULT NULL,
		presence varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT cons_fk_film_category_id_id FOREIGN KEY (category_id) REFERENCES $table_category (id) ON DELETE SET NULL ON UPDATE SET NULL,
		CONSTRAINT cons_fk_film_first_id_id FOREIGN KEY (first_id) REFERENCES $table_first (id) ON DELETE SET NULL ON UPDATE SET NULL
	) $charset_collate;";

	dbDelta( $sql );


	$sql .= "CREATE TABLE IF NOT EXISTS $table_artist (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		artist_name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description text COLLATE utf8_unicode_ci,
		photo_title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		photo_desc varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		video_address varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		edition_id int(11) unsigned DEFAULT NULL,
		wp_post_id int(11) unsigned DEFAULT NULL,
		lang_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		trailer varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		video_provider varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_event_film (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		film_id int(11) unsigned DEFAULT NULL,
		event_id int(11) unsigned DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT event_film_ibfk_1 FOREIGN KEY (film_id) REFERENCES $table_film (id) ON DELETE CASCADE,
		CONSTRAINT event_film_ibfk_2 FOREIGN KEY (event_id) REFERENCES $table_event (id) ON DELETE CASCADE
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_event_artist (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		artist_id int(11) unsigned DEFAULT NULL,
		event_id int(11) unsigned DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT event_artist_ibfk_1 FOREIGN KEY (artist_id) REFERENCES $table_artist (id) ON DELETE CASCADE,
		CONSTRAINT event_artist_ibfk_2 FOREIGN KEY (event_id) REFERENCES $table_event (id) ON DELETE CASCADE
	) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_event_serie (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		event_id int(11) unsigned DEFAULT NULL,
		serie_id int(11) unsigned DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT event_serie_ibfk_1 FOREIGN KEY (event_id) REFERENCES $table_event (id) ON DELETE CASCADE,
		CONSTRAINT event_serie_ibfk_2 FOREIGN KEY (serie_id) REFERENCES $table_serie (id) ON DELETE CASCADE
		) $charset_collate;";

	dbDelta( $sql );


	$sql = "CREATE TABLE IF NOT EXISTS $table_presentator_logo (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		photo varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	dbDelta( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $table_event_presentator_logo (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
		event_id int(11) unsigned DEFAULT NULL,
		presentator_logo_id int(11) unsigned DEFAULT NULL,
		PRIMARY KEY (id),
		CONSTRAINT event_presentator_logo_ibfk_1 FOREIGN KEY (event_id) REFERENCES $table_event (id) ON DELETE CASCADE,
		CONSTRAINT event_presentator_logo_ibfk_2 FOREIGN KEY (presentator_logo_id) REFERENCES $table_presentator_logo (id) ON DELETE CASCADE
		) $charset_collate;";

	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}

