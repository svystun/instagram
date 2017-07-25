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
            statusText: '',
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
            dataArr: [],
            images: []
        },
        mounted: function () {
            var $this = this;
            $('#statusTextBox').show();
            $('.thumb').each(function (index, value) {
                $this.dataArr.push({
                    id: $(this).attr('id'),
                    url: $(this).children('.image-url').prop('src'),
                    txt: $.trim($(this).children('.caption').children('.location-name').html()),
                    insta_id: $(this).children().children('.thumb-id').html()
                });
            });
            // Set markers
            this.recursion();
        },
        methods: {
            toggleInfoWindow: function(marker, idx) {

                this.infoWindowPos = marker.position;
                this.infoContent = marker.infoText;

                // Check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen;
                } else {
                    // If different marker set infowindow to open and reset current marker index
                    this.infoWinOpen = true;
                    this.currentMidx = idx;
                }

                if (this.infoWinOpen) {
                    // Change content of infowindow
                    var button = '<button type="button" class="label label-danger" onclick="app.__vue__.storePost(\''+ marker.id +'\', this)">SAVE</button>';
                    axios.get('/exists?insta_id=' + marker.insta_id).then(function(responce) {
                        if (responce.data.status == 'success') {
                            $('#' + marker.id + 'info').html(button);
                        }
                        if (responce.data.status == 'error') {
                            //
                        }
                    }).catch(function(error) {
                        console.log(error);
                    });
                }
            },
            recursion: function() {
                var $this = this;
                if ($this.dataArr.length == 0) return;
                google_geocoding.geocode($this.dataArr[0].txt, function(err, location) {
                    if( err ) {
                        console.log('Error: ' + err);
                        $this.dataArr.splice(0, 1);
                        $this.recursion();
                    } else if( !location ) {
                        console.log('No result.');
                        $this.dataArr.splice(0, 1);
                        $this.recursion();
                    } else {
                        // Create marker
                        $this.markers.push({
                            position: { lat: location.lat, lng: location.lng },
                            infoText: $this.thumbnail($this.dataArr[0]),
                            text: $this.dataArr[0].txt,
                            id: $this.dataArr[0].id,
                            insta_id: $this.dataArr[0].insta_id
                        });
                        if ($this.dataArr.length == 1) {
                            $this.center = {
                                lat: location.lat,
                                lng: location.lng
                            }
                        }
                        $this.dataArr.splice(0, 1);
                        $this.recursion();
                    }
                });
            },
            thumbnail: function (data) {
                return '<img src="' + data.url + '" height="60" width="60" /></br>' +
                        '<div style="text-align: center" id="' + data.id + 'info"></div>';
            }
        }
    });
});