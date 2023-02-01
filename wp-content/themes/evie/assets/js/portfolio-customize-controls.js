/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Portfolio Customize Controls
 *
 * @author  Wyde
 * @version 1.0.0
 */



wp.customize.bind('ready', () => {
  /**
   * Portfolio Section
   */

  // Only show the Style and Hover Effect controls when the Grid or Waterfall is selected.
  wp.customize.control('portfolio_posts_layout', control => {
    control.setting.bind(value => {
      if (value === 'waterfall') {
        wp.customize.control('portfolio_posts_parallax').activate();
      } else {
        wp.customize.control('portfolio_posts_parallax').deactivate();
      }
      if (['grid', 'waterfall'].includes(value)) {
        wp.customize.control('portfolio_posts_style').activate();
        wp.customize.control('portfolio_posts_hover_effect').activate();
      } else {
        wp.customize.control('portfolio_posts_style').deactivate();
        wp.customize.control('portfolio_posts_hover_effect').deactivate();
      }
    });
  });

  // Only show the Style and Hover Effect controls when the Grid or Waterfall is selected.
  wp.customize.control('portfolio_archive_layout', control => {
    control.setting.bind(value => {
      if (value === 'waterfall') {
        wp.customize.control('portfolio_archive_parallax').activate();
      } else {
        wp.customize.control('portfolio_archive_parallax').deactivate();
      }
      if (['grid', 'waterfall'].includes(value)) {
        wp.customize.control('portfolio_archive_style').activate();
        wp.customize.control('portfolio_archive_hover_effect').activate();
      } else {
        wp.customize.control('portfolio_archive_style').deactivate();
        wp.customize.control('portfolio_archive_hover_effect').deactivate();
      }
    });
  });

  // Only show the Quick View controls when it is enabled.
  wp.customize.control('portfolio_quick_view_enable', control => {
    control.setting.bind(value => {
      if (value) {
        wp.customize.control('portfolio_quick_view_date').activate();
        wp.customize.control('portfolio_quick_view_category').activate();
        wp.customize.control('portfolio_quick_view_buttons').activate();
        wp.customize.control('portfolio_quick_view_author').activate();
      } else {
        wp.customize.control('portfolio_quick_view_date').deactivate();
        wp.customize.control('portfolio_quick_view_category').deactivate();
        wp.customize.control('portfolio_quick_view_buttons').deactivate();
        wp.customize.control('portfolio_quick_view_author').deactivate();
      }
    });
  });
});
/******/ })()
;