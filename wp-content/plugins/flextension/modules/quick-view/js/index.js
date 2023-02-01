/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Quick View
 *
 * Opens a post content in the lightbox when clicking on a quick view button.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension
} = window;

/**
 * Opens the lightbox to show the Quick View post.
 *
 * @param {string} id       The post ID.
 * @param {string} postType The post type.
 * @param {string} mode     Request mode between 'rest' and 'legacy'. Default is 'rest'.
 */
function showQuickView(id, postType, mode) {
  flextension.emit('beforeQuickViewShow');
  let mediaPlayer = null;
  const getMediaPlayer = content => {
    const {
      featuredMediaPlayers
    } = flextension;
    let player = null;
    if (featuredMediaPlayers && featuredMediaPlayers.length > 0) {
      const featuredMedia = content.querySelector('.flext-featured-media.flext-post-video, .flext-featured-media.flext-post-audio');
      if (featuredMedia !== null) {
        featuredMediaPlayers.forEach(video => {
          if (video && video.element && video.element === featuredMedia) {
            player = video;
          }
        });
      }
    }
    return player;
  };
  const args = {
    callback: (content, lightbox) => {
      lightbox.content.innerHTML = content.rendered;
      flextension.emit('afterQuickViewLoaded', lightbox.content);
      flextension.emit('ready', lightbox.content);
      mediaPlayer = getMediaPlayer(lightbox.content);
    }
  };
  if (mode === 'legacy') {
    args.data = {
      action: 'flextension_quick_view',
      id,
      postType
    };
  } else {
    args.endpoint = '/flextension/v1/quick-view';
    args.data = {
      id,
      postType
    };
  }
  new flextension.lightbox('#flext-quick-view-content-' + id, {
    className: `flext-quick-view-modal quick-view-post-type-${postType}`,
    onOpen: lightbox => {
      mediaPlayer = getMediaPlayer(lightbox.content);
      flextension.emit('afterQuickViewOpen', lightbox);
    },
    onClose: lightbox => {
      if (mediaPlayer !== null) {
        mediaPlayer.dispose();
        lightbox.content.remove();
      }
      flextension.emit('afterQuickViewClose', lightbox);
    }
  }).load(args);
  flextension.emit('afterQuickViewShow');
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  content.querySelectorAll('.flext-quick-view-button:not(.flext-quick-view-initialized)').forEach(button => {
    if (button !== null) {
      button.classList.add('flext-quick-view-initialized');
      button.addEventListener('click', e => {
        e.preventDefault();
        showQuickView(button.dataset.postId, button.dataset.postType || '', button.dataset.mode || '');
        return false;
      });
    }
  });
});
/******/ })()
;