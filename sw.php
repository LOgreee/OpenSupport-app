<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/javascript; charset=utf-8');
header('Service-Worker-Allowed: /');

$baseUrl = rtrim($opensupport_link, '/');
?>
const CACHE_NAME = 'opensupport-v1';
const BASE_URL = '<?= $baseUrl ?>';

const ASSETS_TO_CACHE = [
    BASE_URL + '/offline.html',

    BASE_URL + '/src/css/main.css',
    BASE_URL + '/src/js/main.js',

    BASE_URL + '/src/icons/account_circle.svg',
    BASE_URL + '/src/icons/arrow_menu_close.svg',
    BASE_URL + '/src/icons/back_arrow.svg',
    BASE_URL + '/src/icons/bar_chart.svg',
    BASE_URL + '/src/icons/documentation.svg',
    BASE_URL + '/src/icons/feedback.svg',
    BASE_URL + '/src/icons/group.svg',
    BASE_URL + '/src/icons/home.svg',
    BASE_URL + '/src/icons/logout.svg',
    BASE_URL + '/src/icons/priority_high.svg',
    BASE_URL + '/src/icons/priority_low.svg',
    BASE_URL + '/src/icons/priority_neutral.svg',
    BASE_URL + '/src/icons/priority_very_high.svg',
    BASE_URL + '/src/icons/priority_very_low.svg',
    BASE_URL + '/src/icons/settings.svg',
    BASE_URL + '/src/icons/ticket.svg',
    BASE_URL + '/src/icons/visibility.svg',
    BASE_URL + '/src/icons/visibility_off.svg',

    BASE_URL + '/src/opensupport_assets/opensupport_icon.svg',
    BASE_URL + '/src/opensupport_assets/opensupport_icon_white.svg',
    BASE_URL + '/src/opensupport_assets/opensupport_logo.svg',
    BASE_URL + '/src/opensupport_assets/opensupport_logo_white.svg',
    BASE_URL + '/src/opensupport_assets/opensupport_icon_192.png',
    BASE_URL + '/src/opensupport_assets/opensupport_icon_512.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(BASE_URL + '/offline.html'))
        );
        return;
    }

    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Met à jour le cache si la ressource fait partie des assets statiques
                if (response.ok && ASSETS_TO_CACHE.includes(event.request.url)) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() => caches.match(event.request))
    );
});