require('./bootstrap');

import Vue from 'vue';

import toCurrency from './filters/to-currency';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


const files = require.context('./', true, /\.vue$/i);
files.keys().map((key) => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Load Filters
 */
Vue.filter('toCurrency', toCurrency);

if (window.TrackJS) {
  Vue.config.errorHandler = (err, vm, info) => {
    // Log properties passed to the component if there are any
    if (vm.$options.propsData) {
      console.log('Props passed to component', vm.$options.propsData);
    }

    // Emit component name and also the lifecycle hook the error occurred in if present
    var infoMessage = `Error in component: <${vm.$options.name} />\n`;
    if (info) {
      infoMessage += `Error occurred during lifecycle hook: ${info}\n`;
    }

    // This puts the additional error information in the Telemetry Timeline
    console.log(infoMessage);

    // Track the native JS error
    window.TrackJS && TrackJS.track(err);
  };
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



window.onload = function () {
  if (document.getElementById('app') !== null) {
    const app = new Vue({ el: '#app' });
  }
};
