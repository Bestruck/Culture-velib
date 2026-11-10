# Culture Vélib — boutique WooCommerce

Magasin-atelier de vélos **fictif** à Strasbourg : vélos de ville, électriques et cargos, accessoires,
révisions. Projet B3.2 CMS, e-commerce & mobile — IRIS Mastère 1.

**Groupe :** NOM Prénom 1 · NOM Prénom 2 · NOM Prénom 3

| Document | Contenu |
|---|---|
| [`NOTE.md`](NOTE.md) | Partie 1 : ce que fait le CMS, modèle de données, cycle de commande, arbitrage |
| [`charte/`](charte/CHARTE.md) | Logo, icône, couleurs, polices, ton du client |
| [`theme/`](theme/) | Thème en blocs exporté depuis l'éditeur de site |
| [`catalogue/`](catalogue/) | Les 14 produits (CSV) et la source de chaque photo |
| [`api/`](api/boutique.postman_collection.json) | Collection Postman de la Store API |
| [`scripts/`](scripts/) | Configuration, import des photos, sauvegarde, restauration |
| [`extension/`](extension/culture-velib-bandeau/culture-velib-bandeau.php), [`webhook/`](webhook/) | Bonus |

## Captures

| Accueil | Fiche produit | Panier |
|---|---|---|
| ![Accueil](captures/accueil.png) | ![Fiche produit](captures/fiche-produit.png) | ![Panier](captures/panier.png) |

| Commande et e-mail | Vue téléphone (390 px) | Boutique restaurée dans Docker |
|---|---|---|
| ![Commande](captures/commande.png) | ![Téléphone](captures/telephone.png) | ![Docker](captures/docker-restauree.png) |

---

## Relancer la boutique

### Option A — à partir du dépôt, avec Docker (recommandé pour vérifier)

Prérequis : Docker Desktop, Git Bash (sous Windows).

```bash
git clone <url-du-depot> && cd boutique-client
cp .env.example .env          # puis remplir les 4 mots de passe (au hasard, ils restent sur ta machine)
bash scripts/restauration.sh  # restaure la dernière sauvegarde de sauvegardes/
```

La boutique répond sur <http://localhost:8080>. Le script lance les conteneurs, importe la base et les
médias, corrige l'adresse avec `wp search-replace`, puis réinstalle WooCommerce (les extensions
tierces ne sont pas versionnées).

### Option B — partir de zéro (site Local ou Docker vide)

```bash
# Dans Local : clic droit sur le site > Open site shell, puis à la racine du dépôt
bash scripts/configuration-boutique.sh   # langue, WooCommerce, TVA, livraison, paiement, promo, pages, catalogue
bash scripts/images-catalogue.sh         # photos + textes alternatifs + galeries
```

Avec Docker, préfixer par `WP="docker compose run --rm -T cli wp" PROJET=/projet`.
Les deux scripts sont rejouables : ils mettent à jour sans créer de doublons.

Puis activer le thème exporté : Apparence > Thèmes > **Culture Vélib**.

---

## Partie 2 — Identité et design

Thème en blocs dérivé de Twenty Twenty-Five, modifié dans **Apparence > Éditeur**, exporté avec
l'extension *Create Block Theme* dans [`theme/culture-velib/`](theme/).

- **Styles** : palette Vert Ill `#1F4D45`, Grès rose `#B8503A`, Crème `#F5EFE4` ; titres en
  Bricolage Grotesque, texte en Inter (bibliothèque de polices de WordPress). Réglés une seule fois
  dans Styles, jamais bloc par bloc.
- **En-tête** (élément de modèle) : logo, menu, recherche, compte client, mini-panier.
- **Pied de page** : adresse de l'atelier, horaires, liens vers les mentions légales, CGV, confidentialité.
- **Accueil en blocs** : bannière « Roule en ville, on s'occupe du reste » + bouton vers les vélos,
  produits mis en avant, les trois catégories, bande de réassurance (livraison offerte dès 80 €,
  retrait gratuit à l'atelier, paiement sécurisé, 14 jours pour changer d'avis).
- **Fiche produit retouchée** : *[à compléter : par ex. bloc « Révision offerte à 2 mois » sous le prix
  des vélos, parce que c'est l'argument qui fait acheter en magasin plutôt qu'en ligne]*.
- **Page boutique retouchée** : *[à compléter : par ex. filtres par catégorie et prix en colonne de gauche]*.
- **E-mails** (WooCommerce > Réglages > E-mails) : couleurs de la charte appliquées par
  `configuration-boutique.php`, logo PNG en image d'en-tête.
- **Bonus extension maison** : bandeau « Livraison offerte dès 80 € » qui affiche ce qu'il reste à
  ajouter au panier ([47 lignes](extension/culture-velib-bandeau/culture-velib-bandeau.php)).

## Partie 3 — Le catalogue

14 produits dans 3 catégories (Vélos, Accessoires, Atelier & entretien), importés depuis
[`catalogue/produits.csv`](catalogue/produits.csv) avec l'importeur officiel de WooCommerce.

| Exigence | Où |
|---|---|
| Produit variable | Vélo de ville « Kléber » : 2 attributs (Taille S/M/L, Couleur Vert Ill/Grès rose), 6 variations avec prix et stock propres ; L Grès rose à 0 |
| Promotion datée | Antivol en U : 59,90 € → 44,90 € du 20/11 au 31/12/2026 |
| Rupture volontaire | Vélo enfant « Moustique », stock 0 |
| UGS et stock | UGS `CV-…` sur chaque produit et variation, stock géré partout |
| Galeries | Kléber (3 photos), Krutenau (2), Petite France (2) |

### Sources des photos

Toutes les photos viennent de banques libres de droits. Détail fichier par fichier, avec le texte
alternatif utilisé : [`catalogue/images/sources.csv`](catalogue/images/sources.csv).

| Photo | Auteur | Source |
|---|---|---|
| *à compléter depuis sources.csv* | | Unsplash / Pexels |

## Partie 4 — Vendre

| Réglage | Choix |
|---|---|
| TVA | Prix saisis **TTC**, taux standard France **20 %** appliqué aussi à la livraison. Pas de taux réduit : vélos, pièces et réparations relèvent du taux normal (le 5,5 % vise l'alimentaire et le livre). |
| Livraison (zone France) | Colissimo domicile 7,90 € · point relais 4,90 € · **offerte dès 80 €** · retrait gratuit à l'atelier |
| Paiement | Virement bancaire (coordonnées fictives) · carte bancaire **Stripe en mode test** |
| Code promo | `BIENVENUE10` : -10 %, dès 50 € d'achat, jusqu'au 31/12/2026, une fois par client, hors articles déjà soldés |
| Pages légales | Mentions légales, CGV, politique de confidentialité ([`contenu/pages/`](contenu/pages/)) ; CGV reliées à WooCommerce, case à cocher obligatoire dans le bloc Validation de commande |

> WooCommerce enregistre toujours les frais de port **hors taxe**. Pour afficher 7,90 € TTC, le script
> saisit 6,583333 € HT (× 1,20 = 7,90 €).

**Stripe en mode test** : installer *WooCommerce Stripe Gateway*, se créer un compte Stripe
(aucune activation nécessaire), activer le **mode test** et coller les clés `pk_test_…`/`sk_test_…`
dans WooCommerce > Réglages > Paiements. Ces clés restent dans la base, jamais dans Git. Payer avec
la carte de test publiée par Stripe (`4242 4242 4242 4242`, date future, CVC quelconque).

**Commande de test de bout en bout** : fiche produit → panier → `BIENVENUE10` → validation → e-mail
de confirmation visible dans **Mailpit** (Local > onglet Tools). Captures ci-dessus.

## Partie 5 — L'API

Collection : [`api/boutique.postman_collection.json`](api/boutique.postman_collection.json), variable
`baseUrl`, un test par requête. Lancée avec `npx newman run api/boutique.postman_collection.json`
contre la boutique Docker : **5 requêtes, 14 assertions, 0 échec**.

| Requête | En-têtes envoyés et reçus |
|---|---|
| `GET /wc/store/v1/products` | On envoie `Accept: application/json` ; on reçoit `X-WP-Total` (14 produits), `X-WP-TotalPages` et un en-tête `Link` vers la page suivante, avec des prix en centimes (`currency_minor_unit: 2`, `"price": "64900"`) pour éviter les erreurs d'arrondi des nombres à virgule. |
| `GET /wc/store/v1/cart` | Rien de particulier à l'envoi ; on reçoit `Cart-Token`, un jeton JWT signé qui identifie le panier sans cookie (idéal pour une application mobile), qu'on mémorise dans la variable `cartToken`, plus `Nonce` et `Cache-Control: no-store`. |
| `POST /wc/store/v1/cart/add-item` | On renvoie `Cart-Token: {{cartToken}}` et `Content-Type: application/json` ; on reçoit `201 Created` et le même `Cart-Token` : c'est bien ce panier-là qui a été modifié. |
| `POST /wc/store/v1/checkout` | On envoie `Cart-Token` et les adresses en JSON ; la réponse contient `order_id` et le statut `on-hold` (virement) : la commande apparaît dans WooCommerce > Commandes et l'e-mail part. |
| `GET /wc/v3/orders` sans clé | On n'envoie aucune authentification ; on reçoit `401` et `woocommerce_rest_cannot_view`. **C'est une bonne nouvelle** : l'API d'administration contient noms, adresses et téléphones des clients ; elle exige une clé `ck_`/`cs_` liée à un compte autorisé. La Store API, elle, est publique mais ne donne accès qu'à son propre panier. |

## Partie 6 — Git et sécurité

Le dépôt contient ce que nous avons produit : charte, thème exporté, CSV, contenus, scripts, collection
Postman. Il ne contient **pas** le cœur de WordPress, WooCommerce ni le `.env` (voir
[`.gitignore`](.gitignore)). Les mots de passe Docker sont lus dans `.env` ; [`.env.example`](.env.example)
ne donne que les noms des variables.

`git grep -nE "ck_|cs_|password|DB_PASSWORD"` ne renvoie que des noms de variables ou de réglages
(`WORDPRESS_DB_PASSWORD: ${MYSQL_PASSWORD}`, `woocommerce_manage_stock`…), **aucune valeur secrète**.

### Cinq mesures de sécurité

| Mesure | Risque couvert |
|---|---|
| Administrateur nommé `gerance-cv`, jamais `admin`, et utilisateurs masqués de l'API REST (`/wp/v2/users`) | Attaque par force brute : l'attaquant doit deviner l'identifiant **et** le mot de passe |
| Double authentification (extension *Two-Factor*) pour les comptes administrateur et gestionnaire | Mot de passe volé par hameçonnage ou réutilisé d'un autre site |
| Limitation des tentatives de connexion (*Limit Login Attempts Reloaded*) | Essais automatisés de milliers de mots de passe sur `wp-login.php` et `xmlrpc.php` |
| Mises à jour automatiques du cœur et des extensions de sécurité, extensions inutiles supprimées | Exploitation d'une faille connue et corrigée dans une ancienne version |
| HTTPS partout (certificat Let's Encrypt), cookies sécurisés | Interception des mots de passe et des données de commande sur un Wi-Fi public |
| Rôles au plus juste : le vendeur a le rôle *Gestionnaire de boutique*, pas *Administrateur* ; clés API en lecture seule quand c'est possible | Un compte compromis ne peut ni installer d'extension ni modifier le code |

## Partie 7 — Déployer et sauvegarder

### Sauvegarde

[`scripts/sauvegarde.sh`](scripts/sauvegarde.sh) exporte la base avec `wp db export` et archive
`wp-content/uploads` en `tar.gz`, datés du jour, puis supprime les sauvegardes de plus de 14 jours.

**Chaque nuit sur un serveur**, une tâche cron :

```cron
0 3 * * * cd /srv/boutique && DEST=/mnt/sauvegardes bash scripts/sauvegarde.sh >> /var/log/sauvegarde-boutique.log 2>&1
```

`/mnt/sauvegardes` est **un autre disque** (ou un stockage réseau), puis une copie part hors du
serveur (`rclone` vers un stockage objet type S3, dans un autre datacenter). Une sauvegarde sur le même
disque que le site disparaît avec lui.

### Restauration

[`scripts/restauration.sh`](scripts/restauration.sh) rejoue les étapes du sujet : `docker compose up`,
`wp db import`, extraction des médias, `wp search-replace` (jamais un chercher-remplacer dans le
`.sql` : WordPress stocke des tableaux PHP sérialisés dont la longueur est écrite en clair, un `sed`
les casse) puis réinstallation de WooCommerce.

**Testé** : base supprimée (`docker compose down -v`), restauration sur un autre port → 14 produits,
pages et adresses corrigées (40 remplacements). *[à compléter : prénom du membre qui a rejoué la
restauration sur sa machine, et la date]*.

### Chez un hébergeur mutualisé (cPanel)

Pas de Docker ni de terminal, en général : on envoie les fichiers WordPress (cœur, `wp-content`
avec thème et médias) par le **gestionnaire de fichiers** ou en **SFTP** ; on crée une base et son
utilisateur dans *Bases de données MySQL* ; on importe le `.sql` dans **phpMyAdmin** ; on renseigne nom
de base, utilisateur et mot de passe dans **`wp-config.php`** ; on change l'adresse avec l'extension
*Better Search Replace* (qui gère la sérialisation comme `wp search-replace`) ; on active le
**certificat HTTPS** gratuit (AutoSSL / Let's Encrypt) puis on force la redirection en HTTPS.
**Mutualisé** pour un petit commerce comme Culture Vélib : quelques euros par mois, sauvegardes et
certificat gérés par l'hébergeur, aucune administration système. **Docker sur un VPS** quand on veut un
environnement identique du poste au serveur, des restaurations scriptées et testables, ou plusieurs
sites isolés — au prix de la maintenance du serveur.

### Et une extension de sauvegarde ?

*[à compléter après le test d'UpdraftPlus sur le site Local]*. **En plus** de notre script :
planification depuis l'interface, envoi automatique vers un stockage distant (Google Drive, Dropbox,
S3), restauration en un clic, sauvegarde séparée des extensions et thèmes. **En moins** : elle tourne
*dans* WordPress, donc si le site ne démarre plus (extension cassée, base corrompue, serveur perdu),
on ne peut plus cliquer sur « Restaurer » ; il faut d'abord réinstaller un WordPress. Notre script ne
dépend que de WP-CLI et de la ligne de commande.

---

## Bonus

- **Extension maison** : [`extension/culture-velib-bandeau`](extension/culture-velib-bandeau/culture-velib-bandeau.php),
  bandeau « livraison offerte » qui calcule le reste à ajouter (47 lignes).
- **Webhook « Commande créée »** : [`webhook/recepteur.mjs`](webhook/recepteur.mjs) vérifie la
  signature HMAC-SHA256 (`X-WC-Webhook-Signature`) et affiche les commandes sur <http://localhost:4000>.

  ```bash
  WEBHOOK_SECRET=<secret choisi> node webhook/recepteur.mjs
  docker compose run --rm cli wp wc webhook create --user=1 --topic=order.created \
    --delivery_url=http://host.docker.internal:4000/ --secret=<secret choisi> --status=active
  ```

  WordPress refuse par défaut d'appeler une adresse privée (protection SSRF) et les ports autres que
  80/443/8080 : le mu-plugin [`webhook/autoriser-hote-docker.php`](webhook/autoriser-hote-docker.php)
  autorise **uniquement** `host.docker.internal:4000`, en développement. Les livraisons passent par
  l'Action Scheduler ; dans Docker, WP-Cron ne se déclenche pas tout seul :
  `docker compose run --rm cli wp action-scheduler run`.
- **Application Flutter** : *[à faire si le temps le permet]*.
- **Mise en ligne** : *[adresse HTTPS, coupée après la soutenance]*.
