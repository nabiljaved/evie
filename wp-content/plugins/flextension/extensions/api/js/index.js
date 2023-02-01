/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * API
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  flextension,
  URL,
  fetch,
  Headers
} = window;

/**
 * Serializes an object or a set of key/values into a query string.
 *
 * @param {Object} data The data object to serialize.
 * @return {string} Query string parameters.
 */
function serializeSearchParams(data) {
  const searchParams = [];
  Object.keys(data).forEach(key => {
    const value = data[key];
    if (typeof value === 'object') {
      Object.keys(value).forEach(name => {
        searchParams.push(encodeURIComponent(key + '[' + name + ']') + '=' + encodeURIComponent(value[name]));
      });
    } else {
      searchParams.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
    }
  });
  return searchParams.join('&');
}

/**
 * REST API.
 *
 * @param {string} namespace The first URL segment after core prefix.
 */
function RestApi(namespace) {
  this.settings = Object.assign({
    url: '',
    nonce: ''
  }, flextension.settings.api || {});
  if (!this.settings.url) {
    throw new Error('Invalid API URI.');
  }
  if (!namespace) {
    namespace = '';
  }
  this.settings.url += namespace;
  this.settings.url = this.settings.url.replace(/\/$/, '');
}
RestApi.prototype.fetch = function (url, options, callback) {
  const headers = new Headers(options?.headers || {});
  if (!headers.has('Content-Type')) {
    headers.append('Content-Type', 'application/json');
  }
  if (this.settings.nonce) {
    headers.append('X-WP-Nonce', this.settings.nonce);
  }
  options = Object.assign({
    headers
  }, options || {});
  return fetch(url, options).then(response => {
    if (response.ok === true) {
      const nonce = response.headers.get('X-WP-Nonce');
      if (nonce && this.settings.nonce !== nonce) {
        this.settings.nonce = nonce; // Refresh WP Nonce.
      }
    } else {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    if (typeof callback === 'function') {
      callback(response);
    }
    return response.json();
  });
};
RestApi.prototype.get = function (endPoint, data, callback, options) {
  const url = new URL(this.settings.url + endPoint);
  const searchParams = new URLSearchParams(serializeSearchParams(data));
  searchParams.forEach((value, key) => {
    url.searchParams.append(key, value);
  });
  options = Object.assign({
    method: 'GET'
  }, options || {});
  return this.fetch(url, options, callback);
};
RestApi.prototype.post = function (endPoint, data, callback, options) {
  const url = new URL(this.settings.url + endPoint);
  options = Object.assign({
    method: 'POST',
    body: JSON.stringify(data)
  }, options || {});
  return this.fetch(url, options, callback);
};
flextension.RestApi = RestApi;
flextension.api = new flextension.RestApi();

/**
 * Sends an HTTP Request to WordPress Ajax.
 *
 * @param {Object}        params          A parameters object.
 *
 * @param {string}        params.url      A URL to load.
 * @param {string}        params.method   The request method.
 * @param {string|Object} params.data     A literal sequence of name-value string pairs, or any object that produces a sequence of string pairs.
 * @param {Function}      params.callback A callback function.
 * @return {Promise} A Promise that resolves to a Response object.
 */
flextension.ajax = _ref => {
  let {
    url,
    method,
    data,
    callback
  } = _ref;
  if (!method) {
    method = 'GET';
  }
  if (!url) {
    url = flextension.settings.ajaxUrl;
  }
  if (flextension.settings.ajaxNonce) {
    data = Object.assign({}, data, {
      ajaxNonce: flextension.settings.ajaxNonce
    });
  }
  const options = {
    method,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    credentials: 'same-origin'
  };
  if (method === 'GET' || method === 'HEAD') {
    url = new URL(url);
    const searchParams = new URLSearchParams(serializeSearchParams(data));
    searchParams.forEach((value, key) => {
      url.searchParams.append(key, value);
    });
  } else {
    options.body = new URLSearchParams(data);
  }
  return fetch(url, options).then(response => response.json()).then(content => {
    if (typeof callback === 'function') {
      callback(content);
    }
  });
};
/******/ })()
;