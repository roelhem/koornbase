

/**
 * First, we will import and configure all the packages that are needed to display the bootstrap theme properly.
 *
 * This will enable the following packages:
 *  - `lodash`
 *  - `popper.js`
 *  - `jquery`
 *  - `bootstrap`
 */

window._ = require('lodash');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}