/**
 *   CONFIG/BOOTSTRAP.JS
 *
 *   In this file, some dependencies and (global) javascript libraries will be loaded, (like, JQuery, Bootstrap, etc.)
 */

window._ = require('lodash');
window.Popper = require('popper.js').default;





/**
 * JQuery & Bootstrap
 *
 * Mainly to support some of the styling features from third parties. It needs to be registered globally, because
 * most of these scripts aren't made with Laravel-mix (WebPack) in mind.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}





/**
 *  Axios
 *
 *  This library will be loaded globally for convenience. We will also set the default headers of the axios-plugin
 *  so that it will be able to communicate with all the Laravel-Passport protected API-endpoints.
 */

import { csrfToken } from "../utils/tokens";

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;





/**
 *  Moment.js
 *
 *  This library is used to do all the formatting of date- and time at the client side. It is also added to the
 *  'window'-object for convenience.
 */

window.moment = require('moment');
require('moment/locale/nl');