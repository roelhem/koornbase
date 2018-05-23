
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Including and configuring BootstrapVue

import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);


// Including and configuring the vue-moment and moment.

const moment = require('moment');
require('moment/locale/nl');

Vue.use(require('vue-moment'), {
    moment
});

// Including and configuring the v-calendar plugin

import VCalendar from 'v-calendar';

Vue.use(VCalendar, {
    firstDayOfWeek: 2,
    locale: 'nl-NL',
    formats: {
        title:'MMMM YYYY',
        weekdays:'WW',
        navMonths:'MMM',
        input:['DD-MM-YYYY','L','YYYY-MM-DD', 'YYYY/MM/DD'],
        dayPopover:'DD-MM-YYYY',
        data:['L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
    }
});

// Including and configuring the vue-google-maps plugin

import * as VueGoogleMaps from 'vue2-google-maps';


Vue.use(VueGoogleMaps, {
    load: {
        key:'AIzaSyCqKKtjBf4nrCMcyh2Pnnia7iyvHlK2JLo',
    }
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('data-display', require('./components/displays/data-display'));

Vue.component('crud-form', require('./components/forms/crud-form.vue'));

Vue.component('card-user-small', require('./components/cards/card-user-small.vue'));
Vue.component('card-calendar', require('./components/cards/card-calendar.vue'));

Vue.component('full-calendar', require('./components/FullCalendar'));

Vue.component('person-page', require('./components/person/page'));
Vue.component('person-form', require('./components/person/form/form'));
Vue.component('base-avatar', require('./components/BaseAvatar'));

Vue.component('the-page-home', require('./components/ThePageHome'));
Vue.component('the-page-person-search', require('./components/ThePagePersonSearch'));

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app'
});
