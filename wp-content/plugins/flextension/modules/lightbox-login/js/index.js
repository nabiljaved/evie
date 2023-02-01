/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Lightbox Login
 *
 * Opens a login form in the lightbox when clicking on a login button.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Opens the lightbox to show the Login Form.
 *
 * @param {string} form     Form name.
 * @param {string} redirect URL to redirect to.
 */
function showLoginForm(form, redirect) {
  if (!form) {
    form = 'login';
  }
  if (!redirect) {
    redirect = window.location.href;
  }
  new flextension.lightbox('#flext-' + form + '-form', {
    className: 'flext-lightbox-login-modal',
    fullscreen: false,
    onOpen: lightbox => {
      if (lightbox && lightbox.element) {
        setTimeout(() => {
          lightbox.element.classList.add('is-lightbox-login-content-loaded');
          const userNameField = lightbox.element.querySelector('input[name="log"');
          if (userNameField !== null) {
            userNameField.focus();
          }
        }, 800);
      }
      flextension.emit('afterLoginFormOpen', lightbox);
    },
    onClose: lightbox => {
      flextension.emit('afterLoginFormClose', lightbox);
    }
  }).load({
    endpoint: '/flextension/v1/lightbox-login',
    data: {
      form,
      redirect_to: redirect
    },
    callback: (content, lightbox) => {
      lightbox.content.innerHTML = content.rendered;
      const userName = lightbox.content.querySelector('#user_login');
      if (userName !== null) {
        userName.focus();
      }
      flextension.emit('afterLoginFormLoaded', lightbox.content);
      flextension.emit('ready', lightbox.content);
    }
  });
  flextension.emit('afterLoginFormShow');
}
flextension.showLoginForm = showLoginForm;
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-lightbox-login-button:not(.flext-lightbox-login-initialized)').forEach(button => {
    if (button !== null) {
      button.classList.add('flext-lightbox-login-initialized');
      button.addEventListener('click', e => {
        e.preventDefault();
        showLoginForm(button.dataset.form);
        return false;
      });
    }
  });
});
/******/ })()
;