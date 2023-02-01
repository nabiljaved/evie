/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Author Follow Button
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension,
  IntersectionObserver
} = window;

/**
 * Initializes Follow button for the post.
 *
 * @param {Element} content The content to initialize.
 */
function initFollowButton(content) {
  content.querySelectorAll('.flext-author-follow').forEach(button => {
    button.addEventListener('click', event => {
      event.preventDefault();
      const authorId = button.dataset.authorId;
      if (!authorId) {
        return;
      }
      const buttons = content.querySelectorAll('.flext-author-follow[data-author-id="' + authorId + '"]');
      buttons.forEach(el => {
        el.classList.add('flext-is-loading');
      });
      flextension.ajax({
        method: 'POST',
        data: {
          action: 'flextension_author_follow',
          author: authorId
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
                  el.classList.remove('flext-follow-button');
                  el.classList.add('flext-unfollow-button');
                } else {
                  el.classList.remove('flext-unfollow-button');
                  el.classList.add('flext-follow-button');
                }
                el.setAttribute('title', data.button.title);
              });
              const label = button.querySelector('span');
              if (label !== null) {
                label.innerHTML = data.message;
              }
            } else if (401 === status) {
              if (flextension.showLoginForm) {
                flextension.showLoginForm();
              } else {
                new flextension.lightbox('<p>' + data.message + '</p>', {
                  className: 'flext-author-follow-modal',
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
 * Displays a list of followers or following.
 *
 * @param {number} id   Author ID.
 * @param {string} list A followers or following list to show.
 */
function showFollowers(id, list) {
  new flextension.lightbox(`#flext-author-${list}-list-${id}`, {
    className: 'flext-author-follow-modal',
    fullscreen: false
  }).load({
    endpoint: '/flextension/v1/' + list,
    data: {
      id
    },
    callback: (content, lightbox) => {
      lightbox.content.innerHTML = content.rendered;
      initFollowersPagination(lightbox.content);
      flextension.emit('ready', lightbox.content);
    }
  });
}

/**
 * Initializes the links of the followers and following numbers.
 *
 * @param {Element} content The content to initialize.
 */
function initFollowLinks(content) {
  content.querySelectorAll('.flext-author-follow-numbers a').forEach(button => {
    button.addEventListener('click', event => {
      event.preventDefault();
      if (button.classList.contains('is-empty')) {
        return;
      }
      const authorId = button.dataset.authorId;
      if (!authorId) {
        return;
      }
      const list = button.classList.contains('flext-author-followers') ? 'followers' : 'following';
      showFollowers(authorId, list);
    });
  });
}

/**
 * Retrieves followers list.
 *
 * @param {string}   type     Type of list items, accepts followers and following.
 * @param {number}   userId   User ID.
 * @param {number}   page     Page number.
 * @param {Function} callback A callback function.
 */
function loadFollowers(type, userId, page, callback) {
  flextension.api.get('/flextension/v1/' + type, {
    id: userId,
    page
  }).then(content => {
    if (typeof callback === 'function') {
      callback(content, this);
    }
  });
}

/**
 * Initializes the followers list pagination.
 *
 * @param {Element} content The content to initialize.
 */
function initFollowersPagination(content) {
  content.querySelectorAll('.pagination a:not(.is-followers-pagination)').forEach(link => {
    if (link.classList.contains('is-followers-pagination')) {
      return;
    }
    link.classList.add('is-followers-pagination');
    const page = link.dataset.page;
    if (page < 2) {
      return;
    }
    const container = link.closest('.flext-author-follow-list');
    if (container === null) {
      return;
    }
    const type = container.parentElement.classList.contains('flext-author-followers-list') ? 'followers' : 'following';
    const userId = container.dataset.userId;
    let isLoading = false;
    const showItems = data => {
      isLoading = false;
      link.parentElement.remove();
      if (data.rendered) {
        const template = document.createElement('div');
        template.innerHTML = data.rendered;
        container.append(template);
        initFollowersPagination(template);
        flextension.emit('afterFollowersLoaded', template);
        flextension.emit('ready', template);
      }
    };
    link.addEventListener('click', event => {
      event.preventDefault();
      if (isLoading !== true) {
        isLoading = true;
        link.classList.add('flext-is-loading');
        loadFollowers(type, userId, page, showItems);
      }
    });
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.intersectionRatio > 0) {
          if (isLoading !== true) {
            isLoading = true;
            link.classList.add('flext-is-loading');
            loadFollowers(type, userId, page, showItems);
          }
        }
      });
    });
    observer.observe(link);
  });
}

/**
 * Initializes the Follow button.
 */
flextension.on('ready', (event, content) => {
  if (!content) {
    content = document;
  }
  initFollowButton(content);
  initFollowLinks(content);
});
/******/ })()
;