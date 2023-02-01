/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Tabs
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Tabs element.
 *
 * @param {Element}  element  Target element.
 * @param {Object}   options  Options for the function.
 * @param {Function} callback Callback function.
 */
function FlextensionTabs(element, options, callback) {
  if (!element) {
    return;
  }
  const defaults = {
    selectedIndex: 0,
    changed() {}
  };
  const items = element.querySelectorAll('.flext-tabs-nav a');
  const tabs = element.querySelectorAll('.flext-tab');
  if (items.length === 0 || tabs.length === 0) {
    return;
  }
  const settings = Object.assign(defaults, options || {});
  let selectedIndex = settings.selectedIndex;
  this.select = idx => {
    if (idx >= 0) {
      selectedIndex = idx;
    }
    if (selectedIndex < 0 || selectedIndex >= items.length) {
      selectedIndex = 0;
    }
    items.forEach((item, index) => {
      if (selectedIndex !== index) {
        item.classList.remove('is-active');
      } else {
        item.classList.add('is-active');
      }
    });
    tabs.forEach((tab, index) => {
      if (selectedIndex !== index) {
        tab.classList.remove('is-active');
      } else {
        tab.classList.add('is-active');
      }
    });
    if (typeof settings.changed === 'function') {
      settings.changed();
    }
  };
  items.forEach((item, index) => {
    item.addEventListener('click', event => {
      event.preventDefault();
      this.select(index);
    });
  });
  this.select(selectedIndex);
  if (typeof callback === 'function') {
    callback();
  }
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-tabs').forEach(el => {
    new FlextensionTabs(el);
  });
});
/******/ })()
;