<?php
/**
 * Plugin Name:      Culture Vélib — Bandeau livraison offerte
 * Description:      Bandeau en haut de page : « Livraison offerte dès 80 € », puis ce qu'il reste à ajouter au panier.
 * Version:          1.0.0
 * Author:           Groupe Culture Vélib
 * Requires Plugins: woocommerce
 * Text Domain:      culture-velib-bandeau
 */

defined( 'ABSPATH' ) || exit;

// Doit rester égal au minimum de la méthode « Livraison offerte » (scripts/php/configuration-boutique.php).
const CV_SEUIL_LIVRAISON = 80;

add_action(
	'wp_body_open',
	function () {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}
		$total = (float) WC()->cart->get_displayed_subtotal(); // TTC, comme affiché au client
		$reste = CV_SEUIL_LIVRAISON - $total;

		if ( 0 == $total ) {
			$texte = sprintf( "Livraison offerte dès %s · Retrait gratuit à l'atelier", wc_price( CV_SEUIL_LIVRAISON ) );
		} elseif ( $reste > 0 ) {
			$texte = sprintf( 'Plus que %s dans ton panier et la livraison est offerte !', wc_price( $reste ) );
		} else {
			$texte = 'Bonne nouvelle : ta livraison est offerte.';
		}
		printf( '<div class="cv-bandeau" role="status">%s</div>', wp_kses_post( $texte ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_style( 'cv-bandeau', false, array(), '1.0.0' );
		wp_enqueue_style( 'cv-bandeau' );
		wp_add_inline_style(
			'cv-bandeau',
			'.cv-bandeau{background:#0D1B2A;color:#fff;text-align:center;padding:.55rem 1rem;font:600 .9rem/1.4 Montserrat,Arial,sans-serif}
			 .cv-bandeau .amount{color:#40C9FF;font-weight:800}'
		);
	}
);
