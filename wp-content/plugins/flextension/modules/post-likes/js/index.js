/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Post Like Button
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Initializes Like button for the post.
 *
 * @param {Element} content The content to initialize.
 */
function initLikeButton(content) {
  content.querySelectorAll('.flext-post-likes').forEach(button => {
    button.addEventListener('click', event => {
      event.preventDefault();
      const postId = button.dataset.postId;
      if (!postId) {
        return;
      }
      const buttons = content.querySelectorAll('.flext-post-likes[data-post-id="' + postId + '"]');
      buttons.forEach(el => {
        el.classList.add('flext-is-loading');
      });
      flextension.ajax({
        method: 'POST',
        data: {
          action: 'flextension_post_likes',
          post: postId
        },
        callback: data => {
          buttons.forEach(el => {
            el.classList.remove('flext-is-loading');
          });
          if (data) {
            const status = parseInt(data.status, 10);
            if (200 === status) {
              buttons.forEach(el => {
                if (1 === parseInt(data.button.status, 10)) {
                  el.classList.remove('flext-like-button');
                  el.classList.add('flext-unlike-button');
                } else {
                  el.classList.remove('flext-unlike-button');
                  el.classList.add('flext-like-button');
                }
                el.setAttribute('title', data.button.title);
                if (parseInt(data.message, 10) === 0) {
                  el.classList.add('is-empty');
                } else {
                  el.classList.remove('is-empty');
                }
                const label = el.querySelector('span');
                if (label !== null) {
                  label.innerHTML = data.message;
                }
              });
            } else if (401 === status) {
              if (flextension.showLoginForm) {
                flextension.showLoginForm();
              } else {
                new flextension.lightbox('<p>' + data.message + '</p>', {
                  className: 'flext-post-likes-modal',
                  fullscreen: false
                });
              }
            }
          }
        }
      });
    });
  });
}

/**
 * Initializes the Like button.
 */
flextension.on('ready', (event, content) => {
  if (!content) {
    content = document;
  }
  initLikeButton(content);
});
/******/ })()
;