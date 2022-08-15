// Image Cropper
var croppedImageDataURL;
var id;
var type;
var is_array;
var ratio = 16/9;
var name;
var thisWidth, thisHeight;
var cropper;
var canvas = $('.canvas-cropping-image'),
        context = canvas.get(0).getContext('2d'),
        $result = $('.result'),
        modalCropping = $('.modal-cropping');
function croperProductImage() {
    var cloneInput = null;

    // Fix issue chrome (WontFix dari google): https://bugs.chromium.org/p/chromium/issues/detail?id=2508
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    if (isChrome) {
        $(document).on('click', '.cropping-image', function () {
            cloneInput = $(this).clone();
        });
    }
    $(document).on('change', '.cropping-image', function () {
        // Jika cancel pada windows
        if (this.files.length == 0 && cloneInput !== null && isChrome) {
            cloneInput.insertAfter($(this));
            $(this).remove();
            cloneInput = null;
            return;
        }
        let loading = $('.loading');
        loading.addClass('show');
        name = $(this).data('type');
        // let thisChange = $(this);
        id = $(this).data('id');
        type = $(this).data('type');
        is_array = $(this).data('array') === true ? true : false;
        if (is_array === true) {
            if (!!type) {
                name = type + '[' + id + ']';
            }
        } else {
            if (!!type) {
                name = type;
            }
        }
        // console.log('Array', is_array, $(this).data('array'));
        if (!!$(this).data('ratio')) {
            ratio = $(this).data('ratio')
        }
        if ($('.result-'+id+' img').length !== 0) {
            $('<input>').attr({
                type: 'hidden',
                name: 'remove_image[]',
                value: $('.result-'+id+' img').attr('src')
            }).appendTo('#remove-image');
        }
        $('.result-error-crop-image-'+id+'').hide();
        if(this.files && this.files[0]){
            let fileName = this.files[0].name;
            $('.label-data-name-file-'+id+'').val(fileName);
            if(this.files[0].type.match(/^image\//)){
                canvas.cropper('destroy');
                let filereader = new FileReader();
                    filereader.onload = function (e) {
                    let img = new Image();
                    img.onload = function () {
                        thisHeight = this.height;
                        thisWidth = this.width;
                        $('#launchCropModal').click();
                        context.canvas.height = img.height;
                        context.canvas.width = img.width;
                        context.drawImage(img, 0, 0);
                        $(document).on('shown.bs.modal', '.modal-cropping', function () {
                        canvas.cropper({
                            aspectRatio : ratio,
                            viewMode : 1,
                            rotatable: true,
                            zoomOnTouch: false,
                            zoomOnWheel : false
                            });
                        loading.removeClass('show');
                        });
                        // var contData = cropper.getContainerData(); //Get container data
                        // cropper.setCropBoxData({ height: contData.height, width: contData.width  })
                        //croppedImageDataURL = canvas.cropper('getCroppedCanvas').toDataURL('image/png');
                    }
                    img.src = e.target.result;
                }
                filereader.readAsDataURL(this.files[0]);
            }else{
                let validatorImage = $('.list-validation-cropping-image').attr('data-validation-image');
                toastr.error(validatorImage);
                loading.removeClass('show');
            }
        }else{
            let validatorZeroValue = $('.list-validation-cropping-image').attr('data-validation-image');
            toastr.error(validatorZeroValue);
            
        }
    })
}

var imageSave = {};

// $('.btn-crop-image-ratio-one').click(function () {
//     let croppedImageDataURL = canvas.cropper('getCroppedCanvas', {
//         minWidth: 4096,
//         minHeight: 4096,
//         imageSmoothingEnabled: true,
//         imageSmoothingQuality: 'high',
//     });
//     croppedImageDataURL.toBlob(function (blob) {
//         imageSave[name] = blob;
//     });

//     $('result-crop-ratio-one').html($('<img>').attr('src', croppedImageDataURL.toDataURL('image/jpg', 1)));
// });

// $('.cancel-crop').click(function () {
//     $('.label-data-name-file-[data-id='+ id +']').val('');
// });
// input nAME="IMage[0][width]"

$(document).on('click', '.cancel-crop, .close', function () {
    let dataName = $('.label-data-name-file-'+id+'');
    $('[data-id='+id+']').val('');
    // if($(document).closest('.cropping-image'))
    let fileNameDefault = $('.cancel-crop').attr('data-file-name');
    dataName.text(fileNameDefault);
})

$('.btn-crop-image').click(function () {
    let croppedImageDataURL = canvas.cropper('getCroppedCanvas', {
        // minWidth: 1080,
        // minHeight: 1920,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'medium',
    }).toDataURL('image/jpg', 1);
   
    /*croppedImageDataURL.toBlob(function (blob) {
        let name = 'images-'+id;
        if (is_array === true) {
            if (!!type) {
                name = type + '-' + id;
            }
        } else {
            if (!!type) {
                name = type;
            }
            
        }
        imageSave[name] = blob;
    }, 'image/jpg', 1);*/
    // console.log('id', id, croppedImageDataURL.toDataURL('image/jpg'))
    //console.log(base64toBlob(croppedImageDataURL, 'image/png'));
    //$('.result-hidden-'+id).val(croppedImageDataURL);
    $('.result-'+id).html($('<img>').attr('src', croppedImageDataURL));
    // let name = 'images-'+id;
    $('<input>').attr({
        type: 'hidden',
        name: 'original-'+name+'[height]',
        value: thisHeight
    }).appendTo($('.result-'+id));
    $('<input>').attr({
        type: 'hidden',
        name: 'original-'+name+'[width]',
        value: thisWidth
    }).appendTo($('.result-'+id));
    let anu = canvas.cropper('getData');
    $.each(anu, function (i, val) {
        $('<input>').attr({
            type: 'hidden',
            name: 'crop-'+name+'['+i+']',
            value: val
        }).appendTo($('.result-'+id));
    })
    // $('<input>').attr({
    //     type: 'hidden',
    //     id: 'images-'+id,
    //     name: name,
    //     value: croppedImageDataURL
    // }).appendTo('.result-'+id);
    $('.group-input-image-'+id).removeClass('display-none');
});

$(document).on('click','.btn-remove-image-cropper', function () {
    // $(document).closest('.input-group').find('.input-group-cropping-image').hide();
    let input = $(this).parent().parent().find('input.cropping-image');
    let is_array = input.data('array') === true;
    let type = input.data('type');
    let data_id = $(this).data('id');
    let name = type;
    $('.group-input-image-'+data_id).addClass('display-none');
    if (is_array === true) {
        name = name + '-'+data_id;
    }
    let file = $(this).closest('.widget-image').find('.result-'+data_id+' img').attr('src');
    $('.result-'+data_id+' img').remove();
    $('.label-data-name-file-'+data_id).text('');
    $(document).closest('.input-group').find('.custom-file-input').val('');
    if (!!imageSave[name]) {
        delete imageSave[name];
    } else {
        $('<input>').attr({
            type: 'hidden',
            name: 'remove_image[]',
            value: file
        }).appendTo('#remove-image');
    }
    $('.cropping-image[data-id='+ data_id +']').val('');
    let dataName = $('.label-data-name-file-'+id+'');
    $('#images-'+id).remove();
    // if($(document).closest('.cropping-image'))
    let fileNameDefault = $('.cancel-crop').attr('data-file-name');
    dataName.text(fileNameDefault);
});

$(document).on('click', '#btn-zoom-in', function () {
   canvas.cropper('zoom', 0.1);
});

$(document).on('click', '#btn-zoom-out', function () {
    canvas.cropper('zoom', -0.1);
 });

 $(document).on('click', '#btn-down', function () {
    canvas.cropper('move', 0, -10);
 });

 $(document).on('click', '#btn-up', function () {
    canvas.cropper('move', 0, 10);
 });



$(document).on('click', '.btn-rotate', function () {
    canvas.cropper('rotate',  parseInt($(this).data('rotate')));
});




// QUERY SELECTOR FOR INPUT IMAGE FILE CUSTOM

// function selectorCustomInput() {
//     var input = document.querySelectorAll('.beautiful-input-type-file');

// }
