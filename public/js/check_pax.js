$('input[name="product_name"]').on('keyup paste cut change', function () {
    let productNameError = $('#product-name-error');
        productNameError.hide();
        productNameError.text('');
});

$('#country_search').change(function () {
    let error = $('#country-error');
    error.hide();
    error.text('');
});

$('#state_search').change(function () {
    let error = $('#state-error');
    error.hide();
    error.text('');
});

$('#city_search').change(function () {
    let error = $('#city-error');
    error.hide();
    error.text('');
});

$('input[name="brief_description"]').on('keyup paste cut change', function () {
    let error = $('#brief-error');
    error.hide();
    error.text('');
});

$('input[name="long_description"]').on('keyup paste cut change', function () {
    let error = $('#about-this-error');
    error.hide();
    error.text('');
});

$('input[name="start_date[0]"]').change(function () {
    let error = $('#error_start_date');
    error.hide();
    error.text('');
});

$('input[name="start_date[0]"]').change(function () {
    let error = $('#error_end_date');
    error.hide();
    error.text('');
});

$('input[name="duration"]').on('keyup paste cut change', function () {
    let error = $('#min-duration-activity-error');
    error.hide();
    error.text('');
});

$('input[name="max_people"]').on('keyup paste cut change', function () {
    let error = $('#max-people-error');
    error.hide();
    error.text('');
});

function checkMinMaxPeople(){
    let productName = $('input[name="product_name"]').val();
    let selectCountry = $('#country_search').val();
    let selectState = $('#state_search').val();
    let selectCity = $('#city_search').val();
    let briefProduct = $('input[name="brief_description"]').val();
    let longDesciption = $('#long_description').val();
    let durationActivity = $('input[name="duration"]').val();
    let minPeopleForm = $('#min_people').val();
    let maxPeopleForm = $('#max_people').val();
    let minNotice = $('#minimum_notice').val();
    let date_start_product_create = $('input[name="start_date[0]"]').val();
    let date_start_product_edit = $('input[name="start_date[]"]').val();
    let date_end = $('input[name="end_date[0]"]').val();
    let error = [];
    let errors = [];
    let valid = true;
    //CHECK ALL FORM
    let minPeople = parseInt($(document).find('#min_people').val());
    let maxPeople = parseInt($(document).find('#max_people').val());
    let arrayLength = $(document).find('input[name="price_from[]"]').length;
    let valueNumber = '';

    // CHECK THE VALUE FROM MINPRICE
    if (productName === ''){
        valid = false;
        let dataValidation = $('input[name="product_name"]').attr('data-validation');
        error.push(dataValidation, '<br>');
        let productNameError = $('#product-name-error');
        productNameError.show();
        productNameError.text(dataValidation);
    }else{
        let productNameError = $('#product-name-error');
        productNameError.hide();
        productNameError.text('');
    }

    if (selectCountry === null){
        valid = false;
        let dataValidation = $('#country_search').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorCountry = $('#country-error');
        errorCountry.show();
        errorCountry.text(dataValidation);
    }else{
        let errorCountry = $('#country-error');
        errorCountry.show();
        errorCountry.text('');
    }

    if (selectState === null){
        valid = false;
        let dataValidation = $('#state_search').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorState = $('#state-error');
        errorState.show();
        errorState.text(dataValidation);
        // error.push(window.gomodo.country_required);
    }else{
        let errorState = $('#state-error');
        errorState.hide();
        errorState.text('');
    }

    if (selectCity === null){
        valid = false;
        let dataValidation = $('#city_search').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorCity = $('#city-error');
        errorCity.show();
        errorCity.text(dataValidation);
    }else{
        let errorCity = $('#city-error');
        errorCity.hide();
        errorCity.text('');
    }

    if(briefProduct === ''){
        valid = false;
        let dataValidation = $('input[name="brief_description"]').attr('data-validation');
        error.push(dataValidation);
        let errorBrief = $('#brief-error');
        errorBrief.show();
        errorBrief.text(dataValidation);
    }else{
        let errorBrief = $('#brief-error');
        errorBrief.hide();
        errorBrief.text('');
    }

    if(longDesciption === ''){
        valid = false;
        let dataValidation = $('#long_description').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorDescription = $('#about-this-error');
        errorDescription.show();
        errorDescription.text(dataValidation);
    }else {
        let errorDescription = $('#about-this-error');
        errorDescription.hide();
        errorDescription.text('');
    }

    if (durationActivity === ''){
        valid = false;
        let dataValidation = $('input[name="duration"]').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorDuration = $('#min-duration-activity-error');
        errorDuration.show();
        errorDuration.text(dataValidation);
    }else{
        let errorDuration = $('#min-duration-activity-error');
        errorDuration.hide();
        errorDuration.text('');
    }

    if(date_start_product_create !== undefined) {
        if(date_start_product_create === ''){
            valid = false;
            let dataValidation = $('input[name="start_date[0]"').attr('data-validation');
            error.push(dataValidation, '<br>');
            let errorDate = $('#error_start_date');
            errorDate.text(dataValidation);
            errorDate.show();
        }else{
            let errorDate = $('#error_start_date');
            errorDate.text('');
            errorDate.hide();
        }
    }

    if(date_start_product_edit !== undefined) {
        if(date_start_product_edit === ''){
            valid = false;
            let dataValidation = $('input[name="start_date[]"').attr('data-validation');
            error.push(dataValidation, '<br>');
            let errorDate = $('#error_start_date');
            errorDate.text(dataValidation);
            errorDate.show();
        }else{
            let errorDate = $('#error_start_date');
            errorDate.text('');
            errorDate.hide();
        }
    }

    if(date_end === ''){
        valid = false;
        let dataValidation = $('input[name="end_date[0]"').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorDate = $('#error_end_date');
        errorDate.text(dataValidation);
        errorDate.show();
    }else{
        let errorDate = $('#error_end_date');
        errorDate.text('');
        errorDate.hide();
    }

    if(dayCheck()===false) {
        valid = false;
        error.push($('#day-alert').attr('data-validationMessage') + '<br>');
    }

    if (maxPeopleForm === ''){
        valid = false;
        let dataValidation = $('#max_people').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorMaxPeople = $('#max-people-error');
        errorMaxPeople.show();
        errorMaxPeople.text(dataValidation);
    }else{
        let errorMaxPeople = $('#max-people-error');
        errorMaxPeople.hide();
        errorMaxPeople.text('');
    }

    if (minPeopleForm === ''){
        valid = false;
        let dataValidation = $('#min_people').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorMinPeople = $('#min-people-error');
        errorMinPeople.show();
        errorMinPeople.text(dataValidation);
    }else{
        let errorMinPeople = $('#min-people-error');
        errorMinPeople.hide();
        errorMinPeople.text('');
    }

    if(minNotice === ''){
        valid = false;
        let dataValidation = $('input[name="minimum_notice"]').attr('data-validation');
        error.push(dataValidation, '<br>');
        let errorMinNotice = $('#min-notice-error');
        errorMinNotice.show();
        errorMinNotice.text(dataValidation);
    }else{
        let errorMinNotice = $('#min-notice-error');
        errorMinNotice.hide();
        errorMinNotice.text('');
    }

    // if(minPeople === maxPeople){
    //     valid = false;
    //     error.push(window.gomodo.min_people, '<br>');
    //     $('#alert-min-people').html(error);
    // }else if(minPeople >= maxPeople){
    //     valid = false;
    //     error.push(window.gomodo.min_people_else, '<br>');
    //     $('#alert-max-people').html(error);
    // }

    // Product Price Validation
    function productPrice() {
        minMaxPeople_validation();
        priceList_validation();
        priceamount_validation();
        return minMaxPeople_validation() && priceList_validation() && priceamount_validation()
    }

    if(!productPrice()) {
        valid = false;
        error.push($('.product-pricing').attr('data-valdiation'));
    }

    // CHECK PRICE TIER
    if(arrayLength === 1){
        if(parseInt($(document).find('input[name="price_from[]"]').val()) !== minPeople){
            valid = false;
            error.push(window.gomodo.price_from, '<br>');
            errors.push(window.gomodo.price_from, '<br>');
            $('#alert-price-from').html(errors);
        }else if(parseInt($(document).find('input[name="price_until[]"]').val()) !== maxPeople){
            valid = false;
            error.push(window.gomodo.price_from_else, '<br>');
            errors.push(window.gomodo.price_from_else, '<br>');
            $('#alert-price-until').html(errors);
        }
    }else if(arrayLength > 1){
        // for(let i = 0; i < arrayLength; i++){
            // let currentMin = parseInt($($(document).find('input[name="price_from[]"]')[''+i+'']).val());
            // let currentMax = parseInt($($(document).find('input[name="price_until[]"]')[''+i+'']).val());
            // if(currentMin > currentMax){
            //     valid = false;
            //     error.push('In product price, Min Pax Price row '+(i+1)+' must be less than Max Pax row '+(i+1)+'\n');
            //     $('#alert-price-from').html(errors);
            // }
            // if(i === 0){
                // if(currentMin !== minPeople){
                //     valid = false;
                //     error.push('In product price, Min Pax Price row '+(i+1)+' must be more than max pax row '+(i+1)+' \n');
                //     $('#alert-price-from').html(errors);
                // }
            // }else{
                // if(i === (arrayLength -1)){
                    // if(currentMax !== maxPeople){
                    //     valid = false;
                    //     error.push('In product price, max pax last row must be same with max pax tour in activity duration \n');
                    //     $('#alert-price-until').html(errors);
                    // }
                    // let beforeMax = parseInt($($(document).find('input[name="price_until[]"]')[i-1]).val());
                    // if(currentMin !== (beforeMax+1)){
                    //     valid = false;
                    //     error.push('In product price, Min pax price row '+(i+1)+' must higher 1 point from max Pax row '+i+'\n');
                    //     $('#alert-price-from').html(errors);
                    // }
                // }
            // }
        // }
    }

    // let response = '';
    if(valid){
        response = 'valid';
        // $('small').css("display","none");
    }else{
        // introJs().exit();
        for(let i = 0; i < arrayLength; i++){
            // $($("#alert-min-people")[i]).css("display","flex");
            // $($("#alert-max-people")[i]).css("display","flex");
            // $($("#alert-price-from")[i]).css("display","flex");
            // $($("#alert-price-until")[i]).css("display","flex");
        }
        response = '';
        $.each(error, function (i, e) {
            response+=e+'\n';
            Swal({
                title: productErrorTitle,
                type: 'warning',
                // html: response +'\n'
                html: productErrorMessage
            }).then(function(){
                if (RegExp('multipage', 'gi').test(window.location.search)) {
                    StartCreateIntro();
                }
            })
        })
    }
    return valid;
}

$('#btn-submit').click(function(){
    if (checkMinMaxPeople()){
        $(this).closest('form').submit()
    }
});

// Day check list Validation
$(document).on('change', '.day, #availability', function(){
    dayCheck();
    dayCount();
    var input_date = $('input[name="start_date[0]"], input[name="end_date[0]"], input[name="start_date[]"], input[name="end_date[]"]');
    var input_end_date = $('input[name="end_date[0]"], input[name="end_date[]"]');
    input_date.val('');
    input_end_date.prop('disabled', true);
    // Color red temporary for attention
    input_date.addClass('red-temp');
    setTimeout(function(){
        input_date.removeClass('red-temp');
    },500)
})
dayCheck();

function dayCheck(){
    var input_date = $('input[name="start_date[0]"], input[name="end_date[0]"], input[name="start_date[]"], input[name="end_date[]"]');
    var input_start_date = $('input[name="start_date[0]"], input[name="start_date[]"]');
    var input_end_date = $('input[name="end_date[0]"], input[name="end_date[]"]');
    var dayAlert = $('#day-alert');
    var valid = false;
    dayAlert.html('');
    $('.day').each(function(i, e){
        var checked = $(e).prop('checked');
        if(checked == true){
            valid = true;
        }
    })
    if(valid){
        // Day Alert
        dayAlert.html('');
        dayAlert.css('display', 'none');

        input_start_date.prop('disabled', false);
        input_end_date.prop('readonly', true);
    } else {
        // Day Alert
        dayAlert.css('display', 'block');
        dayAlert.html(dayAlert.attr('data-validationMessage'));

        input_date.prop('disabled', true).val('');
    }
    return valid
}
// $('input[name="min_people"]').on('input', function(){
//   valueNumber.push(parseInt($(document).find('input[name="min_people"]').val()));
//   $('input[name="price_from"]').html(valueNumber);
//   $('input[name="price_from"]').prop('readonly', true);
// })
// $('input[name="max_people"]').on('input', function(){
//   valueNumber.push(parseInt($(document).find('input[name="max_people"]').val()));
//   $('input[name="price_until"]').html(valueNumber);
//   $('input[name="price_until"]').prop('readonly', true);
// })

