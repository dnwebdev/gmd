window.$ = jQuery;
// FUNCTION GLOBAL USE
 function showComingSoon(element1, element2) {
     element1.hover( function () {
         element2.show('slow');
         console.log('testing');
     });
 }
