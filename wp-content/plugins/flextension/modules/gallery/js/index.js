/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/modules/gallery/blocks/waterfall-gallery/waterfall.js
const {
  flextension,
  HTMLElement,
  imagesLoaded
} = window;

/**
 * Creates a waterfall gallery.
 *
 * @param {Node}   element The gallery element.
 * @param {Object} options The gallery options.
 */
function Waterfall(element, options) {
  if (element instanceof HTMLElement) {
    this.container = element;
    this.containerClass = element.className;
  } else {
    this.containerClass = element;
    this.container = document.querySelector(element);
  }
  this.options = Object.assign({
    items: '',
    columns: 3
  }, options || {});
  this.init();
}

/**
 * Initializes the gallery.
 */
Waterfall.prototype.init = function init() {
  this.initItems();
  imagesLoaded(this.container, () => {
    this.positionItems();
  });
  window.addEventListener('resize', flextension.debounce(() => {
    this.positionItems();
  }, 300));
};

/**
 * Initializes the items.
 */
Waterfall.prototype.initItems = function () {
  if (!this.options.items) {
    this.items = this.container.children;
  } else if (typeof this.options.items === 'string' && this.options.items !== '') {
    this.items = this.container.querySelectorAll(this.options.items);
  } else if (Array.isArray(this.options.items)) {
    this.items = this.options.items;
  } else {
    this.items = [];
  }
  for (let i = 0; i < this.items.length; i++) {
    this.items[i].style.position = 'absolute';
  }
};

/**
 * Updates the items and repositions them.
 *
 * @param {Object} options The gallery options.
 */
Waterfall.prototype.update = function (options) {
  this.options = Object.assign(this.options, options || {});
  this.initItems();
  imagesLoaded(this.container, () => {
    this.positionItems();
  });
};

/**
 * Calculates the width of a column.
 *
 * @return {number} The width of a column in the grid.
 */
Waterfall.prototype.columnWidth = function () {
  return this.items[0].getBoundingClientRect().width;
};

/**
 * Initializes an array of empty columns.
 *
 * @return {Array} An array list of columns.
 */
Waterfall.prototype.getColumns = function setup() {
  const width = this.container.getBoundingClientRect().width;
  const colWidth = this.columnWidth();
  let numCols = Math.floor(width / colWidth) || 1;
  const columns = [];
  if (this.options.columns && numCols > this.options.columns) {
    numCols = this.options.columns;
  }
  for (let i = 0; i < numCols; i++) {
    columns[i] = {
      height: 0,
      index: i
    };
  }
  return columns;
};

/**
 * Gets the next available column.
 *
 * @param {Array}  columns Array list of columns.
 * @param {number} index   The index of dom element.
 * @return {Object} The next available column.
 */
Waterfall.prototype.nextColumn = function (columns, index) {
  return columns[index % columns.length];
};

/**
 * Positions each item in the grid, based on their corresponding column's height
 * and index then stretches the container to the height of the grid.
 */
Waterfall.prototype.positionItems = function () {
  if (this.items.length === 0) {
    return;
  }
  const columns = this.getColumns();
  const colWidth = this.columnWidth();
  let maxHeight = 0;
  for (let i = 0; i < this.items.length; i++) {
    const column = this.nextColumn(columns, i);
    const item = this.items[i];
    const left = column.index * colWidth + 'px';
    const top = column.height + 'px';
    item.style.transform = 'translate(' + left + ', ' + top + ')';
    column.height += item.getBoundingClientRect().height;
    if (column.height > maxHeight) {
      maxHeight = column.height;
    }
  }
  this.container.style.height = maxHeight + 'px';
  this.container.classList.add('flext-waterfall-initialized');
};
/* harmony default export */ var waterfall = (Waterfall);
;// CONCATENATED MODULE: ./src/modules/gallery/js/index.js
/**
 * Gallery Blocks
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */

const {
  flextension: js_flextension
} = window;
js_flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-block-gallery:not(.flext-gallery-initialized)').forEach(gallery => {
    const waterFallGallery = gallery.querySelector('.flext-waterfall-gallery');
    if (waterFallGallery !== null) {
      new waterfall(waterFallGallery, {
        columns: parseInt(gallery.dataset.columns, 10) || 3
      });
      js_flextension.lightboxGallery(waterFallGallery);
    }
    const carousel = gallery.querySelector('.flext-carousel-gallery');
    if (carousel !== null) {
      const options = {};
      const gutters = carousel.dataset.gutters;
      if (parseInt(carousel.dataset.slidesPerView, 10) === 1) {
        options.parallax = {
          enabled: true,
          speed: gutters ? '25%' : '50%'
        };
      }
      const slidesPerView = carousel.dataset.centeredSlides && parseInt(carousel.dataset.slidesPerView, 10) === 1 ? 'auto' : carousel.dataset.slidesPerView;
      let spaceBetween = 0;
      switch (gutters) {
        case 's':
          spaceBetween = 20;
          break;
        case 'm':
          spaceBetween = 40;
          break;
        case 'l':
          spaceBetween = 60;
          break;
        case 'xl':
          spaceBetween = 80;
          break;
      }
      options.breakpoints = {
        768: {
          slidesPerView: slidesPerView !== 'auto' ? Math.min(slidesPerView, 2) : slidesPerView,
          spaceBetween: Math.min(spaceBetween, 30)
        },
        1024: {
          slidesPerView: slidesPerView !== 'auto' ? Math.min(slidesPerView, 3) : slidesPerView,
          spaceBetween: Math.min(spaceBetween, 40)
        },
        1200: {
          slidesPerView,
          spaceBetween: Math.min(spaceBetween, 50)
        },
        1650: {
          slidesPerView,
          spaceBetween
        }
      };
      if (slidesPerView === 'auto') {
        carousel.classList.add('flext-carousel-center');
      }
      new js_flextension.carousel(carousel, options);
      js_flextension.lightboxGallery(carousel);
    }
    gallery.classList.add('flext-gallery-initialized');
  });
});
/******/ })()
;