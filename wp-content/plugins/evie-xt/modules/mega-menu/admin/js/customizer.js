/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Mega Menu Customize
 *
 * @author Wyde
 * @version 1.0.0
 */



wp.customize.control.bind('add', control => {
  if (control.extended(wp.customize.Menus.MenuItemControl)) {
    control.deferred.embedded.done(() => {
      extendControl(control);
    });
  }
});

/**
 * Extends the control with Mega Menu field.
 *
 * @param {wp.customize.Menus.MenuItemControl} control
 */
function extendControl(control) {
  // Set the initial UI state.
  updateControlFields(control);

  // Update the UI state when the setting changes programmatically.
  control.setting.bind(() => {
    updateControlFields(control);
  });

  // Update the setting when the Mega Menu dropdown is changed.
  control.container.find('.menu-item-evie-mega-menu').on('change', function () {
    control.setting.set(Object.assign({}, _.clone(control.setting()), {
      evie_mega_menu: this.value
    }));
  });

  // Update the setting when the Columns dropdown is changed.
  control.container.find('.menu-item-evie-mega-menu-columns').on('change', function () {
    control.setting.set(Object.assign({}, _.clone(control.setting()), {
      evie_mega_menu_columns: this.value
    }));
  });
}

/**
 * Applies the control's setting value to the control's fields.
 *
 * @param {wp.customize.Menus.MenuItemControl} control
 */
function updateControlFields(control) {
  const settings = control.setting();
  control.container.find('.menu-item-evie-mega-menu').val(settings.evie_mega_menu || '');
  if (settings.evie_mega_menu === 'menu') {
    control.container.find('.menu-item-evie-mega-menu-columns').val(settings.evie_mega_menu_columns || 2);
  }
  control.container.find('.field-evie-mega-menu-columns').css('display', settings.evie_mega_menu === 'menu' ? '' : 'none');
}
wp.customize.bind('ready', () => {
  // Toggles navigation class name based on the current navigation type.
  wp.customize.control('nav_type', control => {
    let navType = control.setting();
    if (!navType) {
      navType = 'top';
    }
    document.body.classList.add('evie-has-' + navType + '-menu');
    control.setting.bind(value => {
      if (!value) {
        document.body.classList.remove('evie-has-full-menu');
        document.body.classList.add('evie-has-top-menu');
      } else {
        document.body.classList.remove('evie-has-top-menu');
        document.body.classList.add('evie-has-full-menu');
      }
    });
  });
});
/******/ })()
;