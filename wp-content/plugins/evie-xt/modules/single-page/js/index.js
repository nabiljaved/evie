/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Single Page Application (SPA)
 *
 * Contains core scrips, handlers and routers for the main application.
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  flextension,
  evieApp,
  DOMParser,
  URL,
  setTimeout
} = window;

/**
 * Single Page class.
 */
class SinglePage {
  /**
   * Constructor.
   */
  constructor() {
    this.settings = {
      container: '#site-content',
      exclude: {
        links: [],
        selectors: []
      }
    };

    // Single Page Settings.
    if (typeof evieApp.settings.singlePage === 'object') {
      this.settings = Object.assign(this.settings, evieApp.settings.singlePage);
    }
    this.container = document.querySelector(this.settings.container);
    if (this.container === null) {
      return;
    }
    this.query = {};
    this.placeholders = ['.archive-image', '.archive-title', '.archive-description', '.posts-count', '.main-posts .posts-list'];
    this.scrollTarget = this.container;
    this.init();
  }
  init() {
    /**
     * Override router's popstate event handler to replace the AJAX Pagination callback function.
     *
     * @param {PopStateEvent} e A PopStateEvent. Inherits from Event.
     */
    evieApp.router.onPopState = e => {
      if (e !== null && e.state !== null) {
        const url = window.location.href.replace(/\/$/, '');
        if (url !== evieApp.router.currentURL && evieApp.router.routers[e.state.routerId]) {
          const previousQuery = Object.assign({}, evieApp.router.query);
          evieApp.router.currentURL = url;
          evieApp.router.query = e.state.query;
          if (e.state.done === true) {
            if (e.state.routerId === 'ajaxPagination' && previousQuery.path !== e.state.query.path) {
              this.getPage(url, e.state.query);
            } else {
              evieApp.router.routers[e.state.routerId].done(url, e.state.query);
            }
          } else {
            evieApp.router.routers[e.state.routerId].fail(url, e.state.error);
          }
        }
      }
    };
    this.router = evieApp.router.create('singlePage', (url, query) => {
      this.getPage(url, query);
    }, url => {
      this.getPage(url, {
        id: -1
      });
    }, true);
    this.initExcludingList();
    evieApp.on('singlePage.initLinks', (context, content) => {
      this.initLinks(content);
    });
    evieApp.on('ready', (context, content) => {
      if (content) {
        this.ready(content);
      }
    });
    this.ready();
  }
  initExcludingList() {
    this.excludingLinks = ['wp-login', 'wp-admin/', 'wp-content/', 'wp-includes/'];
    this.excludingSelectors = ['.no-ajax, .pagination a, .comments-section .loadmore-pagination a, .comment-reply-link, #cancel-comment-reply-link, a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"], a[href$=".bmp"], a[href$=".mp4"], a[href$=".mov"], a[href$=".ogv"], a[href$=".ogg"], a[href$=".webm"]'];
    if (typeof this.settings.exclude === 'object') {
      if (typeof this.settings.exclude.links === 'object') {
        this.settings.exclude.links.forEach(item => {
          if (item) {
            this.excludingLinks.push(item);
          }
        });
      }
      if (typeof this.settings.exclude.selectors === 'object') {
        this.settings.exclude.selectors.forEach(item => {
          if (item) {
            this.excludingSelectors.push(item);
          }
        });
      }
    }
  }
  ready(content) {
    if (!content) {
      content = document;
    }
    this.initSearchForm(content);
    this.initWidgets(content);
    this.initMainContent(content);
    this.afterGetPage();
    evieApp.emit('singlePage.ready');
  }
  isValidLink(link) {
    if (!link) {
      return false;
    }
    if (link.onclick) {
      return false;
    }
    const url = new URL(link.toString());
    if (!url) {
      return false;
    }
    if (url.href.startsWith('#')) {
      return false;
    }
    if (url.pathname === location.pathname && url.search === location.search && url.hash) {
      return false;
    }
    return true;
  }
  isExcludingURL(url) {
    let isExcluded = false;
    for (const i in this.excludingLinks) {
      if (new RegExp(this.excludingLinks[i], 'i').test(url)) {
        isExcluded = true;
        break;
      }
    }
    return isExcluded;
  }
  isExcludingLink(link) {
    for (let i = 0; i < this.excludingSelectors.length; i++) {
      if (link.matches(this.excludingSelectors[i])) {
        return true;
      }
    }
    return false;
  }
  isInternal(link) {
    if (!link) {
      return false;
    }
    const url = link.getAttribute('href');
    let isInternal = link.hostname === location.hostname;
    if (url && url.startsWith('#') || link.getAttribute('target') && link.getAttribute('target') !== '_self') {
      isInternal = false;
    }
    return isInternal;
  }
  initLinks(content) {
    if (!content) {
      content = document;
    }
    const links = [];
    if (content.tagName === 'A') {
      links.push(content);
    } else {
      content.querySelectorAll('a:not(.ajax-page)').forEach(link => {
        if (this.isInternal(link) && !this.isExcludingLink(link)) {
          links.push(link);
        }
      });
    }
    links.forEach(link => {
      link.classList.add('ajax-page');
      if (this.isValidLink(link)) {
        link.addEventListener('click', event => {
          if (event.defaultPrevented) {
            return true;
          }
          if (!this.isExcludingURL(link.href) && !this.isExcludingURL(location.href)) {
            event.preventDefault();
            this.navigate(link.href, true);
          } else {
            this.beforeGetPage(false);
          }
          evieApp.emit('singlePage.linkClick', link, this);
        });
      }
    });
    evieApp.emit('singlePage.afterInitLinks', links, this);
  }
  navigate(url, saveState) {
    evieApp.emit('singlePage.beforeNavigate', url);
    this.router.get(url, saveState);
    evieApp.emit('singlePage.afterNavigate', url);
  }
  initSearchForm(content) {
    if (this.isExcludingURL(location.href)) {
      return;
    }
    if (!content) {
      content = document;
    }
    content.querySelectorAll('.search-form').forEach(form => {
      form.addEventListener('submit', event => {
        event.preventDefault();
        const keyword = form.querySelector('input[name="s"]');
        if (keyword !== null) {
          const url = this.updateUrlParam(form.getAttribute('action'), 's', keyword.value);
          this.navigate(url, true);
        }
      });
    });
  }
  initMainContent(content) {
    if (document.body.classList.contains('singular')) {
      this.initComments();
    }
    this.initLinks(content);
  }
  initComments() {
    if (this.isExcludingURL(location.href)) {
      return;
    }
    if (typeof window.addComment !== 'undefined') {
      window.addComment.init();
    }
    document.querySelectorAll('.comment-form').forEach(el => {
      el.addEventListener('submit', ev => {
        ev.preventDefault();
        const {
          FormData
        } = window;
        const data = new FormData(el);
        const query = {};
        query.post = data.get('comment_post_ID');
        query.parent = data.get('comment_parent');
        query.author_email = data.get('email');
        query.author_name = data.get('author');
        query.author_url = data.get('url');
        query.content = data.get('comment');
        let valid = true;
        if (!query.content) {
          valid = false;
        }
        if (document.querySelector('.logged-in-as') === null && (!query.author_email || !query.author_name)) {
          valid = false;
        }
        if (true === valid) {
          this.addComment(query);
        }
      });
    });
  }
  updateUrlParam(url, key, val) {
    return url.replace(new RegExp('([?&]' + key + '(?=[=&#]|$)[^#&]*|(?=#|$))'), '&' + key + '=' + encodeURIComponent(val)).replace(/^([^?&]+)&/, '$1?');
  }
  getQuery(query) {
    if (!query) {
      query = {};
    }
    if ('/' === query.path) {
      query.path = '';
    }
    return query;
  }
  beforeGetPage(partial) {
    evieApp.emit('singlePage.beforeGetPage', this);
    this.container.classList.remove('is-loaded');
    this.container.classList.add('is-loading');
    if (!partial) {
      return;
    }
    this.container.classList.add('partial-content');
    const selector = this.placeholders.join(',');
    if (selector) {
      this.container.querySelectorAll(selector).forEach(placeholder => {
        placeholder.classList.add('has-content-placeholder');
      });
    }
    this.pageReady = false;
    if (this.scrollTarget) {
      evieApp.scrollTo(this.scrollTarget, () => {
        if (this.pageContent) {
          this.updatePage(this.pageContent);
          this.pageContent = null;
        } else {
          this.pageReady = true;
        }
      });
    } else if (this.pageContent) {
      this.updatePage(this.pageContent);
      this.pageContent = null;
    } else {
      this.pageReady = true;
    }
  }
  getPage(url, query) {
    this.query = this.getQuery(query);
    this.beforeGetPage(true);
    this.restPage(url, this.query);
  }
  restPage(url, query) {
    flextension.api.get('/evie/v1/page-renderer', query, null, {
      referrer: url
    }).then(data => {
      if (this.pageReady === true) {
        this.updatePage(data);
      } else {
        this.pageContent = data;
      }
    }).catch(() => {
      location.reload();
    }).finally(() => {
      this.afterGetPage();
    });
  }
  ajaxPage(url) {
    window.fetch(url).then(response => {
      return response.text();
    }).then(data => {
      // Build the data from response HTML.
      const doc = new DOMParser().parseFromString(data, 'text/html');
      const menuClasses = [];
      doc.querySelectorAll('.main-menu li').forEach(item => {
        menuClasses.push(item.getAttribute('class'));
      });
      const customStyle = doc.querySelector('#flextension-extensions-inline-css');
      const content = {
        title: doc.title,
        bodyClass: doc ? doc.body.getAttribute('class') : '',
        editMenu: doc.querySelector('#wp-admin-bar-edit > .ab-item'),
        menuClasses,
        customStyles: customStyle !== null ? customStyle.innerHTML : '',
        rendered: doc.querySelector(this.settings.container)
      };
      if (this.pageReady === true) {
        this.updatePage(content);
      } else {
        this.pageContent = content;
      }
    }).catch(() => {
      location.reload();
    }).finally(() => {
      this.afterGetPage();
    });
  }
  afterGetPage() {
    const selector = this.placeholders.join(',');
    if (selector) {
      this.container.querySelectorAll(selector).forEach(placeholder => {
        placeholder.classList.remove('has-content-placeholder');
      });
    }
    this.container.classList.remove('partial-content', 'is-loading');
    this.container.classList.add('is-loaded');
    evieApp.emit('singlePage.afterGetPage', this);
  }
  beforeUpdatePage() {
    evieApp.emit('singlePage.beforeUpdatePage', this);
  }
  updatePage(data) {
    this.beforeUpdatePage();
    this.updatePageInfo(data);
    this.container.innerHTML = data.rendered;
    this.initMainContent(this.container);
    this.sendPageView();
    this.afterUpdatePage();
  }
  afterUpdatePage() {
    this.updateContentStyles();
    this.container.classList.add('is-loaded');
    evieApp.emit('singlePage.afterUpdatePage', this.container, this);
  }
  updatePageInfo(data) {
    if (data.title) {
      const title = document.querySelector('title');
      if (title !== null) {
        title.innerHTML = data.title; // Sets the title with special HTML characters.
      } else {
        document.title = data.title;
      }
    }
    this.updateBodyClass(data.bodyClass);
    this.updateCustomStyles(data.customStyles);
    this.updateAdminBar(data.editMenu);
    this.updateMenuClass(data.menuClasses);
  }
  updateBodyClass(bodyClass) {
    if (bodyClass) {
      // This theme always supports the Theme Customizer.
      bodyClass = bodyClass.replace('no-customize-support', 'customize-support');
      const classes = bodyClass.split(' ').map(item => item.trim());
      const remove = classes.indexOf('has-scheme-auto');
      if (remove > -1) {
        classes.splice(remove, 1);
      }
      if (evieApp.isDarkMode) {
        const index = classes.indexOf('has-scheme-light');
        if (index > -1) {
          classes.splice(index, 1);
        }
        if (!classes.includes('has-scheme-dark')) {
          classes.push('has-scheme-dark');
        }
      } else {
        const index = classes.indexOf('has-scheme-dark');
        if (index > -1) {
          classes.splice(index, 1);
        }
        if (!classes.includes('has-scheme-light')) {
          classes.push('has-scheme-light');
        }
      }
      document.body.setAttribute('class', [...new Set(classes)].join(' '));
    }
  }
  updateCustomStyles(customStyles) {
    let customStyle = document.querySelector('#flextension-extensions-inline-css');
    if (customStyle === null) {
      customStyle = document.createElement('style');
      customStyle.setAttribute('id', 'flextension-extensions-inline-css');
      document.head.append(customStyle);
    }
    customStyle.innerHTML = customStyles || '';
  }
  updateContentStyles() {
    document.querySelectorAll('body > style').forEach(style => {
      if (style && style.sheet) {
        const rules = style.sheet.cssRules;
        for (let i = 0; i < rules.length; i++) {
          if (rules[i].selectorText.indexOf('.wp-container-') > -1) {
            style.remove();
            return;
          }
        }
      }
    });
    document.querySelectorAll('#content-footer > link, #content-footer > style, #content-footer > script').forEach(script => {
      const headScript = document.head.querySelector('#' + script.getAttribute('id'));
      if (script && headScript !== null) {
        script.remove();
      } else {
        const bodyScript = document.body.querySelector('#' + script.getAttribute('id'));
        if (bodyScript !== null) {
          script.remove();
        }
      }
    });
  }
  updateAdminBar(menu) {
    if (document.getElementById('wpadminbar') === null) {
      return;
    }
    let editMenu = document.getElementById('wp-admin-bar-edit');
    if (menu) {
      if (editMenu === null) {
        editMenu = document.createElement('li');
        editMenu.setAttribute('id', 'wp-admin-bar-edit');
        editMenu.innerHTML = '<a class="ab-item" href="#">Edit Post</a>';
        const adminBarRoot = document.getElementById('wp-admin-bar-root-default');
        if (adminBarRoot !== null) {
          adminBarRoot.append(editMenu);
        }
      }
      editMenu.style.display = '';
      const link = editMenu.querySelector('.ab-item');
      if (link !== null) {
        link.setAttribute('href', decodeURI(menu.href));
        link.innerHTML = menu.title;
      }
    } else if (editMenu !== null) {
      editMenu.remove();
    }
    const customizeLink = document.querySelector('#wp-admin-bar-customize .ab-item');
    if (customizeLink !== null) {
      const customizeUrl = customizeLink.getAttribute('href');
      customizeLink.setAttribute('href', this.updateUrlParam(customizeUrl, 'url', document.URL));
    }
  }
  updateMenuClass(classes) {
    if (classes) {
      classes.forEach(itemClass => {
        const ids = itemClass.match(/menu-item-(\d+)/g);
        if (ids && ids.length && ids[0]) {
          document.querySelectorAll('.' + ids[0]).forEach(item => {
            if (item.classList.contains('is-visible')) {
              itemClass += ' is-visible';
            }
            if (item.classList.contains('is-sub-menu-visible')) {
              itemClass += ' is-sub-menu-visible';
            }
            item.setAttribute('class', itemClass);
          });
        }
      });
    }
  }
  sendPageView() {
    // Checks to see if Google Analytics script is avaialble.
    if (typeof window.ga === 'function') {
      window.ga('send', 'pageview', location.pathname);
    }
  }
  addComment(query, url) {
    flextension.api.post('/evie/v1/comments', query, response => {
      this.updateCommentsCount(response.headers.get('X-WP-Total'));
    }, {
      referrer: url
    }).then(data => {
      if (data.message) {
        this.setCommentStatus(data.message);
      } else {
        this.showComment(query.parent, data);
      }
    });
  }
  updateCommentsCount(number) {
    const commentsCountLabel = this.container.querySelector('.toggle-comments > span');
    if (commentsCountLabel !== null) {
      commentsCountLabel.innerText = commentsCountLabel.innerText.replace(/\(.+\)/g, `(${number})`);
    }
  }
  setCommentStatus(message) {
    let statusText = document.querySelector('.comment-form .status-text');
    if (statusText === null) {
      statusText = document.createElement('p');
      statusText.classList.add('status-text');
      const form = document.querySelector('.comment-form .form-submit');
      if (form !== null) {
        form.parentElement.insertBefore(statusText, form);
      }
    }
    statusText.style.display = '';
    statusText.innerHTML = message;
  }
  showComment(parent, comment) {
    const textArea = document.querySelector('.comment-form textarea');
    if (textArea !== null) {
      textArea.value = '';
    }
    const cancelLink = document.getElementById('cancel-comment-reply-link');
    if (cancelLink !== null && cancelLink.style.display !== 'none') {
      cancelLink.click();
    }
    const statusText = document.querySelector('.comment-form .status-text');
    if (statusText !== null) {
      statusText.innerHTML = '';
      statusText.style.display = 'none';
    }
    const doc = new DOMParser().parseFromString(comment, 'text/html');
    const commentElement = doc ? doc.body.firstChild : null;
    if (commentElement === null) {
      return;
    }
    commentElement.classList.add('new-comment-hidden', 'new-comment-active');
    if (parent && parseInt(parent, 10) !== 0) {
      const parentComment = document.getElementById('comment-' + parent);
      if (parentComment !== null) {
        let commentList = parentComment.querySelector('.children');
        if (commentList === null) {
          commentList = document.createElement('ol');
          commentList.classList.add('children');
          parentComment.append(commentList);
        }
        commentList.append(commentElement);
      }
    } else {
      let commentList = this.container.querySelector('.comment-list');
      if (commentList === null) {
        commentList = document.createElement('ol');
        commentList.classList.add('comment-list');
        const commentRespond = this.container.querySelector('#respond');
        if (commentRespond !== null) {
          commentRespond.parentElement.insertBefore(commentList, commentRespond);
        }
      }
      commentList.append(commentElement);
    }
    evieApp.scrollTo(commentElement);
    commentElement.classList.remove('new-comment-hidden');
    setTimeout(() => {
      commentElement.classList.remove('new-comment-active');
    }, 3000);
    this.initLinks(commentElement);
  }
  parseLinkHeader(header) {
    if (header.length === 0) {
      return false;
    }
    // Split parts by comma
    const parts = header.split(',');
    const links = {};
    // Parse each part into a named link
    parts.forEach(part => {
      const section = part.split(';');
      if (section.length !== 2) {
        return false;
      }
      const url = section[0].replace(/<(.*)>/, '$1').trim();
      const name = section[1].replace(/rel="(.*)"/, '$1').trim();
      links[name] = url;
    });
    return links;
  }
  initWidgets(content) {
    if (this.isExcludingURL(location.href)) {
      return;
    }
    // Archives Dropdown widget.
    content.querySelectorAll('select[name="archive-dropdown"]').forEach(el => {
      // Remove default onchange events.
      el.removeAttribute('onchange');
      el.onchange = null;
      el.addEventListener('change', () => {
        if (el.value) {
          this.navigate(el.value, true);
        }
      });
    });

    // Categories Dropdown widget.
    content.querySelectorAll('.widget_categories select[name="cat"]').forEach(el => {
      let root = evieApp.router.root.href;
      const form = el.closest('form');
      if (form !== null) {
        root = form.getAttribute('action');
      }
      if (!root) {
        return;
      }
      // Remove default onchange events.
      el.removeAttribute('onchange');
      el.onchange = null;
      el.addEventListener('change', () => {
        if (el.value !== '-1') {
          let url = '';
          if (evieApp.router.settings.permalink === true) {
            url = root + '/' + evieApp.router.settings.taxonomies.category_name + '/' + el.value;
          } else {
            url = this.updateUrlParam(root, 'cat', el.value);
          }
          this.navigate(url, true);
        }
        return true;
      });
    });
  }
}
new SinglePage();
/******/ })()
;