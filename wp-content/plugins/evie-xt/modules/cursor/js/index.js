/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};


const {
  gsap
} = window;

/**
 * Cursor class
 *
 * Displays animated custom cursor.
 *
 * @version 1.0.0
 */
class Cursor {
  /**
   * Constructor
   *
   * @param {Element} el      The target element.
   * @param {Object}  options Cursor options.
   */
  constructor(el, options) {
    if (!el) {
      return;
    }
    this.settings = Object.assign({
      container: document,
      duration: 0.25,
      ease: 'Power2.easeOut',
      onMouseOver: () => {}
    }, options || {});
    this.element = el;
    this.actions = [{
      selector: 'a, button:not([disabled]), [type="button"]:not([disabled]), [type="submit"]:not([disabled]), [type="reset"]:not([disabled])'
    }];
    this.visible = false;
    this.active = false;
    window.addEventListener('mousemove', e => {
      if (this.initialized && this.visible && this.element) {
        gsap.to(this.element, {
          x: e.clientX - this.element.offsetWidth / 2,
          y: e.clientY - this.element.offsetHeight / 2,
          z: 100,
          duration: this.settings.duration,
          ease: this.settings.ease
        });
      }
    });
    document.addEventListener('mouseover', e => {
      if (e.target) {
        if (this.actions.length) {
          let isActive = false;
          this.actions.forEach(action => {
            if (action.selector) {
              let target = null;
              if (e.target.matches(action.selector)) {
                target = e.target;
              } else {
                target = e.target.closest(action.selector);
              }
              if (target) {
                isActive = true;
                this.enter();
                if (typeof action.enter === 'function') {
                  action.enter(this, target);
                }
              } else if (typeof action.leave === 'function') {
                action.leave(this);
              }
            }
          });
          if (!isActive) {
            this.leave();
          }
        }
        if (typeof this.settings.onMouseOver === 'function') {
          this.settings.onMouseOver(e);
        }
      }
    });
    document.addEventListener('click', e => {
      if (e.target) {
        if (this.actions.length) {
          this.actions.forEach(action => {
            if (action.selector) {
              let target = null;
              if (e.target.matches(action.selector)) {
                target = e.target;
              } else {
                target = e.target.closest(action.selector);
              }
              if (target) {
                this.click();
                if (typeof action.click === 'function') {
                  action.click(this, target);
                }
              }
            }
          });
        }
      }
    });
    this.initialized = true;
    this.show();
  }
  changeTo(el) {
    if (!el) {
      return;
    }
    const rect = this.element.getBoundingClientRect();
    this.element.style.display = 'none';
    this.element = el;
    gsap.set(this.element, {
      x: rect.left + window.scrollX,
      y: rect.top + window.scrollY,
      z: 100
    });
    this.element.style.display = '';
    this.show();
  }
  show() {
    if (this.initialized !== true) {
      return;
    }
    this.visible = true;
    if (this.element !== null) {
      this.element.classList.add('is-visible');
    }
  }
  hide() {
    if (this.initialized !== true) {
      return;
    }
    this.visible = false;
    if (this.element !== null) {
      this.element.classList.remove('is-visible', 'is-enter');
    }
  }
  content(html) {
    if (this.initialized !== true || this.visible !== true || !this.element) {
      return;
    }
    const textElement = this.element.querySelector('.cursor-text');
    if (textElement === null) {
      return;
    }
    if (this.currentContent && this.currentContent === html) {
      return;
    }
    this.currentContent = html;
    if (!html) {
      this.removeClass('has-text');
    } else {
      this.enter();
      this.addClass('has-text');
    }
    gsap.set(textElement, {
      y: '100%'
    });
    textElement.innerHTML = html;
    gsap.to(textElement, {
      y: '0%',
      duration: 0.3,
      delay: 0.3
    });
  }
  enter() {
    this.active = true;
    this.addClass('is-enter');
  }
  leave() {
    this.active = false;
    this.content('');
    this.removeClass('is-enter');
    this.removeClass('is-click');
  }
  click() {
    this.addClass('is-click');
    setTimeout(() => {
      this.removeClass('is-click');
    }, 1000);
  }
  addClass(className) {
    if (this.initialized !== true || this.visible !== true || !this.element) {
      return;
    }
    this.element.classList.add(className);
  }
  removeClass(className) {
    if (this.initialized !== true || this.visible !== true || !this.element) {
      return;
    }
    this.element.classList.remove(className);
  }
}
/**
 * Initializes the custom cursor.
 */
function initCursor() {
  const {
    evieApp,
    flextension
  } = window;
  if (evieApp.browser.touch) {
    document.body.classList.remove('has-custom-cursor', 'has-no-cursor');
    return;
  }
  const cursor = document.getElementById('evie-cursor');
  if (cursor !== null) {
    const options = {
      onMouseOver: e => {
        evieApp.emit('cursorActive', e);
      }
    };
    if (document.body.classList.contains('has-no-cursor')) {
      options.duration = 0.15;
    }
    evieApp.cursor = new Cursor(cursor, options);
    evieApp.cursor.actions.push({
      selector: '.menu-button,.flext-button-prev:not(.flext-button-disabled),.flext-button-next:not(.flext-button-disabled)'
    });
    if (document.body.classList.contains('has-no-cursor')) {
      ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange'].forEach(name => {
        document.addEventListener(name, () => {
          if (document.fullscreenElement) {
            document.body.classList.remove('has-no-cursor');
          } else {
            if (document.querySelector('.flext-lightbox-iframe.flext-lightbox-visible') === null) {
              document.body.classList.add('has-no-cursor');
            }
          }
        });
      });
      if (flextension) {
        flextension.on('lightbox.open', (context, lightbox) => {
          if (lightbox.element.classList.contains('flext-lightbox-iframe')) {
            if (evieApp.cursor) {
              evieApp.cursor.hide();
            }
            document.body.classList.remove('has-no-cursor');
          }
        });
        flextension.on('lightbox.close', (context, lightbox) => {
          if (lightbox.element.classList.contains('flext-lightbox-iframe')) {
            if (evieApp.cursor) {
              evieApp.cursor.show();
            }
            document.body.classList.add('has-no-cursor');
          }
        });
      }
    }
    let isMediaActive = false;
    if (flextension) {
      // Use a custom cursor in the Lightbox Gallery.
      let cursorElement = null;
      flextension.on('lightboxGallery.open', (context, lightbox) => {
        if (!lightbox) {
          return;
        }
        let lightboxCursor = lightbox.template.querySelector('.evie-cursor');
        if (lightboxCursor === null) {
          lightboxCursor = document.createElement('div');
          lightboxCursor.innerHTML = evieApp.cursor.element.innerHTML;
          lightboxCursor.setAttribute('class', evieApp.cursor.element.getAttribute('class'));
          lightbox.template.append(lightboxCursor);
        }
        cursorElement = evieApp.cursor.element;
        evieApp.cursor.changeTo(lightboxCursor);
      });

      // Switch back to default theme cursor.
      flextension.on('lightboxGallery.close', () => {
        evieApp.cursor.changeTo(cursorElement);
      });
      flextension.on('mediaPlayer.play', () => {
        if (evieApp.cursor.active && isMediaActive === true) {
          evieApp.cursor.content('<i class="flext-ico-pause"></i>');
        }
      });
      flextension.on('mediaPlayer.pause', () => {
        if (evieApp.cursor.active && isMediaActive === true) {
          evieApp.cursor.content('<i class="flext-ico-play"></i>');
        }
      });
    }
    evieApp.cursor.actions.push({
      selector: '.flext-post-video, .flext-post-audio',
      enter: (cursor, element) => {
        isMediaActive = true;
        if (element.classList.contains('flext-is-playing')) {
          cursor.content('<i class="flext-ico-pause"></i>');
        } else {
          cursor.content('<i class="flext-ico-play"></i>');
        }
      },
      leave: () => {
        isMediaActive = false;
      }
    });
    evieApp.cursor.actions.push({
      selector: '.flext-lightbox-link',
      enter: (cursor, element) => {
        if (element.classList.contains('flext-lightbox-zoom')) {
          cursor.content('<i class="flext-ico-zoom-in"></i>');
        } else if (element.classList.contains('flext-lightbox-external')) {
          cursor.content('<i class="flext-ico-external-link"></i>');
        } else {
          cursor.content('<i class="flext-ico-link"></i>');
        }
      }
    });
    evieApp.cursor.actions.push({
      selector: '.pswp__zoom-wrap img',
      enter: cursor => {
        cursor.content('<i class="flext-ico-zoom-in"></i>');
      }
    });
    evieApp.cursor.actions.push({
      selector: '.flext-quick-view-button',
      enter: cursor => {
        cursor.content('<i class="flext-ico-view"></i>');
      }
    });
    evieApp.cursor.actions.push({
      selector: '.flext-media-controls button',
      enter: cursor => {
        cursor.content('');
      }
    });
  }
}
initCursor();
/******/ })()
;