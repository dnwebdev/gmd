/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */
// Sidebar navigation
var _navigationSidebar = function() {

    // Define default class names and options
    var navClass = 'nav-sidebar',
        navItemClass = 'nav-item',
        navItemOpenClass = 'nav-item-open',
        navLinkClass = 'nav-link',
        navSubmenuClass = 'nav-group-sub',
        navSlidingSpeed = 250;

    // Configure collapsible functionality
    $('.' + navClass).each(function() {
        $(this).find('.' + navItemClass).has('.' + navSubmenuClass).children('.' + navItemClass + ' > ' + '.' + navLinkClass).not('.disabled').on('click', function (e) {
            e.preventDefault();

            // Simplify stuff
            var $target = $(this),
                $navSidebarMini = $('.sidebar-xs').not('.sidebar-mobile-main').find('.sidebar-main .' + navClass).children('.' + navItemClass);

            // Collapsible
            if($target.parent('.' + navItemClass).hasClass(navItemOpenClass)) {
                $target.parent('.' + navItemClass).not($navSidebarMini).removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
            }
            else {
                $target.parent('.' + navItemClass).not($navSidebarMini).addClass(navItemOpenClass).children('.' + navSubmenuClass).slideDown(navSlidingSpeed);
            }

            // Accordion
            if ($target.parents('.' + navClass).data('nav-type') == 'accordion') {
                $target.parent('.' + navItemClass).not($navSidebarMini).siblings(':has(.' + navSubmenuClass + ')').removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
            }
        });
    });

    // Disable click in disabled navigation items
    $(document).on('click', '.' + navClass + ' .disabled', function(e) {
        e.preventDefault();
    });

    // Scrollspy navigation
    $('.nav-scrollspy')
        .find('.' + navItemClass)
        .has('.' + navSubmenuClass)
        .children('.' + navItemClass + ' > ' + '.' + navLinkClass)
        .off('click');
};

// Navbar navigation
var _navigationNavbar = function() {

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function(e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a, .nav-item-levels .disabled').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function(e) {
        $(this).tab('show');
    });
};

$('.sidebar-main-toggle').on('click', function (e) {
    e.preventDefault();
    let navGroup = $('.nav.nav-group-sub');
    if (navGroup.css('display')==='block'){
        navGroup.css('display', '');
    }
    // else{
    //     navGroup.css('display', 'block');
    // }
    console.log('testing')

    $('body').toggleClass('sidebar-xs').removeClass('sidebar-mobile-main');
    revertBottomMenus();

});

// call function
_navigationSidebar();
_navigationNavbar();