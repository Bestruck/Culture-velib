// Reçoit le webhook WooCommerce « Commande créée », vérifie sa signature et l'affiche.
//   WEBHOOK_SECRET=... node webhook/recepteur.mjs
// Puis ouvrir http://localhost:4000 : les commandes reçues s'affichent (rafraîchissement toutes les 5 s).
import { createServer } from 'node:http';
import { createHmac, timingSafeEqual } from 'node:crypto';

const PORT = Number(process.env.PORT ?? 4000);
const SECRET = process.env.WEBHOOK_SECRET;
if (!SECRET) {
  console.error('Définis WEBHOOK_SECRET (le même que dans WooCommerce > Réglages > Avancé > Webhooks).');
  process.exit(1);
}

const recues = [];
const echapper = (s) => String(s ?? '').replace(/[&<>"]/g, (c) => `&#${c.charCodeAt(0)};`);

function signatureValide(corps, signature) {
  const attendue = createHmac('sha256', SECRET).update(corps).digest('base64');
  const a = Buffer.from(attendue);
  const b = Buffer.from(signature ?? '');
  return a.length === b.length && timingSafeEqual(a, b);
}

createServer((req, res) => {
  if (req.method === 'GET') {
    const lignes = recues
      .map((c) => `<tr><td>${c.recu}</td><td>#${c.id}</td><td>${echapper(c.client)}</td><td>${echapper(c.total)} €</td><td>${echapper(c.statut)}</td><td>${echapper(c.paiement)}</td></tr>`)
      .join('');
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(`<!doctype html><meta charset="utf-8"><meta http-equiv="refresh" content="5"><title>Webhooks Culture Vélib</title>
<style>body{font-family:Montserrat,Arial,sans-serif;background:#E9EEF2;color:#0D1B2A;padding:2rem}h1{color:#0D1B2A}table{border-collapse:collapse;background:#fff}td,th{padding:.5rem 1rem;border-bottom:1px solid #ddd;text-align:left}th{background:#0D1B2A;color:#fff}</style>
<h1>Commandes reçues par webhook</h1><table><tr><th>Reçu à</th><th>Commande</th><th>Client</th><th>Total</th><th>Statut</th><th>Paiement</th></tr>${lignes || '<tr><td colspan="6">En attente d’une commande…</td></tr>'}</table>`);
  }

  let corps = '';
  req.on('data', (morceau) => (corps += morceau));
  req.on('end', () => {
    // À la création du webhook, WooCommerce envoie un ping « webhook_id=… » non signé.
    if (!req.headers['x-wc-webhook-signature']) {
      console.log('Ping de WooCommerce reçu :', corps);
      return res.writeHead(200).end('ok');
    }
    if (!signatureValide(corps, req.headers['x-wc-webhook-signature'])) {
      console.warn('Signature invalide : requête rejetée');
      return res.writeHead(401).end('signature invalide');
    }
    const commande = JSON.parse(corps);
    const c = {
      recu: new Date().toLocaleTimeString('fr-FR'),
      id: commande.id,
      client: `${commande.billing?.first_name ?? ''} ${commande.billing?.last_name ?? ''}`.trim(),
      total: commande.total,
      statut: commande.status,
      paiement: commande.payment_method_title,
    };
    recues.unshift(c);
    console.log(`[${req.headers['x-wc-webhook-topic']}] commande #${c.id} — ${c.client} — ${c.total} € — ${c.statut}`);
    res.writeHead(200).end('ok');
  });
}).listen(PORT, () => console.log(`Récepteur de webhooks : http://localhost:${PORT}`));
