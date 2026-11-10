<?php
/**
 * Title: Réassurance
 * Slug: culture-velib/reassurance
 * Categories: woo-commerce
 * Description: Bande bleu nuit : livraison, atelier, retours, paiement sécurisé.
 */

$arguments = array(
	array( '80 €', 'Livraison offerte dès 80 € d\'achat, ou retrait gratuit à l\'atelier.' ),
	array( '48 h', 'Pour réviser ton vélo à l\'atelier, montage et réglage offerts à l\'achat.' ),
	array( '14 jours', 'Pour changer d\'avis, sans avoir à te justifier.' ),
	array( '100 %', 'Paiement sécurisé par carte ou par virement bancaire.' ),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><?php foreach ( $arguments as list( $chiffre, $texte ) ) : ?><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"900","fontStyle":"italic","textTransform":"uppercase","lineHeight":"1"}},"textColor":"accent-2","fontSize":"x-large"} -->
<p class="has-accent-2-color has-text-color has-x-large-font-size" style="font-style:italic;font-weight:900;line-height:1;text-transform:uppercase"><?php echo esc_html( $chiffre ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html( $texte ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --><?php endforeach; ?></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
