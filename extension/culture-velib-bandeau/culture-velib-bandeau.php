<?php
/**
 * Plugin Name:      Rayons d'Ill — Bandeau livraison offerte
 * Description:      Bandeau en haut de page : « Livraison offerte dès 80 € », puis ce qu'il reste à ajouter au panier.
 * Version:          1.0.0
 * Author:           Groupe Rayons d'Ill
 * Requires Plugins: woocommerce
 * Text Domain:      rayons-bandeau
 */

defined( 'ABSPATH' ) || exit;

// Doit rester égal au minimum de la méthode « Livraison offerte » (scripts/php/configuration-boutique.php).
const RDI_SEUIL_LIVRAISON = 80;

add_action(
	'wp_body_open',
	function () {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}
		$total = (float) WC()->cart->get_displayed_subtotal(); // TTC, comme affiché au client
		$reste = RDI_SEUIL_LIVRAISON - $total;

		if ( 0 == $total ) {
			$texte = sprintf( "Livraison offerte dès %s · Retrait gratuit à l'atelier", wc_price( RDI_SEUIL_LIVRAISON ) );
		} elseif ( $reste > 0 ) {
			$texte = sprintf( 'Plus que %s dans ton panier et la livraison est offerte !', wc_price( $reste ) );
		} else {
			$texte = 'Bonne nouvelle : ta livraison est offerte.';
		}
		printf( '<div class="rdi-bandeau" role="status">%s</div>', wp_kses_post( $texte ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_style( 'rdi-bandeau', false, array(), '1.0.0' );
		wp_enqueue_style( 'rdi-bandeau' );
		wp_add_inline_style(
			'rdi-bandeau',
			'.rdi-bandeau{background:#1F4D45;color:#fff;text-align:center;padding:.55rem 1rem;font:600 .9rem/1.4 Inter,system-ui,sans-serif}
			 .rdi-bandeau .amount{color:#F5EFE4;text-decoration:underline #B8503A 2px}'
		);
	}
);
