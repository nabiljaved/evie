/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Categories Widget Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
flextension.widgetCategoriesDisplayChange = target => {
  if (typeof target === 'string') {
    target = document.querySelector(target);
  }
  if (target !== null) {
    const form = target.closest('.form');
    if (form !== null) {
      const termsField = form.querySelector('.flext-widget-categories-terms-field');
      if (termsField !== null) {
        termsField.style.display = target.value === 'all' ? 'none' : '';
      }
    }
  }
};
flextension.widgetCategoriesTaxonomyChange = target => {
  if (typeof target === 'string') {
    target = document.querySelector(target);
  }
  if (target !== null) {
    const form = target.closest('.form');
    if (form !== null) {
      const list = form.querySelector('.flext-widget-categories-terms');
      if (list !== null) {
        list.classList.add('flext-is-loading');
        const taxonomy = target.value || 'category';
        const name = list.parentElement.dataset.name;
        flextension.api.get('/flextension/v1/categories-checklist', {
          taxonomy,
          name
        }).then(content => {
          list.innerHTML = content.rendered;
          list.classList.remove('flext-is-loading');
        });
      }
    }
  }
};
/******/ })()
;