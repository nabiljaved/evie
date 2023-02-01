/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Google Fonts Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Updates font preview.
 *
 * @param {Node}   field Target field.
 * @param {string} font  The font family.
 */
function updatePreview(field, font) {
  if (field !== null) {
    const url = 'https://fonts.googleapis.com/css?family=' + encodeURIComponent(font) + '&subset=' + encodeURIComponent('latin,latin-ext') + '&display=swap';
    let link = field.querySelector('link');
    if (link === null) {
      link = document.createElement('link');
      link.setAttribute('rel', 'stylesheet');
      field.append(link);
    }
    link.setAttribute('href', url);
    field.style.fontFamily = `'${font}', sans-serif`;
  }
}

/**
 * Initializes Google Fonts dropdown.
 *
 * @param {Node} content Content to initialize.
 */
function initGoogleFontsDropdown(content) {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-google-fonts fieldset').forEach(fieldset => {
    const label = fieldset.querySelector('.flext-google-fonts-preview');
    if (label !== null) {
      let value = '';
      fieldset.querySelectorAll('select').forEach(field => {
        value = field.value;
        field.addEventListener('change', () => {
          value = field.value;
          updatePreview(label, value);
        });
      });
      updatePreview(label, value);
    }
  });
}
flextension.on('fieldsListItemAdded', (context, content) => {
  if (!content) {
    return;
  }
  initGoogleFontsDropdown(content);
});
flextension.on('ready', () => {
  initGoogleFontsDropdown();
});
/******/ })()
;