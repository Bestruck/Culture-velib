<?php
/**
 * Importe catalogue/produits.csv avec l'importeur officiel de WooCommerce
 * (le même que Produits > Importer), sans passer par l'interface.
 *   wp eval-file scripts/php/import-catalogue.php <racine-du-depot>
 * Rejouable : les produits existants (même UGS) sont mis à jour.
 */
if ( ! class_exists( 'WooCommerce' ) ) {
	WP_CLI::error( 'WooCommerce doit être installé et actif.' );
}
$racine  = rtrim( $args[0] ?? dirname( __DIR__, 2 ), '/\\' );
$fichier = "$racine/catalogue/produits.csv";

require_once WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php';
wp_set_current_user( get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) )[0] );

// En ligne de commande, il faut faire nous-mêmes l'étape « Correspondance des colonnes » de l'interface.
$correspondance = array(
	'Type'                    => 'type',
	'SKU'                     => 'sku',
	'Name'                    => 'name',
	'Published'               => 'published',
	'Is featured?'            => 'featured',
	'Visibility in catalog'   => 'catalog_visibility',
	'Short description'       => 'short_description',
	'Description'             => 'description',
	'Date sale price starts'  => 'date_on_sale_from',
	'Date sale price ends'    => 'date_on_sale_to',
	'Tax status'              => 'tax_status',
	'Tax class'               => 'tax_class',
	'In stock?'               => 'stock_status',
	'Stock'                   => 'stock_quantity',
	'Backorders allowed?'     => 'backorders',
	'Weight (kg)'             => 'weight',
	'Allow customer reviews?' => 'reviews_allowed',
	'Sale price'              => 'sale_price',
	'Regular price'           => 'regular_price',
	'Categories'              => 'category_ids',
	'Tags'                    => 'tag_ids',
	'Parent'                  => 'parent_id',
);
foreach ( array( 1, 2 ) as $n ) {
	$correspondance[ "Attribute $n name" ]      = "attributes:name$n";
	$correspondance[ "Attribute $n value(s)" ]  = "attributes:value$n";
	$correspondance[ "Attribute $n visible" ]   = "attributes:visible$n";
	$correspondance[ "Attribute $n global" ]    = "attributes:taxonomy$n";
	$correspondance[ "Attribute $n default" ]   = "attributes:default$n";
}

// Passe 1 : crée les produits absents. Passe 2 : met à jour ceux qui existent déjà.
foreach ( array( false => 'Création', true => 'Mise à jour' ) as $mettre_a_jour => $etape ) {
	$importeur = new WC_Product_CSV_Importer(
		$fichier,
		array(
			'update_existing' => $mettre_a_jour,
			'delimiter'       => ',',
			'lines'           => -1,
			'parse'           => true,
			'mapping'         => array(
				'from' => array_keys( $correspondance ),
				'to'   => array_values( $correspondance ),
			),
		)
	);
	$resultat = $importeur->import();
	WP_CLI::log(
		sprintf(
			'%-12s créés %d, mis à jour %d, ignorés %d, en erreur %d',
			$etape,
			count( $resultat['imported'] ),
			count( $resultat['updated'] ),
			count( $resultat['skipped'] ),
			count( $resultat['failed'] )
		)
	);
	foreach ( $resultat['failed'] as $erreur ) {
		WP_CLI::warning( $erreur->get_error_message() );
	}
}
wc_delete_product_transients();
WP_CLI::success( 'Catalogue importé.' );
