/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/inline-animation/animation.js
const styleProps = ['height', 'width'];

/**
 * Returns a size value with unit.
 *
 * @param {string} size Raw size.
 *
 * @return {string} A size value with unit.
 */
function validateSize(size) {
  const results = typeof size !== 'undefined' ? size.toString().match(/^(\d+|\d*\.\d+)(\w+)?$/) : null;
  if (results && results.length > 1) {
    const unit = results[2] ? results[2] : 'px';
    return results[1] + unit;
  }
  return '';
}

/**
 * Animates the text.
 *
 * @param {Node}   el       Target element.
 * @param {string} text     The text to animate.
 * @param {number} duration Animation duration.
 */
function animateText(el, text, duration) {
  if (!el || !text) {
    return;
  }
  let index = 0;
  const speed = duration / text.length;
  const animate = () => {
    if (index < text.length) {
      el.innerText += text.charAt(index);
      index++;
      window.setTimeout(animate, speed * 1000);
    }
  };
  animate();
}
const Animation = {
  /**
   * Sets animation props.
   *
   * @param {Node}   el    Target element.
   * @param {Object} props Animation props.
   */
  set: (el, props) => {
    if (!el) {
      return;
    }
    Object.keys(props).forEach(key => {
      if (styleProps.includes(key)) {
        el.style[key] = validateSize(props[key]);
      } else if (key === 'text') {
        el.innerText = props[key];
      } else if (key === 'className') {
        if (props[key] && props[key].startsWith('+=')) {
          el.classList.add(props[key].replace('+='));
        } else if (props[key] && props[key].startsWith('-=')) {
          el.classList.remove(props[key].replace('-='));
        } else {
          el.setAttribute('class', props[key]);
        }
      }
    });
  },
  /**
   * Sets animation props.
   *
   * @param {Node}   el    Target element.
   * @param {Object} props Animation props.
   */
  to: (el, props) => {
    if (!el) {
      return;
    }
    props = Object.assign({
      ease: '',
      duration: 0
    }, props);
    const transitions = [];
    Object.keys(props).forEach(key => {
      if (styleProps.includes(key)) {
        transitions.push(key);
        el.style[key] = validateSize(props[key]);
      } else if (key === 'text') {
        animateText(el, props[key], props.duration);
      }
    });
    if (transitions.length > 0) {
      el.style.transitionProperty = transitions.join(', ');
      if (props.duration) {
        el.style.transitionDuration = `${props.duration}s`;
      }
      if (props.ease) {
        el.style.transitionTimingFunction = props.ease;
      }
    }
  }
};
/* harmony default export */ var animation = (Animation);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/inline-animation/timeline.js
/**
 * Internal dependencies
 */


/**
 * Allows animations to be queued up and executed in order.
 *
 * @param {Object} options Timeline options.
 */
function Timeline(options) {
  this.options = Object.assign({
    loop: false
  }, options);
  this.queue = [];
  this.currentIndex = 0;
  this.timer = null;
}

/**
 * Adds a new callback function to queue.
 *
 * @param {Function} callback A callback function to add.
 * @param {number}   delay    A delay in seconds.
 * @return {Timeline} The current Timeline instance.
 */
Timeline.prototype.add = function (callback, delay) {
  if (delay) {
    this.queue.push(() => new Promise(resolve => {
      this.timer = setTimeout(() => {
        callback();
        resolve();
      }, delay * 1000);
    }));
  } else {
    this.queue.push(() => new Promise(resolve => {
      callback();
      resolve();
    }));
  }
  return this;
};

/**
 * Clears the queue and stop the queue.
 *
 * @return {Timeline} The current Timeline instance.
 */
Timeline.prototype.stop = function () {
  if (this.timer) {
    clearTimeout(this.timer);
  }
  this.queue = [];
  return this;
};

/**
 * Executes the next queue.
 */
Timeline.prototype.play = function () {
  const promise = this.queue[this.currentIndex];
  const finished = () => {
    this.currentIndex++;
    if (this.options.loop === true && this.currentIndex > this.queue.length - 1) {
      this.currentIndex = 0;
    }
    if (this.currentIndex < this.queue.length) {
      this.play();
    } else {
      this.stop();
    }
  };
  promise().then(finished).catch(finished);
};

/**
 * Sets the animation props.
 *
 * @param {Node}   el      Target element.
 * @param {Object} options Animation options.
 * @return {Timeline} The current Timeline instance.
 */
Timeline.prototype.set = function (el, options) {
  const props = Object.assign({
    delay: 0
  }, options);
  this.add(() => {
    animation.set(el, props);
  }, props.delay);
  return this;
};

/**
 * Sets the animation props.
 *
 * @param {Node}   el      Target element.
 * @param {Object} options Animation options.
 * @return {Timeline} The current Timeline instance.
 */
Timeline.prototype.to = function (el, options) {
  const props = Object.assign({
    delay: 0,
    duration: 0
  }, options);
  this.add(() => {
    animation.to(el, props);
  }, props.delay);
  if (props.duration) {
    this.add(() => {}, props.duration);
  }
  return this;
};
/* harmony default export */ var timeline = (Timeline);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/inline-animation/text-animation.js


/**
 * Internal dependencies
 */



/**
 * Animates an inline text with interchangeable words.
 *
 * @param {Node} element The element to animate.
 */
function TextAnimation(element) {
  if (!element) {
    return false;
  }
  this.element = element;
  const children = element.querySelectorAll('*');
  this.textElement = children.length > 0 ? children[children.length - 1] : element;
  this.originalText = this.textElement.textContent;
  this.words = [];
  this.words.push(this.originalText);
  if (element.dataset.text) {
    element.dataset.text.split(',').forEach(text => this.words.push(text));
  }
  this.textElement.classList.add('flext-inline-animation');
  this.timeline = null;
}

/**
 * Plays the animation timeline.
 */
TextAnimation.prototype.play = function () {
  if (this.timeline) {
    this.timeline.play();
    this.element.classList.add('flext-is-animated');
  }
};

/**
 * Stops and kills the animation timeline.
 */
TextAnimation.prototype.stop = function () {
  if (this.timeline) {
    this.timeline.stop();
  }
  this.textElement.innerText = this.originalText;
  this.element.classList.remove('flext-is-animated');
};

/**
 * Animates the text with clipping animation.
 *
 * @param {Node} element The element to animate.
 */
function ClipAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [2.5, 2, 1.5];
  const wordDelay = delays[this.element.dataset.speed || 1];
  animation.set(this.textElement, {
    height: this.textElement.offsetHeight,
    width: this.textElement.offsetWidth
  });
  this.textElement.innerText = '';
  this.timeline = new timeline({
    loop: this.words.length > 0
  });
  this.words.forEach(text => {
    const word = document.createElement('span');
    word.classList.add('flext-inline-word', 'flext-is-hidden');
    word.innerText = text;
    this.textElement.append(word);
    this.timeline.set(word, {
      className: 'flext-inline-word flext-is-visible'
    }).to(this.textElement, {
      width: word.offsetWidth,
      duration: 0.6
    });
    if (this.words.length > 1) {
      this.timeline.to(this.textElement, {
        width: 0,
        duration: 0.6,
        delay: wordDelay
      });
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-hidden'
      });
    }
  });
  this.play();
}
ClipAnimation.prototype = new TextAnimation();
ClipAnimation.prototype.stop = function () {
  TextAnimation.prototype.stop.call(this);
  this.element.style.height = null;
  this.element.style.width = null;
};

/**
 * Fade In Up animation.
 *
 * @param {Node} element The element to animate.
 */
function FadeUpAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [3, 2.5, 2];
  const wordDelay = delays[this.element.dataset.speed || 1];
  animation.set(this.textElement, {
    height: this.textElement.offsetHeight,
    width: this.textElement.offsetWidth
  });
  this.textElement.innerText = '';
  this.timeline = new timeline({
    loop: this.words.length > 1
  });
  this.words.forEach(text => {
    const word = document.createElement('span');
    word.classList.add('flext-inline-word', 'flext-is-hidden');
    word.innerText = text;
    this.textElement.append(word);
    this.timeline.set(word, {
      className: 'flext-inline-word flext-is-visible'
    }).set(this.textElement, {
      width: word.offsetWidth
    });
    if (this.words.length > 1) {
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-hidden',
        delay: wordDelay
      });
    }
  });
  this.play();
}
FadeUpAnimation.prototype = new TextAnimation();
FadeUpAnimation.prototype.stop = function () {
  TextAnimation.prototype.stop.call(this);
  this.textElement.style.height = null;
  this.textElement.style.width = null;
};

/**
 * Splits word into array of letters.
 *
 * @param {string} word      Word to split.
 * @param {string} className CSS class name for each letter.
 * @return {Array} Array of letters.
 */
function getLetters(word, className) {
  const letters = [];
  word.split('').forEach(char => {
    const letter = document.createElement('span');
    letter.classList.add('flext-inline-letter', className);
    letter.innerHTML = char;
    letters.push(letter);
  });
  return letters;
}

/**
 * Initailizes the animation timeline.
 *
 * @param {Node} element The element to animate.
 */
function FlipAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [3, 2.5, 2];
  const wordDelay = delays[this.element.dataset.speed || 1];
  const letterDelay = 0.03;
  animation.set(this.textElement, {
    height: this.textElement.offsetHeight,
    width: this.textElement.offsetWidth
  });
  this.textElement.innerText = '';
  this.timeline = new timeline({
    loop: this.words.length > 1
  });
  this.words.forEach(text => {
    const word = document.createElement('span');
    word.classList.add('flext-inline-word', 'flext-is-hidden');
    this.textElement.append(word);
    const letters = getLetters(text, 'flext-out');
    letters.forEach(letter => {
      word.append(letter);
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-visible'
      });
      this.timeline.set(letter, {
        className: 'flext-inline-letter flext-in',
        delay: letterDelay
      });
    });
    this.timeline.set(this.textElement, {
      width: word.offsetWidth
    });
    if (this.words.length > 1) {
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-hidden',
        delay: wordDelay
      });
      letters.forEach(letter => {
        this.timeline.set(letter, {
          className: 'flext-inline-letter flext-out',
          delay: letterDelay
        });
      });
    }
  });
  this.play();
}
FlipAnimation.prototype = new TextAnimation();
FlipAnimation.prototype.stop = function () {
  TextAnimation.prototype.stop.call(this);
  this.textElement.style.height = null;
  this.textElement.style.width = null;
};

/**
 * Initailizes the animation timeline.
 *
 * @param {Node} element The element to animate.
 */
function SlideAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [3, 2.5, 2];
  const wordDelay = delays[this.element.dataset.speed || 1];
  animation.set(this.textElement, {
    height: this.textElement.offsetHeight,
    width: this.textElement.offsetWidth
  });
  this.textElement.innerText = '';
  this.timeline = new timeline({
    loop: this.words.length > 1
  });
  this.words.forEach(text => {
    const word = document.createElement('span');
    word.classList.add('flext-inline-word', 'flext-is-hidden');
    word.innerText = text;
    this.textElement.append(word);
    this.timeline.set(word, {
      className: 'flext-inline-word flext-is-visible'
    }).set(this.textElement, {
      width: word.offsetWidth
    });
    if (this.words.length > 1) {
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-hidden',
        delay: wordDelay
      });
    }
  });
  this.play();
}
SlideAnimation.prototype = new TextAnimation();
SlideAnimation.prototype.stop = function stop() {
  TextAnimation.prototype.stop.call(this);
  this.textElement.style.height = null;
  this.textElement.style.width = null;
};

/**
 * Typewriter animation.
 *
 * @param {Node} element The element to animate.
 */
function TypewriterAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [3, 2.5, 2];
  const letterDelay = 0.12;
  const wordDelay = delays[this.element.dataset.speed || 1];
  const wordWrapper = document.createElement('span');
  wordWrapper.classList.add('flext-inline-word');
  this.textElement.innerText = '';
  this.textElement.append(wordWrapper);
  const setSelection = () => {
    wordWrapper.classList.add('flext-is-selected');
  };
  const clearSelection = () => {
    wordWrapper.classList.remove('flext-is-selected');
  };
  const setBlink = () => {
    wordWrapper.classList.add('flext-cursor-blink');
  };
  const clearBlink = () => {
    wordWrapper.classList.remove('flext-cursor-blink');
  };
  this.timeline = new timeline({
    loop: this.words.length > 1
  });
  this.words.forEach(word => {
    this.timeline.to(wordWrapper, {
      text: word,
      duration: letterDelay * word.length
    }).add(setBlink);
    if (this.words.length > 1) {
      this.timeline.add(setSelection, wordDelay).set(wordWrapper, {
        text: '',
        delay: 0.8
      }).add(clearSelection).add(clearBlink, 1.5);
    }
  });
  this.play();
}
TypewriterAnimation.prototype = new TextAnimation();

/**
 * Initailizes the animation timeline.
 *
 * @param {Node} element The element to animate.
 */
function ZoomAnimation(element) {
  TextAnimation.call(this, element);
  const delays = [3, 2.5, 2];
  const wordDelay = delays[this.element.dataset.speed || 1];
  animation.set(this.textElement, {
    height: this.textElement.offsetHeight,
    width: this.textElement.offsetWidth
  });
  this.textElement.innerHTML = '';
  this.timeline = new timeline({
    loop: this.words.length > 1
  });
  this.words.forEach(text => {
    const word = document.createElement('span');
    word.classList.add('flext-inline-word', 'flext-is-hidden');
    word.innerText = text;
    this.textElement.append(word);
    this.timeline.set(word, {
      className: 'flext-inline-word flext-is-visible'
    }).set(this.textElement, {
      width: word.offsetWidth
    });
    if (this.words.length > 1) {
      this.timeline.set(word, {
        className: 'flext-inline-word flext-is-hidden',
        delay: wordDelay
      });
    }
  });
  this.play();
}
ZoomAnimation.prototype = new TextAnimation();
ZoomAnimation.prototype.stop = function stop() {
  TextAnimation.prototype.stop.call(this);
  this.textElement.style.height = null;
  this.textElement.style.width = null;
};
;// CONCATENATED MODULE: ./src/extensions/editor/js/index.js
/**
 * Flextension Extensions
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */

const {
  IntersectionObserver,
  flextension
} = window;

/**
 * Initializes the animation for the highlight markers.
 *
 * @param {Element} content The content element.
 */
function highlightAnimation(content) {
  content.querySelectorAll('.flext-has-highlight').forEach(element => {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          element.classList.add('flext-animated');
          observer.unobserve(element);
        }
      });
    });
    observer.observe(element);
  });
}

/**
 * Initializes the animation for the block.
 *
 * @param {Element} content The content element.
 */
function revealAnimation(content) {
  content.querySelectorAll('.flext-has-animation').forEach(element => {
    const once = element.classList.contains('flext-animation-once');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          element.classList.add('flext-animated');
          if (once) {
            observer.unobserve(element);
          }
        } else if (!once) {
          element.classList.remove('flext-animated');
        }
      });
    });
    observer.observe(element);
  });
}

/**
 * Initializes the inline animation for the block.
 *
 * @param {Element} content The content element.
 */
function textAnimation(content) {
  content.querySelectorAll('.flext-has-inline-animation').forEach(element => {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const animation = element.getAttribute('class').replace(/.*flext-inline-([^\s]*).*/, '$1');
          switch (animation) {
            case 'clip':
              new ClipAnimation(element);
              break;
            case 'fade-up':
              new FadeUpAnimation(element);
              break;
            case 'typewriter':
              new TypewriterAnimation(element);
              break;
            case 'slide-horizontal':
            case 'slide-vertical':
              new SlideAnimation(element);
              break;
            case 'flip-horizontal':
            case 'flip-vertical':
              new FlipAnimation(element);
              break;
            case 'zoom-in':
            case 'zoom-out':
              new ZoomAnimation(element);
              break;
          }
          observer.unobserve(element);
        }
      });
    }, {
      threshold: 0.25
    });
    observer.observe(element);
  });
}
flextension.on('ready', (context, content) => {
  if (!content) {
    content = document;
  }
  highlightAnimation(content);
  revealAnimation(content);
  textAnimation(content);
});
/******/ })()
;