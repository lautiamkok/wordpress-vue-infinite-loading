'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'

// Show/ hide menu mobile/ aside.
export default function aside () {
  var buttonShowMenuMobile = $('.button-show-aside')
  var buttonHideMenuMobile = $('.button-hide-aside')
  var aside = $('.navbar-aside')

  // Do it from server side script which is easier.
  // clone.removeAttr("data-dropdown-menu");
  // clone.removeAttr("data-opacity-target");
  // clone.attr("data-accordion-menu");
  // clone.removeClass("align-center dropdown hide-for-small-only menu-main navbar-items");
  // clone.addClass("vertical menu-main-aside");
  // console.log(clone.wrap('<div></div>').parent().html());
  // target.replaceWith(clone);
  // target.append(clone);
  // console.log($(".menu-main-aside").wrap('<div></div>').parent().html());

  buttonShowMenuMobile.on('click', function () {
    buttonShowMenuMobile.addClass('hide')

    aside.fadeIn('fast', function () {
      // do something or nothing.
    })

    aside.find('.cell-aside').animate({
      width: 'toggle'
    },
     600,
    'easeOutExpo',
    function () {
      // do something or nothing.
    })

    return false
  })

  buttonHideMenuMobile.on('click', function () {
    buttonShowMenuMobile.removeClass('hide')

    aside.find('.cell-aside').animate({
      width: 'toggle'
    },
     600,
    'easeOutExpo',
    function () {
      // do something or nothing.
    })

    aside.fadeOut('fast', function () {
      // do something or nothing.
    })

    return false
  })
}
