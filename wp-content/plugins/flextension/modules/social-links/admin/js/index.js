/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Social Links Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Initializes the Ordered Items.
 */
function initOrderedItems() {
  const container = document.querySelector('.flext-social-links-order');
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
    document.querySelectorAll('.flext-social-links-active input[type="checkbox"]').forEach(checkbox => {
      const label = checkbox.parentElement.querySelector('label');
      checkbox.addEventListener('change', () => {
        toggleItem(label.innerHTML, checkbox.value, checkbox.checked);
      });
    });
  }
}

/**
 * @param {Node}   el   The target element.
 * @param {Object} data A link data.
 */
function updateUrl(el, data) {
  if (el !== null) {
    const format = el.dataset.format || el.innerText;
    let url = format;
    Object.keys(data).forEach(key => {
      url = url.replace('{' + key + '}', data[key]);
    });
    let link = el.querySelector('a');
    if (link === null) {
      el.innerText = '';
      link = document.createElement('a');
      link.setAttribute('target', '_blank');
      el.append(link);
    }
    link.setAttribute('href', url);
    link.innerText = url;
  }
}

/**
 * Initializes Social Links.
 */
function initSocialLinks() {
  document.querySelectorAll('.flext-social-links fieldset').forEach(fieldset => {
    const el = fieldset.querySelector('.flext-social-link');
    if (el !== null) {
      const data = [];
      fieldset.querySelectorAll('input, select').forEach(field => {
        const matches = /\[(\w+)\]$/g.exec(field.getAttribute('name'));
        if (matches && matches[1]) {
          const key = matches[1];
          data[key] = field.value;
          const eventType = field.tagName === 'INPUT' ? 'keyup' : 'change';
          field.addEventListener(eventType, () => {
            data[key] = field.value;
            updateUrl(el, data);
          });
        }
      });
      updateUrl(el, data);
    }
  });
}
initOrderedItems();
initSocialLinks();
/******/ })()
;