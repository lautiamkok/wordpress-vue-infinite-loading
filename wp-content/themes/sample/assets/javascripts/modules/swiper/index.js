'use strict'

import $ from 'jquery'
import 'jquery-ui-bundle'
import Swiper from 'swiper'

export default function swiper () {
  // Create global swiper for image slide
  // var swiperSlide = new Swiper('.row-swiper .swiper-container', {
  //   slidesPerView: 'auto',
  //   centeredSlides: true,
  //   paginationClickable: true,
  //   // loop: true,
  //   // grabCursor: true,
  //   // freeMode: false,
  //   keyboard: {
  //     enabled: true,
  //   },
  //   pagination: {
  //     el: '.row-swiper .swiper-pagination',
  //     clickable: true,
  //   },
  //   navigation: {
  //     nextEl: '.row-swiper .swiper-button-next',
  //     prevEl: '.row-swiper .swiper-button-prev',
  //   },
  // })

  // Create global autoplay swiper for image slide.
  var swiperSlide = new Swiper('.row-background .swiper-container', {
    slidesPerView: 'auto',
    centeredSlides: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    // pagination: {
    //   el: '.row-swiper .swiper-pagination',
    //   clickable: true,
    // },
    // navigation: {
    //   nextEl: '.row-swiper .swiper-button-next',
    //   prevEl: '.row-swiper .swiper-button-prev',
    // },
  })

  // Gallery
  var galleryTop = new Swiper('.gallery-top', {
    spaceBetween: 10,
    slidesPerView: 1,
    loop: true,
    grabCursor: false,
    freeMode: false,
    keyboard: {
      enabled: true,
    },
    navigation: {
      nextEl: '.swiper-gallery .swiper-button-next',
      prevEl: '.swiper-gallery .swiper-button-prev',
    },
  })
  var galleryThumbs = new Swiper('.gallery-thumbs', {
    loop: true,
    spaceBetween: 10,
    // centeredSlides: true,
    slidesPerView: 'auto',
    touchRatio: 0.2,
    slideToClickedSlide: true
  })
  galleryTop.on('slideChange', function () {
    console.log("view index: " + this.realIndex)
    galleryThumbs.slideTo(this.realIndex)
  });
  galleryThumbs.on('slideChange', function () {
    console.log("thumb index: " + this.realIndex)
    galleryTop.slideTo(this.realIndex + 1)
  });

  // Fade in the swiper arrow buttons.
  $('.row-swiper, .swiper-gallery').hover(function () {
    var selector = $('.swiper-arrow')
    $(selector).fadeIn('fast')
  }, function () {
    var selector = $('.swiper-arrow')
    $(selector).fadeOut('fast', function () {
    })
  })

  // Overlay content.
  var buttonLaunch = $('.open-overlay-content')
  var exits = $('.exit-overlay-content i, .overlay-content')
  var swiperSlide = null

  buttonLaunch.on('click', function() {
    console.log('.overlay-content is opened')

    // Get the index.
    var index = $(this).data('index')
    console.log('index: ' + index)

    var context =  $('.overlay-content')
    context.addClass('is-open')
    $('html, body').css('overflow-x', 'hidden')
    $('html, body').css('overflow-y', 'hidden')

    // Create global swiper for image slide
    swiperSlide = new Swiper('.row-swiper .swiper-container', {
      // init: false,
      initialSlide: index,
      slidesPerView: 'auto',
      centeredSlides: true,
      paginationClickable: true,
      loop: true,
      grabCursor: true,
      freeMode: false,
      keyboard: {
        enabled: true,
      },
      navigation: {
        nextEl: '.row-swiper .swiper-button-next',
        prevEl: '.row-swiper .swiper-button-prev',
      },
      pagination: {
        el: '.row-swiper .swiper-pagination',
        clickable: true,
        // renderBullet: function (index, className) {
        //   return '<span class="' + className + '">' + (index + 1) + '</span>';
        // },
      },
    })

    if($('.row-swiper.autoheight').length > 0) {
      // Create global swiper for image slide
      swiperSlide = new Swiper('.row-swiper .swiper-container', {
        // init: false,
        initialSlide: index,
        slidesPerView: 'auto',
        centeredSlides: true,
        paginationClickable: true,
        loop: true,
        grabCursor: true,
        freeMode: false,
        keyboard: {
          enabled: true,
        },
        navigation: {
          nextEl: '.row-swiper .swiper-button-next',
          prevEl: '.row-swiper .swiper-button-prev',
        },
        pagination: {
          el: '.row-swiper .swiper-pagination',
          clickable: true,
        },
        autoHeight: true
      })
    }

    swiperSlide.on('slideChange', function () {
      console.log('view index: ' + this.realIndex)
    })
    return false
  })

  exits.on('click', function(event) {
    // Stop the click event propagating to the child elements.
    if (event.target !== this) {
      return
    }
    console.log('close overlay')

    $('.overlay-content').removeClass('is-open')
    $('html, body').css('overflow-x', 'auto')
    $('html, body').css('overflow-y', 'auto')

    // Destroy the swiper.
    swiperSlide.destroy(true, false)
    return false
  })

  // Check if esc key is pressed.
  // https://stackoverflow.com/questions/3369593/how-to-detect-escape-key-press-with-pure-js-or-jquery
  $(document).keyup(function(e) {
     if (e.keyCode == 27) { // escape key maps to keycode `27`
      $('.overlay-content').removeClass('is-open')
      $('html, body').css('overflow-x', 'auto')
      $('html, body').css('overflow-y', 'auto')

      // Destroy the swiper.
      swiperSlide.destroy(true, false)
    }
  })

  // Re-init the swiper on window resize.
  $( window ).resize(function() {
    // console.log('resize')
    // console.log(swiperSlide)
    if (swiperSlide === null || swiperSlide.destroyed === true) {
      return
    }
    // console.log('swiper not null')
    swiperSlide.init()
  })
}
