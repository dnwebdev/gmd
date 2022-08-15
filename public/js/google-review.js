window.$ = window.jQuery;


function renderButtonGoogle() {

    let a = setInterval(function () {
        let toolbar = $('.eapps-widget-show-toolbar');
        let right = $(document).find("*[class^='WriteAReviewButton__Component']");
        let Header = $(document).find("*[class^='Header__Component']");
        let list = Header.next();
        list.hide();
        console.log(right)
        if (right.length === 1) {
            console.log(right.length);
            clearInterval(a);
            setTimeout(function () {
                right.html('<button class="btn btn-primary btn-block btn-sm mb-3 mb-md-0" id="btn-google" data-close="'+gomodo.hide_google_widget_reviews+'" data-open="'+gomodo.show_google_widget_reviews+'">'+gomodo.show_google_widget_reviews+'</button>')
            }, 1000)


        }
    }, 1000)

}


document.addEventListener('readystatechange', event => {

    if (event.target.readyState === "complete") {
        renderButtonGoogle();
    }

});

$(document).on('click', '#btn-google', function (e) {
    let t = $(this);
    let Header = $(document).find("*[class^='Header__Component']");
    let list = Header.next();
    list.stop().fadeToggle('slow')
    // $('.eagr-cta-component').stop().toggle('fast');
    // if ($(document).find('.eagr-content-component').hasClass('down')){
    //     t.text(t.data('close'))
    // }else{
    //     t.text(t.data('open'))
    //
    // }
});