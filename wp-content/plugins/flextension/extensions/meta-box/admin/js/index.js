/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Meta Box
 *
 * Initializes the meta box fields.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension,
  flextensionMetaBoxDependencies
} = window;
let template = null;
const templateDependencies = [];
const onTemplateInit = () => {
  flextension.emit('metabox.templateInit');
};
const onTemplateChange = () => {
  flextension.emit('metabox.templateChange');
};
function init() {
  initDependencies();
  initFields();
  initTemplateSelector();
}
function initDependencies() {
  if (typeof flextensionMetaBoxDependencies !== 'undefined' && Array.isArray(flextensionMetaBoxDependencies)) {
    flextensionMetaBoxDependencies.forEach(dependency => {
      templateDependencies.push({
        selector: dependency.selector,
        templates: dependency.templates
      });
    });
  }
}
function initFields() {
  document.querySelectorAll('.postbox .flext-field').forEach(el => {
    const dependencies = el.dataset.dependencies && JSON.parse(el.dataset.dependencies);
    if (dependencies) {
      dependencies.forEach(dependency => {
        if (typeof dependency === 'object' && Array.isArray(dependency.templates)) {
          templateDependencies.push({
            selector: el,
            templates: dependency.templates
          });
        }
      });
    }
  });
}
function initTemplateSelector() {
  if (document.querySelector('#editor') !== null) {
    const {
      select,
      subscribe
    } = wp.data;
    subscribe(() => {
      const newTemplate = select('core/editor').getEditedPostAttribute('template');
      if (newTemplate !== undefined && newTemplate !== template) {
        let eventTrigger = onTemplateChange;
        if (template === null) {
          eventTrigger = onTemplateInit;
        }
        template = newTemplate;
        checkTemplateDependencies();
        if (typeof eventTrigger === 'function') {
          eventTrigger();
        }
      }
    });
  } else {
    checkTemplateDependencies();
  }
}
function checkTemplateDependencies() {
  templateDependencies.forEach(dependency => {
    if (dependency.templates) {
      document.querySelectorAll(dependency.selector).forEach(el => {
        el.hidden = !dependency.templates.includes(template);
      });
    }
  });
}
init();
/******/ })()
;