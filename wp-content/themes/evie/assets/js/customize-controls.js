/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Customize Controls
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Returns a size value with unit.
 *
 * @param {string} size Raw size.
 *
 * @return {string} A size value with unit.
 */
function validateSize(size) {
  const results = size ? size.match(/^(\d+|\d*\.\d+)(\w+)?$/) : null;
  if (results && results.length > 1 && results[1]) {
    const unit = results[2] ? results[2] : 'px';
    return results[1] + unit;
  }
  return '';
}

/**
 * Unit control.
 */
wp.customize.controlConstructor['evie-unit'] = wp.customize.Control.extend({
  ready() {
    const control = this;
    const container = control.container.get(0);
    const values = control.setting() || [];
    const sizeInput = container.querySelector('.evie-customize-unit-values');
    if (sizeInput === null) {
      return;
    }
    const clearButton = container.querySelector('.evie-customize-clear-button button');
    let isDirty = values && values.length > 0;
    const setValue = value => {
      sizeInput.value = value;
      control.setting.set(value);
      isDirty = !!value && value !== ',,';
      if (clearButton !== null) {
        clearButton.disabled = !isDirty;
      }
    };
    if (clearButton !== null) {
      clearButton.disabled = !isDirty;
      clearButton.addEventListener('click', e => {
        e.preventDefault();
        inputControls.forEach(input => {
          input.value = '';
        });
        setValue('');
        return false;
      });
    }
    const inputControls = container.querySelectorAll('.evie-customize-input-control input');
    inputControls.forEach((input, index) => {
      input.addEventListener('change', () => {
        input.value = validateSize(input.value);
        values[index] = input.value;
        setValue(values.join(','));
      });
    });
  }
});

/**
 * HTML control.
 */
wp.customize.controlConstructor['evie-html'] = wp.customize.Control.extend({
  ready() {
    const control = this;
    wp.editor.initialize(control.id, {
      tinymce: {
        wpautop: true,
        setup: editor => {
          editor.on('change', () => {
            window.tinymce.triggerSave();
            control.elements[0].element.trigger('change');
          });
        }
      },
      quicktags: true,
      mediaButtons: false
    });
  }
});

/**
 * Range Control
 */
wp.customize.controlConstructor['evie-range'] = wp.customize.Control.extend({
  ready() {
    const container = this.container.get(0);
    const label = container.querySelector('.evie-range-value');
    if (label !== null) {
      container.querySelectorAll('input[type="range"]').forEach(input => {
        input.addEventListener('input', e => {
          label.innerText = e.target.value;
        });
      });
    }
  }
});
wp.customize.bind('ready', () => {
  /**
   * Navigation Section
   */

  // Only show the Align control when the Top Menu is selected.
  wp.customize.control('nav_type', control => {
    control.setting.bind(value => {
      if (value !== '') {
        wp.customize.control('nav_align').deactivate();
      } else {
        wp.customize.control('nav_align').activate();
      }
    });
  });

  /**
   * Header Section
   */

  let headerOverlayMode = '';
  const toggleHeaderOverlayOptions = () => {
    if (headerOverlayMode === 'color') {
      wp.customize.control('header_bg_overlay_color').activate();
      wp.customize.control('header_bg_overlay_opacity').activate();
      wp.customize.control('header_text_mode').activate();
    } else {
      wp.customize.control('header_bg_overlay_color').deactivate();
      wp.customize.control('header_bg_overlay_opacity').deactivate();
      wp.customize.control('header_text_mode').deactivate();
    }
  };

  // Only show the Background Image controls when the Image option is selected.
  wp.customize.control('header_bg', control => {
    control.setting.bind(value => {
      if (value === 'image') {
        wp.customize.control('header_bg_color').deactivate();
        wp.customize.control('header_image').activate();
        wp.customize.control('header_bg_position').activate();
        wp.customize.control('header_bg_size').activate();
        wp.customize.control('header_bg_attachment').activate();
        wp.customize.control('header_bg_repeat').activate();
        wp.customize.control('header_bg_overlay').activate();
        toggleHeaderOverlayOptions();
      } else {
        wp.customize.control('header_bg_color').activate();
        wp.customize.control('header_text_mode').activate();
        wp.customize.control('header_image').deactivate();
        wp.customize.control('header_bg_position').deactivate();
        wp.customize.control('header_bg_size').deactivate();
        wp.customize.control('header_bg_attachment').deactivate();
        wp.customize.control('header_bg_repeat').deactivate();
        wp.customize.control('header_bg_overlay').deactivate();
        wp.customize.control('header_bg_overlay_color').deactivate();
        wp.customize.control('header_bg_overlay_opacity').deactivate();
      }
    });
  });

  // Only show the Background Overlay controls when the Overlay Color option is selected.
  wp.customize.control('header_bg_overlay', control => {
    headerOverlayMode = control.setting();
    control.setting.bind(value => {
      if (value === 'color') {
        wp.customize.control('header_bg_overlay_color').activate();
        wp.customize.control('header_bg_overlay_opacity').activate();
      } else {
        wp.customize.control('header_bg_overlay_color').deactivate();
        wp.customize.control('header_bg_overlay_opacity').deactivate();
      }
      headerOverlayMode = value;
      toggleHeaderOverlayOptions();
    });
  });

  /**
   * Blog Section
   */

  // Only show the Style and Hover Effect controls when the Grid or Waterfall is selected.
  wp.customize.control('blog_posts_layout', control => {
    control.setting.bind(value => {
      if (value === 'waterfall') {
        wp.customize.control('blog_posts_parallax').activate();
      } else {
        wp.customize.control('blog_posts_parallax').deactivate();
      }
      if (['grid', 'waterfall'].includes(value)) {
        wp.customize.control('blog_posts_style').activate();
        wp.customize.control('blog_posts_hover_effect').activate();
      } else {
        wp.customize.control('blog_posts_style').deactivate();
        wp.customize.control('blog_posts_hover_effect').deactivate();
      }
    });
  });

  // Only show the Style and Hover Effect controls when the Grid or Waterfall is selected.
  wp.customize.control('blog_archive_layout', control => {
    control.setting.bind(value => {
      if (value === 'waterfall') {
        wp.customize.control('blog_archive_parallax').activate();
      } else {
        wp.customize.control('blog_archive_parallax').deactivate();
      }
      if (['grid', 'waterfall'].includes(value)) {
        wp.customize.control('blog_archive_style').activate();
        wp.customize.control('blog_archive_hover_effect').activate();
      } else {
        wp.customize.control('blog_archive_style').deactivate();
        wp.customize.control('blog_archive_hover_effect').deactivate();
      }
    });
  });

  /**
   * Loader Section
   */

  // Only show the Loader Logo controls when the "Logo" option is selected.
  wp.customize.control('loader', control => {
    control.setting.bind(value => {
      if (value) {
        wp.customize.control('loader_overlay').activate();
        wp.customize.control('loader_overlay_opacity').activate();
        if ('logo' === value) {
          wp.customize.control('loader_logo').activate();
        } else {
          wp.customize.control('loader_logo').deactivate();
        }
      } else {
        wp.customize.control('loader_overlay').deactivate();
        wp.customize.control('loader_overlay_opacity').deactivate();
      }
    });
  });

  /**
   * Footer Section
   */

  // Only show the Footer Text controls when the "Custom" mode is selected.
  wp.customize.control('site_info', control => {
    control.setting.bind(value => {
      if ('custom' === value) {
        wp.customize.control('footer_text').activate();
      } else {
        wp.customize.control('footer_text').deactivate();
      }
    });
  });
  const {
    URLSearchParams
  } = window;
  document.querySelectorAll('.link-panel-button').forEach(link => {
    link.addEventListener('click', event => {
      event.preventDefault();
      const urlParams = new URLSearchParams(link.search);
      wp.customize.panel(urlParams.get('autofocus[panel]')).focus();
      return false;
    });
  });

  /**
   * Validation
   */

  // Validate all number fields.
  document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('change', () => {
      const max = parseInt(input.getAttribute('max'));
      const min = parseInt(input.getAttribute('min'));
      if (parseInt(input.value) > max) {
        input.value = max;
      } else if (parseInt(input.value) < min) {
        input.value = min;
      }
    });
  });
});
/******/ })()
;