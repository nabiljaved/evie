/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension AJAX Live Search
 *
 * Displays live search suggestions while typing a keyword, this is an extension of the Flextension main app.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;
class LiveSearch {
  constructor(el, options) {
    if (el.dataset.flextLiveSearch === true) {
      return;
    }
    el.dataset.flextLiveSearch = true;
    this.settings = options || {};
    this.DOM = {
      el
    };
    this.init();
  }
  init() {
    this.initSearchForm();
    this.createSearchResults();
  }
  initSearchForm() {
    this.DOM.searchForm = this.DOM.el.querySelector(this.settings.form);
    if (this.DOM.searchForm === null) {
      return;
    }
    this.DOM.keywordField = this.DOM.searchForm.querySelector('#flext-search-keyword');
    if (this.DOM.keywordField !== null) {
      this.DOM.keywordField.setAttribute('autocomplete', 'off');
      this.DOM.keywordField.addEventListener('focus', flextension.debounce(() => {
        if (!this.DOM.el.classList.contains('active')) {
          this.doSearch();
        }
      }, this.settings.delay));
      this.DOM.keywordField.addEventListener('input', flextension.debounce(() => {
        this.doSearch();
      }, this.settings.delay));
    }
    this.DOM.clearSearchButton = this.DOM.searchForm.querySelector('.clear-search-button');
    if (this.DOM.clearSearchButton !== null) {
      this.DOM.clearSearchButton.addEventListener('click', () => {
        this.DOM.el.classList.remove('active');
        this.clearSearchList();
        this.DOM.clearSearchButton.hidden = true;
        this.DOM.keywordField.value = '';
        this.DOM.keywordField.focus();
      });
    }
    this.DOM.searchForm.setAttribute('onsubmit', 'return false;');
  }
  createSearchResults() {
    this.DOM.searchResults = this.DOM.el.querySelector('.live-search-results');
    this.DOM.resultsList = this.DOM.el.querySelector('.search-results-list');
    this.DOM.moreButton = this.DOM.el.querySelector('.search-more');
    if (this.DOM.searchResults === null) {
      this.DOM.searchResults = document.createElement('div');
      this.DOM.searchResults.classList.add('live-search-results');
      this.DOM.el.append(this.DOM.searchResults);
    }
    if (this.DOM.resultsList === null) {
      this.DOM.resultsList = document.createElement('div');
      this.DOM.resultsList.classList.add('search-results-list', 'flext-list-group');
      this.DOM.searchResults.append(this.DOM.resultsList);
    }
    if (this.DOM.moreButton === null) {
      this.DOM.moreButton = document.createElement('div');
      this.DOM.moreButton.classList.add('search-more');
      this.DOM.el.append(this.DOM.moreButton);
    }
    flextension.emit('liveSearch.createResults');
    this.updateSearchList();
    window.addEventListener('resize', flextension.debounce(() => {
      this.updateSearchList();
    }, 300));
  }
  updateSearchList() {
    flextension.emit('liveSearch.updateResults');
  }
  getListItems(name, items) {
    const list = document.createElement('ul');
    list.classList.add('flext-list');
    list.setAttribute('id', name.toLowerCase() + '-search-list');
    let showThumbnail = false;
    let showAuthor = false;
    let showDate = false;
    items.forEach(item => {
      let thumbnail = '';
      if (item.thumbnail) {
        showThumbnail = true;
        thumbnail = '<img src="' + item.thumbnail + '" alt="' + item.title + '" height="150" width="150" />';
      } else {
        thumbnail = '<i class="flext-ico-article"></i>';
      }
      thumbnail = '<span class="item-thumbnail">' + thumbnail + '</span>';
      let author = '';
      if (item.author) {
        showAuthor = true;
        author = '<span>' + item.author + '</span> ';
      }
      let date = '';
      if (item.date) {
        showDate = true;
        date = '<span>' + item.date + '</span> ';
      }
      let description = '';
      if (author || date) {
        description = '<span class="item-meta">' + author + date + '</span>';
      }
      let title = item.title;
      if (title) {
        title = '<strong class="item-title">' + title + '</strong>';
      }
      const listItem = document.createElement('li');
      listItem.innerHTML = '<a href="' + item.post_link + '">' + thumbnail + '<span class="item-header">' + title + description + '</span></a>';
      list.append(listItem);
    });
    if (showThumbnail) {
      list.classList.add('flext-avatar-list');
    }
    if (showAuthor || showDate) {
      list.classList.add('flext-list-two-line');
    }
    return list;
  }
  clearSearchList() {
    if (this.DOM.resultsList !== null) {
      this.DOM.resultsList.innerHTML = '';
      this.DOM.moreButton.innerHTML = '';
      this.updateSearchList();
    }
  }
  showResults() {
    this.DOM.el.classList.add('active');
    flextension.emit('liveSearch.showResults', this.DOM.el);
  }
  hideResults() {
    this.DOM.el.classList.remove('active');
    flextension.emit('liveSearch.hideResults', this.DOM.el);
  }
  loadResults() {
    if (this.DOM.searchResults === null) {
      this.createSearchResults();
    }
    this.showResults();
    this.DOM.el.classList.add('searching');
    flextension.ajax({
      data: {
        action: 'flextension_live_search',
        keyword: this.DOM.keywordField.value
      },
      callback: data => {
        this.DOM.resultsList.innerHTML = '';
        if (data && Array.isArray(data.results) && data.results.length > 0) {
          const results = data.results;
          results.sort((a, b) => {
            const x = a.title;
            const y = b.title;
            if (x < y) {
              return -1;
            }
            return x > y ? 1 : 0;
          });
          results.forEach(item => {
            const title = document.createElement('h4');
            title.innerHTML = item.title;
            this.DOM.resultsList.append(title);
            this.DOM.resultsList.append(this.getListItems(item.name, item.items));
          });
          this.DOM.moreButton.innerHTML = data.moreLink;
          flextension.emit('liveSearch.afterSearchResultsLoad', this.DOM.el);
        } else if (data && data.message) {
          this.DOM.resultsList.innerHTML = '<p class="search-status">' + data.message + '</p>';
          this.DOM.moreButton.innerHTML = '';
        }
        this.DOM.el.classList.remove('searching');
        this.DOM.clearSearchButton.hidden = false;
        this.updateSearchList();
      }
    });
  }
  doSearch() {
    if (this.DOM.keywordField.value.length < this.settings.minlength) {
      this.hideResults();
      this.clearSearchList();
      return;
    }
    this.loadResults();
  }
}
flextension.on('ready', (e, content) => {
  if (content) {
    return;
  }
  const settings = Object.assign({
    delay: 500,
    element: '#flext-live-search',
    form: '#flext-live-search-form',
    minlength: 2
  }, flextension.settings.liveSearch || {});
  if (settings.element) {
    document.querySelectorAll(settings.element).forEach(el => {
      new LiveSearch(el, settings);
    });
  }
});
/******/ })()
;