/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 7418:
/***/ (function(module) {

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

var __webpack_unused_export__;
/** @license React v17.0.2
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var l=__webpack_require__(7418),n=60103,p=60106;__webpack_unused_export__=60107;__webpack_unused_export__=60108;__webpack_unused_export__=60114;var q=60109,r=60110,t=60112;__webpack_unused_export__=60113;var u=60115,v=60116;
if("function"===typeof Symbol&&Symbol.for){var w=Symbol.for;n=w("react.element");p=w("react.portal");__webpack_unused_export__=w("react.fragment");__webpack_unused_export__=w("react.strict_mode");__webpack_unused_export__=w("react.profiler");q=w("react.provider");r=w("react.context");t=w("react.forward_ref");__webpack_unused_export__=w("react.suspense");u=w("react.memo");v=w("react.lazy")}var x="function"===typeof Symbol&&Symbol.iterator;
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
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {

// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(7294);
;// CONCATENATED MODULE: ./src/modules/theme-options/js/inc/icons.js

const darkMode = (0,react.createElement)("svg", {
  width: "20",
  height: "20",
  viewBox: "0 0 24 24",
  fill: "#111"
}, (0,react.createElement)("path", {
  d: "M12 11.807C10.7418 10.5483 9.88488 8.94484 9.53762 7.1993C9.19037 5.45375 9.36832 3.64444 10.049 2C8.10826 2.38205 6.3256 3.33431 4.92899 4.735C1.02399 8.64 1.02399 14.972 4.92899 18.877C8.83499 22.783 15.166 22.782 19.072 18.877C20.4723 17.4805 21.4245 15.6983 21.807 13.758C20.1625 14.4385 18.3533 14.6164 16.6077 14.2692C14.8622 13.9219 13.2588 13.0651 12 11.807V11.807Z"
}));
const lightMode = (0,react.createElement)("svg", {
  width: "20",
  height: "20",
  viewBox: "0 0 24 24",
  fill: "#eee"
}, (0,react.createElement)("path", {
  d: "M6.995 12C6.995 14.761 9.241 17.007 12.002 17.007C14.763 17.007 17.009 14.761 17.009 12C17.009 9.239 14.763 6.993 12.002 6.993C9.241 6.993 6.995 9.239 6.995 12ZM11 19H13V22H11V19ZM11 2H13V5H11V2ZM2 11H5V13H2V11ZM19 11H22V13H19V11Z"
}), (0,react.createElement)("path", {
  d: "M5.63702 19.778L4.22302 18.364L6.34402 16.243L7.75802 17.657L5.63702 19.778Z"
}), (0,react.createElement)("path", {
  d: "M16.242 6.34405L18.364 4.22205L19.778 5.63605L17.656 7.75805L16.242 6.34405Z"
}), (0,react.createElement)("path", {
  d: "M6.34402 7.75902L4.22302 5.63702L5.63802 4.22302L7.75802 6.34502L6.34402 7.75902Z"
}), (0,react.createElement)("path", {
  d: "M19.778 18.3639L18.364 19.7779L16.242 17.6559L17.656 16.2419L19.778 18.3639Z"
}));
const layout1 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  fill: "#ffffff",
  width: "80",
  height: "60"
}), (0,react.createElement)("rect", {
  fill: "#e8e8e8",
  width: "80",
  height: "26.96"
}), (0,react.createElement)("rect", {
  x: "15.04",
  y: "31.02",
  fill: "#c4c4c4",
  width: "38.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "15.04",
  y: "21.02",
  fill: "#c4c4c4",
  width: "14.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "15.04",
  y: "17.02",
  fill: "#c4c4c4",
  width: "14.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "56.04",
  y: "31.02",
  fill: "#c4c4c4",
  width: "8.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "15.04",
  y: "35.02",
  fill: "#c4c4c4",
  width: "38.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "56.04",
  y: "35.02",
  fill: "#c4c4c4",
  width: "8.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "15.04",
  y: "39.02",
  fill: "#c4c4c4",
  width: "38.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "56.04",
  y: "39.02",
  fill: "#c4c4c4",
  width: "8.96",
  height: "1.96"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "45",
  fill: "#e8e8e8",
  width: "50",
  height: "15"
}), (0,react.createElement)("rect", {
  y: "45",
  fill: "#e8e8e8",
  width: "12",
  height: "15"
}), (0,react.createElement)("rect", {
  x: "68",
  y: "45",
  fill: "#e8e8e8",
  width: "12",
  height: "15"
}));
const layout2 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  fill: "#ffffff",
  width: "80",
  height: "60"
}), (0,react.createElement)("rect", {
  x: "4.4",
  fill: "#e8e8e8",
  width: "75.6",
  height: "27"
}), (0,react.createElement)("polyline", {
  fill: "#c4c4c4",
  points: "15,33 15,31 34,31 34,33 "
}), (0,react.createElement)("rect", {
  x: "15",
  y: "35",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "39",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "45",
  fill: "#e8e8e8",
  width: "50",
  height: "15"
}), (0,react.createElement)("rect", {
  y: "45",
  fill: "#e8e8e8",
  width: "12",
  height: "15"
}), (0,react.createElement)("rect", {
  x: "68",
  y: "45",
  fill: "#e8e8e8",
  width: "12",
  height: "15"
}));
const layout3 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  x: "3.9",
  y: "8",
  fill: "#e8e8e8",
  width: "72",
  height: "23"
}), (0,react.createElement)("rect", {
  x: "23",
  y: "3.1",
  fill: "#c4c4c4",
  width: "34",
  height: "2"
}), (0,react.createElement)("g", null, (0,react.createElement)("rect", {
  x: "15",
  y: "37.2",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "41.2",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "47.2",
  fill: "#e8e8e8",
  width: "50",
  height: "12.7"
}), (0,react.createElement)("rect", {
  x: "0",
  y: "47.2",
  fill: "#e8e8e8",
  width: "12",
  height: "12.7"
}), (0,react.createElement)("rect", {
  x: "68",
  y: "47.2",
  fill: "#e8e8e8",
  width: "12",
  height: "12.7"
})));
const layout4 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  fill: "#ffffff",
  width: "80",
  height: "60"
}), (0,react.createElement)("rect", {
  x: "0.1",
  y: "14",
  fill: "#e8e8e8",
  width: "79.9",
  height: "27"
}), (0,react.createElement)("rect", {
  x: "21",
  y: "44",
  fill: "#c4c4c4",
  width: "39",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "21",
  y: "48",
  fill: "#c4c4c4",
  width: "39",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "21",
  y: "3.8",
  fill: "#c4c4c4",
  width: "15.4",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "21",
  y: "7.8",
  fill: "#c4c4c4",
  width: "39",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "53",
  fill: "#e8e8e8",
  width: "50",
  height: "7"
}), (0,react.createElement)("rect", {
  y: "53",
  fill: "#e8e8e8",
  width: "12",
  height: "7"
}), (0,react.createElement)("rect", {
  x: "68",
  y: "53",
  fill: "#e8e8e8",
  width: "12",
  height: "7"
}));
const layout5 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  fill: "#ffffff",
  width: "80",
  height: "60"
}), (0,react.createElement)("rect", {
  x: "4.1",
  y: "13.6",
  fill: "#e8e8e8",
  width: "75.9",
  height: "27.4"
}), (0,react.createElement)("rect", {
  x: "22.5",
  y: "44",
  fill: "#c4c4c4",
  width: "35",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "3.7",
  y: "3.9",
  fill: "#c4c4c4",
  width: "15",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "22.5",
  y: "48",
  fill: "#c4c4c4",
  width: "35",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "4",
  y: "53",
  fill: "#e8e8e8",
  width: "35",
  height: "7"
}), (0,react.createElement)("rect", {
  x: "42",
  y: "53",
  fill: "#e8e8e8",
  width: "35",
  height: "7"
}), (0,react.createElement)("rect", {
  x: "3.7",
  y: "7.9",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}));
const layout6 = (0,react.createElement)("svg", null, (0,react.createElement)("rect", {
  fill: "#ffffff",
  width: "80",
  height: "60"
}), (0,react.createElement)("rect", {
  x: "0.1",
  y: "0.3",
  fill: "#e8e8e8",
  width: "39.9",
  height: "27"
}), (0,react.createElement)("rect", {
  x: "40.1",
  y: "0.3",
  fill: "#f7f7f7",
  width: "39.9",
  height: "27"
}), (0,react.createElement)("rect", {
  x: "48.2",
  y: "14.8",
  fill: "#c4c4c4",
  width: "24.7",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "48.2",
  y: "10.8",
  fill: "#c4c4c4",
  width: "24.7",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "48.1",
  y: "6.2",
  fill: "#c4c4c4",
  width: "9",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "48.2",
  y: "19",
  fill: "#c4c4c4",
  width: "24.7",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "37.3",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "33",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "41.3",
  fill: "#c4c4c4",
  width: "50",
  height: "2"
}), (0,react.createElement)("rect", {
  x: "15",
  y: "47.3",
  fill: "#e8e8e8",
  width: "50",
  height: "12.7"
}), (0,react.createElement)("rect", {
  y: "47.3",
  fill: "#e8e8e8",
  width: "12",
  height: "12.7"
}), (0,react.createElement)("rect", {
  x: "68",
  y: "47.3",
  fill: "#e8e8e8",
  width: "12",
  height: "12.7"
}));
/* harmony default export */ var icons = ({
  darkMode,
  lightMode,
  layout1,
  layout2,
  layout3,
  layout4,
  layout5,
  layout6
});
;// CONCATENATED MODULE: ./src/modules/theme-options/js/inc/dark-mode-toggle-control.js

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  Component
} = wp.element;
const {
  BaseControl
} = wp.components;

/**
 * Dark Mode Toggle Control.
 */
class DarkModeToggleControl extends Component {
  constructor() {
    super(...arguments);
    const {
      isDark
    } = this.props;
    this.state = {
      isDark
    };
  }
  render() {
    const {
      label,
      help,
      onChange
    } = this.props;
    const {
      isDark
    } = this.state;
    const onClick = () => {
      const newValue = !isDark;
      this.setState({
        isDark: newValue
      });
      onChange(newValue);
    };
    let className = 'evie-toggle-button';
    if (isDark) {
      className += ' is-checked';
    }
    return (0,react.createElement)(BaseControl, {
      id: "evie-dark-mode-toggle-control",
      label: label,
      help: help,
      className: "evie-dark-mode-toggle-control"
    }, (0,react.createElement)("button", {
      className: className,
      onClick: onClick
    }, icons.lightMode, icons.darkMode));
  }
}
/* harmony default export */ var dark_mode_toggle_control = (DarkModeToggleControl);
;// CONCATENATED MODULE: ./src/modules/theme-options/js/inc/scheme-switcher-panel.js

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __
} = wp.i18n;
const {
  PluginSidebar
} = wp.editPost;
const {
  PanelBody
} = wp.components;
const {
  useSelect
} = wp.data;
const {
  useEffect,
  useState
} = wp.element;
function SchemeSwitcher() {
  const [darkMode, setDarkMode] = useState(document.body.classList.contains('has-scheme-dark'));
  const [iframeReady, setIframeReady] = useState(false);
  const setContentDarkMode = doc => {
    if (doc && doc.body) {
      if (darkMode === true) {
        doc.body.classList.add('has-scheme-dark', 'is-dark-theme');
      } else {
        doc.body.classList.remove('has-scheme-dark', 'is-dark-theme');
      }
    }
  };
  const deviceType = useSelect(select => {
    return select('core/edit-post').__experimentalGetPreviewDeviceType();
  }, []);
  useEffect(() => {
    if (deviceType !== 'Desktop') {
      const iframe = document.querySelector(`iframe[name='editor-canvas']`);
      if (iframe !== null && !iframeReady) {
        iframe.addEventListener('load', () => {
          setIframeReady(true);
          setContentDarkMode(iframe.contentDocument);
        });
      }
    } else {
      setIframeReady(false);
      setContentDarkMode(document);
    }
  }, [deviceType]);
  useEffect(() => {
    if (deviceType !== 'Desktop') {
      const iframe = document.querySelector(`iframe[name='editor-canvas']`);
      if (iframe !== null && iframeReady) {
        setContentDarkMode(iframe.contentDocument);
      }
    } else {
      setContentDarkMode(document);
    }
  }, [darkMode]);
  return (0,react.createElement)(PluginSidebar, {
    name: "evie-scheme-switcher-sidebar",
    title: __('Dark Mode Switcher', 'evie-xt')
  }, (0,react.createElement)(PanelBody, null, (0,react.createElement)(dark_mode_toggle_control, {
    help: __('Switch between Dark and Light mode to preview your content.', 'evie-xt'),
    isDark: darkMode,
    onChange: value => {
      setDarkMode(value);
    }
  })));
}
/* harmony default export */ var scheme_switcher_panel = (SchemeSwitcher);
;// CONCATENATED MODULE: ./src/modules/theme-options/js/index.js
/**
 * Internal dependencies
 */



/**
 * WordPress dependencies
 */
const {
  registerPlugin
} = wp.plugins;
registerPlugin('evie-scheme-switcher-panel', {
  render: scheme_switcher_panel,
  icon: icons.darkMode
});
}();
/******/ })()
;