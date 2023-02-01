/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Mega Menu Customize
 *
 * @author Wyde
 * @version 1.0.0
 */



/**
 * Initializes Mega Menu setting fields.
 */
function initMegaMenuSettings() {
  document.querySelectorAll('#menu-to-edit .menu-item-settings').forEach(setting => {
    const megaMenuColumns = setting.querySelector('.field-evie-mega-menu-columns');
    if (megaMenuColumns !== null) {
      const megaMenu = setting.querySelector('.menu-item-evie-mega-menu');
      if (megaMenu !== null) {
        megaMenu.addEventListener('change', () => {
          megaMenuColumns.style.display = megaMenu.value === 'menu' ? '' : 'none';
        });
        megaMenuColumns.style.display = megaMenu.value === 'menu' ? '' : 'none';
      }
    }
  });
}
initMegaMenuSettings();
/******/ })()
;