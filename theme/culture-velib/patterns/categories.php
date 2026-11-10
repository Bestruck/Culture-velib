<?php
/**
 * Title: Les trois rayons
 * Slug: culture-velib/categories
 * Categories: woo-commerce
 * Description: Trois cartes vers les catégories Vélos, Accessoires et Atelier & entretien.
 */

$rayons = array(
	array( 'velos', 'icone-velo.png', 'Vélos', 'Ville, électrique, cargo, gravel, enfant : montés et réglés pour toi.' ),
	array( 'accessoires', 'icone-boutique.png', 'Accessoires', 'Antivol, casque, éclairage, sacoches : rouler en sécurité toute l\'année.' ),
	array( 'atelier-entretien', 'icone-atelier.png', 'Atelier & entretien', 'Révision en 48 h, pièces d\'usure et kits pour entretenir ton vélo.' ),
);
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:heading {"textAlign":"center","align":"wide"} -->
<h2 class="wp-block-heading alignwide has-text-align-center">Nos rayons</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><?php foreach ( $rayons as list( $slug, $icone, $titre, $texte ) ) : ?><!-- wp:column {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"accent-3"} -->
<div class="wp-block-column has-accent-3-background-color has-background" style="border-radius:12px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:image {"width":"64px","sizeSlug":"full","linkDestination":"custom"} -->
<figure class="wp-block-image size-full is-resized"><a href="<?php echo esc_url( home_url( "/categorie/$slug/" ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( "assets/images/$icone" ) ); ?>" alt="" style="width:64px"/></a></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="<?php echo esc_url( home_url( "/categorie/$slug/" ) ); ?>"><?php echo esc_html( $titre ); ?></a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html( $texte ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --><?php endforeach; ?></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
