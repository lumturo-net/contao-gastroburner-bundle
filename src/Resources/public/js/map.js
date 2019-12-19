
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
        });
    }

    /**
     * Initialisierung Liste
     */
    function bootstrapList() {
        /**
         * Höhe der Liste auf Höhe der Map-Spalte setzen
         * + scrolling
         */
        adaptListHeight = (function () {
            if ($(window).width() < 1200) {
                // mobile --> raus
                return function () { }
            }

            // var maxHeight = parseInt($('#map').css('height')) + 61;
            var $ul = $('.hotel-list'),
                $map = $('#map'),
                // maxHeight = $map.height() + $('.filter').height() - $('.gb-input-group.mb-3').height(),//+ 61;
                itemHeight = $ul.find('li').first().height(),
                $mod_gastroburnerapplyform = $('.mod_gastroburnerapplyform'),
                modHeight = $mod_gastroburnerapplyform.height(),
                $spacer = $('.js-spacer'),
                scene = null, controller = new ScrollMagic.Controller();


            return function () {
                var $newItems = $ul.find('li'),
                    newItemCount = $newItems.length,
                    maxHeight = $map.height() + $('.filter').height() - $('.gb-input-group.mb-3').height() + 7,//+ 61;
                    ulHeight = newItemCount * itemHeight;//$newItems.eq(0).height();

                // if (/*itemCount == newItemCount && scene != null &&*/ maxHeight == ulHeight) {
                // if (maxHeight == ulHeight) {
                // bleibt alles wie bisher; filter haben keine Anpassung bedingt
                // return;
                // }

                // var progress = 0, scrollTop = 0;
                // var diff = ulHeight - maxHeight - 7;
                var duration = Math.max(1, ulHeight - maxHeight);
                // console.log('duration=' + duration);

                if (true) {// || ulHeight > maxHeight) {
                    $ul.css({
                        'overflow': 'hidden',
                        'height': maxHeight
                    })
                    if (scene) {
                        var state = scene.state();
                        // console.log(state);
                        if (cene.duration() != duration) {
                            if (state == "BEFORE") {
                                // nur länge anpassen, rest dann in der szene
                                scene.duration(duration);
                                scene.update(true);
                            }
                            if (state == "DURING") {
                                // länge anpassen und an den Anfang springen
                                $spacer.css('height', modHeight + duration);
                                $(window).scrollTop(scene.triggerPosition());
                                scene.duration(duration);
                                scene.update(true);
                            }
                            if (state == "AFTER") {
                                // Länge anpassen
                                // console.log('AFTER: duration=' + duration)
                                scene.duration(duration);
                                // scene.update(true);
                            }
                            return;
                        }
                    }

                    scene = new ScrollMagic.Scene({
                        triggerElement: '.js-spacer',//'.mod_gastroburnerapplyform',
                        triggerHook: 0,
                        duration: duration
                    })
                        .addIndicators({ name: 'pin' })
                        .on('start', function (e) {
                            var dir = e.target.controller().info("scrollDirection");
                            var duration = this.duration();
                            if (dir == 'FORWARD') {
                                $spacer.css('height', modHeight + duration);
                                $mod_gastroburnerapplyform.css({
                                    position: 'fixed',
                                    top: 0,
                                    'z-index': 2
                                });
                            } else {
                                $spacer.css('height', 0);
                                $mod_gastroburnerapplyform.css({
                                    position: 'initial',
                                    top: 0
                                });
                                $(window).scrollTop($mod_gastroburnerapplyform.offset().top);
                            }
                        })
                        .on('progress', function (e) {
                            var progress = e.progress.toFixed(3);
                            var scrollTop = progress * duration;
                            $ul.scrollTop(scrollTop);
                        })
                        .on('end', function (e) {
                            var dir = e.target.controller().info("scrollDirection");
                            var duration = this.duration();
                            if (dir == 'FORWARD') {
                                $spacer.css('height', duration)
                                $mod_gastroburnerapplyform.css({
                                    position: 'initial',//'relative',
                                    top: duration
                                })
                                $(window).scrollTop(scene.triggerPosition())// + duration);
                                // $(window).scrollTop($mod_gastroburnerapplyform.offset().top);
                            } else {
                                $spacer.css('height', modHeight + duration);
                                $mod_gastroburnerapplyform.css({
                                    position: 'fixed',
                                    top: 0
                                });

                                $(window).scrollTop($mod_gastroburnerapplyform.offset().top);
                            }

                        })
                        .addTo(controller);

                    // console.log('scene neu: duration = ' + duration)
                    // console.log('anz = ' + newItemCount + "; height=" + $newItems.first().height());
                    // scene = new ScrollMagic.Scene({
                    //     triggerElement: '.mod_gastroburnerapplyform',
                    //     triggerHook: 0,
                    //     duration: duration
                    // })
                    //     .setPin('.js-apply-form-wrapper')
                    //     // .addIndicators({ name: 'pin' })
                    //     .on('progress', function (e) {
                    //         progress = e.progress.toFixed(3);
                    //         scrollTop = progress * diff;
                    //         $ul.scrollTop(scrollTop);
                    //     })
                    //     .addTo(controller);
                }
                else {
                    console.log('scene wech')
                    /*
                    $ul.css({
                        'overflow': 'initial',
                        'height': 'initial'
                    }).scrollTop(0);
                    if (scene) {
                        // scene.removePin(true);
                        // lasse die szene so stehen, allerdings länge 1 und trigger = akt.Position
                        // damit es kein springen in der seite gibt, falls ich mitten in szene bind
                        // und der filter die liste aber einkürzt, so dass kein scrolling mehr nötig wäre
                        // scene.remove();
                        // scene = null;
                        var newDuration = $(window).scrollTop() - scene.scrollOffset() + 1;
                        if (newDuration < 0 || scene.state() != 'DURING') {
                            // noch gar nicht gestartet
                            if (scene) {
                                scene.remove();
                                scene = null;
                                $('.scrollmagic-pin-spacer').css({
                                    'padding-top': '0px',
                                    'min-height': '0px'
                                })
                            }
                        } else {
                            // mittendrin --> hier beenden (duration verkürzen)
                            scene.duration(newDuration)
                            scene.on('end', function (e) {
                                if (scene) {
                                    scene.remove();
                                    scene = null;
                                    // manuell: pin-spacer höhe auf 0
                                    // var scrollDiff = parseInt($('.scrollmagic-pin-spacer').css('padding-top')) + parseInt($('.scrollmagic-pin-spacer').css('min-height'));
                                    $('.scrollmagic-pin-spacer').css({
                                        'padding-top': '0px',
                                        'min-height': '0px'
                                    })
                                    // scrollversatz ausgleichen
                                    // $(window).scrollTop($(window).scrollTop() + scrollDiff + 10);
                                }
                            })
                        }
                    }
                    */
                }
                itemCount = newItemCount;
            }
        })();

        var listOptions = {
            // page: config.list.pagination.entries_per_page,
            // pagination: true,
            valueNames: ['id', 'shortname', 'name']//, 'description']
        };
        list = new List('js-company-list', listOptions);//, config.companies);

        /**
         * Listen-Update-Event: setzt / löscht die Marker
         * übergebe die _addMarker-Methode
         */
        list.on('updated', function (_addMarker) {
            return function (e) {
                // marker entsprechechend löschen / anzeigen
                var _companies = list.items;
                for (var i in _companies) {
                    // if (i == 0) continue; // dummy list entry
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
        $('.js-job-filter').on('click', function (e) {
            // e.preventDefault();
            var $this = $(this);
            var type = $this.data('type')
            var $label = $this.next('label')
            if ($label.hasClass('active')) {
                filter.job[type] = false;
            } else {
                filter.job[type] = true;
            }
            $label.toggleClass('active');
            filterList();
        });

        // Filter nach Sucheingabe
        $('#js-search').on('input', function (e) {
            filter.job.search = $(this).val();
            filterList();
            $(this).focus();
        });
        $('#js-clear-search').on('click', function () {
            $('#js-search').val('');
            filter.job.search = '';
            filterList();
        });

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
        $('form').on('change', '.js-list-column-checkbox', function () {
            var $this = $(this);
            if ($this.prop('checked')) {
                $('form').append('<input type="hidden" class="js-hidden-company" name="hidden_companies[]" value="' + $this.val() + '">');
                $('.js-form-div').removeClass('d-xl-none');
            } else {
                $('.js-hidden-company[value="' + $this.val() + '"]').remove();
                if (!$('.js-hidden-company').length) {
                    $('.js-form-div').addClass('d-xl-none');
                }
            }
        });

        // Suche nach PLZ
        $('.js-filter-zip').on('input', function (e) {
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
                var latlng = [data[0].lat, data[0].lon]

                if (surroundingCircle) {
                    map.removeLayer(surroundingCircle);
                    surroundingCircle = null;
                    $(".js-surrounding").val($(".js-surrounding option:first").val());
                }
                // console.log(latlng);
            });

        });

        adaptListHeight();

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
            return _filterByJob(company) && _filterByMarkerVisibility(company) && _filterBySearch(company);
        }
        list.filter(filter);
        adaptListHeight();
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

    function _filterBySearch(company) {
        if (filter.job.search == '') {
            return true;
        }
        var regexp = new RegExp(filter.job.search, 'i');
        return company.shortname.search(regexp) != -1 || company.company.search(regexp) != -1 || company.description.search(regexp) != -1;
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


    /**
     * API
     */
    return {
        bootstrap: function (_config) {
            config = _config;
            var bsMap = bootstrapMap;
            var bsList = bootstrapList;
            // loadCss('//unpkg.com/leaflet@1.5.1/dist/leaflet.css');
            loadCss('/bundles/contaogastroburner/css/leaflet.css');
            /*
            require([
                // '//unpkg.com/leaflet@1.5.1/dist/leaflet.js',
                '/bundles/contaogastroburner/js/leaflet.js',
            //     '//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js'
            ], function () {
                // require([
                    // '//unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js'
                // ], function () {
                    bsMap();
                    // bsList();
                // });
            });
            */
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

    /**
     * Breite von Karte / Liste anpassen
     */
    var resize = debounce((function (api) {
        var $list = $('.list-map'),
            $container = $('.container'),
            $map = $('#map');
        return function () {
            if ($(window).width() < 1200) {
                $map.css({
                    'position': 'absolute',
                    'left': '-' + ($map.offset().left - 15) + 'px',
                    'width': $(window).width()
                });
                $('#js-company-list').css({
                    'margin-top': $map.height() + 20
                })
                return;
            }
            $list.width(parseInt($container.width()) + parseInt($container.css('margin-right')) + 45); // + 3xmargin
            // setze höhe der karte: laut layout 1060x680
            // breite ist automatisch
            $map.height($map.width() * 680 / 1060);
            api.getMap().invalidateSize(); // Karte neu zeichnen

        }
    })(gastroBurnerMap), 500);

    $(window).resize(function () {
        resize();
    });
    resize();

    // $('.js-toggle-label').on('click', function () {
    //     $(this).toggleClass('active');
    // });
    $('.js-toggle-checkbox').on('click', function () {
        $('.js-submit-application').toggleClass('btn--disable');
        $(this).toggleClass('active');
    });
    $('#apply_form').on('submit', function (e) {
        if ($('.js-toggle-checkbox').hasClass('active')) {
            return true;
        }
        e.preventDefault();
        $('.js-dataprivacy').addClass('error');
        return false;
    })
}
// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};