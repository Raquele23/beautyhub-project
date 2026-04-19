<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            [x-cloak] { display: none !important; }
        </style>

        <script>
            window.BEAUTY_HUB_GEO_KEYS = {
                lat: 'beautyhub:last-lat',
                lon: 'beautyhub:last-lon',
            };

            window.BEAUTY_HUB_GEO = {
                save(lat, lon) {
                    try {
                        localStorage.setItem(window.BEAUTY_HUB_GEO_KEYS.lat, String(lat));
                        localStorage.setItem(window.BEAUTY_HUB_GEO_KEYS.lon, String(lon));
                    } catch (error) {
                        // Ignora falhas de storage.
                    }
                },

                read() {
                    try {
                        const lat = localStorage.getItem(window.BEAUTY_HUB_GEO_KEYS.lat);
                        const lon = localStorage.getItem(window.BEAUTY_HUB_GEO_KEYS.lon);

                        if (!lat || !lon) {
                            return null;
                        }

                        const parsedLat = parseFloat(lat);
                        const parsedLon = parseFloat(lon);

                        if (Number.isNaN(parsedLat) || Number.isNaN(parsedLon)) {
                            return null;
                        }

                        return { lat: parsedLat, lon: parsedLon };
                    } catch (error) {
                        return null;
                    }
                },

                appendToExplore(url) {
                    const coords = this.read();
                    if (!coords) {
                        return url;
                    }

                    try {
                        const exploreUrl = new window.URL(url, window.location.origin);
                        if (exploreUrl.pathname !== '/explore') {
                            return url;
                        }

                        exploreUrl.searchParams.set('lat', coords.lat.toFixed(6));
                        exploreUrl.searchParams.set('lon', coords.lon.toFixed(6));
                        return exploreUrl.toString();
                    } catch (error) {
                        return url;
                    }
                },

                enhanceExploreLinks() {
                    document.addEventListener('click', function (event) {
                        const link = event.target.closest('a[href]');
                        if (!link) {
                            return;
                        }

                        try {
                            const url = new window.URL(link.href, window.location.origin);
                            if (url.pathname !== '/explore') {
                                return;
                            }

                            const coords = window.BEAUTY_HUB_GEO.read();
                            if (!coords) {
                                return;
                            }

                            event.preventDefault();
                            window.location.href = window.BEAUTY_HUB_GEO.appendToExplore(link.href);
                        } catch (error) {
                            // Se falhar, deixa a navegação normal seguir.
                        }
                    }, true);
                },
            };

            window.BEAUTY_HUB_GEO.enhanceExploreLinks();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: #EDE4F8;">
            @include('layouts.navigation')

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>