#!/usr/bin/env bash
# Configure la boutique en une commande (langue, thème, WooCommerce, TVA, livraison, paiement, pages).
#
# Dans Local (Open site shell, depuis la racine du dépôt) :
#   bash scripts/configuration-boutique.sh
# Dans Docker :
#   WP="docker compose run --rm cli wp" PROJET=/projet bash scripts/configuration-boutique.sh
set -euo pipefail

DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP="${WP:-wp}"
# Chemin du dépôt tel que PHP le voit (sous Windows, Local attend C:/... et pas /c/...)
if [ -z "${PROJET:-}" ]; then
  if command -v cygpath >/dev/null 2>&1; then PROJET="$(cygpath -m "$DIR")"; else PROJET="$DIR"; fi
fi

$WP language core install fr_FR --activate || true
$WP rewrite structure '/%postname%/' --hard
$WP theme activate twentytwentyfive || true
$WP plugin is-active woocommerce || $WP plugin install woocommerce --activate
$WP language plugin install woocommerce fr_FR || true

$WP eval-file "$PROJET/scripts/php/configuration-boutique.php" "$PROJET"

echo
echo "Reste à faire dans l'administration :"
echo " - Produits > Importer : catalogue/produits.csv"
echo " - bash scripts/images-catalogue.sh (après avoir mis les photos dans catalogue/images/)"
echo " - Stripe en mode test : voir README, partie 4"
