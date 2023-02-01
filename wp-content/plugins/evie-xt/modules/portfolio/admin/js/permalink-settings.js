/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Portfolio Permalink Settings
 *
 * @author  Wyde
 * @version 1.0.0
 */



function initPermalinkSettings() {
  document.querySelectorAll('.evie-project-permalink-settings').forEach(container => {
    const permalinkField = container.querySelector('#evie-project-permalink-structure');
    if (permalinkField !== null) {
      const options = container.querySelectorAll('input[type="radio"]');
      if (options.length > 0) {
        const defaultStructure = container.querySelector('.default-example');
        const nonDefaultStructure = container.querySelector('.non-default-example');
        options.forEach((option, index) => {
          if (index < options.length - 1) {
            option.addEventListener('change', () => {
              permalinkField.value = option.value;
            });
          }
        });
        const onOptionChange = value => {
          if (value) {
            if (defaultStructure !== null) {
              defaultStructure.style.display = 'none';
            }
            if (nonDefaultStructure !== null) {
              nonDefaultStructure.style.display = 'block';
            }
            options.forEach(input => {
              input.removeAttribute('disabled');
            });
          } else {
            if (defaultStructure !== null) {
              defaultStructure.style.display = 'block';
            }
            if (nonDefaultStructure !== null) {
              nonDefaultStructure.style.display = 'none';
            }
            options[0].checked = true;
            options.forEach(input => {
              input.setAttribute('disabled', true);
            });
          }
        };
        document.querySelectorAll('.permalink-structure input').forEach(option => {
          option.addEventListener('change', () => {
            onOptionChange(option.value);
          });
          if (option.checked) {
            onOptionChange(option.value);
          }
        });
        permalinkField.addEventListener('focus', () => {
          options[options.length - 1].checked = true;
        });
      }
    }
  });
}
initPermalinkSettings();
/******/ })()
;