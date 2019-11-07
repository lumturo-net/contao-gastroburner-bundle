
var gastroBurnerMap = function () {
    var mapDivId = 'map'; // id
    var map, config, surroundingCircle;
    var icon, hoverIcon;
    var list;
    var filter = {
        job: {
            restaurant: false,
            cook: false,
            hotelmanager: false,
            hotelcleaner: false,
            gastro: false
        },
        distance: false
    }

    /**
     *  css wird von requirejs nicht unterstützt 
     * @param {*} url 
     */
    function loadCss(url) {
        var link = document.createElement("link");
        link.type = "text/css";
        link.rel = "stylesheet";
        link.href = url;
        document.getElementsByTagName("head")[0].appendChild(link);
    }
    function calculateDist() {
        // siehe https://www.movable-type.co.uk/scripts/latlong.html
    }

    /**
     * Initialisierung Map
     */
    function bootstrapMap() {
        map = L.map(mapDivId).setView(config.map.center, config.map.zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        // https://github.com/pointhi/leaflet-color-markers
        icon = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        hoverIcon = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var f = function () {
            filterList();
        }

        map.on('moveend', f);
        map.on('zoomend', f);
        map.on('resize', f);

        // Geo-Koordinaten abfragen

        // Circle zeichen
        $('.js-surrounding').on('change', function () {
            var radius = $(this).val();
            if (radius == 5000) {
                var bounds = map.getCenter().toBounds(parseInt(radius) * 1.8);
            } else {
                var bounds = map.getCenter().toBounds(parseInt(radius) * 1.5);
            }

            if (!surroundingCircle) {
                surroundingCircle = L.circle(map.getCenter(), {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);
            }
            else {
                surroundingCircle.setRadius(radius);
            }
            map.fitBounds(bounds);
        });
    }

    /**
     * Initialisierung Liste
     */
    function bootstrapList() {
        var listOptions = {
            page: config.list.pagination.entries_per_page,
            pagination: true,
            valueNames: ['id', 'shortname', 'name']//, 'description']
        };
        list = new List('company_list', listOptions);//, config.companies);

        /**
         * Listen-Update-Event: setzt / löscht die Marker
         * übergebe die _addMarker-Methode
         */
        list.on('updated', function (_addMarker) {
            return function (e) {
                // marker entsprechechend löschen / anzeigen
                var _companies = list.items;
                for (var i in _companies) {
                    var id = _companies[i].values().id;
                    if (_companies[i].visible()) {
                        if (!config.companies[id].marker) {
                            _addMarker(config.companies[id]);
                        }
                    } else {
                        var marker = config.companies[id].marker;
                        if (marker) {
                            map.removeLayer(marker);
                            config.companies[id].marker = null;
                        }
                    }
                }
            }
        }(_addMarker));
        list.update();

        // Filter nach Job-Button
        $('.js-filter').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var type = $this.data('type')
            if ($this.hasClass('active')) {
                filter.job[type] = false;
            } else {
                filter.job[type] = true;
            }
            $this.toggleClass('active');
            filterList();
        })

        // hover der Menü-Einträge
        $('.js_company_list_item').hover(function (companies, icon) {
            return function (e) {
                var id = $(this).data('id');
                var marker = companies[id].marker;
                if (marker) {
                    marker.setIcon(icon);
                }
            }
        }(companies, hoverIcon), function (companies, icon) {
            return function (e) {
                var id = $(this).data('id');
                var marker = companies[id].marker;
                if (marker) {
                    marker.setIcon(icon);
                }
            }
        }(companies, icon));

        // Click auf Ausbildungsbetriebe in der Liste
        // Da sie durch die Pagination versteckt und nicht mehr gezählt werden können,
        // muss ich die Werten als "hidden" ins form duplizieren
        $('form').on('change', '.js-company', function() {
            var $this = $(this);
            if ($this.prop('checked')) {
                $('form').append('<input type="hidden" class="js-hidden-company" name="hidden_companies[]" value="'+$this.val()+'">');
                $('.js-summary').show();
            } else {
                $('.js-hidden-company[value="'+$this.val()+'"]').remove();
                if (!$('.js-hidden-company').length) {
                    $('.js-summary').hide();
                }
            }
        });
    }

    /**
     * Filtert die Liste
     */
    function filterList() {
        list.filter(); // alle Filter löschen
        /**
         * Filter-Funktion für Liste
         * @param {*} item 
         */
        var filter = function (item) {
            var id = item.values().id;
            var company = config.companies[id];
            return _filterByJob(company) && _filterByMarkerVisibility(company);
        }
        list.filter(filter);
    }

    /**
     * @param {Object} company - kompl. JS Object 
     * @param {Icon} icon 
     */
    function _addMarker(company, icon) {
        var id = company.id;
        var marker = L.marker([config.companies[id].lat, config.companies[id].lon]);
        if (icon) {
            marker.setIcon(icon);
        }
        marker.addTo(map);
        config.companies[id].marker = marker;
    }

    function _filterByMarkerVisibility(company) {
        var marker = company.marker;
        if (marker) {
            return map.getBounds().contains(company.marker.getLatLng());
        }
    }

    function _filterByJob(company) {
        for (var job_type in filter.job) {
            if (filter.job[job_type] && company[job_type] == 0) {
                return false;
            }
        }
        return true;
    }


    /**
     * API
     */
    return {
        bootstrap: function (_config) {
            config = _config;
            var bsMap = bootstrapMap;
            var bsList = bootstrapList;
            loadCss('//unpkg.com/leaflet@1.5.1/dist/leaflet.css');
            require([
                '//unpkg.com/leaflet@1.5.1/dist/leaflet.js',
                '//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js'
            ], function () {
                require([
                    '//unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js'
                ], function () {
                    bsMap();
                    bsList();
                });
            });
        },
        // filterByLatLon(minLat, minLon, maxLat, maxLon) { },
        // filterByDistance(lat, lon, km) { },
        // filterByJob(cook, gastro, hotelmanager) { }
    }
}();

window.onload = function () {
    gastroBurnerMap.bootstrap({
        map: {
            center: [54.0887, 12.14049],
            zoom: 8
        },
        list: {
            pagination: {
                entries_per_page: 3
            }
        },
        companies: companies
    });
}