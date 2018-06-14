
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
var _ = require('lodash');
var debounce = require('debounce');

/**
 * Air DatePicker
 */
require("air-datepicker/dist/js/datepicker.js");
require("air-datepicker/dist/js/i18n/datepicker.en.js");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import 'vue2-dropzone/dist/vue2Dropzone.css';
Vue.component('autocomplete', require('./components/admin/Autocomplete.vue'));
Vue.component('stocklist-component', require('./components/admin/StockList.vue'));
Vue.component('vehicle-component', require('./components/admin/Vehicle.vue'));
Vue.component('pageDetails-component', require('./components/admin/PageDetails.vue'));
Vue.component('tabLinks-component', require('./components/admin/TabLinks.vue'));
Vue.component('vehicle-details', require('./components/admin/VehicleDetails.vue'));
Vue.component('vehicle-specification', require('./components/admin/VehicleSpecifications.vue'));
Vue.component('vehicle-images', require('./components/admin/ImageVideos.vue'));
Vue.component('vehicle-history', require('./components/admin/ConditionHistory.vue'));
Vue.component('vehicle-features', require('./components/admin/ConditionFeatures.vue'));
Vue.component('vehicle-visibility', require('./components/admin/VehicleVisibility.vue'));
Vue.component('admin-permissions', require('./components/admin/Permissions.vue'));
Vue.component('settings-models', require('./components/admin/SettingsModels.vue'));
Vue.component('vueDropzone', require('vue2-dropzone'));
Vue.component('draggable', require('vuedraggable'));


Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

import Toasted from 'vue-toasted';

Vue.use(Toasted,{
    position: 'bottom-right',
    iconPack : 'fontawesome',
    type: 'info',
        duration : 2000,
    action : {
        icon : 'times',
        onClick : (e, toastObject) => {
            toastObject.goAway(0);
        }
    }
})

/*
 * Global Vue Filter
 */

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('uppercase', function (value) {
    if (!value) return '';
    return value.toUpperCase()
})

const app = new Vue({
    el: '#app',
    updated: function () {
        this.$nextTick(function () {
            console.log("Updated Rendered");
        })
    },
    data: {
        loading: true
    }
});

