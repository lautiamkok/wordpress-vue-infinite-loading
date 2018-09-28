'use strict'

// Import node modules.
import 'babel-polyfill'
import DocReady from 'es6-docready'
import $ from 'jquery'
import 'jquery-ui-bundle'
import Foundation from 'foundation-sites'
import Vue from 'vue/dist/vue.js'
import autosize from 'autosize'
import AOS from 'aos'
import axios from 'axios'

// Import local modules.
import { getZFcurrentMediaQuery } from './utils'
import Sample from './modules/sample/index'
import Sample2 from './modules/sample2/index'
import affix from './modules/affix/index'
import aside from './modules/aside/index'
import flexSearch from './modules/flex-search/index'
import scrollUp from './modules/scrollup/index'
import localSearch from './modules/local-search/index'
import swiper from './modules/swiper/index'
import affixHeading from './modules/affix-heading/index'

// import Grid from './modules/grid/index'
// import {VueMasonryPlugin} from 'vue-masonry'
import {VueMasonryPlugin} from './modules/grid/index'
Vue.use(VueMasonryPlugin)

// Must wait until DOM is ready before initiating the modules.
DocReady(async () => {
  console.log("DOM is ready. Let's party")

  // Async sample.
  async function example3() {
    return 'example 3'
  }
  console.log(await example3())

  // Render template with Vue.
  // Get json of posts.
  var element = document.getElementById('api-posts')
  if (element !== null) {
    var posts = new Vue({
      el: '#api-posts',
      data: {
        items: [],
        counter: 1,
        loading: true,
        bottom: false,
        filter: '',
        searchTarget: null,
        finish: false,
        reset: false,
        total: 0,
      },
      created() {
        // https://scotch.io/tutorials/simple-asynchronous-infinite-scroll-with-vue-watchershn
        window.addEventListener('scroll', () => {
          this.bottom = this.bottomVisible()
        })
      },
      mounted() {
        // https://github.com/OnsenUI/OnsenUI/issues/2241
        // window.addEventListener('scroll',this.onscroll)

        // Check if any filter is on.
        var elem = $('#api-posts')
        this.filter = elem.data('filter')

        // Fetch the first badge.
        if (this.filter === 'category') {
          this.fetchCatPosts()
        } else {
          this.fetchPosts()
        }

        AOS.init({
          duration: 1200,
        })
      },
      watch: {
        bottom: function (bottom) {
          if (this.finish === true) {
            return
          }

          if (bottom) {
            this.loading = true
            if (this.filter === 'category') {
              this.fetchCatPosts()
            } else if(this.filter === 'source') {
              this.fetchSourcePosts()
            } else if(this.filter === 'year') {
              this.fetchYearPosts()
            } else if(this.filter === 'search') {
              this.searchPosts(this.searchTarget)
            } else {
              this.fetchPosts()
            }
          }
        },
      },
      methods: {
        traceUrl: async function (event) {
          // Get the object.
          var target = $(event.target)

          // Get href from a tag and redirect.
          var link = $(target).find('a')
          var url = link.attr("href")
          window.location.href = url
        },
        resetSearch: async function (event) {
          // Reset.
          this.items = []
          this.counter = 1
          this.total = 0
          this.bottom = false
          this.finish = false
          this.filter = ''

          // Get the object.
          var target = $(event.target)
          this.searchTarget = target

          // Fetch the first badge.
          this.fetchPosts()
        },
        searchByKeywords: async function (event) {
          // Reset.
          this.items = []
          this.counter = 1
          this.total = 0
          this.bottom = false
          this.finish = false
          this.filter = 'search'

          // Get the object.
          var target = $(event.target)
          this.searchTarget = target

          // Fetch the first badge.
          this.searchPosts(target)
        },
        filterByCat: async function (event) {
          // Reset.
          this.items = []
          this.counter = 1
          this.total = 0
          this.bottom = false
          this.finish = false
          this.filter = 'category'

          // Get the data and set.
          var data = event.target.getAttribute('data-cat')
          var buttonLoad = $('#button-load-catposts')
          buttonLoad.data('cat', data)
          buttonLoad.removeClass('disabled')

          var buttonLoad = $('.button-load')
          buttonLoad.removeClass('disabled')

          // Fetch the first badge.
          this.fetchCatPosts()
        },
        filterBySource: async function (event) {
          // Reset.
          this.items = []
          this.counter = 1
          this.total = 0
          this.bottom = false
          this.finish = false
          this.filter = 'source'

          // Get the data and set.
          var data = event.target.getAttribute('data-cat')
          var buttonLoad = $('#button-load-sourceposts')
          buttonLoad.data('cat', data)
          buttonLoad.removeClass('disabled')

          var buttonLoad = $('.button-load')
          buttonLoad.removeClass('disabled')

          // Fetch the first badge.
          this.fetchSourcePosts()
        },
        filterByYear: async function (event) {
          // Reset.
          this.items = []
          this.counter = 1
          this.total = 0
          this.bottom = false
          this.finish = false
          this.filter = 'year'

          // Get the data and set.
          var data = event.target.getAttribute('data-year')
          var buttonLoad = $('#button-load-yearposts')
          buttonLoad.data('year', data)
          buttonLoad.removeClass('disabled')

          var buttonLoad = $('.button-load')
          buttonLoad.removeClass('disabled')

          // Fetch the first badge.
          this.fetchYearPosts()
        },
        fetchPosts: async function (event) {
          var url = null

          // Vanilla JS
          // var buttonLoad = document.getElementById('button-load')
          // var endpoint = buttonLoad.getAttribute('data-posts-endpoint')

          // jQuery
          var buttonLoad = $('#button-load-posts')
          var endpoint = buttonLoad.data('posts-endpoint')

          // With axion - no callbacks.
          var getData = await axios.get(endpoint + this.counter)
          var data = getData.data
          this.counter += 1

          // Disable the button if no more data.
          // https://www.w3schools.com/jsref/prop_pushbutton_disabled.asp
          if (data == null) {
            // Does not work for some reasons.
            // buttonLoad.disabled = true
            // Use jQuery instead.
            buttonLoad.addClass('disabled')
            this.finish = true
            this.loading = false
            return
          }

          // `this` inside methods points to the Vue instance
          var self = this
          data.map(function(item) {
            self.items.push(item)

            if (item.total) {
              self.total = item.total
            }
          })

          this.loading = false
          this.reset = false
        },
        fetchCatPosts: async function (event) {
          var buttonLoad = $('#button-load-catposts')
          var endpoint = buttonLoad.data('posts-endpoint')
          var url = endpoint + buttonLoad.data("cat") + '/page/'

          // With axion - no callbacks.
          var getData = await axios.get(url + this.counter)
          var data = getData.data
          this.counter += 1

          // Disable the button if no more data.
          // https://www.w3schools.com/jsref/prop_pushbutton_disabled.asp
          if (data == null) {
            buttonLoad.addClass('disabled')
            this.finish = true
            this.loading = false
            return
          }

          // `this` inside methods points to the Vue instance
          var self = this
          data.map(function(item) {
             self.items.push(item)

             if (item.total) {
              self.total = item.total
            }
          })
          this.loading = false
          this.reset = true
        },
        fetchSourcePosts: async function (event) {
          var buttonLoad = $('#button-load-sourceposts')
          var endpoint = buttonLoad.data('posts-endpoint')
          var url = endpoint + buttonLoad.data("cat") + '/page/'

          // With axion - no callbacks.
          var getData = await axios.get(url + this.counter)
          var data = getData.data
          this.counter += 1

          // Disable the button if no more data.
          // https://www.w3schools.com/jsref/prop_pushbutton_disabled.asp
          if (data == null) {
            buttonLoad.addClass('disabled')
            this.finish = true
            this.loading = false
            return
          }

          // `this` inside methods points to the Vue instance
          var self = this
          data.map(function(item) {
            self.items.push(item)

            if (item.total) {
              self.total = item.total
            }
          })
          this.loading = false
        },
        fetchYearPosts: async function (event) {
          var buttonLoad = $('#button-load-yearposts')
          var endpoint = buttonLoad.data('posts-endpoint')
          var url = endpoint + buttonLoad.data("year") + '/page/'

          // With axion - no callbacks.
          var getData = await axios.get(url + this.counter)
          var data = getData.data
          this.counter += 1

          // Disable the button if no more data.
          // https://www.w3schools.com/jsref/prop_pushbutton_disabled.asp
          if (data == null) {
            buttonLoad.addClass('disabled')
            this.finish = true
            this.loading = false
            return
          }

          // `this` inside methods points to the Vue instance
          var self = this
          data.map(function(item) {
             self.items.push(item)

             if (item.total) {
              self.total = item.total
            }
          })
          this.loading = false
          this.reset = true
        },
        searchPosts: async function (target) {
          var url = null

          // Vanilla JS
          // var buttonLoad = document.getElementById('button-load')
          // var endpoint = buttonLoad.getAttribute('data-posts-endpoint')

          // Set the context.
          var context = target.parents('.container-search')

          // jQuery
          var buttonLoad = $('.button-local-search', context)
          var endpoint = buttonLoad.data('posts-endpoint')
          var input = $('.input-local-search', context)
          var str = input.val()
          str = str.replace(/\s+/g, '+').toLowerCase()
          var url = endpoint + str + '/page/'
          // console.log('keywords: ' + str)

          // With axion - no callbacks.
          var getData = await axios.get(url + this.counter)
          var data = getData.data
          this.counter += 1

          // Popup mobile search.
          var popup = $('.row-search')
          if (!popup.hasClass('hide')) {
            popup.addClass('hide')
          }

          // Disable the button if no more data.
          // https://www.w3schools.com/jsref/prop_pushbutton_disabled.asp
          if (data == null) {
            this.finish = true
            this.loading = false
            return
          }

          // `this` inside methods points to the Vue instance
          var self = this
          data.map(function(item) {
             self.items.push(item)

             if (item.total) {
              self.total = item.total
            }
          })

          this.loading = false
        },
        onscroll: async function (event) {
          this.bottom = this.bottomVisible()
          if (this.bottom) {
            this.fetchPosts()
            console.log('At the bottom');
          }
        },
        bottomVisible() {
          // https://stackoverflow.com/questions/15615552/get-div-height-with-plain-javascript/15615701
          var footerHeight = document.getElementById('footer').clientHeight;
          var scrollY = window.scrollY
          var visible = document.documentElement.clientHeight
          var pageHeight = document.documentElement.scrollHeight - (footerHeight / 2)
          var bottomOfPage = visible + scrollY >= pageHeight

          // console.log("visible: " + visible)
          // console.log("pageHeight: " + pageHeight)
          // console.log("bottomOfPage: " + bottomOfPage)
          return bottomOfPage || pageHeight < visible
        },
      }
    })
  }





  // Initiate foundation.
  // Must do it after Vue has rendered the view.
  $(document).foundation()

  // var g = new Grid()
  // g.loadMasonry()

  // Change the icon the dropdown menu.
  // $element is the dropdown.
  // https://foundation.zurb.com/sites/docs/dropdown-menu.html#js-events
  // https://foundation.zurb.com/building-blocks/blocks/dropdown-animated.html
  // https://foundation.zurb.com/forum/posts/37499-trouble-with-events-on-f6-dropdown-menus
  $('.menu-side.accordion-menu').on('down.zf.accordionMenu', function(event, $element) {
    var parent = $element.parents('li').addClass('is-expanded')
  })
  $('.menu-side.accordion-menu').on('up.zf.accordionMenu', function(event, $element) {
    var parent = $element.parents('li').removeClass('is-expanded')
  })
  // Disable keyboard interaction completely.
  // https://github.com/zurb/foundation-sites/issues/8011
  Foundation.Keyboard.handleKey = function(){};
  // Or specifically:
  // $('li.menu-text').off('keydown');

  // Sample class evocation.
  let s = new Sample()
  console.log(await s.multiply(2)) // return 4

  // Sample class evocation.
  let s3 = new Sample2()
  console.log(await s3.multiply(2)) // return 8

  affix()
  aside()
  flexSearch()
  scrollUp()
  localSearch()
  swiper()
  affixHeading()

  // Create the accordion menu with jQuery.
  // Make sure to hide it on medium screen and up.
  $('.accordion').accordion({
    heightStyle: "content",
    collapsible: true
  })
  // Disable keyboard interaction with accordion.
  // https://stackoverflow.com/questions/19796158/unbind-accordion-keyboard-events
  $('.ui-accordion [role=tab]').unbind('keydown')

  // Trigger click the element inside.
  $('.trigger-url').on('click', function(e) {
    // var context = $(this).parents('.card')
    // $('.button-for-trigger', $context)[0].click()
    // or:
    $(this).find('a')[0].click()
  })

  // Trigger click the submit input.
  $('.trigger-submit').on('click', function(e) {
    var context = $(this).parents('.cell')
    // console.log(context)
    $('input[type=submit]', context).click()
    return false
    // // or:
    // $(this).find('a')[0].click()
  })

  // Change the window location.
  $('.goto-url').on('click', function() {
    var url = $(this).data('url')
    window.location.href = url
  })

  // Disable autocomplete.
  // https://stackoverflow.com/questions/2186290/disable-autocomplete-via-css
  $(':input').on('focus', function () {
    $(this).attr('autocomplete', 'off')
  })

  // // Disable all clicks on .current.
  // $('.current > a').click(function(e){
  //   return false
  // })

  // Detect ZF sceen size on resize.
  window.addEventListener('resize', () => {
    var current = getZFcurrentMediaQuery()
    console.log('Screen size: ' + current)
  })

  // Detect browser visibility.
  // https://stackoverflow.com/questions/10328665/how-to-detect-browser-minimize-and-maximize-state-in-javascript
  document.addEventListener('visibilitychange', () => {
    console.log(document.hidden, document.visibilityState)
  }, false)

  // Textarea autosize.
  // http://www.jacklmoore.com/autosize/
  // https://github.com/jackmoore/autosize
  // https://www.npmjs.com/package/autosize
  // from a jQuery collection
  autosize($('textarea'))

  // AOS scroll reveal.
  // http://michalsnik.github.io/aos/
  // https://css-tricks.com/aos-css-driven-scroll-animation-library/
  AOS.init({
      duration: 1200,
  })

  // Refresh/ re-init aos on scroll.
  document.addEventListener('scroll', (event) => {
    AOS.init({
      duration: 1200,
    })
    // if (event.target.id === 'idOfUl') { // or any other filtering condition
    //     console.log('scrolling', event.target);
    // }
  }, true /*Capture event*/);
})
