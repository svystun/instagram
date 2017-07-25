
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));

var app = new Vue({
    el: '#app',
    data: {
        loading: false
    },
    methods: {
        storePost: function (id, button) {
            var pref = '#' + id;
            var postFields = {
                insta_id: $(pref + ' .thumb-id').html(),
                insta_url: $(pref + ' .image-url').prop('src'),
                insta_caption: $.trim($(pref + ' .caption-text').html()),
                insta_user: $(pref + ' .user-name').html(),
                insta_name: $.trim($(pref + ' .location-name').html()),
                insta_time: $(pref + ' .time').html(),
                insta_type: $(pref + ' .type').html()
            };
            this.loading = true;
            axios.post('/store', postFields).then(function(responce) {
                app.loading = false;
                if (responce.data.status == 'success') {
                    $(pref + 'thumb').slideUp("slow", function () {
                        $(this).remove();
                    });
                }

                if (responce.data.status == 'error') {
                    alert(responce.data.message);
                }
                // Disable button in InfoWindow (Google Map)
                $(button).prop("disabled",true).css({'color': 'lightgray'})
                    .addClass('label-default')
                    .removeClass('label-danger')
                    .text('SAVED');
            }).catch(function(error) {
                app.loading = false;
                console.log(error);
            });
        },

        deletePost: function (id) {
            this.loading = true;
            axios.post('/delete', {'_method': 'delete', 'id': id}).then(function(responce) {
                app.loading = false;
                if (responce.data.status == 'success') {
                    $('#' + id).slideUp("slow", function () {
                        $(this).remove();
                    });
                }
                if (responce.data.status == 'error') {
                    alert(responce.data.message);
                }
            }).catch(function(error) {
                app.loading = false;
                console.log(error);
            });
        }
    }
});

if ($('#google-map').length > 0){
    require('./google-map');
}