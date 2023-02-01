/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 6436:
/***/ (function() {

/**
 * Creates a Counter block.
 *
 * @param {Node}   element The gallery element.
 * @param {Object} options The gallery options.
 */
function Counter(element, options) {
  if (element instanceof window.HTMLElement) {
    this.element = element;
  } else {
    this.element = document.querySelector(element);
  }
  if (!this.element) {
    return;
  }
  this.options = Object.assign({
    start: this.element.dataset.countStart || 0,
    end: this.element.dataset.countEnd || 100,
    duration: this.element.dataset.countDuration || 1000,
    delay: this.element.dataset.countDelay || 0
  }, options || {});
  this.digits = '';
  const matches = /\d+[\.\,](\d+)?/g.exec(this.options.end.toString());
  if (matches && matches.length > 1) {
    this.digits = matches[1];
  }
  setTimeout(() => {
    this.start();
  }, this.options.delay);
}

/**
 * Animates the counter.
 */
Counter.prototype.start = function () {
  let startTimestamp = null;
  const animate = timestamp => {
    if (!startTimestamp) {
      startTimestamp = timestamp;
    }
    const progress = Math.min((timestamp - startTimestamp) / parseFloat(this.options.duration), 1);
    const number = progress * (parseFloat(this.options.end) - parseFloat(this.options.start)) + parseFloat(this.options.start);
    const formatOptions = {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    };
    if (this.digits && this.digits.length > 0) {
      formatOptions.minimumFractionDigits = this.digits.length;
      formatOptions.maximumFractionDigits = this.digits.length;
    }
    this.element.innerText = number.toLocaleString(undefined, formatOptions);
    if (progress < 1) {
      window.requestAnimationFrame(animate);
    }
  };
  window.requestAnimationFrame(animate);
};
const {
  flextension,
  IntersectionObserver
} = window;

/**
 * Initializes the counter blocks.
 *
 * @param {Element} content The content element.
 */
function initCounterBlocks(content) {
  content.querySelectorAll('.flext-block-counter:not(.flext-counter-initialized)').forEach(element => {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counter = element.querySelector('.flext-block-counter-number');
          if (counter !== null) {
            new Counter(counter);
          }
          observer.unobserve(element);
        }
      });
    }, {
      threshold: 0.25
    });
    observer.observe(element);
  });
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  initCounterBlocks(content);
});

/***/ }),

/***/ 6820:
/***/ (function() {

const {
  flextension
} = window;
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-block-post-carousel').forEach(block => {
    const carousel = block.querySelector('.flext-post-carousel');
    if (carousel !== null) {
      const getColumns = (columns, max) => {
        return Math.min(parseInt(columns, 10) || max, max);
      };
      new flextension.carousel(carousel, {
        spaceBetween: parseInt(carousel.dataset.spaceBetween, 10) || 0,
        breakpoints: {
          768: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 2),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 2)
          },
          1024: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 3),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 3)
          },
          1200: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 4),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 4)
          },
          1600: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 5),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 5)
          },
          1920: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 6),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 6)
          }
        }
      });
    }
  });
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/* harmony import */ var _blocks_counter_init__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6436);
/* harmony import */ var _blocks_counter_init__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_blocks_counter_init__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _blocks_post_carousel_init__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6820);
/* harmony import */ var _blocks_post_carousel_init__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_blocks_post_carousel_init__WEBPACK_IMPORTED_MODULE_1__);
/**
 * Flextension Blocks
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */


}();
/******/ })()
;