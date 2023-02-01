/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/**
 * Product Variation Swatches
 *
 * @author  Wyde
 * @version 1.0.0
 */

const {
  evieApp,
  jQuery: $
} = window;

/**
 * Clears all selected options.
 *
 * @param {HTMLCollection} options Options to clear.
 */
function clearOptions(options) {
  options.forEach(option => {
    option.classList.remove('wc-option-selected');
  });
}

/**
 * Initialize product variation swatches.
 *
 * @param {HTMLFormElement} form The product variations form.
 */
function initVariationSwatches(form) {
  if (form.classList.contains('is-variation-initialized')) {
    return;
  }
  form.classList.add('is-variation-initialized');
  form.querySelectorAll('.variations .product-attribute-placeholder').forEach(select => {
    const options = select.parentElement.querySelectorAll('.wc-variation-swatches li span');
    options.forEach(option => {
      option.addEventListener('click', () => {
        if (!option.classList.contains('wc-option-selected')) {
          clearOptions(options);
          option.classList.add('wc-option-selected');
          $(select).val(option.dataset.value).trigger('change');
        }
      });
    });
  });
  form.querySelectorAll('.reset_variations').forEach(link => {
    link.addEventListener('click', () => {
      clearOptions(form.querySelectorAll('.wc-variation-swatches li span'));
    });
  });
}
evieApp.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  document.querySelectorAll('.variations_form').forEach(form => {
    $(form).on('check_variations', () => {
      initVariationSwatches(form);
    });
  });
});
/******/ })()
;