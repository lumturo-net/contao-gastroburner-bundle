
var gastroBurnerMap = function () {
    var mapDivId = 'map', // id
        map, config, surroundingCircle,
        icon, hoverIcon,
        list,
        filter = {
            job: {
                restaurant: false,
                cook: false,
                hotelmanager: false,
                hotelcleaner: false,
                gastro: false
            },
            search: '',
            position : {lat: null, lon: null},
            distance: false
        };
    var adaptListHeight = null; // wird eine Funktion...


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
            iconUrl: 'bundles/contaogastroburner/images/marker.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        hoverIcon = new L.Icon({
            iconUrl: 'bundles/contaogastroburner/images/marker-sel.png',
            // iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
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
            filter.distance = radius;
            filterList();
        });
    }

    /**
     * Initialisierung Liste
     */
    function bootstrapList() {
        var listOptions = {
            // page: config.list.pagination.entries_per_page,
            // pagination: true,
            valueNames: ['id', 'shortname', 'name']//, 'description']
        };
        list = new List('company-list', listOptions);//, config.companies);

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
                            _addMarker(config.companies[id], icon);
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
        $('.job-filter-item input').on('change', function (e) {
            filter.job[$(this).data('type')] = !filter.job[$(this).data('type')];
            filterList();
        });

        // Filter nach Sucheingabe
        $('.js-search-in-list input').on('input', function (e) {
            if(e.keyCode == 13) {
                e.preventDefault();
                return false;
            }

            filter.job.search = $(this).val();
            filterList();
        });
        $('.js-search-in-list button').on('click', function () {
            $('.js-search-in-list input').val('');
            filter.job.search = '';
            filterList();
        });

        // hover der Menü-Einträge
        $('.company-item').hover(
            function (companies, icon) {
                return function (e) {
                    var id = $(this).data('id');
                    var marker = companies[id].marker;
                    if (marker) {
                        marker.setIcon(icon);
                    }
                }
            }(companies, hoverIcon),

            function (companies, icon) {
                return function (e) {
                    var id = $(this).data('id');
                    var marker = companies[id].marker;
                    if (marker && !$(this).find('input[type="checkbox"]').prop('checked')) {
                        marker.setIcon(icon);
                    }
                }
            }(companies, icon)
        );

        $('.company-item input').on('change', function() {
            var id = $(this).val();
            var marker = config.companies[id].marker;
            if (marker) {
                marker.setIcon($(this).is(':checked') ? hoverIcon : icon);
            }

            if ($(this).is(':checked')) {
                $('#apply_form').append('<input type="hidden" class="js-hidden-company" name="hidden_companies[]" value="' + id + '">');
                config.companies[id].selected = true;
            } else {
                $('.js-hidden-company[value="' + id + '"]').remove();
                config.companies[id].selected = false;
            }
        });

        // Suche nach PLZ
        $('.js-filter-zip').on('input', function (e) {
            if(e.keyCode == 13) {
                e.preventDefault();
                return false;
            }

            var zip = $(this).val();
            if (zip.length < 5) {
                return;
            }
            // frage https://nominatim.openstreetmap.org/search/?q=Germany,18146&format=json an
            var location = 'Germany,' + zip;
            // var geocode = 'https://open.mapquestapi.com/search?format=json&q=' + location;
            var geocode = 'https://nominatim.openstreetmap.org/search?format=json&q=' + location;
            $.getJSON(geocode, function (data) {
                // get lat + lon from first match
                map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                var latlng = [data[0].lat, data[0].lon];
                filter.position.lat=data[0].lat;
                filter.position.lon=data[0].lon;
                // console.log(filter.position);

                if (surroundingCircle) {
                    map.removeLayer(surroundingCircle);
                    surroundingCircle = null;
                    $(".js-surrounding").val($(".js-surrounding option:first").val());
                }
                // console.log(latlng);
            });

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

            return _filterByJob(company) &&
                   (
                       _filterByMarkerVisibility(company) ||
                       _filterBySelected(company)
                   ) &&
                   _filterBySearch(company) &&
                   _filterByLatLon(company);
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
            marker['companyId'] = id;
        if (icon) {
            marker.setIcon(icon);
        }
        marker.addTo(map);
        marker.bindPopup('<h5>' + config.companies[id].shortname + '</h5><img src="' + config.companies[id].companyLogo + '" width="60" height="44">');
        marker.on('click', function() {
            var checkbox =  $('[id="company-' + marker.companyId + '"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
                checkbox.trigger('change');

            var scrollTop = checkbox.parent().offset().top;
            var container = $('#company-list .list');
            $('#company-list .list').animate({
                scrollTop: checkbox.prop('checked') ? scrollTop - container.offset().top + container.scrollTop() : 0
            }, 2000);

            marker.setIcon(checkbox.prop('checked') ? hoverIcon : icon);
            checkbox.prop('checked') ? config.companies[id].selected = true : null;
            !checkbox.prop('checked') ? marker.closePopup() : null;
        });

        config.companies[id].marker = marker;
    }

    function _filterByMarkerVisibility(company) {
        var marker = company.marker;
        if (marker) {
            return map.getBounds().contains(company.marker.getLatLng());
        }
    }

    function _filterBySearch(company) {
        if (filter.job.search == '') {
            return true;
        }
        var regexp = new RegExp(filter.job.search, 'i');
        return company.shortname.search(regexp) != -1 || company.company.search(regexp) != -1 || company.shortdesc.search(regexp) != -1;
    }

    function _filterByJob(company) {
        var ret = false;
        var overallFilterSet = false;
        for (var job_type in filter.job) {
            if (filter.job[job_type]) {
                overallFilterSet = true;
                if (company[job_type] != 0) {
                    ret = true;
                }
            }
        }
        if (overallFilterSet) {
            return ret;
        } else {
            return true;// kein filter gesetzt
        }
    }

    function _filterBySelected(company) {
        return company.selected;
    }

    function _filterByLatLon(company) {
        if (!filter.distance) {
            return true;
        }

        var  lat = parseFloat(company.lat),
        lon = parseFloat(company.lon),
        filterDistance = filter.distance / 1000;
        if (!lat || !lon) {
            return false;
        }
        var dist = distance(filter.position.lat, filter.position.lon, lat, lon, 'K');
        // console.log("distance: " + distance<filterDistance + " " + distance + "< " + filterDistance);
        return (dist < filterDistance);
    }

    function distance(lat1, lon1, lat2, lon2, unit) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        }
        else {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var theta = lon1 - lon2;
            var radtheta = Math.PI * theta / 180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") { dist = dist * 1.609344 }
            if (unit == "N") { dist = dist * 0.8684 }
            return dist;
        }
    }


    /**
     * API
     */
    return {
        bootstrap: function (_config) {
            config = _config;
            var bsMap = bootstrapMap;
            var bsList = bootstrapList;
            loadCss('/bundles/contaogastroburner/css/leaflet.css');
            $('<div class="js-spacer spacer"></div>').insertBefore('.mod_gastroburnerapplyform');
            bsMap();
            bsList();
            // brauche den Spacer ausserhalb dieses Modul-HTML-Snippets
        },
        getMap() {
            return map;
        },
        resizeList() {
            filterList();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function() {
    if($('#map').length > 0) {
        gastroBurnerMap.bootstrap({
            map: {
                center: [54.081417, 12.7653133],
                zoom: 8
            },
            list: {
                pagination: {
                    entries_per_page: 3
                }
            },
            companies: companies
        });

        var $listMapContainer = $('.list-map-container');
        var $listMapContainerOffsetTop = $listMapContainer.offset().top;
    }

    $('.js-map-mode, .js-list-mode').on('click', function() {
        $('.map-container').toggleClass('active').toggleClass('map-mode');
        $('.map-container').parents('form').toggleClass('map-mode');
        gastroBurnerMap.getMap().invalidateSize();
        $('.js-search-in-list').toggleClass('d-flex d-none');
        $('.js-list-mode').toggleClass('active');
    });

    $('.js-toggle-checkbox').on('click', function () {
        $('.js-submit-application').toggleClass('btn--disable');
        $(this).toggleClass('active');
    });

    $('.company-item input').on('change', function() {
        let $selected = $('.company-item input:checked').length;

        $('.js-counter-btn button').toggleClass('btn--disable', !$selected > 0);
        $('.js-counter-btn .counter').toggleClass('hasItems', $selected > 0).text($selected)
    });

    if($('.js-toggle-checkbox').length > 0) {
        $('#apply_form').on('submit', function (e) {
            if ($('.js-toggle-checkbox').hasClass('active')) {
                return true;
            } else {
                $('.js-dataprivacy').addClass('error');
                return false;
            }
        });
    }

    $('.btn-remove-company').on('click', function() {
        $('.js-hidden-company[value="' + $(this).data('id') + '"]').remove();
        $(this).parents('.company').remove();
    });

    $(document).on('scroll', function() {
        if(!window.matchMedia('(min-width:1200px)').matches) {
            $listMapContainer.addClass('fixed', $listMapContainer[0].getBoundingClientRect().top <= 0);
            $listMapContainer.parents('form').addClass('has-fixed', $listMapContainer[0].getBoundingClientRect().top <= 0);

            if($listMapContainer.offset().top < $listMapContainerOffsetTop) {
                $listMapContainer.removeClass('fixed');
                $listMapContainer.parents('form').removeClass('has-fixed');
            }
        }
    });
});
