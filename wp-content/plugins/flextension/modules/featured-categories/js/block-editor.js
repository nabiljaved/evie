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
;// CONCATENATED MODULE: ./src/modules/featured-categories/blocks/categories/block.json
var block_namespaceObject = JSON.parse('{"apiVersion":2,"name":"flextension/categories","title":"Featured Categories","description":"Displays featured categories.","category":"flextension","textdomain":"flextension","keywords":["categories"],"supports":{"anchor":true,"align":["center","wide","full"],"html":false,"flextensionAnimation":true,"flextensionSpacing":true,"flextensionVisibility":true},"attributes":{"align":{"type":"string","default":"center"},"className":{"type":"string"},"layout":{"type":"string","default":""},"textAlign":{"type":"string","default":""},"columns":{"type":"number","default":4},"navigation":{"type":"boolean","default":true},"pagination":{"type":"boolean","default":false},"taxonomy":{"type":"string","default":"category"},"display":{"type":"string","enum":["all","custom"],"default":"all"},"terms":{"type":"array","default":[]},"showPostCounts":{"type":"boolean","default":true},"showImage":{"type":"boolean","default":true},"imageSize":{"type":"string","default":""}},"editorStyle":"flextension-categories","editorScript":"flextension-categories-block-editor","style":"flextension-categories"}');
;// CONCATENATED MODULE: ./src/modules/featured-categories/blocks/categories/edit.js

/**
 * WordPress dependencies
 */
const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  RangeControl,
  SelectControl,
  ToggleControl
} = wp.components;
const {
  AlignmentControl,
  BlockControls,
  InspectorControls,
  useBlockProps
} = wp.blockEditor;
const {
  withSelect
} = wp.data;
const {
  flextension
} = window;
const {
  ServerSideRender,
  HierarchicalTermSelector
} = flextension.editor.components;
function CategoriesEdit(props) {
  const {
    attributes,
    setAttributes,
    taxonomyOptions = [],
    imageSizeOptions = [],
    clientId
  } = props;
  const {
    layout,
    textAlign,
    columns,
    navigation,
    pagination,
    taxonomy = 'category',
    display,
    terms = [],
    showPostCounts,
    showImage,
    imageSize
  } = attributes;
  const blockProps = useBlockProps();
  if (taxonomyOptions.length === 0) {
    return '';
  }
  const onChange = content => {
    if (!content) {
      return;
    }
    const element = content.querySelector('.flext-carousel');
    if (element !== null) {
      const options = {
        simulateTouch: false
      };
      new flextension.carousel(element, options);
    }
  };
  return (0,react.createElement)(Fragment, null, (0,react.createElement)(InspectorControls, null, (0,react.createElement)(PanelBody, null, (0,react.createElement)(SelectControl, {
    label: __('Type', 'flextension'),
    options: [{
      value: '',
      label: __('Plain', 'flextension')
    }, {
      value: 'grid',
      label: __('Grid', 'flextension')
    }, {
      value: 'carousel',
      label: __('Carousel', 'flextension')
    }],
    value: layout,
    onChange: value => setAttributes({
      layout: value
    })
  }), layout !== '' && (0,react.createElement)(RangeControl, {
    label: __('Columns', 'flextension'),
    value: columns,
    onChange: value => {
      setAttributes({
        columns: value
      });
    },
    min: 1,
    max: 8,
    required: true
  }), layout === 'carousel' && (0,react.createElement)(ToggleControl, {
    label: __('Navigation', 'flextension'),
    checked: navigation,
    onChange: value => setAttributes({
      navigation: value
    })
  }), layout === 'carousel' && (0,react.createElement)(ToggleControl, {
    label: __('Pagination', 'flextension'),
    checked: pagination,
    onChange: value => setAttributes({
      pagination: value
    })
  }), (0,react.createElement)(ToggleControl, {
    label: __('Display post counts', 'flextension'),
    checked: showPostCounts,
    onChange: value => setAttributes({
      showPostCounts: value
    })
  }), layout && (0,react.createElement)(ToggleControl, {
    label: __('Display featured image', 'flextension'),
    checked: showImage,
    onChange: value => setAttributes({
      showImage: value
    })
  }), layout && showImage === true && (0,react.createElement)(SelectControl, {
    label: __('Image size', 'flextension'),
    options: imageSizeOptions,
    value: imageSize,
    onChange: value => setAttributes({
      imageSize: value
    })
  })), (0,react.createElement)(PanelBody, {
    title: __('Sorting and filtering', 'flextension')
  }, (0,react.createElement)(SelectControl, {
    label: __('Taxonomy', 'flextension'),
    options: taxonomyOptions,
    value: taxonomy,
    onChange: value => setAttributes({
      taxonomy: value,
      terms: []
    })
  }), (0,react.createElement)(SelectControl, {
    label: __('Display', 'flextension'),
    options: [{
      value: 'all',
      label: __('All', 'flextension')
    }, {
      value: 'custom',
      label: __('Custom', 'flextension')
    }],
    value: display,
    onChange: value => setAttributes({
      display: value
    })
  }), taxonomy && 'custom' === display && (0,react.createElement)(HierarchicalTermSelector, {
    taxonomy: taxonomy,
    terms: terms,
    onChange: value => setAttributes({
      terms: value
    })
  }))), layout === '' && (0,react.createElement)(BlockControls, {
    group: "block"
  }, (0,react.createElement)(AlignmentControl, {
    value: textAlign,
    onChange: nextAlign => {
      setAttributes({
        textAlign: nextAlign
      });
    }
  })), (0,react.createElement)("div", blockProps, (0,react.createElement)(ServerSideRender, {
    block: "flextension/categories",
    attributes: attributes,
    clientId: clientId,
    onChange: onChange
  })));
}
/* harmony default export */ var edit = (withSelect((select, props) => {
  const {
    taxonomies
  } = props;
  const {
    getSettings
  } = select('core/block-editor');
  const {
    imageSizes
  } = getSettings();
  const imageSizeOptions = imageSizes.filter(_ref => {
    let {
      slug
    } = _ref;
    return slug !== 'full';
  }).map(_ref2 => {
    let {
      name,
      slug
    } = _ref2;
    return {
      value: slug,
      label: name
    };
  });
  return {
    taxonomyOptions: taxonomies || window.flextensionCategoriesOptions || [],
    imageSizeOptions
  };
})(CategoriesEdit));
;// CONCATENATED MODULE: ./src/modules/featured-categories/blocks/categories/index.js

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
  addFilter
} = wp.hooks;
const {
  name: categories_name
} = block_namespaceObject;
const settings = {
  icon: (0,react.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24"
  }, (0,react.createElement)("path", {
    d: "M20.5,7.2h-3.3l-2-2c-0.1-0.1-0.2-0.2-0.4-0.2h-3.5H3.5C3.2,5.1,3,5.4,3,5.6v12.7c0,0.3,0.2,0.5,0.5,0.5h16.9 c0.3,0,0.5-0.2,0.5-0.5V7.8C21,7.5,20.8,7.2,20.5,7.2z M15.7,7.2h-2l-1.1-1.1h2L15.7,7.2z M11.1,6.2l1.1,1.1h-2L9,6.2H11.1z M19.9,17.8H4.1V6.2h3.5l2,2c0.1,0.1,0.2,0.2,0.4,0.2h10.1V17.8z M13.6,14.1v2.1c0,0.3,0.2,0.5,0.5,0.5h4.2c0.3,0,0.5-0.2,0.5-0.5 v-2.1c0-0.3-0.2-0.5-0.5-0.5h-4.2C13.8,13.6,13.6,13.8,13.6,14.1z M14.6,14.6h3.2v1.1h-3.2V14.6z"
  })),
  example: {},
  edit: edit
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
  name: categories_name,
  ...block_namespaceObject
}, settings);

/**
 * Sets a new class name for the block.
 *
 * @param {string} className Block class name.
 * @param {string} blockName Block name.
 * @return {string} Block class name.
 */
function setBlockClassName(className, blockName) {
  return blockName === categories_name ? 'flext-block-categories' : className;
}
addFilter('blocks.getBlockDefaultClassName', 'flextension/set-categories-block-class-name', setBlockClassName);
;// CONCATENATED MODULE: ./src/modules/featured-categories/js/block-editor.js
/**
 * Categories Blocks
 *
 * @author  Wyde
 * @version 1.0.0
 */



/**
 * Internal dependencies
 */

}();
/******/ })()
;