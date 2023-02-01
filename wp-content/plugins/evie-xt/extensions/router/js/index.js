/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Router & API
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  evieApp,
  URL,
  history
} = window;

/**
 * The callback function when the route is matched.
 *
 * @callback routerCallback
 * @param {Object} query The query object.
 */

/**
 * A router object.
 *
 * Parses the URL then return the API params.
 *
 * @param {string}         id   ID of the router.
 * @param {routerCallback} done The callback function when the route is matched.
 * @param {routerCallback} fail The callback function when the route is not matched.
 */
function Router(id, done, fail) {
  this.id = id;
  this.done = done;
  this.fail = fail;
}

/**
 * Adds a route condition to the current router.
 *
 * @param {Object}         route    The route Regex or string to add.
 * @param {routerCallback} callback The callback that handles the router.
 * @param {boolean}        absURL   Whether to find absolute URL or just relative path.
 */
Router.prototype.add = function (route, callback, absURL) {
  RouterAPI.add(route, callback, absURL);
};

/**
 * Processes the url.
 *
 * @param {string}  url       The url to process.
 * @param {boolean} saveState Whether to add a new history URL to the browser.
 */
Router.prototype.get = function (url, saveState) {
  RouterAPI.parse(url).then(query => {
    if (query === false) {
      return;
    }
    if (saveState) {
      const state = {
        routerId: this.id,
        done: true,
        query
      };
      history.pushState(state, '', url);
    }
    if (typeof this.done === 'function') {
      this.done(url, query);
    }
  }).catch(error => {
    if (error === false) {
      return;
    }
    if (typeof this.fail === 'function') {
      if (saveState) {
        const state = {
          routerId: this.id,
          done: false,
          error
        };
        history.pushState(state, '', url);
      }
      this.fail(url, error);
    }
  });
};

/**
 * Router API.
 *
 * Handles API routes.
 *
 * @version 1.0.0
 */
const RouterAPI = {
  /**
   * Initializes the router class
   */
  init() {
    const defaults = {
      root: '',
      permalink: false,
      taxonomies: [],
      types: []
    };
    this.settings = Object.assign(defaults, evieApp.settings.router || {});
    if (!this.settings.root) {
      return;
    }
    this.root = new URL(this.settings.root);
    this.routes = [];
    this.routers = [];
    this.query = {};
    this.currentURL = null;

    /**
     * Adds routes for posts archive.
     */
    if (this.settings.permalink === true) {
      const taxonomies = Object.values(this.settings.taxonomies);

      // Do not load feed links
      this.add(/(feed|rdf|rss|rss2|atom)\/?$/, () => {
        window.location.reload();
      });

      // Blog Archive with paginate
      this.add(new RegExp(`(${taxonomies.join('|')}|search|author)\/(.+?)\/page\/?([0-9]{1,})\/?$`, ''), matches => {
        const query = {};
        if (!matches[1] || !matches[2]) {
          return;
        }
        query[matches[1]] = matches[2];
        query.paged = matches[3];
        return this.getQuery(query);
      });

      // Blog Archive
      this.add(new RegExp(`(${taxonomies.join('|')}|search|author)\/(.+?)\/?$`), matches => {
        const query = {};
        if (!matches[1] || !matches[2]) {
          return;
        }
        query[matches[1]] = matches[2];
        return this.getQuery(query);
      });

      // Single page and post (Numeric)
      this.add(/archives\/([0-9]{1,})(?:\/([0-9]+))?\/?$/, matches => {
        const query = {};
        query.id = matches[1];
        query.paged = matches[2];
        return this.getQuery(query);
      });

      // Blog Date Archive
      this.add(/([0-9]{4})(?:\/([0-9]{1,2}))?(?:\/([0-9]{1,2}))?(?:\/page\/([0-9]{1,}))?\/?$/, matches => {
        const query = {};
        query.year = matches[1];
        query.monthnum = matches[2];
        query.day = matches[3];
        query.paged = matches[4];
        return this.getQuery(query);
      });

      // Single page and post (Day and name)
      this.add(/([0-9]{4})\/([0-9]{1,2})\/([0-9]{1,2})\/([^\/]+)(?:\/([0-9]+))?\/?$/, matches => {
        const query = {};
        query.path = matches[0];
        query.paged = matches[5];
        return this.getQuery(query);
      });

      // Single page and post (Month and name)
      this.add(/([0-9]{4})\/([0-9]{1,2})\/([^\/]+)(?:\/([0-9]+))?\/?$/, matches => {
        const query = {};
        query.path = matches[0];
        query.paged = matches[4];
        return this.getQuery(query);
      });

      // Static Posts page and Single page
      this.add(/(.?.+?)\/page\/?([0-9]{1,})\/?$/, matches => {
        const query = {};
        query.path = matches[1];
        query.paged = matches[2];
        return this.getQuery(query);
      });

      // Single page and post (Post name) with comments page
      this.add(/(.?.+?)\/comment-page-([0-9]{1,})\/?$/, matches => {
        const query = {};
        query.path = matches[1];
        query.cpage = matches[2];
        return this.getQuery(query);
      });

      // Blog Posts
      this.add(/page\/?([0-9]{1,})\/?$/, matches => {
        const query = {};
        query.paged = matches[1];
        return this.getQuery(query);
      });

      // Single post and page (Post name) with paginate
      this.add(/(.?.+?)(\/[0-9]+)?\/?$/, matches => {
        const query = {};
        query.path = matches[1];
        if (matches[2] && matches[2].length > 0) {
          query.page = matches[2].replace('/', '');
        }
        return this.getQuery(query);
      });
    } else {
      // Do not load feed links
      this.add(/\?(feed|rdf|rss|rss2|atom)=([^&]*)?/, () => {
        window.location.reload();
      }, true);

      // Plain/Ugly permalinks.
      this.add(/\?(.+)=(.+)?/, () => {
        return this.getQuery();
      }, true);
    }
  },
  /**
   * Adds a route condition to the current router.
   *
   * @param {Object}         route    The route Regex or string to add.
   * @param {routerCallback} callback The callback that handles the router.
   * @param {boolean}        absURL   Whether to find absolute URL or just relative path.
   */
  add(route, callback, absURL) {
    this.routes.push({
      route,
      callback,
      absURL
    });
  },
  getUrlQuery(url) {
    if (!url) {
      url = this.currentURL;
    }
    if (typeof url === 'string') {
      url = new URL(url);
    }
    const query = {};
    const params = new URLSearchParams(url.search);
    if (params) {
      for (const key of params.keys()) {
        query[key] = params.get(key);
      }
    }
    return query;
  },
  parseQuery(params) {
    const query = {};
    for (let i = 0; i < params.length; i++) {
      const vars = params[i].split('=');
      if (!vars.length) {
        return;
      }
      if (!vars[0].length) {
        return;
      }
      query[vars[0]] = decodeURIComponent(vars[1]);
    }
    return query;
  },
  /**
   * Converts the URL parameters to the API queries;
   *
   * @param {Object} query The query object to map.
   */
  mapQuery(query) {
    if (!query) {
      return query;
    }
    const mappedQuery = {};
    const mappings = Object.keys(this.settings.taxonomies).reduce((object, key) => {
      object[this.settings.taxonomies[key]] = key;
      return object;
    }, {});
    const keys = Object.keys(query);
    keys.forEach(key => {
      const value = query[key];
      if (value) {
        if (mappings[key]) {
          mappedQuery[mappings[key]] = value;
        } else {
          mappedQuery[key] = value;
        }
      }
    });
    return mappedQuery;
  },
  getQuery(query) {
    if (!query) {
      query = {};
    }
    query = Object.assign(query, this.getUrlQuery());
    query = this.mapQuery(query);
    return query;
  },
  /**
   * Extracts the parameters from the url.
   *
   * @param {string} url The url to find.
   * @return {Array} An array list of the matched parameters.
   */
  getParameters(url) {
    const results = [];
    for (let i = 0; i < this.routes.length; i++) {
      const route = this.routes[i];
      let checkURL = '';
      if (!url.hostname) {
        url = new URL(url);
      }
      if (!route.absURL) {
        checkURL = url.pathname.replace(this.root.pathname, '');
        if ('' === checkURL) {
          checkURL = '/';
        }
      } else {
        checkURL = url.toString().replace(this.root.toString(), '');
      }

      // Check for matching.
      const matches = checkURL.toString().match(route.route);
      if (matches) {
        results.push({
          route,
          matches
        });
        // Break after first hit.
        break;
      }
    }
    return results;
  },
  /**
   * Checks all available routes.
   *
   * @param {string} url The url to check.
   * @return {Object} The first matched if any route is matched. Otherwise, Empty array object.
   */
  parse(url) {
    if (!url) {
      url = window.location.href;
    }
    url = decodeURI(url);
    url = url.replace(/\/$/, '');
    if (url === this.currentURL) {
      return Promise.reject(false);
    }
    this.currentURL = url;
    let query = false;
    if (url === this.root.href) {
      query = {
        path: ''
      };
    } else {
      const results = this.getParameters(url);
      if (results.length > 0) {
        if (typeof results[0].route.callback === 'function') {
          query = results[0].route.callback(results[0].matches, results[0].route);
        } else {
          query = this.getQuery();
        }
      }
    }
    this.query = query;
    if (query !== false) {
      return Promise.resolve(query);
    }
    return Promise.reject(url);
  },
  /**
   * Creates a new instance of the Router to use in different module.
   *
   * @param {string}         id        ID of the router.
   * @param {routerCallback} done      The callback function when the route is matched.
   * @param {routerCallback} fail      The callback function when the route is not matched.
   * @param {boolean}        saveState Whether to save state on first load.
   */
  create(id, done, fail, saveState) {
    if (!id) {
      id = this.routers.length;
    }
    if (this.routers[id]) {
      this.routers[id].done = done;
      this.routers[id].fail = fail;
    } else {
      this.routers[id] = new Router(id, done, fail);
    }
    if (!this.isPopStateEventAdded) {
      if (saveState === true) {
        this.parse().then(query => {
          if (query === false) {
            return;
          }
          const state = {
            routerId: id,
            done: true,
            query
          };
          history.pushState(state, '', window.location.href);
        });
      }
      window.addEventListener('popstate', this.onPopState.bind(this));
      this.isPopStateEventAdded = true;
    }
    return this.routers[id];
  },
  onPopState(e) {
    if (e !== null && e.state !== null) {
      const url = window.location.href.replace(/\/$/, '');
      if (url !== this.currentURL && this.routers[e.state.routerId]) {
        this.currentURL = url;
        this.query = e.state.query;
        if (e.state.done === true) {
          this.routers[e.state.routerId].done(url, e.state.query);
        } else {
          this.routers[e.state.routerId].fail(url, e.state.error);
        }
      }
    }
  }
};
RouterAPI.init();
evieApp.router = RouterAPI;
/******/ })()
;