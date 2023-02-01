/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Lazyload
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
document.addEventListener('lazybeforeunveil', e => {
  // Add simple support for background images:
  const bg = e.target.getAttribute('data-bg');
  if (bg) {
    e.target.style.backgroundImage = 'url(' + bg + ')';
  }
});
flextension.lazyLoad = Object.assign({
  update: () => {
    if (typeof window.lazySizes !== 'undefined' && typeof window.lazySizes.autoSizer !== 'undefined') {
      window.lazySizes.autoSizer.checkElems();
    }
  }
}, flextension.lazyLoad || {});
/******/ })()
;