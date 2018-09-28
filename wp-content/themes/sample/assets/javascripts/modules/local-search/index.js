'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'

// Show/ hide menu mobile/ localSearch.
export default function localSearch () {
  var localSearch = $('.row-search')
  var ButtonShowLocalSearch = $('.button-show-local-search')
  var ButtonHideLocalSearch = $('.button-hide-local-search')
  ButtonShowLocalSearch.on('click', function () {
    localSearch.removeClass('hide')
    return false
  })

  ButtonHideLocalSearch.on('click', function () {
    localSearch.addClass('hide')
    return false
  })
}
