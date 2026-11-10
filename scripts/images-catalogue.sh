#!/usr/bin/env bash
# Importe les photos de catalogue/images/ dans la médiathèque et les rattache aux produits
# (image principale + galerie + texte alternatif), d'après catalogue/images/sources.csv.
# À lancer APRÈS l'import de catalogue/produits.csv. Photos : format carré conseillé, 1200 x 1200 px.
#
#   bash scripts/images-catalogue.sh                       # Local
#   WP="docker compose run --rm -T cli wp" PROJET=/projet bash scripts/images-catalogue.sh   # Docker
set -euo pipefail

DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP="${WP:-wp}"
if [ -z "${PROJET:-}" ]; then
  if command -v cygpath >/dev/null 2>&1; then PROJET="$(cygpath -m "$DIR")"; else PROJET="$DIR"; fi
fi

declare -A galerie
while IFS=';' read -r fichier sku role alt _auteur _source; do
  [ "$fichier" = "fichier" ] && continue
  if [ ! -f "$DIR/catalogue/images/$fichier" ]; then
    echo "Manquant : catalogue/images/$fichier (ignoré)"; continue
  fi
  id=$($WP post list --post_type=product --meta_key=_sku --meta_value="$sku" --field=ID --format=ids | tr -d '\r')
  if [ -z "$id" ]; then echo "Produit $sku introuvable (as-tu importé le CSV ?)"; continue; fi

  if [ "$role" = "principale" ]; then
    $WP media import "$PROJET/catalogue/images/$fichier" --post_id="$id" --featured_image --alt="$alt" --title="$alt" --porcelain >/dev/null
    echo "Principale : $sku <- $fichier"
  else
    media=$($WP media import "$PROJET/catalogue/images/$fichier" --post_id="$id" --alt="$alt" --title="$alt" --porcelain | tr -d '\r')
    galerie[$id]="${galerie[$id]:+${galerie[$id]},}$media"
    echo "Galerie    : $sku <- $fichier"
  fi
done < <(tr -d '\r' < "$DIR/catalogue/images/sources.csv")

for id in "${!galerie[@]}"; do
  $WP post meta update "$id" _product_image_gallery "${galerie[$id]}" >/dev/null
done
echo "Terminé."
