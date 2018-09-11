/**
 *   THE KOORNBASE DASHBOARD APPLICATION
 *
 *   This main.js file is the entry point of the single-page application named "KoornBase Dashboard." The goal of
 *   this application is to provide an easy way to access all of the most important features of the KoornBase Backend.
 *
 *   The application is mainly build with  Vue.js   and uses    Apollo     with the     GraphQL-API     to communicate
 *   with the Backend.
 */

// Use the bootstrap-file to load some important javascript-libraries.
require('./config/bootstrap');


/**
 *    Vue.js
 *
 *   This is the most important javascript-library/framework for this application. Most of the code written by me has
 *   been build on this framework.
 */


import Vue from 'vue';

// Load the plugins.js file to install and configure the smaller plugins.
require('./config/plugins');




/**
 *    CREATING AND STARTING THE VUE-APP
 *
 *  We'll start the root Vue-component and use those as the backbone of this application.
 */

// The most important Vue plug-ins.
import router from './config/router/index';
import provider from './config/apollo';
// The leading component.
import App from './App';

// The root-component.
new Vue({
    el:'#app',
    router,
    provide: provider.provide(),
    template: '<App/>',
    components: { App }
});