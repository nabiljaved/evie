/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/utils.js
/**
 * Deletes all properties of the object.
 *
 * @param {Object} obj The object to delete.
 */
function deleteProps(obj) {
  const object = obj;
  Object.keys(object).forEach(key => {
    try {
      object[key] = null;
    } catch (e) {
      // no getter for object
    }
    try {
      delete object[key];
    } catch (e) {
      // something got wrong
    }
  });
}

;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/media-player.js

const {
  flextension
} = window;
flextension.mediaPlayer = null;
let isMediaPlayerMuted = true;

/**
 * Media Player.
 *
 * @param {Element} element A media container.
 */
function MediaPlayer(element) {
  if (!element) {
    return;
  }
  if (element.classList.contains('is-player-init')) {
    return;
  }
  element.classList.add('is-player-init');
  this.element = element;

  /**
   * Returns whether the media is paused or not.
   */
  this.paused = false;

  /**
   * Returns whether the media is muted or not.
   */
  this.muted = true;
  this.player = null;
  this.ready = false;
  this.mediaUrl = this.element.dataset.src;
  this.mediaLink = this.element.querySelector('.flext-media-link');
  if (this.mediaLink !== null) {
    this.mediaLink.setAttribute('href', '');
    this.mediaLink.addEventListener('click', event => {
      event.preventDefault();
      if (this.player === null || this.paused === true) {
        this.play();
        if (this.element.dataset.type === 'audio' && this.muted === true) {
          this.unMute();
        }
      } else {
        this.pause();
      }
      return false;
    });
  }
  this.loadThumbnail();
}

/**
 * Fired when the player begins to initialize.
 */
MediaPlayer.prototype.onInit = function () {
  this.element.classList.add('flext-media-loading');
  this.element.classList.add('flext-is-muted');
  flextension.emit('mediaPlayer.init', this);
};

/**
 * Fired when the player is ready.
 */
MediaPlayer.prototype.onReady = function () {
  if (this.ready !== true) {
    this.ready = true;
    this.element.classList.remove('flext-media-loading');
    this.element.classList.add('flext-media-active');
    flextension.emit('mediaPlayer.ready', this);
  }
};

/**
 * Fired when the player begins to play.
 */
MediaPlayer.prototype.onPlay = function () {
  if (this.ready === true) {
    this.paused = false;
    this.element.classList.add('flext-media-active');
    this.element.classList.remove('flext-is-paused');
    this.element.classList.add('flext-is-playing');
    flextension.emit('mediaPlayer.play', this);
  }
};

/**
 * Fired when the player pauses.
 */
MediaPlayer.prototype.onPause = function () {
  if (this.ready === true) {
    this.paused = true;
    this.element.classList.remove('flext-is-playing');
    this.element.classList.add('flext-is-paused');
    this.element.classList.remove('flext-media-active');
    flextension.emit('mediaPlayer.pause', this);
  }
};

/**
 * Fired when the player mutes.
 */
MediaPlayer.prototype.onMute = function () {
  this.muted = true;
  isMediaPlayerMuted = true;
  this.element.classList.add('flext-is-muted');
  flextension.emit('mediaPlayer.mute', this);
};

/**
 * Fired when the player unmutes.
 */
MediaPlayer.prototype.onUnmute = function () {
  this.muted = false;
  isMediaPlayerMuted = false;
  this.element.classList.remove('flext-is-muted');
  flextension.emit('mediaPlayer.unmute', this);
};

/**
 * Initializes the media player.
 */
MediaPlayer.prototype.init = function () {
  this.onInit();
  this.playButton = this.element.querySelector('.flext-play-button');
  if (this.playButton !== null) {
    this.playButton.addEventListener('click', () => {
      if (this.player === null || this.paused === true) {
        this.play();
      } else {
        this.pause();
      }
    });
  }
  this.volumeButton = this.element.querySelector('.flext-volume-button');
  if (this.volumeButton !== null) {
    this.volumeButton.addEventListener('click', () => {
      if (this.player !== null && this.ready === true) {
        if (this.muted === true) {
          this.unMute();
        } else {
          this.mute();
        }
      }
    });
  }
  this.fullButton = this.element.querySelector('.flext-fullscreen-button');
};

/**
 * Starts playing the media.
 */
MediaPlayer.prototype.play = function () {
  if (flextension.mediaPlayer && flextension.mediaPlayer !== this) {
    flextension.mediaPlayer.pause();
  }
  if (isMediaPlayerMuted === true) {
    this.mute();
  } else {
    this.unMute();
  }
  flextension.mediaPlayer = this;
  return true;
};

/**
 * Pauses the currently playing media.
 */
MediaPlayer.prototype.pause = function () {
  return true;
};

/**
 * Turns off sound for the media.
 */
MediaPlayer.prototype.mute = function () {
  this.onMute();
};

/**
 * Turns on sound for the media.
 */
MediaPlayer.prototype.unMute = function () {
  this.onUnmute();
};

/**
 * Obtains the media's thumbnail and passed it back to a callback function.
 */
MediaPlayer.prototype.loadThumbnail = function () {
  return true;
};

/**
 * Resizes the media.
 */
MediaPlayer.prototype.resize = function () {
  return true;
};

/**
 * Disposes the media player.
 */
MediaPlayer.prototype.dispose = function () {
  this.ready = false;
  this.player = null;
  this.element = null;
  deleteProps(this);
  return null;
};
/* harmony default export */ var media_player = (MediaPlayer);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/html5-player.js

const {
  flextension: html5_player_flextension
} = window;

/**
 * HTML5 Video/Audio Player.
 *
 * @param {Element} element A media container.
 */
function HTML5Player(element) {
  media_player.call(this, element);
}
HTML5Player.prototype = new media_player();

/**
 * Initializes the media player.
 */
HTML5Player.prototype.init = function () {
  media_player.prototype.init.call(this);
  const isVideo = this.element.dataset.type === 'video';
  if (isVideo === true) {
    this.media = document.createElement('video');
  } else {
    this.media = document.createElement('audio');
  }
  this.media.classList.add('media-embed');
  this.media.setAttribute('id', 'media-embed-' + parseInt(this.element.getAttribute('id').replace(/^\D+/g, ''), 10));
  this.media.defaultMuted = true;
  this.media.muted = true;
  this.media.setAttribute('loop', 'loop');
  this.media.setAttribute('muted', 'muted');
  this.media.setAttribute('playsinline', true);
  this.media.setAttribute('preload', 'auto');
  const source = document.createElement('source');
  source.setAttribute('src', this.mediaUrl);
  source.setAttribute('type', this.element.dataset.mediaType);
  this.media.append(source);
  this.media.addEventListener('loadeddata', () => {
    media_player.prototype.onReady.call(this);
  });
  this.media.addEventListener('canplay', () => {
    if (this.ready === true) {
      media_player.prototype.play.call(this);
      this.media.play().then(() => {
        this.paused = false;
        this.element.classList.remove('flext-is-paused');
        this.element.classList.add('flext-is-playing');
      }).catch(() => {
        this.paused = true;
        this.element.classList.remove('flext-is-playing');
        this.element.classList.add('flext-is-paused');
        if (this.element.dataset.type === 'audio' && this.muted === true) {
          this.ready = false;
        }
      });
    }
  });
  this.media.addEventListener('play', () => {
    media_player.prototype.onPlay.call(this);
  });
  this.media.addEventListener('pause', () => {
    media_player.prototype.onPause.call(this);
  });
  this.element.prepend(this.media);
  this.media.muted = true;
  this.media.load();
  this.player = this.media;
  if (this.fullButton !== null) {
    this.fullButton.addEventListener('click', () => {
      if (this.player !== null && this.ready === true) {
        new html5_player_flextension.lightbox(this.player, {
          onOpen: () => {
            this.player.controls = true;
            this.unMute();
            this.play();
            setTimeout(() => {
              this.element.classList.remove('flext-media-active');
            }, 500);
          },
          onClose: () => {
            this.muted = this.media.muted;
            if (this.muted === true) {
              this.mute();
            }
            this.player.controls = false;
            this.element.classList.add('flext-media-active');
          }
        });
      }
    });
  }
};

/**
 * Starts playing the media.
 */
HTML5Player.prototype.play = function () {
  if (this.player === null) {
    this.init();
  } else if (this.ready === true && this.paused === true) {
    media_player.prototype.play.call(this);
    this.player.play();
  } else if (this.muted === true) {
    this.ready = true;
    setTimeout(() => {
      media_player.prototype.play.call(this);
      this.player.play();
    }, 300);
  }
};

/**
 * Pauses the currently playing media.
 */
HTML5Player.prototype.pause = function () {
  if (this.ready === true && this.paused === false) {
    media_player.prototype.pause.call(this);
    this.player.pause();
  }
};

/**
 * Turns off sound for the media.
 */
HTML5Player.prototype.mute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.mute.call(this);
    this.player.muted = true;
  }
};

/**
 * Turns on sound for the media.
 */
HTML5Player.prototype.unMute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.unMute.call(this);
    this.player.muted = false;
  }
};

/**
 * Obtains the media's thumbnail and passed it back to a callback function.
 */
HTML5Player.prototype.loadThumbnail = function () {
  if (this.mediaLink !== null && this.element.querySelector('img') === null) {
    media_player.prototype.loadThumbnail.call(this);
    const image = document.createElement('img');
    image.setAttribute('src', this.element.getAttribute('poster'));
    image.setAttribute('alt', '');
    this.mediaLink.append(image);
  }
};

/**
 * Disposes the media player.
 */
HTML5Player.prototype.dispose = function () {
  if (this.media !== null) {
    if (this.element !== null && this.element.contains(this.media)) {
      this.media.remove();
    }
    this.media = null;
  }
  media_player.prototype.dispose.call(this);
};
/* harmony default export */ var html5_player = (HTML5Player);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/youtube-player.js

const {
  flextension: youtube_player_flextension
} = window;

/**
 * YouTube Video Player.
 *
 * @param {Element} element A video container.
 */
function YouTubePlayer(element) {
  if (!window.YT) {
    return;
  }
  if (element) {
    this.videoId = element.dataset.videoId;
  }
  media_player.call(this, element);
}
YouTubePlayer.prototype = new media_player();

/**
 * Initializes the video player.
 */
YouTubePlayer.prototype.init = function () {
  if (!this.videoId) {
    return;
  }
  media_player.prototype.init.call(this);
  const {
    YT
  } = window;
  if (this.fullButton !== null) {
    this.fullButton.addEventListener('click', () => {
      if (this.player !== null && this.ready === true) {
        this.pause();
        const currentTime = this.player.getCurrentTime();
        let fullVideo = null;
        const iframeVideo = document.createElement('div');
        document.body.append(iframeVideo);
        new youtube_player_flextension.lightbox(iframeVideo, {
          className: 'flext-lightbox-iframe',
          onOpen: () => {
            const container = iframeVideo.parentElement;
            new YT.Player(iframeVideo, {
              videoId: this.videoId,
              height: container.clientHeight,
              width: container.clientHeight * 1.777777777777778,
              playerVars: {
                autoplay: 1,
                enablejsapi: 1,
                loop: 1,
                modestbranding: 1,
                playsinline: 1,
                rel: 0,
                showinfo: 0
              },
              events: {
                onReady: event => {
                  if (event.target) {
                    fullVideo = event.target;
                    fullVideo.seekTo(parseInt(currentTime, 10));
                  }
                },
                onStateChange: event => {
                  if (event.data === YT.PlayerState.ENDED) {
                    if (fullVideo === null) {
                      return;
                    }
                    // Loop.
                    fullVideo.seekTo(0);
                    fullVideo.playVideo();
                  }
                }
              }
            });
          },
          onBeforeClose: () => {
            if (fullVideo !== null) {
              if (fullVideo.isMuted()) {
                this.mute();
              } else {
                this.unMute();
              }
              this.player.seekTo(fullVideo.getCurrentTime());
              if (fullVideo.getPlayerState() === 1) {
                this.play();
              }
              fullVideo.destroy();
              fullVideo = null;
            }
          }
        });
      }
    });
  }
  const media = document.createElement('div');
  media.classList.add('media-embed');
  media.setAttribute('id', 'media-embed-' + parseInt(this.element.getAttribute('id').replace(/^\D+/g, ''), 10));
  this.element.prepend(media);
  const options = {
    videoId: this.videoId,
    playerVars: {
      autoplay: 0,
      controls: 0,
      disablekb: 1,
      enablejsapi: 1,
      iv_load_policy: 3,
      modestbranding: 1,
      playsinline: 1,
      rel: 0,
      showinfo: 0
    },
    events: {
      onReady: () => {
        if (this.player === null) {
          return;
        }
        if (this.ready !== true) {
          this.player.mute();
          this.media = this.player.getIframe();
          if (this.media !== null) {
            const mediaWidth = parseInt(this.media.getAttribute('width'), 10);
            const mediaHeight = parseInt(this.media.getAttribute('height'), 10);
            this.mediaRatio = mediaWidth / mediaHeight;
            this.resize();
          }
          setTimeout(() => {
            this.player.playVideo();
          }, 300);
        }
      },
      onStateChange: event => {
        if (event.data === YT.PlayerState.PLAYING) {
          if (this.player === null) {
            return;
          }
          if (this.ready !== true) {
            this.player.seekTo(0);
            media_player.prototype.onReady.call(this);
            media_player.prototype.play.call(this);
          }
          media_player.prototype.onPlay.call(this);
        } else if (event.data === YT.PlayerState.PAUSED) {
          media_player.prototype.onPause.call(this);
        } else if (event.data === YT.PlayerState.ENDED) {
          if (this.player === null) {
            return;
          }
          // Loop.
          this.player.seekTo(0);
          this.player.playVideo();
        }
      }
    }
  };
  if (this.element.dataset.src.match(/\/shorts\//)) {
    options.height = this.element.dataset.height || 640;
    options.width = this.element.dataset.width || 390;
  }
  this.player = new YT.Player(media, options);
};

/**
 * Starts playing the video.
 */
YouTubePlayer.prototype.play = function () {
  if (this.player === null) {
    this.init();
  } else if (this.ready === true && this.paused === true) {
    media_player.prototype.play.call(this);
    this.player.playVideo();
  }
};

/**
 * Pauses the currently playing video.
 */
YouTubePlayer.prototype.pause = function () {
  if (this.player !== null && this.ready === true && this.paused === false) {
    media_player.prototype.pause.call(this);
    this.player.pauseVideo();
  }
};

/**
 * Turns off sound for the video.
 */
YouTubePlayer.prototype.mute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.mute.call(this);
    this.player.mute();
  }
};

/**
 * Turns on sound for the video.
 */
YouTubePlayer.prototype.unMute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.unMute.call(this);
    this.player.unMute();
  }
};

/**
 * Obtains the video's thumbnail and passed it back to a callback function.
 */
YouTubePlayer.prototype.loadThumbnail = function () {
  if (this.videoId && this.mediaLink !== null && this.element.querySelector('img') === null) {
    media_player.prototype.loadThumbnail.call(this);
    const image = document.createElement('img');
    image.setAttribute('src', 'img.youtube.com/vi/' + this.videoId + '/maxresdefault.jpg');
    image.setAttribute('alt', '');
    this.mediaLink.append(image);
  }
};

/**
 * Resizes the video.
 *
 * @param {Node}   container The DOM container of the video.
 * @param {number} ratio     The ratio of the media.
 */
YouTubePlayer.prototype.resize = function (container, ratio) {
  if (this.player === null) {
    return;
  }
  if (!ratio) {
    ratio = this.mediaRatio;
  }
  if (!ratio) {
    return;
  }
  if (!container) {
    container = this.element;
  }
  media_player.prototype.resize.call(this);
  const containerWidth = container.offsetWidth;
  const containerHeight = container.offsetHeight;
  const containerRatio = containerWidth / containerHeight;
  let targetWidth = containerWidth;
  let targetHeight = containerHeight;
  if (containerRatio > ratio) {
    targetHeight = containerWidth / ratio;
  } else {
    targetWidth = containerHeight * ratio;
  }
  const hideControls = 200; // Additional height to hide the video controls and logo.
  this.player.setSize(targetWidth, targetHeight + hideControls);
};

/**
 * Disposes the video player.
 */
YouTubePlayer.prototype.dispose = function () {
  if (this.player !== null) {
    this.player.destroy();
  }
  media_player.prototype.dispose.call(this);
};
/* harmony default export */ var youtube_player = (YouTubePlayer);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/vimeo-player.js

const {
  flextension: vimeo_player_flextension
} = window;

/**
 * Vimeo Video Player
 *
 * @param {Element} element A video container.
 */
function VimeoPlayer(element) {
  if (!window.Vimeo) {
    return;
  }
  if (element) {
    this.videoId = element.dataset.videoId;
  }
  media_player.call(this, element);
}
VimeoPlayer.prototype = new media_player();

/**
 * Initializes the video player.
 */
VimeoPlayer.prototype.init = function () {
  if (!this.videoId) {
    return;
  }
  media_player.prototype.init.call(this);
  const {
    Vimeo
  } = window;
  this.fullButton = this.element.querySelector('.flext-fullscreen-button');
  if (this.fullButton !== null) {
    this.fullButton.addEventListener('click', () => {
      if (this.player !== null && this.ready === true) {
        this.player.getCurrentTime().then(currentTime => {
          this.pause();
          let fullVideo = null;
          new vimeo_player_flextension.lightbox('<span class="flext-loader flext-loder-light"></span>', {
            className: 'flext-lightbox-iframe',
            onOpen: lightbox => {
              fullVideo = new Vimeo.Player(lightbox.container, {
                autoplay: true,
                id: this.videoId,
                loop: true,
                maxheight: lightbox.container.clientHeight,
                maxwidth: lightbox.container.clientHeight * 1.777777777777778
              });
              fullVideo.setVolume(0);
              fullVideo.setCurrentTime(currentTime);
              fullVideo.ready().then(() => {
                fullVideo.play();
              });
              const loader = lightbox.container.querySelector('.flext-loader');
              if (loader !== null) {
                loader.remove();
              }
            },
            onBeforeClose: () => {
              if (fullVideo !== null) {
                Promise.all([fullVideo.getCurrentTime(), fullVideo.getPaused()]).then(props => {
                  const time = props[0];
                  const paused = props[1];
                  this.player.setCurrentTime(time);
                  if (paused !== true) {
                    this.play();
                  }
                  fullVideo.destroy().then(() => {
                    fullVideo = null;
                  });
                });
              }
            }
          });
        });
      }
    });
  }
  this.player = new Vimeo.Player(this.element, {
    autoplay: true,
    background: true,
    byline: false,
    id: this.videoId,
    loop: true,
    muted: true,
    playsinline: true,
    transparent: true
  });
  this.player.on('play', () => {
    if (this.ready !== true) {
      media_player.prototype.onReady.call(this);
    }
    media_player.prototype.onPlay.call(this);
  });
  this.player.on('pause', () => {
    media_player.prototype.onPause.call(this);
  });
  this.player.ready().then(() => {
    this.media = this.element.querySelector('iframe');
    this.media.classList.add('media-embed');
    if (this.media !== null) {
      const mediaWidth = parseInt(this.media.getAttribute('width'), 10);
      const mediaHeight = parseInt(this.media.getAttribute('height'), 10);
      this.mediaRatio = mediaWidth / mediaHeight;
      this.resize();
    }
    setTimeout(() => {
      media_player.prototype.play.call(this);
      this.player.play();
    }, 300);
  });
};

/**
 * Starts playing the video.
 */
VimeoPlayer.prototype.play = function () {
  if (this.player === null) {
    this.init();
  } else if (this.ready === true && this.paused === true) {
    media_player.prototype.play.call(this);
    this.player.play();
  }
};

/**
 * Pauses the currently playing video.
 */
VimeoPlayer.prototype.pause = function () {
  if (this.player !== null && this.ready === true && this.paused === false) {
    this.player.pause();
    media_player.prototype.pause.call(this);
  }
};

/**
 * Turns off sound for the video.
 */
VimeoPlayer.prototype.mute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.mute.call(this);
    this.player.setVolume(0);
  }
};

/**
 * Turns on sound for the video.
 */
VimeoPlayer.prototype.unMute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.unMute.call(this);
    this.player.setVolume(1);
  }
};

/**
 * Obtains the video's thumbnail and passed it back to a callback function.
 */
VimeoPlayer.prototype.loadThumbnail = function () {
  if (this.videoId && this.mediaLink !== null && this.element.querySelector('img') === null) {
    media_player.prototype.loadThumbnail.call(this);
    const {
      fetch
    } = window;
    fetch('http://vimeo.com/api/v2/video/' + this.videoId + '.json').then(response => response.json()).then(data => {
      if (Array.isArray(data) && data.length > 0 && data[0].thumbnail_large) {
        const image = document.createElement('img');
        image.setAttribute('src', data[0].thumbnail_large);
        image.setAttribute('alt', '');
        this.mediaLink.append(image);
      }
    });
  }
};

/**
 * Resizes the video.
 */
VimeoPlayer.prototype.resize = function () {
  if (this.player === null || this.media === null || !this.mediaRatio) {
    return;
  }
  media_player.prototype.resize.call(this);
  const containerWidth = this.element.offsetWidth;
  const containerHeight = this.element.offsetHeight;
  const containerRatio = containerWidth / containerHeight;
  let targetWidth = containerWidth;
  let targetHeight = containerHeight;
  if (containerRatio > this.mediaRatio) {
    targetHeight = containerWidth / this.mediaRatio;
  } else {
    targetWidth = containerHeight * this.mediaRatio;
  }
  this.media.style.width = targetWidth + 'px';
  this.media.style.height = targetHeight + 'px';
};

/**
 * Disposes the video player.
 */
VimeoPlayer.prototype.dispose = function () {
  if (this.player !== null) {
    this.player.destroy();
  }
  media_player.prototype.dispose.call(this);
};
/* harmony default export */ var vimeo_player = (VimeoPlayer);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/soundcloud-player.js

const {
  flextension: soundcloud_player_flextension
} = window;

/**
 * SoundCloud Player
 *
 * @param {Element} element A media container.
 */
function SoundCloudPlayer(element) {
  media_player.call(this, element);
}
SoundCloudPlayer.prototype = new media_player();

/**
 * Initializes the video player.
 */
SoundCloudPlayer.prototype.init = function () {
  media_player.prototype.init.call(this);
  const {
    SC
  } = window;
  const playerUri = 'https://w.soundcloud.com/player/';
  if (this.fullButton !== null) {
    this.fullButton.addEventListener('click', () => {
      if (this.player !== null && this.ready === true) {
        this.pause();
        this.player.getPosition(currentTime => {
          let fullMedia = null;
          new soundcloud_player_flextension.lightbox('<span class="flext-loader flext-loder-light"></span>', {
            className: 'flext-lightbox-iframe',
            onOpen: lightbox => {
              const container = lightbox.container;
              container.innerHTML = '';
              const iframeAudio = document.createElement('iframe');
              iframeAudio.setAttribute('src', playerUri + '?show_artwork=true&visual=true&url=' + encodeURIComponent(this.mediaUrl));
              iframeAudio.setAttribute('allow', 'autoplay');
              iframeAudio.setAttribute('height', container.clientHeight);
              iframeAudio.setAttribute('width', container.clientHeight * 1.777777777777778);
              container.append(iframeAudio);
              fullMedia = new SC.Widget(iframeAudio);
              fullMedia.bind(SC.Widget.Events.READY, () => {
                fullMedia.seekTo(currentTime);
                fullMedia.play();
              });
            },
            onBeforeClose: () => {
              if (fullMedia !== null) {
                fullMedia.isPaused(paused => {
                  fullMedia.getPosition(currentPosition => {
                    this.player.seekTo(currentPosition);
                    if (!paused) {
                      this.play();
                    }
                    fullMedia.pause();
                    fullMedia = null;
                  });
                });
              }
            }
          });
        });
      }
    });
  }
  this.media = document.createElement('iframe');
  this.media.classList.add('media-embed');
  this.media.setAttribute('src', playerUri + '?sharing=false&buying=false&download=false&show_playcount=false&show_user=false&show_comments=false&show_artwork=false&url=' + encodeURIComponent(this.mediaUrl));
  this.media.setAttribute('allow', 'autoplay');
  this.element.prepend(this.media);
  this.player = new SC.Widget(this.media);
  this.player.bind(SC.Widget.Events.READY, () => {
    this.player.bind(SC.Widget.Events.PLAY, () => {
      if (this.muted === true) {
        this.pause();
      }
      media_player.prototype.onPlay.call(this);
    });
    this.player.bind(SC.Widget.Events.PAUSE, () => {
      media_player.prototype.onPause.call(this);
    });
    this.player.bind(SC.Widget.Events.FINISH, () => {
      // Loop.
      this.player.seekTo(0);
      this.player.play();
    });
    media_player.prototype.onReady.call(this);
    media_player.prototype.play.call(this);
    if (this.muted === true) {
      this.mute();
      this.paused = true;
    } else {
      this.player.play();
    }
  });
};

/**
 * Starts playing the video.
 */
SoundCloudPlayer.prototype.play = function () {
  if (this.player === null) {
    this.init();
  } else if (this.ready === true && this.paused === true) {
    media_player.prototype.play.call(this);
    if (this.muted === false) {
      this.player.play();
    }
  }
};

/**
 * Pauses the currently playing video.
 */
SoundCloudPlayer.prototype.pause = function () {
  if (this.player !== null && this.ready === true && this.paused === false) {
    media_player.prototype.pause.call(this);
    this.player.pause();
  }
};

/**
 * Turns off sound for the video.
 */
SoundCloudPlayer.prototype.mute = function () {
  if (this.ready === true && this.player !== null) {
    media_player.prototype.mute.call(this);
    this.player.setVolume(0);
    this.player.pause();
  }
};

/**
 * Turns on sound for the video.
 */
SoundCloudPlayer.prototype.unMute = function () {
  if (this.ready === true && this.player !== null) {
    if (this.paused === true) {
      this.player.play();
    }
    media_player.prototype.unMute.call(this);
    this.player.setVolume(100);
  }
};

/**
 * Obtains the video's thumbnail and passed it back to a callback function.
 */
SoundCloudPlayer.prototype.loadThumbnail = function () {
  if (this.mediaLink !== null && this.element.querySelector('img') === null) {
    media_player.prototype.loadThumbnail.call(this);
    const {
      fetch
    } = window;
    fetch('https://soundcloud.com/oembed?format=json&url=' + encodeURIComponent(this.mediaUrl)).then(response => {
      return response.json();
    }).then(data => {
      if (data && data.thumbnail_url) {
        const image = document.createElement('img');
        image.setAttribute('src', data.thumbnail_url);
        image.setAttribute('alt', data.title);
        this.mediaLink.append(image);
      }
    });
  }
};

/**
 * Disposes the video player.
 */
SoundCloudPlayer.prototype.dispose = function () {
  media_player.prototype.dispose.call(this);
};
/* harmony default export */ var soundcloud_player = (SoundCloudPlayer);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/inc/featured-slider.js
/**
 * Initializes the featured slider.
 *
 * @param {Element} element The target element.
 * @param {Object}  options The slider options.
 */
function FeaturedSlider(element, options) {
  if (element === null) {
    return;
  }
  if (element.dataset.featuredSlider) {
    return;
  }
  element.dataset.featuredSlider = true;
  const settings = Object.assign({
    effect: 'creative',
    slidesPerView: 1,
    breakpoints: {
      768: {
        slidesPerView: 1,
        slidesPerGroup: 1
      }
    }
  }, options || {});
  const {
    flextension
  } = window;
  return new flextension.carousel(element, settings);
}
/* harmony default export */ var featured_slider = (FeaturedSlider);
;// CONCATENATED MODULE: ./src/modules/featured-media/js/index.js
/**
 * Featured Media
 *
 * @author  Wyde
 * @version 1.0.0
 */









const {
  flextension: js_flextension,
  imagesLoaded,
  IntersectionObserver
} = window;

/**
 * Featured Media
 *
 * @param {Element} element DOM element of the featured media.
 */
function FeaturedMedia(element) {
  if (!element) {
    return;
  }
  if (element.dataset.featuredMedia) {
    return;
  }
  element.dataset.featuredMedia = true;
  this.element = element;
  this.type = false;
  this.player = null;
  this.active = false;
  this.init();
}

/**
 * Initializes the player.
 */
FeaturedMedia.prototype.init = function () {
  // Initialize the featured media.
  this.parseMedia();
  this.initAutoplay();
  this.onResize = js_flextension.debounce(() => {
    this.resize();
  }, 300);
  window.addEventListener('resize', this.onResize, false);
};

/**
 * Initializes the autoplay feature.
 */
FeaturedMedia.prototype.initAutoplay = function () {
  const autoplay = this.element.dataset.autoplay || false;
  if (!autoplay) {
    return;
  }
  if (autoplay === 'hover') {
    const post = this.element.closest('.entry');
    if (post !== null) {
      post.addEventListener('mouseenter', () => {
        this.play();
      });
      post.addEventListener('mouseleave', () => {
        this.pause();
      });
    }
  } else if (autoplay === 'visible') {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.play();
        } else {
          this.pause();
        }
      });
    }, {
      threshold: 0.5
    });
    observer.observe(this.element);
  }
};

/**
 * Parses a media URL into a media object.
 *
 * Supported URL formats:
 *
 * YouTube:
 *  -https://www.youtube.com/watch?v=My2FRPA3Gf8
 *  -https://youtu.be/My2FRPA3Gf8
 *  -https://youtube.googleapis.com/v/My2FRPA3Gf8
 *
 * Vimeo:
 *  -https://vimeo.com/25451551
 *  -https://player.vimeo.com/video/25451551
 *  -player.vimeo.com/video/25451551
 *
 * SoundCloud:
 *  -https://soundcloud.com/user/sound
 *
 * HTML5 Video:
 *  -*.mp4, *.ogg, *.webm
 *
 * HTML5 Audio:
 *  -*.mp3, *.mp4, *.wav
 */
FeaturedMedia.prototype.parseMedia = function () {
  this.element.classList.add('flext-media-player');
  this.element.classList.add('flext-media-initialized');
  const mediaUrl = this.element.dataset.src;
  if (!mediaUrl) {
    return false;
  }
  const matches = mediaUrl.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|soundcloud\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/|shorts\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
  if (matches !== null && matches[3] && matches[6]) {
    if (matches[3].indexOf('youtu') > -1) {
      this.type = 'youtube';
      this.element.dataset.videoId = matches[6];
      // Initialize YouTube video player only when API is ready.
      if (window.YT) {
        this.player = new youtube_player(this.element);
      }
    } else if (matches[3].indexOf('vimeo') > -1) {
      this.type = 'vimeo';
      this.element.dataset.videoId = matches[6];
      // Initialize Vimeo video player only when API is ready.
      if (window.Vimeo) {
        this.player = new vimeo_player(this.element);
      }
    } else if (matches[3].indexOf('soundcloud') > -1) {
      this.type = 'soundcloud';
      // Initialize Vimeo video player only when API is ready.
      if (window.SC) {
        this.player = new soundcloud_player(this.element);
      }
    }
  } else {
    const mediaType = this.element.dataset.type;
    const extensions = mediaUrl.match(/\.([^.]*?)(?=\?|#|$)/);
    if (extensions && extensions.length > 1) {
      // Initialize HTML5 player only for supported formats.
      const mediaTypes = ['mp4', 'ogg', 'webm', 'mp3', 'wav'];
      if (mediaTypes.includes(extensions[1])) {
        this.type = 'html5';
        const format = extensions[1] === 'mp3' ? 'mpeg' : extensions[1];
        this.element.dataset.mediaType = mediaType + '/' + format;
        this.player = new html5_player(this.element);
      }
    }
  }
};

/**
 * Sets the player according to the media type.
 */
FeaturedMedia.prototype.updatePlayer = function () {
  if (this.type !== false) {
    switch (this.type) {
      case 'youtube':
        if (window.YT) {
          this.player = new youtube_player(this.element);
          if (this.active === true) {
            this.player.play();
          }
        }
        break;
      case 'vimeo':
        if (window.Vimeo) {
          this.player = new vimeo_player(this.element);
          if (this.active === true) {
            this.player.play();
          }
        }
        break;
      case 'soundcloud':
        if (window.SC) {
          this.player = new soundcloud_player(this.element);
          if (this.active === true) {
            this.player.play();
          }
        }
        break;
      case 'html5':
        this.player = new html5_player(this.element);
        if (this.active === true) {
          this.player.play();
        }
        break;
    }
  } else {
    this.parseMedia();
  }
};

/**
 * Plays a featured media.
 */
FeaturedMedia.prototype.play = function () {
  if (this.player && this.active === false) {
    this.player.play();
  }
  this.active = true;
};

/**
 * Pauses a featured media.
 */
FeaturedMedia.prototype.pause = function () {
  if (this.player && this.active === true) {
    this.player.pause();
  }
  this.active = false;
};

/**
 * Mutes a featured media.
 */
FeaturedMedia.prototype.mute = function () {
  if (this.player && this.active === true) {
    this.player.mute();
  }
};

/**
 * Unmutes a featured media.
 */
FeaturedMedia.prototype.unMute = function () {
  if (this.player && this.active === true) {
    this.player.unMute();
  }
};

/**
 * Resizes the media player.
 */
FeaturedMedia.prototype.resize = function () {
  if (this.player) {
    this.player.resize();
  }
};

/**
 * Resets the states and destroys the player.
 */
FeaturedMedia.prototype.dispose = function () {
  window.removeEventListener('resize', this.onResize, false);
  this.type = null;
  this.active = null;
  if (this.player) {
    this.player.dispose();
  }
  this.player = null;
  this.element.remove();
  this.element = null;
  deleteProps(this);
  return null;
};

/**
 * The list of featured media player.
 */
js_flextension.featuredMediaPlayers = [];

/**
 * Returns whether YouTube API is ready.
 */
let YTAPI_READY = false;

/**
 * Initializes featured video and audio for the posts.
 *
 * @param {Element} content Content element, container of the posts.
 */
function initFeaturedMedia(content) {
  let hasYouTubeVideo = false,
    hasVimeoVideo = false,
    hasSoundCloudAudio = false;
  js_flextension.emit('featuredMedia.beforeInitVideos', js_flextension.featuredMediaPlayers);
  content.querySelectorAll('.flext-post-video:not(.flext-media-initialized), .flext-post-audio:not(.flext-media-initialized)').forEach(el => {
    const media = new FeaturedMedia(el);
    js_flextension.featuredMediaPlayers.push(media);
    if (media) {
      switch (media.type) {
        case 'youtube':
          hasYouTubeVideo = true;
          break;
        case 'vimeo':
          hasVimeoVideo = true;
          break;
        case 'soundcloud':
          hasSoundCloudAudio = true;
          break;
      }
    }
  });
  if (hasYouTubeVideo && YTAPI_READY === false) {
    window.onYouTubeIframeAPIReady = function () {
      YTAPI_READY = true;
      js_flextension.featuredMediaPlayers.forEach(video => {
        // Initialize YouTube video player if it doesn't exist.
        if (typeof video === 'object' && video.type === 'youtube' && video.player === null) {
          video.updatePlayer();
        }
      });
    };
    if (!window.YT) {
      // Load YouTube IFrame API if it doesn't exist.
      const tag = document.createElement('script');
      tag.src = 'https://www.youtube.com/iframe_api';
      const firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    } else {
      window.onYouTubeIframeAPIReady();
    }
  }
  if (hasVimeoVideo && !window.Vimeo) {
    // Load Vimeo API if it doesn't exist.
    new Promise((resolve, reject) => {
      const script = document.createElement('script');
      document.body.appendChild(script);
      script.onload = resolve;
      script.onerror = reject;
      script.async = true;
      script.src = '//player.vimeo.com/api/player.js';
    }).then(() => {
      if (window.Vimeo) {
        js_flextension.featuredMediaPlayers.forEach(video => {
          // Initialize Vimeo video player if it doesn't exist.
          if (typeof video === 'object' && video.type === 'vimeo' && video.player === null) {
            video.updatePlayer();
          }
        });
      }
    });
  }
  if (hasSoundCloudAudio && !window.SC) {
    // Load SoundCloud API if it doesn't exist.
    new Promise((resolve, reject) => {
      const script = document.createElement('script');
      document.body.appendChild(script);
      script.onload = resolve;
      script.onerror = reject;
      script.async = true;
      script.src = '//w.soundcloud.com/player/api.js';
    }).then(() => {
      if (window.SC) {
        js_flextension.featuredMediaPlayers.forEach(audio => {
          // Initialize SoundCloud audio player if it doesn't exist.
          if (typeof audio === 'object' && audio.type === 'soundcloud' && audio.player === null) {
            audio.updatePlayer();
          }
        });
      }
    });
  }
  js_flextension.emit('featuredMedia.afterInitVideos', js_flextension.featuredMediaPlayers);
}

/**
 * The list of featured sliders.
 */
js_flextension.featuredSliders = [];

/**
 * Initializes the gallery slider.
 *
 * @param {Element} content Content element.
 */
function initFeaturedSliders(content) {
  js_flextension.emit('featuredMedia.beforeInitSliders', js_flextension.featuredSliders);
  content.querySelectorAll('.flext-gallery-slider').forEach(el => {
    js_flextension.featuredSliders.push(new featured_slider(el));
  });
  js_flextension.emit('featuredMedia.afterInitSliders', js_flextension.featuredSliders);
}

/**
 * Initializes Lightbox scripts for the image.
 *
 * @param {Element} content Content element.
 */
function initGalleryLightBox(content) {
  content.querySelectorAll('.flext-featured-media.flext-has-lightbox').forEach(gallery => {
    js_flextension.lightboxGallery(gallery);
  });
}

/**
 * Adds spanning to all the masonry items
 *
 * @param {Element} content Content element.
 */
function initMasonryGallery(content) {
  content.querySelectorAll('.flext-gallery-masonry').forEach(gallery => {
    gallery.classList.add('flext-gallery-initialized');
    imagesLoaded(gallery, () => {
      updateMasonryGallery(gallery);
    });
  });
}
function updateMasonryGallery(gallery) {
  gallery.querySelectorAll('.flext-gallery-item').forEach(item => {
    const image = item.querySelector('img');
    if (image !== null) {
      const rowGap = parseInt(window.getComputedStyle(gallery).getPropertyValue('grid-row-gap'), 10),
        rowHeight = parseInt(window.getComputedStyle(gallery).getPropertyValue('grid-auto-rows'), 10);

      /*
       * Spanning for any brick = S
       * Grid's row-gap = G
       * Size of grid's implicitly create row-track = R
       * Height of item content = H
       * Net height of the item = H1 = H + G
       * Net height of the implicit row-track = T = G + R
       * S = H1 / T
       */
      const rowSpan = Math.ceil((image.getBoundingClientRect().height + rowGap) / (rowHeight + rowGap));
      item.style.gridRowEnd = 'span ' + rowSpan;
      image.style.height = item.getBoundingClientRect().height + 'px';
    }
  });
}

/**
 * Initializes the Masonry Gallery images.
 *
 * @param {Element} content Content element.
 */
function initFeaturedGallery(content) {
  initMasonryGallery(content);
}

/**
 * Initializes widgets and blocks.
 */
js_flextension.on('ready', (e, content) => {
  if (!content) {
    content = document;
    window.addEventListener('resize', js_flextension.debounce(() => {
      document.querySelectorAll('.flext-gallery-masonry.flext-gallery-initialized').forEach(gallery => {
        updateMasonryGallery(gallery);
      });
    }, 300));
  }
  initFeaturedGallery(content);
  initFeaturedMedia(content);
  initGalleryLightBox(content);
  setTimeout(() => {
    initFeaturedSliders(content);
  }, 500);
});
/******/ })()
;