<?php
/**
 * Configuration WooCommerce de Culture Vélib (parties 3 et 4).
 * Lancé par scripts/configuration-boutique.sh :
 *   wp eval-file scripts/php/configuration-boutique.php <racine-du-depot>
 * Le script est rejouable : il ne crée rien en double.
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	WP_CLI::error( 'WooCommerce doit être installé et actif.' );
}

$racine = rtrim( $args[0] ?? dirname( __DIR__, 2 ), '/\\' );

/* ---------- 1. Réglages généraux, prix TTC, stock, e-mails ---------- */
$options = array(
	'blogname'                                => "Culture Vélib",
	'blogdescription'                         => 'Ton expert vélo à Orléans · Vélos, équipements, services',
	'timezone_string'                         => 'Europe/Paris',
	'woocommerce_store_address'               => '8 quai du Châtelet',
	'woocommerce_store_city'                  => 'Orléans',
	'woocommerce_store_postcode'              => '45000',
	'woocommerce_default_country'             => 'FR',
	'woocommerce_allowed_countries'           => 'specific',
	'woocommerce_specific_allowed_countries'  => array( 'FR' ),
	'woocommerce_currency'                    => 'EUR',
	'woocommerce_currency_pos'                => 'right_space',
	'woocommerce_price_thousand_sep'          => ' ',
	'woocommerce_price_decimal_sep'           => ',',
	'woocommerce_price_num_decimals'          => 2,
	// TVA : prix saisis TTC, affichés TTC partout.
	'woocommerce_calc_taxes'                  => 'yes',
	'woocommerce_prices_include_tax'          => 'yes',
	'woocommerce_tax_based_on'                => 'shipping',
	'woocommerce_tax_display_shop'            => 'incl',
	'woocommerce_tax_display_cart'            => 'incl',
	'woocommerce_tax_total_display'           => 'itemized',
	'woocommerce_price_display_suffix'        => 'TTC',
	// Stock.
	'woocommerce_manage_stock'                => 'yes',
	'woocommerce_hold_stock_minutes'          => 60,
	'woocommerce_notify_low_stock_amount'     => 2,
	'woocommerce_notify_no_stock_amount'      => 0,
	'woocommerce_hide_out_of_stock_items'     => 'no',
	'woocommerce_enable_coupons'              => 'yes',
	// Boutique en ligne : sinon un visiteur non connecté ne voit qu'une page « Bientôt disponible ».
	'woocommerce_coming_soon'                 => 'no',
	'woocommerce_store_pages_only'            => 'no',
	// E-mails aux couleurs de la charte.
	'woocommerce_email_from_name'             => "Culture Vélib",
	'woocommerce_email_from_address'          => 'bonjour@culture-velib.example',
	'woocommerce_email_base_color'            => '#0D1B2A',
	'woocommerce_email_background_color'      => '#E9EEF2',
	'woocommerce_email_body_background_color' => '#FFFFFF',
	'woocommerce_email_text_color'            => '#0D1B2A',
	'woocommerce_email_footer_text'           => 'Culture Vélib · Vélos, équipements, services · 8 quai du Châtelet, 45000 Orléans',
);
foreach ( $options as $cle => $valeur ) {
	update_option( $cle, $valeur );
}
// Adresses en français : /produit/velo-de-ville-martroi/ plutôt que /product/...
update_option(
	'woocommerce_permalinks',
	array_merge(
		(array) get_option( 'woocommerce_permalinks', array() ),
		array( 'product_base' => 'produit', 'category_base' => 'categorie', 'tag_base' => 'etiquette' )
	)
);
flush_rewrite_rules();
WP_CLI::success( count( $options ) . ' réglages appliqués.' );

/* ---------- 2. TVA française 20 % ---------- */
global $wpdb;
$existe = $wpdb->get_var(
	"SELECT tax_rate_id FROM {$wpdb->prefix}woocommerce_tax_rates
	 WHERE tax_rate_country = 'FR' AND tax_rate_class = '' LIMIT 1"
);
if ( ! $existe ) {
	WC_Tax::_insert_tax_rate(
		array(
			'tax_rate_country'  => 'FR',
			'tax_rate'          => '20.0000',
			'tax_rate_name'     => 'TVA',
			'tax_rate_priority' => 1,
			'tax_rate_compound' => 0,
			'tax_rate_shipping' => 1,
			'tax_rate_order'    => 0,
			'tax_rate_class'    => '',
		)
	);
	WP_CLI::success( 'Taux de TVA 20 % créé.' );
} else {
	WP_CLI::log( 'TVA 20 % déjà présente.' );
}

/* ---------- 3. Zone France et modes de livraison ---------- */
// WooCommerce stocke les frais de port HORS taxe : 6,583333 € HT = 7,90 € TTC.
$zone = null;
foreach ( WC_Shipping_Zones::get_zones() as $z ) {
	if ( 'France' === $z['zone_name'] ) {
		$zone = new WC_Shipping_Zone( $z['id'] );
	}
}
if ( ! $zone ) {
	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'France' );
	$zone->set_zone_order( 1 );
	$zone->add_location( 'FR', 'country' );
	$zone->save();

	$methodes = array(
		array( 'flat_rate', array( 'title' => 'Colissimo à domicile', 'tax_status' => 'taxable', 'cost' => '6.583333' ) ),
		array( 'flat_rate', array( 'title' => 'Colissimo en point relais', 'tax_status' => 'taxable', 'cost' => '4.083333' ) ),
		array( 'free_shipping', array( 'title' => 'Livraison offerte dès 80 €', 'requires' => 'min_amount', 'min_amount' => '80', 'ignore_discounts' => 'no' ) ),
		array( 'local_pickup', array( 'title' => "Retrait à l'atelier (quai du Châtelet)", 'tax_status' => 'none', 'cost' => '0' ) ),
	);
	foreach ( $methodes as list( $type, $reglages ) ) {
		$instance = $zone->add_shipping_method( $type );
		update_option( "woocommerce_{$type}_{$instance}_settings", $reglages );
	}
	WP_CLI::success( 'Zone France créée avec 4 modes de livraison.' );
} else {
	WP_CLI::log( 'Zone France déjà présente.' );
}

/* ---------- 4. Paiement : virement bancaire (la carte passe par Stripe en mode test) ---------- */
update_option(
	'woocommerce_bacs_settings',
	array(
		'enabled'      => 'yes',
		'title'        => 'Virement bancaire',
		'description'  => 'Ta commande part dès que le virement arrive (1 à 2 jours ouvrés).',
		'instructions' => 'Indique ton numéro de commande en libellé du virement.',
	)
);
update_option(
	'woocommerce_bacs_accounts',
	array(
		array(
			'account_name'   => "Culture Vélib SARL (compte fictif)",
			'account_number' => '',
			'bank_name'      => 'Banque fictive',
			'sort_code'      => '',
			'iban'           => 'FR76 0000 0000 0000 0000 0000 000',
			'bic'            => 'FICTFRPPXXX',
		),
	)
);
WP_CLI::success( 'Virement bancaire activé (coordonnées fictives).' );

/* ---------- 5. Code promo avec restrictions ---------- */
if ( ! wc_get_coupon_id_by_code( 'BIENVENUE10' ) ) {
	$coupon = new WC_Coupon();
	$coupon->set_code( 'BIENVENUE10' );
	$coupon->set_description( 'Première commande : -10 % dès 50 €, jusqu’au 31/12/2026, une fois par client.' );
	$coupon->set_discount_type( 'percent' );
	$coupon->set_amount( 10 );
	$coupon->set_minimum_amount( 50 );
	$coupon->set_date_expires( '2026-12-31 23:59:59' );
	$coupon->set_individual_use( true );
	$coupon->set_usage_limit_per_user( 1 );
	$coupon->set_exclude_sale_items( true );
	$coupon->save();
	WP_CLI::success( 'Code promo BIENVENUE10 créé.' );
}

/* ---------- 6. Pages légales ---------- */
$pages = array(
	'mentions-legales'              => 'Mentions légales',
	'conditions-generales-de-vente' => 'Conditions générales de vente',
	'politique-de-confidentialite'  => 'Politique de confidentialité',
);
$ids = array();
foreach ( $pages as $slug => $titre ) {
	$fichier = "$racine/contenu/pages/$slug.html";
	if ( ! is_readable( $fichier ) ) {
		WP_CLI::warning( "Fichier introuvable : $fichier" );
		continue;
	}
	$page = get_page_by_path( $slug );
	$donnees = array(
		'post_title'   => $titre,
		'post_name'    => $slug,
		'post_content' => file_get_contents( $fichier ),
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	if ( $page ) {
		$donnees['ID'] = $page->ID;
	}
	$ids[ $slug ] = wp_insert_post( $donnees );
}
if ( isset( $ids['conditions-generales-de-vente'] ) ) {
	update_option( 'woocommerce_terms_page_id', $ids['conditions-generales-de-vente'] );
}
if ( isset( $ids['politique-de-confidentialite'] ) ) {
	update_option( 'wp_page_for_privacy_policy', $ids['politique-de-confidentialite'] );
}
update_option( 'woocommerce_checkout_terms_and_conditions_checkbox_text', "J'ai lu et j'accepte les [terms] de Culture Vélib." );
WP_CLI::success( count( $ids ) . ' pages légales publiées ; CGV et confidentialité reliées à WooCommerce.' );

/* ---------- 7. Pages de WooCommerce en français, contenu de démonstration retiré ---------- */
$pages_wc = array(
	'woocommerce_shop_page_id'      => array( 'Boutique', 'boutique' ),
	'woocommerce_cart_page_id'      => array( 'Panier', 'panier' ),
	'woocommerce_checkout_page_id'  => array( 'Commande', 'commande' ),
	'woocommerce_myaccount_page_id' => array( 'Mon compte', 'mon-compte' ),
);
foreach ( $pages_wc as $option => list( $titre, $slug ) ) {
	$id = (int) get_option( $option );
	if ( $id && get_post( $id ) ) {
		wp_update_post( array( 'ID' => $id, 'post_title' => $titre, 'post_name' => $slug ) );
	}
}
foreach ( array( 'sample-page' => 'page', 'hello-world' => 'post' ) as $slug => $type ) {
	$demo = get_page_by_path( $slug, OBJECT, $type );
	if ( $demo ) {
		wp_delete_post( $demo->ID, true );
	}
}
$non_classe = get_term_by( 'slug', 'uncategorized', 'product_cat' );
if ( $non_classe ) {
	wp_update_term( $non_classe->term_id, 'product_cat', array( 'name' => 'Non classé', 'slug' => 'non-classe' ) );
}
flush_rewrite_rules();
WP_CLI::success( 'Pages Boutique, Panier, Commande et Mon compte renommées.' );

/* ---------- 8. Identité : thème, logo, icône du site, logo des e-mails ---------- */
if ( wp_get_theme( 'culture-velib' )->exists() ) {
	switch_theme( 'culture-velib' );
	WP_CLI::success( 'Thème Culture Vélib activé.' );
} else {
	WP_CLI::warning( 'Thème culture-velib introuvable (dossier theme/ monté ?) : thème laissé tel quel.' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/** Importe une image de charte/web une seule fois (repérée par une méta) et renvoie son ID. */
function cv_importer_image( $racine, $fichier, $alt ) {
	$existant = get_posts( array( 'post_type' => 'attachment', 'meta_key' => '_cv_source', 'meta_value' => $fichier, 'fields' => 'ids', 'numberposts' => 1 ) );
	if ( $existant ) {
		return $existant[0];
	}
	$copie = wp_tempnam( $fichier );
	copy( "$racine/charte/web/$fichier", $copie ); // le sideload déplace le fichier : on travaille sur une copie
	$id = media_handle_sideload( array( 'name' => $fichier, 'tmp_name' => $copie ), 0, $alt );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "$fichier : " . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_cv_source', $fichier );
	return $id;
}

$logo  = cv_importer_image( $racine, 'logo-principal-transparent.png', "Culture Vélib, vélos, équipements, services" );
$icone = cv_importer_image( $racine, 'icone-cv.png', 'Culture Vélib' );
if ( $logo ) {
	update_option( 'site_logo', $logo );
	update_option( 'woocommerce_email_header_image', wp_get_attachment_url( $logo ) );
}
if ( $icone ) {
	update_option( 'site_icon', $icone );
}
WP_CLI::success( 'Logo, icône du site et en-tête des e-mails en place.' );
