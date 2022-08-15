'use strict';

var jQlogin = jQuery.noConflict();
jQlogin(document).ready(function ($) {
  $('.password-viewer .password-viewer-icon').click(function (e) {
    var $parent = $(this).parent();
    if (!$parent.hasClass('active')) {
      $parent.addClass('active');
      $parent.find('.form-control').attr('type', 'text');
    } else {
      $parent.removeClass('active');
      $parent.find('.form-control').attr('type', 'password');
    }
  });
});