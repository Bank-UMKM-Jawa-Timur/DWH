/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {

// $(".layout-sidebar").perfectScrollbar();

var sidebar = $(".layout-sidebar");
var form = $(".layout-form");

// tab function
$(".tab-wrapping .tab-button").click(function (e) {
  e.preventDefault();
  var tabId = $(this).data("tab");
  $(".tab-content").addClass("hidden");
  $(".tab-wrapping .tab-button").removeClass("bg-white border-b border-theme-primary");
  $(".tab-wrapping .tab-button").removeClass("text-gray-400");
  $(".tab-wrapping .tab-button").removeClass("text-theme-primary");
  $(".tab-wrapping .tab-button").addClass("text-gray-400");
  $(".tab-wrapping .tab-button").addClass("border-b-2");
  $(this).addClass("bg-white border-b-2 border-theme-primary");
  $(this).addClass("text-theme-primary");
  if (tabId) {
    $(this).removeClass("text-gray-400");
    $(this).removeClass("bg-gray-100");
  }
  $("#" + tabId).removeClass("hidden");
});
$("ul.flex a:first").trigger("click");
$(".dropdown-toggle").click(function () {
  $(this).nextAll(".dropdown-menu-link:first").toggleClass("hidden");
  $(this).children(".dropdown-arrow").toggleClass("rotate-180");
  $(".dropdown-toggle").toggleClass("active-hover");
});

// notification toggle
$(".toggle-notification").click(function () {
  $(".notification-list").toggleClass("hidden");
});
$(".toggle-notification").blur(function () {
  $(".notification-list").addClass("hidden");
});
$(".dropdown-account-toggle").click(function () {
  $(".dropdown-account").toggleClass("hidden");
});
$(".dropdown-account-toggle").blur(function () {
  $(".dropdown-account").addClass("hidden");
});
$("#form-toggle").click(function () {
  form.toggleClass("layout-form-collapse");
  toggleForm();
});
$("#form-close").click(function () {
  form.toggleClass("layout-form-collapse");
  toggleForm();
});
$(".toggle-sidebar").click(function () {
  // sidebar.toggleClass("-left-96");
  sidebar.toggleClass("layout-collapse");
  toggleSidebar();
  // $(".layout-overlay").toggleClass("hidden");
});

function toggleForm() {
  if (form.hasClass("layout-form-collapse")) {
    form.removeClass("hidden");
    $(".layout-overlay-form").removeClass("hidden");
  } else {
    form.addClass("hidden");
    $(".layout-overlay-form").addClass("hidden");
  }
}
function toggleSidebar() {
  if (sidebar.hasClass("layout-collapse")) {
    sidebar.removeClass("hidden");
    $(".layout-overlay").removeClass("hidden");
  } else {
    sidebar.addClass("hidden");
    $(".layout-overlay").addClass("hidden");
  }
}

// overlay action
$(".layout-overlay").click(function () {
  sidebar.toggleClass("layout-collapse");
  toggleSidebar();
});
$(".layout-overlay-form").click(function () {
  form.addClass("hidden");
  $(".layout-overlay-form").addClass("hidden");
  // toggleForm();
});

$(".layout-overlay-edit-form").click(function () {
  element.toggleClass("layout-form-collapse");
});

// toggle form edit
$(".toggle-form-edit").on("click", function () {
  var formId = $(this).data("form-id");
  $("#" + formId).removeClass("hidden");
  $(".layout-overlay-edit-form").removeClass("hidden");
});
$(".close-form-edit").on("click", function () {
  var formId = $(this).data("form-id");
  $("#" + formId).addClass("hidden");
  $(".layout-overlay-edit-form").addClass("hidden");
});
$(".toggle-modal").on("click", function () {
  var targetId = $(this).data("target-id");
  $("#" + targetId).removeClass("hidden");
  form.addClass("layout-form-collapse");
  if (targetId.slice(0, 5) !== "modal") {
    $(".layout-overlay-form").removeClass("hidden");
  }
});
$("[data-dismiss-id]").on("click", function () {
  var dismissId = $(this).data("dismiss-id");
  $("#" + dismissId).addClass("hidden");
  if (dismissId.slice(0, 5) !== "modal") {
    $(".layout-overlay-form").addClass("hidden");
  }
});
// chart donut
var options = {
  series: [10, 10],
  colors: ["#122C4F", "#DC3545"],
  chart: {
    type: "donut",
    width: "100%"
  },
  legend: {
    show: false
  },
  dataLabels: {
    enabled: false
  }
};
var donut = new ApexCharts(document.querySelector(".chart"), options);
donut.render();

// line chart
var lineOptions = {
  series: [{
    name: "Data Set",
    data: [10]
  }],
  chart: {
    type: "bar",
    height: 350,
    stacked: true
  },
  colors: ["#DC3545"],
  responsive: [{
    breakpoint: 480,
    options: {
      legend: {
        position: "bottom",
        offsetX: -10,
        offsetY: 0
      }
    }
  }],
  xaxis: {
    categories: ["Data Set"]
  },
  fill: {
    opacity: 1
  },
  legend: {
    position: "top",
    offsetX: 0,
    offsetY: 50
  }
};
var lineChart = document.querySelector(".line-chart");
var chart = new ApexCharts(lineChart, lineOptions);
chart.render();

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/css/app.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;