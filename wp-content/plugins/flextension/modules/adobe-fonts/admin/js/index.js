/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Adobe Fonts Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Initializes the Projects list.
 */
function initProjectsList() {
  document.querySelectorAll('#flext-field-flext-adobe-fonts-project select').forEach(field => {
    field.addEventListener('change', () => {
      const form = field.closest('form');
      if (form !== null) {
        const submitButton = form.querySelector('[name="submit"]');
        if (submitButton !== null) {
          submitButton.remove();
        }
        form.submit();
      }
    });
  });
}

/**
 * Updates font preview.
 *
 * @param {Node}   field Target field.
 * @param {string} font  The font family.
 */
function updatePreview(field, font) {
  if (field !== null) {
    field.style.fontFamily = `'${font}', sans-serif`;
  }
}

/**
 * Initializes Adobe Fonts dropdown.
 *
 * @param {Node} content Content to initialize.
 */
function initAdobeFontsDropdown(content) {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-adobe-fonts fieldset').forEach(fieldset => {
    const label = fieldset.querySelector('.flext-adobe-fonts-preview');
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
  initAdobeFontsDropdown(content);
});
initAdobeFontsDropdown();
initProjectsList();
/******/ })()
;