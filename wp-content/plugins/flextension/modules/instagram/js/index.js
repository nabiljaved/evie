/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Instagram Feed
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Initializes the Instagram video.
 *
 * @param {Element} element Target elememt.
 */
function InstagramVideo(element) {
  if (element.classList.contains('is-initialized')) {
    return;
  }
  this.element = element;
  this.element.classList.add('is-initialized');
  this.video = this.element.querySelector('video');
  this.isReady = this.video !== null;
  this.paused = true;
  this.element.addEventListener('mouseenter', () => {
    if (this.video === null) {
      this.element.classList.add('flext-media-loading');
      this.video = document.createElement('video');
      this.video.setAttribute('loop', 'loop');
      this.video.setAttribute('muted', 'muted');
      this.video.setAttribute('playsinline', true);
      this.video.setAttribute('preload', 'auto');
      const source = document.createElement('source');
      source.setAttribute('src', this.element.dataset.mediaUrl);
      source.setAttribute('type', 'video/mp4');
      this.video.append(source);
      this.video.addEventListener('play', () => {
        this.element.classList.add('flext-is-playing');
        this.element.classList.remove('flext-media-loading');
      });
      this.video.addEventListener('pause', () => {
        this.element.classList.remove('flext-media-loading', 'flext-is-playing');
      });
      this.video.addEventListener('canplay', () => {
        this.isReady = true;
        this.video.play().then(() => {
          this.paused = false;
        }).catch(() => {
          this.paused = true;
        });
      });
      this.element.append(this.video);
      this.video.muted = true;
      this.video.load();
    } else if (this.isReady && this.paused) {
      this.video.play().then(() => {
        this.paused = false;
      }).catch(() => {
        this.paused = true;
      });
    }
  });
  this.element.addEventListener('mouseleave', () => {
    if (this.isReady && !this.paused) {
      this.video.pause();
      this.paused = true;
    }
  });
}

/**
 * Initializes widgets and blocks.
 */
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-instagram-feed .has-media-type-video a:not(.is-initialized)').forEach(el => {
    new InstagramVideo(el);
  });
});
/******/ })()
;