/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Infinite Scroll
 *
 * Adds infinite scrolling support to the blog posts,
 * pulling the next set of posts automatically into view when the reader approaches the bottom of the page.
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  DocumentFragment,
  IntersectionObserver,
  evieApp,
  flextension
} = window;

/**
 * AJAX Pagination for the list of Posts.
 */
class AjaxPagination {
  constructor(element, options) {
    if (element === null) {
      return;
    }
    this.element = element;
    const defaults = {
      list: '.posts-list',
      pagination: '.pagination',
      items: '.entry'
    };
    this.settings = Object.assign({}, defaults, options || {});
    this.init();
  }
  init() {
    if (this.element.classList.contains('is-ajax-pagination')) {
      return;
    }
    this.pagination = this.element.querySelector(this.settings.pagination);
    if (this.pagination === null) {
      return;
    }
    this.list = this.element.querySelector(this.settings.list);
    if (this.list === null) {
      return;
    }
    this.element.classList.add('is-ajax-pagination');
    const id = this.element.getAttribute('id');
    this.partialRefresh = id && /([\w]+\-block\-)/.test(id) ? 'block' : 'posts';
    this.router = evieApp.router.create('ajaxPagination', (url, query) => {
      this.getPosts(url, query);
    }, () => {
      window.location.reload();
    }, true);
    this.initLinks();
    evieApp.emit('ajaxPagination.init', this);
  }
  navigate(url, saveState) {
    this.router.get(url, saveState);
  }
  initLinks() {
    if (this.pagination !== null) {
      const loadMore = this.pagination.classList.contains('loadmore-pagination');
      if (this.partialRefresh === 'posts' && this.pagination.classList.contains('numbered-pagination')) {
        this.partialRefresh = 'all';
      }
      this.pagination.querySelectorAll('a:not(.ajax-page)').forEach(link => {
        link.classList.add('ajax-page');
        link.addEventListener('click', event => {
          event.preventDefault();
          if (loadMore) {
            this.adjacent = link;
          }
          this.navigate(link.getAttribute('href'), !loadMore);
          return false;
        });
        if (loadMore && this.pagination.classList.contains('infinite-scroll') && link.classList.contains('next')) {
          const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
              if (entry.intersectionRatio > 0) {
                if (this.loading !== true && link.style.display !== 'none') {
                  this.loading = true;
                  link.style.display = 'none';
                  if (link.href) {
                    this.adjacent = link;
                    this.navigate(link.href, !loadMore);
                  }
                }
              }
            });
          });
          observer.observe(link);
        }
      });
    }
  }
  getQuery(query) {
    if (!query) {
      query = {};
    }
    if (this.partialRefresh === 'block') {
      query.block = this.element.getAttribute('id')?.replace(/([\w]+\-block\-)/, '');
      if (query.paged) {
        query.attributes = {
          page: query.paged
        };
        delete query.paged;
      }
    }
    return query;
  }
  beforeGetPosts() {
    this.loading = true;
    if (this.pagination.classList.contains('loadmore-pagination')) {
      this.updateMode = 'append';
      this.pagination.classList.add('is-loading');
    } else {
      this.updateMode = 'replace';
      this.element.classList.add('is-loading');
    }
    evieApp.emit('ajaxPagination.beforeGetPosts', this);
  }
  getPosts(url, query) {
    this.query = this.getQuery(query);
    let endPoint = 'posts';
    if (this.partialRefresh === 'block') {
      endPoint = 'block-renderer';
    } else if (this.partialRefresh === 'all') {
      endPoint = 'posts-renderer';
    }
    this.beforeGetPosts();
    flextension.api.get('/evie/v1/' + endPoint, this.query, response => {
      if (this.partialRefresh === 'posts') {
        this.updatePagination(response.headers.get('Link'));
      }
    }, {
      referrer: url
    }).then(data => {
      this.updatePosts(data);
    }).finally(() => {
      this.afterGetPosts();
    });
  }
  afterGetPosts() {
    this.loading = false;
    if (this.pagination.classList.contains('loadmore-pagination')) {
      this.pagination.classList.remove('is-loading');
    } else {
      this.element.classList.remove('is-loading');
    }
  }
  hasPosts() {
    return this.list !== null && this.list.querySelector(this.settings.items) !== null;
  }
  updatePosts(data) {
    if (this.hasPosts()) {
      this.beforeUpdatePosts();
      if (this.partialRefresh === 'posts' && Array.isArray(data) && data.length > 0) {
        const posts = [];
        const fragment = new DocumentFragment();
        data.forEach(item => {
          if (item.rendered) {
            const content = document.createElement('div');
            content.innerHTML = item.rendered;
            const post = content.firstChild;
            fragment.appendChild(post);
            posts.push(post);
          }
        });
        this.list.append(fragment);
        this.afterUpdatePosts(posts);
      } else if (data.rendered) {
        if (data.rendered) {
          const content = document.createElement('div');
          content.innerHTML = data.rendered;
          const posts = content.querySelectorAll(this.settings.items);
          if (posts.length > 0) {
            const fragment = new DocumentFragment();
            posts.forEach(post => {
              fragment.appendChild(post);
            });
            this.list.append(fragment);
            this.afterUpdatePosts(posts);
          }
          this.updatePagination(content);
        }
      }
      this.sendPageView();
    } else {
      location.reload();
    }
  }
  beforeUpdatePosts() {
    evieApp.emit('ajaxPagination.beforeUpdatePosts', this);
  }
  afterUpdatePosts(posts) {
    evieApp.emit('ajaxPagination.afterUpdatePosts', posts, this);
  }
  sendPageView() {
    // Checks to see if Google Analytics script is avaialble.
    if (typeof window.ga === 'function') {
      window.ga('send', 'pageview', location.pathname);
    }
  }
  updatePagination(content) {
    let updateLinks = false;
    if (this.partialRefresh === 'posts') {
      const links = parseLinks(content);
      if (this.pagination !== null) {
        const pageLink = this.pagination.querySelector('.nav-links');
        let prev = pageLink.querySelector('.prev');
        let next = pageLink.querySelector('.next');
        const current = pageLink.querySelector('.current');
        if (current !== null) {
          if (this.pagination.classList.contains('next-previous-pagination')) {
            current.innerHTML = this.query.paged || 1;
          }
        }
        if (links) {
          if (links.prev) {
            if (this.pagination.classList.contains('next-previous-pagination')) {
              if (prev === null) {
                prev = document.createElement('a');
                prev.setAttribute('href', links.prev);
                prev.setAttribute('class', 'prev');
                prev.setAttribute('class', 'prev');
                prev.innerHTML = '<i class="evie-ico-left"></i><span>' + evieApp.settings.strings.prev + '</span>';
                pageLink.prepend(prev);
                updateLinks = true;
              } else {
                prev.setAttribute('href', links.prev);
                this.pagination.style.display = '';
                prev.style.display = '';
              }
            } else if (!(this.adjacent && this.adjacent.classList.contains('next')) && prev !== null) {
              prev.setAttribute('href', links.prev);
              this.pagination.style.display = '';
              prev.style.display = '';
            }
          } else if (prev !== null) {
            prev.setAttribute('href', '');
            prev.style.display = 'none';
            if (this.pagination.classList.contains('loadmore-pagination')) {
              this.pagination.style.display = 'none';
            }
          }
          if (links.next) {
            if (this.pagination.classList.contains('next-previous-pagination')) {
              if (next === null) {
                next = document.createElement('a');
                next.setAttribute('href', links.next);
                next.setAttribute('class', 'next');
                next.innerHTML = '<span>' + evieApp.settings.strings.next + '</span><i class="evie-ico-right"></i>';
                pageLink.append(next);
                updateLinks = true;
              } else {
                next.setAttribute('href', links.next);
                this.pagination.style.display = '';
                next.style.display = '';
              }
            } else if (!(this.adjacent && this.adjacent.classList.contains('prev')) && next !== null) {
              next.setAttribute('href', links.next);
              this.pagination.style.display = '';
              next.style.display = '';
            }
          } else if (next !== null) {
            next.setAttribute('href', '');
            next.style.display = 'none';
            if (this.pagination.classList.contains('loadmore-pagination')) {
              this.pagination.style.display = 'none';
            }
          }
        } else {
          if (prev) {
            prev.setAttribute('href', '');
            prev.style.display = 'none';
            this.pagination.style.display = 'none';
          }
          if (next) {
            next.setAttribute('href', '');
            next.style.display = 'none';
            this.pagination.style.display = 'none';
          }
        }
      }
    } else {
      const pagination = content.querySelector(this.settings.pagination);
      if (pagination !== null) {
        this.pagination.innerHTML = pagination.innerHTML;
        updateLinks = true;
      } else if (this.pagination.classList.contains('loadmore-pagination')) {
        this.pagination.style.display = 'none';
        this.pagination.remove();
      }
    }
    if (updateLinks === true) {
      this.initLinks();
    }
  }
}

/**
 * AJAX Pagination within the Post (paginated post with <!--nextpage-->).
 */
class AjaxPostPagination {
  constructor(element) {
    if (element === null) {
      return;
    }
    if (element.classList.contains('is-post-pagination')) {
      return;
    }
    element.classList.add('is-post-pagination');
    this.element = element;
    this.container = this.element.parentElement;
    this.loading = false;
    this.pathname = location.pathname;
    this.router = evieApp.router.create('ajaxPostPagination', (url, query) => {
      this.getPost(url, query);
    }, () => {
      location.reload();
    });
    this.init();
  }
  init() {
    this.element.querySelectorAll('a:not(.ajax-page)').forEach(link => {
      link.classList.add('ajax-page');
      if (link.href) {
        link.addEventListener('click', event => {
          event.preventDefault();
          this.fetch(link.href);
          return true;
        });
        if (this.element.classList.contains('infinite-scroll')) {
          const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
              if (entry.intersectionRatio > 0) {
                if (this.loading !== true && link.style.display !== 'none') {
                  this.loading = true;
                  link.style.display = 'none';
                  this.fetch(link.href);
                }
              }
            });
          });
          observer.observe(link);
        }
      }
    });
  }
  fetch(url) {
    this.pathname = url.pathname;
    this.router.get(url);
  }
  beforeGetPost() {
    this.loading = true;
    this.element.classList.add('is-loading');
  }
  getPost(url, query) {
    this.beforeGetPost();
    flextension.api.get('/evie/v1/post', query, response => {
      this.updateLinkPages(response.headers.get('Link'));
    }, {
      referrer: url
    }).then(data => {
      this.updatePost(data);
    }).finally(() => {
      this.afterGetPost();
    });
  }
  afterGetPost() {
    this.loading = false;
    this.element.classList.remove('is-loading');
  }
  updatePost(data) {
    const content = document.createElement('div');
    content.classList.add('entry-content');
    content.innerHTML = data.rendered;
    this.container.insertBefore(content, this.element);
    this.sendPageView();
    evieApp.emit('ajaxPagination.afterUpdatePost', content);
  }
  sendPageView() {
    // Checks to see if Google Analytics script is avaialble.
    if (typeof window.ga === 'function') {
      window.ga('send', 'pageview', this.pathname);
    }
  }
  updateLinkPages(link) {
    const nextLink = this.element.querySelector('a');
    if (nextLink !== null) {
      const links = parseLinks(link);
      if (links && links.next) {
        setTimeout(() => {
          nextLink.setAttribute('href', links.next);
          this.element.style.display = '';
          nextLink.style.display = '';
        }, 500);
      } else {
        this.element.innerHTML = '';
        this.element.style.display = 'none';
      }
    }
  }
}

/**
 * AJAX Pagination within the Comments.
 */
class AjaxCommentsPagination {
  constructor(element) {
    if (element === null) {
      return;
    }
    if (element.classList.contains('is-comments-pagination')) {
      return;
    }
    element.classList.add('is-comments-pagination');
    this.element = element;
    this.container = this.element.parentElement;
    this.adjacent = 'prev';
    this.order = 'asc';
    this.loading = false;
    this.pathname = location.pathname;
    this.router = evieApp.router.create('ajaxCommentsPagination', (url, query) => {
      this.getComments(url, query);
    }, () => {
      location.reload();
    });
    this.init();
  }
  init() {
    this.element.querySelectorAll('a:not(.ajax-page)').forEach(link => {
      link.classList.add('ajax-page');
      if (link.href) {
        link.addEventListener('click', event => {
          event.preventDefault();
          this.adjacent = link.classList.contains('next') ? 'next' : 'prev';
          this.fetch(link.href);
          return true;
        });
      }
    });
  }
  fetch(url) {
    this.pathname = url.pathname;
    this.router.get(url);
  }
  beforeGetComments() {
    this.loading = true;
    this.element.classList.add('is-loading');
  }
  getComments(url, query) {
    query.page = query.cpage;
    delete query.cpage;
    this.beforeGetComments();
    flextension.api.get('/evie/v1/comments', query, response => {
      this.order = response.headers.get('X-WP-Order');
      this.updateCommentsPagination(response.headers.get('Link'));
    }, {
      referrer: url
    }).then(data => {
      this.updateComments(data);
    }).catch(error => {
      const content = document.createElement('p');
      content.innerHTML = error;
      this.container.insertBefore(content, this.element);
    }).finally(() => {
      this.afterGetComments();
    });
  }
  afterGetComments() {
    this.loading = false;
    this.element.classList.remove('is-loading');
  }
  updateComments(data) {
    const content = document.createElement('ol');
    content.classList.add('comment-list');
    content.innerHTML = data;
    if (this.adjacent === 'next' && this.order === 'asc' || this.adjacent === 'prev' && this.order === 'desc') {
      this.container.insertBefore(content, this.element);
    } else {
      evieApp.scrollTo(this.container);
      this.container.prepend(content);
    }
    evieApp.emit('ajaxPagination.afterUpdateComments', content);
  }
  updateCommentsPagination(link) {
    const links = parseLinks(link);
    if (links.next || links.prev) {
      const nextLink = this.element.querySelector('.next');
      if (nextLink !== null) {
        if (links.next) {
          nextLink.setAttribute('href', links.next);
        } else {
          nextLink.setAttribute('href', '#');
          nextLink.style.display = 'none';
          this.element.style.display = 'none';
        }
      }
      const prevLink = this.element.querySelector('.prev');
      if (prevLink !== null) {
        if (links.prev) {
          prevLink.setAttribute('href', links.prev);
        } else {
          prevLink.setAttribute('href', '#');
          prevLink.style.display = 'none';
          this.element.style.display = 'none';
        }
      }
    } else {
      this.element.innerHTML = '';
      this.element.style.display = 'none';
    }
  }
}

/**
 * Parses the link from the response header.
 *
 * @param {string} link The link to parse.
 * @return {Object} The links object.
 */
function parseLinks(link) {
  if (link.length === 0) {
    return false;
  }
  // Split parts by comma
  const parts = link.split(',');
  const links = [];
  // Parse each part into a named link
  parts.forEach(item => {
    const sections = item.split(';');
    if (sections.length !== 2) {
      return false;
    }
    const url = sections[0].replace(/<(.*)>/, '$1').trim();
    const name = sections[1].replace(/rel="(.*)"/, '$1').trim();
    links[name] = url;
  });
  return links;
}
evieApp.on('ready', (context, content) => {
  if (document.body.classList.contains('customizer')) {
    return;
  }
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.evie-posts').forEach(el => {
    new AjaxPagination(el);
  });
  content.querySelectorAll('.flext-block-authors').forEach(el => {
    new AjaxPagination(el, {
      list: '.flext-authors-list',
      pagination: '.pagination',
      items: '.flext-author-entry'
    });
  });
  content.querySelectorAll('.post-pagination.loadmore-pagination').forEach(el => {
    new AjaxPostPagination(el);
  });
  content.querySelectorAll('.comments-section .loadmore-pagination').forEach(el => {
    new AjaxCommentsPagination(el);
  });
});
/******/ })()
;