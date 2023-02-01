/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/**
 * Resets fields in the form.
 *
 * @param {HTMLFormElement} form The form to reset.
 */
function resetFormFields(form) {
  // Reset the fields after submitting the form.
  form.querySelectorAll('.flext-field').forEach(field => {
    field.querySelectorAll('input').forEach(input => {
      if (input !== null) {
        input.value = '';
      }
    });
    if (field.classList.contains('flext-field-type-image')) {
      const image = field.querySelector('.image-wrapper img');
      if (image !== null) {
        image.style.display = 'none';
        image.removeAttribute('src');
      }
      const addButton = field.querySelector('.add-image-button');
      if (addButton !== null) {
        addButton.style.display = '';
      }
      const removeButton = field.querySelector('.remove-image-button');
      if (removeButton !== null) {
        removeButton.style.display = 'none';
      }
    } else if (field.classList.contains('flext-field-type-color')) {
      const colorPreview = field.querySelector('.wp-color-result');
      if (colorPreview !== null) {
        colorPreview.style.setProperty('background-color', null);
      }
    }
  });
}

/**
 * Initializes the Product Attribute Editor.
 */
function initProductAttributeEditor() {
  const submitButton = document.querySelector('#submit');
  if (submitButton === null) {
    return;
  }
  submitButton.addEventListener('click', () => {
    const form = submitButton.closest('form');
    if (form !== null) {
      if (typeof window.validateForm === 'function' && !window.validateForm(form)) {
        return false;
      }
      setTimeout(() => {
        const nameInput = form.querySelector('#tag-name');
        if (nameInput !== null && nameInput.value === '') {
          resetFormFields(form);
        }
      }, 1500);
    }
  });
}
initProductAttributeEditor();
/******/ })()
;