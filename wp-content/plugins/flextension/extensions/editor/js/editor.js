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

/***/ 8552:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852),
    root = __webpack_require__(5639);

/* Built-in method references that are verified to be native. */
var DataView = getNative(root, 'DataView');

module.exports = DataView;


/***/ }),

/***/ 1989:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var hashClear = __webpack_require__(1789),
    hashDelete = __webpack_require__(401),
    hashGet = __webpack_require__(7667),
    hashHas = __webpack_require__(1327),
    hashSet = __webpack_require__(1866);

/**
 * Creates a hash object.
 *
 * @private
 * @constructor
 * @param {Array} [entries] The key-value pairs to cache.
 */
function Hash(entries) {
  var index = -1,
      length = entries == null ? 0 : entries.length;

  this.clear();
  while (++index < length) {
    var entry = entries[index];
    this.set(entry[0], entry[1]);
  }
}

// Add methods to `Hash`.
Hash.prototype.clear = hashClear;
Hash.prototype['delete'] = hashDelete;
Hash.prototype.get = hashGet;
Hash.prototype.has = hashHas;
Hash.prototype.set = hashSet;

module.exports = Hash;


/***/ }),

/***/ 8407:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var listCacheClear = __webpack_require__(7040),
    listCacheDelete = __webpack_require__(4125),
    listCacheGet = __webpack_require__(2117),
    listCacheHas = __webpack_require__(7518),
    listCacheSet = __webpack_require__(4705);

/**
 * Creates an list cache object.
 *
 * @private
 * @constructor
 * @param {Array} [entries] The key-value pairs to cache.
 */
function ListCache(entries) {
  var index = -1,
      length = entries == null ? 0 : entries.length;

  this.clear();
  while (++index < length) {
    var entry = entries[index];
    this.set(entry[0], entry[1]);
  }
}

// Add methods to `ListCache`.
ListCache.prototype.clear = listCacheClear;
ListCache.prototype['delete'] = listCacheDelete;
ListCache.prototype.get = listCacheGet;
ListCache.prototype.has = listCacheHas;
ListCache.prototype.set = listCacheSet;

module.exports = ListCache;


/***/ }),

/***/ 7071:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852),
    root = __webpack_require__(5639);

/* Built-in method references that are verified to be native. */
var Map = getNative(root, 'Map');

module.exports = Map;


/***/ }),

/***/ 3369:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var mapCacheClear = __webpack_require__(4785),
    mapCacheDelete = __webpack_require__(1285),
    mapCacheGet = __webpack_require__(6000),
    mapCacheHas = __webpack_require__(9916),
    mapCacheSet = __webpack_require__(5265);

/**
 * Creates a map cache object to store key-value pairs.
 *
 * @private
 * @constructor
 * @param {Array} [entries] The key-value pairs to cache.
 */
function MapCache(entries) {
  var index = -1,
      length = entries == null ? 0 : entries.length;

  this.clear();
  while (++index < length) {
    var entry = entries[index];
    this.set(entry[0], entry[1]);
  }
}

// Add methods to `MapCache`.
MapCache.prototype.clear = mapCacheClear;
MapCache.prototype['delete'] = mapCacheDelete;
MapCache.prototype.get = mapCacheGet;
MapCache.prototype.has = mapCacheHas;
MapCache.prototype.set = mapCacheSet;

module.exports = MapCache;


/***/ }),

/***/ 3818:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852),
    root = __webpack_require__(5639);

/* Built-in method references that are verified to be native. */
var Promise = getNative(root, 'Promise');

module.exports = Promise;


/***/ }),

/***/ 8525:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852),
    root = __webpack_require__(5639);

/* Built-in method references that are verified to be native. */
var Set = getNative(root, 'Set');

module.exports = Set;


/***/ }),

/***/ 8668:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var MapCache = __webpack_require__(3369),
    setCacheAdd = __webpack_require__(619),
    setCacheHas = __webpack_require__(2385);

/**
 *
 * Creates an array cache object to store unique values.
 *
 * @private
 * @constructor
 * @param {Array} [values] The values to cache.
 */
function SetCache(values) {
  var index = -1,
      length = values == null ? 0 : values.length;

  this.__data__ = new MapCache;
  while (++index < length) {
    this.add(values[index]);
  }
}

// Add methods to `SetCache`.
SetCache.prototype.add = SetCache.prototype.push = setCacheAdd;
SetCache.prototype.has = setCacheHas;

module.exports = SetCache;


/***/ }),

/***/ 6384:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var ListCache = __webpack_require__(8407),
    stackClear = __webpack_require__(7465),
    stackDelete = __webpack_require__(3779),
    stackGet = __webpack_require__(7599),
    stackHas = __webpack_require__(4758),
    stackSet = __webpack_require__(4309);

/**
 * Creates a stack cache object to store key-value pairs.
 *
 * @private
 * @constructor
 * @param {Array} [entries] The key-value pairs to cache.
 */
function Stack(entries) {
  var data = this.__data__ = new ListCache(entries);
  this.size = data.size;
}

// Add methods to `Stack`.
Stack.prototype.clear = stackClear;
Stack.prototype['delete'] = stackDelete;
Stack.prototype.get = stackGet;
Stack.prototype.has = stackHas;
Stack.prototype.set = stackSet;

module.exports = Stack;


/***/ }),

/***/ 2705:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var root = __webpack_require__(5639);

/** Built-in value references. */
var Symbol = root.Symbol;

module.exports = Symbol;


/***/ }),

/***/ 1149:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var root = __webpack_require__(5639);

/** Built-in value references. */
var Uint8Array = root.Uint8Array;

module.exports = Uint8Array;


/***/ }),

/***/ 577:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852),
    root = __webpack_require__(5639);

/* Built-in method references that are verified to be native. */
var WeakMap = getNative(root, 'WeakMap');

module.exports = WeakMap;


/***/ }),

/***/ 6874:
/***/ (function(module) {

/**
 * A faster alternative to `Function#apply`, this function invokes `func`
 * with the `this` binding of `thisArg` and the arguments of `args`.
 *
 * @private
 * @param {Function} func The function to invoke.
 * @param {*} thisArg The `this` binding of `func`.
 * @param {Array} args The arguments to invoke `func` with.
 * @returns {*} Returns the result of `func`.
 */
function apply(func, thisArg, args) {
  switch (args.length) {
    case 0: return func.call(thisArg);
    case 1: return func.call(thisArg, args[0]);
    case 2: return func.call(thisArg, args[0], args[1]);
    case 3: return func.call(thisArg, args[0], args[1], args[2]);
  }
  return func.apply(thisArg, args);
}

module.exports = apply;


/***/ }),

/***/ 4174:
/***/ (function(module) {

/**
 * A specialized version of `baseAggregator` for arrays.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} setter The function to set `accumulator` values.
 * @param {Function} iteratee The iteratee to transform keys.
 * @param {Object} accumulator The initial aggregated object.
 * @returns {Function} Returns `accumulator`.
 */
function arrayAggregator(array, setter, iteratee, accumulator) {
  var index = -1,
      length = array == null ? 0 : array.length;

  while (++index < length) {
    var value = array[index];
    setter(accumulator, value, iteratee(value), array);
  }
  return accumulator;
}

module.exports = arrayAggregator;


/***/ }),

/***/ 4963:
/***/ (function(module) {

/**
 * A specialized version of `_.filter` for arrays without support for
 * iteratee shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} predicate The function invoked per iteration.
 * @returns {Array} Returns the new filtered array.
 */
function arrayFilter(array, predicate) {
  var index = -1,
      length = array == null ? 0 : array.length,
      resIndex = 0,
      result = [];

  while (++index < length) {
    var value = array[index];
    if (predicate(value, index, array)) {
      result[resIndex++] = value;
    }
  }
  return result;
}

module.exports = arrayFilter;


/***/ }),

/***/ 7443:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIndexOf = __webpack_require__(2118);

/**
 * A specialized version of `_.includes` for arrays without support for
 * specifying an index to search from.
 *
 * @private
 * @param {Array} [array] The array to inspect.
 * @param {*} target The value to search for.
 * @returns {boolean} Returns `true` if `target` is found, else `false`.
 */
function arrayIncludes(array, value) {
  var length = array == null ? 0 : array.length;
  return !!length && baseIndexOf(array, value, 0) > -1;
}

module.exports = arrayIncludes;


/***/ }),

/***/ 1196:
/***/ (function(module) {

/**
 * This function is like `arrayIncludes` except that it accepts a comparator.
 *
 * @private
 * @param {Array} [array] The array to inspect.
 * @param {*} target The value to search for.
 * @param {Function} comparator The comparator invoked per element.
 * @returns {boolean} Returns `true` if `target` is found, else `false`.
 */
function arrayIncludesWith(array, value, comparator) {
  var index = -1,
      length = array == null ? 0 : array.length;

  while (++index < length) {
    if (comparator(value, array[index])) {
      return true;
    }
  }
  return false;
}

module.exports = arrayIncludesWith;


/***/ }),

/***/ 4636:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseTimes = __webpack_require__(2545),
    isArguments = __webpack_require__(5694),
    isArray = __webpack_require__(1469),
    isBuffer = __webpack_require__(4144),
    isIndex = __webpack_require__(5776),
    isTypedArray = __webpack_require__(6719);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Creates an array of the enumerable property names of the array-like `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @param {boolean} inherited Specify returning inherited property names.
 * @returns {Array} Returns the array of property names.
 */
function arrayLikeKeys(value, inherited) {
  var isArr = isArray(value),
      isArg = !isArr && isArguments(value),
      isBuff = !isArr && !isArg && isBuffer(value),
      isType = !isArr && !isArg && !isBuff && isTypedArray(value),
      skipIndexes = isArr || isArg || isBuff || isType,
      result = skipIndexes ? baseTimes(value.length, String) : [],
      length = result.length;

  for (var key in value) {
    if ((inherited || hasOwnProperty.call(value, key)) &&
        !(skipIndexes && (
           // Safari 9 has enumerable `arguments.length` in strict mode.
           key == 'length' ||
           // Node.js 0.10 has enumerable non-index properties on buffers.
           (isBuff && (key == 'offset' || key == 'parent')) ||
           // PhantomJS 2 has enumerable non-index properties on typed arrays.
           (isType && (key == 'buffer' || key == 'byteLength' || key == 'byteOffset')) ||
           // Skip index properties.
           isIndex(key, length)
        ))) {
      result.push(key);
    }
  }
  return result;
}

module.exports = arrayLikeKeys;


/***/ }),

/***/ 9932:
/***/ (function(module) {

/**
 * A specialized version of `_.map` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function arrayMap(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length,
      result = Array(length);

  while (++index < length) {
    result[index] = iteratee(array[index], index, array);
  }
  return result;
}

module.exports = arrayMap;


/***/ }),

/***/ 2488:
/***/ (function(module) {

/**
 * Appends the elements of `values` to `array`.
 *
 * @private
 * @param {Array} array The array to modify.
 * @param {Array} values The values to append.
 * @returns {Array} Returns `array`.
 */
function arrayPush(array, values) {
  var index = -1,
      length = values.length,
      offset = array.length;

  while (++index < length) {
    array[offset + index] = values[index];
  }
  return array;
}

module.exports = arrayPush;


/***/ }),

/***/ 2908:
/***/ (function(module) {

/**
 * A specialized version of `_.some` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} predicate The function invoked per iteration.
 * @returns {boolean} Returns `true` if any element passes the predicate check,
 *  else `false`.
 */
function arraySome(array, predicate) {
  var index = -1,
      length = array == null ? 0 : array.length;

  while (++index < length) {
    if (predicate(array[index], index, array)) {
      return true;
    }
  }
  return false;
}

module.exports = arraySome;


/***/ }),

/***/ 8470:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var eq = __webpack_require__(7813);

/**
 * Gets the index at which the `key` is found in `array` of key-value pairs.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {*} key The key to search for.
 * @returns {number} Returns the index of the matched value, else `-1`.
 */
function assocIndexOf(array, key) {
  var length = array.length;
  while (length--) {
    if (eq(array[length][0], key)) {
      return length;
    }
  }
  return -1;
}

module.exports = assocIndexOf;


/***/ }),

/***/ 1119:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseEach = __webpack_require__(9881);

/**
 * Aggregates elements of `collection` on `accumulator` with keys transformed
 * by `iteratee` and values set by `setter`.
 *
 * @private
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} setter The function to set `accumulator` values.
 * @param {Function} iteratee The iteratee to transform keys.
 * @param {Object} accumulator The initial aggregated object.
 * @returns {Function} Returns `accumulator`.
 */
function baseAggregator(collection, setter, iteratee, accumulator) {
  baseEach(collection, function(value, key, collection) {
    setter(accumulator, value, iteratee(value), collection);
  });
  return accumulator;
}

module.exports = baseAggregator;


/***/ }),

/***/ 9465:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var defineProperty = __webpack_require__(8777);

/**
 * The base implementation of `assignValue` and `assignMergeValue` without
 * value checks.
 *
 * @private
 * @param {Object} object The object to modify.
 * @param {string} key The key of the property to assign.
 * @param {*} value The value to assign.
 */
function baseAssignValue(object, key, value) {
  if (key == '__proto__' && defineProperty) {
    defineProperty(object, key, {
      'configurable': true,
      'enumerable': true,
      'value': value,
      'writable': true
    });
  } else {
    object[key] = value;
  }
}

module.exports = baseAssignValue;


/***/ }),

/***/ 731:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var SetCache = __webpack_require__(8668),
    arrayIncludes = __webpack_require__(7443),
    arrayIncludesWith = __webpack_require__(1196),
    arrayMap = __webpack_require__(9932),
    baseUnary = __webpack_require__(1717),
    cacheHas = __webpack_require__(4757);

/** Used as the size to enable large array optimizations. */
var LARGE_ARRAY_SIZE = 200;

/**
 * The base implementation of methods like `_.difference` without support
 * for excluding multiple arrays or iteratee shorthands.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {Array} values The values to exclude.
 * @param {Function} [iteratee] The iteratee invoked per element.
 * @param {Function} [comparator] The comparator invoked per element.
 * @returns {Array} Returns the new array of filtered values.
 */
function baseDifference(array, values, iteratee, comparator) {
  var index = -1,
      includes = arrayIncludes,
      isCommon = true,
      length = array.length,
      result = [],
      valuesLength = values.length;

  if (!length) {
    return result;
  }
  if (iteratee) {
    values = arrayMap(values, baseUnary(iteratee));
  }
  if (comparator) {
    includes = arrayIncludesWith;
    isCommon = false;
  }
  else if (values.length >= LARGE_ARRAY_SIZE) {
    includes = cacheHas;
    isCommon = false;
    values = new SetCache(values);
  }
  outer:
  while (++index < length) {
    var value = array[index],
        computed = iteratee == null ? value : iteratee(value);

    value = (comparator || value !== 0) ? value : 0;
    if (isCommon && computed === computed) {
      var valuesIndex = valuesLength;
      while (valuesIndex--) {
        if (values[valuesIndex] === computed) {
          continue outer;
        }
      }
      result.push(value);
    }
    else if (!includes(values, computed, comparator)) {
      result.push(value);
    }
  }
  return result;
}

module.exports = baseDifference;


/***/ }),

/***/ 9881:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseForOwn = __webpack_require__(7816),
    createBaseEach = __webpack_require__(9291);

/**
 * The base implementation of `_.forEach` without support for iteratee shorthands.
 *
 * @private
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array|Object} Returns `collection`.
 */
var baseEach = createBaseEach(baseForOwn);

module.exports = baseEach;


/***/ }),

/***/ 1848:
/***/ (function(module) {

/**
 * The base implementation of `_.findIndex` and `_.findLastIndex` without
 * support for iteratee shorthands.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {Function} predicate The function invoked per iteration.
 * @param {number} fromIndex The index to search from.
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {number} Returns the index of the matched value, else `-1`.
 */
function baseFindIndex(array, predicate, fromIndex, fromRight) {
  var length = array.length,
      index = fromIndex + (fromRight ? 1 : -1);

  while ((fromRight ? index-- : ++index < length)) {
    if (predicate(array[index], index, array)) {
      return index;
    }
  }
  return -1;
}

module.exports = baseFindIndex;


/***/ }),

/***/ 8483:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var createBaseFor = __webpack_require__(5063);

/**
 * The base implementation of `baseForOwn` which iterates over `object`
 * properties returned by `keysFunc` and invokes `iteratee` for each property.
 * Iteratee functions may exit iteration early by explicitly returning `false`.
 *
 * @private
 * @param {Object} object The object to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @param {Function} keysFunc The function to get the keys of `object`.
 * @returns {Object} Returns `object`.
 */
var baseFor = createBaseFor();

module.exports = baseFor;


/***/ }),

/***/ 7816:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseFor = __webpack_require__(8483),
    keys = __webpack_require__(3674);

/**
 * The base implementation of `_.forOwn` without support for iteratee shorthands.
 *
 * @private
 * @param {Object} object The object to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Object} Returns `object`.
 */
function baseForOwn(object, iteratee) {
  return object && baseFor(object, iteratee, keys);
}

module.exports = baseForOwn;


/***/ }),

/***/ 7786:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var castPath = __webpack_require__(1811),
    toKey = __webpack_require__(327);

/**
 * The base implementation of `_.get` without support for default values.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Array|string} path The path of the property to get.
 * @returns {*} Returns the resolved value.
 */
function baseGet(object, path) {
  path = castPath(path, object);

  var index = 0,
      length = path.length;

  while (object != null && index < length) {
    object = object[toKey(path[index++])];
  }
  return (index && index == length) ? object : undefined;
}

module.exports = baseGet;


/***/ }),

/***/ 8866:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var arrayPush = __webpack_require__(2488),
    isArray = __webpack_require__(1469);

/**
 * The base implementation of `getAllKeys` and `getAllKeysIn` which uses
 * `keysFunc` and `symbolsFunc` to get the enumerable property names and
 * symbols of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Function} keysFunc The function to get the keys of `object`.
 * @param {Function} symbolsFunc The function to get the symbols of `object`.
 * @returns {Array} Returns the array of property names and symbols.
 */
function baseGetAllKeys(object, keysFunc, symbolsFunc) {
  var result = keysFunc(object);
  return isArray(object) ? result : arrayPush(result, symbolsFunc(object));
}

module.exports = baseGetAllKeys;


/***/ }),

/***/ 4239:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Symbol = __webpack_require__(2705),
    getRawTag = __webpack_require__(9607),
    objectToString = __webpack_require__(2333);

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

module.exports = baseGetTag;


/***/ }),

/***/ 13:
/***/ (function(module) {

/**
 * The base implementation of `_.hasIn` without support for deep paths.
 *
 * @private
 * @param {Object} [object] The object to query.
 * @param {Array|string} key The key to check.
 * @returns {boolean} Returns `true` if `key` exists, else `false`.
 */
function baseHasIn(object, key) {
  return object != null && key in Object(object);
}

module.exports = baseHasIn;


/***/ }),

/***/ 2118:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseFindIndex = __webpack_require__(1848),
    baseIsNaN = __webpack_require__(2722),
    strictIndexOf = __webpack_require__(2351);

/**
 * The base implementation of `_.indexOf` without `fromIndex` bounds checks.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {*} value The value to search for.
 * @param {number} fromIndex The index to search from.
 * @returns {number} Returns the index of the matched value, else `-1`.
 */
function baseIndexOf(array, value, fromIndex) {
  return value === value
    ? strictIndexOf(array, value, fromIndex)
    : baseFindIndex(array, baseIsNaN, fromIndex);
}

module.exports = baseIndexOf;


/***/ }),

/***/ 3783:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var apply = __webpack_require__(6874),
    castPath = __webpack_require__(1811),
    last = __webpack_require__(928),
    parent = __webpack_require__(292),
    toKey = __webpack_require__(327);

/**
 * The base implementation of `_.invoke` without support for individual
 * method arguments.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Array|string} path The path of the method to invoke.
 * @param {Array} args The arguments to invoke the method with.
 * @returns {*} Returns the result of the invoked method.
 */
function baseInvoke(object, path, args) {
  path = castPath(path, object);
  object = parent(object, path);
  var func = object == null ? object : object[toKey(last(path))];
  return func == null ? undefined : apply(func, object, args);
}

module.exports = baseInvoke;


/***/ }),

/***/ 9454:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGetTag = __webpack_require__(4239),
    isObjectLike = __webpack_require__(7005);

/** `Object#toString` result references. */
var argsTag = '[object Arguments]';

/**
 * The base implementation of `_.isArguments`.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 */
function baseIsArguments(value) {
  return isObjectLike(value) && baseGetTag(value) == argsTag;
}

module.exports = baseIsArguments;


/***/ }),

/***/ 939:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsEqualDeep = __webpack_require__(2492),
    isObjectLike = __webpack_require__(7005);

/**
 * The base implementation of `_.isEqual` which supports partial comparisons
 * and tracks traversed objects.
 *
 * @private
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @param {boolean} bitmask The bitmask flags.
 *  1 - Unordered comparison
 *  2 - Partial comparison
 * @param {Function} [customizer] The function to customize comparisons.
 * @param {Object} [stack] Tracks traversed `value` and `other` objects.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 */
function baseIsEqual(value, other, bitmask, customizer, stack) {
  if (value === other) {
    return true;
  }
  if (value == null || other == null || (!isObjectLike(value) && !isObjectLike(other))) {
    return value !== value && other !== other;
  }
  return baseIsEqualDeep(value, other, bitmask, customizer, baseIsEqual, stack);
}

module.exports = baseIsEqual;


/***/ }),

/***/ 2492:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Stack = __webpack_require__(6384),
    equalArrays = __webpack_require__(7114),
    equalByTag = __webpack_require__(8351),
    equalObjects = __webpack_require__(6096),
    getTag = __webpack_require__(4160),
    isArray = __webpack_require__(1469),
    isBuffer = __webpack_require__(4144),
    isTypedArray = __webpack_require__(6719);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1;

/** `Object#toString` result references. */
var argsTag = '[object Arguments]',
    arrayTag = '[object Array]',
    objectTag = '[object Object]';

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * A specialized version of `baseIsEqual` for arrays and objects which performs
 * deep comparisons and tracks traversed objects enabling objects with circular
 * references to be compared.
 *
 * @private
 * @param {Object} object The object to compare.
 * @param {Object} other The other object to compare.
 * @param {number} bitmask The bitmask flags. See `baseIsEqual` for more details.
 * @param {Function} customizer The function to customize comparisons.
 * @param {Function} equalFunc The function to determine equivalents of values.
 * @param {Object} [stack] Tracks traversed `object` and `other` objects.
 * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
 */
function baseIsEqualDeep(object, other, bitmask, customizer, equalFunc, stack) {
  var objIsArr = isArray(object),
      othIsArr = isArray(other),
      objTag = objIsArr ? arrayTag : getTag(object),
      othTag = othIsArr ? arrayTag : getTag(other);

  objTag = objTag == argsTag ? objectTag : objTag;
  othTag = othTag == argsTag ? objectTag : othTag;

  var objIsObj = objTag == objectTag,
      othIsObj = othTag == objectTag,
      isSameTag = objTag == othTag;

  if (isSameTag && isBuffer(object)) {
    if (!isBuffer(other)) {
      return false;
    }
    objIsArr = true;
    objIsObj = false;
  }
  if (isSameTag && !objIsObj) {
    stack || (stack = new Stack);
    return (objIsArr || isTypedArray(object))
      ? equalArrays(object, other, bitmask, customizer, equalFunc, stack)
      : equalByTag(object, other, objTag, bitmask, customizer, equalFunc, stack);
  }
  if (!(bitmask & COMPARE_PARTIAL_FLAG)) {
    var objIsWrapped = objIsObj && hasOwnProperty.call(object, '__wrapped__'),
        othIsWrapped = othIsObj && hasOwnProperty.call(other, '__wrapped__');

    if (objIsWrapped || othIsWrapped) {
      var objUnwrapped = objIsWrapped ? object.value() : object,
          othUnwrapped = othIsWrapped ? other.value() : other;

      stack || (stack = new Stack);
      return equalFunc(objUnwrapped, othUnwrapped, bitmask, customizer, stack);
    }
  }
  if (!isSameTag) {
    return false;
  }
  stack || (stack = new Stack);
  return equalObjects(object, other, bitmask, customizer, equalFunc, stack);
}

module.exports = baseIsEqualDeep;


/***/ }),

/***/ 2958:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Stack = __webpack_require__(6384),
    baseIsEqual = __webpack_require__(939);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1,
    COMPARE_UNORDERED_FLAG = 2;

/**
 * The base implementation of `_.isMatch` without support for iteratee shorthands.
 *
 * @private
 * @param {Object} object The object to inspect.
 * @param {Object} source The object of property values to match.
 * @param {Array} matchData The property names, values, and compare flags to match.
 * @param {Function} [customizer] The function to customize comparisons.
 * @returns {boolean} Returns `true` if `object` is a match, else `false`.
 */
function baseIsMatch(object, source, matchData, customizer) {
  var index = matchData.length,
      length = index,
      noCustomizer = !customizer;

  if (object == null) {
    return !length;
  }
  object = Object(object);
  while (index--) {
    var data = matchData[index];
    if ((noCustomizer && data[2])
          ? data[1] !== object[data[0]]
          : !(data[0] in object)
        ) {
      return false;
    }
  }
  while (++index < length) {
    data = matchData[index];
    var key = data[0],
        objValue = object[key],
        srcValue = data[1];

    if (noCustomizer && data[2]) {
      if (objValue === undefined && !(key in object)) {
        return false;
      }
    } else {
      var stack = new Stack;
      if (customizer) {
        var result = customizer(objValue, srcValue, key, object, source, stack);
      }
      if (!(result === undefined
            ? baseIsEqual(srcValue, objValue, COMPARE_PARTIAL_FLAG | COMPARE_UNORDERED_FLAG, customizer, stack)
            : result
          )) {
        return false;
      }
    }
  }
  return true;
}

module.exports = baseIsMatch;


/***/ }),

/***/ 2722:
/***/ (function(module) {

/**
 * The base implementation of `_.isNaN` without support for number objects.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is `NaN`, else `false`.
 */
function baseIsNaN(value) {
  return value !== value;
}

module.exports = baseIsNaN;


/***/ }),

/***/ 8458:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isFunction = __webpack_require__(3560),
    isMasked = __webpack_require__(5346),
    isObject = __webpack_require__(3218),
    toSource = __webpack_require__(346);

/**
 * Used to match `RegExp`
 * [syntax characters](http://ecma-international.org/ecma-262/7.0/#sec-patterns).
 */
var reRegExpChar = /[\\^$.*+?()[\]{}|]/g;

/** Used to detect host constructors (Safari). */
var reIsHostCtor = /^\[object .+?Constructor\]$/;

/** Used for built-in method references. */
var funcProto = Function.prototype,
    objectProto = Object.prototype;

/** Used to resolve the decompiled source of functions. */
var funcToString = funcProto.toString;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/** Used to detect if a method is native. */
var reIsNative = RegExp('^' +
  funcToString.call(hasOwnProperty).replace(reRegExpChar, '\\$&')
  .replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$'
);

/**
 * The base implementation of `_.isNative` without bad shim checks.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a native function,
 *  else `false`.
 */
function baseIsNative(value) {
  if (!isObject(value) || isMasked(value)) {
    return false;
  }
  var pattern = isFunction(value) ? reIsNative : reIsHostCtor;
  return pattern.test(toSource(value));
}

module.exports = baseIsNative;


/***/ }),

/***/ 8749:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGetTag = __webpack_require__(4239),
    isLength = __webpack_require__(1780),
    isObjectLike = __webpack_require__(7005);

/** `Object#toString` result references. */
var argsTag = '[object Arguments]',
    arrayTag = '[object Array]',
    boolTag = '[object Boolean]',
    dateTag = '[object Date]',
    errorTag = '[object Error]',
    funcTag = '[object Function]',
    mapTag = '[object Map]',
    numberTag = '[object Number]',
    objectTag = '[object Object]',
    regexpTag = '[object RegExp]',
    setTag = '[object Set]',
    stringTag = '[object String]',
    weakMapTag = '[object WeakMap]';

var arrayBufferTag = '[object ArrayBuffer]',
    dataViewTag = '[object DataView]',
    float32Tag = '[object Float32Array]',
    float64Tag = '[object Float64Array]',
    int8Tag = '[object Int8Array]',
    int16Tag = '[object Int16Array]',
    int32Tag = '[object Int32Array]',
    uint8Tag = '[object Uint8Array]',
    uint8ClampedTag = '[object Uint8ClampedArray]',
    uint16Tag = '[object Uint16Array]',
    uint32Tag = '[object Uint32Array]';

/** Used to identify `toStringTag` values of typed arrays. */
var typedArrayTags = {};
typedArrayTags[float32Tag] = typedArrayTags[float64Tag] =
typedArrayTags[int8Tag] = typedArrayTags[int16Tag] =
typedArrayTags[int32Tag] = typedArrayTags[uint8Tag] =
typedArrayTags[uint8ClampedTag] = typedArrayTags[uint16Tag] =
typedArrayTags[uint32Tag] = true;
typedArrayTags[argsTag] = typedArrayTags[arrayTag] =
typedArrayTags[arrayBufferTag] = typedArrayTags[boolTag] =
typedArrayTags[dataViewTag] = typedArrayTags[dateTag] =
typedArrayTags[errorTag] = typedArrayTags[funcTag] =
typedArrayTags[mapTag] = typedArrayTags[numberTag] =
typedArrayTags[objectTag] = typedArrayTags[regexpTag] =
typedArrayTags[setTag] = typedArrayTags[stringTag] =
typedArrayTags[weakMapTag] = false;

/**
 * The base implementation of `_.isTypedArray` without Node.js optimizations.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 */
function baseIsTypedArray(value) {
  return isObjectLike(value) &&
    isLength(value.length) && !!typedArrayTags[baseGetTag(value)];
}

module.exports = baseIsTypedArray;


/***/ }),

/***/ 7206:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseMatches = __webpack_require__(1573),
    baseMatchesProperty = __webpack_require__(6432),
    identity = __webpack_require__(6557),
    isArray = __webpack_require__(1469),
    property = __webpack_require__(9601);

/**
 * The base implementation of `_.iteratee`.
 *
 * @private
 * @param {*} [value=_.identity] The value to convert to an iteratee.
 * @returns {Function} Returns the iteratee.
 */
function baseIteratee(value) {
  // Don't store the `typeof` result in a variable to avoid a JIT bug in Safari 9.
  // See https://bugs.webkit.org/show_bug.cgi?id=156034 for more details.
  if (typeof value == 'function') {
    return value;
  }
  if (value == null) {
    return identity;
  }
  if (typeof value == 'object') {
    return isArray(value)
      ? baseMatchesProperty(value[0], value[1])
      : baseMatches(value);
  }
  return property(value);
}

module.exports = baseIteratee;


/***/ }),

/***/ 280:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isPrototype = __webpack_require__(5726),
    nativeKeys = __webpack_require__(6916);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */
function baseKeys(object) {
  if (!isPrototype(object)) {
    return nativeKeys(object);
  }
  var result = [];
  for (var key in Object(object)) {
    if (hasOwnProperty.call(object, key) && key != 'constructor') {
      result.push(key);
    }
  }
  return result;
}

module.exports = baseKeys;


/***/ }),

/***/ 9199:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseEach = __webpack_require__(9881),
    isArrayLike = __webpack_require__(8612);

/**
 * The base implementation of `_.map` without support for iteratee shorthands.
 *
 * @private
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function baseMap(collection, iteratee) {
  var index = -1,
      result = isArrayLike(collection) ? Array(collection.length) : [];

  baseEach(collection, function(value, key, collection) {
    result[++index] = iteratee(value, key, collection);
  });
  return result;
}

module.exports = baseMap;


/***/ }),

/***/ 1573:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsMatch = __webpack_require__(2958),
    getMatchData = __webpack_require__(1499),
    matchesStrictComparable = __webpack_require__(2634);

/**
 * The base implementation of `_.matches` which doesn't clone `source`.
 *
 * @private
 * @param {Object} source The object of property values to match.
 * @returns {Function} Returns the new spec function.
 */
function baseMatches(source) {
  var matchData = getMatchData(source);
  if (matchData.length == 1 && matchData[0][2]) {
    return matchesStrictComparable(matchData[0][0], matchData[0][1]);
  }
  return function(object) {
    return object === source || baseIsMatch(object, source, matchData);
  };
}

module.exports = baseMatches;


/***/ }),

/***/ 6432:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsEqual = __webpack_require__(939),
    get = __webpack_require__(7361),
    hasIn = __webpack_require__(9095),
    isKey = __webpack_require__(5403),
    isStrictComparable = __webpack_require__(9162),
    matchesStrictComparable = __webpack_require__(2634),
    toKey = __webpack_require__(327);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1,
    COMPARE_UNORDERED_FLAG = 2;

/**
 * The base implementation of `_.matchesProperty` which doesn't clone `srcValue`.
 *
 * @private
 * @param {string} path The path of the property to get.
 * @param {*} srcValue The value to match.
 * @returns {Function} Returns the new spec function.
 */
function baseMatchesProperty(path, srcValue) {
  if (isKey(path) && isStrictComparable(srcValue)) {
    return matchesStrictComparable(toKey(path), srcValue);
  }
  return function(object) {
    var objValue = get(object, path);
    return (objValue === undefined && objValue === srcValue)
      ? hasIn(object, path)
      : baseIsEqual(srcValue, objValue, COMPARE_PARTIAL_FLAG | COMPARE_UNORDERED_FLAG);
  };
}

module.exports = baseMatchesProperty;


/***/ }),

/***/ 371:
/***/ (function(module) {

/**
 * The base implementation of `_.property` without support for deep paths.
 *
 * @private
 * @param {string} key The key of the property to get.
 * @returns {Function} Returns the new accessor function.
 */
function baseProperty(key) {
  return function(object) {
    return object == null ? undefined : object[key];
  };
}

module.exports = baseProperty;


/***/ }),

/***/ 9152:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGet = __webpack_require__(7786);

/**
 * A specialized version of `baseProperty` which supports deep paths.
 *
 * @private
 * @param {Array|string} path The path of the property to get.
 * @returns {Function} Returns the new accessor function.
 */
function basePropertyDeep(path) {
  return function(object) {
    return baseGet(object, path);
  };
}

module.exports = basePropertyDeep;


/***/ }),

/***/ 8674:
/***/ (function(module) {

/**
 * The base implementation of `_.propertyOf` without support for deep paths.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Function} Returns the new accessor function.
 */
function basePropertyOf(object) {
  return function(key) {
    return object == null ? undefined : object[key];
  };
}

module.exports = basePropertyOf;


/***/ }),

/***/ 5976:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var identity = __webpack_require__(6557),
    overRest = __webpack_require__(5357),
    setToString = __webpack_require__(61);

/**
 * The base implementation of `_.rest` which doesn't validate or coerce arguments.
 *
 * @private
 * @param {Function} func The function to apply a rest parameter to.
 * @param {number} [start=func.length-1] The start position of the rest parameter.
 * @returns {Function} Returns the new function.
 */
function baseRest(func, start) {
  return setToString(overRest(func, start, identity), func + '');
}

module.exports = baseRest;


/***/ }),

/***/ 6560:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var constant = __webpack_require__(5703),
    defineProperty = __webpack_require__(8777),
    identity = __webpack_require__(6557);

/**
 * The base implementation of `setToString` without support for hot loop shorting.
 *
 * @private
 * @param {Function} func The function to modify.
 * @param {Function} string The `toString` result.
 * @returns {Function} Returns `func`.
 */
var baseSetToString = !defineProperty ? identity : function(func, string) {
  return defineProperty(func, 'toString', {
    'configurable': true,
    'enumerable': false,
    'value': constant(string),
    'writable': true
  });
};

module.exports = baseSetToString;


/***/ }),

/***/ 4259:
/***/ (function(module) {

/**
 * The base implementation of `_.slice` without an iteratee call guard.
 *
 * @private
 * @param {Array} array The array to slice.
 * @param {number} [start=0] The start position.
 * @param {number} [end=array.length] The end position.
 * @returns {Array} Returns the slice of `array`.
 */
function baseSlice(array, start, end) {
  var index = -1,
      length = array.length;

  if (start < 0) {
    start = -start > length ? 0 : (length + start);
  }
  end = end > length ? length : end;
  if (end < 0) {
    end += length;
  }
  length = start > end ? 0 : ((end - start) >>> 0);
  start >>>= 0;

  var result = Array(length);
  while (++index < length) {
    result[index] = array[index + start];
  }
  return result;
}

module.exports = baseSlice;


/***/ }),

/***/ 2545:
/***/ (function(module) {

/**
 * The base implementation of `_.times` without support for iteratee shorthands
 * or max array length checks.
 *
 * @private
 * @param {number} n The number of times to invoke `iteratee`.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the array of results.
 */
function baseTimes(n, iteratee) {
  var index = -1,
      result = Array(n);

  while (++index < n) {
    result[index] = iteratee(index);
  }
  return result;
}

module.exports = baseTimes;


/***/ }),

/***/ 531:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Symbol = __webpack_require__(2705),
    arrayMap = __webpack_require__(9932),
    isArray = __webpack_require__(1469),
    isSymbol = __webpack_require__(3448);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolToString = symbolProto ? symbolProto.toString : undefined;

/**
 * The base implementation of `_.toString` which doesn't convert nullish
 * values to empty strings.
 *
 * @private
 * @param {*} value The value to process.
 * @returns {string} Returns the string.
 */
function baseToString(value) {
  // Exit early for strings to avoid a performance hit in some environments.
  if (typeof value == 'string') {
    return value;
  }
  if (isArray(value)) {
    // Recursively convert values (susceptible to call stack limits).
    return arrayMap(value, baseToString) + '';
  }
  if (isSymbol(value)) {
    return symbolToString ? symbolToString.call(value) : '';
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

module.exports = baseToString;


/***/ }),

/***/ 7561:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var trimmedEndIndex = __webpack_require__(7990);

/** Used to match leading whitespace. */
var reTrimStart = /^\s+/;

/**
 * The base implementation of `_.trim`.
 *
 * @private
 * @param {string} string The string to trim.
 * @returns {string} Returns the trimmed string.
 */
function baseTrim(string) {
  return string
    ? string.slice(0, trimmedEndIndex(string) + 1).replace(reTrimStart, '')
    : string;
}

module.exports = baseTrim;


/***/ }),

/***/ 1717:
/***/ (function(module) {

/**
 * The base implementation of `_.unary` without support for storing metadata.
 *
 * @private
 * @param {Function} func The function to cap arguments for.
 * @returns {Function} Returns the new capped function.
 */
function baseUnary(func) {
  return function(value) {
    return func(value);
  };
}

module.exports = baseUnary;


/***/ }),

/***/ 5652:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var SetCache = __webpack_require__(8668),
    arrayIncludes = __webpack_require__(7443),
    arrayIncludesWith = __webpack_require__(1196),
    cacheHas = __webpack_require__(4757),
    createSet = __webpack_require__(3593),
    setToArray = __webpack_require__(1814);

/** Used as the size to enable large array optimizations. */
var LARGE_ARRAY_SIZE = 200;

/**
 * The base implementation of `_.uniqBy` without support for iteratee shorthands.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {Function} [iteratee] The iteratee invoked per element.
 * @param {Function} [comparator] The comparator invoked per element.
 * @returns {Array} Returns the new duplicate free array.
 */
function baseUniq(array, iteratee, comparator) {
  var index = -1,
      includes = arrayIncludes,
      length = array.length,
      isCommon = true,
      result = [],
      seen = result;

  if (comparator) {
    isCommon = false;
    includes = arrayIncludesWith;
  }
  else if (length >= LARGE_ARRAY_SIZE) {
    var set = iteratee ? null : createSet(array);
    if (set) {
      return setToArray(set);
    }
    isCommon = false;
    includes = cacheHas;
    seen = new SetCache;
  }
  else {
    seen = iteratee ? [] : result;
  }
  outer:
  while (++index < length) {
    var value = array[index],
        computed = iteratee ? iteratee(value) : value;

    value = (comparator || value !== 0) ? value : 0;
    if (isCommon && computed === computed) {
      var seenIndex = seen.length;
      while (seenIndex--) {
        if (seen[seenIndex] === computed) {
          continue outer;
        }
      }
      if (iteratee) {
        seen.push(computed);
      }
      result.push(value);
    }
    else if (!includes(seen, computed, comparator)) {
      if (seen !== result) {
        seen.push(computed);
      }
      result.push(value);
    }
  }
  return result;
}

module.exports = baseUniq;


/***/ }),

/***/ 4757:
/***/ (function(module) {

/**
 * Checks if a `cache` value for `key` exists.
 *
 * @private
 * @param {Object} cache The cache to query.
 * @param {string} key The key of the entry to check.
 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
 */
function cacheHas(cache, key) {
  return cache.has(key);
}

module.exports = cacheHas;


/***/ }),

/***/ 1811:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isArray = __webpack_require__(1469),
    isKey = __webpack_require__(5403),
    stringToPath = __webpack_require__(5514),
    toString = __webpack_require__(9833);

/**
 * Casts `value` to a path array if it's not one.
 *
 * @private
 * @param {*} value The value to inspect.
 * @param {Object} [object] The object to query keys on.
 * @returns {Array} Returns the cast property path array.
 */
function castPath(value, object) {
  if (isArray(value)) {
    return value;
  }
  return isKey(value, object) ? [value] : stringToPath(toString(value));
}

module.exports = castPath;


/***/ }),

/***/ 4429:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var root = __webpack_require__(5639);

/** Used to detect overreaching core-js shims. */
var coreJsData = root['__core-js_shared__'];

module.exports = coreJsData;


/***/ }),

/***/ 5189:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var arrayAggregator = __webpack_require__(4174),
    baseAggregator = __webpack_require__(1119),
    baseIteratee = __webpack_require__(7206),
    isArray = __webpack_require__(1469);

/**
 * Creates a function like `_.groupBy`.
 *
 * @private
 * @param {Function} setter The function to set accumulator values.
 * @param {Function} [initializer] The accumulator object initializer.
 * @returns {Function} Returns the new aggregator function.
 */
function createAggregator(setter, initializer) {
  return function(collection, iteratee) {
    var func = isArray(collection) ? arrayAggregator : baseAggregator,
        accumulator = initializer ? initializer() : {};

    return func(collection, setter, baseIteratee(iteratee, 2), accumulator);
  };
}

module.exports = createAggregator;


/***/ }),

/***/ 9291:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isArrayLike = __webpack_require__(8612);

/**
 * Creates a `baseEach` or `baseEachRight` function.
 *
 * @private
 * @param {Function} eachFunc The function to iterate over a collection.
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Function} Returns the new base function.
 */
function createBaseEach(eachFunc, fromRight) {
  return function(collection, iteratee) {
    if (collection == null) {
      return collection;
    }
    if (!isArrayLike(collection)) {
      return eachFunc(collection, iteratee);
    }
    var length = collection.length,
        index = fromRight ? length : -1,
        iterable = Object(collection);

    while ((fromRight ? index-- : ++index < length)) {
      if (iteratee(iterable[index], index, iterable) === false) {
        break;
      }
    }
    return collection;
  };
}

module.exports = createBaseEach;


/***/ }),

/***/ 5063:
/***/ (function(module) {

/**
 * Creates a base function for methods like `_.forIn` and `_.forOwn`.
 *
 * @private
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Function} Returns the new base function.
 */
function createBaseFor(fromRight) {
  return function(object, iteratee, keysFunc) {
    var index = -1,
        iterable = Object(object),
        props = keysFunc(object),
        length = props.length;

    while (length--) {
      var key = props[fromRight ? length : ++index];
      if (iteratee(iterable[key], key, iterable) === false) {
        break;
      }
    }
    return object;
  };
}

module.exports = createBaseFor;


/***/ }),

/***/ 7740:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIteratee = __webpack_require__(7206),
    isArrayLike = __webpack_require__(8612),
    keys = __webpack_require__(3674);

/**
 * Creates a `_.find` or `_.findLast` function.
 *
 * @private
 * @param {Function} findIndexFunc The function to find the collection index.
 * @returns {Function} Returns the new find function.
 */
function createFind(findIndexFunc) {
  return function(collection, predicate, fromIndex) {
    var iterable = Object(collection);
    if (!isArrayLike(collection)) {
      var iteratee = baseIteratee(predicate, 3);
      collection = keys(collection);
      predicate = function(key) { return iteratee(iterable[key], key, iterable); };
    }
    var index = findIndexFunc(collection, predicate, fromIndex);
    return index > -1 ? iterable[iteratee ? collection[index] : index] : undefined;
  };
}

module.exports = createFind;


/***/ }),

/***/ 3593:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Set = __webpack_require__(8525),
    noop = __webpack_require__(308),
    setToArray = __webpack_require__(1814);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/**
 * Creates a set object of `values`.
 *
 * @private
 * @param {Array} values The values to add to the set.
 * @returns {Object} Returns the new set.
 */
var createSet = !(Set && (1 / setToArray(new Set([,-0]))[1]) == INFINITY) ? noop : function(values) {
  return new Set(values);
};

module.exports = createSet;


/***/ }),

/***/ 8777:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852);

var defineProperty = (function() {
  try {
    var func = getNative(Object, 'defineProperty');
    func({}, '', {});
    return func;
  } catch (e) {}
}());

module.exports = defineProperty;


/***/ }),

/***/ 7114:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var SetCache = __webpack_require__(8668),
    arraySome = __webpack_require__(2908),
    cacheHas = __webpack_require__(4757);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1,
    COMPARE_UNORDERED_FLAG = 2;

/**
 * A specialized version of `baseIsEqualDeep` for arrays with support for
 * partial deep comparisons.
 *
 * @private
 * @param {Array} array The array to compare.
 * @param {Array} other The other array to compare.
 * @param {number} bitmask The bitmask flags. See `baseIsEqual` for more details.
 * @param {Function} customizer The function to customize comparisons.
 * @param {Function} equalFunc The function to determine equivalents of values.
 * @param {Object} stack Tracks traversed `array` and `other` objects.
 * @returns {boolean} Returns `true` if the arrays are equivalent, else `false`.
 */
function equalArrays(array, other, bitmask, customizer, equalFunc, stack) {
  var isPartial = bitmask & COMPARE_PARTIAL_FLAG,
      arrLength = array.length,
      othLength = other.length;

  if (arrLength != othLength && !(isPartial && othLength > arrLength)) {
    return false;
  }
  // Check that cyclic values are equal.
  var arrStacked = stack.get(array);
  var othStacked = stack.get(other);
  if (arrStacked && othStacked) {
    return arrStacked == other && othStacked == array;
  }
  var index = -1,
      result = true,
      seen = (bitmask & COMPARE_UNORDERED_FLAG) ? new SetCache : undefined;

  stack.set(array, other);
  stack.set(other, array);

  // Ignore non-index properties.
  while (++index < arrLength) {
    var arrValue = array[index],
        othValue = other[index];

    if (customizer) {
      var compared = isPartial
        ? customizer(othValue, arrValue, index, other, array, stack)
        : customizer(arrValue, othValue, index, array, other, stack);
    }
    if (compared !== undefined) {
      if (compared) {
        continue;
      }
      result = false;
      break;
    }
    // Recursively compare arrays (susceptible to call stack limits).
    if (seen) {
      if (!arraySome(other, function(othValue, othIndex) {
            if (!cacheHas(seen, othIndex) &&
                (arrValue === othValue || equalFunc(arrValue, othValue, bitmask, customizer, stack))) {
              return seen.push(othIndex);
            }
          })) {
        result = false;
        break;
      }
    } else if (!(
          arrValue === othValue ||
            equalFunc(arrValue, othValue, bitmask, customizer, stack)
        )) {
      result = false;
      break;
    }
  }
  stack['delete'](array);
  stack['delete'](other);
  return result;
}

module.exports = equalArrays;


/***/ }),

/***/ 8351:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Symbol = __webpack_require__(2705),
    Uint8Array = __webpack_require__(1149),
    eq = __webpack_require__(7813),
    equalArrays = __webpack_require__(7114),
    mapToArray = __webpack_require__(8776),
    setToArray = __webpack_require__(1814);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1,
    COMPARE_UNORDERED_FLAG = 2;

/** `Object#toString` result references. */
var boolTag = '[object Boolean]',
    dateTag = '[object Date]',
    errorTag = '[object Error]',
    mapTag = '[object Map]',
    numberTag = '[object Number]',
    regexpTag = '[object RegExp]',
    setTag = '[object Set]',
    stringTag = '[object String]',
    symbolTag = '[object Symbol]';

var arrayBufferTag = '[object ArrayBuffer]',
    dataViewTag = '[object DataView]';

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolValueOf = symbolProto ? symbolProto.valueOf : undefined;

/**
 * A specialized version of `baseIsEqualDeep` for comparing objects of
 * the same `toStringTag`.
 *
 * **Note:** This function only supports comparing values with tags of
 * `Boolean`, `Date`, `Error`, `Number`, `RegExp`, or `String`.
 *
 * @private
 * @param {Object} object The object to compare.
 * @param {Object} other The other object to compare.
 * @param {string} tag The `toStringTag` of the objects to compare.
 * @param {number} bitmask The bitmask flags. See `baseIsEqual` for more details.
 * @param {Function} customizer The function to customize comparisons.
 * @param {Function} equalFunc The function to determine equivalents of values.
 * @param {Object} stack Tracks traversed `object` and `other` objects.
 * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
 */
function equalByTag(object, other, tag, bitmask, customizer, equalFunc, stack) {
  switch (tag) {
    case dataViewTag:
      if ((object.byteLength != other.byteLength) ||
          (object.byteOffset != other.byteOffset)) {
        return false;
      }
      object = object.buffer;
      other = other.buffer;

    case arrayBufferTag:
      if ((object.byteLength != other.byteLength) ||
          !equalFunc(new Uint8Array(object), new Uint8Array(other))) {
        return false;
      }
      return true;

    case boolTag:
    case dateTag:
    case numberTag:
      // Coerce booleans to `1` or `0` and dates to milliseconds.
      // Invalid dates are coerced to `NaN`.
      return eq(+object, +other);

    case errorTag:
      return object.name == other.name && object.message == other.message;

    case regexpTag:
    case stringTag:
      // Coerce regexes to strings and treat strings, primitives and objects,
      // as equal. See http://www.ecma-international.org/ecma-262/7.0/#sec-regexp.prototype.tostring
      // for more details.
      return object == (other + '');

    case mapTag:
      var convert = mapToArray;

    case setTag:
      var isPartial = bitmask & COMPARE_PARTIAL_FLAG;
      convert || (convert = setToArray);

      if (object.size != other.size && !isPartial) {
        return false;
      }
      // Assume cyclic values are equal.
      var stacked = stack.get(object);
      if (stacked) {
        return stacked == other;
      }
      bitmask |= COMPARE_UNORDERED_FLAG;

      // Recursively compare objects (susceptible to call stack limits).
      stack.set(object, other);
      var result = equalArrays(convert(object), convert(other), bitmask, customizer, equalFunc, stack);
      stack['delete'](object);
      return result;

    case symbolTag:
      if (symbolValueOf) {
        return symbolValueOf.call(object) == symbolValueOf.call(other);
      }
  }
  return false;
}

module.exports = equalByTag;


/***/ }),

/***/ 6096:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getAllKeys = __webpack_require__(8234);

/** Used to compose bitmasks for value comparisons. */
var COMPARE_PARTIAL_FLAG = 1;

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * A specialized version of `baseIsEqualDeep` for objects with support for
 * partial deep comparisons.
 *
 * @private
 * @param {Object} object The object to compare.
 * @param {Object} other The other object to compare.
 * @param {number} bitmask The bitmask flags. See `baseIsEqual` for more details.
 * @param {Function} customizer The function to customize comparisons.
 * @param {Function} equalFunc The function to determine equivalents of values.
 * @param {Object} stack Tracks traversed `object` and `other` objects.
 * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
 */
function equalObjects(object, other, bitmask, customizer, equalFunc, stack) {
  var isPartial = bitmask & COMPARE_PARTIAL_FLAG,
      objProps = getAllKeys(object),
      objLength = objProps.length,
      othProps = getAllKeys(other),
      othLength = othProps.length;

  if (objLength != othLength && !isPartial) {
    return false;
  }
  var index = objLength;
  while (index--) {
    var key = objProps[index];
    if (!(isPartial ? key in other : hasOwnProperty.call(other, key))) {
      return false;
    }
  }
  // Check that cyclic values are equal.
  var objStacked = stack.get(object);
  var othStacked = stack.get(other);
  if (objStacked && othStacked) {
    return objStacked == other && othStacked == object;
  }
  var result = true;
  stack.set(object, other);
  stack.set(other, object);

  var skipCtor = isPartial;
  while (++index < objLength) {
    key = objProps[index];
    var objValue = object[key],
        othValue = other[key];

    if (customizer) {
      var compared = isPartial
        ? customizer(othValue, objValue, key, other, object, stack)
        : customizer(objValue, othValue, key, object, other, stack);
    }
    // Recursively compare objects (susceptible to call stack limits).
    if (!(compared === undefined
          ? (objValue === othValue || equalFunc(objValue, othValue, bitmask, customizer, stack))
          : compared
        )) {
      result = false;
      break;
    }
    skipCtor || (skipCtor = key == 'constructor');
  }
  if (result && !skipCtor) {
    var objCtor = object.constructor,
        othCtor = other.constructor;

    // Non `Object` object instances with different constructors are not equal.
    if (objCtor != othCtor &&
        ('constructor' in object && 'constructor' in other) &&
        !(typeof objCtor == 'function' && objCtor instanceof objCtor &&
          typeof othCtor == 'function' && othCtor instanceof othCtor)) {
      result = false;
    }
  }
  stack['delete'](object);
  stack['delete'](other);
  return result;
}

module.exports = equalObjects;


/***/ }),

/***/ 1957:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof __webpack_require__.g == 'object' && __webpack_require__.g && __webpack_require__.g.Object === Object && __webpack_require__.g;

module.exports = freeGlobal;


/***/ }),

/***/ 8234:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGetAllKeys = __webpack_require__(8866),
    getSymbols = __webpack_require__(9551),
    keys = __webpack_require__(3674);

/**
 * Creates an array of own enumerable property names and symbols of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names and symbols.
 */
function getAllKeys(object) {
  return baseGetAllKeys(object, keys, getSymbols);
}

module.exports = getAllKeys;


/***/ }),

/***/ 5050:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isKeyable = __webpack_require__(7019);

/**
 * Gets the data for `map`.
 *
 * @private
 * @param {Object} map The map to query.
 * @param {string} key The reference key.
 * @returns {*} Returns the map data.
 */
function getMapData(map, key) {
  var data = map.__data__;
  return isKeyable(key)
    ? data[typeof key == 'string' ? 'string' : 'hash']
    : data.map;
}

module.exports = getMapData;


/***/ }),

/***/ 1499:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isStrictComparable = __webpack_require__(9162),
    keys = __webpack_require__(3674);

/**
 * Gets the property names, values, and compare flags of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the match data of `object`.
 */
function getMatchData(object) {
  var result = keys(object),
      length = result.length;

  while (length--) {
    var key = result[length],
        value = object[key];

    result[length] = [key, value, isStrictComparable(value)];
  }
  return result;
}

module.exports = getMatchData;


/***/ }),

/***/ 852:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsNative = __webpack_require__(8458),
    getValue = __webpack_require__(7801);

/**
 * Gets the native function at `key` of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {string} key The key of the method to get.
 * @returns {*} Returns the function if it's native, else `undefined`.
 */
function getNative(object, key) {
  var value = getValue(object, key);
  return baseIsNative(value) ? value : undefined;
}

module.exports = getNative;


/***/ }),

/***/ 9607:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Symbol = __webpack_require__(2705);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

module.exports = getRawTag;


/***/ }),

/***/ 9551:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var arrayFilter = __webpack_require__(4963),
    stubArray = __webpack_require__(479);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Built-in value references. */
var propertyIsEnumerable = objectProto.propertyIsEnumerable;

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeGetSymbols = Object.getOwnPropertySymbols;

/**
 * Creates an array of the own enumerable symbols of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of symbols.
 */
var getSymbols = !nativeGetSymbols ? stubArray : function(object) {
  if (object == null) {
    return [];
  }
  object = Object(object);
  return arrayFilter(nativeGetSymbols(object), function(symbol) {
    return propertyIsEnumerable.call(object, symbol);
  });
};

module.exports = getSymbols;


/***/ }),

/***/ 4160:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var DataView = __webpack_require__(8552),
    Map = __webpack_require__(7071),
    Promise = __webpack_require__(3818),
    Set = __webpack_require__(8525),
    WeakMap = __webpack_require__(577),
    baseGetTag = __webpack_require__(4239),
    toSource = __webpack_require__(346);

/** `Object#toString` result references. */
var mapTag = '[object Map]',
    objectTag = '[object Object]',
    promiseTag = '[object Promise]',
    setTag = '[object Set]',
    weakMapTag = '[object WeakMap]';

var dataViewTag = '[object DataView]';

/** Used to detect maps, sets, and weakmaps. */
var dataViewCtorString = toSource(DataView),
    mapCtorString = toSource(Map),
    promiseCtorString = toSource(Promise),
    setCtorString = toSource(Set),
    weakMapCtorString = toSource(WeakMap);

/**
 * Gets the `toStringTag` of `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
var getTag = baseGetTag;

// Fallback for data views, maps, sets, and weak maps in IE 11 and promises in Node.js < 6.
if ((DataView && getTag(new DataView(new ArrayBuffer(1))) != dataViewTag) ||
    (Map && getTag(new Map) != mapTag) ||
    (Promise && getTag(Promise.resolve()) != promiseTag) ||
    (Set && getTag(new Set) != setTag) ||
    (WeakMap && getTag(new WeakMap) != weakMapTag)) {
  getTag = function(value) {
    var result = baseGetTag(value),
        Ctor = result == objectTag ? value.constructor : undefined,
        ctorString = Ctor ? toSource(Ctor) : '';

    if (ctorString) {
      switch (ctorString) {
        case dataViewCtorString: return dataViewTag;
        case mapCtorString: return mapTag;
        case promiseCtorString: return promiseTag;
        case setCtorString: return setTag;
        case weakMapCtorString: return weakMapTag;
      }
    }
    return result;
  };
}

module.exports = getTag;


/***/ }),

/***/ 7801:
/***/ (function(module) {

/**
 * Gets the value at `key` of `object`.
 *
 * @private
 * @param {Object} [object] The object to query.
 * @param {string} key The key of the property to get.
 * @returns {*} Returns the property value.
 */
function getValue(object, key) {
  return object == null ? undefined : object[key];
}

module.exports = getValue;


/***/ }),

/***/ 222:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var castPath = __webpack_require__(1811),
    isArguments = __webpack_require__(5694),
    isArray = __webpack_require__(1469),
    isIndex = __webpack_require__(5776),
    isLength = __webpack_require__(1780),
    toKey = __webpack_require__(327);

/**
 * Checks if `path` exists on `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Array|string} path The path to check.
 * @param {Function} hasFunc The function to check properties.
 * @returns {boolean} Returns `true` if `path` exists, else `false`.
 */
function hasPath(object, path, hasFunc) {
  path = castPath(path, object);

  var index = -1,
      length = path.length,
      result = false;

  while (++index < length) {
    var key = toKey(path[index]);
    if (!(result = object != null && hasFunc(object, key))) {
      break;
    }
    object = object[key];
  }
  if (result || ++index != length) {
    return result;
  }
  length = object == null ? 0 : object.length;
  return !!length && isLength(length) && isIndex(key, length) &&
    (isArray(object) || isArguments(object));
}

module.exports = hasPath;


/***/ }),

/***/ 1789:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var nativeCreate = __webpack_require__(4536);

/**
 * Removes all key-value entries from the hash.
 *
 * @private
 * @name clear
 * @memberOf Hash
 */
function hashClear() {
  this.__data__ = nativeCreate ? nativeCreate(null) : {};
  this.size = 0;
}

module.exports = hashClear;


/***/ }),

/***/ 401:
/***/ (function(module) {

/**
 * Removes `key` and its value from the hash.
 *
 * @private
 * @name delete
 * @memberOf Hash
 * @param {Object} hash The hash to modify.
 * @param {string} key The key of the value to remove.
 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
 */
function hashDelete(key) {
  var result = this.has(key) && delete this.__data__[key];
  this.size -= result ? 1 : 0;
  return result;
}

module.exports = hashDelete;


/***/ }),

/***/ 7667:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var nativeCreate = __webpack_require__(4536);

/** Used to stand-in for `undefined` hash values. */
var HASH_UNDEFINED = '__lodash_hash_undefined__';

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Gets the hash value for `key`.
 *
 * @private
 * @name get
 * @memberOf Hash
 * @param {string} key The key of the value to get.
 * @returns {*} Returns the entry value.
 */
function hashGet(key) {
  var data = this.__data__;
  if (nativeCreate) {
    var result = data[key];
    return result === HASH_UNDEFINED ? undefined : result;
  }
  return hasOwnProperty.call(data, key) ? data[key] : undefined;
}

module.exports = hashGet;


/***/ }),

/***/ 1327:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var nativeCreate = __webpack_require__(4536);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Checks if a hash value for `key` exists.
 *
 * @private
 * @name has
 * @memberOf Hash
 * @param {string} key The key of the entry to check.
 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
 */
function hashHas(key) {
  var data = this.__data__;
  return nativeCreate ? (data[key] !== undefined) : hasOwnProperty.call(data, key);
}

module.exports = hashHas;


/***/ }),

/***/ 1866:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var nativeCreate = __webpack_require__(4536);

/** Used to stand-in for `undefined` hash values. */
var HASH_UNDEFINED = '__lodash_hash_undefined__';

/**
 * Sets the hash `key` to `value`.
 *
 * @private
 * @name set
 * @memberOf Hash
 * @param {string} key The key of the value to set.
 * @param {*} value The value to set.
 * @returns {Object} Returns the hash instance.
 */
function hashSet(key, value) {
  var data = this.__data__;
  this.size += this.has(key) ? 0 : 1;
  data[key] = (nativeCreate && value === undefined) ? HASH_UNDEFINED : value;
  return this;
}

module.exports = hashSet;


/***/ }),

/***/ 5776:
/***/ (function(module) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/** Used to detect unsigned integer values. */
var reIsUint = /^(?:0|[1-9]\d*)$/;

/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */
function isIndex(value, length) {
  var type = typeof value;
  length = length == null ? MAX_SAFE_INTEGER : length;

  return !!length &&
    (type == 'number' ||
      (type != 'symbol' && reIsUint.test(value))) &&
        (value > -1 && value % 1 == 0 && value < length);
}

module.exports = isIndex;


/***/ }),

/***/ 5403:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isArray = __webpack_require__(1469),
    isSymbol = __webpack_require__(3448);

/** Used to match property names within property paths. */
var reIsDeepProp = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/,
    reIsPlainProp = /^\w*$/;

/**
 * Checks if `value` is a property name and not a property path.
 *
 * @private
 * @param {*} value The value to check.
 * @param {Object} [object] The object to query keys on.
 * @returns {boolean} Returns `true` if `value` is a property name, else `false`.
 */
function isKey(value, object) {
  if (isArray(value)) {
    return false;
  }
  var type = typeof value;
  if (type == 'number' || type == 'symbol' || type == 'boolean' ||
      value == null || isSymbol(value)) {
    return true;
  }
  return reIsPlainProp.test(value) || !reIsDeepProp.test(value) ||
    (object != null && value in Object(object));
}

module.exports = isKey;


/***/ }),

/***/ 7019:
/***/ (function(module) {

/**
 * Checks if `value` is suitable for use as unique object key.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is suitable, else `false`.
 */
function isKeyable(value) {
  var type = typeof value;
  return (type == 'string' || type == 'number' || type == 'symbol' || type == 'boolean')
    ? (value !== '__proto__')
    : (value === null);
}

module.exports = isKeyable;


/***/ }),

/***/ 5346:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var coreJsData = __webpack_require__(4429);

/** Used to detect methods masquerading as native. */
var maskSrcKey = (function() {
  var uid = /[^.]+$/.exec(coreJsData && coreJsData.keys && coreJsData.keys.IE_PROTO || '');
  return uid ? ('Symbol(src)_1.' + uid) : '';
}());

/**
 * Checks if `func` has its source masked.
 *
 * @private
 * @param {Function} func The function to check.
 * @returns {boolean} Returns `true` if `func` is masked, else `false`.
 */
function isMasked(func) {
  return !!maskSrcKey && (maskSrcKey in func);
}

module.exports = isMasked;


/***/ }),

/***/ 5726:
/***/ (function(module) {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Checks if `value` is likely a prototype object.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
 */
function isPrototype(value) {
  var Ctor = value && value.constructor,
      proto = (typeof Ctor == 'function' && Ctor.prototype) || objectProto;

  return value === proto;
}

module.exports = isPrototype;


/***/ }),

/***/ 9162:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isObject = __webpack_require__(3218);

/**
 * Checks if `value` is suitable for strict equality comparisons, i.e. `===`.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` if suitable for strict
 *  equality comparisons, else `false`.
 */
function isStrictComparable(value) {
  return value === value && !isObject(value);
}

module.exports = isStrictComparable;


/***/ }),

/***/ 7040:
/***/ (function(module) {

/**
 * Removes all key-value entries from the list cache.
 *
 * @private
 * @name clear
 * @memberOf ListCache
 */
function listCacheClear() {
  this.__data__ = [];
  this.size = 0;
}

module.exports = listCacheClear;


/***/ }),

/***/ 4125:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var assocIndexOf = __webpack_require__(8470);

/** Used for built-in method references. */
var arrayProto = Array.prototype;

/** Built-in value references. */
var splice = arrayProto.splice;

/**
 * Removes `key` and its value from the list cache.
 *
 * @private
 * @name delete
 * @memberOf ListCache
 * @param {string} key The key of the value to remove.
 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
 */
function listCacheDelete(key) {
  var data = this.__data__,
      index = assocIndexOf(data, key);

  if (index < 0) {
    return false;
  }
  var lastIndex = data.length - 1;
  if (index == lastIndex) {
    data.pop();
  } else {
    splice.call(data, index, 1);
  }
  --this.size;
  return true;
}

module.exports = listCacheDelete;


/***/ }),

/***/ 2117:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var assocIndexOf = __webpack_require__(8470);

/**
 * Gets the list cache value for `key`.
 *
 * @private
 * @name get
 * @memberOf ListCache
 * @param {string} key The key of the value to get.
 * @returns {*} Returns the entry value.
 */
function listCacheGet(key) {
  var data = this.__data__,
      index = assocIndexOf(data, key);

  return index < 0 ? undefined : data[index][1];
}

module.exports = listCacheGet;


/***/ }),

/***/ 7518:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var assocIndexOf = __webpack_require__(8470);

/**
 * Checks if a list cache value for `key` exists.
 *
 * @private
 * @name has
 * @memberOf ListCache
 * @param {string} key The key of the entry to check.
 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
 */
function listCacheHas(key) {
  return assocIndexOf(this.__data__, key) > -1;
}

module.exports = listCacheHas;


/***/ }),

/***/ 4705:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var assocIndexOf = __webpack_require__(8470);

/**
 * Sets the list cache `key` to `value`.
 *
 * @private
 * @name set
 * @memberOf ListCache
 * @param {string} key The key of the value to set.
 * @param {*} value The value to set.
 * @returns {Object} Returns the list cache instance.
 */
function listCacheSet(key, value) {
  var data = this.__data__,
      index = assocIndexOf(data, key);

  if (index < 0) {
    ++this.size;
    data.push([key, value]);
  } else {
    data[index][1] = value;
  }
  return this;
}

module.exports = listCacheSet;


/***/ }),

/***/ 4785:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var Hash = __webpack_require__(1989),
    ListCache = __webpack_require__(8407),
    Map = __webpack_require__(7071);

/**
 * Removes all key-value entries from the map.
 *
 * @private
 * @name clear
 * @memberOf MapCache
 */
function mapCacheClear() {
  this.size = 0;
  this.__data__ = {
    'hash': new Hash,
    'map': new (Map || ListCache),
    'string': new Hash
  };
}

module.exports = mapCacheClear;


/***/ }),

/***/ 1285:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getMapData = __webpack_require__(5050);

/**
 * Removes `key` and its value from the map.
 *
 * @private
 * @name delete
 * @memberOf MapCache
 * @param {string} key The key of the value to remove.
 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
 */
function mapCacheDelete(key) {
  var result = getMapData(this, key)['delete'](key);
  this.size -= result ? 1 : 0;
  return result;
}

module.exports = mapCacheDelete;


/***/ }),

/***/ 6000:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getMapData = __webpack_require__(5050);

/**
 * Gets the map value for `key`.
 *
 * @private
 * @name get
 * @memberOf MapCache
 * @param {string} key The key of the value to get.
 * @returns {*} Returns the entry value.
 */
function mapCacheGet(key) {
  return getMapData(this, key).get(key);
}

module.exports = mapCacheGet;


/***/ }),

/***/ 9916:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getMapData = __webpack_require__(5050);

/**
 * Checks if a map value for `key` exists.
 *
 * @private
 * @name has
 * @memberOf MapCache
 * @param {string} key The key of the entry to check.
 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
 */
function mapCacheHas(key) {
  return getMapData(this, key).has(key);
}

module.exports = mapCacheHas;


/***/ }),

/***/ 5265:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getMapData = __webpack_require__(5050);

/**
 * Sets the map `key` to `value`.
 *
 * @private
 * @name set
 * @memberOf MapCache
 * @param {string} key The key of the value to set.
 * @param {*} value The value to set.
 * @returns {Object} Returns the map cache instance.
 */
function mapCacheSet(key, value) {
  var data = getMapData(this, key),
      size = data.size;

  data.set(key, value);
  this.size += data.size == size ? 0 : 1;
  return this;
}

module.exports = mapCacheSet;


/***/ }),

/***/ 8776:
/***/ (function(module) {

/**
 * Converts `map` to its key-value pairs.
 *
 * @private
 * @param {Object} map The map to convert.
 * @returns {Array} Returns the key-value pairs.
 */
function mapToArray(map) {
  var index = -1,
      result = Array(map.size);

  map.forEach(function(value, key) {
    result[++index] = [key, value];
  });
  return result;
}

module.exports = mapToArray;


/***/ }),

/***/ 2634:
/***/ (function(module) {

/**
 * A specialized version of `matchesProperty` for source values suitable
 * for strict equality comparisons, i.e. `===`.
 *
 * @private
 * @param {string} key The key of the property to get.
 * @param {*} srcValue The value to match.
 * @returns {Function} Returns the new spec function.
 */
function matchesStrictComparable(key, srcValue) {
  return function(object) {
    if (object == null) {
      return false;
    }
    return object[key] === srcValue &&
      (srcValue !== undefined || (key in Object(object)));
  };
}

module.exports = matchesStrictComparable;


/***/ }),

/***/ 4523:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var memoize = __webpack_require__(8306);

/** Used as the maximum memoize cache size. */
var MAX_MEMOIZE_SIZE = 500;

/**
 * A specialized version of `_.memoize` which clears the memoized function's
 * cache when it exceeds `MAX_MEMOIZE_SIZE`.
 *
 * @private
 * @param {Function} func The function to have its output memoized.
 * @returns {Function} Returns the new memoized function.
 */
function memoizeCapped(func) {
  var result = memoize(func, function(key) {
    if (cache.size === MAX_MEMOIZE_SIZE) {
      cache.clear();
    }
    return key;
  });

  var cache = result.cache;
  return result;
}

module.exports = memoizeCapped;


/***/ }),

/***/ 4536:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var getNative = __webpack_require__(852);

/* Built-in method references that are verified to be native. */
var nativeCreate = getNative(Object, 'create');

module.exports = nativeCreate;


/***/ }),

/***/ 6916:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var overArg = __webpack_require__(5569);

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeKeys = overArg(Object.keys, Object);

module.exports = nativeKeys;


/***/ }),

/***/ 1167:
/***/ (function(module, exports, __webpack_require__) {

/* module decorator */ module = __webpack_require__.nmd(module);
var freeGlobal = __webpack_require__(1957);

/** Detect free variable `exports`. */
var freeExports =  true && exports && !exports.nodeType && exports;

/** Detect free variable `module`. */
var freeModule = freeExports && "object" == 'object' && module && !module.nodeType && module;

/** Detect the popular CommonJS extension `module.exports`. */
var moduleExports = freeModule && freeModule.exports === freeExports;

/** Detect free variable `process` from Node.js. */
var freeProcess = moduleExports && freeGlobal.process;

/** Used to access faster Node.js helpers. */
var nodeUtil = (function() {
  try {
    // Use `util.types` for Node.js 10+.
    var types = freeModule && freeModule.require && freeModule.require('util').types;

    if (types) {
      return types;
    }

    // Legacy `process.binding('util')` for Node.js < 10.
    return freeProcess && freeProcess.binding && freeProcess.binding('util');
  } catch (e) {}
}());

module.exports = nodeUtil;


/***/ }),

/***/ 2333:
/***/ (function(module) {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

module.exports = objectToString;


/***/ }),

/***/ 5569:
/***/ (function(module) {

/**
 * Creates a unary function that invokes `func` with its argument transformed.
 *
 * @private
 * @param {Function} func The function to wrap.
 * @param {Function} transform The argument transform.
 * @returns {Function} Returns the new function.
 */
function overArg(func, transform) {
  return function(arg) {
    return func(transform(arg));
  };
}

module.exports = overArg;


/***/ }),

/***/ 5357:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var apply = __webpack_require__(6874);

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeMax = Math.max;

/**
 * A specialized version of `baseRest` which transforms the rest array.
 *
 * @private
 * @param {Function} func The function to apply a rest parameter to.
 * @param {number} [start=func.length-1] The start position of the rest parameter.
 * @param {Function} transform The rest array transform.
 * @returns {Function} Returns the new function.
 */
function overRest(func, start, transform) {
  start = nativeMax(start === undefined ? (func.length - 1) : start, 0);
  return function() {
    var args = arguments,
        index = -1,
        length = nativeMax(args.length - start, 0),
        array = Array(length);

    while (++index < length) {
      array[index] = args[start + index];
    }
    index = -1;
    var otherArgs = Array(start + 1);
    while (++index < start) {
      otherArgs[index] = args[index];
    }
    otherArgs[start] = transform(array);
    return apply(func, this, otherArgs);
  };
}

module.exports = overRest;


/***/ }),

/***/ 292:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGet = __webpack_require__(7786),
    baseSlice = __webpack_require__(4259);

/**
 * Gets the parent value at `path` of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Array} path The path to get the parent value of.
 * @returns {*} Returns the parent value.
 */
function parent(object, path) {
  return path.length < 2 ? object : baseGet(object, baseSlice(path, 0, -1));
}

module.exports = parent;


/***/ }),

/***/ 5639:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var freeGlobal = __webpack_require__(1957);

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

module.exports = root;


/***/ }),

/***/ 619:
/***/ (function(module) {

/** Used to stand-in for `undefined` hash values. */
var HASH_UNDEFINED = '__lodash_hash_undefined__';

/**
 * Adds `value` to the array cache.
 *
 * @private
 * @name add
 * @memberOf SetCache
 * @alias push
 * @param {*} value The value to cache.
 * @returns {Object} Returns the cache instance.
 */
function setCacheAdd(value) {
  this.__data__.set(value, HASH_UNDEFINED);
  return this;
}

module.exports = setCacheAdd;


/***/ }),

/***/ 2385:
/***/ (function(module) {

/**
 * Checks if `value` is in the array cache.
 *
 * @private
 * @name has
 * @memberOf SetCache
 * @param {*} value The value to search for.
 * @returns {number} Returns `true` if `value` is found, else `false`.
 */
function setCacheHas(value) {
  return this.__data__.has(value);
}

module.exports = setCacheHas;


/***/ }),

/***/ 1814:
/***/ (function(module) {

/**
 * Converts `set` to an array of its values.
 *
 * @private
 * @param {Object} set The set to convert.
 * @returns {Array} Returns the values.
 */
function setToArray(set) {
  var index = -1,
      result = Array(set.size);

  set.forEach(function(value) {
    result[++index] = value;
  });
  return result;
}

module.exports = setToArray;


/***/ }),

/***/ 61:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseSetToString = __webpack_require__(6560),
    shortOut = __webpack_require__(1275);

/**
 * Sets the `toString` method of `func` to return `string`.
 *
 * @private
 * @param {Function} func The function to modify.
 * @param {Function} string The `toString` result.
 * @returns {Function} Returns `func`.
 */
var setToString = shortOut(baseSetToString);

module.exports = setToString;


/***/ }),

/***/ 1275:
/***/ (function(module) {

/** Used to detect hot functions by number of calls within a span of milliseconds. */
var HOT_COUNT = 800,
    HOT_SPAN = 16;

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeNow = Date.now;

/**
 * Creates a function that'll short out and invoke `identity` instead
 * of `func` when it's called `HOT_COUNT` or more times in `HOT_SPAN`
 * milliseconds.
 *
 * @private
 * @param {Function} func The function to restrict.
 * @returns {Function} Returns the new shortable function.
 */
function shortOut(func) {
  var count = 0,
      lastCalled = 0;

  return function() {
    var stamp = nativeNow(),
        remaining = HOT_SPAN - (stamp - lastCalled);

    lastCalled = stamp;
    if (remaining > 0) {
      if (++count >= HOT_COUNT) {
        return arguments[0];
      }
    } else {
      count = 0;
    }
    return func.apply(undefined, arguments);
  };
}

module.exports = shortOut;


/***/ }),

/***/ 7465:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var ListCache = __webpack_require__(8407);

/**
 * Removes all key-value entries from the stack.
 *
 * @private
 * @name clear
 * @memberOf Stack
 */
function stackClear() {
  this.__data__ = new ListCache;
  this.size = 0;
}

module.exports = stackClear;


/***/ }),

/***/ 3779:
/***/ (function(module) {

/**
 * Removes `key` and its value from the stack.
 *
 * @private
 * @name delete
 * @memberOf Stack
 * @param {string} key The key of the value to remove.
 * @returns {boolean} Returns `true` if the entry was removed, else `false`.
 */
function stackDelete(key) {
  var data = this.__data__,
      result = data['delete'](key);

  this.size = data.size;
  return result;
}

module.exports = stackDelete;


/***/ }),

/***/ 7599:
/***/ (function(module) {

/**
 * Gets the stack value for `key`.
 *
 * @private
 * @name get
 * @memberOf Stack
 * @param {string} key The key of the value to get.
 * @returns {*} Returns the entry value.
 */
function stackGet(key) {
  return this.__data__.get(key);
}

module.exports = stackGet;


/***/ }),

/***/ 4758:
/***/ (function(module) {

/**
 * Checks if a stack value for `key` exists.
 *
 * @private
 * @name has
 * @memberOf Stack
 * @param {string} key The key of the entry to check.
 * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
 */
function stackHas(key) {
  return this.__data__.has(key);
}

module.exports = stackHas;


/***/ }),

/***/ 4309:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var ListCache = __webpack_require__(8407),
    Map = __webpack_require__(7071),
    MapCache = __webpack_require__(3369);

/** Used as the size to enable large array optimizations. */
var LARGE_ARRAY_SIZE = 200;

/**
 * Sets the stack `key` to `value`.
 *
 * @private
 * @name set
 * @memberOf Stack
 * @param {string} key The key of the value to set.
 * @param {*} value The value to set.
 * @returns {Object} Returns the stack cache instance.
 */
function stackSet(key, value) {
  var data = this.__data__;
  if (data instanceof ListCache) {
    var pairs = data.__data__;
    if (!Map || (pairs.length < LARGE_ARRAY_SIZE - 1)) {
      pairs.push([key, value]);
      this.size = ++data.size;
      return this;
    }
    data = this.__data__ = new MapCache(pairs);
  }
  data.set(key, value);
  this.size = data.size;
  return this;
}

module.exports = stackSet;


/***/ }),

/***/ 2351:
/***/ (function(module) {

/**
 * A specialized version of `_.indexOf` which performs strict equality
 * comparisons of values, i.e. `===`.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {*} value The value to search for.
 * @param {number} fromIndex The index to search from.
 * @returns {number} Returns the index of the matched value, else `-1`.
 */
function strictIndexOf(array, value, fromIndex) {
  var index = fromIndex - 1,
      length = array.length;

  while (++index < length) {
    if (array[index] === value) {
      return index;
    }
  }
  return -1;
}

module.exports = strictIndexOf;


/***/ }),

/***/ 5514:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var memoizeCapped = __webpack_require__(4523);

/** Used to match property names within property paths. */
var rePropName = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g;

/** Used to match backslashes in property paths. */
var reEscapeChar = /\\(\\)?/g;

/**
 * Converts `string` to a property path array.
 *
 * @private
 * @param {string} string The string to convert.
 * @returns {Array} Returns the property path array.
 */
var stringToPath = memoizeCapped(function(string) {
  var result = [];
  if (string.charCodeAt(0) === 46 /* . */) {
    result.push('');
  }
  string.replace(rePropName, function(match, number, quote, subString) {
    result.push(quote ? subString.replace(reEscapeChar, '$1') : (number || match));
  });
  return result;
});

module.exports = stringToPath;


/***/ }),

/***/ 327:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isSymbol = __webpack_require__(3448);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/**
 * Converts `value` to a string key if it's not a string or symbol.
 *
 * @private
 * @param {*} value The value to inspect.
 * @returns {string|symbol} Returns the key.
 */
function toKey(value) {
  if (typeof value == 'string' || isSymbol(value)) {
    return value;
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

module.exports = toKey;


/***/ }),

/***/ 346:
/***/ (function(module) {

/** Used for built-in method references. */
var funcProto = Function.prototype;

/** Used to resolve the decompiled source of functions. */
var funcToString = funcProto.toString;

/**
 * Converts `func` to its source code.
 *
 * @private
 * @param {Function} func The function to convert.
 * @returns {string} Returns the source code.
 */
function toSource(func) {
  if (func != null) {
    try {
      return funcToString.call(func);
    } catch (e) {}
    try {
      return (func + '');
    } catch (e) {}
  }
  return '';
}

module.exports = toSource;


/***/ }),

/***/ 7990:
/***/ (function(module) {

/** Used to match a single whitespace character. */
var reWhitespace = /\s/;

/**
 * Used by `_.trim` and `_.trimEnd` to get the index of the last non-whitespace
 * character of `string`.
 *
 * @private
 * @param {string} string The string to inspect.
 * @returns {number} Returns the index of the last non-whitespace character.
 */
function trimmedEndIndex(string) {
  var index = string.length;

  while (index-- && reWhitespace.test(string.charAt(index))) {}
  return index;
}

module.exports = trimmedEndIndex;


/***/ }),

/***/ 3729:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var basePropertyOf = __webpack_require__(8674);

/** Used to map HTML entities to characters. */
var htmlUnescapes = {
  '&amp;': '&',
  '&lt;': '<',
  '&gt;': '>',
  '&quot;': '"',
  '&#39;': "'"
};

/**
 * Used by `_.unescape` to convert HTML entities to characters.
 *
 * @private
 * @param {string} chr The matched character to unescape.
 * @returns {string} Returns the unescaped character.
 */
var unescapeHtmlChar = basePropertyOf(htmlUnescapes);

module.exports = unescapeHtmlChar;


/***/ }),

/***/ 5703:
/***/ (function(module) {

/**
 * Creates a function that returns `value`.
 *
 * @static
 * @memberOf _
 * @since 2.4.0
 * @category Util
 * @param {*} value The value to return from the new function.
 * @returns {Function} Returns the new constant function.
 * @example
 *
 * var objects = _.times(2, _.constant({ 'a': 1 }));
 *
 * console.log(objects);
 * // => [{ 'a': 1 }, { 'a': 1 }]
 *
 * console.log(objects[0] === objects[1]);
 * // => true
 */
function constant(value) {
  return function() {
    return value;
  };
}

module.exports = constant;


/***/ }),

/***/ 3279:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isObject = __webpack_require__(3218),
    now = __webpack_require__(7771),
    toNumber = __webpack_require__(4841);

/** Error message constants. */
var FUNC_ERROR_TEXT = 'Expected a function';

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeMax = Math.max,
    nativeMin = Math.min;

/**
 * Creates a debounced function that delays invoking `func` until after `wait`
 * milliseconds have elapsed since the last time the debounced function was
 * invoked. The debounced function comes with a `cancel` method to cancel
 * delayed `func` invocations and a `flush` method to immediately invoke them.
 * Provide `options` to indicate whether `func` should be invoked on the
 * leading and/or trailing edge of the `wait` timeout. The `func` is invoked
 * with the last arguments provided to the debounced function. Subsequent
 * calls to the debounced function return the result of the last `func`
 * invocation.
 *
 * **Note:** If `leading` and `trailing` options are `true`, `func` is
 * invoked on the trailing edge of the timeout only if the debounced function
 * is invoked more than once during the `wait` timeout.
 *
 * If `wait` is `0` and `leading` is `false`, `func` invocation is deferred
 * until to the next tick, similar to `setTimeout` with a timeout of `0`.
 *
 * See [David Corbacho's article](https://css-tricks.com/debouncing-throttling-explained-examples/)
 * for details over the differences between `_.debounce` and `_.throttle`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Function
 * @param {Function} func The function to debounce.
 * @param {number} [wait=0] The number of milliseconds to delay.
 * @param {Object} [options={}] The options object.
 * @param {boolean} [options.leading=false]
 *  Specify invoking on the leading edge of the timeout.
 * @param {number} [options.maxWait]
 *  The maximum time `func` is allowed to be delayed before it's invoked.
 * @param {boolean} [options.trailing=true]
 *  Specify invoking on the trailing edge of the timeout.
 * @returns {Function} Returns the new debounced function.
 * @example
 *
 * // Avoid costly calculations while the window size is in flux.
 * jQuery(window).on('resize', _.debounce(calculateLayout, 150));
 *
 * // Invoke `sendMail` when clicked, debouncing subsequent calls.
 * jQuery(element).on('click', _.debounce(sendMail, 300, {
 *   'leading': true,
 *   'trailing': false
 * }));
 *
 * // Ensure `batchLog` is invoked once after 1 second of debounced calls.
 * var debounced = _.debounce(batchLog, 250, { 'maxWait': 1000 });
 * var source = new EventSource('/stream');
 * jQuery(source).on('message', debounced);
 *
 * // Cancel the trailing debounced invocation.
 * jQuery(window).on('popstate', debounced.cancel);
 */
function debounce(func, wait, options) {
  var lastArgs,
      lastThis,
      maxWait,
      result,
      timerId,
      lastCallTime,
      lastInvokeTime = 0,
      leading = false,
      maxing = false,
      trailing = true;

  if (typeof func != 'function') {
    throw new TypeError(FUNC_ERROR_TEXT);
  }
  wait = toNumber(wait) || 0;
  if (isObject(options)) {
    leading = !!options.leading;
    maxing = 'maxWait' in options;
    maxWait = maxing ? nativeMax(toNumber(options.maxWait) || 0, wait) : maxWait;
    trailing = 'trailing' in options ? !!options.trailing : trailing;
  }

  function invokeFunc(time) {
    var args = lastArgs,
        thisArg = lastThis;

    lastArgs = lastThis = undefined;
    lastInvokeTime = time;
    result = func.apply(thisArg, args);
    return result;
  }

  function leadingEdge(time) {
    // Reset any `maxWait` timer.
    lastInvokeTime = time;
    // Start the timer for the trailing edge.
    timerId = setTimeout(timerExpired, wait);
    // Invoke the leading edge.
    return leading ? invokeFunc(time) : result;
  }

  function remainingWait(time) {
    var timeSinceLastCall = time - lastCallTime,
        timeSinceLastInvoke = time - lastInvokeTime,
        timeWaiting = wait - timeSinceLastCall;

    return maxing
      ? nativeMin(timeWaiting, maxWait - timeSinceLastInvoke)
      : timeWaiting;
  }

  function shouldInvoke(time) {
    var timeSinceLastCall = time - lastCallTime,
        timeSinceLastInvoke = time - lastInvokeTime;

    // Either this is the first call, activity has stopped and we're at the
    // trailing edge, the system time has gone backwards and we're treating
    // it as the trailing edge, or we've hit the `maxWait` limit.
    return (lastCallTime === undefined || (timeSinceLastCall >= wait) ||
      (timeSinceLastCall < 0) || (maxing && timeSinceLastInvoke >= maxWait));
  }

  function timerExpired() {
    var time = now();
    if (shouldInvoke(time)) {
      return trailingEdge(time);
    }
    // Restart the timer.
    timerId = setTimeout(timerExpired, remainingWait(time));
  }

  function trailingEdge(time) {
    timerId = undefined;

    // Only invoke if we have `lastArgs` which means `func` has been
    // debounced at least once.
    if (trailing && lastArgs) {
      return invokeFunc(time);
    }
    lastArgs = lastThis = undefined;
    return result;
  }

  function cancel() {
    if (timerId !== undefined) {
      clearTimeout(timerId);
    }
    lastInvokeTime = 0;
    lastArgs = lastCallTime = lastThis = timerId = undefined;
  }

  function flush() {
    return timerId === undefined ? result : trailingEdge(now());
  }

  function debounced() {
    var time = now(),
        isInvoking = shouldInvoke(time);

    lastArgs = arguments;
    lastThis = this;
    lastCallTime = time;

    if (isInvoking) {
      if (timerId === undefined) {
        return leadingEdge(lastCallTime);
      }
      if (maxing) {
        // Handle invocations in a tight loop.
        clearTimeout(timerId);
        timerId = setTimeout(timerExpired, wait);
        return invokeFunc(lastCallTime);
      }
    }
    if (timerId === undefined) {
      timerId = setTimeout(timerExpired, wait);
    }
    return result;
  }
  debounced.cancel = cancel;
  debounced.flush = flush;
  return debounced;
}

module.exports = debounce;


/***/ }),

/***/ 7813:
/***/ (function(module) {

/**
 * Performs a
 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * comparison between two values to determine if they are equivalent.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 * @example
 *
 * var object = { 'a': 1 };
 * var other = { 'a': 1 };
 *
 * _.eq(object, object);
 * // => true
 *
 * _.eq(object, other);
 * // => false
 *
 * _.eq('a', 'a');
 * // => true
 *
 * _.eq('a', Object('a'));
 * // => false
 *
 * _.eq(NaN, NaN);
 * // => true
 */
function eq(value, other) {
  return value === other || (value !== value && other !== other);
}

module.exports = eq;


/***/ }),

/***/ 3311:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var createFind = __webpack_require__(7740),
    findIndex = __webpack_require__(998);

/**
 * Iterates over elements of `collection`, returning the first element
 * `predicate` returns truthy for. The predicate is invoked with three
 * arguments: (value, index|key, collection).
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Collection
 * @param {Array|Object} collection The collection to inspect.
 * @param {Function} [predicate=_.identity] The function invoked per iteration.
 * @param {number} [fromIndex=0] The index to search from.
 * @returns {*} Returns the matched element, else `undefined`.
 * @example
 *
 * var users = [
 *   { 'user': 'barney',  'age': 36, 'active': true },
 *   { 'user': 'fred',    'age': 40, 'active': false },
 *   { 'user': 'pebbles', 'age': 1,  'active': true }
 * ];
 *
 * _.find(users, function(o) { return o.age < 40; });
 * // => object for 'barney'
 *
 * // The `_.matches` iteratee shorthand.
 * _.find(users, { 'age': 1, 'active': true });
 * // => object for 'pebbles'
 *
 * // The `_.matchesProperty` iteratee shorthand.
 * _.find(users, ['active', false]);
 * // => object for 'fred'
 *
 * // The `_.property` iteratee shorthand.
 * _.find(users, 'active');
 * // => object for 'barney'
 */
var find = createFind(findIndex);

module.exports = find;


/***/ }),

/***/ 998:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseFindIndex = __webpack_require__(1848),
    baseIteratee = __webpack_require__(7206),
    toInteger = __webpack_require__(554);

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeMax = Math.max;

/**
 * This method is like `_.find` except that it returns the index of the first
 * element `predicate` returns truthy for instead of the element itself.
 *
 * @static
 * @memberOf _
 * @since 1.1.0
 * @category Array
 * @param {Array} array The array to inspect.
 * @param {Function} [predicate=_.identity] The function invoked per iteration.
 * @param {number} [fromIndex=0] The index to search from.
 * @returns {number} Returns the index of the found element, else `-1`.
 * @example
 *
 * var users = [
 *   { 'user': 'barney',  'active': false },
 *   { 'user': 'fred',    'active': false },
 *   { 'user': 'pebbles', 'active': true }
 * ];
 *
 * _.findIndex(users, function(o) { return o.user == 'barney'; });
 * // => 0
 *
 * // The `_.matches` iteratee shorthand.
 * _.findIndex(users, { 'user': 'fred', 'active': false });
 * // => 1
 *
 * // The `_.matchesProperty` iteratee shorthand.
 * _.findIndex(users, ['active', false]);
 * // => 0
 *
 * // The `_.property` iteratee shorthand.
 * _.findIndex(users, 'active');
 * // => 2
 */
function findIndex(array, predicate, fromIndex) {
  var length = array == null ? 0 : array.length;
  if (!length) {
    return -1;
  }
  var index = fromIndex == null ? 0 : toInteger(fromIndex);
  if (index < 0) {
    index = nativeMax(length + index, 0);
  }
  return baseFindIndex(array, baseIteratee(predicate, 3), index);
}

module.exports = findIndex;


/***/ }),

/***/ 7361:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGet = __webpack_require__(7786);

/**
 * Gets the value at `path` of `object`. If the resolved value is
 * `undefined`, the `defaultValue` is returned in its place.
 *
 * @static
 * @memberOf _
 * @since 3.7.0
 * @category Object
 * @param {Object} object The object to query.
 * @param {Array|string} path The path of the property to get.
 * @param {*} [defaultValue] The value returned for `undefined` resolved values.
 * @returns {*} Returns the resolved value.
 * @example
 *
 * var object = { 'a': [{ 'b': { 'c': 3 } }] };
 *
 * _.get(object, 'a[0].b.c');
 * // => 3
 *
 * _.get(object, ['a', '0', 'b', 'c']);
 * // => 3
 *
 * _.get(object, 'a.b.c', 'default');
 * // => 'default'
 */
function get(object, path, defaultValue) {
  var result = object == null ? undefined : baseGet(object, path);
  return result === undefined ? defaultValue : result;
}

module.exports = get;


/***/ }),

/***/ 7739:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseAssignValue = __webpack_require__(9465),
    createAggregator = __webpack_require__(5189);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Creates an object composed of keys generated from the results of running
 * each element of `collection` thru `iteratee`. The order of grouped values
 * is determined by the order they occur in `collection`. The corresponding
 * value of each key is an array of elements responsible for generating the
 * key. The iteratee is invoked with one argument: (value).
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Collection
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} [iteratee=_.identity] The iteratee to transform keys.
 * @returns {Object} Returns the composed aggregate object.
 * @example
 *
 * _.groupBy([6.1, 4.2, 6.3], Math.floor);
 * // => { '4': [4.2], '6': [6.1, 6.3] }
 *
 * // The `_.property` iteratee shorthand.
 * _.groupBy(['one', 'two', 'three'], 'length');
 * // => { '3': ['one', 'two'], '5': ['three'] }
 */
var groupBy = createAggregator(function(result, value, key) {
  if (hasOwnProperty.call(result, key)) {
    result[key].push(value);
  } else {
    baseAssignValue(result, key, [value]);
  }
});

module.exports = groupBy;


/***/ }),

/***/ 9095:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseHasIn = __webpack_require__(13),
    hasPath = __webpack_require__(222);

/**
 * Checks if `path` is a direct or inherited property of `object`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Object
 * @param {Object} object The object to query.
 * @param {Array|string} path The path to check.
 * @returns {boolean} Returns `true` if `path` exists, else `false`.
 * @example
 *
 * var object = _.create({ 'a': _.create({ 'b': 2 }) });
 *
 * _.hasIn(object, 'a');
 * // => true
 *
 * _.hasIn(object, 'a.b');
 * // => true
 *
 * _.hasIn(object, ['a', 'b']);
 * // => true
 *
 * _.hasIn(object, 'b');
 * // => false
 */
function hasIn(object, path) {
  return object != null && hasPath(object, path, baseHasIn);
}

module.exports = hasIn;


/***/ }),

/***/ 6557:
/***/ (function(module) {

/**
 * This method returns the first argument it receives.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Util
 * @param {*} value Any value.
 * @returns {*} Returns `value`.
 * @example
 *
 * var object = { 'a': 1 };
 *
 * console.log(_.identity(object) === object);
 * // => true
 */
function identity(value) {
  return value;
}

module.exports = identity;


/***/ }),

/***/ 5907:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseInvoke = __webpack_require__(3783),
    baseRest = __webpack_require__(5976);

/**
 * Invokes the method at `path` of `object`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Object
 * @param {Object} object The object to query.
 * @param {Array|string} path The path of the method to invoke.
 * @param {...*} [args] The arguments to invoke the method with.
 * @returns {*} Returns the result of the invoked method.
 * @example
 *
 * var object = { 'a': [{ 'b': { 'c': [1, 2, 3, 4] } }] };
 *
 * _.invoke(object, 'a[0].b.c.slice', 1, 3);
 * // => [2, 3]
 */
var invoke = baseRest(baseInvoke);

module.exports = invoke;


/***/ }),

/***/ 5694:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsArguments = __webpack_require__(9454),
    isObjectLike = __webpack_require__(7005);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/** Built-in value references. */
var propertyIsEnumerable = objectProto.propertyIsEnumerable;

/**
 * Checks if `value` is likely an `arguments` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 *  else `false`.
 * @example
 *
 * _.isArguments(function() { return arguments; }());
 * // => true
 *
 * _.isArguments([1, 2, 3]);
 * // => false
 */
var isArguments = baseIsArguments(function() { return arguments; }()) ? baseIsArguments : function(value) {
  return isObjectLike(value) && hasOwnProperty.call(value, 'callee') &&
    !propertyIsEnumerable.call(value, 'callee');
};

module.exports = isArguments;


/***/ }),

/***/ 1469:
/***/ (function(module) {

/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

module.exports = isArray;


/***/ }),

/***/ 8612:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isFunction = __webpack_require__(3560),
    isLength = __webpack_require__(1780);

/**
 * Checks if `value` is array-like. A value is considered array-like if it's
 * not a function and has a `value.length` that's an integer greater than or
 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
 * @example
 *
 * _.isArrayLike([1, 2, 3]);
 * // => true
 *
 * _.isArrayLike(document.body.children);
 * // => true
 *
 * _.isArrayLike('abc');
 * // => true
 *
 * _.isArrayLike(_.noop);
 * // => false
 */
function isArrayLike(value) {
  return value != null && isLength(value.length) && !isFunction(value);
}

module.exports = isArrayLike;


/***/ }),

/***/ 9246:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var isArrayLike = __webpack_require__(8612),
    isObjectLike = __webpack_require__(7005);

/**
 * This method is like `_.isArrayLike` except that it also checks if `value`
 * is an object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array-like object,
 *  else `false`.
 * @example
 *
 * _.isArrayLikeObject([1, 2, 3]);
 * // => true
 *
 * _.isArrayLikeObject(document.body.children);
 * // => true
 *
 * _.isArrayLikeObject('abc');
 * // => false
 *
 * _.isArrayLikeObject(_.noop);
 * // => false
 */
function isArrayLikeObject(value) {
  return isObjectLike(value) && isArrayLike(value);
}

module.exports = isArrayLikeObject;


/***/ }),

/***/ 4144:
/***/ (function(module, exports, __webpack_require__) {

/* module decorator */ module = __webpack_require__.nmd(module);
var root = __webpack_require__(5639),
    stubFalse = __webpack_require__(5062);

/** Detect free variable `exports`. */
var freeExports =  true && exports && !exports.nodeType && exports;

/** Detect free variable `module`. */
var freeModule = freeExports && "object" == 'object' && module && !module.nodeType && module;

/** Detect the popular CommonJS extension `module.exports`. */
var moduleExports = freeModule && freeModule.exports === freeExports;

/** Built-in value references. */
var Buffer = moduleExports ? root.Buffer : undefined;

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeIsBuffer = Buffer ? Buffer.isBuffer : undefined;

/**
 * Checks if `value` is a buffer.
 *
 * @static
 * @memberOf _
 * @since 4.3.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a buffer, else `false`.
 * @example
 *
 * _.isBuffer(new Buffer(2));
 * // => true
 *
 * _.isBuffer(new Uint8Array(2));
 * // => false
 */
var isBuffer = nativeIsBuffer || stubFalse;

module.exports = isBuffer;


/***/ }),

/***/ 1609:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseKeys = __webpack_require__(280),
    getTag = __webpack_require__(4160),
    isArguments = __webpack_require__(5694),
    isArray = __webpack_require__(1469),
    isArrayLike = __webpack_require__(8612),
    isBuffer = __webpack_require__(4144),
    isPrototype = __webpack_require__(5726),
    isTypedArray = __webpack_require__(6719);

/** `Object#toString` result references. */
var mapTag = '[object Map]',
    setTag = '[object Set]';

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Checks if `value` is an empty object, collection, map, or set.
 *
 * Objects are considered empty if they have no own enumerable string keyed
 * properties.
 *
 * Array-like values such as `arguments` objects, arrays, buffers, strings, or
 * jQuery-like collections are considered empty if they have a `length` of `0`.
 * Similarly, maps and sets are considered empty if they have a `size` of `0`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is empty, else `false`.
 * @example
 *
 * _.isEmpty(null);
 * // => true
 *
 * _.isEmpty(true);
 * // => true
 *
 * _.isEmpty(1);
 * // => true
 *
 * _.isEmpty([1, 2, 3]);
 * // => false
 *
 * _.isEmpty({ 'a': 1 });
 * // => false
 */
function isEmpty(value) {
  if (value == null) {
    return true;
  }
  if (isArrayLike(value) &&
      (isArray(value) || typeof value == 'string' || typeof value.splice == 'function' ||
        isBuffer(value) || isTypedArray(value) || isArguments(value))) {
    return !value.length;
  }
  var tag = getTag(value);
  if (tag == mapTag || tag == setTag) {
    return !value.size;
  }
  if (isPrototype(value)) {
    return !baseKeys(value).length;
  }
  for (var key in value) {
    if (hasOwnProperty.call(value, key)) {
      return false;
    }
  }
  return true;
}

module.exports = isEmpty;


/***/ }),

/***/ 8446:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsEqual = __webpack_require__(939);

/**
 * Performs a deep comparison between two values to determine if they are
 * equivalent.
 *
 * **Note:** This method supports comparing arrays, array buffers, booleans,
 * date objects, error objects, maps, numbers, `Object` objects, regexes,
 * sets, strings, symbols, and typed arrays. `Object` objects are compared
 * by their own, not inherited, enumerable properties. Functions and DOM
 * nodes are compared by strict equality, i.e. `===`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 * @example
 *
 * var object = { 'a': 1 };
 * var other = { 'a': 1 };
 *
 * _.isEqual(object, other);
 * // => true
 *
 * object === other;
 * // => false
 */
function isEqual(value, other) {
  return baseIsEqual(value, other);
}

module.exports = isEqual;


/***/ }),

/***/ 3560:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGetTag = __webpack_require__(4239),
    isObject = __webpack_require__(3218);

/** `Object#toString` result references. */
var asyncTag = '[object AsyncFunction]',
    funcTag = '[object Function]',
    genTag = '[object GeneratorFunction]',
    proxyTag = '[object Proxy]';

/**
 * Checks if `value` is classified as a `Function` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a function, else `false`.
 * @example
 *
 * _.isFunction(_);
 * // => true
 *
 * _.isFunction(/abc/);
 * // => false
 */
function isFunction(value) {
  if (!isObject(value)) {
    return false;
  }
  // The use of `Object#toString` avoids issues with the `typeof` operator
  // in Safari 9 which returns 'object' for typed arrays and other constructors.
  var tag = baseGetTag(value);
  return tag == funcTag || tag == genTag || tag == asyncTag || tag == proxyTag;
}

module.exports = isFunction;


/***/ }),

/***/ 1780:
/***/ (function(module) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/**
 * Checks if `value` is a valid array-like length.
 *
 * **Note:** This method is loosely based on
 * [`ToLength`](http://ecma-international.org/ecma-262/7.0/#sec-tolength).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
 * @example
 *
 * _.isLength(3);
 * // => true
 *
 * _.isLength(Number.MIN_VALUE);
 * // => false
 *
 * _.isLength(Infinity);
 * // => false
 *
 * _.isLength('3');
 * // => false
 */
function isLength(value) {
  return typeof value == 'number' &&
    value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
}

module.exports = isLength;


/***/ }),

/***/ 3218:
/***/ (function(module) {

/**
 * Checks if `value` is the
 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
 * @example
 *
 * _.isObject({});
 * // => true
 *
 * _.isObject([1, 2, 3]);
 * // => true
 *
 * _.isObject(_.noop);
 * // => true
 *
 * _.isObject(null);
 * // => false
 */
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function');
}

module.exports = isObject;


/***/ }),

/***/ 7005:
/***/ (function(module) {

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

module.exports = isObjectLike;


/***/ }),

/***/ 3448:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseGetTag = __webpack_require__(4239),
    isObjectLike = __webpack_require__(7005);

/** `Object#toString` result references. */
var symbolTag = '[object Symbol]';

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

module.exports = isSymbol;


/***/ }),

/***/ 6719:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIsTypedArray = __webpack_require__(8749),
    baseUnary = __webpack_require__(1717),
    nodeUtil = __webpack_require__(1167);

/* Node.js helper references. */
var nodeIsTypedArray = nodeUtil && nodeUtil.isTypedArray;

/**
 * Checks if `value` is classified as a typed array.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 * @example
 *
 * _.isTypedArray(new Uint8Array);
 * // => true
 *
 * _.isTypedArray([]);
 * // => false
 */
var isTypedArray = nodeIsTypedArray ? baseUnary(nodeIsTypedArray) : baseIsTypedArray;

module.exports = isTypedArray;


/***/ }),

/***/ 3674:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var arrayLikeKeys = __webpack_require__(4636),
    baseKeys = __webpack_require__(280),
    isArrayLike = __webpack_require__(8612);

/**
 * Creates an array of the own enumerable property names of `object`.
 *
 * **Note:** Non-object values are coerced to objects. See the
 * [ES spec](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
 * for more details.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Object
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 *   this.b = 2;
 * }
 *
 * Foo.prototype.c = 3;
 *
 * _.keys(new Foo);
 * // => ['a', 'b'] (iteration order is not guaranteed)
 *
 * _.keys('hi');
 * // => ['0', '1']
 */
function keys(object) {
  return isArrayLike(object) ? arrayLikeKeys(object) : baseKeys(object);
}

module.exports = keys;


/***/ }),

/***/ 928:
/***/ (function(module) {

/**
 * Gets the last element of `array`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Array
 * @param {Array} array The array to query.
 * @returns {*} Returns the last element of `array`.
 * @example
 *
 * _.last([1, 2, 3]);
 * // => 3
 */
function last(array) {
  var length = array == null ? 0 : array.length;
  return length ? array[length - 1] : undefined;
}

module.exports = last;


/***/ }),

/***/ 5161:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var arrayMap = __webpack_require__(9932),
    baseIteratee = __webpack_require__(7206),
    baseMap = __webpack_require__(9199),
    isArray = __webpack_require__(1469);

/**
 * Creates an array of values by running each element in `collection` thru
 * `iteratee`. The iteratee is invoked with three arguments:
 * (value, index|key, collection).
 *
 * Many lodash methods are guarded to work as iteratees for methods like
 * `_.every`, `_.filter`, `_.map`, `_.mapValues`, `_.reject`, and `_.some`.
 *
 * The guarded methods are:
 * `ary`, `chunk`, `curry`, `curryRight`, `drop`, `dropRight`, `every`,
 * `fill`, `invert`, `parseInt`, `random`, `range`, `rangeRight`, `repeat`,
 * `sampleSize`, `slice`, `some`, `sortBy`, `split`, `take`, `takeRight`,
 * `template`, `trim`, `trimEnd`, `trimStart`, and `words`
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Collection
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} [iteratee=_.identity] The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 * @example
 *
 * function square(n) {
 *   return n * n;
 * }
 *
 * _.map([4, 8], square);
 * // => [16, 64]
 *
 * _.map({ 'a': 4, 'b': 8 }, square);
 * // => [16, 64] (iteration order is not guaranteed)
 *
 * var users = [
 *   { 'user': 'barney' },
 *   { 'user': 'fred' }
 * ];
 *
 * // The `_.property` iteratee shorthand.
 * _.map(users, 'user');
 * // => ['barney', 'fred']
 */
function map(collection, iteratee) {
  var func = isArray(collection) ? arrayMap : baseMap;
  return func(collection, baseIteratee(iteratee, 3));
}

module.exports = map;


/***/ }),

/***/ 8306:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var MapCache = __webpack_require__(3369);

/** Error message constants. */
var FUNC_ERROR_TEXT = 'Expected a function';

/**
 * Creates a function that memoizes the result of `func`. If `resolver` is
 * provided, it determines the cache key for storing the result based on the
 * arguments provided to the memoized function. By default, the first argument
 * provided to the memoized function is used as the map cache key. The `func`
 * is invoked with the `this` binding of the memoized function.
 *
 * **Note:** The cache is exposed as the `cache` property on the memoized
 * function. Its creation may be customized by replacing the `_.memoize.Cache`
 * constructor with one whose instances implement the
 * [`Map`](http://ecma-international.org/ecma-262/7.0/#sec-properties-of-the-map-prototype-object)
 * method interface of `clear`, `delete`, `get`, `has`, and `set`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Function
 * @param {Function} func The function to have its output memoized.
 * @param {Function} [resolver] The function to resolve the cache key.
 * @returns {Function} Returns the new memoized function.
 * @example
 *
 * var object = { 'a': 1, 'b': 2 };
 * var other = { 'c': 3, 'd': 4 };
 *
 * var values = _.memoize(_.values);
 * values(object);
 * // => [1, 2]
 *
 * values(other);
 * // => [3, 4]
 *
 * object.a = 2;
 * values(object);
 * // => [1, 2]
 *
 * // Modify the result cache.
 * values.cache.set(object, ['a', 'b']);
 * values(object);
 * // => ['a', 'b']
 *
 * // Replace `_.memoize.Cache`.
 * _.memoize.Cache = WeakMap;
 */
function memoize(func, resolver) {
  if (typeof func != 'function' || (resolver != null && typeof resolver != 'function')) {
    throw new TypeError(FUNC_ERROR_TEXT);
  }
  var memoized = function() {
    var args = arguments,
        key = resolver ? resolver.apply(this, args) : args[0],
        cache = memoized.cache;

    if (cache.has(key)) {
      return cache.get(key);
    }
    var result = func.apply(this, args);
    memoized.cache = cache.set(key, result) || cache;
    return result;
  };
  memoized.cache = new (memoize.Cache || MapCache);
  return memoized;
}

// Expose `MapCache`.
memoize.Cache = MapCache;

module.exports = memoize;


/***/ }),

/***/ 308:
/***/ (function(module) {

/**
 * This method returns `undefined`.
 *
 * @static
 * @memberOf _
 * @since 2.3.0
 * @category Util
 * @example
 *
 * _.times(2, _.noop);
 * // => [undefined, undefined]
 */
function noop() {
  // No operation performed.
}

module.exports = noop;


/***/ }),

/***/ 7771:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var root = __webpack_require__(5639);

/**
 * Gets the timestamp of the number of milliseconds that have elapsed since
 * the Unix epoch (1 January 1970 00:00:00 UTC).
 *
 * @static
 * @memberOf _
 * @since 2.4.0
 * @category Date
 * @returns {number} Returns the timestamp.
 * @example
 *
 * _.defer(function(stamp) {
 *   console.log(_.now() - stamp);
 * }, _.now());
 * // => Logs the number of milliseconds it took for the deferred invocation.
 */
var now = function() {
  return root.Date.now();
};

module.exports = now;


/***/ }),

/***/ 9601:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseProperty = __webpack_require__(371),
    basePropertyDeep = __webpack_require__(9152),
    isKey = __webpack_require__(5403),
    toKey = __webpack_require__(327);

/**
 * Creates a function that returns the value at `path` of a given object.
 *
 * @static
 * @memberOf _
 * @since 2.4.0
 * @category Util
 * @param {Array|string} path The path of the property to get.
 * @returns {Function} Returns the new accessor function.
 * @example
 *
 * var objects = [
 *   { 'a': { 'b': 2 } },
 *   { 'a': { 'b': 1 } }
 * ];
 *
 * _.map(objects, _.property('a.b'));
 * // => [2, 1]
 *
 * _.map(_.sortBy(objects, _.property(['a', 'b'])), 'a.b');
 * // => [1, 2]
 */
function property(path) {
  return isKey(path) ? baseProperty(toKey(path)) : basePropertyDeep(path);
}

module.exports = property;


/***/ }),

/***/ 479:
/***/ (function(module) {

/**
 * This method returns a new empty array.
 *
 * @static
 * @memberOf _
 * @since 4.13.0
 * @category Util
 * @returns {Array} Returns the new empty array.
 * @example
 *
 * var arrays = _.times(2, _.stubArray);
 *
 * console.log(arrays);
 * // => [[], []]
 *
 * console.log(arrays[0] === arrays[1]);
 * // => false
 */
function stubArray() {
  return [];
}

module.exports = stubArray;


/***/ }),

/***/ 5062:
/***/ (function(module) {

/**
 * This method returns `false`.
 *
 * @static
 * @memberOf _
 * @since 4.13.0
 * @category Util
 * @returns {boolean} Returns `false`.
 * @example
 *
 * _.times(2, _.stubFalse);
 * // => [false, false]
 */
function stubFalse() {
  return false;
}

module.exports = stubFalse;


/***/ }),

/***/ 8601:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var toNumber = __webpack_require__(4841);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0,
    MAX_INTEGER = 1.7976931348623157e+308;

/**
 * Converts `value` to a finite number.
 *
 * @static
 * @memberOf _
 * @since 4.12.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {number} Returns the converted number.
 * @example
 *
 * _.toFinite(3.2);
 * // => 3.2
 *
 * _.toFinite(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toFinite(Infinity);
 * // => 1.7976931348623157e+308
 *
 * _.toFinite('3.2');
 * // => 3.2
 */
function toFinite(value) {
  if (!value) {
    return value === 0 ? value : 0;
  }
  value = toNumber(value);
  if (value === INFINITY || value === -INFINITY) {
    var sign = (value < 0 ? -1 : 1);
    return sign * MAX_INTEGER;
  }
  return value === value ? value : 0;
}

module.exports = toFinite;


/***/ }),

/***/ 554:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var toFinite = __webpack_require__(8601);

/**
 * Converts `value` to an integer.
 *
 * **Note:** This method is loosely based on
 * [`ToInteger`](http://www.ecma-international.org/ecma-262/7.0/#sec-tointeger).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {number} Returns the converted integer.
 * @example
 *
 * _.toInteger(3.2);
 * // => 3
 *
 * _.toInteger(Number.MIN_VALUE);
 * // => 0
 *
 * _.toInteger(Infinity);
 * // => 1.7976931348623157e+308
 *
 * _.toInteger('3.2');
 * // => 3
 */
function toInteger(value) {
  var result = toFinite(value),
      remainder = result % 1;

  return result === result ? (remainder ? result - remainder : result) : 0;
}

module.exports = toInteger;


/***/ }),

/***/ 4841:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseTrim = __webpack_require__(7561),
    isObject = __webpack_require__(3218),
    isSymbol = __webpack_require__(3448);

/** Used as references for various `Number` constants. */
var NAN = 0 / 0;

/** Used to detect bad signed hexadecimal string values. */
var reIsBadHex = /^[-+]0x[0-9a-f]+$/i;

/** Used to detect binary string values. */
var reIsBinary = /^0b[01]+$/i;

/** Used to detect octal string values. */
var reIsOctal = /^0o[0-7]+$/i;

/** Built-in method references without a dependency on `root`. */
var freeParseInt = parseInt;

/**
 * Converts `value` to a number.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to process.
 * @returns {number} Returns the number.
 * @example
 *
 * _.toNumber(3.2);
 * // => 3.2
 *
 * _.toNumber(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toNumber(Infinity);
 * // => Infinity
 *
 * _.toNumber('3.2');
 * // => 3.2
 */
function toNumber(value) {
  if (typeof value == 'number') {
    return value;
  }
  if (isSymbol(value)) {
    return NAN;
  }
  if (isObject(value)) {
    var other = typeof value.valueOf == 'function' ? value.valueOf() : value;
    value = isObject(other) ? (other + '') : other;
  }
  if (typeof value != 'string') {
    return value === 0 ? value : +value;
  }
  value = baseTrim(value);
  var isBinary = reIsBinary.test(value);
  return (isBinary || reIsOctal.test(value))
    ? freeParseInt(value.slice(2), isBinary ? 2 : 8)
    : (reIsBadHex.test(value) ? NAN : +value);
}

module.exports = toNumber;


/***/ }),

/***/ 9833:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseToString = __webpack_require__(531);

/**
 * Converts `value` to a string. An empty string is returned for `null`
 * and `undefined` values. The sign of `-0` is preserved.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.toString(null);
 * // => ''
 *
 * _.toString(-0);
 * // => '-0'
 *
 * _.toString([1, 2, 3]);
 * // => '1,2,3'
 */
function toString(value) {
  return value == null ? '' : baseToString(value);
}

module.exports = toString;


/***/ }),

/***/ 7955:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var toString = __webpack_require__(9833),
    unescapeHtmlChar = __webpack_require__(3729);

/** Used to match HTML entities and HTML characters. */
var reEscapedHtml = /&(?:amp|lt|gt|quot|#39);/g,
    reHasEscapedHtml = RegExp(reEscapedHtml.source);

/**
 * The inverse of `_.escape`; this method converts the HTML entities
 * `&amp;`, `&lt;`, `&gt;`, `&quot;`, and `&#39;` in `string` to
 * their corresponding characters.
 *
 * **Note:** No other HTML entities are unescaped. To unescape additional
 * HTML entities use a third-party library like [_he_](https://mths.be/he).
 *
 * @static
 * @memberOf _
 * @since 0.6.0
 * @category String
 * @param {string} [string=''] The string to unescape.
 * @returns {string} Returns the unescaped string.
 * @example
 *
 * _.unescape('fred, barney, &amp; pebbles');
 * // => 'fred, barney, & pebbles'
 */
function unescape(string) {
  string = toString(string);
  return (string && reHasEscapedHtml.test(string))
    ? string.replace(reEscapedHtml, unescapeHtmlChar)
    : string;
}

module.exports = unescape;


/***/ }),

/***/ 5578:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseIteratee = __webpack_require__(7206),
    baseUniq = __webpack_require__(5652);

/**
 * This method is like `_.uniq` except that it accepts `iteratee` which is
 * invoked for each element in `array` to generate the criterion by which
 * uniqueness is computed. The order of result values is determined by the
 * order they occur in the array. The iteratee is invoked with one argument:
 * (value).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Array
 * @param {Array} array The array to inspect.
 * @param {Function} [iteratee=_.identity] The iteratee invoked per element.
 * @returns {Array} Returns the new duplicate free array.
 * @example
 *
 * _.uniqBy([2.1, 1.2, 2.3], Math.floor);
 * // => [2.1, 1.2]
 *
 * // The `_.property` iteratee shorthand.
 * _.uniqBy([{ 'x': 1 }, { 'x': 2 }, { 'x': 1 }], 'x');
 * // => [{ 'x': 1 }, { 'x': 2 }]
 */
function uniqBy(array, iteratee) {
  return (array && array.length) ? baseUniq(array, baseIteratee(iteratee, 2)) : [];
}

module.exports = uniqBy;


/***/ }),

/***/ 3955:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var toString = __webpack_require__(9833);

/** Used to generate unique IDs. */
var idCounter = 0;

/**
 * Generates a unique ID. If `prefix` is given, the ID is appended to it.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Util
 * @param {string} [prefix=''] The value to prefix the ID with.
 * @returns {string} Returns the unique ID.
 * @example
 *
 * _.uniqueId('contact_');
 * // => 'contact_104'
 *
 * _.uniqueId();
 * // => '105'
 */
function uniqueId(prefix) {
  var id = ++idCounter;
  return toString(prefix) + id;
}

module.exports = uniqueId;


/***/ }),

/***/ 2569:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var baseDifference = __webpack_require__(731),
    baseRest = __webpack_require__(5976),
    isArrayLikeObject = __webpack_require__(9246);

/**
 * Creates an array excluding all given values using
 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * for equality comparisons.
 *
 * **Note:** Unlike `_.pull`, this method returns a new array.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Array
 * @param {Array} array The array to inspect.
 * @param {...*} [values] The values to exclude.
 * @returns {Array} Returns the new array of filtered values.
 * @see _.difference, _.xor
 * @example
 *
 * _.without([2, 1, 2, 3], 1, 2);
 * // => [3]
 */
var without = baseRest(function(array, values) {
  return isArrayLikeObject(array)
    ? baseDifference(array, values)
    : [];
});

module.exports = without;


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
/******/ 			id: moduleId,
/******/ 			loaded: false,
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
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
/******/ 	/* webpack/runtime/global */
/******/ 	!function() {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/node module decorator */
/******/ 	!function() {
/******/ 		__webpack_require__.nmd = function(module) {
/******/ 			module.paths = [];
/******/ 			if (!module.children) module.children = [];
/******/ 			return module;
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";

// NAMESPACE OBJECT: ./src/extensions/editor/js/inc/store/breakpoint/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  "getBreakpoint": function() { return getBreakpoint; }
});

// NAMESPACE OBJECT: ./src/extensions/editor/js/inc/store/breakpoint/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  "updateBreakpoint": function() { return updateBreakpoint; }
});

;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/store/breakpoint/reducer.js
function reducer() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
    breakpoint: ''
  };
  let action = arguments.length > 1 ? arguments[1] : undefined;
  if (action.type === 'UPDATE_BREAKPOINT') {
    return {
      ...state,
      breakpoint: action.breakpoint
    };
  }
  return state;
}
/* harmony default export */ var breakpoint_reducer = (reducer);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/store/breakpoint/selectors.js
function getBreakpoint(state) {
  return state.breakpoint;
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/store/breakpoint/actions.js
function updateBreakpoint(breakpoint) {
  return {
    type: 'UPDATE_BREAKPOINT',
    breakpoint
  };
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/store/breakpoint/index.js
/**
 * WordPress dependencies
 */
const {
  registerStore
} = wp.data;

/**
 * Internal dependencies
 */



registerStore('flextension/breakpoint', {
  reducer: breakpoint_reducer,
  selectors: selectors_namespaceObject,
  actions: actions_namespaceObject
});
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/store/index.js
/**
 * Internal dependencies
 */

// EXTERNAL MODULE: ./node_modules/lodash/uniqBy.js
var uniqBy = __webpack_require__(5578);
var uniqBy_default = /*#__PURE__*/__webpack_require__.n(uniqBy);
// EXTERNAL MODULE: ./node_modules/lodash/unescape.js
var lodash_unescape = __webpack_require__(7955);
var unescape_default = /*#__PURE__*/__webpack_require__.n(lodash_unescape);
// EXTERNAL MODULE: ./node_modules/lodash/map.js
var map = __webpack_require__(5161);
var map_default = /*#__PURE__*/__webpack_require__.n(map);
// EXTERNAL MODULE: ./node_modules/lodash/isEmpty.js
var isEmpty = __webpack_require__(1609);
var isEmpty_default = /*#__PURE__*/__webpack_require__.n(isEmpty);
// EXTERNAL MODULE: ./node_modules/lodash/invoke.js
var invoke = __webpack_require__(5907);
var invoke_default = /*#__PURE__*/__webpack_require__.n(invoke);
// EXTERNAL MODULE: ./node_modules/lodash/find.js
var find = __webpack_require__(3311);
var find_default = /*#__PURE__*/__webpack_require__.n(find);
// EXTERNAL MODULE: ./node_modules/lodash/debounce.js
var debounce = __webpack_require__(3279);
var debounce_default = /*#__PURE__*/__webpack_require__.n(debounce);
// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(7294);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/author-select-control/index.js








/**
 * WordPress dependencies
 */
const {
  Component
} = wp.element;
const {
  FormTokenField
} = wp.components;
const {
  withSelect
} = wp.data;
const apiFetch = wp.apiFetch;
const {
  addQueryArgs
} = wp.url;
const MIN_KEYWORD_LENGTH = 2;
const MAX_AUTHORS_SUGGESTIONS = 20;
const DEFAULT_QUERY = {
  per_page: MAX_AUTHORS_SUGGESTIONS,
  _fields: 'id,name'
};

// Lodash unescape function handles &#39; but not &#039; which may be return in some API requests.
const unescapeString = arg => {
  return unescape_default()(arg.replace('&#039;', "'"));
};

/**
 * Returns a author object with name unescaped.
 * The unescape of the name property is done using lodash unescape function.
 *
 * @param {Object} author The author object to unescape.
 * @return {Object} Author object with name property unescaped.
 */
const unescapeAuthor = author => {
  return {
    ...author,
    name: unescapeString(author.name)
  };
};

/**
 * Returns an array of author objects with names unescaped.
 * The unescape of each author is performed using the unescapeAuthor function.
 *
 * @param {Object[]} authors Array of author objects to unescape.
 * @return {Object[]} Array of author objects unescaped.
 */
const unescapeAuthors = authors => {
  return map_default()(authors, unescapeAuthor);
};
const nameToSlug = name => {
  return name.replace(/\s+/g, '-').toLowerCase();
};
const authorNamesToSlugs = names => {
  return map_default()(names, nameToSlug);
};
const isSameAuthorName = (nameA, nameB) => unescapeString(nameA).toLowerCase() === unescapeString(nameB).toLowerCase();
const authorNamesToIds = (names, authors) => {
  return names.map(authorName => find_default()(authors, author => isSameAuthorName(author.name, authorName))?.id);
};

/**
 * Author Select Control Component
 */
class AuthorSelectControl extends Component {
  constructor() {
    super(...arguments);
    this.onChange = this.onChange.bind(this);
    this.searchAuthors = debounce_default()(this.searchAuthors.bind(this), 500);
    this.state = {
      loading: !isEmpty_default()(this.props.authors),
      availableAuthors: [],
      selectedAuthors: []
    };
  }
  componentDidMount() {
    if (!isEmpty_default()(this.props.authors)) {
      const query = {
        per_page: -1
      };
      if (this.props.field === 'id') {
        query.include = this.props.authors.join(',');
      } else {
        query.slug = authorNamesToSlugs(this.props.authors).join(',');
      }
      this.initRequest = this.fetchAuthors(query);
      this.initRequest.then(() => {
        this.setState({
          loading: false
        });
      }, xhr => {
        if (xhr.statusText === 'abort') {
          return;
        }
        this.setState({
          loading: false
        });
      });
    }
  }
  componentWillUnmount() {
    invoke_default()(this.initRequest, ['abort']);
    invoke_default()(this.searchRequest, ['abort']);
  }
  componentDidUpdate(prevProps) {
    if (prevProps.authors !== this.props.authors) {
      this.updateSelectedAuthors(this.props.authors);
    }
  }
  fetchAuthors() {
    let params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    const query = {
      ...DEFAULT_QUERY,
      ...params
    };
    const request = apiFetch({
      path: addQueryArgs(`/wp/v2/users`, query)
    });
    request.then(unescapeAuthors).then(authors => {
      this.setState(state => ({
        availableAuthors: state.availableAuthors.concat(authors.filter(author => !find_default()(state.availableAuthors, availableAuthor => availableAuthor.id === author.id)))
      }));
      this.updateSelectedAuthors(this.props.authors);
    });
    return request;
  }
  updateSelectedAuthors() {
    let authors = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    const selectedAuthors = this.props.field === 'id' ? authors.reduce((accumulator, authorId) => {
      const authorObject = find_default()(this.state.availableAuthors, author => author.id === authorId);
      if (authorObject) {
        accumulator.push(authorObject.name);
      }
      return accumulator;
    }, []) : authors;
    this.setState({
      selectedAuthors
    });
  }
  searchAuthors() {
    let search = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    invoke_default()(this.searchRequest, ['abort']);
    if (search.length >= MIN_KEYWORD_LENGTH) {
      this.searchRequest = this.fetchAuthors({
        search
      });
    }
  }
  onChange(names) {
    const uniqueNames = uniqBy_default()(names, name => name.toLowerCase());
    this.setState({
      selectedAuthors: uniqueNames
    });
    const values = this.props.field === 'id' ? authorNamesToIds(uniqueNames, this.state.availableAuthors) : uniqueNames;
    const {
      onChange
    } = this.props;
    onChange(values);
  }
  render() {
    const {
      label,
      help
    } = this.props;
    const {
      loading,
      availableAuthors,
      selectedAuthors
    } = this.state;
    const authorNames = availableAuthors.map(author => author.name);
    return (0,react.createElement)(FormTokenField, {
      label: label,
      value: selectedAuthors,
      help: help,
      suggestions: authorNames,
      disabled: loading,
      maxSuggestions: MAX_AUTHORS_SUGGESTIONS,
      onInputChange: this.searchAuthors,
      onChange: values => {
        this.onChange(values);
      }
    });
  }
}
/* harmony default export */ var author_select_control = (withSelect((select, props) => {
  const {
    value,
    field = 'id'
  } = props;
  return {
    field,
    authors: value || []
  };
})(AuthorSelectControl));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/post-select-control/index.js








/**
 * WordPress dependencies
 */
const {
  Component: post_select_control_Component
} = wp.element;
const {
  FormTokenField: post_select_control_FormTokenField
} = wp.components;
const {
  withSelect: post_select_control_withSelect
} = wp.data;
const post_select_control_apiFetch = wp.apiFetch;
const {
  addQueryArgs: post_select_control_addQueryArgs
} = wp.url;
const post_select_control_MIN_KEYWORD_LENGTH = 3;
const MAX_TERMS_SUGGESTIONS = 20;
const post_select_control_DEFAULT_QUERY = {
  per_page: MAX_TERMS_SUGGESTIONS,
  _fields: 'id,title'
};

// Lodash unescape function handles &#39; but not &#039; which may be return in some API requests.
const post_select_control_unescapeString = text => {
  return unescape_default()(text.replace('&#039;', "'"));
};

/**
 * Returns a post object with title unescaped.
 * The unescape of the name property is done using lodash unescape function.
 *
 * @param {Object} post The post object to unescape.
 * @return {Object} Post object with name property unescaped.
 */
const unescapePost = post => {
  return {
    id: post.id,
    name: post_select_control_unescapeString(post.title.rendered)
  };
};

/**
 * Returns an array of post objects with names unescaped.
 * The unescape of each post is performed using the unescapePost function.
 *
 * @param {Object[]} posts Array of post objects to unescape.
 * @return {Object[]} Array of post objects unescaped.
 */
const unescapePosts = posts => {
  return map_default()(posts, unescapePost);
};
const isSamePostName = (nameA, nameB) => post_select_control_unescapeString(nameA).toLowerCase() === post_select_control_unescapeString(nameB).toLowerCase();
const postNamesToIds = (names, posts) => {
  return names.map(postName => find_default()(posts, post => isSamePostName(post.name, postName))?.id);
};

/**
 * Post Select Control Component.
 */
class PostSelectControl extends post_select_control_Component {
  constructor() {
    super(...arguments);
    this.onChange = this.onChange.bind(this);
    this.searchPosts = debounce_default()(this.searchPosts.bind(this), 500);
    this.state = {
      loading: false,
      availablePosts: [],
      selectedPosts: []
    };
  }
  loadPosts() {
    const {
      restBase,
      posts
    } = this.props;
    if (restBase && !isEmpty_default()(posts)) {
      this.initRequest = this.fetchPosts({
        include: posts.join(','),
        per_page: -1
      });
      this.initRequest.then(() => {
        this.setState({
          loading: false
        });
      }, xhr => {
        if (xhr.statusText === 'abort') {
          return;
        }
        this.setState({
          loading: false
        });
      });
    }
  }
  componentDidMount() {
    this.loadPosts();
  }
  componentWillUnmount() {
    invoke_default()(this.initRequest, ['abort']);
    invoke_default()(this.searchRequest, ['abort']);
  }
  componentDidUpdate(prevProps) {
    const {
      restBase,
      postType,
      posts
    } = this.props;
    if (prevProps.restBase !== restBase || prevProps.postType !== postType) {
      this.setState({
        availablePosts: [],
        selectedPosts: []
      });
      this.loadPosts();
    }
    if (prevProps.posts !== posts) {
      this.updateSelectedPosts();
    }
  }
  fetchPosts() {
    let params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    const {
      restBase = 'post'
    } = this.props;
    const query = {
      ...post_select_control_DEFAULT_QUERY,
      ...params
    };
    const request = post_select_control_apiFetch({
      path: post_select_control_addQueryArgs(`/wp/v2/${restBase}`, query)
    });
    request.then(unescapePosts).then(posts => {
      this.setState(state => ({
        availablePosts: state.availablePosts.concat(posts.filter(post => !find_default()(state.availablePosts, availablePost => availablePost.id === post.id)))
      }));
      this.updateSelectedPosts();
    });
    return request;
  }
  updateSelectedPosts() {
    const selectedPosts = this.props.posts.reduce((accumulator, postId) => {
      const postObject = find_default()(this.state.availablePosts, post => post.id === postId);
      if (postObject) {
        accumulator.push(postObject.name);
      }
      return accumulator;
    }, []);
    this.setState({
      selectedPosts
    });
  }
  searchPosts() {
    let search = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    invoke_default()(this.searchRequest, ['abort']);
    if (search.length >= post_select_control_MIN_KEYWORD_LENGTH && this.props.restBase) {
      this.searchRequest = this.fetchPosts({
        search
      });
    }
  }
  onChange(names) {
    const uniqueNames = uniqBy_default()(names, name => name.toLowerCase());
    this.setState({
      selectedPosts: uniqueNames
    });
    const values = postNamesToIds(uniqueNames, this.state.availablePosts);
    const {
      onChange
    } = this.props;
    onChange(values);
  }
  render() {
    const {
      label,
      help
    } = this.props;
    const {
      loading,
      availablePosts,
      selectedPosts
    } = this.state;
    const postNames = availablePosts.map(post => post.name);
    return (0,react.createElement)(post_select_control_FormTokenField, {
      label: label,
      value: selectedPosts,
      help: help,
      suggestions: postNames,
      disabled: loading,
      maxSuggestions: MAX_TERMS_SUGGESTIONS,
      onInputChange: this.searchPosts,
      onChange: values => {
        this.onChange(values);
      }
    });
  }
}
/* harmony default export */ var post_select_control = (post_select_control_withSelect((select, props) => {
  const {
    postType = 'post',
    value = []
  } = props;
  const {
    getPostType
  } = select('core');
  const postTypeObject = getPostType(postType);
  return {
    postType,
    restBase: postTypeObject ? postTypeObject.rest_base : '',
    posts: value
  };
})(PostSelectControl));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/post-type-select-control/index.js


/**
 * WordPress dependencies
 */

const {
  Component: post_type_select_control_Component
} = wp.element;
const {
  SelectControl
} = wp.components;
const {
  withSelect: post_type_select_control_withSelect
} = wp.data;

/**
 * Component
 */
class PostTypeSelectControl extends post_type_select_control_Component {
  render() {
    const {
      value,
      label,
      help,
      onChange,
      postTypes
    } = this.props;
    return (0,react.createElement)(SelectControl, {
      label: label,
      help: help,
      value: value,
      options: postTypes,
      onChange: newValue => {
        onChange(newValue);
      }
    });
  }
}
/* harmony default export */ var post_type_select_control = (post_type_select_control_withSelect((select, props) => {
  const {
    getPostTypes
  } = select('core');
  const {
    postTypes
  } = props;
  let types = [];
  const allPostTypes = getPostTypes();
  if (postTypes && postTypes.length > 0) {
    const keys = allPostTypes?.map(postType => postType.slug);
    types = keys ? postTypes.filter(postType => {
      return keys.includes(postType.value);
    }) : postTypes;
  } else {
    types = allPostTypes ? types.filter(postType => {
      return postType.viewable;
    }).map(postType => {
      return {
        label: postType.labels.singular_name,
        value: postType.slug
      };
    }) : [];
  }
  return {
    postTypes: types || []
  };
})(PostTypeSelectControl));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/taxonomy-select-control/index.js


/**
 * WordPress dependencies
 */

const {
  __
} = wp.i18n;
const {
  Component: taxonomy_select_control_Component
} = wp.element;
const {
  SelectControl: taxonomy_select_control_SelectControl
} = wp.components;
const {
  withSelect: taxonomy_select_control_withSelect
} = wp.data;

/**
 * Component
 */
class TaxonomySelectControl extends taxonomy_select_control_Component {
  render() {
    const {
      value,
      label,
      help,
      onChange,
      taxonomies
    } = this.props;
    const taxonomyOptions = [];
    if (taxonomies && taxonomies.length > 0) {
      taxonomyOptions.push({
        value: '',
        label: __('All', 'flextension')
      });
      taxonomies.forEach(taxonomy => {
        taxonomyOptions.push(taxonomy);
      });
    } else {
      return '';
    }
    return (0,react.createElement)(taxonomy_select_control_SelectControl, {
      label: label,
      help: help,
      value: value,
      options: taxonomyOptions,
      onChange: taxonomy => {
        onChange(taxonomy);
      }
    });
  }
}
/* harmony default export */ var taxonomy_select_control = (taxonomy_select_control_withSelect((select, props) => {
  const {
    getTaxonomies
  } = select('core');
  const {
    postType = 'post'
  } = props;
  const taxonomies = getTaxonomies();
  return {
    taxonomies: taxonomies ? taxonomies.filter(taxonomy => {
      return taxonomy.types.includes(postType);
    }).map(taxonomy => {
      return {
        label: taxonomy.name,
        value: taxonomy.slug
      };
    }) : []
  };
})(TaxonomySelectControl));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/term-select-control/index.js








/**
 * WordPress dependencies
 */
const {
  Component: term_select_control_Component
} = wp.element;
const {
  FormTokenField: term_select_control_FormTokenField
} = wp.components;
const {
  withSelect: term_select_control_withSelect
} = wp.data;
const term_select_control_apiFetch = wp.apiFetch;
const {
  addQueryArgs: term_select_control_addQueryArgs
} = wp.url;
const term_select_control_MIN_KEYWORD_LENGTH = 3;
const term_select_control_MAX_TERMS_SUGGESTIONS = 20;
const term_select_control_DEFAULT_QUERY = {
  per_page: term_select_control_MAX_TERMS_SUGGESTIONS,
  orderby: 'name',
  order: 'desc',
  _fields: 'id,name,slug'
};

// Lodash unescape function handles &#39; but not &#039; which may be return in some API requests.
const term_select_control_unescapeString = arg => {
  return unescape_default()(arg.replace('&#039;', "'"));
};

/**
 * Returns a term object with name unescaped.
 * The unescape of the name property is done using lodash unescape function.
 *
 * @param {Object} term The term object to unescape.
 * @return {Object} Term object with name property unescaped.
 */
const unescapeTerm = term => {
  return {
    ...term,
    name: term_select_control_unescapeString(term.name)
  };
};

/**
 * Returns an array of term objects with names unescaped.
 * The unescape of each term is performed using the unescapeTerm function.
 *
 * @param {Object[]} terms Array of term objects to unescape.
 * @return {Object[]} Array of term objects unescaped.
 */
const unescapeTerms = terms => {
  return map_default()(terms, unescapeTerm);
};
const isSameTermName = (nameA, nameB) => term_select_control_unescapeString(nameA).toLowerCase() === term_select_control_unescapeString(nameB).toLowerCase();
const termNamesToIds = (names, terms) => {
  return names.map(termName => find_default()(terms, term => isSameTermName(term.name, termName))?.id);
};
const termNamesToSlugs = (names, terms) => {
  return names.map(termName => find_default()(terms, term => isSameTermName(term.name, termName))?.slug);
};

/**
 * Term Select Control Component
 */
class TermSelectControl extends term_select_control_Component {
  constructor() {
    super(...arguments);
    this.onChange = this.onChange.bind(this);
    this.searchTerms = debounce_default()(this.searchTerms.bind(this), 500);
    this.state = {
      loading: !isEmpty_default()(this.props.terms),
      availableTerms: [],
      selectedTerms: []
    };
  }
  componentDidMount() {
    if (!isEmpty_default()(this.props.terms)) {
      const query = {
        per_page: -1
      };
      if (this.props.field === 'id') {
        query.include = this.props.terms.join(',');
      } else {
        query.slug = termNamesToSlugs(this.props.terms, this.state.availableTerms).join(',');
      }
      this.initRequest = this.fetchTerms(query);
      this.initRequest.then(() => {
        this.setState({
          loading: false
        });
      }, xhr => {
        if (xhr.statusText === 'abort') {
          return;
        }
        this.setState({
          loading: false
        });
      });
    }
  }
  componentWillUnmount() {
    invoke_default()(this.initRequest, ['abort']);
    invoke_default()(this.searchRequest, ['abort']);
  }
  componentDidUpdate(prevProps) {
    if (prevProps.terms !== this.props.terms) {
      this.updateSelectedTerms(this.props.terms);
    }
  }
  fetchTerms() {
    let params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    const {
      taxonomy
    } = this.props;
    const query = {
      ...term_select_control_DEFAULT_QUERY,
      ...params
    };
    const request = term_select_control_apiFetch({
      path: term_select_control_addQueryArgs(`/wp/v2/${taxonomy.rest_base}`, query)
    });
    request.then(unescapeTerms).then(terms => {
      this.setState(state => ({
        availableTerms: state.availableTerms.concat(terms.filter(term => !find_default()(state.availableTerms, availableTerm => availableTerm.id === term.id)))
      }));
      this.updateSelectedTerms(this.props.terms);
    });
    return request;
  }
  updateSelectedTerms() {
    let terms = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    const selectedTerms = this.props.field === 'id' ? terms.reduce((accumulator, termId) => {
      const termObject = find_default()(this.state.availableTerms, term => term.id === termId);
      if (termObject) {
        accumulator.push(termObject.name);
      }
      return accumulator;
    }, []) : terms;
    this.setState({
      selectedTerms
    });
  }
  searchTerms() {
    let search = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    invoke_default()(this.searchRequest, ['abort']);
    if (search.length >= term_select_control_MIN_KEYWORD_LENGTH) {
      this.searchRequest = this.fetchTerms({
        search
      });
    }
  }
  onChange(names) {
    const uniqueNames = uniqBy_default()(names, name => name.toLowerCase());
    this.setState({
      selectedTerms: uniqueNames
    });
    const values = this.props.field === 'id' ? termNamesToIds(uniqueNames, this.state.availableTerms) : uniqueNames;
    const {
      onChange
    } = this.props;
    onChange(values);
  }
  render() {
    const {
      label,
      help
    } = this.props;
    const {
      loading,
      availableTerms,
      selectedTerms
    } = this.state;
    const termNames = availableTerms.map(term => term.name);
    return (0,react.createElement)(term_select_control_FormTokenField, {
      label: label,
      value: selectedTerms,
      help: help,
      suggestions: termNames,
      disabled: loading,
      maxSuggestions: term_select_control_MAX_TERMS_SUGGESTIONS,
      onInputChange: this.searchTerms,
      onChange: values => {
        this.onChange(values);
      }
    });
  }
}
/* harmony default export */ var term_select_control = (term_select_control_withSelect((select, props) => {
  const {
    taxonomy = 'category',
    value,
    field = 'name'
  } = props;
  const {
    getTaxonomy
  } = select('core');
  const taxonomyObject = getTaxonomy(taxonomy);
  return {
    field,
    taxonomy: taxonomyObject,
    terms: taxonomyObject ? value : []
  };
})(TermSelectControl));
// EXTERNAL MODULE: ./node_modules/lodash/without.js
var without = __webpack_require__(2569);
var without_default = /*#__PURE__*/__webpack_require__.n(without);
// EXTERNAL MODULE: ./node_modules/lodash/groupBy.js
var groupBy = __webpack_require__(7739);
var groupBy_default = /*#__PURE__*/__webpack_require__.n(groupBy);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/hierarchical-term-selector/utils.js


/**
 * External dependencies
 */


/**
 * Returns terms in a tree form.
 *
 * @param {Array} flatTerms Array of terms in flat format.
 * @return {Array} Array of terms in tree format.
 */
function buildTermsTree(flatTerms) {
  const termsByParent = groupBy_default()(flatTerms, 'parent');
  const fillWithChildren = terms => {
    return terms.map(term => {
      const children = termsByParent[term.id];
      return {
        ...term,
        children: children && children.length ? fillWithChildren(children) : []
      };
    });
  };
  return fillWithChildren(termsByParent['0'] || []);
}
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(4184);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/hierarchical-term-selector/index.js


/**
 * Internal dependencies
 */






/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */
const {
  Component: hierarchical_term_selector_Component
} = wp.element;
const {
  BaseControl,
  CheckboxControl
} = wp.components;
const {
  withSelect: hierarchical_term_selector_withSelect
} = wp.data;
const {
  withInstanceId,
  compose
} = wp.compose;
const hierarchical_term_selector_apiFetch = wp.apiFetch;
const {
  addQueryArgs: hierarchical_term_selector_addQueryArgs
} = wp.url;

/**
 * Module Constants
 */
const hierarchical_term_selector_DEFAULT_QUERY = {
  per_page: -1,
  orderby: 'name',
  order: 'asc',
  _fields: 'id,name,parent',
  exclude: 1 // Exclude an Uncategorized category
};

class HierarchicalTermSelector extends hierarchical_term_selector_Component {
  constructor() {
    super(...arguments);
    this.findTerm = this.findTerm.bind(this);
    this.onSelectTerm = this.onSelectTerm.bind(this);
    this.setFilterValue = this.setFilterValue.bind(this);
    this.sortBySelected = this.sortBySelected.bind(this);
    this.state = {
      loading: true,
      availableTermsTree: [],
      availableTerms: [],
      filterValue: '',
      filteredTermsTree: []
    };
  }
  onSelectTerm(value) {
    const {
      terms = [],
      onChange = () => {}
    } = this.props;
    const termId = parseInt(value, 10);
    const hasTerm = terms.indexOf(termId) !== -1;
    const newTerms = hasTerm ? without_default()(terms, termId) : [...terms, termId];
    onChange(newTerms);
  }
  findTerm(terms, parent, name) {
    return find_default()(terms, term => {
      return (!term.parent && !parent || parseInt(term.parent) === parseInt(parent)) && term.name.toLowerCase() === name.toLowerCase();
    });
  }
  componentDidMount() {
    this.fetchTerms();
  }
  componentWillUnmount() {
    invoke_default()(this.fetchRequest, ['abort']);
    invoke_default()(this.addRequest, ['abort']);
  }
  componentDidUpdate(prevProps) {
    if (this.props.taxonomy !== prevProps.taxonomy) {
      this.fetchTerms();
    }
  }
  fetchTerms() {
    const {
      taxonomy
    } = this.props;
    if (!taxonomy) {
      return;
    }
    this.fetchRequest = hierarchical_term_selector_apiFetch({
      path: hierarchical_term_selector_addQueryArgs(`/wp/v2/${taxonomy.rest_base}`, hierarchical_term_selector_DEFAULT_QUERY)
    });
    this.fetchRequest.then(terms => {
      // resolve
      const availableTermsTree = this.sortBySelected(buildTermsTree(terms));
      this.fetchRequest = null;
      this.setState({
        loading: false,
        availableTermsTree,
        availableTerms: terms
      });
    }, xhr => {
      // reject
      if (xhr.statusText === 'abort') {
        return;
      }
      this.fetchRequest = null;
      this.setState({
        loading: false
      });
    });
  }
  sortBySelected(termsTree) {
    const {
      terms = []
    } = this.props;
    const treeHasSelection = termTree => {
      if (terms.indexOf(termTree.id) !== -1) {
        return true;
      }
      if (undefined === termTree.children) {
        return false;
      }
      const anyChildIsSelected = termTree.children.map(treeHasSelection).filter(child => child).length > 0;
      if (anyChildIsSelected) {
        return true;
      }
      return false;
    };
    const termOrChildIsSelected = (termA, termB) => {
      const termASelected = treeHasSelection(termA);
      const termBSelected = treeHasSelection(termB);
      if (termASelected === termBSelected) {
        return 0;
      }
      if (termASelected && !termBSelected) {
        return -1;
      }
      if (!termASelected && termBSelected) {
        return 1;
      }
      return 0;
    };
    termsTree.sort(termOrChildIsSelected);
    return termsTree;
  }
  setFilterValue(event) {
    const {
      availableTermsTree
    } = this.state;
    const filterValue = event.target.value;
    const filteredTermsTree = availableTermsTree.map(this.getFilterMatcher(filterValue)).filter(term => term);
    this.setState({
      filterValue,
      filteredTermsTree
    });
  }
  getFilterMatcher(filterValue) {
    const matchTermsForFilter = originalTerm => {
      if ('' === filterValue) {
        return originalTerm;
      }

      // Shallow clone, because we'll be filtering the term's children and
      // don't want to modify the original term.
      const term = {
        ...originalTerm
      };

      // Map and filter the children, recursive so we deal with grandchildren
      // and any deeper levels.
      if (term.children.length > 0) {
        term.children = term.children.map(matchTermsForFilter).filter(child => child);
      }

      // If the term's name contains the filterValue, or it has children
      // (i.e. some child matched at some point in the tree) then return it.
      if (-1 !== term.name.toLowerCase().indexOf(filterValue.toLowerCase()) || term.children.length > 0) {
        return term;
      }

      // Otherwise, return false. After mapping, the list of terms will need
      // to have false values filtered out.
      return false;
    };
    return matchTermsForFilter;
  }
  renderTerms(renderedTerms) {
    const {
      terms = []
    } = this.props;
    return renderedTerms.map(term => {
      const id = `flext-editor-hierarchical-terms-${term.id}`;
      return (0,react.createElement)("div", {
        key: id,
        className: "editor-post-taxonomies__hierarchical-terms-choice"
      }, (0,react.createElement)(CheckboxControl, {
        label: term.name,
        checked: terms && terms.indexOf(term.id) !== -1,
        onChange: () => {
          this.onSelectTerm(term.id);
        }
      }), !!term.children.length && (0,react.createElement)("div", {
        className: "editor-post-taxonomies__hierarchical-terms-subchoices"
      }, this.renderTerms(term.children)));
    });
  }
  render() {
    const {
      taxonomy,
      label,
      help,
      instanceId,
      minFilter = 8,
      className
    } = this.props;
    const {
      availableTermsTree,
      availableTerms,
      filteredTermsTree,
      filterValue
    } = this.state;
    const showFilter = availableTerms.length >= minFilter;
    const id = `flext-component-hierarchical-terms-filter-${instanceId}`;
    return (0,react.createElement)(BaseControl, {
      label: label,
      id: `flext-component-hierarchical-term-selector-${instanceId}`,
      help: help,
      className: classnames_default()('flext-component-hierarchical-term-selector', className)
    }, (0,react.createElement)("label", {
      htmlFor: id,
      className: "components-base-control__label"
    }, taxonomy.name), showFilter && (0,react.createElement)("input", {
      type: "search",
      id: id,
      value: filterValue,
      onChange: this.setFilterValue,
      className: "editor-post-taxonomies__hierarchical-terms-filter",
      key: "term-filter-input"
    }), (0,react.createElement)("div", {
      className: "editor-post-taxonomies__hierarchical-terms-list",
      key: "term-list",
      tabIndex: "0",
      role: "group"
    }, this.renderTerms('' !== filterValue ? filteredTermsTree : availableTermsTree)));
  }
}
/* harmony default export */ var hierarchical_term_selector = (compose([hierarchical_term_selector_withSelect((select, props) => {
  const {
    taxonomy = 'category',
    terms = []
  } = props;
  const {
    getTaxonomy
  } = select('core');
  const taxonomyObject = getTaxonomy(taxonomy);
  return {
    taxonomy: taxonomyObject,
    terms
  };
}), withInstanceId])(HierarchicalTermSelector));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/post-order-control/index.js

/**
 * WordPress dependencies
 */
const {
  __: post_order_control_
} = wp.i18n;
const {
  Component: post_order_control_Component
} = wp.element;
const {
  SelectControl: post_order_control_SelectControl
} = wp.components;

/**
 * Post Order Control Component.
 */
class PostOrderControl extends post_order_control_Component {
  render() {
    const {
      order,
      orderBy,
      onOrderByChange,
      onOrderChange
    } = this.props;
    return (0,react.createElement)(react.Fragment, null, onOrderByChange && (0,react.createElement)(post_order_control_SelectControl, {
      key: "flext-orderby-control",
      label: post_order_control_('Order by', 'flextension'),
      value: orderBy,
      options: [{
        label: post_order_control_('None', 'flextension'),
        value: ''
      }, {
        label: post_order_control_('Published date', 'flextension'),
        value: 'date'
      }, {
        label: post_order_control_('Modified date', 'flextension'),
        value: 'modified'
      }, {
        label: post_order_control_('Title', 'flextension'),
        value: 'title'
      }, {
        label: post_order_control_('Total views', 'flextension'),
        value: 'views'
      }, {
        label: post_order_control_('Total likes', 'flextension'),
        value: 'likes'
      }, {
        label: post_order_control_('Total comments', 'flextension'),
        value: 'comment_count'
      }, {
        label: post_order_control_('Menu order', 'flextension'),
        value: 'menu_order'
      }],
      onChange: onOrderByChange
    }), onOrderChange && orderBy && (0,react.createElement)(post_order_control_SelectControl, {
      key: "flext-order-control",
      label: post_order_control_('Order', 'flextension'),
      value: order,
      options: [{
        label: post_order_control_('Descending', 'flextension'),
        value: 'desc'
      }, {
        label: post_order_control_('Ascending', 'flextension'),
        value: 'asc'
      }],
      onChange: onOrderChange
    }));
  }
}
/* harmony default export */ var post_order_control = (PostOrderControl);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/query-controls/index.js


/**
 * Internal dependencies
 */








/**
 * WordPress dependencies
 */
const {
  __: query_controls_
} = wp.i18n;
const {
  Component: query_controls_Component
} = wp.element;
const {
  RangeControl,
  SelectControl: query_controls_SelectControl
} = wp.components;

/**
 * Component
 */
class QueryControls extends query_controls_Component {
  constructor() {
    super(...arguments);
    this.updateValue = this.updateValue.bind(this);
    this.state = {
      filterBy: this.props.value.posts?.length > 0 ? 'post' : ''
    };
  }
  updateValue(newValue) {
    const {
      value,
      onChange
    } = this.props;
    const data = {
      ...value,
      ...newValue
    };
    onChange(data);
  }
  render() {
    const {
      value: {
        postType = 'post',
        posts = [],
        taxonomy = '',
        terms = [],
        author = '',
        authors = [],
        timeRange = '',
        orderBy = '',
        order = 'DESC',
        numberOfItems
      },
      postTypes = [],
      showPostType = true,
      showFilters = true,
      showAuthor = true,
      showOrderBy = true,
      showNumberOfItems = true,
      maxItems = 50,
      minItems = 1,
      maxSuggestions = 20
    } = this.props;
    const {
      filterBy
    } = this.state;
    return [showPostType && (0,react.createElement)(post_type_select_control, {
      id: "flext-query-control-post-type",
      label: query_controls_('Post type', 'flextension'),
      value: postType,
      postTypes: postTypes,
      onChange: value => {
        this.updateValue({
          postType: value,
          taxonomy: '',
          terms: [],
          posts: []
        });
      }
    }), showFilters && (0,react.createElement)(query_controls_SelectControl, {
      id: "flext-query-control-filter",
      label: query_controls_('Filter by', 'flextension'),
      options: [{
        value: '',
        label: query_controls_('Taxonomy', 'flextension')
      }, {
        value: 'post',
        label: query_controls_('Specific posts', 'flextension')
      }],
      value: filterBy,
      onChange: value => {
        this.updateValue({
          taxonomy: '',
          posts: [],
          authors: [],
          terms: []
        });
        this.setState({
          filterBy: value
        });
      }
    }), showFilters && filterBy === 'post' && postType && (0,react.createElement)(post_select_control, {
      id: "flext-query-control-posts",
      label: query_controls_('Titles', 'flextension'),
      value: posts,
      postType: postType,
      onChange: value => {
        this.updateValue({
          posts: value
        });
      }
    }), showFilters && filterBy !== 'post' && postType && (0,react.createElement)(taxonomy_select_control, {
      id: "flext-query-control-taxonomy",
      label: query_controls_('Taxonomy', 'flextension'),
      value: taxonomy,
      postType: postType,
      onChange: value => {
        this.updateValue({
          taxonomy: value,
          terms: []
        });
      }
    }), showFilters && filterBy !== 'post' && postType && taxonomy && (0,react.createElement)(term_select_control, {
      id: "flext-query-control-terms",
      label: query_controls_('Terms', 'flextension'),
      value: terms,
      taxonomy: taxonomy,
      maxSuggestions: maxSuggestions,
      onChange: value => {
        this.updateValue({
          terms: value
        });
      }
    }), showAuthor && filterBy !== 'post' && (0,react.createElement)(query_controls_SelectControl, {
      id: "flext-query-control-author-filter",
      label: query_controls_('Authors', 'flextension'),
      options: [{
        value: '',
        label: query_controls_('All', 'flextension')
      }, {
        value: 'following',
        label: query_controls_('Authors you follow', 'flextension')
      }, {
        value: 'authors',
        label: query_controls_('Specific authors', 'flextension')
      }],
      value: author,
      onChange: value => this.updateValue({
        authors: [],
        author: value
      })
    }), showAuthor && filterBy !== 'post' && author === 'authors' && (0,react.createElement)(author_select_control, {
      id: "flext-query-control-authors",
      label: query_controls_('Author names', 'flextension'),
      value: authors,
      maxSuggestions: maxSuggestions,
      onChange: value => {
        this.updateValue({
          authors: value
        });
      }
    }), showFilters && filterBy !== 'post' && (0,react.createElement)(query_controls_SelectControl, {
      id: "flext-query-control-time-range",
      label: query_controls_('Time range', 'flextension'),
      options: [{
        value: '',
        label: query_controls_('All', 'flextension')
      }, {
        value: 'daily',
        label: query_controls_('24 hours', 'flextension')
      }, {
        value: '2days',
        label: query_controls_('48 hours', 'flextension')
      }, {
        value: '3days',
        label: query_controls_('72 hours', 'flextension')
      }, {
        value: 'weekly',
        label: query_controls_('7 days ago', 'flextension')
      }, {
        value: 'monthly',
        label: query_controls_('1 month ago', 'flextension')
      }],
      value: timeRange,
      onChange: value => this.updateValue({
        timeRange: value
      })
    }), showOrderBy && (0,react.createElement)(post_order_control, {
      id: "flext-order-control",
      order: order,
      orderBy: orderBy,
      onOrderChange: value => {
        this.updateValue({
          order: value
        });
      },
      onOrderByChange: value => {
        this.updateValue({
          orderBy: value
        });
      }
    }), showNumberOfItems && (0,react.createElement)(RangeControl, {
      id: "flext-query-control-number-of-items",
      label: query_controls_('Number of items', 'flextension'),
      value: numberOfItems,
      onChange: value => {
        this.updateValue({
          numberOfItems: value
        });
      },
      min: minItems,
      max: maxItems,
      required: true
    })];
  }
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/heading-level-dropdown/heading-level-icon.js

/**
 * WordPress dependencies
 */
const {
  Path,
  SVG
} = wp.components;

/** @typedef {wp.element.WPComponent} WPComponent */

/**
 * HeadingLevelIcon props.
 *
 * @typedef WPHeadingLevelIconProps
 * @property {number}   level     The heading level to show an icon for.
 * @property {?boolean} isPressed Whether or not the icon should appear pressed; default: false.
 */

/**
 * Heading level icon.
 *
 * @param {WPHeadingLevelIconProps} props Component props.
 * @return {?WPComponent} The icon.
 */
function HeadingLevelIcon(_ref) {
  let {
    level,
    isPressed = false
  } = _ref;
  const levelToPath = {
    1: 'M9 5h2v10H9v-4H5v4H3V5h2v4h4V5zm6.6 0c-.6.9-1.5 1.7-2.6 2v1h2v7h2V5h-1.4z',
    2: 'M7 5h2v10H7v-4H3v4H1V5h2v4h4V5zm8 8c.5-.4.6-.6 1.1-1.1.4-.4.8-.8 1.2-1.3.3-.4.6-.8.9-1.3.2-.4.3-.8.3-1.3 0-.4-.1-.9-.3-1.3-.2-.4-.4-.7-.8-1-.3-.3-.7-.5-1.2-.6-.5-.2-1-.2-1.5-.2-.4 0-.7 0-1.1.1-.3.1-.7.2-1 .3-.3.1-.6.3-.9.5-.3.2-.6.4-.8.7l1.2 1.2c.3-.3.6-.5 1-.7.4-.2.7-.3 1.2-.3s.9.1 1.3.4c.3.3.5.7.5 1.1 0 .4-.1.8-.4 1.1-.3.5-.6.9-1 1.2-.4.4-1 .9-1.6 1.4-.6.5-1.4 1.1-2.2 1.6V15h8v-2H15z',
    3: 'M12.1 12.2c.4.3.8.5 1.2.7.4.2.9.3 1.4.3.5 0 1-.1 1.4-.3.3-.1.5-.5.5-.8 0-.2 0-.4-.1-.6-.1-.2-.3-.3-.5-.4-.3-.1-.7-.2-1-.3-.5-.1-1-.1-1.5-.1V9.1c.7.1 1.5-.1 2.2-.4.4-.2.6-.5.6-.9 0-.3-.1-.6-.4-.8-.3-.2-.7-.3-1.1-.3-.4 0-.8.1-1.1.3-.4.2-.7.4-1.1.6l-1.2-1.4c.5-.4 1.1-.7 1.6-.9.5-.2 1.2-.3 1.8-.3.5 0 1 .1 1.6.2.4.1.8.3 1.2.5.3.2.6.5.8.8.2.3.3.7.3 1.1 0 .5-.2.9-.5 1.3-.4.4-.9.7-1.5.9v.1c.6.1 1.2.4 1.6.8.4.4.7.9.7 1.5 0 .4-.1.8-.3 1.2-.2.4-.5.7-.9.9-.4.3-.9.4-1.3.5-.5.1-1 .2-1.6.2-.8 0-1.6-.1-2.3-.4-.6-.2-1.1-.6-1.6-1l1.1-1.4zM7 9H3V5H1v10h2v-4h4v4h2V5H7v4z',
    4: 'M9 15H7v-4H3v4H1V5h2v4h4V5h2v10zm10-2h-1v2h-2v-2h-5v-2l4-6h3v6h1v2zm-3-2V7l-2.8 4H16z',
    5: 'M12.1 12.2c.4.3.7.5 1.1.7.4.2.9.3 1.3.3.5 0 1-.1 1.4-.4.4-.3.6-.7.6-1.1 0-.4-.2-.9-.6-1.1-.4-.3-.9-.4-1.4-.4H14c-.1 0-.3 0-.4.1l-.4.1-.5.2-1-.6.3-5h6.4v1.9h-4.3L14 8.8c.2-.1.5-.1.7-.2.2 0 .5-.1.7-.1.5 0 .9.1 1.4.2.4.1.8.3 1.1.6.3.2.6.6.8.9.2.4.3.9.3 1.4 0 .5-.1 1-.3 1.4-.2.4-.5.8-.9 1.1-.4.3-.8.5-1.3.7-.5.2-1 .3-1.5.3-.8 0-1.6-.1-2.3-.4-.6-.2-1.1-.6-1.6-1-.1-.1 1-1.5 1-1.5zM9 15H7v-4H3v4H1V5h2v4h4V5h2v10z',
    6: 'M9 15H7v-4H3v4H1V5h2v4h4V5h2v10zm8.6-7.5c-.2-.2-.5-.4-.8-.5-.6-.2-1.3-.2-1.9 0-.3.1-.6.3-.8.5l-.6.9c-.2.5-.2.9-.2 1.4.4-.3.8-.6 1.2-.8.4-.2.8-.3 1.3-.3.4 0 .8 0 1.2.2.4.1.7.3 1 .6.3.3.5.6.7.9.2.4.3.8.3 1.3s-.1.9-.3 1.4c-.2.4-.5.7-.8 1-.4.3-.8.5-1.2.6-1 .3-2 .3-3 0-.5-.2-1-.5-1.4-.9-.4-.4-.8-.9-1-1.5-.2-.6-.3-1.3-.3-2.1s.1-1.6.4-2.3c.2-.6.6-1.2 1-1.6.4-.4.9-.7 1.4-.9.6-.3 1.1-.4 1.7-.4.7 0 1.4.1 2 .3.5.2 1 .5 1.4.8 0 .1-1.3 1.4-1.3 1.4zm-2.4 5.8c.2 0 .4 0 .6-.1.2 0 .4-.1.5-.2.1-.1.3-.3.4-.5.1-.2.1-.5.1-.7 0-.4-.1-.8-.4-1.1-.3-.2-.7-.3-1.1-.3-.3 0-.7.1-1 .2-.4.2-.7.4-1 .7 0 .3.1.7.3 1 .1.2.3.4.4.6.2.1.3.3.5.3.2.1.5.2.7.1z'
  };
  if (!levelToPath.hasOwnProperty(level)) {
    return null;
  }
  return (0,react.createElement)(SVG, {
    width: "24",
    height: "24",
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg",
    isPressed: isPressed
  }, (0,react.createElement)(Path, {
    d: levelToPath[level]
  }));
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/heading-level-dropdown/index.js

/**
 * WordPress dependencies
 */
const {
  ToolbarDropdownMenu
} = wp.components;
const {
  __: heading_level_dropdown_,
  sprintf
} = wp.i18n;

/**
 * Internal dependencies
 */

const HEADING_LEVELS = [1, 2, 3, 4, 5, 6];
const POPOVER_PROPS = {
  className: 'block-library-heading-level-dropdown'
};

/** @typedef {wp.element.WPComponent} WPComponent */

/**
 * HeadingLevelDropdown props.
 *
 * @typedef WPHeadingLevelDropdownProps
 *
 * @property {number}   selectedLevel The chosen heading level.
 * @property {Function} onChange      Callback to run when
 *                                    toolbar value is changed.
 */

/**
 * Dropdown for selecting a heading level (1 through 6).
 *
 * @param {WPHeadingLevelDropdownProps} props Component props.
 *
 * @return {WPComponent} The toolbar.
 */
function HeadingLevelDropdown(_ref) {
  let {
    selectedLevel,
    onChange
  } = _ref;
  return (0,react.createElement)(ToolbarDropdownMenu, {
    popoverProps: POPOVER_PROPS,
    icon: (0,react.createElement)(HeadingLevelIcon, {
      level: selectedLevel
    }),
    label: heading_level_dropdown_('Change heading level', 'flextension'),
    controls: HEADING_LEVELS.map(targetLevel => {
      {
        const isActive = targetLevel === selectedLevel;
        return {
          icon: (0,react.createElement)(HeadingLevelIcon, {
            level: targetLevel,
            isPressed: isActive
          }),
          label: sprintf(
          // translators: %s: heading level e.g: "1", "2", "3"
          heading_level_dropdown_('Heading %d', 'flextension'), targetLevel),
          isActive,
          onClick() {
            onChange(targetLevel);
          }
        };
      }
    })
  });
}
// EXTERNAL MODULE: ./node_modules/lodash/uniqueId.js
var uniqueId = __webpack_require__(3955);
var uniqueId_default = /*#__PURE__*/__webpack_require__.n(uniqueId);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/repeater-control/index.js

/**
 * External dependencies
 */



/**
 * WordPress dependencies
 */
const {
  __: repeater_control_
} = wp.i18n;
const {
  BaseControl: repeater_control_BaseControl,
  Button,
  ButtonGroup
} = wp.components;
const {
  Component: repeater_control_Component
} = wp.element;
/**
 * Repeater Control.
 */
class RepeaterControl extends repeater_control_Component {
  constructor() {
    super(...arguments);
    this.onChange = this.onChange.bind(this);
    this.onAddNew = this.onAddNew.bind(this);
    this.onMove = this.onMove.bind(this);
    this.onRemove = this.onRemove.bind(this);
    this.state = {
      items: this.props.items || []
    };
  }
  onChange(items) {
    const {
      onChange
    } = this.props;
    this.setState({
      items
    });
    onChange(items);
  }
  onAddNew() {
    const {
      onAddNew
    } = this.props;
    const items = [...this.state.items];
    items.push(onAddNew());
    this.onChange(items);
  }
  onMove(from, to) {
    const items = [...this.state.items];
    items.splice(to, 0, items.splice(from, 1)[0]);
    this.onChange(items);
  }
  onRemove(index) {
    const items = [...this.state.items];
    items.splice(index, 1);
    this.onChange(items);
  }
  render() {
    const {
      onAddNew,
      id,
      children,
      label,
      help,
      className
    } = this.props;
    const controlId = id ? id : uniqueId_default()('flext-component-repeater-control-');
    const {
      items
    } = this.state;
    return (0,react.createElement)(repeater_control_BaseControl, {
      className: classnames_default()('flext-component-repeater-control', className),
      label: label,
      help: help,
      id: controlId
    }, (0,react.createElement)("ul", {
      className: "flext-component-repeater-control-fields"
    }, items && items.map((item, index) => {
      const isFirstItem = index === 0;
      const isLastItem = index + 1 === items.length;
      const onChange = value => {
        items[index] = value;
        this.onChange(items);
      };
      return (0,react.createElement)("li", {
        key: `flext-component-repeater-item-${index}`
      }, children({
        item,
        index,
        onChange
      }), (0,react.createElement)(ButtonGroup, {
        className: "flext-item-buttons"
      }, (0,react.createElement)(Button, {
        icon: "arrow-up-alt2",
        label: repeater_control_('Move up', 'flextension'),
        isSmall: "true",
        "aria-disabled": isFirstItem,
        disabled: isFirstItem,
        className: "flext-item-move-up-button",
        onClick: () => {
          if (!isFirstItem) {
            this.onMove(index, index - 1);
          }
        }
      }), (0,react.createElement)(Button, {
        icon: "arrow-down-alt2",
        label: repeater_control_('Move down', 'flextension'),
        isSmall: "true",
        "aria-disabled": isLastItem,
        disabled: isLastItem,
        className: "flext-item-move-down-button",
        onClick: () => {
          if (!isLastItem) {
            this.onMove(index, index + 1);
          }
        }
      }), (0,react.createElement)(Button, {
        icon: "no-alt",
        label: repeater_control_('Remove', 'flextension'),
        isSmall: "true",
        className: "flext-item-remove-button",
        onClick: () => {
          this.onRemove(index);
        }
      })));
    })), onAddNew && (0,react.createElement)(Button, {
      id: controlId,
      variant: 'link',
      text: repeater_control_('Add New', 'flextension'),
      className: "flext-item-add-button is-link",
      onClick: this.onAddNew
    }));
  }
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/responsive-dropdown/breakpoints.js
/**
 * WordPress dependencies
 */
const {
  __: breakpoints_
} = wp.i18n;
const {
  flextensionEditor
} = window;
const breakpointSizes = Object.assign({
  desktop: 1024,
  tablet: 768,
  mobile: 0
}, flextensionEditor?.breakpoints || {});
const Breakpoints = {
  desktop: {
    label: breakpoints_('Desktop', 'flextension'),
    icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h7v2H8v2h8v-2h-2v-2h7c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H3V4h18v12z"/></svg>',
    width: breakpointSizes.desktop
  },
  tablet: {
    label: breakpoints_('Tablet', 'flextension'),
    icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.5 0h-14C3.12 0 2 1.12 2 2.5v19C2 22.88 3.12 24 4.5 24h14c1.38 0 2.5-1.12 2.5-2.5v-19C21 1.12 19.88 0 18.5 0zm-7 23c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm7.5-4H4V3h15v16z"/></svg>',
    width: breakpointSizes.tablet
  },
  mobile: {
    label: breakpoints_('Mobile', 'flextension'),
    icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"/></svg>',
    width: breakpointSizes.mobile
  }
};
/* harmony default export */ var breakpoints = (Breakpoints);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/responsive-dropdown/index.js

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */

const {
  Component: responsive_dropdown_Component,
  createRef
} = wp.element;
const {
  compose: responsive_dropdown_compose
} = wp.compose;
const {
  withSelect: responsive_dropdown_withSelect,
  withDispatch
} = wp.data;

/**
 * Responsive Dropdown Control.
 */
class ResponsiveDropdownControl extends responsive_dropdown_Component {
  constructor() {
    super(...arguments);
    this.state = {
      isOpened: false
    };
    this.componentRef = createRef();
    this.handleClickOutside = this.handleClickOutside.bind(this);
    this.getButton = this.getButton.bind(this);
  }
  componentDidMount() {
    document.addEventListener('mousedown', this.handleClickOutside);
  }
  componentWillUnmount() {
    document.removeEventListener('mousedown', this.handleClickOutside);
  }

  /**
   * Hides opened dropdown.
   *
   * @param {Event} e Event.
   */
  handleClickOutside(e) {
    if (this.componentRef && this.componentRef.current && this.componentRef.current.contains(e.target)) {
      return;
    }
    this.setState({
      isOpened: false
    });
  }

  /**
   * Gets responsive button.
   *
   * @param {string}   name    Breakpoint name.
   * @param {Function} onClick Callback function.
   * @return {HTMLButtonElement} Dropdown button.
   */
  getButton(name, onClick) {
    const {
      breakpoint
    } = this.props;
    name = name || 'desktop';
    if (typeof breakpoints[name] === 'undefined') {
      return '';
    }
    const info = breakpoints[name];
    return (0,react.createElement)("button", {
      key: name,
      className: classnames_default()('flext-component-breakpoints-dropdown-item', breakpoint === name ? 'flext-component-breakpoints-dropdown-item-active' : ''),
      onClick: () => {
        onClick(name === 'desktop' ? '' : name);
      },
      dangerouslySetInnerHTML: {
        __html: info.icon
      }
    });
  }
  render() {
    const {
      breakpoint,
      updateBreakpoint
    } = this.props;
    const {
      isOpened
    } = this.state;
    return (0,react.createElement)("div", {
      className: "flext-component-breakpoints",
      ref: this.componentRef
    }, this.getButton(breakpoint, () => {
      this.setState({
        isOpened: !isOpened
      });
    }), isOpened ? (0,react.createElement)("div", {
      className: "flext-component-breakpoints-dropdown-list"
    }, Object.keys(breakpoints).map(name => {
      return this.getButton(name, value => {
        updateBreakpoint(value);
        this.setState({
          isOpened: false
        });
      });
    })) : '');
  }
}
/* harmony default export */ var responsive_dropdown = (responsive_dropdown_compose([responsive_dropdown_withSelect(select => {
  const {
    getBreakpoint
  } = select('flextension/breakpoint');
  return {
    breakpoint: getBreakpoint()
  };
}), withDispatch(dispatch => {
  const {
    updateBreakpoint
  } = dispatch('flextension/breakpoint');
  return {
    updateBreakpoint
  };
})])(ResponsiveDropdownControl));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/responsive-panel/index.js

/**
 * WordPress dependencies
 */
const {
  Component: responsive_panel_Component,
  Fragment
} = wp.element;
const {
  compose: responsive_panel_compose
} = wp.compose;
const {
  withSelect: responsive_panel_withSelect,
  withDispatch: responsive_panel_withDispatch
} = wp.data;


/**
 * Responsive Panel Component
 */
class ResponsivePanel extends responsive_panel_Component {
  render() {
    const {
      breakpoint,
      children
    } = this.props;
    const data = {
      breakpoint,
      ResponsiveDropdown: responsive_dropdown
    };
    return (0,react.createElement)(Fragment, {
      key: `responsive-panel-${breakpoint}`
    }, children(data));
  }
}
/* harmony default export */ var responsive_panel = (responsive_panel_compose([responsive_panel_withSelect(select => {
  const {
    getBreakpoint
  } = select('flextension/breakpoint');
  return {
    breakpoint: getBreakpoint()
  };
}), responsive_panel_withDispatch(dispatch => {
  const {
    updateBreakpoint
  } = dispatch('flextension/breakpoint');
  return {
    updateBreakpoint
  };
})])(ResponsivePanel));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/scheme-dropdown/schemes.js

/**
 * WordPress dependencies
 */
const {
  __: schemes_
} = wp.i18n;
const SCHEMES = {
  default: {
    label: schemes_('Default', 'flextension'),
    icon: 'admin-customizer',
    value: ''
  },
  dark: {
    label: schemes_('Dark', 'flextension'),
    icon: (0,react.createElement)("svg", {
      width: "20",
      height: "20",
      viewBox: "0 0 24 24",
      fill: "#111"
    }, (0,react.createElement)("path", {
      d: "M12 11.807C10.7418 10.5483 9.88488 8.94484 9.53762 7.1993C9.19037 5.45375 9.36832 3.64444 10.049 2C8.10826 2.38205 6.3256 3.33431 4.92899 4.735C1.02399 8.64 1.02399 14.972 4.92899 18.877C8.83499 22.783 15.166 22.782 19.072 18.877C20.4723 17.4805 21.4245 15.6983 21.807 13.758C20.1625 14.4385 18.3533 14.6164 16.6077 14.2692C14.8622 13.9219 13.2588 13.0651 12 11.807V11.807Z"
    })),
    value: 'dark'
  },
  light: {
    label: schemes_('Light', 'flextension'),
    icon: (0,react.createElement)("svg", {
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
    })),
    value: 'light'
  }
};
/* harmony default export */ var schemes = (SCHEMES);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/scheme-dropdown/index.js

/**
 * Internal dependencies
 */

const {
  Component: scheme_dropdown_Component
} = wp.element;
const {
  Button: scheme_dropdown_Button,
  Dropdown,
  Icon,
  MenuItem,
  MenuGroup
} = wp.components;

/**
 * Scheme Dropdown Control.
 */
class SchemeDropdownControl extends scheme_dropdown_Component {
  constructor() {
    super(...arguments);
    const {
      value
    } = this.props;
    this.state = {
      value
    };
  }
  render() {
    const {
      label,
      onChange
    } = this.props;
    const current = schemes[this.state.value || 'default'] || schemes["default"];
    return (0,react.createElement)(Dropdown, {
      className: 'flext-component-scheme-dropdown',
      contentClassName: "flext-component-scheme-dropdown-content",
      position: "bottom right",
      renderToggle: _ref => {
        let {
          isOpen,
          onToggle
        } = _ref;
        return (0,react.createElement)(scheme_dropdown_Button, {
          icon: current.icon,
          onClick: onToggle,
          "aria-expanded": isOpen
        }, label);
      },
      renderContent: _ref2 => {
        let {
          onClose
        } = _ref2;
        return (0,react.createElement)(MenuGroup, {
          label: label
        }, Object.keys(schemes).map(key => {
          return (0,react.createElement)(MenuItem, {
            key: 'flext-component-scheme-dropdown-item-' + schemes[key].value,
            className: current.value === schemes[key].value ? 'flext-component-scheme-dropdown-item is-selected' : 'flext-component-scheme-dropdown-item',
            icon: current.value === schemes[key].value ? 'yes' : null,
            isSelected: current.value === schemes[key].value,
            onClick: () => {
              this.setState({
                value: schemes[key].value
              });
              if (onChange) {
                onChange(schemes[key].value);
              }
              onClose();
            }
          }, (0,react.createElement)(Icon, {
            className: "flext-component-scheme-dropdown-item-icon",
            icon: schemes[key].icon
          }), schemes[key].label);
        }));
      }
    });
  }
}
/* harmony default export */ var scheme_dropdown = (SchemeDropdownControl);
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
// EXTERNAL MODULE: ./node_modules/lodash/isEqual.js
var isEqual = __webpack_require__(8446);
var isEqual_default = /*#__PURE__*/__webpack_require__.n(isEqual);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/server-side-render/server-side-render.js


/**
 * External dependencies
 */





/**
 * WordPress dependencies
 */
const {
  Component: server_side_render_Component,
  RawHTML
} = wp.element;
const {
  __: server_side_render_,
  sprintf: server_side_render_sprintf
} = wp.i18n;
const server_side_render_apiFetch = wp.apiFetch;
const {
  addQueryArgs: server_side_render_addQueryArgs
} = wp.url;
const {
  Placeholder,
  Spinner
} = wp.components;
const {
  doAction
} = wp.hooks;
function rendererPath(block) {
  let attributes = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  let urlQueryArgs = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  return server_side_render_addQueryArgs(`/wp/v2/block-renderer/${block}`, {
    context: 'edit',
    ...(null !== attributes ? {
      attributes
    } : {}),
    ...urlQueryArgs
  });
}
class ServerSideRender extends server_side_render_Component {
  constructor(props) {
    super(props);
    this.state = {
      response: null,
      prevResponse: null
    };
  }
  componentDidMount() {
    this.isStillMounted = true;
    this.fetch(this.props);
    // Only debounce once the initial fetch occurs to ensure that the first
    // renders show data as soon as possible.
    this.fetch = debounce_default()(this.fetch, 500);
  }
  componentWillUnmount() {
    this.isStillMounted = false;
  }
  componentDidUpdate(prevProps) {
    const prevAttributes = prevProps.attributes;
    const curAttributes = this.props.attributes;
    if (!isEqual_default()(prevAttributes, curAttributes)) {
      this.fetch(this.props);
    }
  }
  fetch(props) {
    if (!this.isStillMounted) {
      return;
    }
    const {
      block,
      attributes = null,
      onBeforeChange = () => {},
      onChange = () => {},
      clientId
    } = props;

    /**
     * Calls 'onChange' callback function after the content is loaded.
     *
     * @since 1.1.2
     */
    const onLoad = () => {
      const {
        flextension
      } = window;
      const content = document.querySelector('#block-' + clientId);
      if (content !== null) {
        onChange(content);
        doAction('flextension.components.serverSideRender.onChange', this.props, content);
        flextension.emit('ready', content);
      }
    };
    if (null !== this.state.response) {
      this.setState({
        response: null,
        prevResponse: this.state.response
      });
    }
    const path = rendererPath(block);

    // Store the latest fetch request so that when we process it, we can
    // check if it is the current request, to avoid race conditions on slow networks.
    const fetchRequest = this.currentFetchRequest = server_side_render_apiFetch({
      method: 'POST',
      path,
      data: {
        attributes
      }
    }).then(response => {
      if (this.isStillMounted && fetchRequest === this.currentFetchRequest && response) {
        onBeforeChange();
        doAction('flextension.components.serverSideRender.onBeforeChange', this.props);
        this.setState({
          response: response.rendered,
          prevResponse: null
        }, onLoad);
      }
    }).catch(error => {
      if (this.isStillMounted && fetchRequest === this.currentFetchRequest) {
        onBeforeChange();
        doAction('flextension.components.serverSideRender.onBeforeChange', this.props);
        this.setState({
          response: {
            error: true,
            errorMsg: error.message
          },
          prevResponse: null
        }, onLoad);
      }
    });
    return fetchRequest;
  }
  render() {
    const {
      response,
      prevResponse
    } = this.state;
    const {
      EmptyResponsePlaceholder,
      ErrorResponsePlaceholder,
      LoadingResponsePlaceholder
    } = this.props;
    let {
      className
    } = this.props;
    className = classnames_default()(className, 'flext-component-server-side-render');
    if (response === '') {
      return (0,react.createElement)(EmptyResponsePlaceholder, _extends({
        response: response
      }, this.props));
    } else if (!response && prevResponse) {
      className = classnames_default()(className, 'flext-component-server-side-render-loading');
      return (0,react.createElement)("div", {
        className: className
      }, (0,react.createElement)(Spinner, null), (0,react.createElement)(RawHTML, {
        key: "html",
        className: "flext-component-server-side-render-content"
      }, prevResponse));
    } else if (!response) {
      return (0,react.createElement)(LoadingResponsePlaceholder, _extends({
        response: response
      }, this.props));
    } else if (response.error) {
      return (0,react.createElement)(ErrorResponsePlaceholder, _extends({
        response: response
      }, this.props));
    }
    return (0,react.createElement)("div", {
      className: className
    }, (0,react.createElement)(RawHTML, {
      key: "html",
      className: "flext-component-server-side-render-content"
    }, response));
  }
}
ServerSideRender.defaultProps = {
  EmptyResponsePlaceholder: _ref => {
    let {
      className
    } = _ref;
    return (0,react.createElement)(Placeholder, {
      className: className
    }, server_side_render_('Block rendered as empty.', 'flextension'));
  },
  ErrorResponsePlaceholder: _ref2 => {
    let {
      response,
      className
    } = _ref2;
    const errorMessage = server_side_render_sprintf(
    // translators: %s: error message describing the problem
    server_side_render_('Error loading block: %s', 'flextension'), response.errorMsg);
    return (0,react.createElement)(Placeholder, {
      className: className
    }, errorMessage);
  },
  LoadingResponsePlaceholder: _ref3 => {
    let {
      className
    } = _ref3;
    return (0,react.createElement)(Placeholder, {
      className: className
    }, (0,react.createElement)(Spinner, null));
  }
};
/* harmony default export */ var server_side_render = (ServerSideRender);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/server-side-render/index.js


/**
 * WordPress dependencies
 */


const {
  useMemo
} = wp.element;
const {
  withSelect: server_side_render_withSelect
} = wp.data;

/**
 * Internal dependencies
 */


/**
 * Constants
 */
const EMPTY_OBJECT = {};
/* harmony default export */ var components_server_side_render = (server_side_render_withSelect(select => {
  const coreEditorSelect = select('core/editor');
  if (coreEditorSelect) {
    const currentPostId = coreEditorSelect.getCurrentPostId();
    if (currentPostId) {
      return {
        currentPostId
      };
    }
  }
  return EMPTY_OBJECT;
})(_ref => {
  let {
    urlQueryArgs = EMPTY_OBJECT,
    currentPostId,
    ...props
  } = _ref;
  const newUrlQueryArgs = useMemo(() => {
    if (!currentPostId) {
      return urlQueryArgs;
    }
    return {
      post_id: currentPostId,
      ...urlQueryArgs
    };
  }, [currentPostId, urlQueryArgs]);
  return (0,react.createElement)(server_side_render, _extends({
    urlQueryArgs: newUrlQueryArgs
  }, props));
}));
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/spacing-control/utils.js

/**
 * WordPress dependencies
 */
const {
  __: utils_
} = wp.i18n;

/**
 * Constants
 */
const LABELS = {
  all: utils_('All', 'flextension'),
  top: utils_('Top', 'flextension'),
  bottom: utils_('Bottom', 'flextension'),
  left: utils_('Left', 'flextension'),
  right: utils_('Right', 'flextension')
};
const DEFAULT_SIDES = ['top', 'right', 'bottom', 'left'];
const DEFAULT_VALUES = {
  top: null,
  right: null,
  bottom: null,
  left: null
};

/**
 * Parses a number and unit from a value.
 *
 * @param {string} initialValue Value to parse
 * @return {Array<number, string>} The extracted number and unit.
 */
function parseUnit(initialValue) {
  const value = String(initialValue).trim();
  let num = parseFloat(value, 10);
  num = isNaN(num) ? '' : num;
  const unitMatch = value.match(/[\d.\-\+]*\s*(.*)/)[1];
  let unit = unitMatch !== undefined ? unitMatch : '';
  unit = unit.toLowerCase();
  return [num, unit];
}

/**
 * Checks to determine if values are mixed.
 *
 * @param {Object} values Box values.
 * @return {boolean} Whether values are mixed.
 */
function isValuesMixed() {
  let values = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  /*const allValue = getAllValue( values );
  const isMixed = isNaN( parseFloat( allValue ) );*/

  values = {
    ...DEFAULT_VALUES,
    ...values
  };
  return !Object.values(values).every((value, index, object) => value === object[0]);
}

/**
 * Checks to determine if values are defined.
 *
 * @param {Object} values Box values.
 *
 * @return {boolean} Whether values are mixed.
 */
function isValuesDefined(values) {
  return values !== undefined && !isEmpty_default()(Object.values(values).filter(Boolean));
}
;// CONCATENATED MODULE: ./node_modules/@wordpress/primitives/build-module/svg/index.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/** @typedef {{isPressed?: boolean} & import('react').ComponentPropsWithoutRef<'svg'>} SVGProps */

/**
 * @param {import('react').ComponentPropsWithoutRef<'circle'>} props
 *
 * @return {JSX.Element} Circle component
 */

const Circle = props => createElement('circle', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'g'>} props
 *
 * @return {JSX.Element} G component
 */

const G = props => createElement('g', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'line'>} props
 *
 * @return {JSX.Element} Path component
 */

const Line = props => createElement('line', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'path'>} props
 *
 * @return {JSX.Element} Path component
 */

const svg_Path = props => (0,react.createElement)('path', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'polygon'>} props
 *
 * @return {JSX.Element} Polygon component
 */

const Polygon = props => createElement('polygon', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'rect'>} props
 *
 * @return {JSX.Element} Rect component
 */

const Rect = props => createElement('rect', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'defs'>} props
 *
 * @return {JSX.Element} Defs component
 */

const Defs = props => createElement('defs', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'radialGradient'>} props
 *
 * @return {JSX.Element} RadialGradient component
 */

const RadialGradient = props => createElement('radialGradient', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'linearGradient'>} props
 *
 * @return {JSX.Element} LinearGradient component
 */

const LinearGradient = props => createElement('linearGradient', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'stop'>} props
 *
 * @return {JSX.Element} Stop component
 */

const Stop = props => createElement('stop', props);
/**
 *
 * @param {SVGProps} props isPressed indicates whether the SVG should appear as pressed.
 *                         Other props will be passed through to svg component.
 *
 * @return {JSX.Element} Stop component
 */

const svg_SVG = _ref => {
  let {
    className,
    isPressed,
    ...props
  } = _ref;
  const appliedProps = { ...props,
    className: classnames_default()(className, {
      'is-pressed': isPressed
    }) || undefined,
    'aria-hidden': true,
    focusable: false
  }; // Disable reason: We need to have a way to render HTML tag for web.
  // eslint-disable-next-line react/forbid-elements

  return (0,react.createElement)("svg", appliedProps);
};
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./node_modules/@wordpress/icons/build-module/library/link.js


/**
 * WordPress dependencies
 */

const link_link = (0,react.createElement)(svg_SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,react.createElement)(svg_Path, {
  d: "M15.6 7.2H14v1.5h1.6c2 0 3.7 1.7 3.7 3.7s-1.7 3.7-3.7 3.7H14v1.5h1.6c2.8 0 5.2-2.3 5.2-5.2 0-2.9-2.3-5.2-5.2-5.2zM4.7 12.4c0-2 1.7-3.7 3.7-3.7H10V7.2H8.4c-2.9 0-5.2 2.3-5.2 5.2 0 2.9 2.3 5.2 5.2 5.2H10v-1.5H8.4c-2 0-3.7-1.7-3.7-3.7zm4.6.9h5.3v-1.5H9.3v1.5z"
}));
/* harmony default export */ var library_link = (link_link);
//# sourceMappingURL=link.js.map
;// CONCATENATED MODULE: ./node_modules/@wordpress/icons/build-module/library/link-off.js


/**
 * WordPress dependencies
 */

const linkOff = (0,react.createElement)(svg_SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,react.createElement)(svg_Path, {
  d: "M15.6 7.3h-.7l1.6-3.5-.9-.4-3.9 8.5H9v1.5h2l-1.3 2.8H8.4c-2 0-3.7-1.7-3.7-3.7s1.7-3.7 3.7-3.7H10V7.3H8.4c-2.9 0-5.2 2.3-5.2 5.2 0 2.9 2.3 5.2 5.2 5.2H9l-1.4 3.2.9.4 5.7-12.5h1.4c2 0 3.7 1.7 3.7 3.7s-1.7 3.7-3.7 3.7H14v1.5h1.6c2.9 0 5.2-2.3 5.2-5.2 0-2.9-2.4-5.2-5.2-5.2z"
}));
/* harmony default export */ var link_off = (linkOff);
//# sourceMappingURL=link-off.js.map
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/spacing-control/index.js

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */

const {
  __: spacing_control_
} = wp.i18n;
const {
  Component: spacing_control_Component
} = wp.element;
const {
  Button: spacing_control_Button,
  __experimentalUnitControl: UnitControl
} = wp.components;

/**
 * Spacing Control
 */
class SpacingControl extends spacing_control_Component {
  constructor() {
    super(...arguments);
    const {
      sides = DEFAULT_SIDES,
      values: valuesProp,
      values
    } = this.props;
    const hasInitialValue = isValuesDefined(valuesProp);
    const hasOneSide = sides?.length === 1;
    this.handleOnChange = this.handleOnChange.bind(this);
    this.handleOnReset = this.handleOnReset.bind(this);
    this.toggleLinked = this.toggleLinked.bind(this);
    this.state = {
      values: valuesProp,
      isLinked: !hasInitialValue || !isValuesMixed(values) || hasOneSide,
      isDirty: hasInitialValue
    };
  }

  /**
   * Updates spacing values.
   *
   * @param {string} nextValues New spacing values.
   */
  handleOnChange(nextValues) {
    const {
      onChange
    } = this.props;
    const values = isValuesDefined(nextValues) ? nextValues : undefined;
    onChange(values);
    this.setState({
      values,
      isDirty: true
    });
  }

  /**
   * Reset spacing values.
   */
  handleOnReset() {
    const {
      onChange
    } = this.props;
    onChange(undefined);
    this.setState({
      values: undefined,
      isDirty: false,
      isLinked: true
    });
  }

  /**
   * Links or Unlinks spacing sides.
   */
  toggleLinked() {
    const {
      values,
      isLinked
    } = this.state;
    const nextLinked = !isLinked;
    if (nextLinked && isValuesDefined(values)) {
      const {
        sides = DEFAULT_SIDES
      } = this.props;
      const nextValues = {
        ...values
      };
      const side = 'top';
      sides.forEach(name => {
        if (name !== side) {
          if (values[side]) {
            nextValues[name] = values[side];
          } else {
            delete nextValues[name];
          }
        }
      });
      this.handleOnChange(nextValues);
    }
    this.setState({
      isLinked: nextLinked
    });
  }
  render() {
    const {
      label,
      sides = DEFAULT_SIDES,
      values = {},
      responsiveDropdown: ResponsiveDropdown
    } = this.props;
    const createHandleOnChange = side => next => {
      const nextValues = {
        ...values
      };
      const {
        isLinked
      } = this.state;
      if (next) {
        nextValues[side] = next;
      } else {
        delete nextValues[side];
      }
      if (isLinked) {
        sides.forEach(name => {
          if (name !== side) {
            if (next) {
              nextValues[name] = next;
            } else {
              delete nextValues[name];
            }
          }
        });
      }
      this.handleOnChange(nextValues);
    };
    const {
      isLinked,
      isDirty
    } = this.state;
    const filteredSides = isLinked ? sides.filter((side, index) => index === 0) : sides;
    return (0,react.createElement)("div", {
      className: "flext-component-spacing-control"
    }, (0,react.createElement)("div", {
      className: "flext-component-spacing-control-heading"
    }, label && (0,react.createElement)("span", {
      className: "flext-component-spacing-label"
    }, label), ResponsiveDropdown && (0,react.createElement)(ResponsiveDropdown, null), isDirty && (0,react.createElement)(spacing_control_Button, {
      className: "flext-component-spacing-reset-button",
      isSecondary: true,
      isSmall: true,
      onClick: this.handleOnReset
    }, spacing_control_('Reset', 'flextension'))), (0,react.createElement)("div", {
      className: "flext-component-spacing-control-input-controls"
    }, filteredSides.map(side => {
      let spacingValue = values[side];
      if (typeof spacingValue === 'undefined') {
        spacingValue = '';
      }
      return (0,react.createElement)(UnitControl, {
        key: `flext-spacing-${side}`,
        label: isLinked ? LABELS.all : LABELS[side],
        value: spacingValue,
        labelPosition: "bottom",
        onChange: createHandleOnChange(side)
      });
    }), (0,react.createElement)(spacing_control_Button, {
      isPrimary: isLinked,
      isSecondary: !isLinked,
      icon: isLinked ? library_link : link_off,
      iconSize: 12,
      label: isLinked ? spacing_control_('Unlink Sides', 'flextension') : spacing_control_('Link Sides', 'flextension'),
      showTooltip: true,
      onClick: this.toggleLinked
    })));
  }
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/components/index.js
/**
 * Internal dependencies
 */
















// Extend the Flextension plugin.
const {
  flextension
} = window;
flextension.editor = {
  components: {
    AuthorSelectControl: author_select_control,
    PostSelectControl: post_select_control,
    PostTypeSelectControl: post_type_select_control,
    TaxonomySelectControl: taxonomy_select_control,
    TermSelectControl: term_select_control,
    HierarchicalTermSelector: hierarchical_term_selector,
    PostOrderControl: post_order_control,
    QueryControls: QueryControls,
    HeadingLevelDropdown: HeadingLevelDropdown,
    RepeaterControl: RepeaterControl,
    ResponsiveDropdown: responsive_dropdown,
    ResponsivePanel: responsive_panel,
    SchemeDropdownControl: scheme_dropdown,
    ServerSideRender: components_server_side_render,
    SpacingControl: SpacingControl
  }
};
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/highlight/inline-ui.js

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __: inline_ui_
} = wp.i18n;
const {
  useRef,
  useState
} = wp.element;
const {
  applyFormat,
  removeFormat,
  useAnchorRef
} = wp.richText;
const {
  ColorPalette
} = wp.blockEditor;
const {
  Button: inline_ui_Button,
  Popover,
  SelectControl: inline_ui_SelectControl
} = wp.components;
const Styles = [{
  label: inline_ui_('Underline', 'flextension'),
  value: 'underline'
}, {
  label: inline_ui_('Background', 'flextension'),
  value: 'background'
}, {
  label: inline_ui_('Rectangle', 'flextension'),
  value: 'rectangle'
}, {
  label: inline_ui_('Oval', 'flextension'),
  value: 'oval'
}, {
  label: inline_ui_('Strikethrough', 'flextension'),
  value: 'strikethrough'
}];
function InlineUI(_ref) {
  let {
    name,
    value,
    onChange,
    onClose,
    activeAttributes,
    addingAnimation,
    contentRef
  } = _ref;
  const [highlightValue, setHighlightValue] = useState({
    color: activeAttributes.style ? activeAttributes.style.replace(new RegExp(`^--flext-highlight-color:\\s*`), '') : '',
    style: activeAttributes.class ? activeAttributes.class.replace(/.*flext-is-style-([^\s]*).*/, '$1') : ''
  });
  function changeHighlight(newValue) {
    newValue = {
      ...highlightValue,
      ...newValue
    };
    setHighlightValue(newValue);
    if (!newValue) {
      newValue = highlightValue;
    }
    if (newValue.color) {
      if (!newValue.style) {
        newValue.style = 'underline';
      }
      const format = {
        type: name,
        attributes: {
          class: `flext-is-style-${newValue.style}`,
          style: `--flext-highlight-color:${newValue.color}`
        }
      };
      onChange(applyFormat(value, format));
    } else {
      onChange(removeFormat(value, name));
    }
  }
  const anchorRef = useAnchorRef({
    ref: contentRef,
    value,
    settings: highlight
  });
  const anchorRect = anchorRef ? anchorRef.getBoundingClientRect() : contentRef.current.getBoundingClientRect();

  // The focusOnMount prop shouldn't evolve during render of a Popover
  // otherwise it causes a render of the content.
  const focusOnMount = useRef(addingAnimation ? 'container' : false);
  return (0,react.createElement)(Popover, {
    className: "flext-inline-highlight-popover",
    position: "bottom center",
    focusOnMount: focusOnMount.current,
    anchorRect: anchorRect,
    onClose: onClose,
    onFocusOutside: onClose
  }, (0,react.createElement)(ColorPalette, {
    label: inline_ui_('Color', 'flextension'),
    value: highlightValue.color,
    onChange: color => {
      changeHighlight({
        color
      });
    }
  }), (0,react.createElement)(inline_ui_SelectControl, {
    label: inline_ui_('Style', 'flextension'),
    value: highlightValue.style,
    options: Styles,
    onChange: style => {
      changeHighlight({
        style
      });
    }
  }), (0,react.createElement)("div", {
    className: "flext-format-highlight-buttons"
  }, (0,react.createElement)(inline_ui_Button, {
    isPrimary: true,
    disabled: !highlightValue.color,
    onClick: () => {
      changeHighlight();
      onClose();
    }
  }, inline_ui_('Apply', 'flextension'))));
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/highlight/index.js


/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __: highlight_
} = wp.i18n;
const {
  useCallback,
  useMemo: highlight_useMemo,
  useState: highlight_useState
} = wp.element;
const {
  BlockFormatControls,
  useSetting
} = wp.blockEditor;
const {
  ToolbarButton,
  ToolbarGroup
} = wp.components;
const {
  getActiveFormat,
  registerFormatType,
  removeFormat: highlight_removeFormat
} = wp.richText;
const highlight_name = 'flext/highlight';
const title = highlight_('Highlight Marker', 'flextension');
const icon = (0,react.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "24",
  height: "24",
  viewBox: "0 0 24 24"
}, (0,react.createElement)("path", {
  d: "M17.6,18c0,0.2-0.2,0.4-0.4,0.4h-11c-0.2,0-0.4-0.2-0.4-0.4s0.2-0.4,0.4-0.4h11C17.4,17.5,17.6,17.7,17.6,18z M17.9,9.7 l-5.2,5.9c-0.1,0.1-0.2,0.1-0.3,0.2c0,0,0,0,0,0c-0.1,0-0.2,0-0.3-0.1l-1.4,0.7c-0.1,0-0.1,0-0.2,0c-0.1,0-0.2,0-0.3-0.1l-0.3-0.3 l-0.6,0.6c-0.1,0.1-0.2,0.1-0.3,0.1H6.5c-0.2,0-0.3-0.1-0.4-0.3C6,16.2,6,16,6.2,15.9L8,14l-0.3-0.3c-0.1-0.1-0.2-0.3-0.1-0.5 l0.7-1.4c-0.1-0.1-0.1-0.2-0.1-0.3c0-0.1,0.1-0.2,0.2-0.3L14.2,6c0.6-0.6,1.6-0.5,2.2,0.1l1.4,1.4C18.5,8,18.5,9,17.9,9.7z M9.3,15.3l-0.6-0.6l-1.1,1.1h1.3L9.3,15.3z M11.5,15L9,12.5l-0.4,0.8l1.7,1.7c0,0,0,0,0,0s0,0,0,0l0.4,0.4L11.5,15z M17.2,8.1 l-1.4-1.4c-0.3-0.3-0.7-0.3-1,0l-5.6,4.9l3.1,3.1l4.9-5.6C17.5,8.8,17.5,8.3,17.2,8.1z"
}));
const EMPTY_ARRAY = [];
function getActiveColor(formatName, formatValue) {
  const activeColorFormat = getActiveFormat(formatValue, formatName);
  if (!activeColorFormat) {
    return;
  }
  const styleColor = activeColorFormat.attributes.style;
  if (styleColor) {
    return styleColor.replace(new RegExp(`^--flext-highlight-color:\\s*`), '');
  }
}
function HighlightEdit(_ref) {
  let {
    value,
    onChange,
    isActive,
    activeAttributes,
    contentRef
  } = _ref;
  const allowCustomControl = useSetting('color.custom');
  const colors = useSetting('color.palette') || EMPTY_ARRAY;
  const [isAddingHighlight, setIsAddingHighlight] = highlight_useState(false);
  const enableIsAddingHighlight = useCallback(() => setIsAddingHighlight(true), [setIsAddingHighlight]);
  const disableIsAddingHighlight = useCallback(() => setIsAddingHighlight(false), [setIsAddingHighlight]);
  const colorIndicatorStyle = highlight_useMemo(() => {
    const activeColor = getActiveColor(highlight_name, value);
    if (!activeColor) {
      return undefined;
    }
    return {
      backgroundColor: activeColor
    };
  }, [value]);
  const hasColorsToChoose = !isEmpty_default()(colors) || !allowCustomControl;
  if (!hasColorsToChoose && !isActive) {
    return null;
  }
  return (0,react.createElement)(BlockFormatControls, null, (0,react.createElement)(ToolbarGroup, null, (0,react.createElement)(ToolbarButton, {
    className: "flext-format-highlight-button",
    isActive: isActive,
    icon: (0,react.createElement)(react.Fragment, null, icon, isActive && (0,react.createElement)("span", {
      className: "flext-format-highlight-button-indicator",
      style: colorIndicatorStyle
    })),
    title: title
    // If has no colors to choose but a color is active remove the color onClick
    ,
    onClick: hasColorsToChoose ? enableIsAddingHighlight : () => onChange(highlight_removeFormat(value, highlight_name))
  })), isAddingHighlight && (0,react.createElement)(InlineUI, {
    name: highlight_name,
    onClose: disableIsAddingHighlight,
    activeAttributes: activeAttributes,
    value: value,
    onChange: function () {
      onChange(...arguments);
    },
    contentRef: contentRef
  }));
}
const highlight = {
  name: highlight_name,
  title,
  tagName: 'mark',
  className: 'flext-has-highlight',
  attributes: {
    class: 'class',
    style: 'style'
  },
  edit: HighlightEdit
};
registerFormatType(highlight_name, highlight);
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
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/inline-animation/inline-ui.js

/**
 * Internal dependencies
 */


const {
  __: inline_animation_inline_ui_
} = wp.i18n;
const {
  useRef: inline_ui_useRef,
  useState: inline_ui_useState
} = wp.element;
const {
  applyFormat: inline_ui_applyFormat,
  removeFormat: inline_ui_removeFormat,
  useAnchorRef: inline_ui_useAnchorRef
} = wp.richText;
const {
  Button: inline_animation_inline_ui_Button,
  Popover: inline_ui_Popover,
  SelectControl: inline_animation_inline_ui_SelectControl,
  FormTokenField: inline_ui_FormTokenField
} = wp.components;
const Animations = [{
  label: inline_animation_inline_ui_('None', 'flextension'),
  value: ''
}, {
  label: inline_animation_inline_ui_('Clip', 'flextension'),
  value: 'clip'
}, {
  label: inline_animation_inline_ui_('Fade In Up', 'flextension'),
  value: 'fade-up'
}, {
  label: inline_animation_inline_ui_('Flip - Horizontal', 'flextension'),
  value: 'flip-horizontal'
}, {
  label: inline_animation_inline_ui_('Flip - Vertical', 'flextension'),
  value: 'flip-vertical'
}, {
  label: inline_animation_inline_ui_('Slide - Horizontal', 'flextension'),
  value: 'slide-horizontal'
}, {
  label: inline_animation_inline_ui_('Slide - Vertical', 'flextension'),
  value: 'slide-vertical'
}, {
  label: inline_animation_inline_ui_('Typewriter', 'flextension'),
  value: 'typewriter'
}, {
  label: inline_animation_inline_ui_('Zoom In', 'flextension'),
  value: 'zoom-in'
}, {
  label: inline_animation_inline_ui_('Zoom Out', 'flextension'),
  value: 'zoom-out'
}];
let currentAnimation = null;
let animationTimeout = null;
function InlineAnimationUI(_ref) {
  let {
    name,
    value,
    onChange,
    onClose,
    activeAttributes,
    addingAnimation,
    contentRef
  } = _ref;
  const [animationValue, setAnimationValue] = inline_ui_useState({
    animation: activeAttributes.class ? activeAttributes.class.replace(/.*flext-inline-([^\s]*).*/, '$1') : '',
    text: activeAttributes.text,
    speed: activeAttributes.speed
  });
  const [isPlayed, setIsPlayed] = inline_ui_useState(false);
  function changeAnimation(newValue) {
    newValue = {
      ...animationValue,
      ...newValue
    };
    setAnimationValue(newValue);
    applyAnimation(newValue);
    if (animationTimeout) {
      clearTimeout(animationTimeout);
    }
    animationTimeout = setTimeout(() => {
      playAnimation(newValue);
    }, 500);
  }
  function close() {
    stopAnimation();
    onClose();
  }
  function applyAnimation(newValue) {
    if (!newValue) {
      newValue = animationValue;
    }
    if (newValue.animation) {
      const attributes = {
        class: `flext-inline-${newValue.animation}`
      };
      if (newValue.text) {
        attributes.text = newValue.text;
      }
      if (newValue.speed) {
        attributes.speed = newValue.speed;
      }
      const format = {
        type: name,
        attributes
      };
      onChange(inline_ui_applyFormat(value, format));
    } else {
      onChange(inline_ui_removeFormat(value, name));
    }
  }
  function clearAnimation() {
    applyAnimation({
      animation: '',
      text: '',
      speed: 1
    });
    close();
  }
  function playAnimation(newValue) {
    if (!newValue) {
      newValue = animationValue;
    }
    if (!newValue.animation) {
      stopAnimation();
      return;
    }
    if (!contentRef.current) {
      return;
    }
    stopAnimation();
    contentRef.current.setAttribute('contenteditable', 'false');
    contentRef.current.querySelectorAll('.flext-has-inline-animation').forEach(el => {
      switch (newValue.animation) {
        case 'clip':
          currentAnimation = new ClipAnimation(el);
          break;
        case 'fade-up':
          currentAnimation = new FadeUpAnimation(el);
          break;
        case 'typewriter':
          currentAnimation = new TypewriterAnimation(el);
          break;
        case 'flip-horizontal':
        case 'flip-vertical':
          currentAnimation = new FlipAnimation(el);
          break;
        case 'slide-horizontal':
        case 'slide-vertical':
          currentAnimation = new SlideAnimation(el);
          break;
        case 'zoom-in':
        case 'zoom-out':
          currentAnimation = new ZoomAnimation(el);
          break;
      }
    });
    setIsPlayed(true);
  }
  function stopAnimation() {
    if (animationTimeout) {
      clearTimeout(animationTimeout);
    }
    if (currentAnimation) {
      currentAnimation.stop();
    }
    contentRef.current.setAttribute('contenteditable', 'true');
    setIsPlayed(false);
  }
  const anchorRef = inline_ui_useAnchorRef({
    ref: contentRef,
    value,
    settings: inlineAnimation
  });
  const anchorRect = anchorRef ? anchorRef.getBoundingClientRect() : contentRef.current.getBoundingClientRect();

  // The focusOnMount prop shouldn't evolve during render of a Popover
  // otherwise it causes a render of the content.
  const focusOnMount = inline_ui_useRef(addingAnimation ? 'container' : false);
  return (0,react.createElement)(inline_ui_Popover, {
    className: "flext-inline-animation-popover",
    position: "bottom center",
    focusOnMount: focusOnMount.current,
    anchorRect: anchorRect,
    onClose: close,
    onFocusOutside: close
  }, (0,react.createElement)(inline_animation_inline_ui_SelectControl, {
    label: inline_animation_inline_ui_('Animation', 'flextension'),
    value: animationValue.animation,
    options: Animations,
    onChange: animation => {
      changeAnimation({
        animation
      });
    }
  }), animationValue.animation && (0,react.createElement)(inline_ui_FormTokenField, {
    label: inline_animation_inline_ui_('Rotating words', 'flextension'),
    value: animationValue.text ? animationValue.text.split(',') : [],
    onChange: words => {
      changeAnimation({
        text: words ? words.join(',') : ''
      });
    }
  }), animationValue.animation && (0,react.createElement)(inline_animation_inline_ui_SelectControl, {
    label: inline_animation_inline_ui_('Speed', 'flextension'),
    value: animationValue.speed || 1,
    options: [{
      value: 0,
      label: inline_animation_inline_ui_('Slow', 'flextension')
    }, {
      value: 1,
      label: inline_animation_inline_ui_('Normal', 'flextension')
    }, {
      value: 2,
      label: inline_animation_inline_ui_('Fast', 'flextension')
    },,],
    onChange: speed => {
      changeAnimation({
        speed
      });
    }
  }), (0,react.createElement)("div", {
    className: "flext-format-inline-animation-buttons"
  }, animationValue.animation && (0,react.createElement)(inline_animation_inline_ui_Button, {
    isSecondary: true,
    icon: isPlayed ? 'controls-pause' : 'controls-play',
    label: isPlayed ? inline_animation_inline_ui_('Stop', 'flextension') : inline_animation_inline_ui_('Preview', 'flextension'),
    onClick: () => isPlayed ? stopAnimation() : playAnimation()
  }), (0,react.createElement)(inline_animation_inline_ui_Button, {
    isPrimary: true,
    onClick: () => {
      applyAnimation();
      close();
    }
  }, inline_animation_inline_ui_('Apply', 'flextension')), animationValue.animation && (0,react.createElement)(inline_animation_inline_ui_Button, {
    isSecondary: true,
    onClick: () => clearAnimation()
  }, inline_animation_inline_ui_('Clear', 'flextension'))));
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/inline-animation/index.js


/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */
const {
  __: inline_animation_
} = wp.i18n;
const {
  useState: inline_animation_useState
} = wp.element;
const {
  BlockFormatControls: inline_animation_BlockFormatControls
} = wp.blockEditor;
const {
  ToolbarButton: inline_animation_ToolbarButton,
  ToolbarGroup: inline_animation_ToolbarGroup
} = wp.components;
const {
  registerFormatType: inline_animation_registerFormatType
} = wp.richText;
const inline_animation_name = 'flextension/inline-animation';
const inline_animation_title = inline_animation_('Inline animation', 'flextension');
const inline_animation_icon = (0,react.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "24",
  height: "24",
  viewBox: "0 0 24 24"
}, (0,react.createElement)("path", {
  d: "M6.2,8.2H5V7h1.2V8.2z M12.3,7h-5v1.2h5V7z M9.6,12.6h1.2v-1.2H9.6V12.6z M3.4,12.6h5v-1.2h-5V12.6z M3.4,15.8H2.2V17h1.2 V15.8z M8.3,15.8h-4V17h4V15.8z M21.8,18.4h-2.2l-1.3-3.5h-4.9l-1.3,3.5H9.9l4.7-12.9h2.3L21.8,18.4z M17.7,13.2l-2-5.7L14,13.2 H17.7z"
}));
function InlineAnimationEdit(_ref) {
  let {
    value,
    onChange,
    onFocus,
    isActive,
    activeAttributes,
    contentRef
  } = _ref;
  const [addingAnimation, setAddingAnimation] = inline_animation_useState(false);
  const hasAnimationsToChoose = !isEmpty_default()(Animations);
  if (!hasAnimationsToChoose && !isActive) {
    return null;
  }
  function addAnimation() {
    if (isActive || value.end - value.start > 0) {
      setAddingAnimation(true);
    }
  }
  function onClose() {
    setAddingAnimation(false);
    onFocus();
  }
  return (0,react.createElement)(inline_animation_BlockFormatControls, null, (0,react.createElement)(inline_animation_ToolbarGroup, null, (0,react.createElement)(inline_animation_ToolbarButton, {
    key: 'flext-inline-animation-not-active',
    className: "flext-format-inline-animation-button",
    isActive: isActive,
    icon: inline_animation_icon,
    title: inline_animation_title,
    onClick: addingAnimation ? onClose : addAnimation
  })), addingAnimation && (0,react.createElement)(InlineAnimationUI, {
    name: inline_animation_name,
    addingAnimation: addingAnimation,
    activeAttributes: activeAttributes,
    value: value,
    onChange: onChange,
    onClose: onClose,
    contentRef: contentRef
  }));
}
const inlineAnimation = {
  name: inline_animation_name,
  title: inline_animation_title,
  tagName: 'span',
  className: 'flext-has-inline-animation',
  attributes: {
    class: 'class',
    text: 'data-text',
    speed: 'data-speed'
  },
  edit: InlineAnimationEdit
};
inline_animation_registerFormatType(inline_animation_name, inlineAnimation);
;// CONCATENATED MODULE: ./node_modules/@wordpress/icons/build-module/library/format-underline.js


/**
 * WordPress dependencies
 */

const formatUnderline = (0,react.createElement)(svg_SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,react.createElement)(svg_Path, {
  d: "M7 18v1h10v-1H7zm5-2c1.5 0 2.6-.4 3.4-1.2.8-.8 1.1-2 1.1-3.5V5H15v5.8c0 1.2-.2 2.1-.6 2.8-.4.7-1.2 1-2.4 1s-2-.3-2.4-1c-.4-.7-.6-1.6-.6-2.8V5H7.5v6.2c0 1.5.4 2.7 1.1 3.5.8.9 1.9 1.3 3.4 1.3z"
}));
/* harmony default export */ var format_underline = (formatUnderline);
//# sourceMappingURL=format-underline.js.map
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/underline/index.js

/**
 * WordPress dependencies
 */

const {
  __: underline_
} = wp.i18n;
const {
  registerFormatType: underline_registerFormatType,
  toggleFormat
} = wp.richText;
const {
  BlockFormatControls: underline_BlockFormatControls
} = wp.blockEditor;
const {
  ToolbarButton: underline_ToolbarButton,
  ToolbarGroup: underline_ToolbarGroup
} = wp.components;
const underline_name = 'flextension/underline';
const underline_title = underline_('Underline', 'flextension');
const underline = {
  name: underline_name,
  title: underline_title,
  tagName: 'span',
  className: 'flext-format-underline',
  edit(_ref) {
    let {
      isActive,
      value,
      onChange,
      onFocus
    } = _ref;
    function onToggle() {
      onChange(toggleFormat(value, {
        type: underline_name
      }));
    }
    function onClick() {
      onToggle();
      onFocus();
    }
    return (0,react.createElement)(underline_BlockFormatControls, null, (0,react.createElement)(underline_ToolbarGroup, null, (0,react.createElement)(underline_ToolbarButton, {
      icon: format_underline,
      title: underline_title,
      onClick: onClick,
      isActive: isActive
    })));
  }
};
underline_registerFormatType(underline_name, underline);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/formats/index.js
/**
 * Internal dependencies
 */



;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/animation/animations.js
/**
 * WordPress dependencies
 */
const {
  __: animations_
} = wp.i18n;
const {
  flextensionAnimations
} = window;
const ANIMATIONS = Object.assign([{
  value: '',
  label: animations_('None', 'flextension')
}, {
  value: 'fade-in',
  label: animations_('Fade In', 'flextension')
}, {
  value: 'fade-up',
  label: animations_('Fade Up', 'flextension')
}, {
  value: 'fade-down',
  label: animations_('Fade Down', 'flextension')
}, {
  value: 'fade-left',
  label: animations_('Fade Left', 'flextension')
}, {
  value: 'fade-right',
  label: animations_('Fade Right', 'flextension')
}, {
  value: 'elastic-in-up',
  label: animations_('Elastic In Up', 'flextension')
}, {
  value: 'elastic-in-down',
  label: animations_('Elastic In Down', 'flextension')
}, {
  value: 'elastic-in-left',
  label: animations_('Elastic In Left', 'flextension')
}, {
  value: 'elastic-in-right',
  label: animations_('Elastic In Right', 'flextension')
}, {
  value: 'zoom-in',
  label: animations_('Zoom In', 'flextension')
}, {
  value: 'zoom-in-up',
  label: animations_('Zoom In Up', 'flextension')
}, {
  value: 'zoom-in-down',
  label: animations_('Zoom In Down', 'flextension')
}, {
  value: 'zoom-in-left',
  label: animations_('Zoom In Left', 'flextension')
}, {
  value: 'zoom-in-right',
  label: animations_('Zoom In Right', 'flextension')
}, {
  value: 'stretch-in-left',
  label: animations_('Stretch In Left', 'flextension')
}, {
  value: 'stretch-in-right',
  label: animations_('Stretch In Right', 'flextension')
}, {
  value: 'rotate-in-up-left',
  label: animations_('Rotate In Up Left', 'flextension')
}, {
  value: 'rotate-in-up-right',
  label: animations_('Rotate In Up Right', 'flextension')
}, {
  value: 'flip-in-from-top',
  label: animations_('Flip In from Top', 'flextension')
}, {
  value: 'flip-in-from-bottom',
  label: animations_('Flip In from Bottom', 'flextension')
}, {
  value: 'slide-back',
  label: animations_('Slide Back', 'flextension')
}, {
  value: 'fly-in',
  label: animations_('Fly In', 'flextension')
}, {
  value: 'reveal',
  label: animations_('Reveal', 'flextension')
}, {
  value: 'rotate',
  label: animations_('Infinite Rotate', 'flextension')
}, {
  value: 'swing',
  label: animations_('Infinite Swing', 'flextension')
}], flextensionAnimations || []);
/* harmony default export */ var animations = (ANIMATIONS);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/utils.js
/**
 * WordPress dependencies
 */
const {
  __: extensions_utils_
} = wp.i18n;
const SPACING_OPTIONS = {
  margin: extensions_utils_('Margin', 'flextension'),
  padding: extensions_utils_('Padding', 'flextension')
};

/**
 * Returns whether the block supports extensions.
 *
 * @param {string} name block name
 *
 * @return {boolean} Whethers the block supports extensions.
 */
function hasExtensionSupport(name) {
  const {
    flextensionEditor
  } = window;
  const blocks = flextensionEditor?.blocks || ['core/archives', 'core/audio', 'core/button', 'core/buttons', 'core/calendar', 'core/categories', 'core/code', 'core/column', 'core/columns', 'core/comments', 'core/cover', 'core/embed', 'core/file', 'core/gallery', 'core/group', 'core/heading', 'core/image', 'core/latest-comments', 'core/latest-posts', 'core/list-item', 'core/list', 'core/media-text', 'core/navigation', 'core/page-list', 'core/paragraph', 'core/pullquote', 'core/quote', 'core/read-more', 'core/rss', 'core/search', 'core/separator', 'core/social-link', 'core/social-links', 'core/spacer', 'core/table-of-contents', 'core/table', 'core/tag-cloud', 'core/text-columns', 'core/video'];
  return name && blocks.includes(name);
}

/**
 * Capitalizes text.
 *
 * @param {string} text Text to capitalize.
 * @return {string} Capitalized text.
 */
function capitalize(text) {
  if (typeof text !== 'string') {
    return '';
  }
  return text.charAt(0).toUpperCase() + text.slice(1);
}

/**
 * Returns a responsive suffix.
 *
 * @param {string} breakpoint Breakpoint name.
 *
 * @return {string} Responsive suffix.
 */
function getResponsiveSuffix(breakpoint) {
  let suffix = '';
  if (breakpoint && breakpoint !== 'desktop') {
    suffix = breakpoint;
  }
  return suffix;
}

/**
 * Returns attribute name with responsive suffix.
 *
 * @param {string} name       Attribute name.
 * @param {string} breakpoint Breakpoint name.
 *
 * @return {string} Attribute name.
 */
function getAttributeName(name, breakpoint) {
  const suffix = getResponsiveSuffix(breakpoint);
  return `flext${capitalize(name)}${capitalize(suffix)}`;
}
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/animation/index.js


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
  __: animation_
} = wp.i18n;
const {
  addFilter
} = wp.hooks;
const {
  createHigherOrderComponent
} = wp.compose;
const {
  Component: animation_Component,
  Fragment: animation_Fragment
} = wp.element;
const {
  InspectorControls
} = wp.blockEditor;
const {
  PanelBody,
  RangeControl: animation_RangeControl,
  SelectControl: animation_SelectControl,
  ToggleControl
} = wp.components;
const {
  hasBlockSupport
} = wp.blocks;

/**
 * Adds attributes for the Animation feature.
 *
 * @param {Object} settings The block settings.
 * @param {string} name     The block name.
 * @return {Object} The new block settings.
 */
function addAttributes(settings, name) {
  let supports = hasBlockSupport(settings, 'flextensionAnimation', false);

  // Add support to core blocks.
  if (!supports && hasExtensionSupport(name)) {
    settings.supports = {
      ...settings.supports,
      flextensionAnimation: true
    };
    supports = true;
  }
  if (supports) {
    if (!settings.attributes) {
      settings.attributes = {};
    }
    settings.attributes = Object.assign(settings.attributes, {
      flextAnimation: {
        type: 'string'
      },
      flextAnimationDelay: {
        type: 'number',
        default: 0
      },
      flextAnimationOnce: {
        type: 'boolean',
        default: true
      }
    });
  }
  return settings;
}
addFilter('blocks.registerBlockType', 'flextension/animation/add-attributes', addAttributes);

/**
 * Overrides the default edit UI to include a new block inspector control for
 * assigning the custom spacings if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Wrapped component.
 */
const addControls = createHigherOrderComponent(BlockEdit => {
  return props => {
    if (!hasBlockSupport(props.name, 'flextensionAnimation', false)) {
      return (0,react.createElement)(BlockEdit, props);
    }
    const {
      attributes,
      setAttributes
    } = props;
    const {
      flextAnimation = '',
      flextAnimationDelay = 0,
      flextAnimationOnce = true
    } = attributes;
    return (0,react.createElement)(animation_Fragment, null, (0,react.createElement)(BlockEdit, props), (0,react.createElement)(InspectorControls, null, (0,react.createElement)(PanelBody, {
      title: animation_('Animation settings', 'flextension')
    }, (0,react.createElement)(animation_SelectControl, {
      label: animation_('Reveal', 'flextension'),
      options: animations,
      value: flextAnimation,
      onChange: value => setAttributes({
        flextAnimation: value
      })
    }), flextAnimation && (0,react.createElement)(animation_RangeControl, {
      label: animation_('Delay', 'flextension'),
      help: animation_('Animation delay in seconds.', 'flextension'),
      value: flextAnimationDelay,
      onChange: value => setAttributes({
        flextAnimationDelay: value
      }),
      marks: [{
        value: 0,
        label: '0'
      }, {
        value: 0.25,
        label: '0.25'
      }, {
        value: 0.5,
        label: '0.5'
      }, {
        value: 0.75,
        label: '0.75'
      }, {
        value: 1,
        label: '1'
      }, {
        value: 1.25,
        label: '1.25'
      }, {
        value: 1.5,
        label: '1.5'
      }, {
        value: 1.75,
        label: '1.75'
      }, {
        value: 2,
        label: '2'
      }],
      withInputField: false,
      min: 0,
      max: 2,
      step: 0.125,
      required: true
    }), flextAnimation && (0,react.createElement)(ToggleControl, {
      label: animation_('Trigger once', 'flextension'),
      help: animation_('Plays an animation only once.', 'flextension'),
      checked: flextAnimationOnce,
      onChange: value => setAttributes({
        flextAnimationOnce: value
      })
    }))));
  };
}, 'addControls');
addFilter('editor.BlockEdit', 'flextension/animation/add-controls', addControls);

/**
 * Overrides the default edit UI to include a new block edit classes for
 * assigning the animation if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const addEditClasses = createHigherOrderComponent(BlockListBlock => {
  return props => {
    if (!hasBlockSupport(props.name, 'flextensionAnimation', false)) {
      return (0,react.createElement)(BlockListBlock, props);
    }
    const {
      flextAnimation = '',
      flextAnimationDelay = 0
    } = props.attributes;
    return (0,react.createElement)(BlockListBlock, _extends({}, props, {
      className: classnames_default()(props.className, {
        [`flext-animation-${flextAnimation}`]: flextAnimation,
        [`flext-animation-delay-${flextAnimationDelay * 1000}`]: flextAnimation && flextAnimationDelay > 0,
        'flext-animated': flextAnimation
      })
    }));
  };
}, 'addEditClasses');
addFilter('editor.BlockListBlock', 'flextension/animation/add-edit-classes', addEditClasses);

/**
 * Adds animation class names to the block extra props.
 *
 * @param {Object} extraProps Block props.
 * @param {Object} blockType  Blocks object.
 * @param {Object} attributes Blocks attributes.
 * @return {Object} The block extra props.
 */
function addClasses(extraProps, blockType, attributes) {
  if (!hasBlockSupport(blockType.name, 'flextensionAnimation', false)) {
    return extraProps;
  }
  const {
    flextAnimation,
    flextAnimationDelay,
    flextAnimationOnce
  } = attributes;
  if (flextAnimation) {
    extraProps.className = classnames_default()(extraProps.className, {
      'flext-has-animation': flextAnimation,
      [`flext-animation-${flextAnimation}`]: flextAnimation,
      [`flext-animation-delay-${flextAnimationDelay * 1000}`]: flextAnimation && flextAnimationDelay > 0,
      'flext-animation-once': flextAnimation && flextAnimationOnce === true
    });
  }
  return extraProps;
}
addFilter('blocks.getSaveContent.extraProps', 'flextension/animation/add-classes', addClasses);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/spacing/index.js

/**
 * Internal dependencies
 */





/**
 * WordPress dependencies
 */
const {
  __: spacing_
} = wp.i18n;
const {
  addFilter: spacing_addFilter
} = wp.hooks;
const {
  Component: spacing_Component,
  Fragment: spacing_Fragment
} = wp.element;
const {
  PanelBody: spacing_PanelBody
} = wp.components;
const {
  createHigherOrderComponent: spacing_createHigherOrderComponent
} = wp.compose;
const {
  InspectorControls: spacing_InspectorControls
} = wp.blockEditor;
const {
  hasBlockSupport: spacing_hasBlockSupport
} = wp.blocks;

/**
 * Adds spacing attributes to the block.
 *
 * @param {Object} settings Block settings.
 * @param {string} name     Block name.
 * @return {Object} Filtered block settings.
 */
function spacing_addAttributes(settings, name) {
  let supports = spacing_hasBlockSupport(settings, 'flextensionSpacing', false);

  // Add support to core blocks.
  if (!supports && hasExtensionSupport(name)) {
    settings.supports = {
      ...settings.supports,
      flextensionSpacing: true
    };
    supports = true;
  }
  if (supports) {
    if (!settings.attributes) {
      settings.attributes = {};
    }

    // Responsive attributes.
    Object.keys(breakpoints).forEach(breakpoint => {
      // Spacing Options.
      Object.keys(SPACING_OPTIONS).forEach(spacing => {
        if (!settings.attributes[getAttributeName(spacing, breakpoint)]) {
          settings.attributes[getAttributeName(spacing, breakpoint)] = {
            type: 'object'
          };
        }
      });
    });
  }
  return settings;
}
spacing_addFilter('blocks.registerBlockType', 'flextension/spacing/add-attributes', spacing_addAttributes);

/**
 * Overrides the default edit UI to include a new block inspector control for
 * assigning the custom spacing settings if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const spacing_addControls = spacing_createHigherOrderComponent(BlockEdit => {
  return props => {
    if (!spacing_hasBlockSupport(props.name, 'flextensionSpacing', false)) {
      return (0,react.createElement)(BlockEdit, props);
    }
    const {
      attributes,
      setAttributes
    } = props;

    /**
     * Updates spacing values and custom CSS attributes.
     *
     * @param {string} name   Spacing attribute name.
     * @param {Object} values Spacing values.
     */
    const handleOnChange = (name, values) => {
      setAttributes({
        [name]: values
      });
    };
    return (0,react.createElement)(spacing_Fragment, null, (0,react.createElement)(BlockEdit, props), (0,react.createElement)(spacing_InspectorControls, null, (0,react.createElement)(spacing_PanelBody, {
      title: spacing_('Spacing settings', 'flextension'),
      initialOpen: false
    }, (0,react.createElement)(responsive_panel, null, _ref => {
      let {
        breakpoint,
        ResponsiveDropdown
      } = _ref;
      const controls = [];
      Object.keys(SPACING_OPTIONS).forEach(spacing => {
        const name = getAttributeName(spacing, breakpoint);
        controls.push((0,react.createElement)(SpacingControl, {
          key: `spacing-control-${spacing}`,
          label: SPACING_OPTIONS[spacing],
          values: attributes[name],
          onChange: values => handleOnChange(name, values),
          responsiveDropdown: ResponsiveDropdown
        }));
      });
      return controls;
    }))));
  };
}, 'withInspectorControls');
spacing_addFilter('editor.BlockEdit', 'flextension/spacing/add-controls', spacing_addControls);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/visibility/index.js

/**
 * Internal dependencies
 */



/**
 * WordPress dependencies
 */
const {
  __: visibility_,
  sprintf: visibility_sprintf
} = wp.i18n;
const {
  addFilter: visibility_addFilter
} = wp.hooks;
const {
  Component: visibility_Component,
  Fragment: visibility_Fragment
} = wp.element;
const {
  createHigherOrderComponent: visibility_createHigherOrderComponent
} = wp.compose;
const {
  InspectorControls: visibility_InspectorControls
} = wp.blockEditor;
const {
  PanelBody: visibility_PanelBody,
  ToggleControl: visibility_ToggleControl
} = wp.components;
const {
  hasBlockSupport: visibility_hasBlockSupport
} = wp.blocks;

/**
 * Adds visibility attributes to the block.
 *
 * @param {Object} settings Block settings.
 * @param {string} name     Block name.
 * @return {Object} Filtered block settings.
 */
function visibility_addAttributes(settings, name) {
  let supports = visibility_hasBlockSupport(settings, 'flextensionVisibility', false);

  // Add support to core blocks.
  if (!supports && hasExtensionSupport(name)) {
    settings.supports = {
      ...settings.supports,
      flextensionVisibility: true
    };
    supports = true;
  }
  if (supports) {
    if (!settings.attributes) {
      settings.attributes = {};
    }

    // Responsive attributes.
    Object.keys(breakpoints).forEach(breakpoint => {
      if (!settings.attributes[getAttributeName('hidden', breakpoint)]) {
        settings.attributes[getAttributeName('hidden', breakpoint)] = {
          type: 'boolean'
        };
      }
    });
  }
  return settings;
}
visibility_addFilter('blocks.registerBlockType', 'flextension/visibility/add-attributes', visibility_addAttributes);

/**
 * Overrides the default edit UI to include a new block inspector control for
 * assigning the custom spacings if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 * @return {Function} Modified block edit component.
 */
const visibility_addControls = visibility_createHigherOrderComponent(BlockEdit => {
  return props => {
    if (!visibility_hasBlockSupport(props.name, 'flextensionVisibility', false)) {
      return (0,react.createElement)(BlockEdit, props);
    }
    const {
      setAttributes,
      attributes
    } = props;
    return (0,react.createElement)(visibility_Fragment, null, (0,react.createElement)(BlockEdit, props), (0,react.createElement)(visibility_InspectorControls, null, (0,react.createElement)(visibility_PanelBody, {
      title: visibility_('Visibility settings', 'flextension'),
      initialOpen: false
    }, Object.keys(breakpoints).map(breakpoint => {
      const name = getAttributeName('hidden', breakpoint);
      return (0,react.createElement)(visibility_ToggleControl, {
        key: `visibility-${breakpoint}`,
        label: visibility_sprintf(
        // translators: %s: Breakpoint name
        visibility_('Hide on %s', 'flextension'), breakpoints[breakpoint].label),
        checked: !!attributes[name],
        onChange: () => {
          setAttributes({
            [name]: attributes[name] ? undefined : true
          });
        }
      });
    }))));
  };
}, 'addControls');
visibility_addFilter('editor.BlockEdit', 'flextension/visibility/add-controls', visibility_addControls);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/custom-styles/index.js



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
  addFilter: custom_styles_addFilter
} = wp.hooks;
const {
  Component: custom_styles_Component,
  Fragment: custom_styles_Fragment
} = wp.element;
const {
  createHigherOrderComponent: custom_styles_createHigherOrderComponent
} = wp.compose;
const {
  hasBlockSupport: custom_styles_hasBlockSupport
} = wp.blocks;
const STYLE_ATTRIBUTES = ['margin', 'padding', 'hidden'];

/**
 * Returns true if the block defines support for a feature, or false otherwise.
 *
 * @param {(string|Object)} nameOrType Block name or type object.
 *
 * @return {boolean} Whether block supports feature.
 */
function isSupported(nameOrType) {
  return custom_styles_hasBlockSupport(nameOrType, 'flextensionSpacing', false) || custom_styles_hasBlockSupport(nameOrType, 'flextensionVisibility', false);
}

/**
 * Returns whether one of style attributes has been changed.
 *
 * @param {Object} attributes     Block attributes.
 * @param {Object} prevAttributes Previous attributes.
 *
 * @return {boolean} Whether one of style attributes has been changed.
 */
function isStyleAttributesChanged(attributes, prevAttributes) {
  let isChanged = false;
  if (attributes.flextClassName !== prevAttributes.flextClassName) {
    return true;
  }
  STYLE_ATTRIBUTES.forEach(name => {
    if (attributes[getAttributeName(name)] !== prevAttributes[getAttributeName(name)]) {
      isChanged = true;
    }
    if (isChanged !== true) {
      Object.keys(breakpoints).forEach(breakpoint => {
        if (attributes[getAttributeName(name, breakpoint)] !== prevAttributes[getAttributeName(name, breakpoint)]) {
          isChanged = true;
        }
      });
    }
  });
  return isChanged;
}

/**
 * Returns CSS styles for custom spacing.
 *
 * @param {Object} attributes Block attributes.
 * @return {string} Custom CSS styles for custom spacing.
 */
function getCustomStyles(attributes) {
  const {
    flextClassName
  } = attributes;
  if (!flextClassName) {
    return '';
  }
  const blockSelector = `.${flextClassName}`;
  const customStyles = [];
  let prevWidth = 0;
  Object.keys(breakpoints).forEach(breakpoint => {
    let maxWidth = 0;
    const suffix = getResponsiveSuffix(breakpoint);
    if (suffix) {
      maxWidth = prevWidth - 1;
    }
    prevWidth = breakpoints[breakpoint].width;
    const cssProps = [];
    Object.keys(SPACING_OPTIONS).forEach(spacing => {
      const values = attributes[getAttributeName(spacing, breakpoint)];
      if (typeof values === 'object') {
        Object.keys(values).forEach(side => {
          if (!isNaN(parseInt(values[side], 10))) {
            cssProps.push(`${spacing}-${side}: ${values[side]} !important;`);
          }
        });
      }
    });
    const hide = attributes[getAttributeName('hidden', breakpoint)];
    if (hide === true) {
      cssProps.push('-webkit-filter: opacity(0.4) saturate(0);');
      cssProps.push('filter: opacity(0.4) saturate(0);');
    }
    if (cssProps.length > 0) {
      const mediaRules = [];
      if (parseInt(breakpoints[breakpoint].width, 10) > 0) {
        mediaRules.push(`(min-width: ${breakpoints[breakpoint].width}px)`);
      }
      if (maxWidth > 0) {
        mediaRules.push(`(max-width: ${maxWidth}px)`);
      }
      const cssStyles = `@media ${mediaRules.join(' and ')} { ${blockSelector} { ${cssProps.join(' ')} } }`;
      customStyles.push(cssStyles);
    }
  });
  return customStyles.reverse().join(' ');
}

/**
 * Returns whether the value is valid.
 *
 * @param {*} value The value to validate.
 *
 * @return {boolean} Whether the value is valid.
 */
function isValid(value) {
  if (typeof value === 'undefined') {
    return false;
  }
  if (typeof value === 'object' && isEmpty_default()(value)) {
    return false;
  }
  return value;
}

/**
 * Returns whether the attributes contain custom styles.
 *
 * @param {Object} attributes The block attributes.
 * @return {boolean} Whether the attributes contain custom styles.
 */
function hasCustomStyleAttributes(attributes) {
  let hasStyleAttributes = false;
  STYLE_ATTRIBUTES.forEach(name => {
    if (isValid(attributes[getAttributeName(name)])) {
      hasStyleAttributes = true;
    }
    if (!hasStyleAttributes) {
      Object.keys(breakpoints).forEach(breakpoint => {
        if (isValid(attributes[getAttributeName(name, breakpoint)])) {
          hasStyleAttributes = true;
        }
      });
    }
  });
  return hasStyleAttributes;
}

/**
 * Extends block attributes with unique class name.
 *
 * @param {Object} settings Original block settings.
 *
 * @return {Object} New block settings.
 */
function custom_styles_addAttributes(settings) {
  if (isSupported(settings)) {
    if (!settings.attributes) {
      settings.attributes = {};
    }
    if (!settings.attributes.flextClassName) {
      settings.attributes.flextClassName = {
        type: 'string'
      };
    }
  }
  return settings;
}
custom_styles_addFilter('blocks.registerBlockType', 'flextension/custom-styles/add-attributes', custom_styles_addAttributes);

/**
 * Overrides the default edit UI to include a new block inspector control for
 * assigning the unique class if needed.
 *
 * @param {(Function|Component)} BlockEdit Original component.
 *
 * @return {Component} Wrapped component.
 */
const custom_styles_addControls = custom_styles_createHigherOrderComponent(BlockEdit => {
  return class CustomStylesWrapper extends custom_styles_Component {
    constructor() {
      super(...arguments);
      if (!isSupported(this.props.name)) {
        return;
      }
      this.getAllBlocks = this.getAllBlocks.bind(this);
      this.maybeCreateUniqueClass = this.maybeCreateUniqueClass.bind(this);
      this.state = {
        customStyles: ''
      };
    }
    componentDidMount() {
      if (!isSupported(this.props.name)) {
        return;
      }
      this.maybeCreateUniqueClass(true);
      const {
        attributes
      } = this.props;
      this.setState({
        customStyles: getCustomStyles(attributes)
      });
    }
    componentDidUpdate(prevProps) {
      if (!isSupported(this.props.name)) {
        return;
      }
      const {
        attributes
      } = this.props;
      if (isStyleAttributesChanged(attributes, prevProps.attributes)) {
        this.maybeCreateUniqueClass();
        this.setState({
          customStyles: getCustomStyles(attributes)
        });
      }
    }

    /**
     * Returns array of the blocks including inner blocks in the page.
     *
     * @param {Array} blocks An array list of all blocks.
     * @return {Array} An array list of the blocks in the page.
     */
    getAllBlocks() {
      let blocks = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      let result = [];
      if (!blocks) {
        const {
          select
        } = wp.data;
        blocks = select('core/block-editor').getBlocks();
      }
      if (!blocks) {
        return result;
      }
      blocks.forEach(data => {
        result.push(data);
        if (data.innerBlocks && data.innerBlocks.length) {
          result = [...result, ...this.getAllBlocks(data.innerBlocks)];
        }
      });
      return result;
    }

    /**
     * Generates a unique class name and custom CSS for the block.
     *
     * @param {boolean} checkForDuplication Whether to check for duplication.
     */
    maybeCreateUniqueClass(checkForDuplication) {
      const {
        name,
        attributes,
        setAttributes,
        clientId
      } = this.props;
      if (!isSupported(name)) {
        return;
      }
      let {
        flextClassName
      } = attributes;

      // Prevent unique ID duplication after block duplicated.
      if (flextClassName && checkForDuplication) {
        const allBlocks = this.getAllBlocks();
        allBlocks.forEach(data => {
          if (data.clientId !== clientId && data.attributes && data.attributes.flextClassName && data.attributes.flextClassName === flextClassName) {
            flextClassName = '';
          }
        });
      }
      if (!flextClassName) {
        flextClassName = 'flext-block-' + new Date().getTime();
      }
      if (!hasCustomStyleAttributes(attributes)) {
        flextClassName = undefined;
      }
      setAttributes({
        flextClassName
      });
    }
    render() {
      if (!isSupported(this.props.name)) {
        return (0,react.createElement)(BlockEdit, this.props);
      }
      const {
        customStyles
      } = this.state;
      return (0,react.createElement)(custom_styles_Fragment, null, (0,react.createElement)(BlockEdit, this.props), customStyles && (0,react.createElement)("style", null, customStyles));
    }
  };
}, 'addControls');
custom_styles_addFilter('editor.BlockEdit', 'flextension/custom-styles/add-controls', custom_styles_addControls);

/**
 * Modifies the block’s wrapper component containing the block’s edit
 * component and all toolbars. It receives the original BlockListBlock
 * component and returns a new wrapped component.
 */
const custom_styles_addEditClasses = custom_styles_createHigherOrderComponent(BlockListBlock => {
  return props => {
    if (props.name && isSupported(props.name) && props.attributes.flextClassName) {
      return (0,react.createElement)(BlockListBlock, _extends({}, props, {
        className: props.attributes.flextClassName
      }));
    }
    return (0,react.createElement)(BlockListBlock, props);
  };
}, 'addEditClasses');
custom_styles_addFilter('editor.BlockListBlock', 'flextension/custom-styles/add-edit-classes', custom_styles_addEditClasses);

/**
 * Overrides props assigned to save component to inject custom styles.
 * This is only applied if the block's save result is an
 * element and not a markup string.
 *
 * @param {Object} extraProps Additional props applied to save element.
 * @param {Object} blockType  Block type.
 * @param {Object} attributes Current block attributes.
 *
 * @return {Object} Filtered props applied to save element.
 */
function custom_styles_addClasses(extraProps, blockType, attributes) {
  if (blockType.name && isSupported(blockType.name) && attributes.flextClassName) {
    if (hasCustomStyleAttributes(attributes)) {
      extraProps.className = classnames_default()(extraProps.className, attributes.flextClassName);
    }
  }
  return extraProps;
}
custom_styles_addFilter('blocks.getSaveContent.extraProps', 'flextension/custom-styles/add-classes', custom_styles_addClasses);
;// CONCATENATED MODULE: ./src/extensions/editor/js/inc/extensions/index.js


/**
 * Internal dependencies
 */





;// CONCATENATED MODULE: ./src/extensions/editor/js/editor.js
/**
 * Flextension Editor
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