'use strict';

var jQvan = jQuery.noConflict();
jQvan(document).ready(function ($) {
  $('body').addClass('ready');
  var scrolled = window.pageYOffset || document.documentElement.scrollTop;
  // var $header = $('#header');
  // if (scrolled < 80) {
  //   $header.addClass('scrolled');
  // } else {
  //   $header.removeClass('scrolled');
  // }
  // $(window).on('scroll', function (e) {
  //   var scrolled = window.pageYOffset || document.documentElement.scrollTop;
  //   if (scrolled < 80) {
  //     $header.addClass('scrolled');
  //   } else {
  //     $header.removeClass('scrolled');
  //   }
  // });
  $('.btn-mobile-nav').on('click', function (e) {
    $header.toggleClass('opened');
  });
});