#!/usr/bin/env bash
# Sauvegarde la base (produits, commandes, réglages, pages) et les médias (photos, logo).
#
# Dans Local : clic droit sur le site > Open site shell, puis depuis la racine du dépôt :
#   bash scripts/sauvegarde.sh
# Sur un serveur, chaque nuit à 3 h (crontab -e) :
#   0 3 * * * cd /srv/boutique && DEST=/mnt/sauvegardes bash scripts/sauvegarde.sh >> /var/log/sauvegarde-boutique.log 2>&1
set -euo pipefail
export MSYS_NO_PATHCONV=1   # Git Bash sous Windows : ne pas réécrire les chemins

DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP="${WP:-wp}"
DEST="${DEST:-$DIR/sauvegardes}"
GARDER_JOURS="${GARDER_JOURS:-14}"
JOUR="$(date +%F)"
mkdir -p "$DEST"

# Chemins vus par PHP (Windows) et par tar (Git Bash)
if command -v cygpath >/dev/null 2>&1; then
  DEST_PHP="$(cygpath -m "$DEST")"
  WP_CONTENT="$(cygpath -u "$($WP eval 'echo WP_CONTENT_DIR;' | tr -d '\r')")"
else
  DEST_PHP="$DEST"
  WP_CONTENT="$($WP eval 'echo WP_CONTENT_DIR;')"
fi

# 1. La base
$WP db export "$DEST_PHP/base-$JOUR.sql"

# 2. Les médias
tar -czf "$DEST/medias-$JOUR.tar.gz" -C "$WP_CONTENT" uploads

# 3. On ne garde que les N derniers jours
find "$DEST" -maxdepth 1 \( -name 'base-*.sql' -o -name 'medias-*.tar.gz' \) -mtime +"$GARDER_JOURS" -delete

echo "Sauvegarde du $JOUR terminée dans $DEST :"
ls -lh "$DEST"/*-"$JOUR".*
