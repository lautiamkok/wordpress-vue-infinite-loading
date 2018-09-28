'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'
import { getZFcurrentMediaQuery } from '../../utils'

// Create affix and clone target.
export default function affixHeading () {
  var threshold = $('.row-divider.collection')
  var affix = $('.heading-related')
  var current = getZFcurrentMediaQuery()

  if (affix.length > 0) {
    var position = threshold.position()
    window.addEventListener('scroll', function () {
      var height = $(window).scrollTop()
      if (height > (position.top - 60)) {
        if (current === 'xxlarge' || current === 'xlarge' || current === 'large') {
          affix.addClass('fixed-for-large')
        }
      } else {
        affix.removeClass('fixed-for-large')
      }
    })
  }

  // Detect ZF sceen size on resize.
  window.addEventListener('resize', () => {
    current = getZFcurrentMediaQuery()
    if (current === 'medium' || current === 'small') {
      affix.removeClass('fixed-for-large')
    }
  })
}
