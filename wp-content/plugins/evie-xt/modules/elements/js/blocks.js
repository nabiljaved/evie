/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

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

/***/ 1594:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * Colcade v0.2.0
 * Lightweight masonry layout
 * by David DeSandro
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */
  /*global define: false, module: false */
  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory() {

// -------------------------- Colcade -------------------------- //

function Colcade( element, options ) {
  element = getQueryElement( element );

  // do not initialize twice on same element
  if ( element && element.colcadeGUID ) {
    var instance = instances[ element.colcadeGUID ];
    instance.option( options );
    return instance;
  }

  this.element = element;
  // options
  this.options = {};
  this.option( options );
  // kick things off
  this.create();
}

var proto = Colcade.prototype;

proto.option = function( options ) {
  this.options = extend( this.options, options );
};

// globally unique identifiers
var GUID = 0;
// internal store of all Colcade intances
var instances = {};

proto.create = function() {
  this.errorCheck();
  // add guid for Colcade.data
  var guid = this.guid = ++GUID;
  this.element.colcadeGUID = guid;
  instances[ guid ] = this; // associate via id
  // update initial properties & layout
  this.reload();
  // events
  this._windowResizeHandler = this.onWindowResize.bind( this );
  this._loadHandler = this.onLoad.bind( this );
  window.addEventListener( 'resize', this._windowResizeHandler );
  this.element.addEventListener( 'load', this._loadHandler, true );
};

proto.errorCheck = function() {
  var errors = [];
  if ( !this.element ) {
    errors.push( 'Bad element: ' + this.element );
  }
  if ( !this.options.columns ) {
    errors.push( 'columns option required: ' + this.options.columns );
  }
  if ( !this.options.items ) {
    errors.push( 'items option required: ' + this.options.items );
  }

  if ( errors.length ) {
    throw new Error( '[Colcade error] ' + errors.join('. ') );
  }
};

// update properties and do layout
proto.reload = function() {
  this.updateColumns();
  this.updateItems();
  this.layout();
};

proto.updateColumns = function() {
  this.columns = querySelect( this.options.columns, this.element );
};

proto.updateItems = function() {
  this.items = querySelect( this.options.items, this.element );
};

proto.getActiveColumns = function() {
  return this.columns.filter( function( column ) {
    var style = getComputedStyle( column );
    return style.display != 'none';
  });
};

// ----- layout ----- //

// public, updates activeColumns
proto.layout = function() {
  this.activeColumns = this.getActiveColumns();
  this._layout();
};

// private, does not update activeColumns
proto._layout = function() {
  // reset column heights
  this.columnHeights = this.activeColumns.map( function() {
    return 0;
  });
  // layout all items
  this.layoutItems( this.items );
};

proto.layoutItems = function( items ) {
  items.forEach( this.layoutItem, this );
};

proto.layoutItem = function( item ) {
  // layout item by appending to column
  var minHeight = Math.min.apply( Math, this.columnHeights );
  var index = this.columnHeights.indexOf( minHeight );
  this.activeColumns[ index ].appendChild( item );
  // at least 1px, if item hasn't loaded
  // Not exactly accurate, but it's cool
  this.columnHeights[ index ] += item.offsetHeight || 1;
};

// ----- adding items ----- //

proto.append = function( elems ) {
  var items = this.getQueryItems( elems );
  // add items to collection
  this.items = this.items.concat( items );
  // lay them out
  this.layoutItems( items );
};

proto.prepend = function( elems ) {
  var items = this.getQueryItems( elems );
  // add items to collection
  this.items = items.concat( this.items );
  // lay out everything
  this._layout();
};

proto.getQueryItems = function( elems ) {
  elems = makeArray( elems );
  var fragment = document.createDocumentFragment();
  elems.forEach( function( elem ) {
    fragment.appendChild( elem );
  });
  return querySelect( this.options.items, fragment );
};

// ----- measure column height ----- //

proto.measureColumnHeight = function( elem ) {
  var boundingRect = this.element.getBoundingClientRect();
  this.activeColumns.forEach( function( column, i ) {
    // if elem, measure only that column
    // if no elem, measure all columns
    if ( !elem || column.contains( elem ) ) {
      var lastChildRect = column.lastElementChild.getBoundingClientRect();
      // not an exact calculation as it includes top border, and excludes item bottom margin
      this.columnHeights[ i ] = lastChildRect.bottom - boundingRect.top;
    }
  }, this );
};

// ----- events ----- //

proto.onWindowResize = function() {
  clearTimeout( this.resizeTimeout );
  this.resizeTimeout = setTimeout( function() {
    this.onDebouncedResize();
  }.bind( this ), 100 );
};

proto.onDebouncedResize = function() {
  var activeColumns = this.getActiveColumns();
  // check if columns changed
  var isSameLength = activeColumns.length == this.activeColumns.length;
  var isSameColumns = true;
  this.activeColumns.forEach( function( column, i ) {
    isSameColumns = isSameColumns && column == activeColumns[i];
  });
  if ( isSameLength && isSameColumns ) {
    return;
  }
  // activeColumns changed
  this.activeColumns = activeColumns;
  this._layout();
};

proto.onLoad = function( event ) {
  this.measureColumnHeight( event.target );
};

// ----- destroy ----- //

proto.destroy = function() {
  // move items back to container
  this.items.forEach( function( item ) {
    this.element.appendChild( item );
  }, this );
  // remove events
  window.removeEventListener( 'resize', this._windowResizeHandler );
  this.element.removeEventListener( 'load', this._loadHandler, true );
  // remove data
  delete this.element.colcadeGUID;
  delete instances[ this.guid ];
};

// -------------------------- HTML init -------------------------- //

docReady( function() {
  var dataElems = querySelect('[data-colcade]');
  dataElems.forEach( htmlInit );
});

function htmlInit( elem ) {
  // convert attribute "foo: bar, qux: baz" into object
  var attr = elem.getAttribute('data-colcade');
  var attrParts = attr.split(',');
  var options = {};
  attrParts.forEach( function( part ) {
    var pair = part.split(':');
    var key = pair[0].trim();
    var value = pair[1].trim();
    options[ key ] = value;
  });

  new Colcade( elem, options );
}

Colcade.data = function( elem ) {
  elem = getQueryElement( elem );
  var id = elem && elem.colcadeGUID;
  return id && instances[ id ];
};

// -------------------------- jQuery -------------------------- //

Colcade.makeJQueryPlugin = function( $ ) {
  $ = $ || window.jQuery;
  if ( !$ ) {
    return;
  }

  $.fn.colcade = function( arg0 /*, arg1 */) {
    // method call $().colcade( 'method', { options } )
    if ( typeof arg0 == 'string' ) {
      // shift arguments by 1
      var args = Array.prototype.slice.call( arguments, 1 );
      return methodCall( this, arg0, args );
    }
    // just $().colcade({ options })
    plainCall( this, arg0 );
    return this;
  };

  function methodCall( $elems, methodName, args ) {
    var returnValue;
    $elems.each( function( i, elem ) {
      // get instance
      var colcade = $.data( elem, 'colcade' );
      if ( !colcade ) {
        return;
      }
      // apply method, get return value
      var value = colcade[ methodName ].apply( colcade, args );
      // set return value if value is returned, use only first value
      returnValue = returnValue === undefined ? value : returnValue;
    });
    return returnValue !== undefined ? returnValue : $elems;
  }

  function plainCall( $elems, options ) {
    $elems.each( function( i, elem ) {
      var colcade = $.data( elem, 'colcade' );
      if ( colcade ) {
        // set options & init
        colcade.option( options );
        colcade.layout();
      } else {
        // initialize new instance
        colcade = new Colcade( elem, options );
        $.data( elem, 'colcade', colcade );
      }
    });
  }
};

// try making plugin
Colcade.makeJQueryPlugin();

// -------------------------- utils -------------------------- //

function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

// turn element or nodeList into an array
function makeArray( obj ) {
  var ary = [];
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    ary = obj;
  } else if ( obj && typeof obj.length == 'number' ) {
    // convert nodeList to array
    for ( var i=0; i < obj.length; i++ ) {
      ary.push( obj[i] );
    }
  } else {
    // array of single index
    ary.push( obj );
  }
  return ary;
}

// get array of elements
function querySelect( selector, elem ) {
  elem = elem || document;
  var elems = elem.querySelectorAll( selector );
  return makeArray( elems );
}

function getQueryElement( elem ) {
  if ( typeof elem == 'string' ) {
    elem = document.querySelector( elem );
  }
  return elem;
}

function docReady( onReady ) {
  if ( document.readyState == 'complete' ) {
    onReady();
    return;
  }
  document.addEventListener( 'DOMContentLoaded', onReady );
}

// -------------------------- end -------------------------- //

return Colcade;

}));


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
;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/block.json
var block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"evie/featured-posts","title":"Featured Posts","description":"Displays the featured posts.","category":"flextension","textdomain":"evie-xt","keywords":["featured posts","top posts","post","blog","portfolio","project"],"supports":{"html":false,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"blockId":{"type":"string","default":""},"className":{"type":"string"},"type":{"type":"string","enum":["carousel","slider","split","vertical"],"default":"carousel"},"backgroundOverlay":{"type":"string","enum":["","dark","light"],"default":""},"displayCategory":{"type":"boolean","default":true},"mousewheel":{"type":"boolean","default":false},"disableScroll":{"type":"boolean","default":false},"query":{"type":"object","default":{"postType":"post","posts":[],"taxonomy":"","terms":[],"author":"","authors":[],"timeRange":"","orderBy":"date","order":"DESC","numberOfItems":10}}},"editorStyle":"evie-edit-blocks","editorScript":"evie-edit-blocks","style":"evie-blocks","viewScript":"evie-blocks"}');
;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/split-slider.js
/**
 * Split Slider
 *
 * @author Wyde
 * @version 1.0.0
 */



const {
  gsap
} = window;

/**
 * Slide class
 */
class Slide {
  constructor(el) {
    this.DOM = {
      el
    };
  }
  setCurrent() {
    this.DOM.el.classList.add('current-slide');
  }
  reset() {
    this.DOM.el.classList.remove('current-slide');
  }
}

/**
 * Column class
 */
class Column {
  constructor(el, options) {
    this.DOM = {
      el
    };
    // The slide settings.
    this.settings = {
      reverse: false,
      duration: 1.2,
      ease: 'Expo.easeInOut'
    };
    Object.assign(this.settings, options || {});
    this.slides = [];
    this.current = 0;
    this.init();
  }

  /**
   * Initialize slides in the column.
   */
  init() {
    this.DOM.el.querySelectorAll('.evie-slide').forEach(slide => {
      if (true === this.settings.reverse) {
        this.DOM.el.insertBefore(slide, this.DOM.el.firstChild);
      }
      this.slides.push(new Slide(slide));
    });
  }

  /**
   * Loads the slides.
   *
   * @param {number} index The active slide index.
   */
  load(index) {
    this.slideTo(index, false);
  }

  /**
   * Navigates to specific slide.
   *
   * @param {number}  index   The slide index to navigate to.
   * @param {boolean} animate Whether the slide should be animated.
   */
  slideTo(index) {
    let animate = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    return new Promise(resolve => {
      this.slides[this.current].reset();
      let translate = index;
      if (this.settings.reverse) {
        translate = this.slides.length - 1 - index;
      }
      if (!animate) {
        gsap.set(this.DOM.el, {
          y: -(translate * 100) + '%',
          onComplete: () => {
            this.current = index;
            this.slides[index].setCurrent();
          }
        });
      } else {
        gsap.to(this.DOM.el, {
          y: -(translate * 100) + '%',
          duration: this.settings.duration,
          ease: this.settings.ease,
          onComplete: () => {
            this.current = index;
            this.slides[index].setCurrent();
            resolve();
          }
        });
      }
    });
  }
}

/**
 * The navigation class. Controls the slide animations (e.g. pagination animation).
 */
class Navigation {
  constructor(el, options) {
    if (!el) {
      return;
    }
    this.DOM = {
      el
    };
    this.settings = {
      total: 0,
      current: 0,
      slideTo: () => {
        return false;
      }
    };
    Object.assign(this.settings, options);
    this.current = this.settings.current;
    this.buttons = [];
    this.init();
  }

  /**
   * Initializes the navigation.
   */
  init() {
    const buttonWrapper = document.createElement('div');
    buttonWrapper.classList.add('evie-page-buttons');
    this.DOM.el.appendChild(buttonWrapper);
    this.prevButton = document.createElement('span');
    this.prevButton.classList.add('evie-nav-button', 'evie-button-prev');
    this.prevButton.innerHTML = '<i class="evie-ico-arrow-up"></i>';
    this.prevButton.addEventListener('click', e => {
      e.preventDefault();
      const index = this.current - 1;
      if (index < 0) {
        return;
      }
      this.slideTo(index);
    });
    buttonWrapper.appendChild(this.prevButton);
    this.currentPage = document.createElement('span');
    this.currentPage.classList.add('evie-first-page');
    this.currentPage.innerHTML = ('0' + (this.current + 1)).slice(-2);
    buttonWrapper.appendChild(this.currentPage);
    for (let i = 0; i < this.settings.total; i++) {
      const button = document.createElement('span');
      button.classList.add('evie-page-button');
      button.addEventListener('click', e => {
        e.preventDefault();
        this.slideTo(i);
        return false;
      });
      this.buttons.push(button);
      buttonWrapper.appendChild(button);
    }
    this.totalPage = document.createElement('span');
    this.totalPage.classList.add('evie-last-page');
    this.totalPage.innerHTML = ('0' + this.settings.total).slice(-2);
    buttonWrapper.appendChild(this.totalPage);
    this.nextButton = document.createElement('span');
    this.nextButton.classList.add('evie-nav-button', 'evie-button-next');
    this.nextButton.innerHTML = '<i class="evie-ico-arrow-down"></i>';
    this.nextButton.addEventListener('click', e => {
      e.preventDefault();
      const index = this.current + 1;
      if (index > this.settings.total - 1) {
        return;
      }
      this.slideTo(index < this.settings.total ? index : 0);
    });
    buttonWrapper.appendChild(this.nextButton);
  }

  /**
   * Sets the current slide to show.
   *
   * @param {number} current The current slide index.
   */
  setCurrent(current) {
    if (this.buttons && this.buttons.length > 0) {
      this.current = current;
      this.currentPage.innerText = ('0' + (this.current + 1)).slice(-2);
      this.buttons.forEach((button, index) => {
        if (index === this.current) {
          button.classList.add('current');
        } else {
          button.classList.remove('current');
        }
      });
    }
    if (this.prevButton) {
      this.prevButton.classList.toggle('evie-button-disabled', this.current === 0);
    }
    if (this.nextButton) {
      this.nextButton.classList.toggle('evie-button-disabled', this.current === this.settings.total - 1);
    }
  }

  /**
   * Slides to target index.
   *
   * @param {number} index Slide index.
   */
  slideTo(index) {
    if (typeof this.settings.slideTo === 'function') {
      this.settings.slideTo(index);
    }
  }
}

/**
 * Split Slider class.
 */
class SplitSlider {
  constructor(el, options) {
    if (!el) {
      return;
    }
    if (el.classList.contains('is-split-slider-initialized')) {
      return;
    }
    el.classList.add('is-split-slider-initialized');
    this.DOM = {
      el
    };
    this.DOM.wrapper = this.DOM.el.querySelector('.evie-slides');
    this.settings = {
      start: 0,
      loop: false,
      mousewheel: true,
      keyboard: true,
      touch: true,
      duration: 0.3,
      ease: 'Expo.easeInOut',
      onInit: () => {},
      onChange: () => {},
      onBeforeChange: () => {}
    };
    Object.assign(this.settings, options || {});
    this.columns = [];
    this.slides = [];
    this.DOM.el.querySelectorAll('.evie-column').forEach(col => {
      if (!col.dataset.reverse) {
        const column = new Column(col);
        this.slides = column.slides;
        this.columns.push(column);
      } else {
        this.columns.push(new Column(col, {
          reverse: true
        }));
      }
    });
    this.slidesTotal = this.slides.length;

    // Initialize the navigation instance. When clicking the next or prev ctrl buttons, trigger the navigate function.
    this.navigation = new Navigation(this.DOM.el.querySelector('.slider-pagination'), {
      slideTo: index => this.slideTo(index),
      total: this.slidesTotal
    });

    // Current slide position.
    this.current = 0;
    // Initialize the slideshow.
    this.init();
    this.load();
  }

  /**
   * Returns whether an element is within the viewport.
   *
   * @param {Element} element Target element.
   * @return {boolean} Whether the element is within the viewport.
   */
  isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return !(rect.bottom < 0 || rect.top - window.innerHeight >= 0);
  }

  /**
   * Initializes the slider.
   */
  init() {
    this.initMouseWheel();
    this.initKeyboard();
    this.initTouch();
    if (typeof this.settings.onInit === 'function') {
      this.settings.onInit(this);
    }
  }
  initMouseWheel() {
    if (this.settings.mousewheel === true) {
      // Play slides using mouse scroll.
      this.DOM.el.addEventListener('wheel', e => {
        let playSlide = true;
        if (e.deltaY > 0) {
          playSlide = this.navigate('next');
        } else {
          playSlide = this.navigate('prev');
        }
        if (true === playSlide) {
          e.preventDefault();
          return false;
        }
      });
    }
  }
  initKeyboard() {
    if (this.settings.keyboard === true) {
      // Play slides using keyboard.
      const keys = ['ArrowUp', 'ArrowDown', 'ArrowArrowLeft', 'ArrowRight'];
      window.addEventListener('keydown', e => {
        if (-1 !== keys.indexOf(e.key)) {
          if (!this.isInViewport(this.DOM.wrapper)) {
            return;
          }
          let playSlide = true;
          if ('ArrowDown' === e.key) {
            playSlide = this.navigate('next');
          } else if ('ArrowUp' === e.key) {
            playSlide = this.navigate('prev');
          }
          if (window.scrollY > 10) {
            return true;
          }
          if (true === playSlide) {
            e.preventDefault();
            return false;
          }
        }
      }, false);
    }
  }
  initTouch() {
    if (this.settings.touch) {
      let xDown = null;
      let yDown = null;
      const getTouches = event => {
        return event.touches || event.originalEvent.touches;
      };
      const handleTouchStart = event => {
        const firstTouch = getTouches(event)[0];
        xDown = firstTouch.clientX;
        yDown = firstTouch.clientY;
      };
      const handleTouchMove = event => {
        if (!xDown || !yDown) {
          return;
        }
        const xUp = event.touches[0].clientX;
        const yUp = event.touches[0].clientY;
        const xDiff = xDown - xUp;
        const yDiff = yDown - yUp;
        let playSlide = true;
        if (Math.abs(xDiff) > Math.abs(yDiff)) {
          /*most significant*/
          if (xDiff > 0) {
            /* left swipe */
          } else {
            /* right swipe */
          }
        } else if (yDiff > 0) {
          playSlide = this.navigate('next');
        } else {
          playSlide = this.navigate('prev');
        }
        /* reset values */
        xDown = null;
        yDown = null;
        if (true === playSlide) {
          return false;
        }
      };
      document.addEventListener('touchstart', handleTouchStart, false);
      document.addEventListener('touchmove', handleTouchMove, false);
    }
  }

  /**
   * Loads the slider.
   */
  load() {
    if (this.columns.length > 0 && this.slides.length > 0) {
      this.columns.forEach(column => {
        column.load(this.settings.start);
      });
      this.navigation.setCurrent(this.settings.start);
      this.slideChange(this.settings.start);
      if (this.settings.start !== this.current) {
        this.slideTo(this.current);
      }
    }
    this.DOM.el.classList.add('is-loaded');
  }

  /**
   * Navigates to the given direction.
   *
   * @param {string} direction The slide direction between next and previous.
   */
  navigate(direction) {
    let nextSlideIndex;
    if ('next' === direction) {
      nextSlideIndex = this.current + 1;
      if (nextSlideIndex > this.slidesTotal - 1) {
        if (this.settings.loop !== true) {
          return false;
        }
        nextSlideIndex = 0;
      }
    } else {
      nextSlideIndex = this.current - 1;
      if (nextSlideIndex < 0) {
        if (this.settings.loop !== true) {
          return false;
        }
        nextSlideIndex = this.slidesTotal - 1;
      }
    }
    this.slideTo(nextSlideIndex);
    return true;
  }

  /**
   * Slides to the specific slide.
   *
   * @param {number} index The slide index to navigate to.
   */
  slideTo(index) {
    // If animating return.
    if (this.isAnimating) {
      return false;
    }
    this.isAnimating = true;
    if (typeof this.settings.onBeforeChange === 'function') {
      this.settings.onBeforeChange(this, this.slides[index]);
    }
    if (index > this.current) {
      this.DOM.el.classList.remove('slide-to-prev');
      this.DOM.el.classList.add('slide-to-next');
    } else {
      this.DOM.el.classList.remove('slide-to-next');
      this.DOM.el.classList.add('slide-to-prev');
    }
    Promise.all([this.columns[0].slideTo(index), this.columns[1].slideTo(index, true)]).then(() => {
      this.current = index;
      this.slideChange(index);
      this.isAnimating = false;
    });
    this.navigation.setCurrent(index);
  }
  slideChange(index) {
    if (this.slides.length === 0) {
      return;
    }
    if (typeof this.settings.onChange === 'function') {
      this.settings.onChange(this, this.slides[index]);
    }
    const slide = this.slides[index];
    if (slide && slide.DOM.el.classList.contains('has-scheme-light')) {
      this.navigation.DOM.el.classList.remove('has-scheme-dark');
      this.navigation.DOM.el.classList.add('has-scheme-light');
    } else if (slide.DOM.el.classList.contains('has-scheme-dark')) {
      this.navigation.DOM.el.classList.remove('has-scheme-light');
      this.navigation.DOM.el.classList.add('has-scheme-dark');
    } else {
      this.navigation.DOM.el.classList.remove('has-scheme-light', 'has-scheme-dark');
    }
  }
}
/* harmony default export */ var split_slider = (SplitSlider);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/edit.js

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
  Fragment,
  useEffect
} = wp.element;
const {
  PanelBody,
  SelectControl,
  ToggleControl
} = wp.components;
const {
  InspectorControls,
  useBlockProps
} = wp.blockEditor;
const {
  flextension
} = window;
const {
  QueryControls,
  ServerSideRender
} = flextension.editor.components;
function FeaturedPostsEdit(props) {
  const {
    attributes,
    setAttributes,
    clientId
  } = props;
  const {
    blockId,
    type,
    backgroundOverlay,
    displayCategory,
    mousewheel,
    disableScroll,
    query
  } = attributes;
  useEffect(() => {
    setAttributes({
      blockId: clientId
    });
  }, [blockId, clientId]);
  const blockProps = useBlockProps();
  const onChange = content => {
    if (!content) {
      return;
    }
    const element = content.querySelector('.evie-block-featured-posts');
    if (element !== null) {
      switch (type) {
        case 'carousel':
          new flextension.carousel(element, {
            simulateTouch: false,
            mousewheel,
            loop: true,
            breakpoints: {
              1024: {
                centeredSlides: true,
                slidesPerView: 2
              }
            }
          });
          break;
        case 'slider':
          new flextension.carousel(element, {
            grabCursor: true,
            navigation: {
              nextEl: '.evie-button-next',
              prevEl: '.evie-button-prev',
              disabledClass: 'evie-button-disabled',
              hiddenClass: 'flext-button-hidden',
              lockClass: 'flext-button-lock'
            },
            simulateTouch: false,
            mousewheel,
            breakpoints: {
              768: {
                slidesPerView: 1
              }
            }
          });
          break;
        case 'vertical':
          new split_slider(element, {
            mousewheel: element.classList.contains('has-mousewheel'),
            keyboard: false,
            touch: false,
            onChange: (slider, slide) => {
              const backgroundText = slider.DOM.el.querySelector('.slider-background span');
              if (backgroundText !== null) {
                const title = slide.DOM.el.querySelector('.slide-title');
                if (title) {
                  const text = title.innerText + ' ';
                  backgroundText.innerText = text.repeat(5);
                }
              }
            }
          });
          break;
        case 'split':
          new split_slider(element, {
            mousewheel: element.classList.contains('has-mousewheel'),
            keyboard: false,
            touch: false
          });
          break;
      }
    }
  };
  return (0,react.createElement)(Fragment, null, (0,react.createElement)(InspectorControls, null, (0,react.createElement)(PanelBody, {
    title: __('Layout', 'evie-xt')
  }, (0,react.createElement)(SelectControl, {
    label: __('Type', 'evie-xt'),
    options: [{
      value: 'carousel',
      label: __('Carousel', 'evie-xt')
    }, {
      value: 'slider',
      label: __('Large Slider', 'evie-xt')
    }, {
      value: 'vertical',
      label: __('Vertical Slider', 'evie-xt')
    }, {
      value: 'split',
      label: __('Split Slider', 'evie-xt')
    }],
    value: type,
    onChange: value => setAttributes({
      type: value
    })
  }), 'split' === type && (0,react.createElement)(SelectControl, {
    label: __('Background Overlay', 'evie-xt'),
    options: [{
      value: '',
      label: __('None', 'evie-xt')
    }, {
      value: 'dark',
      label: __('Dark', 'evie-xt')
    }, {
      value: 'light',
      label: __('Light', 'evie-xt')
    }],
    value: backgroundOverlay,
    onChange: value => setAttributes({
      backgroundOverlay: value
    })
  }), (0,react.createElement)(ToggleControl, {
    label: __('Display category', 'evie-xt'),
    checked: displayCategory,
    onChange: value => setAttributes({
      displayCategory: value
    })
  }), (0,react.createElement)(ToggleControl, {
    label: __('Mousewheel navigation', 'evie-xt'),
    checked: mousewheel,
    onChange: value => setAttributes({
      mousewheel: value
    })
  }), (0,react.createElement)(ToggleControl, {
    label: __('Disable scrolling', 'evie-xt'),
    help: __('Prevent page from scrolling.', 'evie-xt'),
    checked: disableScroll,
    onChange: value => setAttributes({
      disableScroll: value
    })
  })), (0,react.createElement)(PanelBody, {
    title: __('Sorting and filtering', 'evie-xt')
  }, (0,react.createElement)(QueryControls, {
    postTypes: [{
      value: 'post',
      label: __('Post', 'evie-xt')
    }, {
      value: 'project',
      label: __('Project', 'evie-xt')
    }],
    value: query,
    onChange: value => setAttributes({
      query: value
    })
  }))), (0,react.createElement)("div", blockProps, (0,react.createElement)(ServerSideRender, {
    block: "evie/featured-posts",
    attributes: attributes,
    clientId: clientId,
    onChange: onChange
  })));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/featured-posts/block.js

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
  name: block_name
} = block_namespaceObject;
const settings = {
  icon: (0,react.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24"
  }, (0,react.createElement)("path", {
    d: "M3,5.8v12h18v-12C21,5.8,3,5.8,3,5.8z M18.9,16.6c-1.2-0.4-2.3-0.8-3.4-1.3c1.2-0.5,2.6-0.9,4.1-0.9c0.1,0,0.1,0,0.2,0v2.1 h-0.9V16.6z M19.8,7v6.1c-0.1,0-0.2,0-0.2,0c-2.3,0-4.4,0.8-5.8,1.5c-1.9-0.7-3.9-1.4-5.7-1.4c-1.3,0-2.6,0.3-3.9,0.7v-7L19.8,7 L19.8,7z M4.2,15.3L4.2,15.3c1.4-0.5,2.6-0.7,3.8-0.7c1.7,0,3.7,0.7,5.5,1.5l0.9,0.3c0.2,0.1,0.4,0.1,0.5,0.2H4.2V15.3z M6.5,11.1 h2.4c0.7,0,1.3-0.6,1.3-1.3c-0.1-0.7-0.5-1.1-1.1-1.4C8.9,8,8.3,7.7,7.7,7.7C7.2,7.7,6.6,8,6.3,8.5c-0.7,0.2-1,0.7-1,1.3 C5.2,10.5,5.8,11.1,6.5,11.1z M6.4,9.3h0.3l0.1-0.1C7,8.8,7.3,8.6,7.6,8.6s0.7,0.2,0.8,0.6c0,0,0.1,0.1,0.2,0.1c0,0,0.1,0,0.2,0 c0.3,0,0.5,0.2,0.5,0.5c0,0.2-0.1,0.5-0.5,0.5H6.5C6.3,10.3,6,10,6,9.8C6,9.5,6.3,9.3,6.4,9.3z M3,3.3h6.7v1.2H3V3.3z M11.6,3.3h1.9 v1.2h-1.9V3.3z M3,19h1.2v1.2H3V19z M5.7,19h1.2v1.2H5.7V19z M8.4,19h1.2v1.2H8.4V19z M23,1.9v20.2H1V1.9H23 M24,0.9H0v22.2h24V0.9 L24,0.9z"
  })),
  getEditWrapperProps() {
    // This block always displays the content in full width layout.
    return {
      'data-align': 'full'
    };
  },
  edit: FeaturedPostsEdit
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
  name: block_name,
  ...block_namespaceObject
}, settings);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/block.json
var post_carousel_block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"evie/post-carousel","title":"Post Carousel","description":"Displays your posts in a carousel.","category":"flextension","textdomain":"evie-xt","keywords":["carousel","slider","post","blog","portfolio","project"],"supports":{"align":["wide","full"],"html":false,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"blockId":{"type":"string","default":""},"className":{"type":"string"},"title":{"type":"string","default":""},"description":{"type":"string"},"postStyle":{"type":"string","default":"card"},"hoverEffect":{"type":"string","default":""},"columns":{"type":"number","default":5},"displayNumber":{"type":"boolean","default":false},"displayTitle":{"type":"boolean","default":true},"displayCategory":{"type":"boolean","default":true},"displayAuthor":{"type":"boolean","default":true},"displayDate":{"type":"boolean","default":true},"displayButtons":{"type":"boolean","default":true},"navigation":{"type":"boolean","default":false},"pagination":{"type":"boolean","default":false},"link":{"type":"string","enum":["","archive","more","custom"],"default":""},"linkText":{"type":"string","default":"See all"},"linkURL":{"type":"string","default":""},"query":{"type":"object","default":{"postType":"post","posts":[],"taxonomy":"","terms":[],"author":"","authors":[],"timeRange":"","orderBy":"date","order":"DESC","numberOfItems":10}},"isEditMode":{"type":"boolean","default":false}},"styles":[{"name":"vertical","label":"Vertical","isDefault":true},{"name":"horizontal","label":"Horizontal"}],"editorStyle":"evie-edit-blocks","editorScript":"evie-edit-blocks","style":"evie-blocks","viewScript":"evie-blocks"}');
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(4184);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/posts/shared.js
/**
 * WordPress dependencies
 */
const {
  __: shared_
} = wp.i18n;
const POST_STYLES = [{
  value: 'card',
  label: shared_('Card', 'evie-xt')
}, {
  value: 'text-overlay',
  label: shared_('Text Overlay', 'evie-xt')
}];
const HOVER_EFFECTS = [{
  value: 'none',
  label: shared_('None', 'evie-xt')
}, {
  value: '1',
  label: shared_('Inset Border', 'evie-xt')
}, {
  value: '3',
  label: shared_('Ripple', 'evie-xt')
}, {
  value: '2',
  label: shared_('Slide Up', 'evie-xt')
}];
const REVEAL_ANIMATIONS = [{
  value: '',
  label: shared_('None', 'evie-xt')
}, {
  value: '2',
  label: shared_('Fade In', 'evie-xt')
}, {
  value: '3',
  label: shared_('Fade Up', 'evie-xt')
}, {
  value: '1',
  label: shared_('Zoom In', 'evie-xt')
}];
const PAGINATION_OPTIONS = [{
  value: '',
  label: shared_('None', 'evie-xt')
}, {
  value: 'scroll',
  label: shared_('Infinite Scroll', 'evie-xt')
}, {
  value: 'loadmore',
  label: shared_('Load More', 'evie-xt')
}, {
  value: 'next_previous',
  label: shared_('Next and Previous', 'evie-xt')
}, {
  value: 'numbered',
  label: shared_('Numbered Pagination', 'evie-xt')
}];
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/edit.js

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
  __: edit_
} = wp.i18n;
const {
  Fragment: edit_Fragment,
  useEffect: edit_useEffect
} = wp.element;
const {
  PanelBody: edit_PanelBody,
  RangeControl,
  SelectControl: edit_SelectControl,
  TextControl,
  ToggleControl: edit_ToggleControl
} = wp.components;
const {
  InspectorControls: edit_InspectorControls,
  RichText,
  useBlockProps: edit_useBlockProps
} = wp.blockEditor;
const {
  flextension: edit_flextension,
  evieApp
} = window;
const {
  QueryControls: edit_QueryControls,
  ServerSideRender: edit_ServerSideRender
} = edit_flextension.editor.components;
function PostCarouselEdit(props) {
  const {
    attributes,
    setAttributes,
    clientId
  } = props;
  const {
    blockId,
    title,
    description,
    postStyle,
    hoverEffect,
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
    if (content !== null) {
      const carousel = content.querySelector('.posts-layout-carousel');
      if (carousel !== null) {
        const isHorizontal = content.classList.contains('is-style-horizontal');
        const getColumns = max => {
          if (isHorizontal && (evieApp.browser.lg || evieApp.browser.xl)) {
            max = max - 1;
          }
          return Math.min(parseInt(columns, 10) || max, max);
        };
        new edit_flextension.carousel(carousel, {
          simulateTouch: false,
          navigation: {
            nextEl: content.querySelector('.flext-button-next'),
            prevEl: content.querySelector('.flext-button-prev')
          },
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
    }
  };
  const blockProps = edit_useBlockProps({
    className: classnames_default()('evie-block-post-carousel', {
      'has-post-number': displayNumber
    })
  });
  return (0,react.createElement)(edit_Fragment, null, (0,react.createElement)(edit_InspectorControls, null, (0,react.createElement)(edit_PanelBody, null, (0,react.createElement)(edit_SelectControl, {
    label: edit_('Post style', 'evie-xt'),
    options: POST_STYLES,
    value: postStyle,
    onChange: value => setAttributes({
      postStyle: value
    })
  }), (0,react.createElement)(edit_SelectControl, {
    label: edit_('Hover effect', 'evie-xt'),
    options: HOVER_EFFECTS,
    value: hoverEffect,
    onChange: value => setAttributes({
      hoverEffect: value
    })
  }), (0,react.createElement)(RangeControl, {
    label: edit_('Columns', 'evie-xt'),
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
    label: edit_('Display title', 'evie-xt'),
    checked: displayTitle,
    onChange: value => setAttributes({
      displayTitle: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Display category', 'evie-xt'),
    checked: displayCategory,
    onChange: value => setAttributes({
      displayCategory: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Display author', 'evie-xt'),
    checked: displayAuthor,
    onChange: value => setAttributes({
      displayAuthor: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Display date', 'evie-xt'),
    checked: displayDate,
    onChange: value => setAttributes({
      displayDate: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Display Likes, Share and Comments', 'evie-xt'),
    checked: displayButtons,
    onChange: value => setAttributes({
      displayButtons: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Display post number', 'evie-xt'),
    checked: displayNumber,
    onChange: value => setAttributes({
      displayNumber: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Navigation', 'evie-xt'),
    checked: navigation,
    onChange: value => setAttributes({
      navigation: value
    })
  }), (0,react.createElement)(edit_ToggleControl, {
    label: edit_('Pagination', 'evie-xt'),
    checked: pagination,
    onChange: value => setAttributes({
      pagination: value
    })
  })), (0,react.createElement)(edit_PanelBody, {
    title: edit_('Sorting and filtering', 'evie-xt')
  }, (0,react.createElement)(edit_QueryControls, {
    postTypes: [{
      value: 'post',
      label: edit_('Post', 'evie-xt')
    }, {
      value: 'project',
      label: edit_('Project', 'evie-xt')
    }],
    value: query,
    onChange: value => setAttributes({
      query: value
    })
  })), (0,react.createElement)(edit_PanelBody, {
    title: edit_('Link settings', 'evie-xt')
  }, (0,react.createElement)(edit_SelectControl, {
    label: edit_('Link to', 'evie-xt'),
    options: [{
      value: '',
      label: edit_('None', 'evie-xt')
    }, {
      value: 'archive',
      label: edit_('Archive page (all posts)', 'evie-xt')
    }, {
      value: 'more',
      label: edit_('More similar posts', 'evie-xt')
    }, {
      value: 'custom',
      label: edit_('Custom', 'evie-xt')
    }],
    value: link,
    onChange: value => setAttributes({
      link: value
    })
  }), link && (0,react.createElement)(TextControl, {
    label: edit_('Link text', 'evie-xt'),
    value: linkText,
    onChange: value => setAttributes({
      linkText: value
    })
  }), link === 'custom' && (0,react.createElement)(TextControl, {
    label: edit_('URL', 'evie-xt'),
    type: "url",
    value: linkURL,
    onChange: value => setAttributes({
      linkURL: value
    })
  }))), (0,react.createElement)("div", blockProps, (0,react.createElement)("div", {
    className: "post-carousel-header"
  }, (0,react.createElement)("div", {
    className: "post-carousel-content"
  }, (0,react.createElement)("h2", {
    className: "post-carousel-title"
  }, (0,react.createElement)(RichText, {
    identifier: "title",
    tagName: "span",
    value: title,
    onChange: value => {
      setAttributes({
        title: value
      });
    },
    placeholder: edit_('Heading…', 'evie-xt')
  })), (0,react.createElement)("p", {
    className: "post-carousel-description"
  }, (0,react.createElement)(RichText, {
    identifier: "description",
    tagName: "span",
    value: description,
    onChange: value => {
      setAttributes({
        description: value
      });
    },
    placeholder: edit_('Description…', 'evie-xt')
  }))), navigation && (0,react.createElement)("div", {
    className: "post-carousel-navigation"
  }, (0,react.createElement)("div", {
    className: "flext-button-prev"
  }), (0,react.createElement)("div", {
    className: "flext-button-next"
  }))), (0,react.createElement)(edit_ServerSideRender, {
    block: "evie/post-carousel",
    attributes: {
      ...attributes,
      isEditMode: true
    },
    clientId: clientId,
    onChange: onChange,
    className: "post-carousel-wrapper"
  })));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/post-carousel/block.js

/**
 * Internal dependencies
 */



/**
 * WordPress dependencies
 */
const {
  registerBlockType: block_registerBlockType,
  unregisterBlockType
} = wp.blocks;
const {
  name: post_carousel_block_name
} = post_carousel_block_namespaceObject;
const block_settings = {
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
block_registerBlockType({
  name: post_carousel_block_name,
  ...post_carousel_block_namespaceObject
}, block_settings);
wp.domReady(() => {
  /**
   * Removes the Flextension Post Carousel block and use the one from this plugin instead.
   *
   * @since 1.0.7
   */
  unregisterBlockType('flextension/post-carousel');
});
;// CONCATENATED MODULE: ./src/modules/elements/blocks/posts/block.json
var posts_block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"evie/posts","title":"Posts","description":"Displays your posts.","category":"flextension","textdomain":"evie-xt","keywords":["post","blog","portfolio","project"],"supports":{"html":false,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"blockId":{"type":"string","default":""},"className":{"type":"string"},"layout":{"type":"string","default":"list"},"postStyle":{"type":"string","default":"card"},"hoverEffect":{"type":"string","default":"none"},"columns":{"type":"number","default":3},"parallax":{"type":"boolean","default":false},"animation":{"type":"string","default":""},"pagination":{"type":"string","default":""},"link":{"type":"string","default":""},"linkText":{"type":"string","default":"See all"},"linkURL":{"type":"string","default":""},"displayTitle":{"type":"boolean","default":true},"displayCategory":{"type":"boolean","default":true},"displayAuthor":{"type":"boolean","default":true},"displayDate":{"type":"boolean","default":true},"displayButtons":{"type":"boolean","default":true},"page":{"type":"number","default":1},"query":{"type":"object","default":{"postType":"post","posts":[],"taxonomy":"","terms":[],"author":"","authors":[],"timeRange":"","orderBy":"date","order":"DESC","numberOfItems":10}}},"editorStyle":"evie-edit-blocks","editorScript":"evie-edit-blocks","style":"evie-blocks","viewScript":"evie-blocks"}');
// EXTERNAL MODULE: ./node_modules/colcade/colcade.js
var colcade = __webpack_require__(1594);
var colcade_default = /*#__PURE__*/__webpack_require__.n(colcade);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/posts/edit.js

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
  __: posts_edit_
} = wp.i18n;
const {
  InspectorControls: posts_edit_InspectorControls,
  useBlockProps: posts_edit_useBlockProps
} = wp.blockEditor;
const {
  Fragment: posts_edit_Fragment,
  useEffect: posts_edit_useEffect
} = wp.element;
const {
  PanelBody: posts_edit_PanelBody,
  RangeControl: edit_RangeControl,
  SelectControl: posts_edit_SelectControl,
  TextControl: edit_TextControl,
  ToggleControl: posts_edit_ToggleControl
} = wp.components;
const {
  flextension: posts_edit_flextension
} = window;
const {
  QueryControls: posts_edit_QueryControls,
  ServerSideRender: posts_edit_ServerSideRender
} = posts_edit_flextension.editor.components;
function PostsEdit(props) {
  const {
    attributes,
    setAttributes,
    clientId
  } = props;
  const {
    blockId,
    layout,
    postStyle,
    hoverEffect,
    columns,
    parallax,
    animation,
    pagination,
    link,
    linkText,
    linkURL,
    query,
    displayTitle,
    displayCategory,
    displayAuthor,
    displayDate,
    displayButtons
  } = attributes;
  posts_edit_useEffect(() => {
    setAttributes({
      blockId: clientId
    });
  }, [blockId, clientId]);
  const blockProps = posts_edit_useBlockProps();
  const onChange = content => {
    if (!content) {
      return;
    }
    const grid = content.querySelector('.posts-layout-waterfall .grid-columns');
    if (grid !== null) {
      new (colcade_default())(grid, {
        columns: '.grid-column',
        items: '.entry'
      });
    }
  };
  const showGridOptions = ['grid', 'waterfall'].includes(layout);
  return (0,react.createElement)(posts_edit_Fragment, null, (0,react.createElement)(posts_edit_InspectorControls, null, (0,react.createElement)(posts_edit_PanelBody, null, (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Layout', 'evie-xt'),
    options: [{
      value: 'large',
      label: posts_edit_('Large', 'evie-xt')
    }, {
      value: 'list',
      label: posts_edit_('List', 'evie-xt')
    }, {
      value: 'grid',
      label: posts_edit_('Grid', 'evie-xt')
    }, {
      value: 'waterfall',
      label: posts_edit_('Waterfall', 'evie-xt')
    }],
    value: layout,
    onChange: value => setAttributes({
      layout: value
    })
  }), 'waterfall' === layout && (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Parallax Columns', 'evie-xt'),
    checked: parallax,
    onChange: value => setAttributes({
      parallax: value
    })
  }), showGridOptions && (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Style', 'evie-xt'),
    options: POST_STYLES,
    value: postStyle,
    onChange: value => setAttributes({
      postStyle: value
    })
  }), showGridOptions && (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Hover effect', 'evie-xt'),
    options: HOVER_EFFECTS,
    value: hoverEffect,
    onChange: value => setAttributes({
      hoverEffect: value
    })
  }), showGridOptions && (0,react.createElement)(edit_RangeControl, {
    label: posts_edit_('Columns', 'evie-xt'),
    value: columns,
    onChange: value => {
      setAttributes({
        columns: value
      });
    },
    min: 2,
    max: 3,
    required: true
  }), (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Animation', 'evie-xt'),
    options: REVEAL_ANIMATIONS,
    value: animation,
    onChange: value => setAttributes({
      animation: value
    })
  }), (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Pagination', 'evie-xt'),
    options: PAGINATION_OPTIONS,
    value: pagination,
    onChange: value => setAttributes({
      pagination: value
    })
  }), (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Display title', 'evie-xt'),
    checked: displayTitle,
    onChange: value => setAttributes({
      displayTitle: value
    })
  }), (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Display category', 'evie-xt'),
    checked: displayCategory,
    onChange: value => setAttributes({
      displayCategory: value
    })
  }), (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Display author', 'evie-xt'),
    checked: displayAuthor,
    onChange: value => setAttributes({
      displayAuthor: value
    })
  }), (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Display date', 'evie-xt'),
    checked: displayDate,
    onChange: value => setAttributes({
      displayDate: value
    })
  }), (0,react.createElement)(posts_edit_ToggleControl, {
    label: posts_edit_('Display Likes, Share and Comments', 'evie-xt'),
    checked: displayButtons,
    onChange: value => setAttributes({
      displayButtons: value
    })
  })), (pagination === '' || pagination === 'none') && (0,react.createElement)(posts_edit_PanelBody, {
    title: posts_edit_('Link settings', 'evie-xt')
  }, (0,react.createElement)(posts_edit_SelectControl, {
    label: posts_edit_('Link to', 'evie-xt'),
    options: [{
      value: '',
      label: posts_edit_('None', 'evie-xt')
    }, {
      value: 'archive',
      label: posts_edit_('Archive page (all posts)', 'evie-xt')
    }, {
      value: 'more',
      label: posts_edit_('More similar posts', 'evie-xt')
    }, {
      value: 'custom',
      label: posts_edit_('Custom', 'evie-xt')
    }],
    value: link,
    onChange: value => setAttributes({
      link: value
    })
  }), link && (0,react.createElement)(edit_TextControl, {
    label: posts_edit_('Link text', 'evie-xt'),
    value: linkText,
    onChange: value => setAttributes({
      linkText: value
    })
  }), link === 'custom' && (0,react.createElement)(edit_TextControl, {
    label: posts_edit_('URL', 'evie-xt'),
    type: "url",
    value: linkURL,
    onChange: value => setAttributes({
      linkURL: value
    })
  })), (0,react.createElement)(posts_edit_PanelBody, {
    title: posts_edit_('Sorting and filtering', 'evie-xt')
  }, (0,react.createElement)(posts_edit_QueryControls, {
    postTypes: [{
      value: 'post',
      label: posts_edit_('Post', 'evie-xt')
    }, {
      value: 'project',
      label: posts_edit_('Project', 'evie-xt')
    }],
    value: query,
    onChange: value => setAttributes({
      query: value
    })
  }))), (0,react.createElement)("div", blockProps, (0,react.createElement)(posts_edit_ServerSideRender, {
    block: "evie/posts",
    clientId: clientId,
    attributes: attributes,
    onChange: onChange
  })));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/posts/block.js

/**
 * Internal dependencies
 */



/**
 * WordPress dependencies
 */
const {
  registerBlockType: posts_block_registerBlockType
} = wp.blocks;
const {
  name: posts_block_name
} = posts_block_namespaceObject;
const posts_block_settings = {
  icon: (0,react.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24"
  }, (0,react.createElement)("path", {
    d: "M3,3.3v12h18v-12H3z M18.9,14.1c-1.2-0.4-2.3-0.8-3.4-1.3c1.2-0.5,2.6-0.9,4.1-0.9c0.1,0,0.1,0,0.2,0v2.1H18.9z M19.8,4.5 v6.1c-0.1,0-0.2,0-0.2,0c-2.3,0-4.4,0.8-5.8,1.5c-1.9-0.7-3.9-1.4-5.7-1.4c-1.3,0-2.6,0.3-3.9,0.7v-7H19.8z M4.2,12.8 c0,0,0.1,0,0.1,0c1.3-0.5,2.5-0.7,3.7-0.7c1.7,0,3.7,0.7,5.5,1.5l0.9,0.3c0.2,0.1,0.4,0.1,0.5,0.2H4.2V12.8z M6.5,8.6h2.4 c0.7,0,1.3-0.6,1.3-1.3c-0.1-0.7-0.5-1.1-1.1-1.4C8.9,5.5,8.3,5.2,7.7,5.2C7.2,5.2,6.6,5.5,6.3,6c-0.7,0.2-1,0.7-1,1.3 C5.2,8,5.8,8.6,6.5,8.6z M6.4,6.8h0.3c0,0,0.1-0.1,0.1-0.1C7,6.3,7.3,6.1,7.6,6.1s0.7,0.2,0.8,0.6c0,0,0.1,0.1,0.2,0.1 c0,0,0.1,0,0.2,0c0.3,0,0.5,0.2,0.5,0.5c0,0.2-0.1,0.5-0.5,0.5H6.5C6.3,7.8,6,7.5,6,7.3C6,7,6.3,6.8,6.4,6.8z M4.9,17.3v1.2H3v-1.2 H4.9z M6.8,17.3h11.4v1.2H6.8V17.3z M3,19.9h6.7v1.2H3V19.9z M11.6,19.9h1.9v1.2h-1.9V19.9z"
  })),
  getEditWrapperProps() {
    // This block always displays the content in full width layout.
    return {
      'data-align': 'full'
    };
  },
  edit: PostsEdit
};

/**
 * Registers a block from metadata.
 *
 * @param {string} name     Block name.
 * @param {Object} settings Block settings.
 * @return {?WPBlock} The block, if it has been successfully registered;
 *                    otherwise `undefined`.
 */
posts_block_registerBlockType({
  name: posts_block_name,
  ...posts_block_namespaceObject
}, posts_block_settings);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/block.json
var scrolling_text_block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"evie/scrolling-text","title":"Scrolling Text","description":"Adds a scrolling area of text.","category":"flextension","textdomain":"evie-xt","keywords":["title","marquee"],"supports":{"anchor":true,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"align":{"type":"string","default":"full"},"content":{"type":"string","source":"html","selector":".scrolling-text","default":""},"level":{"type":"number","default":2},"hasMedia":{"type":"boolean","default":false},"fontSize":{"type":"string"},"textColor":{"type":"string"},"customTextColor":{"type":"string"},"direction":{"type":"string","default":"rtl"},"placeholder":{"type":"string"}},"editorStyle":"evie-edit-blocks","editorScript":"evie-edit-blocks","style":"evie-blocks","viewScript":"evie-blocks"}');
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
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/button-media-appender.js


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */
const {
  createHigherOrderComponent
} = wp.compose;
const {
  useBlockEditContext
} = wp.blockEditor;
const {
  Button,
  Tooltip,
  VisuallyHidden
} = wp.components;
const {
  forwardRef
} = wp.element;
const {
  __: button_media_appender_,
  sprintf
} = wp.i18n;
const {
  Inserter
} = wp.blockEditor;
function ButtonBlockAppender(_ref, ref) {
  let {
    rootClientId,
    className,
    label,
    __experimentalSelectBlockOnInsert: selectBlockOnInsert,
    onFocus,
    tabIndex
  } = _ref;
  return (0,react.createElement)(Inserter, {
    position: "bottom center",
    rootClientId: rootClientId,
    __experimentalSelectBlockOnInsert: selectBlockOnInsert,
    __experimentalIsQuick: true,
    renderToggle: _ref2 => {
      let {
        onToggle,
        disabled,
        isOpen,
        blockTitle,
        hasSingleBlockType
      } = _ref2;
      let buttonLabel = label;
      if (!buttonLabel) {
        if (hasSingleBlockType) {
          buttonLabel = sprintf(
          // translators: %s: the name of the block when there is only one
          button_media_appender_('Add %s', 'evie-xt'), blockTitle);
        } else {
          buttonLabel = button_media_appender_('Add Media', 'evie-xt');
        }
      }
      const isToggleButton = !hasSingleBlockType;
      let inserterButton = (0,react.createElement)(Button, {
        ref: ref,
        onFocus: onFocus,
        tabIndex: tabIndex,
        className: classnames_default()(className, 'block-editor-button-block-appender'),
        onClick: onToggle,
        "aria-haspopup": isToggleButton ? 'true' : undefined,
        "aria-expanded": isToggleButton ? isOpen : undefined,
        disabled: disabled,
        label: buttonLabel,
        icon: "plus"
      }, !hasSingleBlockType && (0,react.createElement)(VisuallyHidden, {
        as: "span"
      }, buttonLabel));
      if (isToggleButton || hasSingleBlockType) {
        inserterButton = (0,react.createElement)(Tooltip, {
          text: buttonLabel
        }, inserterButton);
      }
      return inserterButton;
    },
    isAppender: true
  });
}
const BaseButtonBlockAppender = forwardRef(ButtonBlockAppender);
const withClientId = createHigherOrderComponent(WrappedComponent => props => {
  const {
    clientId
  } = useBlockEditContext();
  return (0,react.createElement)(WrappedComponent, _extends({}, props, {
    clientId: clientId
  }));
}, 'withClientId');
const ButtonMediaAppender = _ref3 => {
  let {
    clientId,
    showSeparator,
    isFloating,
    onAddBlock
  } = _ref3;
  return (0,react.createElement)(BaseButtonBlockAppender, {
    className: "evie-button-media-appender",
    label: button_media_appender_('Add Overlay', 'evie-xt'),
    rootClientId: clientId,
    showSeparator: showSeparator,
    isFloating: isFloating,
    onAddBlock: onAddBlock
  });
};
/* harmony default export */ var button_media_appender = (withClientId(ButtonMediaAppender));
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/edit.js

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
  __: scrolling_text_edit_
} = wp.i18n;
const {
  useEffect: scrolling_text_edit_useEffect
} = wp.element;
const {
  createBlock
} = wp.blocks;
const {
  BlockControls,
  PanelColorSettings,
  InnerBlocks,
  InspectorControls: scrolling_text_edit_InspectorControls,
  RichText: edit_RichText,
  useBlockProps: scrolling_text_edit_useBlockProps,
  useSetting,
  withColors
} = wp.blockEditor;
const {
  useSelect
} = wp.data;
const {
  PanelBody: scrolling_text_edit_PanelBody,
  SelectControl: scrolling_text_edit_SelectControl,
  ToolbarGroup
} = wp.components;

/**
 * Flextension
 */
const {
  flextension: scrolling_text_edit_flextension
} = window;
const {
  HeadingLevelDropdown
} = scrolling_text_edit_flextension.editor.components;

/**
 * Allowed blocks constant is passed to InnerBlocks precisely as specified here.
 * The contents of the array should never change.
 * The array should contain the name of each block that is allowed.
 *
 * @constant
 *
 * @type {string[]}
 */
const ALLOWED_BLOCKS = ['core/image', 'core/video'];
function ScrollingTextEdit(_ref) {
  let {
    attributes,
    clientId,
    textColor,
    setTextColor,
    setAttributes,
    mergeBlocks,
    onReplace,
    isSelected
  } = _ref;
  const {
    fontSize = '',
    direction,
    content,
    level,
    hasMedia,
    placeholder
  } = attributes;
  const TagName = 'h' + level;
  const hasChildBlocks = useSelect(select => {
    const {
      getBlockOrder
    } = select('core/block-editor');
    return getBlockOrder(clientId).length > 0;
  }, [clientId]);
  scrolling_text_edit_useEffect(() => {
    setAttributes({
      hasMedia: hasChildBlocks
    });
  }, [hasMedia, hasChildBlocks]);
  const defaultLayout = useSetting('layout') || {};
  const blockProps = scrolling_text_edit_useBlockProps({
    className: classnames_default()(textColor.class, {
      'has-featured-media': hasMedia,
      [`has-${fontSize}-font-size`]: fontSize,
      [`scroll-dir-${direction}`]: direction,
      'is-selected': isSelected
    }),
    style: {
      color: textColor.color
    }
  });
  return (0,react.createElement)(react.Fragment, null, (0,react.createElement)(BlockControls, null, (0,react.createElement)(ToolbarGroup, null, (0,react.createElement)(HeadingLevelDropdown, {
    selectedLevel: level,
    onChange: value => setAttributes({
      level: value
    })
  }))), (0,react.createElement)(scrolling_text_edit_InspectorControls, null, (0,react.createElement)(scrolling_text_edit_PanelBody, null, (0,react.createElement)(scrolling_text_edit_SelectControl, {
    label: scrolling_text_edit_('Direction', 'evie-xt'),
    options: [{
      value: 'rtl',
      label: scrolling_text_edit_('Slide to left', 'evie-xt')
    }, {
      value: 'ltr',
      label: scrolling_text_edit_('Slide to right', 'evie-xt')
    }],
    value: direction,
    onChange: value => setAttributes({
      direction: value
    })
  }), (0,react.createElement)(scrolling_text_edit_SelectControl, {
    label: scrolling_text_edit_('Font size', 'evie-xt'),
    options: [{
      value: 'small',
      label: scrolling_text_edit_('Small', 'evie-xt')
    }, {
      value: '',
      label: scrolling_text_edit_('Normal', 'evie-xt')
    }, {
      value: 'large',
      label: scrolling_text_edit_('Large', 'evie-xt')
    }],
    value: fontSize,
    onChange: value => setAttributes({
      fontSize: value
    })
  })), (0,react.createElement)(PanelColorSettings, {
    title: scrolling_text_edit_('Color settings', 'evie-xt'),
    colorSettings: [{
      label: scrolling_text_edit_('Text color', 'evie-xt'),
      value: textColor.color,
      onChange: setTextColor
    }]
  })), (0,react.createElement)("div", blockProps, (0,react.createElement)(TagName, {
    className: "scrolling-area"
  }, (0,react.createElement)(edit_RichText, {
    className: "scrolling-text",
    identifier: "content",
    tagName: "span",
    value: content,
    onChange: value => setAttributes({
      content: value
    }),
    onMerge: mergeBlocks,
    onSplit: value => {
      if (!value) {
        return createBlock('core/paragraph');
      }
      return createBlock('evie/scrolling-text', {
        ...attributes,
        content: value
      });
    },
    onReplace: onReplace,
    onRemove: () => onReplace([]),
    "aria-label": scrolling_text_edit_('Heading text', 'evie-xt'),
    placeholder: placeholder || scrolling_text_edit_('Heading…', 'evie-xt')
  })), (0,react.createElement)(InnerBlocks, {
    templateLock: false,
    allowedBlocks: ALLOWED_BLOCKS,
    renderAppender: hasChildBlocks ? undefined : button_media_appender,
    __experimentalLayout: defaultLayout
  })));
}
/* harmony default export */ var edit = (withColors({
  textColor: 'color'
})(ScrollingTextEdit));
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/save.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */
const {
  getColorClassName,
  InnerBlocks: save_InnerBlocks,
  RichText: save_RichText,
  useBlockProps: save_useBlockProps
} = wp.blockEditor;
function save(_ref) {
  let {
    attributes
  } = _ref;
  const {
    direction,
    fontSize = '',
    textColor,
    customTextColor,
    content,
    level,
    hasMedia
  } = attributes;
  const TagName = 'h' + level;
  const textColorClass = getColorClassName('color', textColor);
  const className = classnames_default()('alignfull', textColorClass, {
    'has-featured-media': hasMedia,
    [`has-${fontSize}-font-size`]: fontSize,
    [`scroll-dir-${direction}`]: direction
  });
  const style = {};
  if (!textColorClass) {
    style.color = customTextColor;
  }
  return (0,react.createElement)("div", save_useBlockProps.save({
    className,
    style
  }), (0,react.createElement)(TagName, {
    className: "scrolling-area"
  }, (0,react.createElement)("span", {
    className: "scrolling-text"
  }, (0,react.createElement)(save_RichText.Content, {
    value: content
  }))), (0,react.createElement)(save_InnerBlocks.Content, null));
}
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/transforms.js
/**
 * WordPress dependencies
 */
const {
  createBlock: transforms_createBlock
} = wp.blocks;
const transforms_name = 'evie/scrolling-text';
const transforms = {
  from: [{
    type: 'block',
    isMultiBlock: true,
    blocks: ['core/paragraph'],
    transform: attributes => attributes.map(_ref => {
      let {
        content,
        anchor
      } = _ref;
      return transforms_createBlock(transforms_name, {
        content,
        anchor
      });
    })
  }, {
    type: 'block',
    isMultiBlock: true,
    blocks: ['core/heading'],
    transform: attributes => attributes.map(_ref2 => {
      let {
        content,
        level,
        placeholder,
        anchor
      } = _ref2;
      return transforms_createBlock(transforms_name, {
        content,
        level,
        placeholder,
        anchor
      });
    })
  }],
  to: [{
    type: 'block',
    isMultiBlock: true,
    blocks: ['core/paragraph'],
    transform: attributes => attributes.map(_ref3 => {
      let {
        content,
        anchor
      } = _ref3;
      return transforms_createBlock('core/paragraph', {
        content,
        anchor
      });
    })
  }, {
    type: 'block',
    isMultiBlock: true,
    blocks: ['core/heading'],
    transform: attributes => attributes.map(_ref4 => {
      let {
        content,
        anchor,
        level,
        placeholder
      } = _ref4;
      return transforms_createBlock('core/heading', {
        content,
        level,
        placeholder,
        anchor
      });
    })
  }]
};
/* harmony default export */ var scrolling_text_transforms = (transforms);
;// CONCATENATED MODULE: ./src/modules/elements/blocks/scrolling-text/block.js

/**
 * Internal dependencies
 */





/**
 * WordPress dependencies
 */
const {
  __: block_
} = wp.i18n;
const {
  registerBlockType: scrolling_text_block_registerBlockType
} = wp.blocks;
const {
  addFilter
} = wp.hooks;
const {
  name: scrolling_text_block_name
} = scrolling_text_block_namespaceObject;
const scrolling_text_block_settings = {
  icon: (0,react.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24"
  }, (0,react.createElement)("path", {
    d: "M9.2,13.3h2.2v1.4H9.2V13.3z M23.3,13.6L21.8,12c-0.3-0.3-0.7-0.3-0.9,0c-0.3,0.3-0.3,0.7,0,0.9l1.1,1.1l-1.1,1.1 c-0.3,0.3-0.3,0.7,0,0.9h0c0.3,0.3,0.7,0.3,0.9,0l1.5-1.5c0.1-0.1,0.2-0.3,0.2-0.5C23.5,13.9,23.5,13.7,23.3,13.6z M23.5,9.2v1.4 h-5.5v2.7h2.1v1.4h-2.1v3.7H5.9v-3.7H0.5v-1.4h5.5v-2.7H3.9V9.2h2.1V5.6h12.1v3.7H23.5z M16.9,10.6h-0.7V9.2h0.7V6.8H7.1v2.5h3.9 v1.4H7.1v2.7h0.7v1.4H7.1v2.5h9.7v-2.5h-3.9v-1.4h3.9V10.6z M12.6,10.6h2.2V9.2h-2.2V10.6z M0.7,10.4L2.2,12c0.3,0.3,0.7,0.3,0.9,0 h0c0.3-0.3,0.3-0.7,0-0.9L2.1,10l1.1-1.1c0.3-0.3,0.3-0.7,0-0.9c-0.3-0.3-0.7-0.3-0.9,0L0.7,9.5C0.5,9.6,0.5,9.8,0.5,10 C0.5,10.1,0.5,10.3,0.7,10.4z"
  })),
  example: {
    attributes: {
      content: block_('Scrolling Text', 'evie-xt'),
      level: 2
    }
  },
  getEditWrapperProps() {
    // This block always displays the content in full-width layout.
    return {
      'data-align': 'full'
    };
  },
  transforms: scrolling_text_transforms,
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
scrolling_text_block_registerBlockType({
  name: scrolling_text_block_name,
  ...scrolling_text_block_namespaceObject
}, scrolling_text_block_settings);

/**
 * Sets a new class name for the block.
 *
 * @param {string} className Block class name.
 * @param {string} blockName Block name.
 * @return {string} Block class name.
 */
function setBlockClassName(className, blockName) {
  return blockName === scrolling_text_block_name ? 'evie-block-scrolling-text' : className;
}
addFilter('blocks.getBlockDefaultClassName', 'evie/scrolling-text-block-class-name', setBlockClassName);
;// CONCATENATED MODULE: ./src/modules/elements/js/blocks.js
/**
 * Internal dependencies
 */




wp.domReady(() => {
  /**
   * WordPress dependencies
   */
  const {
    __
  } = wp.i18n;
  const {
    registerBlockStyle
  } = wp.blocks;

  /**
   * Registers new styles for the Button block.
   */
  registerBlockStyle('core/button', [{
    name: 'evie-link',
    label: __('Link', 'evie-xt')
  }, {
    name: 'evie-circle',
    label: __('Circle Before', 'evie-xt')
  }, {
    name: 'evie-circle-after',
    label: __('Circle After', 'evie-xt')
  }]);

  /**
   * Registers a new style for the Table block.
   */
  registerBlockStyle('core/table', {
    name: 'evie-list',
    label: __('List', 'evie-xt')
  });
});
}();
/******/ })()
;