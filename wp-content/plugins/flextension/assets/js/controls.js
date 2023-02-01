/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Flextension Controls
 *
 * Initializes the setting fields.
 *
 * @author  Wyde
 * @version 1.0.0
 */



const {
  flextension,
  jQuery
} = window;

/**
 * Field class
 */
class Field {
  constructor(el) {
    if (!el) {
      return;
    }
    if (el.classList.contains('flext-field-initialized')) {
      return;
    }
    el.classList.add('flext-field-initialized');
    this.element = el;
    this.init();
  }
  init() {
    this.input = this.element.querySelector('input');
    if (this.input !== null) {
      if (this.input.min !== '' || this.input.max !== '') {
        this.input.addEventListener('change', e => {
          if (e.target.min && Number(e.target.value) < Number(e.target.min)) {
            e.target.value = e.target.min;
          } else if (e.target.max && Number(e.target.value) > Number(e.target.max)) {
            e.target.value = e.target.max;
          }
        });
      }
      if (this.input.type === 'number' || this.input.type === 'tel') {
        this.input.addEventListener('input', e => {
          e.target.value = e.target.value.replace(/\D/g, '');
        });
      }
    }
    this.initDependencies();
  }
  initDependencies() {
    this.dependencies = this.element.dataset.dependencies && JSON.parse(this.element.dataset.dependencies);
    if (this.dependencies) {
      this.dependencies.forEach(dependency => {
        if (typeof dependency === 'object') {
          const referers = this.getReferers(dependency);
          if (referers !== null) {
            this.checkVisibility(dependency, referers);
            referers.forEach(referer => {
              if (referer.type === 'hidden') {
                referer.onchange = () => {
                  this.checkVisibility(dependency, referers);
                  this.toggleDisplay(this.dependencies);
                };
              } else {
                referer.addEventListener('change', () => {
                  this.checkVisibility(dependency, referers);
                  this.toggleDisplay(this.dependencies);
                });
              }
            });
          }
        }
      });
      this.toggleDisplay(this.dependencies);
    }
  }
  getValues(referers) {
    const values = [];
    referers.forEach(input => {
      if ('checkbox' === input.type) {
        values.push(input.checked);
      } else if ('radio' === input.type) {
        if (input.checked) {
          values.push(input.value);
        }
      } else {
        values.push(input.value);
      }
    });
    return values;
  }
  getReferers(dependency) {
    if (dependency.name) {
      return document.querySelectorAll('[name="' + dependency.name + '"]');
    } else if (dependency.selector) {
      return document.querySelectorAll(dependency.selector);
    }
    return null;
  }
  checkVisibility(dependency, referers) {
    if (typeof dependency === 'object') {
      let visible = false;
      if (!dependency.operator) {
        dependency.operator = '=';
      }
      const values = this.getValues(referers);
      let comparedValues = [];
      if (Array.isArray(dependency.value)) {
        comparedValues = dependency.value;
      } else {
        comparedValues.push(dependency.value);
      }
      switch (dependency.operator) {
        case '!=':
        case '!==':
          visible = !comparedValues.some(value => values.includes(value));
          break;
        default:
          visible = comparedValues.some(value => values.includes(value));
          break;
      }
      dependency.visible = visible;
    }
  }
  toggleDisplay(dependencies) {
    if (Array.isArray(dependencies)) {
      const hide = dependencies.some(dependency => {
        return dependency && dependency.visible !== true;
      });
      const wrapper = this.element.dataset.wrapper ? this.element.closest(this.element.dataset.wrapper) : this.element;
      if (wrapper !== null) {
        wrapper.style.display = hide ? 'none' : '';
      }
    }
  }
}

/**
 * Color field
 */
class ColorField extends Field {
  constructor(el) {
    super(el);
    if (this.input !== null) {
      jQuery(this.input).wpColorPicker({
        change: (event, ui) => {
          flextension.emit('colorControlChange', this.input.getAttribute('name'), ui.color.toString(), event);
        },
        clear: event => {
          flextension.emit('colorControlChange', this.input.getAttribute('name'), '', event);
        }
      });
    }
  }
}

/**
 * Range field
 */
class RangeField extends Field {
  constructor(el) {
    super(el);
    this.label = this.element.querySelector('.flext-field-range-value');
    if (this.input !== null && this.label !== null) {
      this.input.addEventListener('input', e => {
        this.label.innerText = e.target.value;
      });
    }
  }
}

/**
 * Media field
 */
class MediaField extends Field {
  constructor(el) {
    super(el);
    this.mediaUrlField = this.element.querySelector('.flext-field-media-url');
    if (this.mediaUrlField === null) {
      return;
    }
    this.mediaPopup = null;
    this.mediaType = this.mediaUrlField.dataset.type;
    this.addButton = this.element.querySelector('.add-media-button');
    this.removeButton = this.element.querySelector('.remove-media-button');
    this.mediaPreview = this.element.querySelector('.flext-field-media-preview');
    flextension.on('mediaField.change', () => {
      if (this.mediaType !== this.mediaUrlField.dataset.type) {
        this.mediaType = this.mediaUrlField.dataset.type;
        this.initMediaPopup();
      }
    });
    this.initFields();
  }
  initFields() {
    this.mediaUrlField.addEventListener('change', () => {
      this.fetchPreview(this.mediaUrlField.value);
    });
    this.addButton.addEventListener('click', event => {
      event.preventDefault();
      if (this.mediaPopup === null) {
        this.initMediaPopup();
      }
      this.mediaPopup.open();
    });
    this.removeButton.addEventListener('click', event => {
      event.preventDefault();
      this.mediaUrlField.value = '';
      this.hideMedia();
      this.toggleButtons();
    });
    this.togglePreview();
    this.toggleButtons();
  }
  getMediaFrame() {
    const frame = wp.media({
      library: {
        type: this.mediaType
      },
      multiple: false
    });
    frame.on('select', () => {
      const media = frame.state().get('selection').first().toJSON();
      if (media) {
        this.showMedia(media);
        this.mediaUrlField.value = media.url;
      }
      this.toggleButtons();
      frame.close();
    });
    return frame;
  }
  initMediaPopup() {
    this.mediaPopup = this.getMediaFrame();
  }
  fetchPreview(url) {
    if (!url) {
      this.hideMedia();
      this.toggleButtons();
      return;
    }
    this.mediaPreview.innerHTML = '<span class="flext-loader flext-loader-xs"></span>';
    this.togglePreview(true);
    wp.apiFetch({
      path: '/oembed/1.0/proxy/?url=' + encodeURIComponent(url) + '&maxwidth=' + 252
    }).then(data => {
      if (data && data.thumbnail_url) {
        this.showMedia(data);
        this.toggleButtons();
      }
    });
  }
  hasMedia() {
    return '' !== this.mediaUrlField.value;
  }
  showMedia(media) {
    if (this.mediaPreview === null) {
      return;
    }
    this.mediaPreview.innerHTML = '';
    const {
      MediaElementPlayer
    } = window;
    if (this.player) {
      if (!this.player.paused) {
        this.player.pause();
      }
      this.mediaPreview.innerHTML = '';
      delete this.player;
    }
    if (media) {
      if (media.html) {
        this.mediaPreview.innerHTML = media.html;
      } else if (media.url && media.type) {
        this.mediaPreview.innerHTML = '<' + media.type + '><source src="' + media.url + '" type="' + media.mime + '"></' + media.type + '>';
        this.player = new MediaElementPlayer(this.mediaPreview.querySelector(media.type), {
          stretching: 'responsive'
        });
      } else if (media.icon) {
        this.mediaPreview.innerHTML = '<img src="' + media.icon + '" alt="' + media.title + '" />';
      }
      this.mediaPreview.style.display = '';
    }
  }
  hideMedia() {
    if (this.mediaPreview === null) {
      return;
    }
    if (this.player) {
      if (!this.player.paused) {
        this.player.pause();
      }
      delete this.player;
    }
    this.mediaPreview.style.display = 'none';
    this.mediaPreview.innerHTML = '';
  }
  togglePreview(visible) {
    if (this.mediaPreview === null) {
      return;
    }
    if (typeof show === 'undefined') {
      visible = this.hasMedia();
    }
    this.mediaPreview.style.display = visible ? '' : 'none';
  }
  toggleButtons() {
    this.addButton.style.display = this.hasMedia() ? 'none' : '';
    this.removeButton.style.display = this.hasMedia() ? '' : 'none';
  }
}

/**
 * Image field
 */
class ImageField extends Field {
  constructor(el) {
    super(el);
    this.mediaPopup = null;
    this.imageIDField = this.element.querySelector('.image-id');
    this.addButton = this.element.querySelector('.add-image-button');
    this.removeButton = this.element.querySelector('.remove-image-button');
    this.image = this.element.querySelector('.image-wrapper img');
    this.initButtons();
  }
  initButtons() {
    this.addButton.addEventListener('click', event => {
      event.preventDefault();
      if (this.mediaPopup) {
        this.mediaPopup.open();
        return;
      }
      let mediaParams = {
        library: {
          type: 'image'
        },
        multiple: false
      };
      if (this.hasImage()) {
        mediaParams = Object.assign(mediaParams, {
          editing: true
        });
      }
      this.mediaPopup = wp.media(mediaParams);
      this.mediaPopup.on('select', () => {
        const image = this.mediaPopup.state().get('selection').first().toJSON();
        if (image) {
          this.showImage(image);
          this.imageIDField.value = image.id;
          if (typeof this.imageIDField.onchange === 'function') {
            this.imageIDField.onchange();
          }
        }
        this.toggleButtons();
        this.mediaPopup.close();
      });
      this.mediaPopup.on('open', () => {
        if (this.hasImage()) {
          const selection = this.mediaPopup.state().get('selection');
          const id = parseInt(this.imageIDField.value, 10);
          const image = wp.media.attachment(id);
          image.fetch();
          selection.add(image ? [image] : []);
        }
      });
      this.mediaPopup.open();
    });
    this.removeButton.addEventListener('click', event => {
      event.preventDefault();
      this.imageIDField.value = '';
      if (typeof this.imageIDField.onchange === 'function') {
        this.imageIDField.onchange();
      }
      this.hideImage();
      this.toggleButtons();
    });
    this.toggleButtons();
  }
  hasImage() {
    return parseInt(this.imageIDField.value, 10) > 0;
  }
  showImage(image) {
    if (this.image && image) {
      const previewImage = image.sizes[this.image.dataset.size] ? image.sizes[this.image.dataset.size] : image.sizes.full;
      if (previewImage) {
        this.image.setAttribute('width', previewImage.width);
        this.image.setAttribute('height', previewImage.height);
        this.image.setAttribute('src', previewImage.url);
        this.image.setAttribute('alt', image.alt);
        this.image.style.display = '';
      }
    }
  }
  hideImage() {
    if (this.image) {
      this.image.style.display = 'none';
      this.image.removeAttribute('src');
    }
  }
  toggleButtons() {
    this.addButton.style.display = this.hasImage() ? 'none' : '';
    this.removeButton.style.display = this.hasImage() ? '' : 'none';
  }
}

/**
 * Images field
 */
class ImagesField extends Field {
  constructor(el) {
    super(el);
    this.mediaPopup = null;
    this.imagesList = this.element.querySelector('.flext-gallery-images');
    this.imageIDs = this.element.querySelector('.flext-image-ids');
    this.addButton = this.element.querySelector('.add-image-button');
    this.initAddButton();
    this.initDeleteButtons();
    this.initSortable();
  }
  initAddButton() {
    this.addButton.addEventListener('click', event => {
      event.preventDefault();

      // If the media frame already exists, reopen it.
      if (this.mediaPopup) {
        this.mediaPopup.open();
        return;
      }

      // Create the media frame.
      this.mediaPopup = wp.media({
        // Set the title of the modal.
        title: this.addButton.dataset.title,
        button: {
          text: this.addButton.dataset.add
        },
        states: [new wp.media.controller.Library({
          title: this.addButton.dataset.title,
          filterable: 'all',
          multiple: true
        })]
      });

      // When an image is selected, run a callback.
      this.mediaPopup.on('select', () => {
        const selection = this.mediaPopup.state().get('selection');
        const imageIds = this.imageIDs.value ? this.imageIDs.value.split(',') : [];
        selection.forEach(image => {
          image = image.toJSON();
          if (image.id) {
            imageIds.push(image.id);
            const imageUrl = image.sizes && image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url;
            const item = document.createElement('li');
            item.classList.add('flext-field-image');
            item.dataset.imageId = image.id;
            item.innerHTML = '<img src="' + imageUrl + '" /><a href="#" class="delete-item-button"><i class="dashicons dashicons-no-alt"></i></a>';
            this.imagesList.appendChild(item);
          }
        });
        this.imageIDs.value = imageIds.join(',');
        this.initDeleteButtons();
      });

      // Finally, open the modal.
      this.mediaPopup.open();
    });
  }
  initDeleteButtons() {
    // Remove button.
    this.element.querySelectorAll('.delete-item-button').forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();
        button.parentElement.remove();
        this.updateImages();
        return false;
      });
    });
  }
  initSortable() {
    // Image ordering.
    jQuery(this.imagesList).sortable({
      items: 'li',
      cursor: 'move',
      scrollSensitivity: 40,
      forceHelperSize: true,
      helper: 'clone',
      opacity: 0.65,
      update: () => {
        this.updateImages();
      }
    });
  }
  updateImages() {
    const imageIds = [];
    this.imagesList.querySelectorAll('li').forEach(image => {
      const imageId = image.dataset.imageId;
      if (imageId) {
        imageIds.push(imageId);
      }
    });
    this.imageIDs.value = imageIds.join(',');
  }
}

/**
 * File field
 */
class FileField extends Field {
  constructor(el) {
    super(el);
    this.mediaPopup = null;
    this.fileIDField = this.element.querySelector('.file-id');
    this.addButton = this.element.querySelector('.add-file-button');
    this.removeButton = this.element.querySelector('.remove-file-button');
    this.preview = this.element.querySelector('.file-preview');
    this.fileType = this.fileIDField.dataset.type;
    this.file = null;
    this.initButtons();
  }
  initButtons() {
    this.addButton.addEventListener('click', event => {
      event.preventDefault();
      if (this.mediaPopup) {
        this.mediaPopup.open();
        return;
      }
      this.mediaPopup = wp.media({
        library: {
          type: this.fileType
        },
        multiple: false
      });
      this.mediaPopup.on('select', () => {
        this.file = this.mediaPopup.state().get('selection').first().toJSON();
        if (this.file) {
          this.fileIDField.value = this.file.id;
          this.showPreview();
        }
        this.toggleButtons();
        this.mediaPopup.close();
      });
      this.mediaPopup.on('open', () => {
        if (this.hasFile()) {
          const selection = this.mediaPopup.state().get('selection');
          const id = parseInt(this.fileIDField.value, 10);
          const attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add(attachment ? [attachment] : []);
        }
      });
      this.mediaPopup.open();
    });
    this.removeButton.addEventListener('click', event => {
      event.preventDefault();
      if (this.removeButton.dataset.deleteFile && this.removeButton.dataset.confirmMessage) {
        const {
          confirm
        } = window;
        if (confirm(this.removeButton.dataset.confirmMessage)) {
          this.deleteFile();
        }
      } else {
        this.removeFile();
      }
    });
    this.toggleButtons();
  }
  hasFile() {
    return parseInt(this.fileIDField.value, 10) > 0;
  }
  showPreview() {
    if (this.preview && this.file) {
      this.preview.style.display = '';
      this.preview.innerHTML = '<a href="' + this.file.url + '" target="_blank">' + this.file.filename + '</a>';
    }
  }
  hidePreview() {
    if (this.preview) {
      this.preview.innerText = '';
      this.preview.style.display = 'none';
    }
  }
  removeFile() {
    this.file = null;
    this.fileIDField.value = '';
    this.hidePreview();
    this.toggleButtons();
  }
  deleteFile() {
    const id = this.file ? this.file.id : parseInt(this.fileIDField.value, 10);
    const attachment = wp.media.attachment(id);
    attachment.fetch().done(() => {
      attachment.destroy();
      this.file = null;
      this.fileIDField.value = '';
      this.hidePreview();
      this.toggleButtons();
    });
  }
  toggleButtons() {
    this.removeButton.style.display = this.hasFile() ? '' : 'none';
  }
}

/**
 * Fields List field
 */
class FieldsListField extends Field {
  constructor(el) {
    super(el);
    this.initFieldsList();
  }
  initFieldsList() {
    this.list = this.element.querySelector('.flext-input-list');
    this.isAddable = this.list.classList.contains('flext-is-addable');
    this.sortable = this.list.classList.contains('flext-is-sortable');
    this.showPlaceholder = this.list.classList.contains('flext-has-placeholder');
    if (this.isAddable) {
      this.list.querySelectorAll('.flext-input-list > li').forEach(item => {
        item.append(this.createDeleteButton());
      });
      this.template = this.element.querySelector('#' + this.element.id + '-template');
      if (this.template !== null) {
        this.addButton = this.element.querySelector('#' + this.element.id + '-add-button');
        if (this.addButton !== null) {
          this.addButton.addEventListener('click', event => {
            event.preventDefault();
            this.addNewItem();
            return false;
          });
        }
        if (this.list.children.length < 1 && this.showPlaceholder) {
          this.addNewItem();
        }
      }
    }
    if (this.sortable) {
      jQuery(this.list).sortable();
    }
  }
  updateItemIndex(content) {
    ['[id]', '[name]', '[for]'].forEach(selector => {
      content.querySelectorAll(selector).forEach(el => {
        const attribute = selector.replace(/[\[\]]/g, '');
        let newAttribute = el.getAttribute(attribute);
        if (newAttribute) {
          newAttribute = newAttribute.replace(/\{\{index\}\}|\-\-index/, this.list.children.length);
        }
        el.setAttribute(attribute, newAttribute);
        if (el.classList.contains('flext-input-template')) {
          this.updateItemIndex(el.content);
        }
      });
    });
  }
  addNewItem() {
    const item = document.createElement('li');
    const content = this.template.content.cloneNode(true);
    this.updateItemIndex(content);
    item.append(content);
    if (this.isAddable) {
      item.append(this.createDeleteButton());
    }
    this.list.append(item);
    initFields(item);
    flextension.emit('fieldsListItemAdded', item);
  }
  createDeleteButton() {
    const deleteButton = document.createElement('a');
    deleteButton.setAttribute('href', '#');
    deleteButton.classList.add('flext-delete-item-button');
    deleteButton.innerHTML = '<i class="dashicons dashicons-no-alt"></i>';
    deleteButton.addEventListener('click', event => {
      event.preventDefault();
      flextension.emit('fieldsListItemDeleted', deleteButton.parentElement);
      deleteButton.parentElement.remove();
      return false;
    });
    return deleteButton;
  }
}

/**
 * Initializes the fields.
 *
 * @param {Node} container The content container element to initialize.
 */
function initFields(container) {
  container.querySelectorAll('.flext-field').forEach(field => {
    switch (field.dataset.type) {
      case 'color':
        new ColorField(field);
        break;
      case 'image':
        new ImageField(field);
        break;
      case 'images':
        new ImagesField(field);
        break;
      case 'media':
        new MediaField(field);
        break;
      case 'file':
        new FileField(field);
        break;
      case 'range':
        new RangeField(field);
        break;
      case 'fields_list':
        new FieldsListField(field);
        break;
      default:
        new Field(field);
        break;
    }
  });
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  initFields(content);
});
/******/ })()
;