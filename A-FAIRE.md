# À faire par le groupe (à supprimer avant le rendu)

Ce qui est déjà prêt et testé : charte, note, catalogue CSV, configuration WooCommerce par script,
pages légales, collection Postman (14/14), Docker, sauvegarde, restauration, extension, webhook.
Il reste ce qui se fait à la souris, et ce qui doit venir de vous.

## Semaine 1 (avant le 15/11)
- [ ] Remplacer `NOM Prénom` dans `README.md` et `NOTE.md`. Relire la note et la reformuler avec vos mots.
- [x] Créer le dépôt GitHub (https://github.com/Bestruck/Culture-velib). Reste : inviter le groupe.
- [ ] Installer **Local** (localwp.com), créer le site `boutique` (adresse `http://boutique.local`),
      administrateur **`gerance-cv`** (pas `admin`).
- [ ] Local > clic droit > *Open site shell*, aller à la racine du dépôt, puis :
      `"C:\Program Files\Git\bin\bash.exe" scripts/configuration-boutique.sh`

## Semaine 2 (16–22/11) — design et photos
- [x] Logos détourés, thème Culture Vélib (palette, Montserrat, en-tête, pied de page, accueil en
      blocs, fiche produit et boutique retouchées), logo des e-mails, vérification à 390 px.
- [ ] 18 photos libres de droits (Unsplash, Pexels), **carrées 1200 × 1200**, nommées comme dans
      `catalogue/images/sources.csv` ; remplir les colonnes auteur et source ; lancer
      `bash scripts/images-catalogue.sh` ; recopier les sources dans le tableau du README.
- [ ] Ouvrir **Apparence > Éditeur**, ajuster à votre goût (textes de la bannière, ordre des
      sections…), puis *Create Block Theme* > « Enregistrer les changements dans le thème » pour que
      vos retouches arrivent dans `theme/culture-velib/`, et commit. Le sujet demande que le thème
      vienne de l'éditeur de site : faites-y au moins une vraie retouche vous-mêmes.
- [ ] Captures accueil, fiche produit, vue téléphone, e-mail de commande.

## Semaine 3 (23–29/11) — vendre
- [ ] Stripe en mode test (voir README partie 4). Clés de test : jamais dans Git.
- [ ] Bloc *Validation de commande* : activer la case « J'accepte les conditions » (obligatoire).
- [ ] Commande complète avec `BIENVENUE10`, e-mail dans Mailpit. Captures.
- [ ] Lancer la collection Postman sur Local (`baseUrl` = `http://boutique.local`).

## Semaine 4 (30/11–6/12) — sauvegarde et rendu
- [ ] UpdraftPlus : une sauvegarde, compléter le paragraphe du README.
- [ ] `bash scripts/sauvegarde.sh` depuis Local, déplacer les deux fichiers dans `sauvegardes/demo/`,
      commit. (Le .sql contient l'empreinte hachée du mot de passe admin, pas le mot de passe :
      utilisez un mot de passe propre à ce site.)
- [ ] **Un autre membre** clone le dépôt et lance `bash scripts/restauration.sh` sur sa machine ;
      noter son prénom et la date dans le README ; capture `captures/docker-restauree.png`.
- [ ] Les 6 captures dans `captures/` (noms déjà prévus dans le README).
- [ ] Des commits de **chaque membre**.
- [ ] Supprimer ce fichier, vérifier `git grep -nE "ck_|cs_|password|DB_PASSWORD"`, poster le lien sur Teams.
- [ ] Répéter la soutenance de 5 minutes (tout le monde parle).
