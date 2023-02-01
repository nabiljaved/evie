/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Share Buttons
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
let permissionChecked = false;
let isClipboardEnabled = false;
async function copyText(text) {
  if (!permissionChecked) {
    await navigator.permissions.query({
      name: 'clipboard-write'
    }).then(result => {
      permissionChecked = true;
      if (result.state === 'granted' || result.state === 'prompt') {
        isClipboardEnabled = true;
        navigator.clipboard.writeText(text);
      }
    });
  } else if (isClipboardEnabled) {
    await navigator.clipboard.writeText(text);
  }
}

/**
 * Opens the lightbox to show the Share Buttons.
 *
 * @param {string} id The post ID.
 */
function showShareButtons(id) {
  new flextension.lightbox('#flext-share-buttons-content-' + id, {
    className: 'flext-share-modal',
    fullscreen: false
  }).load({
    endpoint: '/flextension/v1/share-buttons',
    data: {
      id
    },
    callback: (content, lightbox) => {
      lightbox.content.innerHTML = content.rendered;
      const button = lightbox.content.querySelector('.copy-clipboard');
      if (button !== null) {
        button.addEventListener('click', () => {
          const permalink = lightbox.content.querySelector('.modal-permalink');
          if (permalink !== null) {
            if (typeof flextension.currentClipboard !== 'undefined') {
              // Reset recent Clipboard button.
              const icon = flextension.currentClipboard.querySelector('i');
              if (icon !== null) {
                icon.classList.remove('flext-ico-check');
                icon.classList.add('flext-ico-copy');
              }
            }

            // Copy the permalink to the clipboard.
            copyText(permalink.value);
            const icon = button.querySelector('i');
            if (icon !== null) {
              icon.classList.remove('flext-ico-copy');
              icon.classList.add('flext-ico-check');
            }
            flextension.currentClipboard = button;
          }
        });
      }
      flextension.emit('ready', lightbox.content);
    }
  });
}

/**
 * Initializes a share button for the post.
 *
 * @param {Element} content The target content to initialize.
 */
function initShareButton(content) {
  content.querySelectorAll('.post-share').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      showShareButtons(button.dataset.postId);
    });
  });
}

/**
 * Initializes the post buttons.
 */
flextension.on('ready', (event, content) => {
  if (!content) {
    content = document;
  }
  initShareButton(content);
});
/******/ })()
;