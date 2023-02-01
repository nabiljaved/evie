/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Featured Media Editor.
 *
 * @author  Wyde
 * @version 1.0.0
 */


window.addEventListener('load', () => {
  const editor = document.getElementById('editor');
  if (editor === null) {
    return;
  }
  const featuredMediaSection = document.getElementById('flextension-meta-box-featured-media');
  if (featuredMediaSection === null) {
    return;
  }
  const {
    flextension
  } = window;
  const mediaTypeField = featuredMediaSection.querySelector('#flext-field-flext-featured-media-type');
  const rolloverImageField = featuredMediaSection.querySelector('#flext-field-flext-featured-rollover-image');
  const galleryField = featuredMediaSection.querySelector('#flext-field-flext-featured-images');
  const mediaField = featuredMediaSection.querySelector('#flext-field-flext-featured-media');
  const posterField = featuredMediaSection.querySelector('#flext-field-flext-featured-media-poster');
  const onPostFormatChange = postFormat => {
    if (featuredMediaSection !== null) {
      featuredMediaSection.hidden = !['standard', 'image', 'gallery', 'audio', 'video'].includes(postFormat);
    }
    if (mediaTypeField !== null) {
      const types = mediaTypeField.querySelectorAll('select option');
      if (types.length > 1) {
        mediaTypeField.hidden = !('gallery' === postFormat);
      } else {
        mediaTypeField.hidden = true;
      }
    }
    if (rolloverImageField !== null) {
      rolloverImageField.hidden = !['standard', 'image'].includes(postFormat);
    }
    if (galleryField !== null) {
      galleryField.hidden = !('gallery' === postFormat);
    }
    if (mediaField !== null) {
      if (['audio', 'video'].includes(postFormat)) {
        mediaField.hidden = false;
        const mediaUrl = mediaField.querySelector('.flext-field-media-url');
        if (mediaUrl !== null) {
          mediaUrl.dataset.type = postFormat;
          flextension.emit('mediaField.change', postFormat);
        }
      } else {
        mediaField.hidden = true;
      }
    }
    if (posterField !== null) {
      posterField.hidden = !['audio', 'video'].includes(postFormat);
    }
  };
  const postFormatSelector = '.editor-post-format select';
  // When changing post format, show or hide the Featured Media & Gallery section.
  editor.addEventListener('change', event => {
    if (event.target && event.target === editor.querySelector(postFormatSelector)) {
      const postFormat = event.target.value || 'standard';
      onPostFormatChange(postFormat);
      flextension.emit('featuredMedia.postFormatChange', postFormat);
    }
  });
  setTimeout(() => {
    const dropdown = editor.querySelector(postFormatSelector);
    if (dropdown !== null) {
      onPostFormatChange(dropdown.value || 'standard');
    }
  }, 1000);
});
/******/ })()
;