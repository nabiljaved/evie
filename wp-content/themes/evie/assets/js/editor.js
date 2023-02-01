/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/assets/js/inc/components/browser.js
/**
 * Browser object.
 */
const Browser = {};
const ua = window.navigator.userAgent;
const msie = ua.indexOf('MSIE ');

// IE 10 or older
if (msie > -1) {
  Browser.msie = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
}

// IE 11
const trident = ua.indexOf('Trident/');
if (trident > -1) {
  const rv = ua.indexOf('rv:');
  Browser.msie = parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
}

// MS Edge.
const edge = ua.indexOf('Edge/');
if (edge > -1) {
  Browser.msie = parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
}

// Whether the touch is enabled.
if ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0) {
  Browser.touch = true;
} else {
  Browser.touch = false;
}

// Whether the browser is RTL (the document direction is right-to-left like Hebrew or Arabic).
Browser.rtl = 'rtl' === document.querySelector('html').getAttribute('dir');
/* harmony default export */ var browser = (Browser);
;// CONCATENATED MODULE: ./src/assets/js/inc/components/throttle-debounce.js
/**
 * Throttle execution of a function. Especially useful for rate limiting
 * execution of handlers on events like resize and scroll.
 *
 * @param {Function} callback               A function to be executed after delay milliseconds. The `this` context and all arguments are passed through,
 *                                          as-is, to `callback` when the throttled-function is executed.
 * @param {number}   delay                  A zero-or-greater delay in milliseconds. For event callbacks, values around 100 or 250 (or even higher)
 *                                          are most useful.
 * @param {Object}   [options]              An object to configure options.
 * @param {boolean}  [options.noTrailing]   Optional, defaults to false. If noTrailing is true, callback will only execute every `delay` milliseconds
 *                                          while the throttled-function is being called. If noTrailing is false or unspecified, callback will be executed
 *                                          one final time after the last throttled-function call. (After the throttled-function has not been called for
 *                                          `delay` milliseconds, the internal counter is reset).
 * @param {boolean}  [options.noLeading]    Optional, defaults to false. If noLeading is false, the first throttled-function call will execute callback
 *                                          immediately. If noLeading is true, the first the callback execution will be skipped. It should be noted that
 *                                          callback will never executed if both noLeading = true and noTrailing = true.
 * @param {boolean}  [options.debounceMode] If `debounceMode` is true (at begin), schedule `clear` to execute after `delay` ms. If `debounceMode` is
 *                                          false (at end), schedule `callback` to execute after `delay` ms.
 *
 * @return {Function} A new, throttled, function.
 */
function throttle(callback, delay, options) {
  const {
    noTrailing = false,
    noLeading = false,
    debounceMode = undefined
  } = options || {};
  /*
   * After wrapper has stopped being called, this timeout ensures that
   * `callback` is executed at the proper times in `throttle` and `end`
   * debounce modes.
   */
  let timeoutID;
  let cancelled = false;

  // Keep track of the last time `callback` was executed.
  let lastExec = 0;

  // Function to clear existing timeout
  function clearExistingTimeout() {
    if (timeoutID) {
      clearTimeout(timeoutID);
    }
  }

  // Function to cancel next exec
  function cancel(settings) {
    const {
      upcomingOnly = false
    } = settings || {};
    clearExistingTimeout();
    cancelled = !upcomingOnly;
  }

  /*
   * The `wrapper` function encapsulates all of the throttling / debouncing
   * functionality and when executed will limit the rate at which `callback`
   * is executed.
   */
  function wrapper() {
    for (var _len = arguments.length, arguments_ = new Array(_len), _key = 0; _key < _len; _key++) {
      arguments_[_key] = arguments[_key];
    }
    const self = this;
    const elapsed = Date.now() - lastExec;
    if (cancelled) {
      return;
    }

    // Execute `callback` and update the `lastExec` timestamp.
    function exec() {
      lastExec = Date.now();
      callback.apply(self, arguments_);
    }

    /*
     * If `debounceMode` is true (at begin) this is used to clear the flag
     * to allow future `callback` executions.
     */
    function clear() {
      timeoutID = undefined;
    }
    if (!noLeading && debounceMode && !timeoutID) {
      /*
       * Since `wrapper` is being called for the first time and
       * `debounceMode` is true (at begin), execute `callback`
       * and noLeading != true.
       */
      exec();
    }
    clearExistingTimeout();
    if (debounceMode === undefined && elapsed > delay) {
      if (noLeading) {
        /*
         * In throttle mode with noLeading, if `delay` time has
         * been exceeded, update `lastExec` and schedule `callback`
         * to execute after `delay` ms.
         */
        lastExec = Date.now();
        if (!noTrailing) {
          timeoutID = setTimeout(debounceMode ? clear : exec, delay);
        }
      } else {
        /*
         * In throttle mode without noLeading, if `delay` time has been exceeded, execute
         * `callback`.
         */
        exec();
      }
    } else if (noTrailing !== true) {
      /*
       * In trailing throttle mode, since `delay` time has not been
       * exceeded, schedule `callback` to execute `delay` ms after most
       * recent execution.
       *
       * If `debounceMode` is true (at begin), schedule `clear` to execute
       * after `delay` ms.
       *
       * If `debounceMode` is false (at end), schedule `callback` to
       * execute after `delay` ms.
       */
      timeoutID = setTimeout(debounceMode ? clear : exec, debounceMode === undefined ? delay - elapsed : delay);
    }
  }
  wrapper.cancel = cancel;

  // Return the wrapper function.
  return wrapper;
}

/**
 * Debounce execution of a function. Debouncing, unlike throttling,
 * guarantees that a function is only executed a single time, either at the
 * very beginning of a series of calls, or at the very end.
 *
 * @param {Function} callback          A function to be executed after delay milliseconds. The `this` context and all arguments are passed through, as-is,
 *                                     to `callback` when the debounced-function is executed.
 * @param {number}   delay             A zero-or-greater delay in milliseconds. For event callbacks, values around 100 or 250 (or even higher) are most useful.
 * @param {Object}   [options]         An object to configure options.
 * @param {boolean}  [options.atBegin] Optional, defaults to false. If atBegin is false or unspecified, callback will only be executed `delay` milliseconds
 *                                     after the last debounced-function call. If atBegin is true, callback will be executed only at the first debounced-function call.
 *                                     (After the throttled-function has not been called for `delay` milliseconds, the internal counter is reset).
 *
 * @return {Function} A new, debounced function.
 */
function debounce(callback, delay, options) {
  const {
    atBegin = false
  } = options || {};
  return throttle(callback, delay, {
    debounceMode: atBegin !== false
  });
}

;// CONCATENATED MODULE: ./src/assets/js/inc/components/class-app.js


/**
 * Internal dependencies
 */


/**
 * External dependencies
 */


/**
 * Main App
 *
 * Creates main functionality.
 *
 * @version 1.0.0
 */
class App {
  /**
   * Initializes the App.
   */
  constructor() {
    this.settings = {
      breakpoints: {
        xs: 0,
        sm: 576,
        md: 768,
        lg: 1024,
        xl: 1200
      },
      desktopMenuBreakpoint: 1080
    };
    const {
      evieSettings
    } = window;
    if (typeof evieSettings === 'object') {
      this.settings = Object.assign(this.settings, evieSettings);
    }
    this.eventsListeners = {};
    this.isReady = false;
    this.isDarkMode = false;
    this.initDarkMode();
    this.initBrowser();
    this.init();
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
      if (this.isReady !== true) {
        setTimeout(() => {
          this.ready();
        }, 1);
        this.isReady = true;
      }
    } else {
      document.addEventListener('DOMContentLoaded', () => {
        if (this.isReady !== true) {
          this.ready();
          this.isReady = true;
        }
      });
    }
  }
  initDarkMode() {
    if (document.body.classList.contains('has-scheme-auto')) {
      this.isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    } else {
      this.isDarkMode = document.body.classList.contains('has-scheme-dark');
    }
    if (!document.body.classList.contains('logged-in') && document.body.classList.contains('has-user-color-support') && window.localStorage.getItem('evieDarkMode') !== null) {
      this.isDarkMode = window.localStorage.getItem('evieDarkMode') === '1';
    }
    document.body.classList.remove('has-scheme-auto');
    if (this.isDarkMode) {
      document.body.classList.remove('has-scheme-light');
      document.body.classList.add('has-scheme-dark');
    } else {
      document.body.classList.remove('has-scheme-dark');
      document.body.classList.add('has-scheme-light');
    }
  }
  init() {
    this.emit('init');
  }
  initBrowser() {
    this.browser = browser;

    // Set the browser window sizes.
    this.setBrowserSizes();
    window.addEventListener('resize', debounce(() => {
      this.setBrowserSizes();
    }, 300));
  }
  setBrowserSizes() {
    const width = window.innerWidth;
    this.browser.xs = width < this.settings.breakpoints.sm;
    this.browser.sm = width >= this.settings.breakpoints.sm && width < this.settings.breakpoints.md;
    this.browser.md = width >= this.settings.breakpoints.sm && width < this.settings.breakpoints.lg;
    this.browser.lg = width >= this.settings.breakpoints.lg && width < this.settings.breakpoints.xl;
    this.browser.xl = width >= this.settings.breakpoints.xl;
    this.browser.hover = window.matchMedia('(hover:hover)').matches;
  }
  on(events, handler, priority) {
    if (typeof handler !== 'function') {
      return this;
    }
    const method = priority ? 'unshift' : 'push';
    events.split(' ').forEach(event => {
      if (!this.eventsListeners[event]) {
        this.eventsListeners[event] = [];
      }
      this.eventsListeners[event][method](handler);
    });
    return this;
  }
  once(events, handler, priority) {
    if (typeof handler !== 'function') {
      return this;
    }
    function onceHandler() {
      this.off(events, onceHandler);
      if (onceHandler.__emitterProxy) {
        delete onceHandler.__emitterProxy;
      }
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      handler.apply(this, args);
    }
    onceHandler.__emitterProxy = handler;
    return this.on(events, onceHandler, priority);
  }
  off(events, handler) {
    if (!this.eventsListeners) {
      return this;
    }
    events.split(' ').forEach(event => {
      if (typeof handler === 'undefined') {
        this.eventsListeners[event] = [];
      } else if (this.eventsListeners[event]) {
        this.eventsListeners[event].forEach((eventHandler, index) => {
          if (eventHandler === handler || eventHandler.__emitterProxy && eventHandler.__emitterProxy === handler) {
            this.eventsListeners[event].splice(index, 1);
          }
        });
      }
    });
    return this;
  }
  emit() {
    if (!this.eventsListeners) {
      return this;
    }
    let events;
    let data;
    let context;
    for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
      args[_key2] = arguments[_key2];
    }
    if (typeof args[0] === 'string' || Array.isArray(args[0])) {
      events = args[0];
      data = args.slice(1, args.length);
      context = this;
    } else {
      events = args[0].events;
      data = args[0].data;
      context = args[0].context || this;
    }
    data.unshift(context);
    const eventsArray = Array.isArray(events) ? events : events.split(' ');
    eventsArray.forEach(event => {
      if (this.eventsListeners && this.eventsListeners[event]) {
        this.eventsListeners[event].forEach(eventHandler => {
          eventHandler.apply(context, data);
        });
      }
    });
    return this;
  }
  ready() {
    this.emit('ready');
  }
}
/* harmony default export */ var class_app = (App);
;// CONCATENATED MODULE: ./src/assets/js/inc/admin-app.js


/**
 * Internal dependencies
 */


/**
 * Main App
 *
 * Creates main functionality.
 *
 * @version 1.0.0
 */
window.evieApp = new class_app();
;// CONCATENATED MODULE: ./src/assets/js/inc/components/scroller.js


/**
 * External dependencies
 */


/**
 *
 * @param {Element} element Target element.
 * @param {Object}  options Options.
 */
function Scroller(element, options) {
  if (!element) {
    return;
  }
  if (element.classList.contains('is-scroller-initialized')) {
    return;
  }
  options = Object.assign({
    container: element.parentElement
  }, options || {});
  element.classList.add('evie-scroller', 'is-scroller-initialized');
  let container;
  if (typeof options.container === 'string') {
    container = element.closest(options.container);
    if (container === null) {
      container = document.createElement('div');
      element.parentElement.insertBefore(container, element);
      container.append(element);
    }
  } else if (typeof options.container === 'object') {
    container = options.container;
  }
  container.classList.add('evie-scroller-wrapper');
  const backwardArrow = document.createElement('span');
  backwardArrow.classList.add('evie-scroll-arrow', 'evie-scroll-backward', 'evie-ico-left');
  container.prepend(backwardArrow);
  const forwardArrow = document.createElement('span');
  forwardArrow.classList.add('evie-scroll-arrow', 'evie-scroll-forward', 'evie-ico-right');
  container.append(forwardArrow);
  const selectedItem = element.querySelector('.is-selected');
  if (selectedItem !== null) {
    if (selectedItem.offsetLeft + selectedItem.offsetWidth > element.clientWidth) {
      element.scrollLeft = selectedItem.offsetLeft - backwardArrow.offsetWidth;
    }
  }
  const updateArrows = () => {
    backwardArrow.classList.toggle('is-scroll-active', element.scrollLeft > 0);
    forwardArrow.classList.toggle('is-scroll-active', element.scrollLeft + element.clientWidth < element.scrollWidth - 2);
  };
  backwardArrow.addEventListener('click', () => {
    element.scrollLeft -= element.clientWidth;
  });
  forwardArrow.addEventListener('click', () => {
    element.scrollLeft += element.clientWidth;
  });
  updateArrows();
  element.addEventListener('scroll', debounce(() => {
    updateArrows();
  }, 150));
  window.addEventListener('resize', debounce(() => {
    updateArrows();
  }, 300));
}
;// CONCATENATED MODULE: ./src/assets/js/inc/components/slideToggle.js
/**
 * Slides the target panel up (close it).
 *
 * @param {Element} target  Target element.
 * @param {Object}  options Slide options.
 */
const slideUp = (target, options) => {
  options = Object.assign({
    duration: 500,
    callback: () => {}
  }, options || {});
  target.style.transitionProperty = 'height, margin, padding';
  target.style.transitionDuration = options.duration + 'ms';
  target.style.height = target.offsetHeight + 'px';
  setTimeout(() => {
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
      target.style.display = 'none';
      target.style.removeProperty('height');
      target.style.removeProperty('padding-top');
      target.style.removeProperty('padding-bottom');
      target.style.removeProperty('margin-top');
      target.style.removeProperty('margin-bottom');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
      if (typeof options.callback === 'function') {
        options.callback();
      }
    }, options.duration);
  }, 100);
};

/**
 * Slides the target panel down (open it).
 *
 * @param {Element} target  Target element.
 * @param {Object}  options Slide options.
 */
const slideDown = (target, options) => {
  options = Object.assign({
    duration: 500,
    callback: () => {}
  }, options || {});
  target.style.removeProperty('display');
  let display = window.getComputedStyle(target).display;
  if (display === 'none') {
    display = 'block';
  }
  target.style.display = display;
  const height = target.offsetHeight;
  target.style.overflow = 'hidden';
  target.style.height = 0;
  target.style.paddingTop = 0;
  target.style.paddingBottom = 0;
  target.style.marginTop = 0;
  target.style.marginBottom = 0;
  setTimeout(() => {
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = options.duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    window.setTimeout(() => {
      target.style.removeProperty('height');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
      if (typeof options.callback === 'function') {
        options.callback();
      }
    }, options.duration);
  }, 100);
};

/**
 * Toggles the target panel up or down.
 *
 * @param {Element} target  Target element.
 * @param {Object}  options Slide options.
 */
const slideToggle = (target, options) => {
  if (window.getComputedStyle(target).display === 'none') {
    slideDown(target, options);
  } else {
    slideUp(target, options);
  }
};

;// CONCATENATED MODULE: ./src/assets/js/inc/posts.js


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


const {
  Colcade,
  IntersectionObserver,
  getComputedStyle,
  ScrollMagic,
  evieApp,
  flextension
} = window;

/**
 * Initializes the posts in the list.
 *
 * @param {Array}   posts   An array of the posts.
 * @param {Element} element The posts container element.
 */
function initPosts(posts, element) {
  if (posts && posts.length > 0) {
    const gridLayout = element.classList.contains('posts-layout-grid') || element.classList.contains('posts-layout-waterfall');
    const columns = gridLayout ? getComputedStyle(element).getPropertyValue('--evie-grid-columns') : 1;
    posts.forEach((post, index) => {
      if (element.classList.contains('has-post-animation')) {
        let triggerHook = 0.1;
        if (gridLayout === true) {
          const delay = index % columns;
          post.style.setProperty('--evie-transition-delay', (delay * 0.1).toFixed(1) + 's');
        } else {
          triggerHook = 0.5;
        }
        const observer = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              post.classList.add('evie-active');
              observer.unobserve(post);
            }
          });
        }, {
          threshold: triggerHook
        });
        observer.observe(post);
      }
    });
  }
  evieApp.emit('afterInitPosts', posts, element);
}
let isFilterOpen = false;

/**
 * Initializes the post filters.
 *
 * @param {Element} content Target element.
 */
function initPostFilters(content) {
  content.querySelectorAll('.posts-filters').forEach(filter => {
    const list = filter.querySelector('.filter-categories .terms-list');
    if (list !== null) {
      Scroller(list);
    }
    const filterToggle = filter.querySelector('.filter-toggle-button');
    if (filterToggle !== null) {
      const filterPanel = content.querySelector('.filter-options');
      if (isFilterOpen === true) {
        filterToggle.classList.add('is-selected');
        filterPanel.style.display = 'block';
      }
      let isAnimating = false;
      filterToggle.addEventListener('click', e => {
        e.preventDefault();
        if (isAnimating === true) {
          return;
        }
        isAnimating = true;
        isFilterOpen = !isFilterOpen;
        filterToggle.classList.toggle('is-selected', isFilterOpen);
        slideToggle(filterPanel, {
          callback: () => {
            isAnimating = false;
          }
        });
      });
    }
  });
}

/**
 * Adds a scrolling parallax effect to columns in a grid.
 *
 * @version 1.0.0
 * @param {Element} element Target element.
 */
function ParallaxGrid(element) {
  if (!element) {
    return;
  }
  if (element.classList.contains('is-parallax-initialized')) {
    return;
  }
  element.classList.add('is-parallax-initialized');
  const columns = Array.from(element.children);
  const parallax = columns[0] && (columns[0].getBoundingClientRect().top - element.getBoundingClientRect().top) * 2;
  const scene = new ScrollMagic.Scene({
    triggerElement: element,
    triggerHook: 1,
    duration: () => {
      return element.offsetHeight + window.innerHeight;
    }
  });
  scene.on('progress', e => {
    const translate = e.progress * parallax - parallax * 0.33;
    for (let i = 0; i < columns.length; i++) {
      const column = columns[i];
      if (column.offsetParent === null) {
        return;
      }
      const posY = i % 2 ? Math.max(0, translate) : Math.max(-parallax, translate * -1);
      column.style.transform = `translate3d(0, ${Math.min(parallax, posY)}px, 0)`;
    }
  });
  scene.addTo(evieApp.scrollController);
  scene.enabled(columns[1] && columns[1].offsetParent !== null);
  window.addEventListener('resize', debounce(() => {
    scene.enabled(columns[1] && columns[1].offsetParent !== null);
  }, 300));
}
evieApp.on('ready initPosts', (context, content) => {
  if (!content) {
    content = document;
  }
  initPostFilters(content);
  content.querySelectorAll('.evie-posts').forEach(element => {
    const posts = element.querySelectorAll('.entry');
    // Initialize the Waterfall columns.
    if (element.classList.contains('posts-layout-waterfall')) {
      const grid = element.querySelector('.grid-columns');
      if (grid !== null) {
        new Colcade(grid, {
          columns: '.grid-column',
          items: '.entry'
        });
        evieApp.emit('afterInitWaterfall', grid);
      }
    }
    initPosts(posts, element);
  });
  content.querySelectorAll('.grid-parallax .posts-list').forEach(el => {
    ParallaxGrid(el);
  });
});
evieApp.on('ajaxPagination.beforeGetPosts', (e, ajaxPagination) => {
  if (ajaxPagination && ajaxPagination.updateMode === 'replace') {
    evieApp.scrollTo(ajaxPagination.list);
  }
});
evieApp.on('ajaxPagination.afterUpdatePost', (e, content) => {
  evieApp.emit('ready', content);
  if (flextension) {
    flextension.emit('ready', content);
  }
});
evieApp.on('ajaxPagination.afterUpdatePosts', (context, posts, ajaxPage) => {
  const colc = Colcade && Colcade.data(ajaxPage.list);
  if (ajaxPage.element.classList.contains('posts-layout-waterfall')) {
    if (colc) {
      if (ajaxPage.updateMode === 'append') {
        colc.append(posts);
      } else {
        colc.updateItems();
        colc.layout();
      }
      evieApp.emit('afterUpdateWaterfall', ajaxPage.element, posts);
    }
  } else if (colc) {
    colc.destroy();
  }
  initPosts(posts, ajaxPage.element);
  if (ajaxPage.updateMode === 'append') {
    if (posts && posts.length > 0) {
      posts.forEach(post => {
        if (flextension) {
          flextension.emit('ready', post);
        }
        evieApp.emit('ready', post);
      });
    }
  } else {
    if (flextension) {
      flextension.emit('ready', ajaxPage.list);
    }
    evieApp.emit('ready', ajaxPage.list);
  }
});
evieApp.on('ajaxPagination.beforeUpdatePosts', (context, ajaxPage) => {
  if ('replace' === ajaxPage.updateMode) {
    if (ajaxPage.list.classList.contains('grid-columns')) {
      const columns = ajaxPage.list.querySelectorAll('.grid-column');
      if (columns !== null && columns.length > 0) {
        columns.forEach(column => {
          column.innerHTML = '';
        });
      } else {
        ajaxPage.list.innerHTML = '';
      }
    } else {
      ajaxPage.list.innerHTML = '';
    }
  }
});
if (flextension) {
  // Quick View Lightbox.
  flextension.on('afterQuickViewLoaded', (context, content) => {
    if (!content) {
      return;
    }
    evieApp.emit('ready', content);
  });
  flextension.on('afterQuickViewOpen', (context, lightbox) => {
    if (evieApp.browser.xl) {
      evieApp.disableScroll(window, '.quick-view-content ');
    }
  });
  flextension.on('afterQuickViewClose', () => {
    if (evieApp.browser.xl) {
      evieApp.enableScroll();
    }
  });
}
;// CONCATENATED MODULE: ./src/assets/js/editor.js
/**
 * Editor
 *
 * @author  Wyde
 * @version 1.0.0
 */





const {
  evieApp: editor_evieApp,
  flextension: editor_flextension
} = window;
editor_evieApp.on('ready', () => {
  const editorContainer = document.getElementById('editor');
  if (editorContainer === null) {
    return;
  }
  const headerLayoutChange = value => {
    let headerClass = 'has-header-description';
    switch (value) {
      case 'breadcrumb':
        headerClass = 'has-header-breadcrumb';
        break;
      case 'none':
        headerClass = 'has-header-none';
        break;
    }
    editorContainer.classList.remove('has-header-none', 'has-header-description', 'has-header-breadcrumb');
    editorContainer.classList.add(headerClass);
  };
  const headerSizeChange = value => {
    editorContainer.classList.remove('has-header-size-short', 'has-header-size-medium', 'has-header-size-tall', 'has-header-size-full');
    if (value) {
      editorContainer.classList.add(`has-header-size-${value}`);
    }
  };
  const headerWidthChange = value => {
    editorContainer.classList.remove('has-header-width-wide', 'has-header-width-full');
    if (value) {
      editorContainer.classList.add(`has-header-width-${value}`);
    }
  };
  const headerAlignChange = value => {
    editorContainer.classList.remove('has-header-align-left', 'has-header-align-center', 'has-header-align-right');
    if (value) {
      editorContainer.classList.add(`has-header-align-${value}`);
    }
  };
  const headerTextModeChange = value => {
    editorContainer.classList.remove('has-header-text-light', 'has-header-text-dark');
    if (value) {
      editorContainer.classList.add(`has-header-text-${value}`);
    }
  };
  const headerBackgroundChange = value => {
    editorContainer.classList.remove('has-header-background-color', 'has-header-background-image');
    if (value) {
      editorContainer.classList.add(`has-header-background-${value}`);
    }
  };
  const headerBackgroundColorChange = value => {
    editorContainer.style.setProperty('--evie-editor-header-background-color', value);
  };
  const headerBackgroundImageChange = value => {
    editorContainer.style.setProperty('--evie-editor-header-background-image', `url(${value})`);
  };
  const headerBackgroundPositionChange = value => {
    if (!value) {
      value = 'center center';
    }
    editorContainer.style.setProperty('--evie-editor-header-background-position', value);
  };
  const headerGapChange = value => {
    editorContainer.classList.toggle('is-header-gap-hidden', value);
  };
  const headerBackgroundSizeChange = value => {
    if (!value) {
      value = 'cover';
    }
    editorContainer.style.setProperty('--evie-editor-header-background-size', value);
  };
  const headerBackgroundAttachmentChange = value => {
    editorContainer.classList.toggle('has-header-background-fixed', value);
  };
  const headerBackgroundRepeatChange = value => {
    editorContainer.classList.toggle('has-header-background-repeat', value);
  };
  const headerOverlayChange = value => {
    editorContainer.classList.toggle('has-header-gradient-background', value !== 'color');
    editorContainer.classList.toggle('has-header-overlay-background', value === 'color');
    const headerGap = document.querySelector('#flext-field-evie-hide-header-gap');
    if (headerGap !== null) {
      headerGap.style.display = value === 'color';
    }
  };
  const headerOverlayColorChange = value => {
    editorContainer.style.setProperty('--evie-editor-header-overlay-color', value);
  };
  const headerOverlayOpacityChange = value => {
    editorContainer.style.setProperty('--evie-editor-header-overlay-opacity', parseFloat(value / 100).toFixed(1));
  };
  document.querySelectorAll('[name="_evie_header"').forEach(option => {
    option.addEventListener('change', event => {
      if (event.target.checked) {
        headerLayoutChange(event.target.value);
      }
    });
    if (option.checked) {
      headerLayoutChange(option.value);
    }
  });
  document.querySelectorAll('[name="_evie_hide_header_gap"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerGapChange(setting.checked);
    });
    headerGapChange(setting.checked);
  });
  document.querySelectorAll('[name="_evie_header_size"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerSizeChange(setting.value);
    });
    headerSizeChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_width"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerWidthChange(setting.value);
    });
    headerWidthChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_align"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerAlignChange(setting.value);
    });
    headerAlignChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_text_mode"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerTextModeChange(setting.value);
    });
    headerTextModeChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_bg"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerBackgroundChange(setting.value);
    });
    headerBackgroundChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_bg_position"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerBackgroundPositionChange(setting.value);
    });
    headerBackgroundPositionChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_bg_size"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerBackgroundSizeChange(setting.value);
    });
    headerBackgroundSizeChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_bg_attachment"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerBackgroundAttachmentChange(setting.checked);
    });
    headerBackgroundAttachmentChange(setting.checked);
  });
  document.querySelectorAll('[name="_evie_header_bg_repeat"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerBackgroundRepeatChange(setting.checked);
    });
    headerBackgroundRepeatChange(setting.checked);
  });
  document.querySelectorAll('[name="_evie_header_bg_color"').forEach(setting => {
    editor_flextension.on('colorControlChange', (context, name, color) => {
      if (name === setting.name) {
        headerBackgroundColorChange(color);
      }
    });
    headerBackgroundColorChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_bg_overlay"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerOverlayChange(setting.value);
    });
    headerOverlayChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_overlay_color"').forEach(setting => {
    editor_flextension.on('colorControlChange', (context, name, color) => {
      if (name === setting.name) {
        headerOverlayColorChange(color);
      }
    });
    headerOverlayColorChange(setting.value);
  });
  document.querySelectorAll('[name="_evie_header_overlay_opacity"').forEach(setting => {
    setting.addEventListener('change', () => {
      headerOverlayOpacityChange(setting.value);
    });
    headerOverlayOpacityChange(setting.value);
  });
  const {
    select,
    subscribe
  } = wp.data;
  let featuredImageId = null;
  let featuredImage = null;
  subscribe(() => {
    const newImageId = select('core/editor').getEditedPostAttribute('featured_media');
    if (newImageId !== undefined) {
      const newFeaturedImage = select('core').getMedia(parseInt(newImageId, 10));
      if (newFeaturedImage !== undefined) {
        if (featuredImageId === null) {
          featuredImageId = newImageId;
          featuredImage = newFeaturedImage;
          headerBackgroundImageChange(featuredImage.source_url);
        }
        if (newImageId !== featuredImageId) {
          featuredImageId = newImageId;
          featuredImage = newFeaturedImage;
          headerBackgroundImageChange(featuredImage.source_url);
        }
      }
    }
  });
});
/******/ })()
;