'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'

// Slide in search form using flex.
export default function flexSearch () {
  $(document).on('click','.button-show-search', function(event) {
    event.preventDefault()
    var $this = $(this)
    var context = $this.closest('form')
    var expand = $this.closest('.expand-for-search')
    var sibling = expand.siblings('.hide-for-search')
    var target = context.find('.container-input')
    var input = target.find('input')

    $this.removeClass('active').addClass('clicked')

    // Toggle functions
    if(input.is(":visible") && $this.hasClass('clicked')) {
      target.removeClass('active')
      $this.removeClass('clicked')

      setTimeout(function() {
        input.addClass('hide')
        sibling.removeClass('hide')
      }, 500);

    } else {
      sibling.addClass('hide')
      target.addClass('active')
      input.removeClass('hide')

      setTimeout(function() {
        input.focus()
      }, 500)
    }
  })

  // Reset search form.
  $(document).on('click','div, p, span:not(.propagate), li', function(event) {
    // Stop the click event propagating to the child elements.
    if (event.target !== this) {
      return
    }
    console.log('reset search.')

    var $this = $('.flex-search')
    var context = $this.closest('form')
    var expand = $this.closest('.expand-for-search')
    var sibling = expand.siblings('.hide-for-search')
    var target = context.find('.container-input')
    var input = target.find('input')

    // Toggle functions
    if(input.is(":visible") && $this.hasClass('clicked')) {
      target.removeClass('active')
      $this.removeClass('clicked')

      setTimeout(function() {
        input.addClass('hide')
        sibling.removeClass('hide')
      }, 500);
    }

    return false
  })
}
