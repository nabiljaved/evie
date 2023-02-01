/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/assets/js/inc/components/toggle.js
/**
 * Toggles the visibility of the element.
 *
 * @param {string|Element|NodeList} target Target element.
 * @param {boolean}                 show   Whether to show the element.
 */
function toggle(target, show) {
  const elements = [];
  if (typeof target === 'string') {
    document.querySelectorAll(target).forEach(el => {
      elements.push(el);
    });
  } else if (typeof target === 'object') {
    elements.push(target);
  }
  elements.forEach(el => {
    if (typeof show === 'undefined') {
      show = el.style.display === 'none';
    }
    el.style.display = show ? '' : 'none';
  });
}
;// CONCATENATED MODULE: ./src/assets/js/customize-preview.js
/**
 * Customize Preview
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */

const {
  evieApp,
  setTimeout: customize_preview_setTimeout
} = window;

// Collect information from customize-controls.js about which panels are opening.
wp.customize.bind('preview-ready', () => {
  wp.customize.selectiveRefresh.bind('partial-content-rendered', placement => {
    if (['blog_posts_layout', 'blog_posts_parallax', 'blog_posts_style', 'blog_posts_hover_effect', 'blog_posts_animation', 'blog_archive_layout', 'blog_archive_parallax', 'blog_archive_style', 'blog_archive_hover_effect', 'blog_archive_animation'].includes(placement.partial.id)) {
      const container = placement.container.length ? placement.container[0] : null;
      evieApp.emit('initPosts', container ? container.parentElement : null);
    } else if (['loader', 'loader_overlay', 'loader_logo'].includes(placement.partial.id)) {
      customize_preview_setTimeout(() => {
        const container = placement.container.length ? placement.container[0] : null;
        evieApp.loader = container;
        evieApp.hideLoader();
      }, 3000);
    } else if (placement.partial.id === 'footer_widgets') {
      const container = placement.container.length ? placement.container[0] : null;
      if (window.flextension) {
        window.flextension.emit('ready', container ? container.parentElement : null);
      }
      evieApp.emit('ready', container ? container.parentElement : null);
    }
  });
  wp.customize.selectiveRefresh.bind('widget-updated', placement => {
    if (placement && placement.widgetId) {
      if (window.flextension) {
        window.flextension.emit('ready', document.getElementById(placement.widgetId));
      }
      evieApp.emit('ready', document.getElementById(placement.widgetId));
    }
  });
});

// Site logo.
wp.customize('custom_logo', value => {
  value.bind(to => {
    if (to) {
      toggle('.custom-logo-link, .light-logo-link', true);
      document.body.classList.add('title-tagline-hidden');
    } else {
      toggle('.custom-logo-link, .light-logo-link', false);
      document.body.classList.remove('title-tagline-hidden');
    }
  });
});

// Site title and description.
wp.customize('blogname', value => {
  value.bind(to => {
    document.querySelectorAll('.site-title .custom-logo-link').forEach(link => {
      link.innerText = to;
    });
  });
});
wp.customize('blogdescription', value => {
  value.bind(to => {
    document.querySelectorAll('.site-description').forEach(text => {
      text.innerText = to;
    });
  });
});
wp.customize('display_header_text', value => {
  value.bind(to => {
    if (to) {
      document.body.classList.remove('title-tagline-hidden');
    } else {
      document.body.classList.add('title-tagline-hidden');
    }
  });
});

/**
 * Colors section.
 */

/**
 * Converts HEX color to RGB variable separated by comma.
 *
 * @param {string} color HEX color string.
 * @return {string} RGB variable separated by comma.
 */
function hexToRGBVar(color) {
  if (!color) {
    return null;
  }
  // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
  const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  const hex = color.replace(shorthandRegex, (m, r, g, b) => {
    return r + r + g + g + b + b;
  });
  const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  const rgb = result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : [0, 0, 0];
  return rgb.join(',');
}
let onSurfaceColor = '';
let onSurfaceColorDark = '';
function updateOnSurfaceColor() {
  if (evieApp.isDarkMode) {
    const container = document.querySelector('body.has-scheme-dark');
    if (container !== null) {
      container.style.setProperty('--evie-color-on-surface-rgb', onSurfaceColorDark);
    }
  } else {
    const container = document.querySelector('body:not(.has-scheme-dark)');
    if (container !== null) {
      container.style.setProperty('--evie-color-on-surface-rgb', onSurfaceColor);
    }
  }
}

// Update On Surface Color when changing dark mode.
evieApp.on('colorSchemeChanged', () => {
  updateOnSurfaceColor();
});

// Colors -> Primary color.
wp.customize('primary_color', value => {
  value.bind(to => {
    document.body.style.setProperty('--evie-color-primary-rgb', hexToRGBVar(to));
  });
});

// Colors -> On Primary color.
wp.customize('on_primary_color', value => {
  value.bind(to => {
    const rgbVar = `${to},${to},${to}`;
    document.body.style.setProperty('--evie-color-on-primary-rgb', rgbVar);
  });
});

// Colors -> Secondary color.
wp.customize('secondary_color', value => {
  value.bind(to => {
    document.body.style.setProperty('--evie-color-secondary-rgb', hexToRGBVar(to));
  });
});

// Colors -> On Secondary color.
wp.customize('on_secondary_color', value => {
  value.bind(to => {
    const rgbVar = `${to},${to},${to}`;
    document.body.style.setProperty('--evie-color-on-secondary-rgb', rgbVar);
  });
});

// Colors -> Text color.
wp.customize('on_surface_color', value => {
  const color = value();
  onSurfaceColor = `${color},${color},${color}`;
  value.bind(to => {
    onSurfaceColor = `${to},${to},${to}`;
    updateOnSurfaceColor();
  });
});

// Colors -> Text color (Dark Mode).
wp.customize('on_surface_dark_color', value => {
  const color = value();
  onSurfaceColorDark = `${color},${color},${color}`;
  value.bind(to => {
    onSurfaceColorDark = `${to},${to},${to}`;
    updateOnSurfaceColor();
  });
});
let isDarkMode = evieApp.isDarkMode;

// Colors -> Color scheme.
wp.customize('color_scheme', value => {
  value.bind(to => {
    let color = to;
    if (!color) {
      color = isDarkMode ? 'dark' : 'light';
    }
    evieApp.changeColorScheme(color);
  });
});

// Colors -> Allows users to switch color schemes.
wp.customize('user_color_support', value => {
  value.bind(to => {
    document.body.classList.toggle('has-user-color-support', to);
    if (to) {
      evieApp.changeColorScheme(isDarkMode ? 'dark' : 'light');
    } else {
      let color = wp.customize('color_scheme').get();
      if (!color) {
        color = isDarkMode ? 'dark' : 'light';
      }
      evieApp.changeColorScheme(color);
    }
  });
});

/**
 * Navigation section
 */
// Navigation -> Type
wp.customize('nav_type', value => {
  value.bind(to => {
    if (!to) {
      to = 'top';
    }
    const header = document.getElementById('site-header');
    if (header !== null) {
      header.style.visibility = 'hidden';
    }
    document.body.classList.remove('top-menu', 'full-menu');
    document.body.classList.add(to + '-menu');
    customize_preview_setTimeout(() => {
      evieApp.initNavMenu();
    }, 3000);
  });
});

// Navigation -> Align
wp.customize('nav_align', value => {
  value.bind(to => {
    if (!to) {
      to = 'left';
    }
    const menu = document.querySelector('#site-header .main-menu');
    if (menu !== null) {
      menu.classList.remove('menu-align-left', 'menu-align-center', 'menu-align-right');
      menu.classList.add('menu-align-' + to);
    }
  });
});

// Navigation -> Sticky Menu
wp.customize('nav_sticky_menu', value => {
  value.bind(to => {
    if (to) {
      document.body.classList.add('is-sticky-menu');
    } else {
      document.body.classList.remove('is-sticky-menu');
    }
  });
});

// Navigation -> Search Button
wp.customize('nav_search_button', value => {
  toggle('#site-header .main-menu .menu-item-search', value());
  value.bind(to => {
    document.querySelectorAll('#site-header .main-menu .menu-item-search').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Navigation -> Login Button
wp.customize('nav_login_button', value => {
  toggle('#site-header .main-menu .menu-item-login', value());
  value.bind(to => {
    document.querySelectorAll('#site-header .main-menu .menu-item-login').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Navigation -> Sidebar Button
wp.customize('nav_sidebar_button', value => {
  toggle('.desktop-menu.full-menu #site-header .main-menu .menu-item-sidebar, .desktop-menu.top-menu #site-header .main-menu .menu-button', value());
  value.bind(to => {
    document.querySelectorAll('.desktop-menu.full-menu #site-header .main-menu .menu-item-sidebar, .desktop-menu.top-menu #site-header .main-menu .menu-button').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Header section
 */

// Header -> Background Color
wp.customize('header_bg_color', value => {
  value.bind(to => {
    document.body.style.setProperty('--evie-color-header', to);
    document.querySelectorAll('#site-content .page-header.has-default-background .header-background').forEach(background => {
      if (to) {
        background.style.backgroundColor = to;
      } else {
        background.style.backgroundColor = null;
      }
    });
  });
});

// Header -> Image Position
wp.customize('header_bg_position', value => {
  value.bind(to => {
    document.querySelectorAll('#site-content .page-header.has-default-background .header-background').forEach(background => {
      if (to) {
        background.style.backgroundPosition = to;
      } else {
        background.style.backgroundPosition = null;
      }
    });
  });
});

// Header -> Image Size
wp.customize('header_bg_size', value => {
  value.bind(to => {
    document.querySelectorAll('#site-content .page-header.has-default-background .header-background').forEach(background => {
      background.classList.remove('has-background-auto', 'has-background-contain');
      if (to) {
        background.classList.add('has-background-' + to);
      }
    });
  });
});

// Header -> Fixed background
wp.customize('header_bg_attachment', value => {
  value.bind(to => {
    document.querySelectorAll('#site-content .page-header.has-default-background .header-background').forEach(background => {
      background.classList.toggle('has-background-parallax', to);
    });
  });
});

// Header -> Repeated background
wp.customize('header_bg_repeat', value => {
  value.bind(to => {
    document.querySelectorAll('#site-content .page-header.has-default-background .header-background').forEach(background => {
      background.classList.toggle('has-background-repeat', to);
    });
  });
});
let currentHeaderTextClass = '';

// Header -> Background Overlay

function headerBackgroundOverlayChanged(value) {
  document.querySelectorAll('#site-content .page-header.has-default-background').forEach(overlay => {
    overlay.classList.toggle('has-gradient-overlay', !value);
  });
}
wp.customize('header_bg_overlay', value => {
  const header = document.querySelector('#site-content .page-header.has-default-background');
  if (header !== null) {
    if (header.classList.contains('has-text-mode-light')) {
      currentHeaderTextClass = 'has-text-mode-light';
    } else if (header.classList.contains('has-text-mode-dark')) {
      currentHeaderTextClass = 'has-text-mode-dark';
    }
    if (currentHeaderTextClass) {
      header.classList.toggle(currentHeaderTextClass, value());
    }
  }
  value.bind(to => {
    headerBackgroundOverlayChanged(to);
    if (header !== null && currentHeaderTextClass) {
      header.classList.toggle(currentHeaderTextClass, to);
    }
  });
});

// Header -> Overlay Color

function headerBackgroundOverlayColorChanged(value) {
  document.querySelectorAll('#site-content .page-header.has-default-background .background-overlay').forEach(overlay => {
    if (value) {
      overlay.style.backgroundColor = value;
    } else {
      overlay.style.backgroundColor = null;
    }
  });
}
wp.customize('header_bg_overlay_color', value => {
  headerBackgroundOverlayColorChanged(value());
  value.bind(to => {
    headerBackgroundOverlayColorChanged(to);
  });
});

// Header -> Overlay Opacity

function headerBackgroundOverlayOpacityChanged(value) {
  document.querySelectorAll('#site-content .page-header.has-default-background .background-overlay').forEach(overlay => {
    if (value) {
      overlay.style.opacity = parseFloat(value / 100).toFixed(2);
    } else {
      overlay.style.opacity = null;
    }
  });
}
wp.customize('header_bg_overlay_opacity', value => {
  headerBackgroundOverlayOpacityChanged(value());
  value.bind(to => {
    headerBackgroundOverlayOpacityChanged(to);
  });
});

// Header -> Text Mode
wp.customize('header_text_mode', value => {
  const textMode = value();
  if (textMode) {
    currentHeaderTextClass = 'has-text-mode-' + textMode;
  }
  value.bind(to => {
    document.querySelectorAll('#site-content .page-header.has-default-background').forEach(header => {
      header.classList.remove('has-text-mode-dark', 'has-text-mode-light');
      if (to) {
        header.classList.add('has-text-mode-' + to);
        currentHeaderTextClass = 'has-text-mode-' + to;
      } else {
        currentHeaderTextClass = '';
      }
    });
  });
});

/**
 * Background Section
 */

// Background -> Background color.
wp.customize('background_color', value => {
  value.bind(to => {
    document.body.style.setProperty('--evie-color-background-rgb', hexToRGBVar(to));
  });
});

/**
 * Loader Section
 */

// Loader -> Background Overlay
let loaderTimer = null;
function showLoader(loader) {
  loader.classList.remove('is-loaded');
  loader.classList.add('is-loading');
}
function hideLoader(loader) {
  loader.classList.remove('is-loading');
  loader.classList.add('is-loaded');
}
function loaderBackgroundOverlayColorChanged(value) {
  const background = document.querySelector('#site-loader .loader-background');
  if (background !== null) {
    if (value) {
      background.style.backgroundColor = value;
    } else {
      background.style.backgroundColor = null;
    }
    showLoader(background.parentElement);
    if (loaderTimer) {
      clearTimeout(loaderTimer);
    }
    loaderTimer = customize_preview_setTimeout(() => {
      hideLoader(background.parentElement);
    }, 3000);
  }
}
wp.customize('loader_overlay', value => {
  loaderBackgroundOverlayColorChanged(value());
  value.bind(to => {
    loaderBackgroundOverlayColorChanged(to);
  });
});
function loaderBackgroundOverlayOpacityChanged(value) {
  const background = document.querySelector('#site-loader .loader-background');
  if (background !== null) {
    if (value) {
      background.style.opacity = parseFloat(value / 100).toFixed(2);
    } else {
      background.style.opacity = null;
    }
    showLoader(background.parentElement);
    if (loaderTimer) {
      clearTimeout(loaderTimer);
    }
    loaderTimer = customize_preview_setTimeout(() => {
      hideLoader(background.parentElement);
    }, 3000);
  }
}
wp.customize('loader_overlay_opacity', value => {
  loaderBackgroundOverlayOpacityChanged(value());
  value.bind(to => {
    loaderBackgroundOverlayOpacityChanged(to);
  });
});

/**
 * Blog -> Posts Page.
 */

/**
 * Toggles author display.
 *
 * @param {string} value Setting value.
 */
function togglePostAuthor(value) {
  switch (value) {
    case 'hide':
      toggle('.main-posts .post .author > a, .main-posts .post .author .fn', false);
      break;
    case 'name':
      toggle('.main-posts .post .author > a', false);
      toggle('.main-posts .post .author .fn', true);
      break;
    case 'avatar':
      toggle('.main-posts .post .author > a', true);
      toggle('.main-posts .post .author .fn', false);
      break;
    default:
      toggle('.main-posts .post .author > a, .main-posts .post .author .fn', true);
      break;
  }
}

// Blog -> Posts Page -> Custom Content.
wp.customize('blog_posts_page_content', value => {
  toggle('.blog .main-content > .entry-content', value());
  value.bind(to => {
    document.querySelectorAll('.blog .main-content > .entry-content').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Posts Page -> Publication date.
wp.customize('blog_posts_date', value => {
  toggle('.blog .main-posts .post .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.blog .main-posts .post .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Posts Page -> Post category.
wp.customize('blog_posts_category', value => {
  toggle('.blog .main-posts .post .meta-category', value());
  value.bind(to => {
    document.querySelectorAll('.blog .main-posts .post .meta-category').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Posts Page -> Post Buttons.
wp.customize('blog_posts_buttons', value => {
  toggle('.blog .main-posts .post .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.blog .main-posts .post .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Posts Page -> Author.
wp.customize('blog_posts_author', value => {
  if (document.querySelectorAll('.blog .main-posts .post .author').length > 0) {
    togglePostAuthor(value());
  }
  value.bind(to => {
    togglePostAuthor(to);
    document.querySelectorAll('.blog .main-posts .post .author').forEach(el => {
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Blog - Archive Pages.
 */

// Blog -> Archive Pages -> Publication date.
wp.customize('blog_archive_date', value => {
  toggle('.archive .main-posts.posts-type-post .post .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.archive .main-posts.posts-type-post .post .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Archive Pages -> Post category.
wp.customize('blog_archive_category', value => {
  toggle('.archive .main-posts.posts-type-post .post .meta-category', value());
  value.bind(to => {
    document.querySelectorAll('.archive .main-posts.posts-type-post .post .meta-category').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Archive Pages -> Post Buttons.
wp.customize('blog_archive_buttons', value => {
  toggle('.archive .main-posts.posts-type-post .post .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.archive .main-posts.posts-type-post .post .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Archive Pages -> Author.
wp.customize('blog_archive_author', value => {
  if (document.querySelectorAll('.archive .main-posts.posts-type-post .post .author').length > 0) {
    togglePostAuthor(value());
  }
  value.bind(to => {
    togglePostAuthor(to);
    document.querySelectorAll('.archive .main-posts.posts-type-post .post .author').forEach(el => {
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Blog -> Single Post.
 */

// Blog -> Single Post -> Publication date.
wp.customize('blog_single_post_date', value => {
  toggle('.single-post .single-entry .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .single-entry .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Post category.
wp.customize('blog_single_post_category', value => {
  toggle('.single-post .single-entry .meta-categories', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .single-entry .meta-categories').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Post tags.
wp.customize('blog_single_post_tags', value => {
  toggle('.single-post .single-entry .tags-links', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .single-entry .tags-links').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Post Buttons.
wp.customize('blog_single_post_buttons', value => {
  toggle('.single-post .single-entry-footer .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .single-entry-footer .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Author Information.
wp.customize('blog_single_post_author', value => {
  toggle('.single-post .single-entry-footer .post-author', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .single-entry-footer .post-author').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Post Navigation.
wp.customize('blog_single_post_navigation', value => {
  toggle('.single-post .post-navigation', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .post-navigation').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Blog -> Single Post -> Related Posts.
wp.customize('blog_single_post_related', value => {
  toggle('.single-post .related-posts', value());
  value.bind(to => {
    document.querySelectorAll('.single-post .related-posts').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Typography Section
 */

// Font Family.
let fontCSSRule = null;
function getFontCSSRule() {
  let rule = null;
  let style = document.querySelector('#evie-inline-css');
  if (style === null) {
    style = document.createElement('style');
    style.setAttribute('id', 'evie-inline-css');
    document.head.appendChild(style);
  }
  if (style && style.sheet) {
    for (let i = 0; i < style.sheet.cssRules.length; i++) {
      if (style.sheet.cssRules[i].selectorText.indexOf('body') > -1) {
        rule = style.sheet.cssRules.item(i);
        return rule;
      }
    }
    if (rule === null) {
      const index = style.sheet.insertRule('body{}', 0);
      rule = style.sheet.cssRules.item(index);
    }
  }
  return rule;
}

/**
 * Updates the customize preview styles.
 */
function updateFontPreview(key, value) {
  fontCSSRule = fontCSSRule || getFontCSSRule();
  if (fontCSSRule) {
    if (value) {
      if (value.indexOf(',') < 0) {
        value = `"${value}", sans-serif`;
      }
      fontCSSRule.style.setProperty(`--evie-font-${key}`, value);
    } else {
      fontCSSRule.style.removeProperty(`--evie-font-${key}`);
    }
  }
}
const fontSettings = [{
  id: 'headings',
  name: 'primary'
}, {
  id: 'base',
  name: 'secondary'
}];
fontSettings.forEach(font => {
  wp.customize(`typography_font_${font.id}`, value => {
    value.bind(to => {
      updateFontPreview(font.name, to);
    });
  });
});

// Font Size.

const mediaQueries = ['@media (max-width: 700px)', '@media (min-width: 768px) and (max-width: 1000px)', '@media (min-width: 1024px)'];
const previewStyles = {};
const fontSizeSettings = ['base', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

/**
 * Updates the customize preview styles.
 */
function updatePreviewStyles() {
  let style = document.querySelector('#evie-customize-preview');
  if (style === null) {
    style = document.createElement('style');
    style.setAttribute('id', 'evie-customize-preview');
    document.head.appendChild(style);
  }
  const styles = [];
  Object.values(previewStyles).forEach(value => {
    if (value) {
      styles.push(value);
    }
  });
  style.innerHTML = styles.join(' ');
}

/**
 * Changes the font size.
 *
 * @param {string} name  A font setting name.
 * @param {string} value A font setting value.
 */
function changeFontSize(name, value) {
  if (value) {
    const styles = [];
    const sizes = typeof value === 'string' ? value.split(',') : value;
    sizes.forEach((size, index) => {
      if (size) {
        let style = `body { --evie-font-size-${name}: ${size}; }`;
        if (mediaQueries[index]) {
          style = `${mediaQueries[index]} { ${style} }`;
        }
        styles.push(style);
      }
    });
    previewStyles[`font-size-${name}`] = styles.join(' ');
  } else {
    previewStyles[`font-size-${name}`] = '';
  }
  updatePreviewStyles();
}
fontSizeSettings.forEach(name => {
  wp.customize(`typography_font_${name}_sizes`, value => {
    changeFontSize(name, value());
    value.bind(to => {
      changeFontSize(name, to || '');
    });
  });
});

/**
 * Footer Section
 */

// Footer Text
wp.customize('footer_text', value => {
  value.bind(to => {
    document.querySelectorAll('#site-footer .footer-copyright').forEach(el => {
      el.innerHTML = to;
    });
    document.querySelectorAll('#site-footer .footer-text').forEach(el => {
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Footer Menu
wp.customize('footer_menu', value => {
  toggle('#site-footer .footer-menu-wrapper', value() === 'show');
  value.bind(to => {
    document.querySelectorAll('#site-footer .footer-menu-wrapper').forEach(el => {
      toggle(el, to === 'show');
      el.classList.add('evie-customize-ready');
      customize_preview_setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});
/******/ })()
;