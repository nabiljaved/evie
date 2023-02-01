/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Author Widget Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
flextension.authorWidgetImageSizeChange = target => {
  if (typeof target === 'string') {
    target = document.querySelector(target);
  }
  if (target !== null) {
    const tagsField = target.parentElement.parentElement.querySelector('.flext-author-widget-image-size-field');
    if (tagsField !== null) {
      tagsField.style.display = target.checked ? '' : 'none';
    }
  }
};
/******/ })()
;