const assetsCacheName = 'v1-assets';
const dynamicCacheName = 'v1-dynamic';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    self.clients.claim()
});

self.addEventListener('fetch', (event) => {

    // Ignore crossdomain requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }
    // Ignore non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }
    // Ignore browser-sync
    if (event.request.url.indexOf('browser-sync') > -1) {
        return;
    }
    // Prevent index route being cached - activate when doing cache first to avoid stale app
    /*if (event.request.url === (self.location.origin + '/')) {
        return;
    }*/

    // if (event.request.url === (self.location.origin + '/undefined')) {
    //     return;
    // }

    // Tell the fetch to respond with this Promise chain
    event.respondWith(
        // Open the cache

        /*  Activate this when using cache first, fallback to network for static content like css,js
        caches.open(assetsCacheName)
            .then((cache) => {
                // Look for matching request in the cache
                return cache.match(event.request)
                    .then((matched) => {
                        // If a match is found return the cached version first
                        if (matched) {
                            return matched;
                        }
                        // Otherwise continue to the network
                        return fetch(event.request)
                            .then((response) => {
                                // Cache the response
                                cache.put(event.request, response.clone());
                                // Return the original response to the page
                                return response;
                            });
                    });
            })
        */

        /* In our case, we want to use network first, then fallback
          to cache due to API requests done on the server side
        */
        caches.open(assetsCacheName).then((cache) => {
            // Make the request to the network
            return fetch(event.request)
                .then((response) => {
                    // Cache the response
                    cache.put(event.request, response.clone());
                    // Return the original response
                    return response;
                }).catch(() => {
                    // On failure look for a match in the cache
                    return caches.match(event.request);
                });
        })
    );
});

/**
*
* DYNAMIC CACHING - Not needed now since we are doing server to server
* - Great for true client > server
*
*/
self.addEventListener('fetch', (event) => {
    // Ignore non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }
    // Ignore browser-sync
    if (event.request.url.indexOf('browser-sync') > -1) {
        return;
    }
    let allow = false;

    // Allow index route to be cached
    if (event.request.url === (self.location.origin + '/')) {
        allow = true;
    }
    // Allow index without trailing slash to be cached
    if (event.request.url === self.location.origin) {
        allow = true;
    }
    // Allow API requests to be cached
    if (event.request.url.startsWith('https://api.mdarasa.co.ke:8443/api/v1')) {
        allow = true;
    }

    if (allow) {
        // Detect requests to API
        if (event.request.url.startsWith('https://api.mdarasa.co.ke:8443/api/v1')) {
            // Network first
            event.respondWith(
                // Open the dynamic cache
                caches.open(dynamicCacheName).then((cache) => {
                    // Make the request to the network
                    return fetch(event.request)
                        .then((response) => {
                            // Cache the response
                            cache.put(event.request, response.clone());
                            // Return the original response
                            return response;
                        });
                })
            );
        }
    } else {
        console.log("Ignoring unauthorized domain")
    }
});