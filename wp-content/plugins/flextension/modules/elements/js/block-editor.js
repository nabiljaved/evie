/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 5581:
/***/ (function() {

/**
 * WordPress dependencies
 */
const {
  addFilter
} = wp.hooks;

/**
 * Adds visibility attributes to the block.
 *
 * @param {Object} settings Block settings.
 * @param {string} name     Block name.
 * @return {Object} Filtered block settings.
 */
function addAlignment(settings, name) {
  if (name === 'core/paragraph') {
    if (!settings.supports) {
      settings.supports = {};
    }
    settings.supports.align = ['wide', 'full'];
  }
  return settings;
}
addFilter('blocks.registerBlockType', 'flextension/blocks/paragraph/add-alignment', addAlignment);

/***/ }),

/***/ 4184:
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;
	var nativeCodeString = '[native code]';

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
					classes.push(arg.toString());
					continue;
				}

				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ 7418:
/***/ (function(module) {

"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/


/* eslint-disable no-unused-vars */
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var hasOwnProperty = Object.prototype.hasOwnProperty;
var propIsEnumerable = Object.prototype.propertyIsEnumerable;

function toObject(val) {
	if (val === null || val === undefined) {
		throw new TypeError('Object.assign cannot be called with null or undefined');
	}

	return Object(val);
}

function shouldUseNative() {
	try {
		if (!Object.assign) {
			return false;
		}

		// Detect buggy property enumeration order in older V8 versions.

		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
		test1[5] = 'de';
		if (Object.getOwnPropertyNames(test1)[0] === '5') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test2 = {};
		for (var i = 0; i < 10; i++) {
			test2['_' + String.fromCharCode(i)] = i;
		}
		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
			return test2[n];
		});
		if (order2.join('') !== '0123456789') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test3 = {};
		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
			test3[letter] = letter;
		});
		if (Object.keys(Object.assign({}, test3)).join('') !==
				'abcdefghijklmnopqrst') {
			return false;
		}

		return true;
	} catch (err) {
		// We don't expect any of the above to throw, but better to be safe.
		return false;
	}
}

module.exports = shouldUseNative() ? Object.assign : function (target, source) {
	var from;
	var to = toObject(target);
	var symbols;

	for (var s = 1; s < arguments.length; s++) {
		from = Object(arguments[s]);

		for (var key in from) {
			if (hasOwnProperty.call(from, key)) {
				to[key] = from[key];
			}
		}

		if (getOwnPropertySymbols) {
			symbols = getOwnPropertySymbols(from);
			for (var i = 0; i < symbols.length; i++) {
				if (propIsEnumerable.call(from, symbols[i])) {
					to[symbols[i]] = from[symbols[i]];
				}
			}
		}
	}

	return to;
};


/***/ }),

/***/ 2408:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";
var __webpack_unused_export__;
/** @license React v17.0.2
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var l=__webpack_require__(7418),n=60103,p=60106;exports.Fragment=60107;__webpack_unused_export__=60108;__webpack_unused_export__=60114;var q=60109,r=60110,t=60112;__webpack_unused_export__=60113;var u=60115,v=60116;
if("function"===typeof Symbol&&Symbol.for){var w=Symbol.for;n=w("react.element");p=w("react.portal");exports.Fragment=w("react.fragment");__webpack_unused_export__=w("react.strict_mode");__webpack_unused_export__=w("react.profiler");q=w("react.provider");r=w("react.context");t=w("react.forward_ref");__webpack_unused_export__=w("react.suspense");u=w("react.memo");v=w("react.lazy")}var x="function"===typeof Symbol&&Symbol.iterator;
function y(a){if(null===a||"object"!==typeof a)return null;a=x&&a[x]||a["@@iterator"];return"function"===typeof a?a:null}function z(a){for(var b="https://reactjs.org/docs/error-decoder.html?invariant="+a,c=1;c<arguments.length;c++)b+="&args[]="+encodeURIComponent(arguments[c]);return"Minified React error #"+a+"; visit "+b+" for the full message or use the non-minified dev environment for full errors and additional helpful warnings."}
var A={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}},B={};function C(a,b,c){this.props=a;this.context=b;this.refs=B;this.updater=c||A}C.prototype.isReactComponent={};C.prototype.setState=function(a,b){if("object"!==typeof a&&"function"!==typeof a&&null!=a)throw Error(z(85));this.updater.enqueueSetState(this,a,b,"setState")};C.prototype.forceUpdate=function(a){this.updater.enqueueForceUpdate(this,a,"forceUpdate")};
function D(){}D.prototype=C.prototype;function E(a,b,c){this.props=a;this.context=b;this.refs=B;this.updater=c||A}var F=E.prototype=new D;F.constructor=E;l(F,C.prototype);F.isPureReactComponent=!0;var G={current:null},H=Object.prototype.hasOwnProperty,I={key:!0,ref:!0,__self:!0,__source:!0};
function J(a,b,c){var e,d={},k=null,h=null;if(null!=b)for(e in void 0!==b.ref&&(h=b.ref),void 0!==b.key&&(k=""+b.key),b)H.call(b,e)&&!I.hasOwnProperty(e)&&(d[e]=b[e]);var g=arguments.length-2;if(1===g)d.children=c;else if(1<g){for(var f=Array(g),m=0;m<g;m++)f[m]=arguments[m+2];d.children=f}if(a&&a.defaultProps)for(e in g=a.defaultProps,g)void 0===d[e]&&(d[e]=g[e]);return{$$typeof:n,type:a,key:k,ref:h,props:d,_owner:G.current}}
function K(a,b){return{$$typeof:n,type:a.type,key:b,ref:a.ref,props:a.props,_owner:a._owner}}function L(a){return"object"===typeof a&&null!==a&&a.$$typeof===n}function escape(a){var b={"=":"=0",":":"=2"};return"$"+a.replace(/[=:]/g,function(a){return b[a]})}var M=/\/+/g;function N(a,b){return"object"===typeof a&&null!==a&&null!=a.key?escape(""+a.key):b.toString(36)}
function O(a,b,c,e,d){var k=typeof a;if("undefined"===k||"boolean"===k)a=null;var h=!1;if(null===a)h=!0;else switch(k){case "string":case "number":h=!0;break;case "object":switch(a.$$typeof){case n:case p:h=!0}}if(h)return h=a,d=d(h),a=""===e?"."+N(h,0):e,Array.isArray(d)?(c="",null!=a&&(c=a.replace(M,"$&/")+"/"),O(d,b,c,"",function(a){return a})):null!=d&&(L(d)&&(d=K(d,c+(!d.key||h&&h.key===d.key?"":(""+d.key).replace(M,"$&/")+"/")+a)),b.push(d)),1;h=0;e=""===e?".":e+":";if(Array.isArray(a))for(var g=
0;g<a.length;g++){k=a[g];var f=e+N(k,g);h+=O(k,b,c,f,d)}else if(f=y(a),"function"===typeof f)for(a=f.call(a),g=0;!(k=a.next()).done;)k=k.value,f=e+N(k,g++),h+=O(k,b,c,f,d);else if("object"===k)throw b=""+a,Error(z(31,"[object Object]"===b?"object with keys {"+Object.keys(a).join(", ")+"}":b));return h}function P(a,b,c){if(null==a)return a;var e=[],d=0;O(a,e,"","",function(a){return b.call(c,a,d++)});return e}
function Q(a){if(-1===a._status){var b=a._result;b=b();a._status=0;a._result=b;b.then(function(b){0===a._status&&(b=b.default,a._status=1,a._result=b)},function(b){0===a._status&&(a._status=2,a._result=b)})}if(1===a._status)return a._result;throw a._result;}var R={current:null};function S(){var a=R.current;if(null===a)throw Error(z(321));return a}var T={ReactCurrentDispatcher:R,ReactCurrentBatchConfig:{transition:0},ReactCurrentOwner:G,IsSomeRendererActing:{current:!1},assign:l};
__webpack_unused_export__={map:P,forEach:function(a,b,c){P(a,function(){b.apply(this,arguments)},c)},count:function(a){var b=0;P(a,function(){b++});return b},toArray:function(a){return P(a,function(a){return a})||[]},only:function(a){if(!L(a))throw Error(z(143));return a}};__webpack_unused_export__=C;__webpack_unused_export__=E;__webpack_unused_export__=T;
__webpack_unused_export__=function(a,b,c){if(null===a||void 0===a)throw Error(z(267,a));var e=l({},a.props),d=a.key,k=a.ref,h=a._owner;if(null!=b){void 0!==b.ref&&(k=b.ref,h=G.current);void 0!==b.key&&(d=""+b.key);if(a.type&&a.type.defaultProps)var g=a.type.defaultProps;for(f in b)H.call(b,f)&&!I.hasOwnProperty(f)&&(e[f]=void 0===b[f]&&void 0!==g?g[f]:b[f])}var f=arguments.length-2;if(1===f)e.children=c;else if(1<f){g=Array(f);for(var m=0;m<f;m++)g[m]=arguments[m+2];e.children=g}return{$$typeof:n,type:a.type,
key:d,ref:k,props:e,_owner:h}};__webpack_unused_export__=function(a,b){void 0===b&&(b=null);a={$$typeof:r,_calculateChangedBits:b,_currentValue:a,_currentValue2:a,_threadCount:0,Provider:null,Consumer:null};a.Provider={$$typeof:q,_context:a};return a.Consumer=a};exports.createElement=J;__webpack_unused_export__=function(a){var b=J.bind(null,a);b.type=a;return b};__webpack_unused_export__=function(){return{current:null}};__webpack_unused_export__=function(a){return{$$typeof:t,render:a}};__webpack_unused_export__=L;
__webpack_unused_export__=function(a){return{$$typeof:v,_payload:{_status:-1,_result:a},_init:Q}};__webpack_unused_export__=function(a,b){return{$$typeof:u,type:a,compare:void 0===b?null:b}};__webpack_unused_export__=function(a,b){return S().useCallback(a,b)};__webpack_unused_export__=function(a,b){return S().useContext(a,b)};__webpack_unused_export__=function(){};__webpack_unused_export__=function(a,b){return S().useEffect(a,b)};__webpack_unused_export__=function(a,b,c){return S().useImperativeHandle(a,b,c)};
__webpack_unused_export__=function(a,b){return S().useLayoutEffect(a,b)};__webpack_unused_export__=function(a,b){return S().useMemo(a,b)};__webpack_unused_export__=function(a,b,c){return S().useReducer(a,b,c)};__webpack_unused_export__=function(a){return S().useRef(a)};__webpack_unused_export__=function(a){return S().useState(a)};__webpack_unused_export__="17.0.2";


/***/ }),

/***/ 7294:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


if (true) {
  module.exports = __webpack_require__(2408);
} else {}


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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";

// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(7294);
;// CONCATENATED MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(4184);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/columns/index.js


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __
} = wp.i18n;
const {
  addFilter
} = wp.hooks;
const {
  Component
} = wp.element;
const {
  PanelBody,
  ToggleControl
} = wp.components;
const {
  createHigherOrderComponent
} = wp.compose;
const {
  InspectorControls
} = wp.blockEditor;
const {
  hasBlockSupport
} = wp.blocks;

/**
 * Adds spacing attributes to the block.
 *
 * @param {Object} settings Block settings.
 * @param {string} name     Block name.
 * @return {Object} Filtered block settings.
 */
function addAttributes(settings, name) {
  if (name === 'core/columns') {
    if (!settings.attributes) {
      settings.attributes = {};
    }
    if (!settings.attributes.hasOwnProperty('isStackedOnMobile')) {
      settings.attributes.isStackedOnMobile = {
        type: 'boolean',
        default: true
      };
    }
  }
  return settings;
}
addFilter('blocks.registerBlockType', 'flextension/blocks/columns/add-attributes', addAttributes);

/**
 * Overrides the default edit UI to include a new block inspector control for
 * assigning the custom spacing settings if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const addControls = createHigherOrderComponent(BlockEdit => {
  return props => {
    if (props.name === 'core/columns' && !hasBlockSupport(props.name, 'spacing', false)) {
      const {
        attributes,
        setAttributes
      } = props;
      const {
        isStackedOnMobile
      } = attributes;
      return (0,react.createElement)(react.Fragment, null, (0,react.createElement)(InspectorControls, null, (0,react.createElement)(PanelBody, null, (0,react.createElement)(ToggleControl, {
        label: __('Stack on mobile', 'flextension'),
        checked: isStackedOnMobile,
        onChange: () => setAttributes({
          isStackedOnMobile: !isStackedOnMobile
        })
      }))), (0,react.createElement)(BlockEdit, props));
    }
    return (0,react.createElement)(BlockEdit, props);
  };
}, 'addControls');
addFilter('editor.BlockEdit', 'flextension/blocks/columns/add-controls', addControls);

/**
 * Overrides the default edit UI to include a new block edit classes for
 * assigning the animation if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const addEditClasses = createHigherOrderComponent(BlockListBlock => {
  return props => {
    if (props.name === 'core/columns') {
      const {
        isStackedOnMobile
      } = props.attributes;
      return (0,react.createElement)(BlockListBlock, _extends({}, props, {
        className: classnames_default()(props.className, {
          [`is-not-stacked-on-mobile`]: !isStackedOnMobile
        })
      }));
    }
    return (0,react.createElement)(BlockListBlock, props);
  };
}, 'addEditClasses');
addFilter('editor.BlockListBlock', 'flextension/blocks/columns/add-edit-classes', addEditClasses);

/**
 * Adds animation class names to the block extra props.
 *
 * @param {Object} extraProps Block props.
 * @param {Object} blockType  Blocks object.
 * @param {Object} attributes Blocks attributes.
 * @return {Object} The block extra props.
 */
function addClasses(extraProps, blockType, attributes) {
  if (blockType.name === 'core/columns') {
    const {
      isStackedOnMobile = true
    } = attributes;
    extraProps.className = classnames_default()(extraProps.className, {
      [`is-not-stacked-on-mobile`]: !isStackedOnMobile
    });
  }
  return extraProps;
}
addFilter('blocks.getSaveContent.extraProps', 'flextension/blocks/columns/add-classes', addClasses);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/counter/block.json
var block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"flextension/counter","title":"Counter","description":"An animated numbered counter.","category":"flextension","textdomain":"flextension","keywords":["count up","animated number"],"supports":{"anchor":true,"color":{"background":false},"typography":{"fontSize":true},"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"textAlign":{"type":"string"},"start":{"type":"number","default":0},"end":{"type":"number","default":100},"prefix":{"type":"string","default":""},"suffix":{"type":"string","default":""},"duration":{"type":"number","default":1000},"delay":{"type":"number","default":0}},"editorStyle":"flextension-block-editor","editorScript":"flextension-block-editor","style":"flextension-elements","viewScript":"flextension-elements"}');
;// CONCATENATED MODULE: ./src/modules/elements/blocks/counter/icon.js

const icon = (0,react.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "24",
  height: "24",
  viewBox: "0 0 24 24"
}, (0,react.createElement)("g", null, (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M23,6v12H1V6H23 M23,5H0.9C0.4,5,0,5.5,0,6v12.1c0,0.5,0.4,1,1,1H23c0.5,0,1-0.4,1-1V6C24,5.5,23.5,5,23,5L23,5z"
})), (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M16,6.3"
})), (0,react.createElement)("g", null, (0,react.createElement)("circle", {
  cx: "8",
  cy: "6.5",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "7.5",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "8.4",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "9.3",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "10.2",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "11.1",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "12",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "12.9",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "13.8",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "14.8",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "15.7",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "16.6",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "8",
  cy: "17.5",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "6.5",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "7.5",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "8.4",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "9.3",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "10.2",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "11.1",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "12",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "12.9",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "13.8",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "14.8",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "15.7",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "16.6",
  r: "0.3"
}), (0,react.createElement)("circle", {
  cx: "16",
  cy: "17.5",
  r: "0.3"
})), (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M5.8,12.2c0,1.4-0.5,2.8-2,2.8c-1.5,0-2-1.3-2-2.7c0-1.4,0.5-2.7,2-2.7C5.3,9.5,5.8,10.7,5.8,12.2z M3,12.2 c0,1,0.1,1.8,0.9,1.8c0.7,0,0.8-0.8,0.8-1.8c0-1-0.1-1.8-0.8-1.8C3.1,10.4,3,11.1,3,12.2z"
})), (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M11.8,14.9V11c-0.2,0.1-0.7,0.2-1,0.2v-0.9c0.5-0.1,1-0.4,1.3-0.7H13v5.3H11.8z"
})), (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M17.7,6h1.1c0.1-0.5,0.3-0.8,0.8-0.8c0.4,0,0.7,0.3,0.7,0.7c0,0.5-0.2,0.6-1,1.2c-1.1,0.8-1.6,1.6-1.6,2.5v0.1h3.7l0.1-1 h-2.5c0.1-0.2,0.3-0.5,1-1c1-0.7,1.3-1.1,1.3-1.8c0-0.3-0.1-0.6-0.3-0.9H18C17.8,5.3,17.7,5.6,17.7,6z"
})), (0,react.createElement)("g", null, (0,react.createElement)("path", {
  d: "M20.6,16.2L20.6,16.2c0.3-0.2,0.7-0.5,0.7-1.1c0-0.7-0.5-1.4-1.8-1.4c-1.3,0-1.8,0.8-1.8,1.4h1.1c0.1-0.3,0.2-0.6,0.7-0.6 c0.5,0,0.7,0.2,0.7,0.6c0,0.4-0.2,0.6-0.7,0.6H19v0.9h0.5c0.6,0,0.9,0.3,0.9,0.8c0,0.5-0.3,0.8-0.8,0.8c-0.5,0-0.8-0.3-0.9-0.7 h-1.1c0,0.6,0.4,1.3,1.3,1.5h1.2c1-0.2,1.3-0.9,1.3-1.6C21.5,16.6,21,16.3,20.6,16.2z"
}))));
/* harmony default export */ var counter_icon = (icon);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/counter/edit.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __: edit_
} = wp.i18n;
const {
  useEffect,
  useState
} = wp.element;
const {
  AlignmentControl,
  BlockControls,
  InspectorControls: edit_InspectorControls,
  useBlockProps
} = wp.blockEditor;
const {
  PanelBody: edit_PanelBody,
  RangeControl,
  TextControl
} = wp.components;
function CounterEdit(_ref) {
  let {
    attributes,
    setAttributes
  } = _ref;
  const {
    textAlign,
    start,
    end,
    prefix,
    suffix,
    duration,
    delay
  } = attributes;
  const [count, setCount] = useState(start.toString());
  let digits = '';
  const matches = /\d+[\.\,](\d+)?/g.exec(end.toString());
  if (matches && matches.length > 1) {
    digits = matches[1];
  }
  let startTimestamp = null;
  let animationRequest = null;
  const animate = timestamp => {
    if (!startTimestamp) {
      startTimestamp = timestamp;
    }
    const speed = parseFloat(duration) || 0.1;
    const progress = Math.min((timestamp - startTimestamp) / speed, 1);
    const number = progress * (parseFloat(end) - parseFloat(start)) + parseFloat(start);
    const formatOptions = {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    };
    if (digits && digits.length > 0) {
      formatOptions.minimumFractionDigits = digits.length;
      formatOptions.maximumFractionDigits = digits.length;
    }
    setCount(number.toLocaleString(undefined, formatOptions));
    if (progress < 1) {
      animationRequest = window.requestAnimationFrame(animate);
    }
  };
  useEffect(() => {
    if (animationRequest) {
      window.cancelAnimationFrame(animationRequest);
    }
    setTimeout(() => {
      animationRequest = window.requestAnimationFrame(animate);
    }, delay);
  }, [start, end, duration]);
  const blockProps = useBlockProps({
    className: classnames_default()({
      [`has-text-align-${textAlign}`]: textAlign
    })
  });
  return (0,react.createElement)(react.Fragment, null, (0,react.createElement)(BlockControls, {
    group: "block"
  }, (0,react.createElement)(AlignmentControl, {
    value: textAlign,
    onChange: value => setAttributes({
      textAlign: value
    })
  })), (0,react.createElement)(edit_InspectorControls, null, (0,react.createElement)(edit_PanelBody, null, (0,react.createElement)(TextControl, {
    label: edit_('Start', 'flextension'),
    type: "number",
    size: "small",
    value: start,
    onChange: value => setAttributes({
      start: parseInt(value, 10)
    })
  }), (0,react.createElement)(TextControl, {
    label: edit_('End', 'flextension'),
    type: "number",
    size: "small",
    value: end,
    onChange: value => setAttributes({
      end: parseInt(value, 10)
    })
  }), (0,react.createElement)(TextControl, {
    label: edit_('Prefix', 'flextension'),
    value: prefix,
    onChange: value => setAttributes({
      prefix: value
    })
  }), (0,react.createElement)(TextControl, {
    label: edit_('Suffix', 'flextension'),
    value: suffix,
    onChange: value => setAttributes({
      suffix: value
    })
  }), (0,react.createElement)(RangeControl, {
    label: edit_('Duration', 'flextension'),
    help: edit_('Animation duration in seconds.', 'flextension'),
    value: duration,
    onChange: value => setAttributes({
      duration: parseInt(value, 10)
    }),
    marks: [{
      value: 0,
      label: '0'
    }, {
      value: 250,
      label: '0.25'
    }, {
      value: 500,
      label: '0.5'
    }, {
      value: 750,
      label: '0.75'
    }, {
      value: 1000,
      label: '1'
    }, {
      value: 1250,
      label: '1.25'
    }, {
      value: 1500,
      label: '1.5'
    }, {
      value: 1750,
      label: '1.75'
    }, {
      value: 2000,
      label: '2'
    }],
    withInputField: false,
    min: 0,
    max: 2000,
    step: 250,
    required: true
  }), (0,react.createElement)(RangeControl, {
    label: edit_('Delay', 'flextension'),
    help: edit_('Animation delay in seconds.', 'flextension'),
    value: delay,
    onChange: value => setAttributes({
      delay: parseInt(value, 10)
    }),
    marks: [{
      value: 0,
      label: '0'
    }, {
      value: 250,
      label: '0.25'
    }, {
      value: 500,
      label: '0.5'
    }, {
      value: 750,
      label: '0.75'
    }, {
      value: 1000,
      label: '1'
    }, {
      value: 1250,
      label: '1.25'
    }, {
      value: 1500,
      label: '1.5'
    }, {
      value: 1750,
      label: '1.75'
    }, {
      value: 2000,
      label: '2'
    }],
    withInputField: false,
    min: 0,
    max: 2000,
    step: 250,
    required: true
  }))), (0,react.createElement)("div", blockProps, prefix && (0,react.createElement)("span", {
    className: "flext-block-counter-prefix"
  }, prefix), (0,react.createElement)("span", {
    className: "flext-block-counter-number"
  }, count), suffix && (0,react.createElement)("span", {
    className: "flext-block-counter-suffix"
  }, suffix)));
}
/* harmony default export */ var edit = (CounterEdit);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/counter/save.js

/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */
const {
  useBlockProps: save_useBlockProps
} = wp.blockEditor;
function save(_ref) {
  let {
    attributes
  } = _ref;
  const {
    textAlign,
    start,
    end,
    prefix,
    suffix,
    duration,
    delay
  } = attributes;
  const className = classnames_default()({
    [`has-text-align-${textAlign}`]: textAlign
  });
  return (0,react.createElement)("div", save_useBlockProps.save({
    className
  }), prefix && (0,react.createElement)("span", {
    className: "flext-block-counter-prefix"
  }, prefix), (0,react.createElement)("span", {
    className: "flext-block-counter-number",
    "data-count-start": start,
    "data-count-end": end,
    "data-count-duration": duration,
    "data-count-delay": delay
  }, start), suffix && (0,react.createElement)("span", {
    className: "flext-block-counter-suffix"
  }, suffix));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/counter/index.js
/**
 * Internal dependencies
 */





/**
 * WordPress dependencies
 */
const {
  registerBlockType
} = wp.blocks;
const {
  addFilter: counter_addFilter
} = wp.hooks;
const {
  name: counter_name
} = block_namespaceObject;
const settings = {
  icon: counter_icon,
  example: {},
  edit: edit,
  save: save
};

/**
 * Registers a block from metadata.
 *
 * @param {string} name     Block name.
 * @param {Object} settings Block settings.
 * @return {?WPBlock} The block, if it has been successfully registered;
 *                    otherwise `undefined`.
 */
registerBlockType({
  name: counter_name,
  ...block_namespaceObject
}, settings);

/**
 * Sets a new class name for the block.
 *
 * @param {string} className Block class name.
 * @param {string} blockName Block name.
 * @return {string} Block class name.
 */
function setClassName(className, blockName) {
  return blockName === counter_name ? 'flext-block-counter' : className;
}
counter_addFilter('blocks.getBlockDefaultClassName', 'flextension/blocks/counter/set-class-name', setClassName);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/list/index.js


/**
 * WordPress dependencies
 */
const {
  __: list_
} = wp.i18n;
const {
  Component: list_Component
} = wp.element;
const {
  registerBlockStyle
} = wp.blocks;
const {
  createHigherOrderComponent: list_createHigherOrderComponent
} = wp.compose;
const {
  addFilter: list_addFilter
} = wp.hooks;

/**
 * Overrides the default edit UI to include a new block edit classes for
 * assigning the animation if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const addListCounter = list_createHigherOrderComponent(BlockListBlock => {
  return props => {
    if (props.name === 'core/list') {
      const {
        start,
        ordered,
        className
      } = props.attributes;
      if (ordered && start && className && className.includes('is-style-flext-list-circle')) {
        const wrapperProps = props.wrapperProps ? props.wrapperProps : {};
        wrapperProps.style = {
          '--flext-item-index': start.toString()
        };
        return (0,react.createElement)(BlockListBlock, _extends({}, props, {
          wrapperProps: wrapperProps
        }));
      }
    }
    return (0,react.createElement)(BlockListBlock, props);
  };
}, 'addEditCounter');
list_addFilter('editor.BlockListBlock', 'flextension/blocks/list/add-list-counter', addListCounter);

/**
 * Overrides props assigned to save component to inject custom styles.
 * This is only applied if the block's save result is an
 * element and not a markup string.
 *
 * @param {Object} extraProps Additional props applied to save element.
 * @param {Object} blockType  Block type.
 * @param {Object} attributes Current block attributes.
 * @return {Object} Filtered props applied to save element.
 */
function addCounter(extraProps, blockType, attributes) {
  if (blockType.name === 'core/list') {
    const {
      start,
      ordered,
      className
    } = attributes;
    if (ordered && start && className && className.includes('is-style-flext-list-circle')) {
      extraProps.style = Object.assign({
        '--flext-item-index': start.toString()
      }, extraProps.style);
    }
  }
  return extraProps;
}
list_addFilter('blocks.getSaveContent.extraProps', 'flextension/blocks/list/add-couter', addCounter);
wp.domReady(() => {
  /**
   * Registers new styles for the List block.
   */
  registerBlockStyle('core/list', {
    name: 'flext-list-circle',
    label: list_('Circle', 'flextension')
  });
});
// EXTERNAL MODULE: ./src/modules/elements/blocks/paragraph/index.js
var paragraph = __webpack_require__(5581);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/block.json
var post_carousel_block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"flextension/post-carousel","title":"Post Carousel","description":"Displays your posts in a carousel.","category":"flextension","textdomain":"flextension","keywords":["carousel","slider","post","blog","portfolio","project"],"supports":{"align":["wide","full"],"html":false,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"blockId":{"type":"string","default":""},"className":{"type":"string"},"title":{"type":"string"},"columns":{"type":"number","default":5},"displayNumber":{"type":"boolean","default":false},"displayTitle":{"type":"boolean","default":true},"displayCategory":{"type":"boolean","default":true},"displayAuthor":{"type":"boolean","default":true},"displayDate":{"type":"boolean","default":true},"displayButtons":{"type":"boolean","default":true},"navigation":{"type":"boolean","default":false},"pagination":{"type":"boolean","default":false},"link":{"type":"string","enum":["","archive","more","custom"],"default":""},"linkText":{"type":"string","default":"See all"},"linkURL":{"type":"string","default":""},"query":{"type":"object","default":{"postType":"post","posts":[],"taxonomy":"","terms":[],"author":"","authors":[],"timeRange":"","orderBy":"date","order":"DESC","numberOfItems":10}}},"editorStyle":"flextension-block-editor","editorScript":"flextension-block-editor","style":"flextension-blocks","viewScript":"flextension-blocks"}');
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/edit.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __: post_carousel_edit_
} = wp.i18n;
const {
  Fragment,
  useEffect: edit_useEffect
} = wp.element;
const {
  PanelBody: post_carousel_edit_PanelBody,
  RangeControl: edit_RangeControl,
  SelectControl,
  TextControl: edit_TextControl,
  ToggleControl: edit_ToggleControl
} = wp.components;
const {
  InspectorControls: post_carousel_edit_InspectorControls,
  useBlockProps: edit_useBlockProps
} = wp.blockEditor;
const {
  flextension
} = window;
const {
  QueryControls,
  ServerSideRender
} = flextension.editor.components;
function PostCarouselEdit(props) {
  const {
    attributes,
    setAttributes,
    clientId
  } = props;
  const {
    blockId,
    title,
    columns,
    displayNumber,
    displayTitle,
    displayCategory,
    displayAuthor,
    displayDate,
    displayButtons,
    navigation,
    pagination,
    link,
    linkText,
    linkURL,
    query
  } = attributes;
  edit_useEffect(() => {
    setAttributes({
      blockId: clientId
    });
  }, [blockId, clientId]);
  const onChange = content => {
    if (!content) {
      return;
    }
    const carousel = content.querySelector('.flext-post-carousel');
    if (carousel !== null) {
      const getColumns = max => {
        return Math.min(parseInt(columns, 10) || max, max);
      };
      new flextension.carousel(carousel, {
        simulateTouch: false,
        spaceBetween: parseInt(carousel.dataset.spaceBetween, 10) || 0,
        breakpoints: {
          768: {
            slidesPerView: getColumns(2),
            slidesPerGroup: getColumns(2)
          },
          1024: {
            slidesPerView: getColumns(3),
            slidesPerGroup: getColumns(3)
          },
          1200: {
            slidesPerView: getColumns(4),
            slidesPerGroup: getColumns(4)
          },
          1600: {
            slidesPerView: getColumns(5),
            slidesPerGroup: getColumns(5)
          },
          1920: {
            slidesPerView: getColumns(6),
            slidesPerGroup: getColumns(6)
          }
        }
      });
    }
  };
  const blockProps = edit_useBlockProps({
    className: classnames_default()({
      'has-post-number': displayNumber
    })
  });
  return (0,react.createElement)(Fragment, null, (0,react.createElement)(post_carousel_edit_InspectorControls, null, (0,react.createElement)(post_carousel_edit_PanelBody, null, (0,react.createElement)(edit_TextControl, {
    label: post_carousel_edit_('Title', 'flextension'),
    value: title,
    onChange: value => setAttributes({
      title: value
    })
  }), (0,react.createElement)(edit_RangeControl, {
    label: post_carousel_edit_('Columns', 'flextension'),
    value: columns,
    onChange: value => {
      setAttributes({
        columns: value
      });
    },
    min: 1,
    max: 5,
    required: true
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Navigation', 'flextension'),
    checked: navigation,
    onChange: value => setAttributes({
      navigation: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Pagination', 'flextension'),
    checked: pagination,
    onChange: value => setAttributes({
      pagination: value
    })
  })), (0,react.createElement)(post_carousel_edit_PanelBody, {
    title: post_carousel_edit_('Post settings', 'flextension')
  }, (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display title', 'flextension'),
    checked: displayTitle,
    onChange: value => setAttributes({
      displayTitle: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display category', 'flextension'),
    checked: displayCategory,
    onChange: value => setAttributes({
      displayCategory: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display author', 'flextension'),
    checked: displayAuthor,
    onChange: value => setAttributes({
      displayAuthor: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display date', 'flextension'),
    checked: displayDate,
    onChange: value => setAttributes({
      displayDate: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display Likes, Share and Comments', 'flextension'),
    checked: displayButtons,
    onChange: value => setAttributes({
      displayButtons: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: post_carousel_edit_('Display post number', 'flextension'),
    checked: displayNumber,
    onChange: value => setAttributes({
      displayNumber: value
    })
  })), (0,react.createElement)(post_carousel_edit_PanelBody, {
    title: post_carousel_edit_('Sorting and filtering', 'flextension')
  }, (0,react.createElement)(QueryControls, {
    value: query,
    onChange: value => setAttributes({
      query: value
    })
  })), (0,react.createElement)(post_carousel_edit_PanelBody, {
    title: post_carousel_edit_('Link settings', 'flextension')
  }, (0,react.createElement)(SelectControl, {
    label: post_carousel_edit_('Link to', 'flextension'),
    options: [{
      value: '',
      label: post_carousel_edit_('None', 'flextension')
    }, {
      value: 'archive',
      label: post_carousel_edit_('Archive page (all posts)', 'flextension')
    }, {
      value: 'more',
      label: post_carousel_edit_('More similar posts', 'flextension')
    }, {
      value: 'custom',
      label: post_carousel_edit_('Custom', 'flextension')
    }],
    value: link,
    onChange: value => setAttributes({
      link: value
    })
  }), link && (0,react.createElement)(edit_TextControl, {
    label: post_carousel_edit_('Link text', 'flextension'),
    value: linkText,
    onChange: value => setAttributes({
      linkText: value
    })
  }), link === 'custom' && (0,react.createElement)(edit_TextControl, {
    label: post_carousel_edit_('URL', 'flextension'),
    type: "url",
    value: linkURL,
    onChange: value => setAttributes({
      linkURL: value
    })
  }))), (0,react.createElement)("div", blockProps, (0,react.createElement)(ServerSideRender, {
    block: "flextension/post-carousel",
    attributes: attributes,
    clientId: clientId,
    onChange: onChange
  })));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/index.js

/**
 * Internal dependencies
 */



/**
 * WordPress dependencies
 */
const {
  registerBlockType: post_carousel_registerBlockType
} = wp.blocks;
const {
  addFilter: post_carousel_addFilter
} = wp.hooks;
const {
  name: post_carousel_name
} = post_carousel_block_namespaceObject;
const post_carousel_settings = {
  icon: (0,react.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24"
  }, (0,react.createElement)("path", {
    d: "M15.5,18.5h-7C8.2,18.5,8,18.3,8,18l0,0c0-0.3,0.2-0.5,0.5-0.5h6.9c0.3,0,0.5,0.2,0.5,0.5l0,0C16,18.3,15.8,18.5,15.5,18.5z M6,6.5V15H1V6.5H6 M7,5.5H0V16h7V5.5L7,5.5z M14.5,6.5V15h-5V6.5H14.5 M15.5,5.5h-7V16h7V5.5L15.5,5.5z M23,6.5V15h-5V6.5H23 M24,5.5h-7V16h7V5.5L24,5.5z"
  })),
  edit: PostCarouselEdit
};

/**
 * Registers a block from metadata.
 *
 * @param {string} name     Block name.
 * @param {Object} settings Block settings.
 * @return {?WPBlock} The block, if it has been successfully registered;
 *                    otherwise `undefined`.
 */
post_carousel_registerBlockType({
  name: post_carousel_name,
  ...post_carousel_block_namespaceObject
}, post_carousel_settings);

/**
 * Sets a new class name for the block.
 *
 * @param {string} className Block class name.
 * @param {string} blockName Block name.
 * @return {string} Block class name.
 */
function post_carousel_setClassName(className, blockName) {
  return blockName === post_carousel_name ? 'flext-block-post-carousel' : className;
}
post_carousel_addFilter('blocks.getBlockDefaultClassName', 'flextension/blocks/post-carousel/set-class-name', post_carousel_setClassName);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/block.json
var section_block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"flextension/section","title":"Section","description":"A section block.","category":"flextension","textdomain":"flextension","keywords":["row","container"],"supports":{"anchor":true,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"align":{"type":"string"},"url":{"type":"string"},"id":{"type":"number"},"hasParallax":{"type":"boolean","default":false},"isRepeated":{"type":"boolean","default":false},"dimRatio":{"type":"number","default":50},"overlayColor":{"type":"string"},"customOverlayColor":{"type":"string"},"scheme":{"type":"string"},"backgroundType":{"type":"string","default":"image"},"focalPoint":{"type":"object"},"minHeight":{"type":"number"},"minHeightUnit":{"type":"string"},"gradient":{"type":"string"},"customGradient":{"type":"string"}},"editorStyle":"flextension-block-editor","editorScript":"flextension-block-editor","style":"flextension-elements"}');
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/icon.js

const icon_icon = (0,react.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "24",
  height: "24",
  viewBox: "0 0 24 24"
}, (0,react.createElement)("path", {
  d: "M4,4v7h16V4H4z M18.5,9.5h-13v-4h13V9.5z M4,19.9h16v-7H4V19.9z M5.5,14.4h13v4h-13V14.4z"
}));
/* harmony default export */ var section_icon = (icon_icon);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/shared.js
const IMAGE_BACKGROUND_TYPE = 'image';
const VIDEO_BACKGROUND_TYPE = 'video';
const SECTION_MIN_HEIGHT = 50;
function backgroundImageStyles(url) {
  return url ? {
    backgroundImage: `url(${url})`
  } : {};
}
function dimRatioToClass(ratio) {
  return ratio === 0 || ratio === 50 || !ratio ? null : 'has-background-dim-' + 10 * Math.round(ratio / 10);
}
function mediaPosition(_ref) {
  let {
    x,
    y
  } = _ref;
  return `${Math.round(x * 100)}% ${Math.round(y * 100)}%`;
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/edit.js


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */

const {
  flextension: edit_flextension
} = window;
const {
  SchemeDropdownControl
} = edit_flextension.editor.components;

/**
 * WordPress dependencies
 */
const {
  Fragment: edit_Fragment,
  useMemo,
  useRef,
  useState: edit_useState
} = wp.element;
const {
  BaseControl,
  Button,
  FocalPointPicker,
  PanelBody: section_edit_PanelBody,
  PanelRow,
  RangeControl: section_edit_RangeControl,
  ResizableBox,
  Spinner,
  ToggleControl: section_edit_ToggleControl,
  __experimentalUseCustomUnits: useCustomUnits,
  __experimentalUnitControl: UnitControl,
  __experimentalParseQuantityAndUnitFromRawValue: parseQuantityAndUnitFromRawValue,
  withNotices
} = wp.components;
const {
  compose,
  withInstanceId,
  useInstanceId
} = wp.compose;
const {
  BlockControls: edit_BlockControls,
  InnerBlocks,
  InspectorControls: section_edit_InspectorControls,
  MediaReplaceFlow,
  withColors,
  useBlockProps: section_edit_useBlockProps,
  useInnerBlocksProps,
  useSetting,
  __experimentalUseGradient,
  __experimentalPanelColorGradientSettings: PanelColorGradientSettings,
  __experimentalBlockFullHeightAligmentControl: FullHeightAlignmentControl
} = wp.blockEditor;
const {
  __: section_edit_
} = wp.i18n;
const {
  useSelect,
  withDispatch
} = wp.data;
const {
  getBlobTypeByURL,
  isBlobURL
} = wp.blob;

/**
 * Module Constants
 */
const ALLOWED_MEDIA_TYPES = ['image', 'video'];
function SectionHeightInput(_ref) {
  let {
    onChange,
    onUnitChange,
    unit = 'px',
    value = ''
  } = _ref;
  const instanceId = useInstanceId(UnitControl);
  const inputId = `flext-block-section-height-input-${instanceId}`;
  const isPx = unit === 'px';
  const units = useCustomUnits({
    availableUnits: useSetting('spacing.units') || ['px', 'em', 'rem', 'vw', 'vh'],
    defaultValues: {
      px: 430,
      '%': 20,
      em: 20,
      rem: 20,
      vw: 20,
      vh: 50
    }
  });
  const handleOnChange = unprocessedValue => {
    const inputValue = unprocessedValue !== '' ? parseFloat(unprocessedValue) : undefined;
    if (isNaN(inputValue) && inputValue !== undefined) {
      return;
    }
    onChange(inputValue);
  };
  const computedValue = useMemo(() => {
    const [parsedQuantity] = parseQuantityAndUnitFromRawValue(value);
    return [parsedQuantity, unit].join('');
  }, [unit, value]);
  const min = isPx ? SECTION_MIN_HEIGHT : 0;
  return (0,react.createElement)(BaseControl, {
    label: section_edit_('Minimum height of section', 'flextension'),
    id: inputId
  }, (0,react.createElement)(UnitControl, {
    id: inputId,
    isResetValueOnUnitChange: true,
    min: min,
    onChange: handleOnChange,
    onUnitChange: onUnitChange,
    step: "1",
    style: {
      maxWidth: 80
    },
    units: units,
    value: computedValue
  }));
}
const RESIZABLE_BOX_ENABLE_OPTION = {
  top: false,
  right: false,
  bottom: true,
  left: false,
  topRight: false,
  bottomRight: false,
  bottomLeft: false,
  topLeft: false
};
function ResizableSection(_ref2) {
  let {
    className,
    onResizeStart,
    onResize,
    onResizeStop,
    ...props
  } = _ref2;
  const [isResizing, setIsResizing] = edit_useState(false);
  return (0,react.createElement)(ResizableBox, _extends({
    className: classnames_default()(className, {
      'is-resizing': isResizing
    }),
    enable: RESIZABLE_BOX_ENABLE_OPTION,
    onResizeStart: (_event, _direction, elt) => {
      onResizeStart(elt.clientHeight);
      onResize(elt.clientHeight);
    },
    onResize: (_event, _direction, elt) => {
      onResize(elt.clientHeight);
      if (!isResizing) {
        setIsResizing(true);
      }
    },
    onResizeStop: (_event, _direction, elt) => {
      onResizeStop(elt.clientHeight);
      setIsResizing(false);
    }
  }, props));
}
function SectionEdit(_ref3) {
  let {
    attributes,
    clientId,
    isSelected,
    overlayColor,
    setAttributes,
    setOverlayColor,
    toggleSelection
  } = _ref3;
  const {
    id,
    backgroundType,
    dimRatio,
    scheme,
    focalPoint,
    hasParallax,
    isRepeated,
    minHeight,
    minHeightUnit,
    url
  } = attributes;
  const {
    gradientClass,
    gradientValue,
    setGradient
  } = __experimentalUseGradient();
  const isBlobUrl = isBlobURL(url);
  const [prevMinHeightValue, setPrevMinHeightValue] = edit_useState(minHeight);
  const [prevMinHeightUnit, setPrevMinHeightUnit] = edit_useState(minHeightUnit);
  const isMinFullHeight = minHeightUnit === 'vh' && minHeight === 100;
  const isImageBackground = IMAGE_BACKGROUND_TYPE === backgroundType;
  const isVideoBackground = VIDEO_BACKGROUND_TYPE === backgroundType;
  const minHeightWithUnit = minHeight && minHeightUnit ? `${minHeight}${minHeightUnit}` : minHeight;
  const isImgElement = !(hasParallax || isRepeated);
  const blockStyle = {
    minHeight: minHeightWithUnit || undefined
  };
  const mediaStyle = {
    objectPosition: focalPoint && (isImgElement || isVideoBackground) ? mediaPosition(focalPoint) : undefined
  };
  const hasBackground = !!(url || overlayColor.color || gradientValue);
  const showFocalPointPicker = isVideoBackground || isImageBackground && (!hasParallax || isRepeated);
  const {
    hasChildBlocks
  } = useSelect(select => {
    const {
      getBlockOrder,
      getBlockRootClientId
    } = select('core/block-editor');
    return {
      hasChildBlocks: getBlockOrder(clientId).length > 0,
      rootClientId: getBlockRootClientId(clientId)
    };
  }, [clientId]);
  const defaultLayout = useSetting('layout') || {};
  const ref = useRef();
  const blockProps = section_edit_useBlockProps({
    ref
  });
  const innerBlocksProps = useInnerBlocksProps({
    className: 'flext-block-section-inner'
  }, {
    templateLock: false,
    renderAppender: hasChildBlocks ? undefined : InnerBlocks.ButtonBlockAppender,
    __experimentalLayout: defaultLayout
  });
  const toggleMinFullHeight = () => {
    if (isMinFullHeight) {
      // If there aren't previous values, take the default ones.
      if (prevMinHeightUnit === 'vh' && prevMinHeightValue === 100) {
        return setAttributes({
          minHeight: undefined,
          minHeightUnit: undefined
        });
      }

      // Set the previous values of height.
      return setAttributes({
        minHeight: prevMinHeightValue,
        minHeightUnit: prevMinHeightUnit
      });
    }
    setPrevMinHeightValue(minHeight);
    setPrevMinHeightUnit(minHeightUnit);

    // Set full height.
    return setAttributes({
      minHeight: 100,
      minHeightUnit: 'vh'
    });
  };
  const toggleParallax = () => {
    setAttributes({
      hasParallax: !hasParallax,
      ...(!hasParallax ? {
        focalPoint: undefined
      } : {})
    });
  };
  const toggleIsRepeated = () => {
    setAttributes({
      isRepeated: !isRepeated
    });
  };
  const onSelectMedia = media => {
    if (!media || !media.url) {
      setAttributes({
        url: undefined,
        id: undefined
      });
      return;
    }
    if (isBlobURL(media.url)) {
      media.type = getBlobTypeByURL(media.url);
    }
    let mediaType;
    // for media selections originated from a file upload.
    if (media.media_type) {
      if (media.media_type === IMAGE_BACKGROUND_TYPE) {
        mediaType = IMAGE_BACKGROUND_TYPE;
      } else {
        // only images and videos are accepted so if the media_type is not an image we can assume it is a video.
        // Videos contain the media type of 'file' in the object returned from the rest api.
        mediaType = VIDEO_BACKGROUND_TYPE;
      }
    } else {
      // for media selections originated from existing files in the media library.
      if (media.type !== IMAGE_BACKGROUND_TYPE && media.type !== VIDEO_BACKGROUND_TYPE) {
        return;
      }
      mediaType = media.type;
    }
    setAttributes({
      url: media.url,
      id: media.id,
      backgroundType: mediaType,
      ...(mediaType === VIDEO_BACKGROUND_TYPE ? {
        focalPoint: undefined,
        hasParallax: undefined
      } : {})
    });
  };
  const classes = classnames_default()({
    'is-dark-theme': scheme === 'dark',
    'is-transient': isBlobUrl,
    'flext-has-background': hasBackground,
    'flext-has-background-dim': (overlayColor.color || gradientValue) && dimRatio !== 0,
    'flext-has-background-parallax': hasParallax,
    'flext-is-background-repeated': isRepeated,
    'flext-has-background-gradient': gradientValue,
    [`flext-has-scheme-${scheme}`]: scheme
  }, (overlayColor.color || gradientValue) && dimRatioToClass(dimRatio), 'alignfull');
  const backgroundStyle = {
    ...(isImageBackground && !isImgElement ? backgroundImageStyles(url) : undefined),
    backgroundPosition: focalPoint && !(isImgElement || isVideoBackground) ? mediaPosition(focalPoint) : undefined
  };
  return (0,react.createElement)(edit_Fragment, null, (0,react.createElement)(edit_BlockControls, {
    group: "block"
  }, (0,react.createElement)(FullHeightAlignmentControl, {
    isActive: isMinFullHeight,
    onToggle: toggleMinFullHeight,
    isDisabled: !hasBackground
  })), (0,react.createElement)(edit_BlockControls, {
    group: "other"
  }, (0,react.createElement)(MediaReplaceFlow, {
    mediaId: id,
    mediaURL: url,
    allowedTypes: ALLOWED_MEDIA_TYPES,
    accept: "image/*,video/*",
    onSelect: onSelectMedia,
    name: !url ? section_edit_('Background Media', 'flextension') : section_edit_('Replace', 'flextension')
  })), (0,react.createElement)(section_edit_InspectorControls, null, !!url && (0,react.createElement)(section_edit_PanelBody, {
    title: section_edit_('Background settings', 'flextension')
  }, isImageBackground && (0,react.createElement)(edit_Fragment, null, (0,react.createElement)(section_edit_ToggleControl, {
    label: section_edit_('Fixed background', 'flextension'),
    checked: hasParallax,
    onChange: toggleParallax
  }), (0,react.createElement)(section_edit_ToggleControl, {
    label: section_edit_('Repeated background', 'flextension'),
    checked: isRepeated,
    onChange: toggleIsRepeated
  })), showFocalPointPicker && (0,react.createElement)(FocalPointPicker, {
    label: section_edit_('Focal point picker', 'flextension'),
    url: url,
    value: focalPoint,
    onChange: value => setAttributes({
      focalPoint: value
    })
  }), (0,react.createElement)(PanelRow, null, (0,react.createElement)(Button, {
    isSecondary: true,
    isSmall: true,
    className: "block-library-cover__reset-button",
    onClick: () => setAttributes({
      url: undefined,
      id: undefined,
      backgroundType: undefined,
      dimRatio: undefined,
      focalPoint: undefined,
      hasParallax: undefined,
      isRepeated: undefined
    })
  }, section_edit_('Clear Media', 'flextension')))), (0,react.createElement)(section_edit_PanelBody, {
    title: section_edit_('Dimensions', 'flextension')
  }, (0,react.createElement)(SectionHeightInput, {
    value: minHeight,
    unit: minHeightUnit,
    onChange: newMinHeight => setAttributes({
      minHeight: newMinHeight
    }),
    onUnitChange: nextUnit => setAttributes({
      minHeightUnit: nextUnit
    })
  })), (0,react.createElement)(PanelColorGradientSettings, {
    __experimentalHasMultipleOrigins: true,
    __experimentalIsRenderedInSidebar: true,
    title: section_edit_('Color settings', 'flextension'),
    initialOpen: true,
    settings: [{
      colorValue: overlayColor.color,
      gradientValue,
      onColorChange: setOverlayColor,
      onGradientChange: setGradient,
      label: !url ? section_edit_('Background color', 'flextension') : section_edit_('Overlay color', 'flextension')
    }]
  }, (overlayColor.color || gradientValue) && (0,react.createElement)(section_edit_RangeControl, {
    label: section_edit_('Opacity', 'flextension'),
    value: dimRatio,
    onChange: value => setAttributes({
      dimRatio: value
    }),
    min: 0,
    max: 100,
    step: 10,
    required: true
  }), (0,react.createElement)(SchemeDropdownControl, {
    label: section_edit_('Scheme', 'flextension'),
    value: scheme,
    onChange: value => setAttributes({
      scheme: value
    })
  }))), (0,react.createElement)("div", _extends({}, blockProps, {
    className: classnames_default()(classes, blockProps.className),
    style: {
      ...blockStyle,
      ...blockProps.style
    }
  }), (0,react.createElement)(ResizableSection, {
    className: "block-library-cover__resize-container",
    onResizeStart: () => {
      setAttributes({
        minHeightUnit: 'px'
      });
      toggleSelection(false);
    },
    onResize: value => {
      setAttributes({
        minHeight: value
      });
    },
    onResizeStop: newMinHeight => {
      toggleSelection(true);
      setAttributes({
        minHeight: newMinHeight
      });
    },
    showHandle: isSelected
  }), hasBackground && (0,react.createElement)("div", {
    className: "flext-block-section-background",
    style: backgroundStyle
  }, url && isImageBackground && isImgElement && (0,react.createElement)("img", {
    className: "flext-block-section-image-background",
    alt: "",
    src: url,
    style: mediaStyle
  }), url && isVideoBackground && (0,react.createElement)("video", {
    className: "flext-block-section-video-background",
    autoPlay: true,
    muted: true,
    loop: true,
    src: url,
    style: mediaStyle
  }), (overlayColor.color || gradientValue) && dimRatio !== 0 && (0,react.createElement)("span", {
    "aria-hidden": "true",
    className: classnames_default()('flext-block-section-overlay-background', gradientClass, overlayColor.class),
    style: {
      backgroundColor: overlayColor.color,
      backgroundImage: gradientValue
    }
  }), isBlobUrl && (0,react.createElement)(Spinner, null)), (0,react.createElement)("div", innerBlocksProps)));
}
/* harmony default export */ var section_edit = (compose([withDispatch(dispatch => {
  const {
    toggleSelection
  } = dispatch('core/block-editor');
  return {
    toggleSelection
  };
}), withColors({
  overlayColor: 'background-color'
}), withNotices, withInstanceId])(SectionEdit));
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/save.js

/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  InnerBlocks: save_InnerBlocks,
  getColorClassName,
  __experimentalGetGradientClass,
  useBlockProps: section_save_useBlockProps
} = wp.blockEditor;
function save_save(_ref) {
  let {
    attributes
  } = _ref;
  const {
    backgroundType,
    gradient,
    customGradient,
    customOverlayColor,
    dimRatio,
    scheme,
    focalPoint,
    hasParallax,
    isRepeated,
    overlayColor,
    url,
    id,
    minHeight,
    minHeightUnit,
    margin,
    padding
  } = attributes;
  const overlayColorClass = getColorClassName('background-color', overlayColor);
  const gradientClass = __experimentalGetGradientClass(gradient);
  let minHeightWithUnit = minHeight && minHeightUnit ? `${minHeight}${minHeightUnit}` : minHeight;
  let isFullHeight = false;
  if (minHeightWithUnit === '100vh') {
    minHeightWithUnit = undefined;
    isFullHeight = true;
  }
  const isImageBackground = IMAGE_BACKGROUND_TYPE === backgroundType;
  const isVideoBackground = VIDEO_BACKGROUND_TYPE === backgroundType;
  const isImgElement = !(hasParallax || isRepeated);
  const blockStyle = {
    minHeight: minHeightWithUnit || undefined,
    marginTop: margin && margin.top ? margin.top : undefined,
    marginRight: margin && margin.right ? margin.right : undefined,
    marginBottom: margin && margin.bottom ? margin.bottom : undefined,
    marginLeft: margin && margin.left ? margin.left : undefined,
    paddingTop: padding && padding.top ? padding.top : undefined,
    paddingRight: padding && padding.right ? padding.right : undefined,
    paddingBottom: padding && padding.bottom ? padding.bottom : undefined,
    paddingLeft: padding && padding.left ? padding.left : undefined
  };
  const mediaStyle = {
    objectPosition: focalPoint && (isImgElement || isVideoBackground) ? mediaPosition(focalPoint) : undefined
  };
  const hasBackground = url || overlayColorClass || customOverlayColor || gradientClass || customGradient;
  const classes = classnames_default()({
    'flext-has-background': hasBackground,
    'flext-has-background-dim': (overlayColorClass || customOverlayColor || gradientClass || customGradient) && dimRatio !== 0,
    'flext-has-background-parallax': hasParallax,
    'flext-is-background-repeated': isRepeated,
    'flext-is-full-height': isFullHeight,
    'flext-has-background-gradient': gradient || customGradient,
    [`flext-has-scheme-${scheme}`]: scheme
  }, (overlayColorClass || customOverlayColor || gradientClass || customGradient) && dimRatioToClass(dimRatio), 'alignfull');
  const backgroundStyle = {
    ...(isImageBackground && !isImgElement ? backgroundImageStyles(url) : undefined),
    backgroundPosition: focalPoint && !(isImgElement || isVideoBackground) ? mediaPosition(focalPoint) : undefined
  };
  return (0,react.createElement)("div", section_save_useBlockProps.save({
    className: classes,
    style: blockStyle
  }), hasBackground && (0,react.createElement)("div", {
    className: "flext-block-section-background",
    style: backgroundStyle
  }, url && isImageBackground && isImgElement && (0,react.createElement)("img", {
    className: classnames_default()('flext-block-section-image-background', id ? `wp-image-${id}` : null),
    alt: "",
    src: url,
    style: mediaStyle
  }), url && isVideoBackground && (0,react.createElement)("video", {
    className: classnames_default()('flext-block-section-video-background', 'intrinsic-ignore'),
    autoPlay: true,
    muted: true,
    loop: true,
    playsInline: true,
    src: url,
    style: mediaStyle
  }), (overlayColorClass || customOverlayColor || gradientClass || customGradient) && dimRatio !== 0 && (0,react.createElement)("span", {
    className: classnames_default()('flext-block-section-overlay-background', gradientClass, overlayColorClass),
    style: {
      background: customGradient ? customGradient : undefined,
      backgroundColor: !overlayColorClass ? customOverlayColor : undefined
    }
  })), (0,react.createElement)("div", {
    className: "flext-block-section-inner"
  }, (0,react.createElement)(save_InnerBlocks.Content, null)));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/transforms.js
/**
 * WordPress dependencies
 */
const {
  createBlock
} = wp.blocks;
const transforms = {
  from: [{
    type: 'block',
    blocks: ['core/cover'],
    transform: (attributes, innerBlocks) => createBlock('flextension/section', attributes, innerBlocks)
  }, {
    type: 'block',
    blocks: ['core/image'],
    transform: _ref => {
      let {
        url,
        align,
        id,
        anchor
      } = _ref;
      return createBlock('flextension/section', {
        url,
        align,
        id,
        anchor
      });
    }
  }, {
    type: 'block',
    blocks: ['core/video'],
    transform: _ref2 => {
      let {
        src,
        align,
        id,
        anchor
      } = _ref2;
      return createBlock('flextension/section', {
        url: src,
        align,
        id,
        backgroundType: 'video',
        anchor
      });
    }
  }],
  to: [{
    type: 'block',
    blocks: ['core/cover'],
    transform: (attributes, innerBlocks) => createBlock('core/cover', attributes, innerBlocks)
  }]
};
/* harmony default export */ var section_transforms = (transforms);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/section/index.js
/**
 * Internal dependencies
 */






/**
 * WordPress dependencies
 */
const {
  registerBlockType: section_registerBlockType
} = wp.blocks;
const {
  addFilter: section_addFilter
} = wp.hooks;
const {
  name: section_name
} = section_block_namespaceObject;
const section_settings = {
  icon: section_icon,
  edit: section_edit,
  save: save_save,
  transforms: section_transforms
};

/**
 * Registers a block from metadata.
 *
 * @param {string} name     Block name.
 * @param {Object} settings Block settings.
 * @return {?WPBlock} The block, if it has been successfully registered;
 *                    otherwise `undefined`.
 */
section_registerBlockType({
  name: section_name,
  ...section_block_namespaceObject
}, section_settings);

/**
 * Sets a new class name for the block.
 *
 * @param {string} className Block class name.
 * @param {string} blockName Block name.
 * @return {string} Block class name.
 */
function section_setClassName(className, blockName) {
  return blockName === section_name ? 'flext-block-section' : className;
}
section_addFilter('blocks.getBlockDefaultClassName', 'flextension/blocks/section/set-class-name', section_setClassName);
;// CONCATENATED MODULE: ./src/modules/elements/js/block-editor.js
/**
 * Flextension Block Editor
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */








/**
 * WordPress dependencies
 */
const {
  updateCategory
} = wp.blocks;
const {
  Icon
} = wp.components;
updateCategory('flextension', {
  icon: (0,react.createElement)(Icon, {
    className: "flext-icon",
    icon: (0,react.createElement)("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      height: "20",
      width: "15",
      viewBox: "0 0 512 512"
    }, (0,react.createElement)("path", {
      xmlns: "http://www.w3.org/2000/svg",
      d: "M400.268,175.599c-1.399-3.004-4.412-4.932-7.731-4.932h-101.12l99.797-157.568c1.664-2.628,1.766-5.956,0.265-8.678 C389.977,1.69,387.109,0,384.003,0H247.47c-3.234,0-6.187,1.826-7.637,4.719l-128,256c-1.323,2.637-1.178,5.777,0.375,8.294 c1.562,2.517,4.301,4.053,7.262,4.053h87.748l-95.616,227.089c-1.63,3.883-0.179,8.388,3.413,10.59 c1.382,0.845,2.918,1.254,4.446,1.254c2.449,0,4.864-1.05,6.537-3.029l273.067-324.267 C401.206,182.161,401.667,178.611,400.268,175.599z"
    }))
  })
});
}();
/******/ })()
;