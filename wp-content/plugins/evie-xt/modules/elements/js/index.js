/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 2681:
/***/ (function() {

const {
  evieApp,
  flextension
} = window;
evieApp.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.evie-block-post-carousel').forEach(block => {
    const carousel = block.querySelector('.posts-layout-carousel');
    if (carousel !== null) {
      const isHorizontal = block.classList.contains('is-style-horizontal');
      const getColumns = (columns, max) => {
        if (isHorizontal && (evieApp.browser.lg || evieApp.browser.xl)) {
          max = max - 1;
        }
        return Math.min(parseInt(columns, 10) || max, max);
      };
      new flextension.carousel(carousel, {
        navigation: {
          nextEl: block.querySelector('.flext-button-next'),
          prevEl: block.querySelector('.flext-button-prev')
        },
        spaceBetween: parseInt(carousel.dataset.spaceBetween, 10) || 0,
        breakpoints: {
          768: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 2),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 2)
          },
          1024: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 3),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 3)
          },
          1200: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 4),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 4)
          },
          1600: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 5),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 5)
          },
          1920: {
            slidesPerView: getColumns(carousel.dataset.slidesPerView, 6),
            slidesPerGroup: getColumns(carousel.dataset.slidesPerView, 6)
          }
        }
      });
    }
  });
});

/***/ }),

/***/ 5598:
/***/ (function() {

const {
  ScrollMagic,
  gsap,
  evieApp
} = window;
function scrollingText(element) {
  const slideText = element.querySelector('.scrolling-text');
  if (slideText.offsetWidth < window.innerWidth * 2) {
    const text = slideText.textContent.replace(/(\r\n|\n|\r)/gm, '') + ' ';
    slideText.innerText = text.repeat(Math.round(window.innerWidth * 2 / slideText.offsetWidth));
  }
  const reverse = element.classList.contains('scroll-dir-ltr');
  if (reverse === true) {
    gsap.set(slideText, {
      x: -window.innerWidth
    });
  }
  new ScrollMagic.Scene({
    triggerElement: element,
    triggerHook: 1,
    duration: () => {
      return element.offsetHeight + window.innerHeight;
    }
  }).on('progress', e => {
    const x = e.progress * -window.innerWidth;
    gsap.to(slideText, {
      x: reverse ? -window.innerWidth - x + 'px' : x + 'px',
      duration: 0.3
    });
  }).addTo(evieApp.scrollController);
  element.classList.add('scrolling-initialized');
}
evieApp.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.evie-block-scrolling-text').forEach(el => {
    scrollingText(el);
  });
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";

;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/split-slider.js
/**
 * Split Slider
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  gsap
} = window;

/**
 * Slide class
 */
class Slide {
  constructor(el) {
    this.DOM = {
      el
    };
  }
  setCurrent() {
    this.DOM.el.classList.add('current-slide');
  }
  reset() {
    this.DOM.el.classList.remove('current-slide');
  }
}

/**
 * Column class
 */
class Column {
  constructor(el, options) {
    this.DOM = {
      el
    };
    // The slide settings.
    this.settings = {
      reverse: false,
      duration: 1.2,
      ease: 'Expo.easeInOut'
    };
    Object.assign(this.settings, options || {});
    this.slides = [];
    this.current = 0;
    this.init();
  }

  /**
   * Initialize slides in the column.
   */
  init() {
    this.DOM.el.querySelectorAll('.evie-slide').forEach(slide => {
      if (true === this.settings.reverse) {
        this.DOM.el.insertBefore(slide, this.DOM.el.firstChild);
      }
      this.slides.push(new Slide(slide));
    });
  }

  /**
   * Loads the slides.
   *
   * @param {number} index The active slide index.
   */
  load(index) {
    this.slideTo(index, false);
  }

  /**
   * Navigates to specific slide.
   *
   * @param {number}  index   The slide index to navigate to.
   * @param {boolean} animate Whether the slide should be animated.
   */
  slideTo(index) {
    let animate = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    return new Promise(resolve => {
      this.slides[this.current].reset();
      let translate = index;
      if (this.settings.reverse) {
        translate = this.slides.length - 1 - index;
      }
      if (!animate) {
        gsap.set(this.DOM.el, {
          y: -(translate * 100) + '%',
          onComplete: () => {
            this.current = index;
            this.slides[index].setCurrent();
          }
        });
      } else {
        gsap.to(this.DOM.el, {
          y: -(translate * 100) + '%',
          duration: this.settings.duration,
          ease: this.settings.ease,
          onComplete: () => {
            this.current = index;
            this.slides[index].setCurrent();
            resolve();
          }
        });
      }
    });
  }
}

/**
 * The navigation class. Controls the slide animations (e.g. pagination animation).
 */
class Navigation {
  constructor(el, options) {
    if (!el) {
      return;
    }
    this.DOM = {
      el
    };
    this.settings = {
      total: 0,
      current: 0,
      slideTo: () => {
        return false;
      }
    };
    Object.assign(this.settings, options);
    this.current = this.settings.current;
    this.buttons = [];
    this.init();
  }

  /**
   * Initializes the navigation.
   */
  init() {
    const buttonWrapper = document.createElement('div');
    buttonWrapper.classList.add('evie-page-buttons');
    this.DOM.el.appendChild(buttonWrapper);
    this.prevButton = document.createElement('span');
    this.prevButton.classList.add('evie-nav-button', 'evie-button-prev');
    this.prevButton.innerHTML = '<i class="evie-ico-arrow-up"></i>';
    this.prevButton.addEventListener('click', e => {
      e.preventDefault();
      const index = this.current - 1;
      if (index < 0) {
        return;
      }
      this.slideTo(index);
    });
    buttonWrapper.appendChild(this.prevButton);
    this.currentPage = document.createElement('span');
    this.currentPage.classList.add('evie-first-page');
    this.currentPage.innerHTML = ('0' + (this.current + 1)).slice(-2);
    buttonWrapper.appendChild(this.currentPage);
    for (let i = 0; i < this.settings.total; i++) {
      const button = document.createElement('span');
      button.classList.add('evie-page-button');
      button.addEventListener('click', e => {
        e.preventDefault();
        this.slideTo(i);
        return false;
      });
      this.buttons.push(button);
      buttonWrapper.appendChild(button);
    }
    this.totalPage = document.createElement('span');
    this.totalPage.classList.add('evie-last-page');
    this.totalPage.innerHTML = ('0' + this.settings.total).slice(-2);
    buttonWrapper.appendChild(this.totalPage);
    this.nextButton = document.createElement('span');
    this.nextButton.classList.add('evie-nav-button', 'evie-button-next');
    this.nextButton.innerHTML = '<i class="evie-ico-arrow-down"></i>';
    this.nextButton.addEventListener('click', e => {
      e.preventDefault();
      const index = this.current + 1;
      if (index > this.settings.total - 1) {
        return;
      }
      this.slideTo(index < this.settings.total ? index : 0);
    });
    buttonWrapper.appendChild(this.nextButton);
  }

  /**
   * Sets the current slide to show.
   *
   * @param {number} current The current slide index.
   */
  setCurrent(current) {
    if (this.buttons && this.buttons.length > 0) {
      this.current = current;
      this.currentPage.innerText = ('0' + (this.current + 1)).slice(-2);
      this.buttons.forEach((button, index) => {
        if (index === this.current) {
          button.classList.add('current');
        } else {
          button.classList.remove('current');
        }
      });
    }
    if (this.prevButton) {
      this.prevButton.classList.toggle('evie-button-disabled', this.current === 0);
    }
    if (this.nextButton) {
      this.nextButton.classList.toggle('evie-button-disabled', this.current === this.settings.total - 1);
    }
  }

  /**
   * Slides to target index.
   *
   * @param {number} index Slide index.
   */
  slideTo(index) {
    if (typeof this.settings.slideTo === 'function') {
      this.settings.slideTo(index);
    }
  }
}

/**
 * Split Slider class.
 */
class SplitSlider {
  constructor(el, options) {
    if (!el) {
      return;
    }
    if (el.classList.contains('is-split-slider-initialized')) {
      return;
    }
    el.classList.add('is-split-slider-initialized');
    this.DOM = {
      el
    };
    this.DOM.wrapper = this.DOM.el.querySelector('.evie-slides');
    this.settings = {
      start: 0,
      loop: false,
      mousewheel: true,
      keyboard: true,
      touch: true,
      duration: 0.3,
      ease: 'Expo.easeInOut',
      onInit: () => {},
      onChange: () => {},
      onBeforeChange: () => {}
    };
    Object.assign(this.settings, options || {});
    this.columns = [];
    this.slides = [];
    this.DOM.el.querySelectorAll('.evie-column').forEach(col => {
      if (!col.dataset.reverse) {
        const column = new Column(col);
        this.slides = column.slides;
        this.columns.push(column);
      } else {
        this.columns.push(new Column(col, {
          reverse: true
        }));
      }
    });
    this.slidesTotal = this.slides.length;

    // Initialize the navigation instance. When clicking the next or prev ctrl buttons, trigger the navigate function.
    this.navigation = new Navigation(this.DOM.el.querySelector('.slider-pagination'), {
      slideTo: index => this.slideTo(index),
      total: this.slidesTotal
    });

    // Current slide position.
    this.current = 0;
    // Initialize the slideshow.
    this.init();
    this.load();
  }

  /**
   * Returns whether an element is within the viewport.
   *
   * @param {Element} element Target element.
   * @return {boolean} Whether the element is within the viewport.
   */
  isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return !(rect.bottom < 0 || rect.top - window.innerHeight >= 0);
  }

  /**
   * Initializes the slider.
   */
  init() {
    this.initMouseWheel();
    this.initKeyboard();
    this.initTouch();
    if (typeof this.settings.onInit === 'function') {
      this.settings.onInit(this);
    }
  }
  initMouseWheel() {
    if (this.settings.mousewheel === true) {
      // Play slides using mouse scroll.
      this.DOM.el.addEventListener('wheel', e => {
        let playSlide = true;
        if (e.deltaY > 0) {
          playSlide = this.navigate('next');
        } else {
          playSlide = this.navigate('prev');
        }
        if (true === playSlide) {
          e.preventDefault();
          return false;
        }
      });
    }
  }
  initKeyboard() {
    if (this.settings.keyboard === true) {
      // Play slides using keyboard.
      const keys = ['ArrowUp', 'ArrowDown', 'ArrowArrowLeft', 'ArrowRight'];
      window.addEventListener('keydown', e => {
        if (-1 !== keys.indexOf(e.key)) {
          if (!this.isInViewport(this.DOM.wrapper)) {
            return;
          }
          let playSlide = true;
          if ('ArrowDown' === e.key) {
            playSlide = this.navigate('next');
          } else if ('ArrowUp' === e.key) {
            playSlide = this.navigate('prev');
          }
          if (window.scrollY > 10) {
            return true;
          }
          if (true === playSlide) {
            e.preventDefault();
            return false;
          }
        }
      }, false);
    }
  }
  initTouch() {
    if (this.settings.touch) {
      let xDown = null;
      let yDown = null;
      const getTouches = event => {
        return event.touches || event.originalEvent.touches;
      };
      const handleTouchStart = event => {
        const firstTouch = getTouches(event)[0];
        xDown = firstTouch.clientX;
        yDown = firstTouch.clientY;
      };
      const handleTouchMove = event => {
        if (!xDown || !yDown) {
          return;
        }
        const xUp = event.touches[0].clientX;
        const yUp = event.touches[0].clientY;
        const xDiff = xDown - xUp;
        const yDiff = yDown - yUp;
        let playSlide = true;
        if (Math.abs(xDiff) > Math.abs(yDiff)) {
          /*most significant*/
          if (xDiff > 0) {
            /* left swipe */
          } else {
            /* right swipe */
          }
        } else if (yDiff > 0) {
          playSlide = this.navigate('next');
        } else {
          playSlide = this.navigate('prev');
        }
        /* reset values */
        xDown = null;
        yDown = null;
        if (true === playSlide) {
          return false;
        }
      };
      document.addEventListener('touchstart', handleTouchStart, false);
      document.addEventListener('touchmove', handleTouchMove, false);
    }
  }

  /**
   * Loads the slider.
   */
  load() {
    if (this.columns.length > 0 && this.slides.length > 0) {
      this.columns.forEach(column => {
        column.load(this.settings.start);
      });
      this.navigation.setCurrent(this.settings.start);
      this.slideChange(this.settings.start);
      if (this.settings.start !== this.current) {
        this.slideTo(this.current);
      }
    }
    this.DOM.el.classList.add('is-loaded');
  }

  /**
   * Navigates to the given direction.
   *
   * @param {string} direction The slide direction between next and previous.
   */
  navigate(direction) {
    let nextSlideIndex;
    if ('next' === direction) {
      nextSlideIndex = this.current + 1;
      if (nextSlideIndex > this.slidesTotal - 1) {
        if (this.settings.loop !== true) {
          return false;
        }
        nextSlideIndex = 0;
      }
    } else {
      nextSlideIndex = this.current - 1;
      if (nextSlideIndex < 0) {
        if (this.settings.loop !== true) {
          return false;
        }
        nextSlideIndex = this.slidesTotal - 1;
      }
    }
    this.slideTo(nextSlideIndex);
    return true;
  }

  /**
   * Slides to the specific slide.
   *
   * @param {number} index The slide index to navigate to.
   */
  slideTo(index) {
    // If animating return.
    if (this.isAnimating) {
      return false;
    }
    this.isAnimating = true;
    if (typeof this.settings.onBeforeChange === 'function') {
      this.settings.onBeforeChange(this, this.slides[index]);
    }
    if (index > this.current) {
      this.DOM.el.classList.remove('slide-to-prev');
      this.DOM.el.classList.add('slide-to-next');
    } else {
      this.DOM.el.classList.remove('slide-to-next');
      this.DOM.el.classList.add('slide-to-prev');
    }
    Promise.all([this.columns[0].slideTo(index), this.columns[1].slideTo(index, true)]).then(() => {
      this.current = index;
      this.slideChange(index);
      this.isAnimating = false;
    });
    this.navigation.setCurrent(index);
  }
  slideChange(index) {
    if (this.slides.length === 0) {
      return;
    }
    if (typeof this.settings.onChange === 'function') {
      this.settings.onChange(this, this.slides[index]);
    }
    const slide = this.slides[index];
    if (slide && slide.DOM.el.classList.contains('has-scheme-light')) {
      this.navigation.DOM.el.classList.remove('has-scheme-dark');
      this.navigation.DOM.el.classList.add('has-scheme-light');
    } else if (slide.DOM.el.classList.contains('has-scheme-dark')) {
      this.navigation.DOM.el.classList.remove('has-scheme-light');
      this.navigation.DOM.el.classList.add('has-scheme-dark');
    } else {
      this.navigation.DOM.el.classList.remove('has-scheme-light', 'has-scheme-dark');
    }
  }
}
/* harmony default export */ var split_slider = (SplitSlider);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/index.js

const {
  imagesLoaded,
  evieApp,
  flextension,
  gsap: featured_posts_gsap
} = window;
let isResizeEventAdded = false;
/**
 * Initializes the Featured Slider.
 *
 * @param {Element} content The content element.
 */
function initFeaturedSliders(content) {
  const fullSlider = content.querySelector('.evie-block-featured-posts.evie-disable-scrolling');
  if (fullSlider !== null) {
    setTimeout(() => {
      document.documentElement.classList.add('no-scroll');
      document.body.classList.add('no-scroll');
    }, 500);
  } else {
    setTimeout(() => {
      document.documentElement.classList.remove('no-scroll');
      document.body.classList.remove('no-scroll');
    }, 500);
  }
  const initFullHeight = () => {
    content.querySelectorAll('.evie-fullscreen').forEach(el => {
      el.style.setProperty('--evie-full-height', `${window.innerHeight}px`);
    });
  };
  if (true !== isResizeEventAdded) {
    window.addEventListener('resize', flextension.debounce(() => {
      initFullHeight();
    }, 300));
    isResizeEventAdded = true;
  }
  initFullHeight();
  content.querySelectorAll('.evie-carousel').forEach(el => {
    new flextension.carousel(el, {
      loop: true,
      breakpoints: {
        1024: {
          centeredSlides: true,
          slidesPerView: 2
        }
      },
      on: {
        afterInit: carousel => {
          if (el.classList.contains('has-mousewheel')) {
            carousel.mousewheel.disable();
            if (!carousel.mousewheel.enabled) {
              setTimeout(() => {
                carousel.mousewheel.enable();
              }, 1000);
            }
          }
          const background = carousel.el.querySelector('.slider-background');
          if (background !== null) {
            const slideText = background.querySelector('.slider-background-text');

            // Animate the background when the mouse moves.
            carousel.el.addEventListener('mousemove', e => {
              const winWidth = window.innerWidth,
                elWidth = carousel.el.offsetWidth;
              const x = (winWidth - elWidth) / 2 - (e.pageX - winWidth / 2) / 2;
              featured_posts_gsap.to(background, {
                x,
                duration: 0.3
              });
              if (slideText !== null) {
                if (e.pageX < carousel.el.offsetWidth / 2) {
                  slideText.classList.add('slide-text-reverse');
                } else {
                  slideText.classList.remove('slide-text-reverse');
                }
              }
            });
          }
        },
        activeIndexChange: carousel => {
          const background = carousel.el.querySelector('.slider-background');
          if (background !== null) {
            background.classList.remove('evie-fade-in');
          }
        },
        slideChange: carousel => {
          const background = carousel.el.querySelector('.slider-background');
          if (background === null) {
            return;
          }
          const backgroundText = background.querySelector('span');
          if (backgroundText !== null) {
            setTimeout(() => {
              const slide = carousel.slides[carousel.activeIndex];
              if (slide !== null) {
                const title = slide.querySelector('.slide-title');
                if (title !== null) {
                  const text = title.textContent.replace(/(\r\n|\n|\r)/gm, '') + ' ';
                  backgroundText.innerText = text.repeat(3);
                }
                background.classList.add('evie-fade-in');
              }
            }, 500);
          }
        }
      }
    });
  });
  content.querySelectorAll('.evie-slider').forEach(el => {
    new flextension.carousel(el, {
      grabCursor: true,
      navigation: {
        nextEl: '.evie-button-next',
        prevEl: '.evie-button-prev',
        disabledClass: 'evie-button-disabled',
        hiddenClass: 'flext-button-hidden',
        lockClass: 'flext-button-lock'
      },
      breakpoints: {
        768: {
          slidesPerView: 1
        }
      },
      on: {
        afterInit: carousel => {
          if (el.classList.contains('has-mousewheel')) {
            carousel.mousewheel.disable();
            if (!carousel.mousewheel.enabled) {
              setTimeout(() => {
                carousel.mousewheel.enable();
              }, 1000);
            }
          }
        }
      }
    });
  });
  content.querySelectorAll('.evie-split-slider').forEach(el => {
    let menuTextMode = '';
    if (document.body.classList.contains('menu-text-dark')) {
      menuTextMode = 'menu-text-dark';
    } else if (document.body.classList.contains('menu-text-light')) {
      menuTextMode = 'menu-text-light';
    }
    imagesLoaded(el.querySelectorAll('img'), () => {
      new split_slider(el, {
        mousewheel: el.classList.contains('has-mousewheel'),
        onChange: (slider, slide) => {
          if (!slide.DOM.el) {
            return;
          }
          document.body.classList.remove('menu-text-light', 'menu-text-dark');
          if (slide.DOM.el.classList.contains('has-scheme-dark')) {
            if (document.body.classList.contains('transparent-menu')) {
              document.body.classList.add('menu-text-light');
            }
          } else if (slide.DOM.el.classList.contains('has-scheme-light')) {
            if (document.body.classList.contains('transparent-menu')) {
              document.body.classList.add('menu-text-dark');
            }
          } else if (menuTextMode) {
            document.body.classList.add(menuTextMode);
          }
        }
      });
    });
  });
  content.querySelectorAll('.evie-vertical-slider').forEach(el => {
    imagesLoaded(el.querySelectorAll('img'), () => {
      new split_slider(el, {
        mousewheel: el.classList.contains('has-mousewheel'),
        onInit: slider => {
          const background = slider.DOM.el.querySelector('.slider-background');
          if (background === null) {
            return;
          }
          const slideText = background.querySelector('.slider-background-text');

          // Animate the background when the mouse moves.
          slider.DOM.el.addEventListener('mousemove', e => {
            const winWidth = window.innerWidth,
              elWidth = slider.DOM.el.offsetWidth;
            const x = (winWidth - elWidth) / 2 - (e.pageX - winWidth / 2) / 2;
            featured_posts_gsap.to(background, {
              x,
              duration: 0.3
            });
            if (slideText !== null) {
              if (e.pageX < slider.DOM.el.offsetWidth / 2) {
                slideText.classList.add('slide-text-reverse');
              } else {
                slideText.classList.remove('slide-text-reverse');
              }
            }
          });
        },
        onBeforeChange: slider => {
          const background = slider.DOM.el.querySelector('.slider-background');
          if (background !== null) {
            background.classList.remove('evie-fade-in');
          }
        },
        onChange: (slider, slide) => {
          const background = slider.DOM.el.querySelector('.slider-background');
          if (background === null) {
            return;
          }
          const backgroundText = background.querySelector('span');
          if (backgroundText !== null) {
            const title = slide.DOM.el.querySelector('.slide-title');
            if (title) {
              const text = title.textContent.replace(/(\r\n|\n|\r)/gm, '') + ' ';
              backgroundText.innerText = text;
              if (backgroundText.offsetWidth < window.innerWidth * 4) {
                backgroundText.innerText = text.repeat(Math.round(window.innerWidth * 4 / backgroundText.offsetWidth));
              }
            }
          }
          background.classList.add('evie-fade-in');
        }
      });
    });
  });
}
evieApp.on('ready', (context, content) => {
  if (!content) {
    initFeaturedSliders(document);
    if (evieApp.cursor && evieApp.browser.touch !== true && (evieApp.browser.lg || evieApp.browser.xl)) {
      // Set custom slider cursor.
      evieApp.cursor.actions.push({
        selector: '.evie-page-buttons .evie-nav-button:not(.evie-button-disabled), .evie-page-buttons .evie-page-button',
        enter: cursor => {
          cursor.content('');
        }
      });
      evieApp.cursor.actions.push({
        selector: '.evie-carousel .flext-button-prev',
        enter: cursor => {
          cursor.content('<i class="evie-ico-arrow-left"></i>');
        }
      });
      evieApp.cursor.actions.push({
        selector: '.evie-carousel .flext-button-next',
        enter: cursor => {
          cursor.content('<i class="evie-ico-arrow-right"></i>');
        }
      });
      evieApp.cursor.actions.push({
        selector: '.evie-carousel .flext-slide-active .slide-image',
        enter: cursor => {
          cursor.content('<i class="evie-ico-eye"></i>');
        }
      });
    }
  } else {
    setTimeout(() => {
      initFeaturedSliders(content);
    }, 1200);
  }
});
// EXTERNAL MODULE: ./src/modules/elements/blocks/post-carousel/index.js
var post_carousel = __webpack_require__(2681);
// EXTERNAL MODULE: ./src/modules/elements/blocks/scrolling-text/index.js
var scrolling_text = __webpack_require__(5598);
;// CONCATENATED MODULE: ./src/modules/elements/js/index.js
/**
 * Internal dependencies
 */



}();
/******/ })()
;