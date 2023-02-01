/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};


const {
  evieApp
} = window;

/**
 * Initializes the Contact Form after the page is updated.
 */
evieApp.on('singlePage.afterUpdatePage', (context, content) => {
  if (!content) {
    return;
  }
  // Contact Form 7 forms.
  content.querySelectorAll('.wpcf7-form').forEach(form => {
    const action = form.getAttribute('action');
    if (action) {
      // Replace an action URL (Rest URL) with the current URL.
      const hash = action.indexOf('#') ? action.substring(action.indexOf('#')).replace(/[^\w#_-]+/g, '') : '';
      form.setAttribute('action', window.location.href + hash);
    }
    if (typeof window.wpcf7 !== 'undefined') {
      window.wpcf7.init(form);
      if (window.wpcf7.cached) {
        window.wpcf7.reset(form);
      }
    }
  });
});
/******/ })()
;