<?php
/**
 * Thème Culture Vélib : charge la feuille de style du thème enfant
 * (un thème en blocs ne la charge pas tout seul).
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		// Version = date de modification : le navigateur recharge la feuille dès qu'elle change.
		wp_enqueue_style( 'culture-velib', get_stylesheet_uri(), array(), (string) filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
);
