/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Customize Controls
 *
 * @author  Wyde
 * @version 1.0.0
 */



(function () {
  wp.customize.bind('ready', () => {
    /**
     * Cursor Section
     */
    // Only show the Hide Default Cursor control when the custom "Cursor" option is selected.
    wp.customize.control('cursor', control => {
      control.setting.bind(value => {
        if (value) {
          wp.customize.control('cursor_hide_default').activate();
        } else {
          wp.customize.control('cursor_hide_default').deactivate();
        }
      });
    });
  });
})();
/******/ })()
;