/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 102);
/******/ })
/************************************************************************/
/******/ ({

/***/ 102:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(60);


/***/ }),

/***/ 40:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
var bodyElement = exports.bodyElement = function bodyElement() {
  return document.getElementsByTagName('body')[0];
};

var width = exports.width = function width() {
  return (window.innerWidth || document.documentElement.clientWidth || bodyElement().clientWidth) - 90;
};
var height = exports.height = function height() {
  return (window.innerHeight || document.documentElement.clientHeight || bodyElement().clientHeight) - 90;
};

/***/ }),

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _helpers = __webpack_require__(40);

tinymce.PluginManager.requireLangPack('cripfilesys', 'en_GB,lv,ru');

tinymce.PluginManager.add('cripfilesys', function (editor) {
  function OpenCripFilesystemManager() {
    var file = editor.settings.external_filemanager_path + '?target=tinymce';
    var title = 'Crip Filesystem Manager';
    editor.focus(true);

    editor.windowManager.open({
      title: title,
      file: file,
      width: (0, _helpers.width)(),
      height: (0, _helpers.height)()
    }, {
      setUrl: function setUrl(selectedUrl) {
        editor.insertContent(editor.convertURL(selectedUrl));
      }
    });
  }

  editor.addButton('cripfilesys-btn', {
    icon: 'browse',
    tooltip: 'Insert file',
    shortcut: 'Ctrl+E',
    onclick: OpenCripFilesystemManager
  });

  editor.addShortcut('Ctrl+E', '', OpenCripFilesystemManager);

  editor.addMenuItem('cripfilesys-menu-btn', {
    icon: 'browse',
    text: 'Insert file',
    shortcut: 'Ctrl+E',
    onclick: OpenCripFilesystemManager,
    context: 'insert'
  });
});

/***/ })

/******/ });