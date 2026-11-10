<?php
/**
 * Title: Bannière d'accueil
 * Slug: culture-velib/banniere
 * Categories: banner
 * Description: Grande bannière avec le logo, l'accroche et deux appels à l'action.
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"accent-3","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-accent-3-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.2em","textTransform":"uppercase"}},"textColor":"accent-5","fontSize":"small"} -->
<p class="has-accent-5-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.2em;text-transform:uppercase">Vélos · Équipements · Services</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-xx-large-font-size">Ton expert vélo à <span class="has-inline-color has-accent-1-color">Orléans</span></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">Vélos de ville, électriques et cargos, montés et réglés à l'atelier du quai du Châtelet. Tu repars en selle, on s'occupe du reste.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent-1","textColor":"contrast","style":{"typography":{"textTransform":"uppercase","fontStyle":"italic","fontWeight":"800"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-accent-1-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/categorie/velos/' ) ); ?>" style="font-style:italic;font-weight:800;text-transform:uppercase">Voir les vélos</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","style":{"typography":{"textTransform":"uppercase","fontStyle":"italic","fontWeight":"800"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/produit/forfait-revision-complete/' ) ); ?>" style="font-style:italic;font-weight:800;text-transform:uppercase">Réserver une révision</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%","className":"cv-masque-mobile"} -->
<div class="wp-block-column is-vertically-aligned-center cv-masque-mobile" style="flex-basis:45%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-principal-transparent.png' ) ); ?>" alt="Culture Vélib : un cycliste devant des montagnes, vélos, équipements, services"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
