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

;// CONCATENATED MODULE: ./src/assets/js/inc/components/class-vertical-menu.js
/**
 * Internal dependencies
 */

const {
  Node,
  setTimeout: class_vertical_menu_setTimeout
} = window;

/**
 * Vertical Menu
 *
 * @version 1.0.0
 */
class VerticalMenu {
  /**
   * Vertical Menu constructor.
   *
   * @param {Element}  element  Target element.
   * @param {Object}   options  Options for the function.
   * @param {Function} callback Callback function.
   */
  constructor(element, options, callback) {
    if (!element) {
      return;
    }
    if (element.dataset.verticalMenu) {
      return;
    }
    element.dataset.verticalMenu = true;
    const defaults = {
      currentItemClass: 'current-menu-item',
      subMenuActiveClass: 'is-sub-menu-visible',
      hasChildrenSelector: '.menu-item-has-children',
      subMenuSelector: '.sub-menu',
      subMenuButtonSelector: '.sub-menu-button',
      singleOpen: true,
      changed: () => {}
    };
    this.settings = Object.assign(defaults, options || {});
    this.DOM = {
      el: element
    };
    this.init();
    if (typeof callback === 'function') {
      callback(element);
    }
  }

  /**
   * Initializes the vertical menu.
   */
  init() {
    this.DOM.el.querySelectorAll(this.settings.hasChildrenSelector).forEach(item => {
      const sub = item && item.querySelector(this.settings.subMenuSelector);
      if (sub) {
        item.querySelector('a').addEventListener('click', e => {
          if ('#' === e.target.getAttribute('href') || e.target.pathname === window.location.pathname) {
            e.preventDefault();
            this.toggleMenu(item);
            return false;
          }
          return true;
        });
        item.querySelector(this.settings.subMenuButtonSelector).addEventListener('click', () => {
          this.toggleMenu(item);
          return false;
        });
      }
    });
    class_vertical_menu_setTimeout(() => {
      this.openSubMenu();
    }, 500);
  }
  getParents(el, selector) {
    const elements = [];
    const ishaveselector = selector !== undefined;
    while ((el = el.parentElement) !== null) {
      if (el.nodeType !== Node.ELEMENT_NODE) {
        continue;
      }
      if (!ishaveselector || el.matches(selector)) {
        elements.push(el);
      }
    }
    return elements;
  }
  slideUp(menu, callback) {
    slideUp(menu.querySelector(this.settings.subMenuSelector), {
      duration: 300,
      callback: () => {
        menu.classList.remove(this.settings.subMenuActiveClass);
        if (typeof callback === 'function') {
          callback();
        }
      }
    });
  }
  slideDown(menu, callback) {
    menu.classList.add(this.settings.subMenuActiveClass);
    slideDown(menu.querySelector(this.settings.subMenuSelector), {
      duration: 300,
      callback
    });
  }

  /**
   * Shows or hides sub menu items.
   *
   * @param {Element} menu The sub menu to show or hide.
   */
  toggleMenu(menu) {
    if (this.settings.singleOpen) {
      const openItems = [menu].concat(this.getParents(menu, this.settings.hasChildrenSelector));
      this.DOM.el.querySelectorAll('.' + this.settings.subMenuActiveClass).forEach(item => {
        if (!openItems.includes(item)) {
          this.slideUp(item);
        }
      });
    }
    if (menu.classList.contains(this.settings.subMenuActiveClass)) {
      this.slideUp(menu, () => {
        if (typeof this.settings.changed === 'function') {
          this.settings.changed(this.DOM.el);
        }
      });
    } else {
      this.slideDown(menu, () => {
        if (typeof this.settings.changed === 'function') {
          this.settings.changed(this.DOM.el);
        }
      });
    }
  }

  /**
   * Opens sub menu items.
   */
  openSubMenu() {
    const currentMenu = this.DOM.el.querySelector('.' + this.settings.currentItemClass);
    // Select sub menu item
    const menu = currentMenu && currentMenu.closest(this.settings.hasChildrenSelector);
    if (menu !== null) {
      this.toggleMenu(menu);
    }
  }
}
;// CONCATENATED MODULE: ./src/assets/js/inc/components/scroll-to.js
/**
 * Returns the element offset object.
 *
 * @param {Element} el Target element.
 * @return {Object} An element offset object.
 */
function getElementOffset(el) {
  let top = 0;
  let left = 0;
  let element = el;
  // Loop through the DOM tree
  // and add it's parent's offset to get page offset
  do {
    top += element.offsetTop || 0;
    left += element.offsetLeft || 0;
    element = element.offsetParent;
  } while (element);
  return {
    top,
    left
  };
}

/**
 * Scroll Dom Element class.
 */
class ScrollDomElement {
  constructor(element) {
    this.element = element;
  }
  getHorizontalScroll() {
    return this.element.scrollLeft;
  }
  getVerticalScroll() {
    return this.element.scrollTop;
  }
  getMaxHorizontalScroll() {
    return this.element.scrollWidth - this.element.clientWidth;
  }
  getMaxVerticalScroll() {
    return this.element.scrollHeight - this.element.clientHeight;
  }
  getHorizontalElementScrollOffset(elementToScrollTo, elementToScroll) {
    return getElementOffset(elementToScrollTo).left - getElementOffset(elementToScroll).left;
  }
  getVerticalElementScrollOffset(elementToScrollTo, elementToScroll) {
    return getElementOffset(elementToScrollTo).top - getElementOffset(elementToScroll).top;
  }
  scrollTo(x, y) {
    this.element.scrollLeft = x;
    this.element.scrollTop = y;
  }
}

/**
 *  Scroll Window class.
 */
class ScrollWindow {
  getHorizontalScroll() {
    return window.scrollX || document.documentElement.scrollLeft;
  }
  getVerticalScroll() {
    return window.scrollY || document.documentElement.scrollTop;
  }
  getMaxHorizontalScroll() {
    return Math.max(document.body.scrollWidth, document.documentElement.scrollWidth, document.body.offsetWidth, document.documentElement.offsetWidth, document.body.clientWidth, document.documentElement.clientWidth) - window.innerWidth;
  }
  getMaxVerticalScroll() {
    return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight, document.body.offsetHeight, document.documentElement.offsetHeight, document.body.clientHeight, document.documentElement.clientHeight) - window.innerHeight;
  }
  getHorizontalElementScrollOffset(elementToScrollTo) {
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;
    return scrollLeft + elementToScrollTo.getBoundingClientRect().left;
  }
  getVerticalElementScrollOffset(elementToScrollTo) {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    return scrollTop + elementToScrollTo.getBoundingClientRect().top;
  }
  scrollTo(x, y) {
    window.scrollTo(x, y);
  }
}
// --------- KEEPING TRACK OF ACTIVE ANIMATIONS
const activeAnimations = {
  elements: [],
  cancelMethods: [],
  add: (element, cancelAnimation) => {
    activeAnimations.elements.push(element);
    activeAnimations.cancelMethods.push(cancelAnimation);
  },
  remove: function (element) {
    let shouldStop = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    const index = activeAnimations.elements.indexOf(element);
    if (index > -1) {
      // Stop animation
      if (shouldStop) {
        activeAnimations.cancelMethods[index]();
      }
      // Remove it
      activeAnimations.elements.splice(index, 1);
      activeAnimations.cancelMethods.splice(index, 1);
    }
  }
};
const WINDOW_EXISTS = typeof window !== 'undefined';
const defaultOptions = {
  cancelOnUserAction: true,
  easing: t => --t * t * t + 1,
  elementToScroll: WINDOW_EXISTS ? window : null,
  horizontalOffset: 0,
  maxDuration: 3000,
  minDuration: 250,
  speed: 500,
  verticalOffset: 0
};
async function animateScrollTo(numberOrCoordsOrElement) {
  let userOptions = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  // Check for server rendering
  if (!WINDOW_EXISTS) {
    // @ts-ignore
    // If it still gets called on server, return Promise for API consistency
    return new Promise(resolve => {
      resolve(false); // Returning false on server
    });
  } else if (!window.Promise) {
    throw "Browser doesn't support Promises, and animated-scroll-to depends on it, please provide a polyfill.";
  }
  let x;
  let y;
  let scrollToElement;
  const options = Object.assign(Object.assign({}, defaultOptions), userOptions);
  const isWindow = options.elementToScroll === window;
  const isElement = !!options.elementToScroll.nodeName;
  if (!isWindow && !isElement) {
    throw 'Element to scroll needs to be either window or DOM element.';
  }

  // Select the correct scrolling interface
  const elementToScroll = isWindow ? new ScrollWindow() : new ScrollDomElement(options.elementToScroll);
  if (numberOrCoordsOrElement instanceof Element) {
    scrollToElement = numberOrCoordsOrElement;
    // If "elementToScroll" is not a parent of "scrollToElement"
    if (isElement && (!options.elementToScroll.contains(scrollToElement) || options.elementToScroll.isSameNode(scrollToElement))) {
      throw 'options.elementToScroll has to be a parent of scrollToElement';
    }
    x = elementToScroll.getHorizontalElementScrollOffset(scrollToElement, options.elementToScroll);
    y = elementToScroll.getVerticalElementScrollOffset(scrollToElement, options.elementToScroll);
  } else if (typeof numberOrCoordsOrElement === 'number') {
    x = elementToScroll.getHorizontalScroll();
    y = numberOrCoordsOrElement;
  } else if (Array.isArray(numberOrCoordsOrElement) && numberOrCoordsOrElement.length === 2) {
    x = numberOrCoordsOrElement[0] === null ? elementToScroll.getHorizontalScroll() : numberOrCoordsOrElement[0];
    y = numberOrCoordsOrElement[1] === null ? elementToScroll.getVerticalScroll() : numberOrCoordsOrElement[1];
  } else {
    // ERROR
    throw 'Wrong function signature. Check documentation.\n' + 'Available method signatures are:\n' + '  animateScrollTo(y:number, options)\n' + '  animateScrollTo([x:number | null, y:number | null], options)\n' + '  animateScrollTo(scrollToElement:Element, options)';
  }

  // Add offsets
  if (typeof options.horizontalOffset === 'function') {
    x += options.horizontalOffset(x);
  } else {
    x += options.horizontalOffset;
  }
  if (typeof options.verticalOffset === 'function') {
    y += options.verticalOffset(y);
  } else {
    y += options.verticalOffset;
  }

  // Horizontal scroll distance
  const maxHorizontalScroll = elementToScroll.getMaxHorizontalScroll();
  const initialHorizontalScroll = elementToScroll.getHorizontalScroll();
  // If user specified scroll position is greater than maximum available scroll
  if (x > maxHorizontalScroll) {
    x = maxHorizontalScroll;
  }
  // Calculate distance to scroll
  const horizontalDistanceToScroll = x - initialHorizontalScroll;
  // Vertical scroll distance distance
  const maxVerticalScroll = elementToScroll.getMaxVerticalScroll();
  const initialVerticalScroll = elementToScroll.getVerticalScroll();
  // If user specified scroll position is greater than maximum available scroll
  if (y > maxVerticalScroll) {
    y = maxVerticalScroll;
  }
  // Calculate distance to scroll
  const verticalDistanceToScroll = y - initialVerticalScroll;
  // Calculate duration of the scroll
  const horizontalDuration = Math.abs(Math.round(horizontalDistanceToScroll / 1000 * options.speed));
  const verticalDuration = Math.abs(Math.round(verticalDistanceToScroll / 1000 * options.speed));
  let duration = horizontalDuration > verticalDuration ? horizontalDuration : verticalDuration;
  if (duration < options.minDuration) {
    duration = options.minDuration;
  } else if (duration > options.maxDuration) {
    duration = options.maxDuration;
  }
  return new Promise(resolve => {
    // Scroll is already in place, nothing to do
    if (horizontalDistanceToScroll === 0 && verticalDistanceToScroll === 0) {
      // Resolve promise with a boolean hasScrolledToPosition set to true
      resolve(true);
    }
    // Cancel existing animation if it is already running on the same element
    activeAnimations.remove(options.elementToScroll, true);
    // To cancel animation we have to store request animation frame ID
    let requestID;
    // Cancel animation handler
    const cancelAnimation = () => {
      removeListeners();
      window.cancelAnimationFrame(requestID);
      // Resolve promise with a boolean hasScrolledToPosition set to false
      resolve(false);
    };
    // Registering animation so it can be canceled if function
    // gets called again on the same element
    activeAnimations.add(options.elementToScroll, cancelAnimation);
    // Prevent user actions handler
    const preventDefaultHandler = e => e.preventDefault();
    const handler = options.cancelOnUserAction ? cancelAnimation : preventDefaultHandler;
    // If animation is not cancelable by the user, we can't use passive events
    const eventOptions = options.cancelOnUserAction ? {
      passive: true
    } : {
      passive: false
    };
    const events = ['wheel', 'touchstart', 'keydown', 'mousedown'];
    // Function to remove listeners after animation is finished
    const removeListeners = () => {
      events.forEach(eventName => {
        options.elementToScroll.removeEventListener(eventName, handler, eventOptions);
      });
    };
    // Add listeners
    events.forEach(eventName => {
      options.elementToScroll.addEventListener(eventName, handler, eventOptions);
    });
    // Animation
    const startingTime = Date.now();
    const step = () => {
      const timeDiff = Date.now() - startingTime;
      const t = timeDiff / duration;
      const horizontalScrollPosition = Math.round(initialHorizontalScroll + horizontalDistanceToScroll * options.easing(t));
      const verticalScrollPosition = Math.round(initialVerticalScroll + verticalDistanceToScroll * options.easing(t));
      if (timeDiff < duration && (horizontalScrollPosition !== x || verticalScrollPosition !== y)) {
        // If scroll didn't reach desired position or time is not elapsed
        // Scroll to a new position
        elementToScroll.scrollTo(horizontalScrollPosition, verticalScrollPosition);
        // And request a new step
        requestID = window.requestAnimationFrame(step);
      } else {
        // If the time elapsed or we reached the desired offset
        // Set scroll to the desired offset (when rounding made it to be off a pixel or two)
        // Clear animation frame to be sure
        elementToScroll.scrollTo(x, y);
        window.cancelAnimationFrame(requestID);
        // Remove listeners
        removeListeners();
        // Remove animation from the active animations coordinator
        activeAnimations.remove(options.elementToScroll, false);
        // Resolve promise with a boolean hasScrolledToPosition set to true
        resolve(true);
      }
    };
    // Start animating scroll
    requestID = window.requestAnimationFrame(step);
  });
}
/* harmony default export */ var scroll_to = (animateScrollTo);
;// CONCATENATED MODULE: ./src/assets/js/inc/components/disable-scroll.js


/**
 * left: 37, up: 38, right: 39, down: 40,
 * spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
 */
const keys = {
  37: 1,
  38: 1,
  39: 1,
  40: 1
};
let excludingSelectors = [];
function preventDefault(e) {
  if (isScrollDisabled(e)) {
    e.preventDefault();
  }
}
function preventDefaultForScrollKeys(e) {
  if (keys[e.keyCode] && isScrollDisabled(e)) {
    preventDefault(e);
    return false;
  }
}
function isScrollDisabled(e) {
  let isDisabled = true;
  if (typeof excludingSelectors === 'string') {
    excludingSelectors = excludingSelectors.split(',');
  }
  if (Array.isArray(excludingSelectors) && excludingSelectors.length > 0) {
    excludingSelectors.forEach(exclude => {
      if (typeof exclude === 'string') {
        exclude = exclude.trim();
      }
      if (e.target && (e.target.matches(exclude) || e.target.matches(exclude + ' *'))) {
        isDisabled = false;
      }
    });
  }
  return isDisabled;
}

// Modern Chrome requires { passive: false } when adding event.
let supportsPassive = false;
try {
  window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
    get() {
      supportsPassive = true;
    }
  }));
} catch (e) {}
const wheelOpt = supportsPassive ? {
  passive: false
} : false;
const wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';
const disabledElements = [];

/**
 * Disables scrolling temporarily.
 *
 * @param {Array}        elements The elements to disable.
 * @param {string|Array} excludes A string representing the selector or an array of selectors to exclude.
 */
function disableScroll(elements, excludes) {
  if (!elements || elements.length === 0) {
    elements = [window];
  }
  excludingSelectors = excludes;
  elements.forEach(el => {
    disabledElements.push(el);
    el.addEventListener('DOMMouseScroll', preventDefault, false);
    el.addEventListener(wheelEvent, preventDefault, wheelOpt);
    el.addEventListener('touchmove', preventDefault, wheelOpt);
    el.addEventListener('keydown', preventDefaultForScrollKeys, false);
  });
}

/**
 * Enables scrolling.
 *
 * @param {Array} elements The elements to enable.
 */
function enableScroll(elements) {
  if (!elements || elements.length === 0) {
    elements = [window];
  }
  excludingSelectors = [];
  elements.forEach(el => {
    el.removeEventListener('DOMMouseScroll', preventDefault, false);
    el.removeEventListener(wheelEvent, preventDefault, wheelOpt);
    el.removeEventListener('touchmove', preventDefault, wheelOpt);
    el.removeEventListener('keydown', preventDefaultForScrollKeys, false);
    const index = disabledElements.indexOf(el);
    if (index > -1) {
      disabledElements.splice(index, 1);
    }
  });
}

;// CONCATENATED MODULE: ./src/assets/js/inc/frontend-app.js


/**
 * Internal dependencies
 */





/**
 * External dependencies
 */

const {
  ScrollMagic,
  setTimeout: frontend_app_setTimeout,
  flextension
} = window;
const appPrototypes = {
  init() {
    this.isSidebarActive = false;
    this.isSearchActive = false;
    this.isNavActive = false;
    this.isScrollbarDisabled = false;
    this.menuTextMode = '';
    this.isCustomizePreview = document.body.classList.contains('customizer');
    this.initNavMenu();
    this.initToTopButton();
    const contentOverlay = document.getElementById('site-content-overlay');
    if (contentOverlay !== null) {
      contentOverlay.addEventListener('click', () => {
        this.hideNav();
        this.hideSearch();
        this.hideSidebar();
        this.emit('overlayClick');
      });
      disableScroll([contentOverlay]);
    }
    const adminBar = this.getAdminBar();
    if (adminBar !== null) {
      adminBar.style.position = 'fixed';
    }
    this.loader = document.getElementById('site-loader');
    this.scrollController = new ScrollMagic.Controller();
    this.emit('init', this);
  },
  ready() {
    this.initSidebarButton();
    this.hideLoader();
    this.emit('ready');
  },
  initDarkModeButton() {
    const darkModeButton = document.querySelector('.dark-mode-button');
    if (darkModeButton !== null) {
      darkModeButton.addEventListener('click', e => {
        e.preventDefault();
        this.changeColorScheme();
        return false;
      });
    }
  },
  changeColorScheme(color) {
    if (typeof color === 'undefined') {
      if (document.body.classList.contains('has-scheme-dark')) {
        color = 'light';
      } else {
        color = 'dark';
      }
    }
    this.isDarkMode = color === 'dark';
    if (!this.isCustomizePreview) {
      // For a logged in user, save the setting in the user's metadata.
      if (document.body.classList.contains('logged-in')) {
        const {
          fetch
        } = window;
        const searchParams = {
          action: 'evie_set_color_scheme',
          evieColor: color,
          ajaxNonce: this.settings.ajaxNonce
        };
        fetch(this.settings.ajaxUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams(searchParams),
          credentials: 'same-origin'
        });
      } else {
        // For a visitor, save it to the LocalStorage instead.
        if (color === 'auto') {
          window.localStorage.removeItem('evieDarkMode');
        } else {
          window.localStorage.setItem('evieDarkMode', this.isDarkMode ? '1' : '0');
        }
      }
    }
    document.body.classList.add('is-changing-scheme');
    if (this.isDarkMode) {
      document.body.classList.remove('has-scheme-light');
      document.body.classList.add('has-scheme-dark');
    } else {
      document.body.classList.remove('has-scheme-dark');
      document.body.classList.add('has-scheme-light');
    }
    frontend_app_setTimeout(() => {
      document.body.classList.remove('is-changing-scheme');
    }, 800);
    this.emit('colorSchemeChanged', color);
  },
  getHeader() {
    return document.getElementById('site-header');
  },
  getAdminBar() {
    return document.getElementById('wpadminbar');
  },
  showLoader() {
    if (this.loader !== null) {
      if (this.loaderTimeout) {
        clearTimeout(this.loaderTimeout);
      }
      this.loader.classList.remove('is-loaded');
      this.loader.classList.add('is-loading');
    }
  },
  hideLoader() {
    if (this.loader !== null) {
      if (this.loaderTimeout) {
        clearTimeout(this.loaderTimeout);
      }
      this.loader.classList.remove('is-loading');
      this.loaderTimeout = frontend_app_setTimeout(() => {
        this.loader.classList.add('is-loaded');
      }, 1000);
    }
  },
  initNavMenu() {
    this.initMenuSize();
    window.addEventListener('resize', debounce(() => {
      this.initMenuSize();
    }, 300));

    // Hamburger menu.
    const menuButton = document.querySelector('.menu-button');
    if (menuButton !== null) {
      menuButton.addEventListener('click', e => {
        e.preventDefault();
        if (document.body.classList.contains('nav-active')) {
          this.hideNav();
        } else {
          this.showNav();
        }
        return false;
      });
    }
    new VerticalMenu(document.getElementById('mobile-menu'));
    document.querySelectorAll('.full-menu .split-menu > li').forEach((item, index) => {
      item.style.setProperty('--evie-transition-delay', (index * 0.1).toFixed(1) + 's');
      const menuText = item.querySelector('.menu-text');
      if (menuText !== null) {
        menuText.dataset.title = menuText.textContent;
      }
    });
    document.querySelectorAll('.full-menu .menu-widgets .widget').forEach((item, index) => {
      item.style.setProperty('--evie-transition-delay', (index * 0.2).toFixed(1) + 's');
    });
    this.initLiveSearch();
    this.initDarkModeButton();
    if (document.body.classList.contains('has-sticky-menu') || this.isCustomizePreview) {
      this.initStickyMenu();
    }
  },
  initMenuSize() {
    if (window.innerWidth >= this.settings.desktopMenuBreakpoint) {
      document.body.classList.remove('mobile-menu');
      document.body.classList.add('desktop-menu');
    } else {
      document.body.classList.remove('desktop-menu');
      document.body.classList.add('mobile-menu');
    }
  },
  initStickyMenu() {
    const header = this.getHeader();
    if (header !== null) {
      let lastScroll = 0;
      const stickyHeader = () => {
        if (document.documentElement.classList.contains('is-scrolling-disabled') || document.body.classList.contains('nav-active')) {
          return;
        }
        const scroll = window.pageYOffset;
        if (scroll > header.offsetTop + header.offsetHeight) {
          header.classList.add('is-sticky');
        } else {
          header.classList.remove('is-sticky', 'is-menu-visible');
        }
        if (scroll > lastScroll) {
          header.classList.remove('is-menu-visible');
        } else if (scroll > 0) {
          header.classList.add('is-menu-visible');
        }
        lastScroll = scroll;
      };
      window.addEventListener('scroll', throttle(() => {
        stickyHeader();
      }, 300));
      window.addEventListener('resize', debounce(() => {
        stickyHeader();
      }, 300));
      stickyHeader();
    }
  },
  initToTopButton() {
    // Back to Top button.
    const toTop = document.getElementById('back-to-top');
    if (toTop !== null) {
      toTop.addEventListener('click', e => {
        e.preventDefault();
        this.scrollTo(0);
      });
      let lastScroll = 0;
      window.addEventListener('scroll', throttle(() => {
        const scroll = window.scrollY;
        if (scroll > 1000) {
          if (scroll < lastScroll) {
            toTop.classList.add('active');
          } else {
            toTop.classList.remove('active');
          }
        } else {
          toTop.classList.remove('active');
        }
        lastScroll = scroll;
      }, 300));
    }
  },
  initLiveSearch() {
    if (flextension) {
      flextension.on('liveSearch.showResults', () => {
        document.body.classList.add('show-search-results');
      });
      flextension.on('liveSearch.hideResults', () => {
        document.body.classList.remove('show-search-results');
      });
    }
    document.body.addEventListener('keyup', e => {
      if (e.key === 'Escape') {
        this.hideSearch();
        const searchButton = document.querySelector('.live-search-button');
        if (searchButton !== null) {
          searchButton.focus();
        }
      }
    });
    document.querySelectorAll('.live-search-button').forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();
        if (document.body.classList.contains('search-active')) {
          this.hideSearch();
        } else {
          this.showSearch();
        }
        return false;
      });
    });
    document.querySelectorAll('.close-search-button').forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();
        this.hideSearch();
      });
    });
  },
  initSidebarButton() {
    const sidebarButton = document.querySelector('.menu-item-sidebar .sidebar-button');
    if (sidebarButton !== null) {
      const toggleSidebar = () => {
        if (document.body.classList.contains('desktop-menu')) {
          if (document.body.classList.contains('top-menu')) {
            document.body.classList.toggle('menu-widgets-active');
          } else if (document.body.classList.contains('sidebar-active')) {
            this.hideSidebar();
          } else {
            this.showSidebar();
          }
        } else if (document.body.classList.contains('sidebar-active')) {
          this.hideSidebar();
        } else {
          this.showSidebar();
        }
      };
      sidebarButton.addEventListener('click', e => {
        e.preventDefault();
        toggleSidebar();
        return false;
      });
      document.addEventListener('click', e => {
        if (document.body.classList.contains('menu-widgets-active') && !e.target.closest('.menu-widgets, .sidebar-button')) {
          document.body.classList.remove('menu-widgets-active');
        }
      });
    }
  },
  disableScroll(selector, excludes) {
    const elements = [window];
    document.querySelectorAll('.main-menu, #wpadminbar').forEach(el => {
      elements.push(el);
    });
    if (typeof selector === 'string') {
      document.querySelectorAll(selector).forEach(el => {
        elements.push(el);
      });
    } else if (typeof selector === 'object') {
      elements.push(selector);
    }

    // Disable scrolling for admin bar.
    if (this.getAdminBar() !== null && window.innerWidth <= 600) {
      document.body.style.removeProperty('--evie-scrollbar-width');
      document.body.style.height = '100%';
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.height = null;
      document.body.style.overflow = null;
      const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
      if (scrollbarWidth > 0) {
        document.body.style.setProperty('--evie-scrollbar-width', scrollbarWidth + 'px');
        document.body.classList.add('has-scrollbar');
      }
    }
    disableScroll(elements, excludes);
    document.documentElement.classList.add('is-scrolling-disabled');
    this.isScrollbarDisabled = true;
  },
  enableScroll(selector) {
    if (!selector && !this.isScrollbarDisabled) {
      return;
    }
    document.body.style.height = null;
    document.body.style.overflow = null;
    document.documentElement.classList.remove('is-scrolling-disabled');
    document.body.style.removeProperty('--evie-scrollbar-width');
    document.body.classList.remove('has-scrollbar');
    const elements = [window];
    document.querySelectorAll('.main-menu, #wpadminbar').forEach(el => {
      elements.push(el);
    });
    if (typeof selector === 'string') {
      document.querySelectorAll(selector).forEach(el => {
        elements.push(el);
      });
    } else if (typeof selector === 'object') {
      elements.push(selector);
    }
    enableScroll(elements);
    this.isScrollbarDisabled = false;
  },
  closeActiveSidebar() {
    if (typeof this.onActiveSidebarClose === 'function') {
      this.onActiveSidebarClose();
      this.emit('activeSidebarClose');
      this.onActiveSidebarClose = null;
    }
  },
  showNav() {
    if (this.isNavActive) {
      return;
    }
    this.closeActiveSidebar();
    if (document.body.classList.contains('desktop-menu') && document.body.classList.contains('top-menu')) {
      this.showSidebar();
    } else {
      this.hideSidebar();
    }
    document.body.classList.add('nav-active');
    if (document.body.classList.contains('mobile-menu')) {
      this.disableScroll(false, '#side-navigation');
      if (document.body.classList.contains('menu-text-light')) {
        this.menuTextMode = 'light';
        document.body.classList.remove('menu-text-light');
      } else if (document.body.classList.contains('menu-text-dark')) {
        this.menuTextMode = 'dark';
        document.body.classList.remove('menu-text-dark');
      }
    }
    this.isNavActive = true;
    this.emit('afterShowNav', this);
  },
  hideNav() {
    if (!this.isNavActive) {
      return;
    }
    this.closeActiveSidebar();
    document.body.classList.remove('nav-active');
    if (this.menuTextMode) {
      document.body.classList.add('menu-text-' + this.menuTextMode);
      this.menuTextMode = '';
    }
    this.enableScroll();
    this.isNavActive = false;
    this.emit('afterHideNav');
  },
  showSearch() {
    if (this.isSearchActive) {
      return;
    }
    this.closeActiveSidebar();
    if (document.body.classList.contains('desktop-menu') && document.body.classList.contains('top-menu')) {
      this.hideNav();
    }
    if (this.browser.xs || this.browser.sm) {
      this.disableScroll(false, '.live-search-results');
    }
    this.onActiveSidebarClose = this.hideSearch;
    document.body.classList.add('search-active');
    frontend_app_setTimeout(() => {
      const input = document.querySelector('.main-search-bar input[name="s"]');
      if (input !== null) {
        input.focus();
      }
    }, 500);
    this.isSearchActive = true;
    this.emit('afterShowSearch');
  },
  hideSearch() {
    if (!this.isSearchActive) {
      return;
    }
    this.enableScroll();
    document.body.classList.remove('search-active');
    this.isSearchActive = false;
    this.emit('afterHideSearch');
  },
  showSidebar() {
    if (this.isSidebarActive) {
      return;
    }
    this.closeActiveSidebar();
    let delay = 100;
    if (document.body.classList.contains('desktop-menu')) {
      if (this.isNavActive === true && document.body.classList.contains('full-menu')) {
        delay = 400;
      }
      this.hideNav();
    } else {
      this.hideSearch();
    }
    this.disableScroll(false, '.widget-wrapper');
    frontend_app_setTimeout(() => {
      document.body.classList.add('sidebar-active');
      this.onActiveSidebarClose = this.hideSidebar;
      this.isSidebarActive = true;
      this.emit('afterShowSidebar');
    }, delay);
  },
  hideSidebar() {
    if (!this.isSidebarActive) {
      return;
    }
    this.enableScroll();
    document.body.classList.remove('sidebar-active');
    this.isSidebarActive = false;
    this.emit('afterHideSidebar');
  },
  scrollTo(target, options) {
    const defaults = {
      duration: Math.floor(window.scrollY * 0.5),
      maxDuration: 600,
      minDuration: 120,
      verticalOffset: 0,
      onComplete: () => {}
    };
    if (typeof options === 'function') {
      const callback = options;
      options = {
        onComplete: callback
      };
    }
    const settings = Object.assign(defaults, options || {});
    let verticalOffset = settings.verticalOffset;
    settings.verticalOffset = y => {
      if (y > 0) {
        const adminBar = this.getAdminBar();
        if (adminBar !== null) {
          verticalOffset -= adminBar.offsetHeight;
        }
        const header = this.getHeader();
        if (header !== null) {
          verticalOffset -= header.offsetHeight;
        }
      }
      return verticalOffset;
    };
    if (typeof target === 'string') {
      target = document.querySelector(target);
    }
    scroll_to(target, settings).then(completed => {
      if (typeof settings.onComplete === 'function') {
        settings.onComplete(completed);
      }
    });
  }
};
Object.keys(appPrototypes).forEach(proto => {
  class_app.prototype[proto] = appPrototypes[proto];
});

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
  ScrollMagic: posts_ScrollMagic,
  evieApp,
  flextension: posts_flextension
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
  const scene = new posts_ScrollMagic.Scene({
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
  if (posts_flextension) {
    posts_flextension.emit('ready', content);
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
        if (posts_flextension) {
          posts_flextension.emit('ready', post);
        }
        evieApp.emit('ready', post);
      });
    }
  } else {
    if (posts_flextension) {
      posts_flextension.emit('ready', ajaxPage.list);
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
if (posts_flextension) {
  // Quick View Lightbox.
  posts_flextension.on('afterQuickViewLoaded', (context, content) => {
    if (!content) {
      return;
    }
    evieApp.emit('ready', content);
  });
  posts_flextension.on('afterQuickViewOpen', (context, lightbox) => {
    if (evieApp.browser.xl) {
      evieApp.disableScroll(window, '.quick-view-content ');
    }
  });
  posts_flextension.on('afterQuickViewClose', () => {
    if (evieApp.browser.xl) {
      evieApp.enableScroll();
    }
  });
}
;// CONCATENATED MODULE: ./src/assets/js/main.js
/**
 * Main Application
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */






/**
 * External dependencies
 */

const {
  ScrollMagic: main_ScrollMagic,
  flextension: main_flextension,
  evieApp: main_evieApp,
  IntersectionObserver: main_IntersectionObserver
} = window;

/**
 * Initializes the single post content.
 *
 * @param {Element} content The content to initialize.
 */
function initSingle(content) {
  if (main_evieApp.browser.lg || main_evieApp.browser.xl) {
    content.querySelectorAll('.parallax-background .post-thumbnail img').forEach(el => {
      ParallaxBackground(el);
    });
  }
  content.querySelectorAll('.page-header.effect-parallax img').forEach(el => {
    ParallaxBackground(el);
  });
  if (document.body.classList.contains('singular')) {
    content.querySelectorAll('.single-entry-footer .tags-links .terms-list').forEach(list => {
      Scroller(list);
    });
    if ('#post-authors' === window.location.hash) {
      setTimeout(() => {
        main_evieApp.scrollTo(window.location.hash);
      }, 1000);
    }
    const commentSection = content.querySelector('#comments');
    if (commentSection !== null) {
      const commentsList = commentSection.querySelector('.evie-container');
      if (window.location.hash === '#comments' || window.location.hash === '#respond') {
        slideDown(commentsList, {
          callback: () => {
            commentSection.classList.add('is-visible');
            main_evieApp.scrollTo(window.location.hash);
          }
        });
      }
      const toggleButton = commentSection.querySelector('.toggle-comments');
      if (toggleButton !== null) {
        toggleButton.addEventListener('click', () => {
          slideToggle(commentsList, {
            callback: () => {
              commentSection.classList.toggle('is-visible');
            }
          });
        });
      }
      commentSection.querySelectorAll('.comment-form').forEach(el => {
        el.addEventListener('submit', e => {
          let valid = true;
          el.querySelectorAll('.evie-text-field').forEach(field => {
            const input = field.querySelector('.evie-input');
            if (input === null) {
              return;
            }
            if (input.getAttribute('required') && !input.value) {
              field.classList.add('evie-required');
              valid = false;
            } else {
              field.classList.remove('evie-required');
            }
          });
          if (true !== valid) {
            e.preventDefault();
            return false;
          }
        });
      });
    }
  }
  main_evieApp.emit('afterInitSingle');
}

/**
 * Initializes custom elements.
 *
 * @param {Element} content The cotent container of the elements.
 */
function initElements(content) {
  // Validate input fields.
  const validate = el => {
    el.querySelectorAll('.evie-input').forEach(input => {
      if (el.classList.contains('evie-required') && input.value !== '') {
        el.classList.remove('evie-required');
      }
      if ('email' === input.getAttribute('type')) {
        validateEmail(el);
      }
    });
  };

  // Validate email fields.
  const validateEmail = el => {
    el.querySelectorAll('input[type="email"]').forEach(input => {
      if (input.value !== '') {
        if (!input.value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
          el.classList.add('evie-required');
          input.classList.add('evie-invalid');
          return;
        }
      }
      el.classList.remove('evie-required');
      input.classList.remove('evie-invalid');
    });
  };
  const addFloatingclass = el => {
    if (el.classList.contains('evie-floating-label')) {
      el.classList.add('evie-float-above');
    }
  };
  const removeFloatingclass = el => {
    if (el.classList.contains('evie-floating-label')) {
      const input = el.querySelector('.evie-input');
      if (input !== null && input.value === '') {
        el.classList.remove('evie-float-above');
      }
    }
  };
  content.querySelectorAll('.evie-text-field').forEach(field => {
    if (field.classList.contains('evie-floating-label')) {
      const input = field.querySelector('.evie-input');
      if (input !== null && input.value !== '') {
        field.classList.add('evie-float-above');
      }
    }
    validate(field);
    field.addEventListener('focusin', () => {
      field.classList.add('evie-focused');
      addFloatingclass(field);
      validate(field);
    });
    field.addEventListener('focusout', () => {
      field.classList.remove('evie-focused');
      removeFloatingclass(field);
      validate(field);
    });
  });
}

/**
 * Initializes widgets.
 *
 * @param {Element} content The content to initialize.
 */
function initWidgets(content) {
  // Navigation Menu widget
  content.querySelectorAll('.vertical-menu').forEach(el => {
    new VerticalMenu(el);
  });
  content.querySelectorAll('.wp-block-table.is-style-evie-list').forEach(element => {
    element.querySelectorAll('tr').forEach((row, index) => {
      row.style.setProperty('--evie-transition-delay', (index * 0.5).toFixed(1) + 's');
    });
    const observer = new main_IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          element.classList.add('evie-active');
          observer.unobserve(element);
        }
      });
    });
    observer.observe(element);
  });
}

/**
 * Adds a scrolling parallax effect to columns within a Grid.
 *
 * @version 1.0.0
 * @param {Element}  element  Target element.
 * @param {Object}   options  Options for the function.
 * @param {Function} callback Callback function.
 */
function ParallaxBackground(element, options, callback) {
  if (!element) {
    return;
  }
  const defaults = {
    direction: 'down',
    parallax: 0.5,
    autoHeight: false,
    zoom: false
  };
  const settings = Object.assign(defaults, options || {});
  if (settings.parallax === false) {
    return;
  }
  if (element.dataset.parallaxBackground) {
    return;
  }
  element.dataset.parallaxBackground = true;
  const container = element.parentElement;
  const update = () => {
    const ratio = 100 * settings.parallax;
    container.style.overflow = 'hidden';
    if (settings.autoHeight === true) {
      const height = element.offsetHeight;
      container.style.height = height - height * ratio / 100 + 'px';
    }
    element.style.position = 'absolute';
    element.style.left = '0';
    element.style.transition = 'none';
    if (settings.autoHeight === true && settings.direction === 'down') {
      element.style.top = ratio * -1 + '%';
    }
  };
  const animate = progress => {
    const percent = progress * 100;
    const percentTranslate = settings.direction === 'down' ? percent * settings.parallax : percent * settings.parallax * -1;
    let cssTransform = 'translate3d(0, ' + percentTranslate + '%, 0)';
    if (settings.zoom) {
      const scale = 1 + 0.2 * percent / 100;
      cssTransform += ' scale(' + scale + ', ' + scale + ')';
    }
    element.style.transform = cssTransform;
  };
  update();
  const scene = new main_ScrollMagic.Scene({
    triggerElement: container,
    triggerHook: 'onLeave',
    duration: '100%'
  });
  scene.on('progress', e => {
    animate(e.progress);
  });
  scene.addTo(main_evieApp.scrollController);
  window.addEventListener('resize', debounce(() => {
    update();
  }, 300));
  if (typeof callback === 'function') {
    callback(element, settings);
  }
}

/**
 * Executes the script after the content is loaded.
 *
 * @param {Object}  context The Evie app instance.
 * @param {Element} content The HTML element of the content. Or Null if this is the initial page load.
 */
main_evieApp.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  initSingle(content);
  initElements(content);
  initWidgets(content);
});

/** Single Page */
/**
 * Disposes the featured media after the post and page updated via AJAX.
 */
function disposeFeaturedMedia() {
  if (!main_flextension) {
    return;
  }
  const {
    featuredMediaPlayers,
    featuredSliders
  } = main_flextension;
  if (featuredMediaPlayers) {
    if (Array.isArray(featuredMediaPlayers)) {
      featuredMediaPlayers.forEach((video, index) => {
        if (!document.body.contains(video.element)) {
          video.dispose();
          featuredMediaPlayers.splice(index, 1);
        }
      });
    }
  }
  if (featuredSliders) {
    if (Array.isArray(featuredSliders)) {
      featuredSliders.forEach((slider, index) => {
        if (!document.body.contains(slider.el)) {
          if (slider.hasOwnProperty('destroy')) {
            slider.destroy();
          }
          featuredSliders.splice(index, 1);
        }
      });
    }
  }
}
let showLoader = true;
main_evieApp.on('singlePage.linkClick', (context, link, singlePage) => {
  const filterNavigation = link.closest('.posts-filters');
  if (filterNavigation) {
    showLoader = false;
    singlePage.scrollTarget = null;
    const list = link.closest('.terms-list');
    if (list !== null) {
      list.querySelectorAll('.is-selected').forEach(item => {
        item.classList.remove('is-selected');
      });
      link.parentElement.classList.add('is-selected');
    } else {
      link.parentElement.classList.toggle('is-selected');
    }
  }
});
main_evieApp.on('singlePage.beforeNavigate', () => {
  if (main_flextension && main_flextension.lightbox) {
    main_flextension.lightbox.closeAll();
  }
  if (main_evieApp.isNavActive) {
    main_evieApp.hideNav();
  } else {
    main_evieApp.closeActiveSidebar();
  }
});
main_evieApp.on('singlePage.beforeGetPage', (context, singlePage) => {
  if (showLoader) {
    main_evieApp.showLoader();
  }
});
main_evieApp.on('singlePage.afterGetPage', () => {
  main_evieApp.hideLoader();
});
main_evieApp.on('singlePage.afterUpdatePage', (context, content, singlePage) => {
  singlePage.scrollTarget = singlePage.container;
  main_evieApp.initMenuSize();
  disposeFeaturedMedia();
  main_evieApp.emit('ready', content);
  if (main_flextension) {
    main_flextension.emit('ready', content);
  }
});
main_evieApp.on('megaMenu.afterDisplayPosts', (context, content) => {
  main_evieApp.emit('singlePage.initLinks', content);
});
main_evieApp.on('ajaxPagination.afterUpdatePosts', (context, posts, ajaxPage) => {
  if (ajaxPage.updateMode === 'replace') {
    disposeFeaturedMedia();
  }
});
if (main_flextension) {
  // Initialize the live search results.
  main_flextension.on('liveSearch.afterSearchResultsLoad', (context, content) => {
    main_evieApp.emit('singlePage.initLinks', content);
  });

  // Initialize the Nextend Social Login and Register plugin buttons.
  main_flextension.on('afterLoginFormLoaded', (context, content) => {
    if (!content) {
      return;
    }
    let targetWindow = window._targetWindow || 'prefer-popup',
      lastPopup = false;
    document.querySelectorAll(' a[data-plugin="nsl"][data-action="connect"], a[data-plugin="nsl"][data-action="link"]').forEach(buttonLink => {
      buttonLink.addEventListener('click', e => {
        if (lastPopup && !lastPopup.closed) {
          e.preventDefault();
          lastPopup.focus();
        } else {
          let href = buttonLink.href,
            success = false;
          if (href.indexOf('?') !== -1) {
            href += '&';
          } else {
            href += '?';
          }
          const redirectTo = buttonLink.dataset.redirect;
          if (redirectTo === 'current') {
            href += 'redirect=' + encodeURIComponent(window.location.href) + '&';
          } else if (redirectTo && redirectTo !== '') {
            href += 'redirect=' + encodeURIComponent(redirectTo) + '&';
          }
          if (targetWindow === 'prefer-popup') {
            lastPopup = NSLPopup(href + 'display=popup', 'nsl-social-connect', buttonLink.dataset.popupwidth, buttonLink.dataset.popupheight);
            if (lastPopup) {
              success = true;
              e.preventDefault();
            }
          } else if (targetWindow === 'prefer-new-tab') {
            const newTab = window.open(href + 'display=popup', '_blank');
            if (newTab) {
              if (window.focus) {
                newTab.focus();
              }
              success = true;
              e.preventDefault();
            }
          }
          if (!success) {
            window.location = href;
            e.preventDefault();
          }
        }
      });
    });
  });
  main_flextension.on('afterFollowersLoaded', (context, content) => {
    if (!content) {
      return;
    }
    main_evieApp.emit('ready', content);
  });
}
/******/ })()
;