/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Lightbox Gallery
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension,
  PhotoSwipe
} = window;

/**
 * Retrieves the DOM element for the lightbox gallery.
 *
 * @return {Node} The lightbox gallery element.
 */
function getLightboxElement() {
  let lightbox = document.getElementById('flext-lightbox-gallery');
  if (lightbox === null) {
    const lightboxMarkup = `<div id="flext-lightbox-gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>

				<div class="pswp__scroll-wrap">

					<div class="pswp__container">
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
					</div>

					<div class="pswp__ui pswp__ui--hidden">

						<div class="pswp__top-bar">

							<div class="pswp__counter"></div>

							<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

							<button class="pswp__button pswp__button--share" title="Share"></button>

							<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

							<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

							<!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
							<!-- element will get class pswp__preloader--active when preloader is running -->
							<div class="pswp__preloader">
								<div class="pswp__preloader__icn">
								<div class="pswp__preloader__cut">
									<div class="pswp__preloader__donut"></div>
								</div>
								</div>
							</div>
						</div>

						<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
							<div class="pswp__share-tooltip"></div>
						</div>

						<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
						</button>

						<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
						</button>

						<div class="pswp__caption">
							<div class="pswp__caption__center"></div>
						</div>

					</div>

				</div>

			</div>`;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = lightboxMarkup;
    lightbox = wrapper.firstChild;
    document.body.append(lightbox);
  }
  return lightbox;
}

/**
 * Returns the caption for the image.
 *
 * @param {Node} image The image element.
 * @return {string} Image caption.
 */
function getCaption(image) {
  let caption = '';
  const figure = image.closest('figure');
  if (figure !== null) {
    const figcaption = figure.querySelector('figcaption');
    if (figcaption !== null) {
      caption = figcaption.innerHTML;
    }
  }
  if (!caption) {
    caption = image.getAttribute('title');
  }
  if (!caption) {
    caption = image.getAttribute('alt');
  }
  return caption;
}

/**
 * Retrieves the large size of the image.
 *
 * @param {HTMLImageElement} thumbnail The thunbnail image.
 * @return {Object} The image size object.
 */
function getLargeImageSize(thumbnail) {
  let srcWidth = parseInt(thumbnail.getAttribute('width'), 10),
    srcHeight = parseInt(thumbnail.getAttribute('height'), 10);
  if (!srcWidth || !srcHeight) {
    const rect = thumbnail.getBoundingClientRect();
    srcWidth = rect.width;
    srcHeight = rect.height;
  }
  const ratio = Math.min(window.innerWidth / srcWidth, window.innerHeight / srcHeight);
  return {
    width: srcWidth * ratio,
    height: srcHeight * ratio
  };
}

/**
 * Displays the large image in the lightbox gallery.
 *
 * @param {number} index   The active image index.
 * @param {Array}  slides  An array list of the slides.
 * @param {Object} options The gallery options.
 */
function openLightbox(index, slides, options) {
  const {
    PhotoSwipeUI_Default: defaults
  } = window;
  options.index = index;
  const lightboxElement = getLightboxElement();
  // Initializes and opens PhotoSwipe
  const gallery = new PhotoSwipe(lightboxElement, defaults, slides, options);
  gallery.listen('gettingData', (idx, item) => {
    if (item.ready !== true) {
      const largeImage = new window.Image();
      largeImage.addEventListener('load', () => {
        item.w = largeImage.naturalWidth;
        item.h = largeImage.naturalHeight;
        gallery.updateSize(true);
        item.ready = true;
      });
      largeImage.src = item.src;
    }
  });
  gallery.listen('afterInit', () => {
    flextension.emit('lightboxGallery.open', gallery);
  });
  gallery.listen('close', () => {
    flextension.emit('lightboxGallery.close', gallery);
  });
  gallery.init();
}

/**
 * Creates a lightbox gallery object.
 *
 * @param {Node}   element The gallery element.
 * @param {Object} options The gallery options.
 */
flextension.lightboxGallery = function (element, options) {
  const slides = [];
  options = Object.assign({
    mainClass: 'flext-lightbox-gallery',
    history: false,
    closeOnScroll: false,
    getThumbBoundsFn: index => {
      const pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
        rect = slides[index].thumbnail.getBoundingClientRect();
      return {
        x: rect.left,
        y: rect.top + pageYScroll,
        w: rect.width
      };
    }
  }, options || {});
  const mediaExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'mp4', 'mov', 'ogv', 'ogg', 'webm'];
  element.querySelectorAll('a:not(.flext-lightbox-link) img').forEach((image, index) => {
    if (image === null) {
      return;
    }
    const link = image.closest('a');
    if (link === null) {
      return;
    }
    if (link.classList.contains('flext-lightbox-link')) {
      return;
    }
    link.classList.add('flext-lightbox-link');
    const url = link.href ? link.href.toString().toLowerCase() : '';
    if (url && mediaExtensions.indexOf(url.split('.').pop()) > -1) {
      link.classList.add('flext-lightbox-zoom');
      let ready = true;
      let largeSize = {
        width: parseInt(link.dataset.width, 10),
        height: parseInt(link.dataset.height, 10)
      };
      if (!largeSize.width || !largeSize.height) {
        ready = false;
        largeSize = getLargeImageSize(image);
      }
      slides.push({
        thumbnail: image,
        src: link.getAttribute('href'),
        w: largeSize.width,
        h: largeSize.height,
        msrc: image.src,
        title: getCaption(image),
        ready
      });
      link.addEventListener('click', e => {
        e.preventDefault();
        openLightbox(index, slides, options);
        return false;
      });
    } else if (new URL(url).origin !== location.origin) {
      link.classList.add('flext-lightbox-external');
    }
  });
};

/**
 * Fires the function when the Flextension plugin is ready.
 */
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document.body;
  }
  content.querySelectorAll('p.attachment, .gallery, .wp-caption, .wp-block-image, .wp-block-media-text, .wp-block-gallery').forEach(element => {
    flextension.lightboxGallery(element);
  });
});
/******/ })()
;