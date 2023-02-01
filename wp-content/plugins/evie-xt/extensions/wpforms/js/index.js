/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};


const {
  evieApp
} = window;

/**
 * Executes when the page is updated.
 */
evieApp.on('singlePage.afterUpdatePage', (context, content) => {
  if (!content) {
    return;
  }
  // WPForms plugin.
  if (content.querySelector('.wpforms-form') !== null && typeof window.wpforms !== 'undefined') {
    window.wpforms.init();
  }
});
/******/ })()
;