/**
 *    VUE.JS  PLUGINS
 *
 *  This javascript-file is used to load and configurate all the small plugins for Vue.
 */

import Vue from 'vue';

import BootstrapVue from 'bootstrap-vue';
import VueMoment from 'vue-moment';
import contenteditableDirective from 'vue-contenteditable-directive';
import VCalendar from 'v-calendar';


// Loading the bootstrap-vue library into Vue. This gives some components that are typical for Bootstrap-layouts.
Vue.use(BootstrapVue);


// Loading the contenteditableDirective into Vue to make it more easy to work with HTML5-style editable div-elements.
Vue.use(contenteditableDirective);


// Loading the vue-moment library into Vue. This adds some convenient filters.
Vue.use(VueMoment, {
    moment:window.moment
});


// VCalendar is used for all the small calendars and date-pickers in the Dashboard-app.
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