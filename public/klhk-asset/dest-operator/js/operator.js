'use strict';

var jQvan = jQuery.noConflict();
jQvan(document).ready(function ($) {
  $('body').addClass('ready');
  var scrolled = window.pageYOffset || document.documentElement.scrollTop;
  var $header = $('#header');
  if (scrolled < 80) {
    $header.addClass('scrolled');
  } else {
    $header.removeClass('scrolled');
  }
  $(window).on('scroll', function (e) {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if (scrolled < 80) {
      $header.addClass('scrolled');
    } else {
      $header.removeClass('scrolled');
    }
  });
  $('.btn-mobile-nav').on('click', function (e) {
    $header.toggleClass('opened');
  });
  /* jquery handle custom-default-logo */
  $('[name="default-logo"]').on('change', function (e) {
    $('#default-logo').attr('checked', true).prop('checked', true);
    $('[name="default-logo"]').parent().addClass('not-selected');
    $(this).parent().removeClass('not-selected');
  });
  /* jquery handle custom-default-banner */
  $('[name="default-banner"]').on('change', function (e) {
    $('#default-banner').attr('checked', true).prop('checked', true);
    $('[name="default-banner"]').parent().addClass('not-selected');
    $(this).parent().removeClass('not-selected');
  });

  //modal product page
  $('#successModal').on('shown.bs.modal', function () {
    // when body click it will redirect to product page
    $('body').on('click', function () {
      $(location).attr('href', 'product.html');
    });
  });
});

// table sorting
function sortTable(n, table, type, el) {
  var table,
      rows,
      switching,
      i,
      x,
      y,
      shouldSwitch,
      dir,
      switchcount = 0;
  table = document.getElementById(table);
  switching = true;
  // Set the sorting direction to ascending:

  /* Make a loop that will continue until
  no switching has been done: */
  var dir = el.className;
  if (dir === 'desc') {
    el.className = "asc";
    el.innerHTML = "<span class='fa fa-caret-up'></span>";
  } else {
    el.className = "desc";
    el.innerHTML = "<span class='fa fa-caret-down'></span>";
  }
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < rows.length - 1; i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (type === "number") {
          if (Number(x.innerHTML) > Number(y.innerHTML)) {
            shouldSwitch = true;
            break;
          }
        }
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (type === "number") {
          if (Number(x.innerHTML) < Number(y.innerHTML)) {
            shouldSwitch = true;
            break;
          }
        }
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}