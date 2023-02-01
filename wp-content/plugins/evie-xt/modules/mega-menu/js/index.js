/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Mega Menu
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  flextension,
  evieApp
} = window;
class MegaMenu {
  constructor(menu) {
    if (!menu) {
      return;
    }
    if (menu.dataset.megaMenu) {
      return;
    }
    menu.dataset.megaMenu = true;
    this.menu = menu;
    this.menuItems = this.menu.querySelectorAll('.sub-menu .menu-item');
    this.contentPanels = this.menu.querySelectorAll('.evie-mm-content');
    this.currentIndex = 0;
    this.init();
  }
  init() {
    if (this.contentPanels !== null) {
      const container = document.createElement('div');
      container.classList.add('sub-menu');
      this.menu.append(container);
      const subMenu = this.menu.querySelector('ul');
      if (subMenu !== null) {
        subMenu.classList.remove('sub-menu');
        subMenu.classList.add('evie-mm-categories');
        container.append(subMenu);
      }
      const content = this.menu.querySelector('.evie-mm-posts');
      if (content !== null) {
        container.append(content);
      }
      this.menu.addEventListener('mouseenter', () => {
        this.show();
      });
      this.menuItems.forEach((item, index) => {
        item.addEventListener('mouseenter', () => {
          this.currentIndex = index;
          this.show();
        });
      });
    }
  }
  show() {
    this.hide();
    if (this.currentIndex < this.menuItems.length && this.menuItems[this.currentIndex]) {
      this.menuItems[this.currentIndex].classList.add('is-visible');
    }
    if (this.currentIndex < this.contentPanels.length && this.contentPanels[this.currentIndex]) {
      this.contentPanels[this.currentIndex].classList.add('content-active');
      if (!this.contentPanels[this.currentIndex].classList.contains('is-loaded')) {
        this.loadPosts(this.currentIndex);
      }
    }
  }
  hide() {
    this.menuItems.forEach((item, index) => {
      if (index !== this.currentIndex) {
        item.classList.remove('is-visible');
      }
    });
    this.contentPanels.forEach((panel, index) => {
      if (index !== this.currentIndex) {
        panel.classList.remove('content-active');
      }
    });
  }
  getColumnsPerRow(grid) {
    let columns = 4;
    const style = window.getComputedStyle(grid);
    if (style) {
      columns = parseInt(style.getPropertyValue('--evie-mega-menu-posts-columns'), 10);
    }
    return columns;
  }
  loadPosts(index) {
    if (this.contentPanels[index].classList.contains('is-loading')) {
      return;
    }
    this.contentPanels[index].classList.add('is-loading');
    const columns = this.getColumnsPerRow(this.contentPanels[index]);
    const itemTemplate = '<article class="entry mega-menu-item-placeholder"><div class="entry-media"></div><div class="entry-header"></div></article>';
    this.contentPanels[index].innerHTML = itemTemplate.repeat(columns);
    flextension.api.get('/evie/v1/mega-menu', {
      taxonomy: this.contentPanels[index].dataset.taxonomy || 'category',
      term: this.contentPanels[index].dataset.term || 0,
      columns
    }).then(data => {
      this.displayPosts(index, data.rendered);
      this.contentPanels[index].classList.remove('is-loading');
    });
  }
  displayPosts(index, posts) {
    if (index < this.contentPanels.length) {
      this.contentPanels[index].innerHTML = posts;
      this.contentPanels[index].classList.add('is-loaded');
      evieApp.emit('megaMenu.afterDisplayPosts', this.contentPanels[index]);
      setTimeout(() => {
        if (flextension) {
          flextension.emit('ready', this.contentPanels[index]);
        }
        evieApp.emit('ready', this.contentPanels[index]);
      }, 500);
    }
  }
}
evieApp.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('#primary-menu .evie-mega-menu.evie-mm-has-posts').forEach(menu => {
    new MegaMenu(menu);
  });
});
/******/ })()
;