#!/usr/bin/env bash
# Configure la boutique en une commande : langue, WooCommerce, TVA, livraison, paiement,
# code promo, pages légales, puis import du catalogue. Rejouable sans créer de doublons.
#
# Dans Local (Open site shell, depuis la racine du dépôt) :
#   bash scripts/configuration-boutique.sh
# Dans Docker :
#   WP="docker compose run --rm -T cli wp" PROJET=/projet bash scripts/configuration-boutique.sh
set -euo pipefail
export MSYS_NO_PATHCONV=1   # Git Bash sous Windows : ne pas réécrire les chemins

DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP="${WP:-wp}"
# Chemin du dépôt tel que PHP le voit (sous Windows, Local attend C:/... et pas /c/...)
if [ -z "${PROJET:-}" ]; then
  if command -v cygpath >/dev/null 2>&1; then PROJET="$(cygpath -m "$DIR")"; else PROJET="$DIR"; fi
fi

# wordpress.org répond parfois en erreur : on réessaie 3 fois
reessayer() { for i in 1 2 3; do "$@" && return 0; echo "Échec, nouvel essai ($i/3)..."; sleep 5; done; return 1; }

reessayer $WP language core install fr_FR --activate
$WP rewrite structure '/%postname%/'
$WP plugin is-active woocommerce || reessayer $WP plugin install woocommerce --activate
reessayer $WP language plugin install woocommerce fr_FR

$WP eval-file "$PROJET/scripts/php/configuration-boutique.php" "$PROJET"
$WP eval-file "$PROJET/scripts/php/import-catalogue.php" "$PROJET"

echo
echo "Reste à faire :"
echo " - mettre les photos dans catalogue/images/ puis : bash scripts/images-catalogue.sh"
echo " - Stripe en mode test : voir README, partie 4"
