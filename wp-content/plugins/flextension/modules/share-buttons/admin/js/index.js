/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Share Buttons Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Initializes the Ordered Items.
 */
function initOrderedItems() {
  const container = document.querySelector('.flext-share-buttons-order');
  if (container !== null) {
    const template = container.querySelector('template');
    if (template === null) {
      return;
    }
    const list = container.querySelector('ul');
    const toggleItem = (label, value, checked) => {
      if (checked) {
        const content = template.content.cloneNode(true);
        const item = document.createElement('li');
        for (let i = 0; i < content.children.length; i++) {
          item.append(content.children[i]);
        }
        ['[id]', '[name]', '[for]'].forEach(selector => {
          item.querySelectorAll(selector).forEach(el => {
            const attribute = selector.replace(/[\[\]]/g, '');
            el.setAttribute(attribute, el.getAttribute(attribute).replace(/\{\{index\}\}|\-\-index/g, list.children.length));
          });
        });
        const contentLabel = item.querySelector('label');
        if (contentLabel !== null) {
          contentLabel.innerHTML = label;
        }
        const contentInput = item.querySelector('input');
        if (contentInput !== null) {
          contentInput.value = value;
        }
        list.append(item);
      } else {
        const input = list.querySelector(`input[value="${value}"]`);
        if (input !== null) {
          const item = input.closest('li');
          if (item !== null) {
            item.remove();
          }
        }
      }
    };
    document.querySelectorAll('.flext-share-buttons-active input[type="checkbox"]').forEach(checkbox => {
      const label = checkbox.parentElement.querySelector('label');
      checkbox.addEventListener('change', () => {
        toggleItem(label.innerHTML, checkbox.value, checkbox.checked);
      });
    });
  }
}
initOrderedItems();
/******/ })()
;