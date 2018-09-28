'use strict'

// import $ from 'jquery'
// import Masonry from 'masonry-layout';

// // Must use jQueryBridget to glue it to jQuery if you want to use it with jQuery.
// // http://masonry.desandro.com/extras.html#webpack
// // http://stackoverflow.com/questions/39951167/masonry-does-not-work-with-jquery-in-es6
// // import jQueryBridget from 'jquery-bridget';

// // Make Masonry a jQuery plugin.
// // jQueryBridget('masonry', Masonry, $);

// class Grid {
//    loadMasonry() {
//         // If .grid doesn't exist, don't call masonry.
//         // http://stackoverflow.com/questions/5629684/how-to-check-if-element-exists-in-the-visible-dom
//         var x = document.getElementsByClassName("grid").length;
//         console.log('.grid length: ' + x);

//         if (x == 0) {
//             return;
//         }

//         window.addEventListener("load", () => {
//             // // Now you can use $().masonry()
//             // $('.grid').masonry({
//             //     // options
//             //     itemSelector: '.grid-item',
//             //     percentPosition: true,
//             //     // gutter: 10,
//             //     // columnWidth: 200
//             // });

//             // Plain vanilla js - better!
//             var msnry = new Masonry( '.grid', {
//                 // options
//                 itemSelector: '.grid-item',
//                 percentPosition: true,
//                 // gutter: 10,
//                 // columnWidth: 200
//             });
//         });

//         // Plain vanilla js - better!
//         var msnry = new Masonry( '.grid', {
//             // options
//             itemSelector: '.grid-item',
//             percentPosition: true,
//             // gutter: 10,
//             // columnWidth: 200
//         });
//     }
// }

// export { Grid as default }

// To fix the issue with vue.
// https://github.com/shershen08/vue-masonry
import Masonry from 'masonry-layout'
import ImageLoaded from 'imagesloaded'

const attributesMap = {
  'column-width': 'columnWidth',
  'transition-duration': 'transitionDuration',
  'item-selector': 'itemSelector',
  'origin-left': 'originLeft',
  'origin-top': 'originTop',
  'fit-width': 'fitWidth',
  'stamp': 'stamp',
  'gutter': 'gutter',
  'percent-position': 'percentPosition',
  'horizontal-order': 'horizontalOrder',
  'stagger': 'stagger'
}
const EVENT_ADD = 'vuemasonry.itemAdded'
const EVENT_REMOVE = 'vuemasonry.itemRemoved'
const EVENT_IMAGE_LOADED = 'vuemasonry.imageLoaded'
const EVENT_DESTROY = 'vuemasonry.destroy'

const stringToBool = function (val) { return (val + '').toLowerCase() === 'true' }

const numberOrSelector = function (val) { return isNaN(val) ? val : parseInt(val) }

const collectOptions = function (attrs) {
  var res = {}
  var attributesArray = Array.prototype.slice.call(attrs)
  attributesArray.forEach(function (attr) {
    if (Object.keys(attributesMap).indexOf(attr.name) > -1) {
      if (attr.name.indexOf('origin') > -1) {
        res[attributesMap[attr.name]] = stringToBool(attr.value)
      } else if (attr.name === 'column-width' || attr.name === 'gutter') {
        res[attributesMap[attr.name]] = numberOrSelector(attr.value)
      } else {
        res[attributesMap[attr.name]] = attr.value
      }
    }
  })
  return res
}

export const VueMasonryPlugin = function () {}

VueMasonryPlugin.install = function (Vue, options) {
  const Events = new Vue({})

  Vue.directive('masonry', {
    props: ['transitionDuration', ' itemSelector'],

    inserted: function (el, nodeObj) {
      if (!Masonry) {
        throw new Error('Masonry plugin is not defined. Please check it\'s connected and parsed correctly.')
      }
      const masonry = new Masonry(el, collectOptions(el.attributes))
      const masonryDraw = function () {
        masonry.reloadItems()
        masonry.layout()
      }
      Vue.nextTick(function () {
        masonryDraw()
      })

      const masonryRedrawHandler = function (eventData) {
        masonryDraw()
      }

      const masonryDestroyHandler = function (eventData) {
        Events.$off(EVENT_ADD, masonryRedrawHandler)
        Events.$off(EVENT_REMOVE, masonryRedrawHandler)
        Events.$off(EVENT_IMAGE_LOADED, masonryRedrawHandler)
        Events.$off(EVENT_DESTROY, masonryDestroyHandler)
        masonry.destroy()
      }

      Events.$on(EVENT_ADD, masonryRedrawHandler)
      Events.$on(EVENT_REMOVE, masonryRedrawHandler)
      Events.$on(EVENT_IMAGE_LOADED, masonryRedrawHandler)
      Events.$on(EVENT_DESTROY, masonryDestroyHandler)
    },
    unbind: function (el, nodeObj) {
      Events.$emit(EVENT_DESTROY)
    }
  })

  Vue.directive('masonryTile', {

    inserted: function (el) {
      Events.$emit(EVENT_ADD, {
        'element': el
      })
      // eslint-disable-next-line
      new ImageLoaded(el, function () {
        Events.$emit(EVENT_IMAGE_LOADED, {
          'element': el
        })
      })
    },
    unbind: function (el) {
      Events.$emit(EVENT_REMOVE, {
        'element': el
      })
    }
  })

  Vue.prototype.$redrawVueMasonry = function () {
    Events.$emit(EVENT_ADD)
  }
}
