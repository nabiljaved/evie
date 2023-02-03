/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

/**
 * WooCommerce.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  evieApp,
  flextension,
  jQuery: $
} = window;

/**
 * Initializes the minus and plus buttons for the quantity field in the cart.
 *
 * @param {Node} content The content to initialize.
 */
function initCartQuantityField(content) {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.quantity:not(.is-initialized)').forEach(field => {
    field.classList.add('is-initialized');
    const quantity = field.querySelector('.qty');
    if (quantity === null) {
      return;
    }
    if (quantity.min !== '' || quantity.max !== '') {
      $(quantity).on('change', (e, target) => {
        if (e.target.min && Number(e.target.value) < Number(e.target.min)) {
          e.target.value = e.target.min;
        } else if (e.target.max && Number(e.target.value) > Number(e.target.max)) {
          e.target.value = e.target.max;
        }
        $(document.body).trigger('wc_cart_quantity_changed', target || e.target);
      });
    }
    const minusButton = field.querySelector('.wc-quantity-minus-button');
    if (minusButton !== null) {
      minusButton.addEventListener('click', e => {
        e.preventDefault();
        quantity.value = Number(quantity.value) - Number(quantity.step || 1);
        $(quantity).trigger('change');
      });
    }
    const plusButton = field.querySelector('.wc-quantity-plus-button');
    if (plusButton !== null) {
      plusButton.addEventListener('click', e => {
        e.preventDefault();
        quantity.value = Number(quantity.value) + Number(quantity.step || 1);
        $(quantity).trigger('change');
      });
    }
  });
}

/**
 * Initializes the Product Gallery.
 *
 * @param {Node} gallery Gallery element.
 */
function initProductGallery(gallery) {
  const images = gallery.querySelectorAll('.woocommerce-product-gallery__image');
  if (images.length < 2) {
    return;
  }
  const carousel = document.createElement('div');
  carousel.classList.add('wc-control-nav-carousel');
  gallery.append(carousel);
  const list = document.createElement('ul');
  list.classList.add('flex-control-nav', 'flex-control-thumbs');
  images.forEach(image => {
    const thumbnail = document.createElement('img');
    thumbnail.setAttribute('src', image.dataset.thumb);
    thumbnail.setAttribute('alt', image.dataset.thumbAlt);
    const item = document.createElement('li');
    item.append(thumbnail);
    list.append(item);
  });
  carousel.append(list);
  $(carousel).flexslider({
    animation: 'slide',
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 145,
    itemMargin: 5,
    asNavFor: gallery,
    selector: 'ul > li',
    touch: false
  });
}

/**
 * Initializes the Product meta data.
 *
 * @param {Node} content The content to initialize.
 */
function initProductMeta(content) {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.wc-product-meta-toggle').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      button.parentElement.classList.toggle('is-visible');
    });
  });
}

/**
 * Initializes the Single Product.
 */
function initSingleProduct() {
  $('.woocommerce-product-gallery').on('wc-product-gallery-after-init', (e, gallery) => {
    initProductGallery(gallery);
  });
  initProductMeta();
}

/**
 * Hides the side cart.
 */
function hideCart() {
  if (document.body.classList.contains('desktop-menu') && document.body.classList.contains('top-menu')) {
    document.body.classList.remove('nav-active');
    evieApp.isNavActive = false;
  }
  document.body.classList.remove('cart-active');
  evieApp.enableScroll('#cart-sidebar');
}

/**
 * Shows the side cart.
 */
function showCart() {
  evieApp.closeActiveSidebar();
  let delay = 100;
  if (document.body.classList.contains('desktop-menu')) {
    if (document.body.classList.contains('top-menu')) {
      evieApp.closeActiveSidebar();
      document.body.classList.add('nav-active');
      evieApp.isNavActive = true;
    } else if (evieApp.isNavActive === true) {
      delay = 400;
      evieApp.hideNav();
    }
  }
  setTimeout(() => {
    const header = evieApp.getHeader();
    if (header !== null && header.classList.contains('is-sticky')) {
      header.classList.add('is-menu-visible');
    }
    evieApp.disableScroll('#cart-sidebar', ['.wc-side-cart-form']);
    document.body.classList.add('cart-active');
    evieApp.onActiveSidebarClose = hideCart;
  }, delay);
}

/**
 * Initializes the Cart sidebar.
 */
function initCart() {
  evieApp.on('overlayClick', hideCart);
  const cartButton = document.querySelector('.shopping-cart-button');
  if (cartButton !== null) {
    cartButton.addEventListener('click', e => {
      e.preventDefault();
      if (document.body.classList.contains('cart-active')) {
        hideCart();
      } else {
        showCart();
      }
      return false;
    });
  }
}

/**
 * Initializes the Login form.
 */
function initLoginForm() {
  const currentTab = 0;
  const panels = document.querySelectorAll('.wc-login-tabs .u-columns > div');
  panels.forEach((panel, index) => {
    if (index === currentTab) {
      panel.classList.add('is-active');
    }
    const button = panel.querySelector('h2');
    if (button !== null) {
      button.addEventListener('click', e => {
        e.preventDefault();
        panels.forEach((item, idx) => {
          if (idx !== index) {
            item.classList.remove('is-active');
          }
        });
        panel.classList.add('is-active');
      });
    }
  });
}

/**
 * Initializes the product filters.
 *
 * @param {Node} content Target element to initialize.
 */
function initProductFilters(content) {
  if (!content) {
    content = document;
  }
  const {
    fetch,
    history
  } = window;
  content.querySelectorAll('.product-filter-widgets').forEach(panel => {
    const widgets = panel.querySelectorAll('.widget');
    const max = Math.min(widgets.length, 4);
    if (max > 1) {
      panel.classList.add('evie-grid', `has-${max}-columns`);
    }
  });
  const partials = document.querySelectorAll('#site-content, #site-sidebar');
  const showLoader = () => {
    partials.forEach(partial => {
      partial.classList.add('partial-content', 'is-loading');
    });
  };
  const hideLoader = () => {
    partials.forEach(partial => {
      partial.classList.remove('partial-content', 'is-loading');
    });
  };
  const setFilter = url => {
    showLoader();
    history.pushState({}, null, url);
    fetch(url).then(response => {
      return response.text();
    }).then(data => {
      updateContent(data);
      hideLoader();
    });
  };
  const filterLinks = content.querySelectorAll('.wc-layered-nav-term a, .wc-layered-nav-rating a');
  filterLinks.forEach(link => {
    if (!link.classList.contains('is-initialized')) {
      link.classList.add('is-initialized');
      link.addEventListener('click', e => {
        e.preventDefault();
        link.parentElement.classList.toggle('chosen');
        setFilter(link.href);
        return false;
      });
    }
  });
  content.querySelectorAll('.filter-buttons a').forEach(link => {
    if (!link.classList.contains('is-initialized')) {
      link.classList.add('is-initialized', 'no-ajax');
      link.addEventListener('click', e => {
        e.preventDefault();
        filterLinks.forEach(item => {
          item.parentElement.classList.remove('chosen');
        });
        setFilter(link.href);
        return false;
      });
    }
  });
  content.querySelectorAll('.woocommerce-widget-layered-nav form, .widget_price_filter form').forEach(form => {
    if (!form.classList.contains('is-initialized')) {
      form.classList.add('is-initialized');
      form.addEventListener('submit', e => {
        e.preventDefault();
        showLoader();
        const url = new URL(form.getAttribute('action'));
        new URLSearchParams(window.location.search).forEach((value, key) => {
          url.searchParams.set(key, value);
        });
        const searchParams = new URLSearchParams($(form).serialize());
        searchParams.forEach((value, key) => {
          url.searchParams.set(key, value);
        });
        history.pushState({}, null, url.toString());
        $.ajax({
          type: form.getAttribute('method') || 'GET',
          url: url.toString(),
          dataType: 'html',
          success(data) {
            updateContent(data);
            hideLoader();
          }
        });
        return false;
      });
      form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
          form.submit();
        });
      });
    }
  });
}

/**
 * Initialzies the Quick View Content.
 *
 * @param {Node} content Target element to initialize.
 */
function initProductQuickView(content) {
  if (typeof $.fn.wc_product_gallery !== 'undefined') {
    content.querySelectorAll('.woocommerce-product-gallery').forEach(gallery => {
      $(gallery).wc_product_gallery();
      initProductGallery(gallery);
    });
  }
  content.querySelectorAll('.variations_form').forEach(form => {
    $(form).wc_variation_form();
    // Add Color and Label Integration.
    if (typeof $.fn.yith_wccl !== 'undefined') {
      $(form).yith_wccl();
    }
  });
}

/**
 * Updates the page when filtering.
 *
 * @param {string} data New HTML content.
 */
function updateContent(data) {
  const {
    DOMParser
  } = window;
  // Build the data from response HTML.
  const doc = new DOMParser().parseFromString(data, 'text/html');
  const selectors = ['#site-content', '#site-sidebar .widget.woocommerce-widget-layered-nav', '#site-sidebar .widget.widget_price_filter'];
  let hasUpdated = false;
  selectors.forEach(selector => {
    document.querySelectorAll(selector).forEach(currentContent => {
      let newContent = null;
      if (currentContent.getAttribute('id')) {
        newContent = doc.getElementById(currentContent.getAttribute('id'));
      } else {
        newContent = doc.querySelector(selector);
      }
      if (newContent !== null) {
        currentContent.style.display = '';
        currentContent.innerHTML = newContent.innerHTML;
        hasUpdated = true;
        flextension.emit('ready', currentContent);
        evieApp.emit('ready', currentContent);
      } else {
        currentContent.innerHTML = '';
        currentContent.style.display = 'none';
      }
    });
  });
  if (true === hasUpdated) {
    $(document.body).trigger('init_price_filter');
  }
}
initProductFilters();
initCartQuantityField();
initCart();
initSingleProduct();
initLoginForm();
evieApp.on('ready', (context, content) => {
  if (!content) {
    return;
  }
  initProductFilters(content);
  initProductQuickView(content);
  initCartQuantityField(content);
  initProductMeta(content);
});
$(document.body).on('updated_wc_div wc_fragments_loaded wc_fragments_refreshed', () => {
  initCartQuantityField();
});
$(document.body).on('wc_cart_quantity_changed', (event, target) => {
  $('[name="' + $(target).prop('name') + '"]').val($(target).val());
  const miniCartForm = $(target).parents('.wc-side-cart-form');
  if (miniCartForm.length > 0) {
    miniCartForm.parent().addClass('is-loading');
    $('<input />').attr('type', 'hidden').attr('name', 'update_cart').attr('value', 'Update Cart').appendTo(miniCartForm);

    // Make call to actual form post URL.
    $.ajax({
      type: miniCartForm.attr('method'),
      url: miniCartForm.attr('action'),
      data: miniCartForm.serialize(),
      dataType: 'html',
      success() {
        $(document.body).trigger('updated_wc_div');
      }
    });
  }
  $('.woocommerce-cart-form button[name="update_cart"]').prop('disabled', false).prop('clicked', 'true').trigger('click');
});
let loadingSideCart = false;
$(document.body).on('added_to_cart.showSideCart', () => {
  if (loadingSideCart === true) {
    return;
  }
  loadingSideCart = true;
  setTimeout(() => {
    showCart();
    setTimeout(() => {
      loadingSideCart = false;
    }, 500);
  }, 1000);
});
/******/ })()
;