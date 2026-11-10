# À faire par le groupe (à supprimer avant le rendu)

Ce qui est déjà prêt et testé : charte, note, catalogue CSV, configuration WooCommerce par script,
pages légales, collection Postman (14/14), Docker, sauvegarde, restauration, extension, webhook.
Il reste ce qui se fait à la souris, et ce qui doit venir de vous.

## Semaine 1 (avant le 15/11)
- [ ] Remplacer `NOM Prénom` dans `README.md` et `NOTE.md`. Relire la note et la reformuler avec vos mots.
- [ ] Créer le dépôt GitHub, `git remote add origin …`, `git push -u origin main`, inviter le groupe.
- [ ] Installer **Local** (localwp.com), créer le site `boutique` (adresse `http://boutique.local`),
      administrateur **`gerance-rdi`** (pas `admin`).
- [ ] Local > clic droit > *Open site shell*, aller à la racine du dépôt, puis :
      `"C:\Program Files\Git\bin\bash.exe" scripts/configuration-boutique.sh`

## Semaine 2 (16–22/11) — design et photos
- [ ] Exporter `charte/logo.svg` et `charte/icone.svg` en **PNG transparent** (WordPress refuse le SVG) :
      logo 720 px de large, icône 512 × 512. Ouvrir le SVG dans Figma ou Inkscape, exporter.
- [ ] 18 photos libres de droits (Unsplash, Pexels), **carrées 1200 × 1200**, nommées comme dans
      `catalogue/images/sources.csv` ; remplir les colonnes auteur et source ; lancer
      `bash scripts/images-catalogue.sh` ; recopier les sources dans le tableau du README.
- [ ] Apparence > Éditeur > Styles : palette + polices (Bricolage Grotesque, Inter) depuis la bibliothèque de polices.
- [ ] En-tête (logo, menu, compte, mini-panier) et pied de page (adresse, liens légaux).
- [ ] Accueil en blocs : bannière + bouton, produits mis en avant, catégories, réassurance.
- [ ] Retoucher les modèles *Produit unique* et *Catalogue de produits* ; noter le pourquoi dans le README.
- [ ] Vérifier à 390 px (outils de développement du navigateur).
- [ ] Logo PNG dans WooCommerce > Réglages > E-mails (image d'en-tête).
- [ ] Extension *Create Block Theme* > Exporter le thème, nom **Rayons d'Ill**, dézipper dans
      `theme/rayons-dill/` (le dossier monté par Docker), commit.

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
