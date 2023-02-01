/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension
 *
 * The core class of the Flextension plugin.
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Flextension class
 */
class Flextension {
  /**
   * Initializes the class.
   */
  constructor() {
    this.settings = {};
    if (typeof window.flextensionSettings === 'object') {
      this.settings = Object.assign(this.settings, window.flextensionSettings);
    }
    this.eventsListeners = {};
    this.isReady = false;
    this.emit('init');
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

    // Print out Credits.
    if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
      window.console.log.apply(window.console, ['\n %c Made with ♥ by Wyde Themes %c %c https://wydethemes.com/ %c %c \n', 'color: #fff; background: #e43333; padding: 5px 0;', 'background: #131419; padding: 5px 0;', 'color: #fff; background: #1c1c1c; padding: 5px 0;', 'background: #131419; padding: 5px 0;', 'background: #fff; padding: 5px 0;']);
    } else if (window.console) {
      window.console.log('Made with love ♥ Wyde Themes - https://wydethemes.com/');
    }
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
  throttle(callback, delay, options) {
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
      for (var _len3 = arguments.length, arguments_ = new Array(_len3), _key3 = 0; _key3 < _len3; _key3++) {
        arguments_[_key3] = arguments[_key3];
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
  debounce(callback, delay, options) {
    const {
      atBegin = false
    } = options || {};
    return this.throttle(callback, delay, {
      debounceMode: atBegin !== false
    });
  }
  ready() {
    this.emit('ready');
  }
}
window.flextension = new Flextension();
/******/ })()
;