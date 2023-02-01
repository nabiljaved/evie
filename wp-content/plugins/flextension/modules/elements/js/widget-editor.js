/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Widget Editor
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
flextension.postCarouselTypeChange = target => {
  if (typeof target === 'string') {
    target = document.querySelector(target);
  }
  if (target !== null) {
    const tagsField = target.parentElement.parentElement.querySelector('.flext-post-carousel-widget-tags-input-field');
    if (tagsField !== null) {
      const display = target.value === 'tags' ? '' : 'none';
      tagsField.style.display = display;
    }
  }
};
/******/ })()
;