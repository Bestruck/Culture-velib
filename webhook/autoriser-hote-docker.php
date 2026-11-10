<?php
/**
 * Plugin Name: Rayons d'Ill — Webhooks vers la machine hôte (développement)
 * Description: WordPress refuse d'appeler une adresse privée (protection SSRF). En local, le récepteur de
 *              webhooks tourne sur la machine hôte : on autorise uniquement host.docker.internal.
 *              Inutile en production, où le webhook vise une adresse publique en HTTPS.
 */
defined( 'ABSPATH' ) || exit;

add_filter(
	'http_request_host_is_external',
	fn( $externe, $hote ) => $externe || 'host.docker.internal' === $hote,
	10,
	2
);

// WordPress n'accepte que les ports 80, 443 et 8080 : on ajoute celui du récepteur (webhook/recepteur.mjs).
add_filter( 'http_allowed_safe_ports', fn( $ports ) => array_merge( $ports, array( 4000 ) ) );
