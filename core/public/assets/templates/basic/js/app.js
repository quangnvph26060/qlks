"use strict";


$(document).ready(function () {
  //preloader
  $(".preloader")
    .delay(300)
    .animate(
      {
        opacity: "0",
      },
      300,
      function () {
        $(".preloader").css("display", "none");
      }
    );
});

// ============== Header Hide Click On Body Js Start ========
$('.navbar-toggler').on('click', function () {
  $('.body-overlay').toggleClass('show-overlay')
});
$('.body-overlay').on('click', function () {
  $('.navbar-toggler').trigger('click')
  $(this).removeClass('show-overlay');
});
// =============== Header Hide Click On Body Js End =========


// menu options custom affix
var fixed_top = $(".header");
$(window).on("scroll", function () {
  if ($(window).scrollTop() > 50) {
    fixed_top.addClass("animated fadeInDown menu-fixed");
  } else {
    fixed_top.removeClass("animated fadeInDown menu-fixed");
  }
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on(
  "click",
  function () {
    const element = $(this).parent("li");
    if (element.hasClass("open")) {
      element.removeClass("open");
      element.find("li").removeClass("open");
    } else {
      element.addClass("open");
      element.siblings("li").removeClass("open");
      element.siblings("li").find("li").removeClass("open");
    }
  }
);

$(".header__search-btn").on("click", function () {
  $(this).toggleClass("active");
  $(".header-search-form").toggleClass("active");
});

$(document).on("click touchstart", function (e) {
  if (
    !$(e.target).is(
      ".header__search-btn, .header__search-btn *, .header-search-form, .header-search-form *"
    )
  ) {
    $(".header-search-form").removeClass("active");
    $(".header__search-btn").removeClass("active");
  }
});

// main wrapper calculator
var bodySelector = document.querySelector("body");
var headerTop = document.querySelector(".header__top");
var heroSection = document.querySelector(".hero-section");
var innerHeroSection = document.querySelector(".inner-hero");

(function () {
  if (bodySelector.contains(headerTop) && bodySelector.contains(heroSection)) {
    var headerTopHeight = headerTop.clientHeight;

    var totalHeight = parseInt(headerTopHeight, 10) + "px";
    heroSection.style.marginTop = `${totalHeight}`;
  }

  if (
    bodySelector.contains(headerTop) &&
    bodySelector.contains(innerHeroSection)
  ) {
    var headerTopHeight = headerTop.clientHeight;

    var totalHeight = parseInt(headerTopHeight, 10) + "px";
    innerHeroSection.style.marginTop = `${totalHeight}`;
  }
})();

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});


// Show or hide the sticky footer button
$(window).on("scroll", function () {
  if ($(this).scrollTop() > 200) {
    $(".scroll-to-top").fadeIn(200);
  } else {
    $(".scroll-to-top").fadeOut(200);
  }
});

// Animate the scroll to top
$(".scroll-to-top").on("click", function (event) {
  event.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, 300);
});

new WOW().init();

// lightcase plugin init
$("a[data-rel^=lightcase]").lightcase();

$(".datepicker-here").datepicker({
  autoClose: true,
  minDate: new Date(),
});

// faq js
$(".faq-single__header").each(function () {
  $(this).on("click", function () {
    $(this).siblings(".faq-single__content").slideToggle();
    $(this).parent(".faq-single").toggleClass("active");
  });
});

// brand image append js
$(".brand-item").each(function () {
  var imgsrc = $(this).attr("data-src");
  $(this).append(`
    <img src="${imgsrc}" alt="image" class="front-img">
    <img src="${imgsrc}" alt="image" class="back-img">
  `);
});

$(".sidebar-open-btn").on("click", function () {
  $(".sidebar").addClass("active");
});

$(".sidebar-close-btn").on("click", function () {
  $(".sidebar").removeClass("active");
});

$(".user-sidebar-open-btn").on("click", function () {
  $(".user-sidebar__menu").slideToggle();
});

// other-room-slider js
$(".other-room-slider").slick({
  infinite: true,
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: false,
  arrows: true,
  prevArrow: '<div class="prev"><i class="las la-angle-left"></i></div>',
  nextArrow: '<div class="next"><i class="las la-angle-right"></i></div>',
  autoplay: false,
  cssEase: "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
  speed: 1000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
      },
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
      },
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2,
      },
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
      },
    },
  ],
});

// testimonial-slider js
$(".testimonial-slider").slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 1,
  dots: false,
  arrows: false,
  autoplay: true,
  cssEase: "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
  speed: 2000,
  autoplaySpeed: 1000,
  responsive: [
    {
      breakpoint: 1400,
      settings: {
        slidesToShow: 3,
      },
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
      },
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
      },
    },
  ],
});

$(".room-details-thumb-slider").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  dots: false,
  fade: true,
  asNavFor: ".room-details-nav-slider",
});
$(".room-details-nav-slider").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  asNavFor: ".room-details-thumb-slider",
  arrows: false,
  dots: false,
  focusOnSelect: true,
});

$(".sidebar-open-btn").on("click", function (e) {
  $(".overlay").toggleClass("active");
});

$(".sidebar-close-btn").on("click", function (e) {
  $(".overlay").removeClass("active");
});

$(".overlay").on("click", function (e) {
  $(".overlay").removeClass("active");
  $(".sidebar").removeClass("active");
});

$(".user-sidebar-toggler").on("click", function (e) {
  $(".overlay").toggleClass("active");
  $(".user-sidebar").toggleClass("active");
});

$(".sidebar-close, .overlay").on("click", function (e) {
  $(".overlay").removeClass("active");
  $(".user-sidebar").removeClass("active");
});

$(".datepicker-here").on("keydown", function () {
  return false;
});

//faq - open by odd -even  items!
window.addEventListener("DOMContentLoaded", () => {
  let faqElements = document.querySelectorAll(".accordion-item");
  let faqContainer = document.getElementById("faqAccordion1");
  let oddElement = "";
  let evenElement = "";

  if (
    faqContainer == undefined ||
    faqContainer.tagName != "DIV" ||
    typeof faqElements != "object"
  )
    return false;

  Array.from(faqElements).forEach(function (element, i) {
    if (i % 2 == 0) {
      evenElement += element.outerHTML;
    } else {
      oddElement += element.outerHTML;
    }
  });

  faqContainer.innerHTML = `
	<div class="row gy-3">
	  <div class="col-lg-6">${evenElement}</div>
	  <div class="col-lg-6">${oddElement}</div>
	</div>`;
});

// =====Faq End======



//required
$.each($('input, select, textarea'), function (i, element) {
  if (element.hasAttribute('required')) {
    $(element).closest('.form-group').find('label').first().addClass('required');
  }

});


//data-label of table//
Array.from(document.querySelectorAll('table')).forEach(table => {
  let heading = table.querySelectorAll('thead tr th');
  Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
    Array.from(row.querySelectorAll('td')).forEach((column, i) => {
      (column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
    });
  });
});

$(document).ready(function () {
  const $mainlangList = $(".langList");
  const $langBtn = $(".language-content");
  const $langListItem = $mainlangList.children();

  $langListItem.each(function () {
    const $innerItem = $(this);
    const $languageText = $innerItem.find(".language_text");
    const $languageFlag = $innerItem.find(".language_flag");

    $innerItem.on("click", function (e) {
      e.preventDefault();
      $langBtn.find(".language_text_select").text($languageText.text());
      $langBtn.find(".language_flag").html($languageFlag.html());
      var location = $innerItem.find('a').attr('href');
      window.location.href = location;
    });
  });
});


