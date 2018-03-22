<?php
/**
 */
/*
Plugin Name: Programmation FL Plugin
Plugin URI: https://fortunelab.net
Description: A plugin for creating and displaying events.
Author: Fortune Lab
Author URI: http://fortunelab.net
Version: 1.1
License: GPLv2
*/



require 'interface.php';
require 'create-tables.php';
register_activation_hook( __FILE__, 'jal_install' );
// add_action( 'init', 'jal_install_data' );


?>