/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import Vue from 'vue';


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
/*
import * as VueGoogleMaps from 'vue2-google-maps';


Vue.use(VueGoogleMaps, {
    load: {
        key:'AIzaSyCqKKtjBf4nrCMcyh2Pnnia7iyvHlK2JLo',
    }
});*/


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import router from './router';
import store from  './store';



store.dispatch('loadCurrentUser');


Vue.component('the-layout', require('./components/TheLayout'));
Vue.component('the-footer', require('./components/TheFooter'));



import { ApolloClient } from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import VueApollo from 'vue-apollo';

let csrf = '';
let metas = document.getElementsByTagName('meta');
console.log(metas);
for(var i=0; i<metas.length; i++) {
    if(metas[i].getAttribute("name") === 'csrf-token') {
        csrf = metas[i].getAttribute('content');
    }
}

const httpLink = new HttpLink({
    uri: '/graphql',
    credentials: 'include',
    headers: {
        "X-CSRF-TOKEN":csrf,
        "X-Requested-With":"XMLHttpRequest"
    }
});

const apolloClient = new ApolloClient({
    link: httpLink,
    cache: new InMemoryCache(),
    connectToDevTools: true
});

Vue.use(VueApollo);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
});



const app = new Vue({
    router,
    store,
    provide: apolloProvider.provide()
}).$mount('#app');