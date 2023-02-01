/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Lightbox
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension,
  HTMLElement,
  requestAnimationFrame
} = window;
const activeItems = [];

/**
 * Creates a lightbox.
 *
 * @param {Node|string|Function} content The content to show in the lightbox.
 * @param {?Object}              options The lighbox options.
 */
function lightbox(content, options) {
  this.options = Object.assign({
    className: '',
    open: true,
    fullscreen: true,
    closeButton: true,
    onBeforeOpen: () => {},
    onOpen: () => {},
    onBeforeClose: () => {},
    onClose: () => {},
    onInit: () => {}
  }, options);
  this.isLoaded = false;
  this.content = this.parseContent(content);
  if (this.content === null) {
    return;
  }
  this.element = document.createElement('div');
  this.addClass('flext-lightbox');

  // Close lightbox when clicking the background
  if (this.options.className) {
    const classNames = this.options.className.split(' ');
    classNames.forEach(name => {
      this.addClass(name);
    });
  }
  document.body.appendChild(this.element);
  this.container = document.createElement('div');
  this.container.classList.add('flext-lightbox-container');
  this.container.setAttribute('role', 'dialog');
  this.element.appendChild(this.container);
  this.element.addEventListener('click', e => {
    if (e.target === this.element || e.target === this.container) {
      this.close();
    }
  });
  if (this.options.closeButton !== false) {
    this.closeButton = document.createElement('button');
    this.closeButton.classList.add('flext-lightbox-close', 'flext-ico-cancel');
    if (this.options.fullscreen) {
      this.element.appendChild(this.closeButton);
    } else {
      this.container.appendChild(this.closeButton);
    }
    this.closeButton.addEventListener('click', () => {
      this.close();
    });
  }
  if (this.element && this.element.ownerDocument.contains(this.content)) {
    // Add a placeholder before the content.
    this.placeholder = document.createElement('div');
    this.placeholder.classList.add('flext-lightbox-placeholder');
    this.content.parentElement.insertBefore(this.placeholder, this.content);
  }

  // Move content into lightbox container.
  this.container.appendChild(this.content);

  // Add special treatment class when it only contains an image, a video or iframe.
  // This class is necessary to center the image, video or iframe.
  if (containsTag(this.container, 'IMG') === true) {
    this.addClass('flext-lightbox-img');
  }
  if (containsTag(this.container, 'VIDEO') === true) {
    this.addClass('flext-lightbox-video');
  }
  if (containsTag(this.container, 'AUDIO') === true) {
    this.addClass('flext-lightbox-audio');
  }
  if (containsTag(this.container, 'IFRAME') === true) {
    this.addClass('flext-lightbox-iframe');
  }
  if (this.options.fullscreen) {
    this.addClass('flext-lightbox-fullscreen');
  }
  if (typeof this.options.onInit === 'function') {
    this.options.onInit(this);
  }
  if (this.options.open === true) {
    // Wait a while to ensure that the class change triggers the animation
    setTimeout(() => {
      this.open();
    }, 100);
  }
}

/**
 * Validates and converts content.
 *
 * @param {Node | string} content
 * @return {Array} content - Validated content.
 */
lightbox.prototype.parseContent = function (content) {
  const isString = typeof content === 'string';
  const isHTMLElement = content instanceof HTMLElement === true;
  if (isString === false && isHTMLElement === false) {
    throw new Error('Content must be a DOM element/node or string');
  }
  this.isLoaded = true;
  if (isString) {
    if (content && content.startsWith('#')) {
      let el = document.querySelector(content);
      if (el === null) {
        el = document.createElement('div');
        el.setAttribute('id', content.replace('#', ''));
        el.classList.add('flext-lightbox-content-placeholder');
        let container = document.getElementById('site-content');
        if (container === null) {
          container = document.body;
        }
        container.append(el);
        this.isLoaded = false;
      }
      return el;
    }
    // Convert HTML string to DOM element.
    return toElement(content);
  }

  // HTMLElement
  return content;
};

/**
 * Fires a callback function before opening a lightbox.
 */
lightbox.prototype.beforeOpen = function () {
  if (typeof this.options.onBeforeOpen === 'function') {
    return this.options.onBeforeOpen(this);
  }
  return true;
};

/**
 * Opens a lightbox.
 */
lightbox.prototype.open = function () {
  if (this.beforeOpen() === false) {
    return;
  }
  this.content.classList.add('flext-lightbox-content');
  addKeyDownEvent();
  requestAnimationFrame(() => {
    this.addClass('flext-lightbox-visible');
    if (typeof this.options.onOpen === 'function') {
      this.options.onOpen(this);
    }
    flextension.emit('lightbox.open', this);
  });
  activeItems.push(this);
};

/**
 * Fires a callback function before closing a lightbox.
 */
lightbox.prototype.beforeClose = function () {
  if (typeof this.options.onBeforeClose === 'function') {
    return this.options.onBeforeClose(this);
  }
  return true;
};

/**
 * Closes a lightbox by fading the element out and by removing the element from the DOM.
 */
lightbox.prototype.close = function () {
  if (this.beforeClose() === false) {
    return;
  }
  this.removeClass('flext-lightbox-visible');
  setTimeout(() => {
    this.content?.classList.remove('flext-lightbox-content');
    if (this.visible() === false) {
      if (typeof this.options.onClose === 'function') {
        this.options.onClose(this);
      }
    }
    if (this.placeholder) {
      this.placeholder.parentElement.insertBefore(this.content, this.placeholder);
      this.placeholder.remove();
    }
    this.element?.parentElement.removeChild(this.element);
    if (typeof this.options.onClose === 'function') {
      this.options.onClose(this);
    }

    // Remove this item from the Active Items list.
    activeItems.forEach((item, index) => {
      if (item === this) {
        activeItems.splice(index, 1);
      }
    });
    flextension.emit('lightbox.close', this);
    removeKeyDownEvent();

    // Delete all properties of the lightbox object.
    deleteProps(this);
  }, 500);
};

/**
 * Returns whether the lightbox is visible.
 *
 * @return {boolean} Whether the lightbox is visible.
 */
lightbox.prototype.visible = function () {
  return this.element && this.element?.ownerDocument?.body.contains(this.element);
};

/**
 * Loads the content.
 *
 * @param {Object}        params          A parameters object.
 *
 * @param {string}        params.url      A URL to load.
 * @param {string}        params.endpoint An API endpoint.
 * @param {string|Object} params.data     A literal sequence of name-value string pairs, or any object that produces a sequence of string pairs.
 * @param {Function}      params.callback A callback function.
 */
lightbox.prototype.load = function (_ref) {
  let {
    url,
    endpoint,
    data,
    callback
  } = _ref;
  if (this.isLoaded) {
    this.loaded();
    return;
  }
  this.loading();
  const loader = document.createElement('span');
  loader.classList.add('flext-loader');
  this.content.append(loader);
  if (endpoint) {
    if (!flextension.api) {
      return;
    }
    flextension.api.get(endpoint, data).then(content => {
      loader.remove();
      this.loaded();
      if (typeof callback === 'function') {
        callback(content, this);
      }
    });
  } else {
    flextension.ajax({
      url,
      data,
      callback: content => {
        loader.remove();
        this.loaded();
        if (typeof callback === 'function') {
          callback(content, this);
        }
      }
    });
  }
};

/**
 * Sets the lightbox status to 'Loading'.
 */
lightbox.prototype.loading = function () {
  this.removeClass('flext-lightbox-loaded');
  this.addClass('flext-lightbox-loading');
};

/**
 * Sets the lightbox status to 'Loaded'.
 */
lightbox.prototype.loaded = function () {
  this.isLoaded = true;
  this.removeClass('flext-lightbox-loading');
  this.addClass('flext-lightbox-loaded');
};

/**
 * Adds new CSS class to the lightbox.
 *
 * @param {string} cssClass The CSS class to add.
 */
lightbox.prototype.addClass = function (cssClass) {
  this.element.classList.add(cssClass);
};

/**
 * Removes a CSS class from the lightbox.
 *
 * @param {string} cssClass The CSS class to remove.
 */
lightbox.prototype.removeClass = function (cssClass) {
  this.element.classList.remove(cssClass);
};

/**
 * Deletes all properties of the object.
 *
 * @param {Object} obj The object to delete.
 */
function deleteProps(obj) {
  const object = obj;
  Object.keys(object).forEach(key => {
    try {
      object[key] = null;
    } catch (e) {
      // no getter for object
    }
    try {
      delete object[key];
    } catch (e) {
      // something got wrong
    }
  });
}

/**
 * Creates an element from a HTML string.
 *
 * @param {string} html The HTML string of the element.
 * @return {Node} The DOM node of the content element.
 */
function toElement(html) {
  const content = document.createElement('div');
  content.innerHTML = html.trim();
  return content;
}

/**
 * Checks if an element's first child has a specific tag.
 *
 * @param {Node}   elem
 * @param {string} tag
 * @return {boolean} containsTag
 */
function containsTag(elem, tag) {
  const children = elem.children;
  return children.length === 1 && children[0].tagName === tag;
}
let keyDownEventAdded = false;

/**
 * Closes the active lightbox when pressing an Esc key.
 *
 * @param {KeyboardEvent} event
 */
function keyDownEventHandler(event) {
  if (event.key === 'Escape') {
    if (flextension.lightbox) {
      flextension.lightbox.close();
    }
  }
}

/**
 * Adds the keydown event handler.
 * This method adds a keydown event handler only once.
 */
function addKeyDownEvent() {
  // If the keydown event handler hasn't been added, add it.
  if (keyDownEventAdded !== true) {
    document.addEventListener('keydown', keyDownEventHandler);
    keyDownEventAdded = true;
  }
}

/**
 * Removes the keydown event handler.
 * This method remove a keydown event handler only when there are no active lightboxes.
 */
function removeKeyDownEvent() {
  // If there are no active lightboxes, remove the keydown event handler.
  if (keyDownEventAdded === true && activeItems.length === 0) {
    document.removeEventListener('keydown', keyDownEventHandler);
    keyDownEventAdded = false;
  }
}

/**
 * Lightbox object.
 */
flextension.lightbox = lightbox;

/**
 * Closes the current active lightbox.
 */
flextension.lightbox.close = function () {
  if (Array.isArray(activeItems) && activeItems.length > 0) {
    activeItems[activeItems.length - 1].close();
  }
};

/**
 * Closes all lightboxes.
 */
flextension.lightbox.closeAll = function () {
  if (Array.isArray(activeItems) && activeItems.length > 0) {
    activeItems.forEach(item => {
      item.close();
    });
  }
};
/******/ })()
;