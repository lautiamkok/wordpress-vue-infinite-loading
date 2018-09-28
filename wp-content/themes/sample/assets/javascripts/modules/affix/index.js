'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'

// Create affix and clone target.
export default function affix () {
  var target = $('.row-nav2')
  // target.after('<div class="affix" id="affix"></div>')

  var affix = $('.row-affix')
  // affix.append(target.clone(true))
  // affix.find('.menu-mobile-only').removeClass('hide-for-medium hide-for-large')
  // affix.find('.menu-main').animate({height: 'toggle'}, 600, 'easeOutExpo', function () {})

  // Show affix on scroll.
  // http://stackoverflow.com/questions/14389687/window-scroll-in-vanilla-javascript
  // http://stackoverflow.com/questions/17441065/how-to-detect-scroll-position-of-page-using-jquery
  // http://stackoverflow.com/questions/5686629/jquery-window-scroll-event-does-not-fire-up
  if (affix.length > 0) {
    var position = target.position()
    window.addEventListener('scroll', function () {
      var height = $(window).scrollTop()
      if (height > position.top) {
        target.css('visibility', 'hidden')
        affix.fadeIn()
      } else {
        affix.hide()
        target.css('visibility', 'visible')
      }
    })
  }
}
