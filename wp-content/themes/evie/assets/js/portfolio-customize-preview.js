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
;// CONCATENATED MODULE: ./src/assets/js/portfolio-customize-preview.js
/**
 * Portfolio Customize Preview
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */

const {
  evieApp
} = window;

// Collect information from customize-controls.js about which panels are opening.
wp.customize.bind('preview-ready', () => {
  wp.customize.selectiveRefresh.bind('partial-content-rendered', placement => {
    if (['portfolio_posts_layout', 'portfolio_posts_parallax', 'portfolio_posts_style', 'portfolio_posts_hover_effect', 'portfolio_posts_animation', 'portfolio_archive_layout', 'portfolio_archive_parallax', 'portfolio_archive_style', 'portfolio_archive_hover_effect', 'portfolio_archive_animation', 'portfolio_quick_view_enable'].includes(placement.partial.id)) {
      const container = placement.container.length ? placement.container[0] : null;
      evieApp.emit('initPosts', container ? container.parentElement : null);
    }
  });
});

/**
 * Portfolio -> Portfolio Page.
 */

/**
 * Toggles project author display.
 *
 * @param {string} value    Setting value.
 * @param {string} selector Element selector.
 */
function togglePostAuthor(value, selector) {
  if (!selector) {
    selector = '.main-posts .entry';
  }
  switch (value) {
    case 'hide':
      toggle(`${selector} .author > a, ${selector} .author .fn`, false);
      break;
    case 'name':
      toggle(`${selector} .author > a`, false);
      toggle(`${selector} .author .fn`, true);
      break;
    case 'avatar':
      toggle(`${selector} .author > a`, true);
      toggle(`${selector} .author .fn`, false);
      break;
    default:
      toggle(`${selector} .author > a, ${selector} .author .fn`, true);
      break;
  }
}

// Portfolio -> Portfolio Page -> Custom Content.
wp.customize('portfolio_posts_page_content', value => {
  toggle('.post-type-archive-project .main-content > .entry-content', value());
  value.bind(to => {
    document.querySelectorAll('.post-type-archive-project .main-content > .entry-content').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Portfolio Page -> Publication date.
wp.customize('portfolio_posts_date', value => {
  toggle('.post-type-archive-project .main-posts.posts-type-project .entry .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.post-type-archive-project .main-posts.posts-type-project .entry .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Portfolio Page -> Post category.
wp.customize('portfolio_posts_category', value => {
  toggle('.post-type-archive-project .main-posts.posts-type-project .entry .meta-category', value());
  value.bind(to => {
    document.querySelectorAll('.post-type-archive-project .main-posts.posts-type-project .entry .meta-category').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Portfolio Page -> Post Buttons.
wp.customize('portfolio_posts_buttons', value => {
  toggle('.post-type-archive-project .main-posts.posts-type-project .entry .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.post-type-archive-project .main-posts.posts-type-project .entry .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Portfolio Page -> Author.
wp.customize('portfolio_posts_author', value => {
  if (document.querySelectorAll('.post-type-archive-project .main-posts.posts-type-project .entry .author').length > 0) {
    togglePostAuthor(value());
  }
  value.bind(to => {
    togglePostAuthor(to);
    document.querySelectorAll('.post-type-archive-project .main-posts.posts-type-project .entry .author').forEach(el => {
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Portfolio -> Archive Pages.
 */

// Portfolio-> Archive Pages -> Publication date.
wp.customize('portfolio_archive_date', value => {
  toggle('body:not(.post-type-archive) .main-posts.posts-type-project .entry .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('body:not(.post-type-archive) .main-posts.posts-type-project .entry .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Archive Pages -> Post category.
wp.customize('portfolio_archive_category', value => {
  toggle('body:not(.post-type-archive) .main-posts.posts-type-project .entry .meta-category', value());
  value.bind(to => {
    document.querySelectorAll('body:not(.post-type-archive) .main-posts.posts-type-project .entry .meta-category').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Archive Pages -> Post Buttons.
wp.customize('portfolio_archive_buttons', value => {
  toggle('body:not(.post-type-archive) .main-posts.posts-type-project .entry .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('body:not(.post-type-archive) .main-posts.posts-type-project .entry .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Archive Pages -> Author.
wp.customize('portfolio_archive_author', value => {
  if (document.querySelectorAll('body:not(.post-type-archive) .main-posts.posts-type-project .entry .author').length > 0) {
    togglePostAuthor(value());
  }
  value.bind(to => {
    togglePostAuthor(to);
    document.querySelectorAll('body:not(.post-type-archive) .main-posts.posts-type-project .entry .author').forEach(el => {
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Portfolio -> Single Project
 */
// Portfolio -> Single Project -> Publication date.
wp.customize('portfolio_single_post_date', value => {
  toggle('.single-project .single-entry .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .single-entry .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Post category.
wp.customize('portfolio_single_post_category', value => {
  toggle('.single-project .single-entry .meta-categories', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .single-entry .meta-categories').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Post tags.
wp.customize('portfolio_single_post_tags', value => {
  toggle('.single-project .single-entry .tags-links', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .single-entry .tags-links').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Post Buttons.
wp.customize('portfolio_single_post_buttons', value => {
  toggle('.single-project .single-entry-footer .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .single-entry-footer .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Author Information.
wp.customize('portfolio_single_post_author', value => {
  toggle('.single-project .single-entry-footer .post-author', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .single-entry-footer .post-author').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Post Navigation.
wp.customize('portfolio_single_post_navigation', value => {
  toggle('.single-project .post-navigation', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .post-navigation').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Single Project -> Related Posts.
wp.customize('portfolio_single_post_related', value => {
  toggle('.single-project .related-posts', value());
  value.bind(to => {
    document.querySelectorAll('.single-project .related-posts').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

/**
 * Portfolio -> Quick View.
 */

// Portfolio-> Quick View -> Publication date.
wp.customize('portfolio_quick_view_date', value => {
  toggle('.quick-view-content .meta-date', value());
  value.bind(to => {
    document.querySelectorAll('.quick-view-content .meta-date').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Quick View -> Project category.
wp.customize('portfolio_quick_view_category', value => {
  toggle('.quick-view-content .meta-categories', value());
  value.bind(to => {
    document.querySelectorAll('.quick-view-content .meta-categories').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio-> Quick View -> Author.
wp.customize('portfolio_quick_view_author', value => {
  if (document.querySelectorAll('.quick-view-content .author').length > 0) {
    togglePostAuthor(value(), '.quick-view-content');
  }
  value.bind(to => {
    togglePostAuthor(to, '.quick-view-content');
    document.querySelectorAll('.quick-view-content .author').forEach(el => {
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});

// Portfolio -> Quick View -> Post Buttons.
wp.customize('portfolio_quick_view_buttons', value => {
  toggle('.quick-view-content .entry-buttons', value());
  value.bind(to => {
    document.querySelectorAll('.quick-view-content .entry-buttons').forEach(el => {
      toggle(el, to);
      el.classList.add('evie-customize-ready');
      setTimeout(() => {
        el.classList.remove('customize-partial-refreshing');
      }, 1000);
    });
  });
});
/******/ })()
;