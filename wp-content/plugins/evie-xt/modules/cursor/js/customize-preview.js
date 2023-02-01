/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Cursor Customize Preview
 *
 * @author  Wyde
 * @version 1.0.0
 */



wp.customize.bind('preview-ready', () => {
  /**
   * Cursor Section
   */
  // Cursor -> Custom Cursor
  wp.customize('cursor', value => {
    value.bind(to => {
      const cursor = document.getElementById('evie-cursor');
      if (cursor !== null) {
        if (to) {
          cursor.classList.remove('is-style-none', 'is-style-dot', 'is-style-circle');
          cursor.classList.add('is-style-' + to);
          document.body.classList.add('has-custom-cursor');
        } else {
          cursor.classList.remove('is-style-dot', 'is-style-circle');
          cursor.classList.add('is-style-none');
          document.body.classList.remove('has-custom-cursor', 'has-no-cursor');
        }
      }
    });
  });

  // Cursor -> Hide Default Cursor
  wp.customize('cursor_hide_default', value => {
    value.bind(to => {
      if (to) {
        document.body.classList.add('has-no-cursor');
        evieApp.cursor.settings.duration = 0.15;
      } else {
        document.body.classList.remove('has-no-cursor');
        evieApp.cursor.settings.duration = 0.25;
      }
    });
  });
});
/******/ })()
;