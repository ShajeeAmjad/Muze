// Cache name has a timestamp because the browser re-caches the assets when the service worker file is modified
const staticCacheName = "M8-cache-" + "22-04-01-1448";
const assets = [
    "assets/images/MuzeAlternateLogo.png",
    "assets/images/aurora.jpg",
    "assets/images/backgroundDraft.png",
    "assets/images/blackwhite.jpg",
    "assets/images/bluewhite.jpg",
    "assets/images/city.jpg",
    "assets/images/desert.jpg",
    "assets/images/forest-old.jpg",
    "assets/images/forest.jpg",
    "assets/images/greenwhite.jpg",
    "assets/images/guessTheSong.pdf",
    "assets/images/guessTheSong.png",
    "assets/images/heart_filled.pdf",
    "assets/images/heart_unfilled.pdf",
    "assets/images/icon.png",
    "assets/images/mountain.jpg",
    "assets/images/muze_image.png",
    "assets/images/ocean.jpg",
    "assets/images/pinkred.jpg",
    "assets/images/playlist.webp",
    "assets/images/purplecyan.jpg",
    "assets/images/redblack.jpg",
    "assets/images/redblue.jpg",
    "assets/images/redyellow.jpg",
    "assets/images/songHangman.png",
    "assets/images/space_bg.jpg",
    "assets/images/stars.jpg",
    "assets/images/sunset.jpg",
    "assets/images/hangman/hangman_stage1.png",
    "assets/images/hangman/hangman_stage2.png",
    "assets/images/hangman/hangman_stage3.png",
    "assets/images/hangman/hangman_stage4.png",
    "assets/images/hangman/hangman_stage5.png",
    "assets/images/hangman/hangman_stage6.png",
    "assets/images/hangman/hangman_stage7.png",
    "assets/images/hangman/hangman_stage8.png",
    "assets/images/hangman/hangman_stage9.png",
    "assets/images/hangman/hangman_stage10.png",
];

self.addEventListener('install', (evt) => {
    evt.waitUntil(
        (async () => {
            const cache = await caches.open(staticCacheName);
            await cache.addAll(assets);
        })()
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        (async () => {
            let cacheNames = await caches.keys();
            await Promise.all(cacheNames.map((cacheName) => {
                if (staticCacheName.indexOf(cacheName) === -1) return caches.delete(cacheName);
            }));
        })()
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        (async () => {
            let response = await caches.match(event.request.url, {
                cacheName: staticCacheName
            });
            if (!!response) return response;
            else return await fetch(event.request);
        })()
    );
});
