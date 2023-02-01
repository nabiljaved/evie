/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Facebook Page Widget
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Flextension - Facebook Page
 *
 * @param {Element} element Target element.
 * @param {Object}  options Options.
 */
function FacebookPage(element, options) {
  const defaults = {
    pageUrl: '',
    width: null,
    height: 500,
    showFacepile: true,
    smallHeader: false,
    tabs: 'timeline'
  };
  const settings = Object.assign(defaults, options || {});
  const iframe = document.createElement('iframe');
  this.refresh = function () {
    let width = settings.width;
    if (!width) {
      width = element.offsetWidth;
    }
    if (!settings.height) {
      settings.height = 500;
    }
    element.style.width = width + 'px';
    element.style.height = settings.height + 'px';
    iframe.setAttribute('src', 'https://www.facebook.com/v3.1/plugins/page.php?width=' + width + '&height=' + settings.height + '&show_facepile=' + settings.showFacepile + '&small_header=' + settings.smallHeader + '&tabs=' + encodeURIComponent(settings.tabs) + '&href=' + encodeURIComponent(settings.pageUrl));
    element.append(iframe);
  };
  this.refresh();
  window.addEventListener('resize', flextension.debounce(() => {
    if (!settings.width) {
      this.refresh();
    }
  }, 300));
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-facebook').forEach(el => {
    const tabs = [];
    if (el.dataset.timeline) {
      tabs.push('timeline');
    }
    if (el.dataset.events) {
      tabs.push('events');
    }
    if (el.dataset.messages) {
      tabs.push('messages');
    }
    new FacebookPage(el, {
      width: el.dataset.width,
      height: el.dataset.height,
      showFacepile: el.dataset.showFacepile,
      smallHeader: el.dataset.smallHeader,
      tabs: tabs.join(','),
      pageUrl: el.dataset.pageUrl
    });
  });
});
/******/ })()
;