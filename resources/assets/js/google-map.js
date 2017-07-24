// Add google api key
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyAIqI5zwpzVd-6EglBMk_3a1nWLWIOhRfM'
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const map = new Vue({
        el: '#google-map',
        data: {
            center: {
                lat: 0,
                lng: 0
            },
            infoContent: '',
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            infoWinOpen: false,
            currentMidx: null,
            //optional: offset infowindow so it visually sits nicely on top of our marker
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            markers: [],
            desc: [],
            images: []
        },
        mounted: function () {
            var $this = this;
            $('.location-name').each(function (index, value) {
                $this.desc.push($.trim($(this).text()));
            });

            $('.image-url').each(function (index, value) {
                $this.images.push($(this).prop('src'));
            });

            this.recursion();
        },
        methods: {
            toggleInfoWindow: function(marker, idx) {

                this.infoWindowPos = marker.position;
                this.infoContent = marker.infoText;

                //check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen;
                }
                //if different marker set infowindow to open and reset current marker index
                else {
                    this.infoWinOpen = true;
                    this.currentMidx = idx;

                }
            },

            recursion: function() {
                var $this = this;
                if ($this.desc.length == 0) return;
                google_geocoding.geocode($this.desc[0], function(err, location) {
                    if( err ) {
                        console.log('Error: ' + err);
                        $this.desc.splice(0, 1);
                        $this.recursion();
                    } else if( !location ) {
                        console.log('No result.');
                        $this.desc.splice(0, 1);
                        $this.recursion();
                    } else {
                        $this.markers.push({
                            position: {
                                lat: location.lat,
                                lng: location.lng
                            },
                            infoText: 'Latitude: ' + location.lat + ' ; Longitude: ' + location.lng
                        });

                        if ($this.desc.length == 1) {
                            $this.center = {
                                lat: location.lat,
                                lng: location.lng
                            }
                        }

                        $this.desc.splice(0, 1);
                        $this.recursion();
                    }
                });
            }

        }
    });
});