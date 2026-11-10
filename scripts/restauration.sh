#!/usr/bin/env bash
# Reconstruit la boutique dans Docker à partir d'une sauvegarde du dossier sauvegardes/.
#
#   cp .env.example .env    # puis remplir les mots de passe
#   bash scripts/restauration.sh              # dernière sauvegarde trouvée
#   bash scripts/restauration.sh 2026-12-05   # sauvegarde d'un jour précis
set -euo pipefail
export MSYS_NO_PATHCONV=1   # Git Bash sous Windows : ne pas réécrire /sauvegardes

cd "$(dirname "$0")/.."
[ -f .env ] || { echo "Fichier .env manquant : cp .env.example .env puis remplis-le."; exit 1; }
PORT="$(grep -E '^WP_PORT=' .env | cut -d= -f2 | tr -d '\r')"
NOUVELLE_URL="http://localhost:${PORT:-8080}"

# Sauvegardes cherchées dans sauvegardes/ puis sauvegardes/demo/ (celle versionnée dans Git)
shopt -s nullglob
toutes=(sauvegardes/base-*.sql sauvegardes/demo/base-*.sql)
[ ${#toutes[@]} -gt 0 ] || { echo "Aucune sauvegarde base-*.sql dans sauvegardes/"; exit 1; }
JOUR="${1:-$(printf '%s\n' "${toutes[@]}" | sed -E 's/.*base-(.*)\.sql/\1/' | sort | tail -1)}"
sql=(sauvegardes/base-"$JOUR".sql sauvegardes/demo/base-"$JOUR".sql)
medias=(sauvegardes/medias-"$JOUR".tar.gz sauvegardes/demo/medias-"$JOUR".tar.gz)
SQL="${sql[0]:-}"
MEDIAS="${medias[0]:-}"
[ -n "$SQL" ] || { echo "Aucune sauvegarde base-$JOUR.sql dans sauvegardes/"; exit 1; }
echo "Restauration de la sauvegarde du $JOUR ($SQL)"

wp() { docker compose run --rm -T cli wp "$@"; }

# 1. Les conteneurs
docker compose up -d
echo "Attente de WordPress..."
until docker compose exec -T wordpress test -f /var/www/html/wp-config.php; do sleep 2; done

# 2. La base
wp db import "/$SQL"   # sauvegardes/ du dépôt = /sauvegardes dans le conteneur

# 3. Les médias
if [ -n "$MEDIAS" ]; then
  docker compose run --rm -T cli tar -xzf "/$MEDIAS" -C /var/www/html/wp-content
fi

# 4. L'adresse : toujours wp search-replace (données sérialisées), jamais sed sur le .sql
ANCIENNE_URL="$(wp option get siteurl | tr -d '\r')"
if [ "$ANCIENNE_URL" != "$NOUVELLE_URL" ]; then
  wp search-replace "$ANCIENNE_URL" "$NOUVELLE_URL" --all-tables --precise
fi

# 5. Les extensions tierces ne sont pas dans Git : on les réinstalle
wp plugin install woocommerce --activate
wp language core install fr_FR --activate || true
wp language plugin install woocommerce fr_FR || true
wp plugin activate culture-velib-bandeau || true
wp rewrite flush
wp cache flush

echo
echo "Boutique restaurée : $NOUVELLE_URL"
