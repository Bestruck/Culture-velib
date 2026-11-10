<?php
/**
 * Thème Culture Vélib : charge la feuille de style du thème enfant
 * (un thème en blocs ne la charge pas tout seul).
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'culture-velib', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
	}
);
