<?php
/**
 * Title: Pied de page Culture Vélib
 * Slug: culture-velib/pied-de-page
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * Le logo horizontal est inséré en SVG dans la page (et non en <img>) pour hériter de la police
 * Montserrat chargée par le thème : il reste net à toutes les tailles.
 */

$logo = file_get_contents( get_theme_file_path( 'assets/images/logo_horizontal_sombre.svg' ) );
$logo = str_replace( '<svg ', '<svg width="100%" style="max-width:300px;height:auto;display:block" ', $logo );
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|accent-2"}}}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:html -->
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Culture Vélib, accueil"><?php echo $logo; // SVG du dépôt, contenu maîtrisé. ?></a>
<!-- /wp:html -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">Vélos, équipements et services. Magasin-atelier en bord de Loire, au cœur d'Orléans.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"accent-2","fontSize":"medium"} -->
<h3 class="wp-block-heading has-accent-2-color has-text-color has-medium-font-size">Le magasin</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">8 quai du Châtelet<br>45000 Orléans<br>Du mardi au samedi, 10 h – 19 h<br>02 00 00 00 00</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"accent-2","fontSize":"medium"} -->
<h3 class="wp-block-heading has-accent-2-color has-text-color has-medium-font-size">Infos</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><a href="<?php echo esc_url( home_url( '/mon-compte/' ) ); ?>">Mon compte</a><br><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a><br><a href="<?php echo esc_url( home_url( '/conditions-generales-de-vente/' ) ); ?>">Conditions générales de vente</a><br><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">Politique de confidentialité</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","backgroundColor":"accent-1","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-text-color has-accent-1-color has-alpha-channel-opacity has-accent-1-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">© 2026 Culture Vélib · Projet étudiant IRIS, enseigne et coordonnées fictives</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
