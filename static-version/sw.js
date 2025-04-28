// static-version/sw.js
const CACHE_NAME = 'giadung-cache-v1';
const urlsToCache = [
    '/',
    '/index.html',
    '/css/base.css',
    '/css/header.css',
    '/css/footer.css',
    '/css/message.css',
    '/css/main.css',
    '/js/index.js',
    '/web_giadung.json',
    '/assets/font/fontawesome-free-6.6.0-web/css/all.min.css'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});