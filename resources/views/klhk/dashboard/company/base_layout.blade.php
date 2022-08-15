<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gomodo">
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <?php
    $company = App\Models\Company::where('id_company', Auth::user()->id_company)->first();
    $logo = $company->logo ? $company->logo : '';
    $logo = "favicon.ico";
    if ($logo != "") {
        if (count(explode('/', $company->logo)) > 1)
            $logo = $company->logo;
        else
            $logo = 'uploads/company_logo/' . $company->logo;
    }
    ?>
    <link rel="icon" href="{{ asset($logo) }}">
    <title>@yield('title')</title>
    <!-- Favicons-->
    <link rel="icon" href="{{ asset($logo) }}" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <!-- CORE CSS-->
    <link href="{{ asset('materialize/css/datedropper.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('materialize/css/my-style.css') }}" type="text/css" rel="stylesheet">
    <!-- Custom CSS-->
    {{-- <link href="{{ asset('klhk-asset/dest-operator/lib/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('klhk-asset/dest-operator/lib/css/fontface.min.css') }}" rel="stylesheet">
    <link href="{{ asset('klhk-asset/dest-operator/lib/css/chosen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('klhk-asset/dest-operator/lib/css/jquery-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href=" {{ asset('js/cropper.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/tooltipster.bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/tooltipster-sideTip-noir.min.css')}}">
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/introjs.css')}}">
    <link rel="stylesheet" href="{{asset('css/gmap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('klhk-asset/dest-operator/lib/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="{{asset('klhk-asset/css/jquery-fab-button-custome.css')}}" rel="stylesheet">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk_global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Custome CSS Files -->
    <link href="{{ asset('klhk-asset/dest-operator/css/index_klhk.css') }}" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/floating-help.css') }}">
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/inline-fix.css') }}">
    <!-- /custome CSS Files -->

    <!-- Core JS files (file?) -->
    <script type="text/javascript" src="{{ asset('materialize/js/plugins/jquery-1.11.2.min.js') }}"></script>
    {{-- <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/main/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/ui/ripple.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/inputs/inputmask.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/extensions/jquery_ui/core.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/tags/tagsinput.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/inputs/maxlength.min.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/inputs/formatter.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/extensions/cookie.js') }}"></script>
    
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_floating_labels.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_validation_custome.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk-assets/js/app.js') }}"></script>
    <!-- /theme JS files -->

    <!-- Custome JS Files -->
    <script src="{{ asset('klhk-asset/dest-operator/klhk-assets/js/custom.js') }}"></script>
    <!-- /custome JS Files -->
    @if(env('APP_ENV') == 'production')
        @include('analytics')
    @endif
    @yield('additionalStyle')
</head>
<body class="klhk">
{{-- Loading --}}
<div class="loading">
    <div class="loading-content">
        <div class="spin">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <div class="loading-text">
            {{ trans("general.loading") }}
        </div>
    </div>
</div>
<!-- top bar -->
<div data-template="top_bar">@include('klhk.dashboard.company.top_bar')</div>
<!-- end top bar -->
<!-- Page content -->
<div class="page-content">
      <!-- Main sidebar -->
      <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
          <!-- Sidebar mobile toggler -->
          <div class="sidebar-mobile-toggler text-center">
              <a href="#" class="sidebar-mobile-main-toggle">
                  <i class="icon-arrow-left8"></i>
              </a>
              Navigation
              <a href="#" class="sidebar-mobile-expand">
                  <i class="icon-screen-full"></i>
                  <i class="icon-screen-normal"></i>
              </a>
          </div>
          <!-- /sidebar mobile toggler -->
    
          <!-- Sidebar content -->
          <div class="sidebar-content sidebar-fixed">

              <!-- Sidebar nav -->
              <div data-template="side_navbar">@include('klhk.dashboard.company.sidebar_layout')</div>
              <!-- /sidebar nav -->

          </div>
          <!-- /sidebar content -->
      </div>
      <!-- /main sidebar -->
      <!-- content-wrapper -->
      <div class="content-wrapper">
              
            {{-- Whole Content Start --}}
            <section id="content">
                @yield('breadcrumb')
                @yield('content')
                <div class="floating-help">
                    <div class="fixed-action-btn vertical">
                        <a class="btn-floating btn-large red">
                            {{-- <i class="large material-icons">help</i> --}}
                            <img src="{{ asset('klhk-asset/dest-operator/img/support.png')}}" class="support" alt="">
                        </a>
                        <ul>
                            {{-- <li>
                                <a class="btn-floating bg-indigo btn-large" onclick="introStartBtn()">
                                    <img src="{{ asset('klhk-asset/dest-operator/img/bulb.png') }}">
                                </a>
                                <span class="floating-label">Tutorial</span>
                            </li> --}}
                            <li>
                                <a class="btn-floating bg-indigo btn-large" href="https://api.whatsapp.com/send?phone=6281211119655"
                                    target="_black">
                                    <img class="whatsapp" src="{{ asset('klhk-asset/dest-operator/img/whatsapp.png') }}">
                                </a>
                                <span class="floating-label">{{ trans('dashboard_provider.whatsapp_us') }}</span>
                            </li>
                            <li style="margin-bottom: 5px">
                                <a class="btn-floating bg-indigo btn-large" href="https://medium.com/gomodo" target="_blank">
                                    <img src="{{ asset('klhk-asset/dest-operator/img/medium.png') }}">
                                </a>
                                <span class="floating-label">{{ trans('dashboard_provider.medium') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- Translate --}}
                <label id="preview_now_trans" data-translate="{{ trans('setting_provider.preview_now') }}"></label>
                <label id="no_connection_title" data-translate="{{ trans('general.no_connection_title') }}"></label>
                <label id="no_connection_desc" data-translate="{{ trans('general.no_connection_desc') }}"></label>
                <label id="try_again" data-translate="{{ trans('general.try_again') }}"></label>
                <label id="tinyMce_charCount" class="d-none" data-translate="{{ trans('general.char_count') }}"></label>
                <label id="pac-input-gamification-translate" data-translate="{{ trans('setting_provider.address_company_map') }}"></label>
            </section>
            <form id="form_ajax" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" value="{{$company->domain_memoria}}" id="gomodo_domain">
            </form>
            {{-- Whole Content End --}}
          
          <!-- Footer -->
          <footer data-template="footer" class="footer">@include('klhk.dashboard.company.footer')</footer>
      <!-- /footer -->
      </div>
      @include('dashboard.company.logout_modal')
      @include('klhk.dashboard.company.product.modal_cropping_image')
      <!-- end conten-wrapper -->
</div>
<!-- end page-content -->


@include('javascript')
<script>
    var errorMessage = "{{trans('general.whoops')}}";

    // use in check_pax.js
    var productErrorMessage = "{{ trans('product_provider.error_message') }}" ;
    var productErrorTitle = "{{ trans('product_provider.error_title') }}";
</script>
<!-- Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
{{-- <script type="text/javascript" src="{{ asset('klhk-asset/dest-operator/lib/js/bootstrap.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/core.js"></script>

<!-- Plugin -->
<script type="text/javascript" src="{{ asset('materialize/js/materialize.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/datedropper.js') }}"></script>
<script type="text/javascript" src="{{ asset('klhk-asset/dest-operator/lib/js/chosen.jquery.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('materialize/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{asset('klhk-asset/dest-operator/lib/js/bootstrap-datepicker.id.js')}}"
        charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('materialize/js/custom-script.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js"></script>
<script type="text/javascript" src="{{asset('js/tooltipster.bundle.min.js')}}"></script>
<script src="{{ asset('klhk-asset/dest-operator/lib/js/jquery.dataTables.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src="{{asset('js/intro.js')}}"></script>
<script src="{{asset('js/jquery-fab-button-custome.js')}}"></script>



<!-- Custom -->
<script type="text/javascript" src="{{ asset('materialize/js/custom-script.js') }}"></script>
    <!-- Gamification -->
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/inputs/touchspin.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3rfV14WCZO6iNH5iX37OltWufEx7AK4k&libraries=places"></script>
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce-charactercount.plugin.js') }}"></script>

<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/uploader_dropzone.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_inputs.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/image_cropper_setup.js') }}"></script>
{{--<script src="{{asset('js/gmap.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/gmap-gamification.js') }}"></script>--}}
    <!-- /gamification -->
<script>
    var is_klhk = true;
    var swal = swal.mixin({
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
    });

    // Loader
    let $ = jQuery;
    $(document).ready(function () {
        $('.btn-sidebar').click(function (e) {
            jQuery('.loading').addClass('show');
            if (e.ctrlKey || e.shiftKey || e.metaKey) {
                jQuery('.loading').removeClass('show');
            }
        });
    });

    // Toastr
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Tooltip by Mobile or Dekstop
    StartTooltipster();
    function StartTooltipster(){
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            jQuery(document).find('.tooltips').tooltipster({
                animation: 'fade',
                delay: 200,
                // theme: 'tooltipster-noir',
                trigger: 'click',
                interactive: true,
                maxWidth: 400,
                contentCloning: true
            });
        } else {
            jQuery(document).find('.tooltips').tooltipster({
                animation: 'fade',
                delay: 200,
                // theme: 'tooltipster-noir',
                trigger: 'hover',
                interactive: true,
                maxWidth: 400,
                contentCloning: true
            });
        }
    }

    if (typeof gomodo !== 'undefined') {
        if (gomodo.error !== undefined) {
            toastr.error(gomodo.error, '{{__('general.whoops')}}');
        }
        if (gomodo.success !== undefined) {
            toastr.success(gomodo.success);
        }
        if (gomodo.warning !== undefined) {
            toastr.warning(gomodo.warning);
        }
        if (gomodo.info !== undefined) {
            toastr.info(gomodo.info);
        }
    }

    // Prevent Char in Input Type Number
    const number_format = 'input[type="number"], input[type="tel"], .number'
    $(document).on('change keydown', number_format, function (e) {
        var t = $(this);
        var text = t.val().replace(/[^0-9]/g, '');
        t.val(text);
        if (!digitOnly(e)) {
            e.preventDefault();
        }
    })

    function digitOnly(e) {
        let allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'MetaLeft', 'MetaRight', 'v', 'a', 'c', 'x'];
        if (!allowed.includes(e.key)) {
            return false
        }
        return true
    }

    // Disable Paste in input type number
    function catchPaste(evt, elem, callback) {
        if (navigator.clipboard && navigator.clipboard.readText) {
            // modern approach with Clipboard API
            navigator.clipboard.readText().then(callback);
        } else if (evt.originalEvent && evt.originalEvent.clipboardData) {
            // OriginalEvent is a property from jQuery, normalizing the event object
            callback(evt.originalEvent.clipboardData.getData('text'));
        } else if (evt.clipboardData) {
            // used in some browsers for clipboardData
            callback(evt.clipboardData.getData('text/plain'));
        } else if (window.clipboardData) {
            // Older clipboardData version for Internet Explorer only
            callback(window.clipboardData.getData('Text'));
        } else {
            // Last resort fallback, using a timer
            setTimeout(function() {
            callback(elem.value)
            }, 100);
        }
    }
    $(document).on('paste', number_format, function (e) {
        e.preventDefault();
        let t = $(this);
        let val_before = t.val();

        t.val('');
        catchPaste(e, this, function(clipData) {
            var text = clipData.replace(/[^0-9]/g, '')
            if (t.prop('maxlength') != -1) {
                let max_length = t.prop('maxlength')
                let all_text = val_before + text
                t.val(all_text.slice(0, max_length))
            } else {
                if (val_before == '') {
                    t.val(text)
                } else {
                    t.val(val_before + text)
                }
            }
        });
    });
    // Disable Scroll in input type number
    $(document).on('wheel', number_format, function (e) {
        $(this).blur();
    });

    // Separator Check
    $(document).ready(function () {
        separator();
        unSeparator();
    })

    $(document).on('focus', '.format-money', function () {
        separator();
    })

    $(document).on('change blur', '.format-money', function () {
        separator();
        unSeparator();
    })

    // Remove dot separator
    function separator() {
        $('.format-money').each(function (i, e) {
            var thisMoney = $(e).val();
            let p = thisMoney.replace(/[^0-9\.]+/g, '');
            $(this).attr('inputmode', 'numeric');
            $(e).val(p);
        });
    }

    // Add dot separator
    function unSeparator() {
        $('.format-money').each(function (i, e) {
            if ($(e).val() != '') {
                var thisMoney = parseFloat($(e).val()).formatMoney();
                $(e).val(thisMoney);
            }
        });
    }

    Number.prototype.formatMoney = function (places, symbol, thousand, decimal, front) {
        places = !isNaN(places = Math.abs(places)) ? places : 0;
        symbol = symbol !== undefined ? symbol : "";
        thousand = thousand || ",";
        decimal = decimal || ".";
        let number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "";
        let j = i.length;
        if (j > 3) {
            j = j % 3;
        } else {
            j = 0;
        }

        front = front || 0;
        if (front === 0) {

            return negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "") + " " + symbol;
        } else {
            return symbol + ' ' + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
        }
    };

    // Prevent KeyDown timepicker
    // $('.timepicker').on('keydown change', function (e) {
    //     e.preventDefault();
    // })

    // disable mousewheel on a input number field when in focus
    // (to prevent Cromium browsers change the value when scrolling)
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
    })
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll')
    })

    function introStartBtn() {
        window.location.href = window.location.origin + '/company/dashboard?multipage=true';
    }

    // Max length 3 digit
    jQuery(document).on('keyup', '.max-3, .max-5', function (e) {
        if ($(this).hasClass('max-3')) {
            maxCharSlice($(this), 3)
        } else {
            maxCharSlice($(this), 5)
        }
    })

    function maxCharSlice(el, max) {
        let this_val = el.val(),
            sliced = this_val.slice(0, max);
        el.val(sliced)
    }

    // For update
    checkCount();

    function checkCount(p = 1) {
        jQuery('.loading').addClass('show');
        let type = $('.tab-full>a.active').data('type');
        $.ajax({
            url: '{{route('company.update.count')}}',
            dataType: 'json',
            data: {type, page: p},
            success: function (data) {
                jQuery('.loading').removeClass('show');
                var count_update_all = parseInt(data.result.unread_info_promo_count + data.result.unread_upcoming_features_count + data.result.unread_patch_notes_count + data.result.unread_new_features_count);
                if (count_update_all === 0) {
                    $('.badge.count-update-all').addClass('hidden');
                    $('.badge.count-update-all').text(count_update_all);
                } else {
                    $('.badge.count-update-all').text(count_update_all);
                }
                if(parseInt(data.result.unread_order_count) ===0){
                    $('.badge.count-update-order').addClass('hidden');
                    $('.badge.count-update-order').text(data.result.unread_order_count);
                }else{
                    $('.badge.count-update-order').text(data.result.unread_order_count);
                }
                // $('.count-update-all')
                if (data.result.unread_info_promo_count === 0) {
                    $('span.badge.count-info-promo').addClass('hidden');
                    $('span.count-info-promo').text(data.result.unread_info_promo_count);
                } else {
                    $('span.count-info-promo').text(data.result.unread_info_promo_count).removeClass('hidden');
                }
                if (data.result.unread_upcoming_features_count === 0) {
                    $('span.badge.count-upcoming-features').addClass('hidden');
                    $('span.count-upcoming-features').text(data.result.unread_upcoming_features_count);
                } else {
                    $('span.count-upcoming-features').text(data.result.unread_upcoming_features_count).removeClass('hidden');
                }
                if (data.result.unread_patch_notes_count === 0) {
                    $('span.badge.count-patch-notes').addClass('hidden');
                    $('span.count-patch-notes').text(data.result.unread_patch_notes_count);
                } else {
                    $('span.count-patch-notes').text(data.result.unread_patch_notes_count).removeClass('hidden');
                }
                if (data.result.unread_new_features_count === 0) {
                    $('span.badge.count-new-features').addClass('hidden');
                    $('span.count-new-features').text(data.result.unread_new_features_count);
                } else {
                    $('span.count-new-features').text(data.result.unread_new_features_count).removeClass('hidden');
                }
                let html = '';
                $('.tab-pane .inbox-data').empty();
                $.each(data.result.notifications.data, function (i, e) {
                    if (e.read_at !== null) {
                        html += '<div data-id="' + e.id + '" class="list-inbox read">\n';
                    } else {
                        html += '<div data-id="' + e.id + '" class="list-inbox">\n';
                    }

                    html += '<div class="row">' +
                        '<div class="col-auto">' + e.created_at +
                        '</div>' +
                        '<div class="col message-content">'
                            @if(app()->getLocale()=='id')
                        + e.title_indonesia +
                            @else
                                +e.title +
                            @endif
                                '</div>' +
                        '<div class="col-auto">';
                    if (e.read_at === null) {
                        html += '<span class="fa fa-envelope"></span>';
                    }
                    html += '</div>\n' +
                        '</div>\n' +
                        '</div>';
                })
                $('.tab-pane.active .inbox-data').append(html);
                $('.tab-pane.active .footer-inbox').html(data.result.view);
                $('.tab-pane.active .head-inbox span.total-notif').text(data.result.notifications.total);
            }
        })
    }

    // $('.date-picker').datepicker({
    //     language: 'id'
    // })

    // Floating Help button follow sidebar toggle
    $(document).on('click', '.sidebar-main-toggle', function(){
        if($('body').hasClass('sidebar-xs')) {
            $('.fixed-action-btn').css('left', '77px');
            $('.nav-link .support').css('margin-right', '1px')
        } else {
            $('.fixed-action-btn').css('left', '290px');
            $('.nav-link .support').css('margin-right', '18px')
        }
    })
</script>
<script>
    // Gamification Modal Start
    var stepComplete = 'business_type';
    var companyStatus = '';
    var currentCaption = '{!! trans('gamification.business-type.caption') !!}';
    var nextNotNull = true;

    checkProgressInit();
    function checkProgressInit() {
        $.ajax({
            url:"/achievement/progress/business-profile",
            dataType:'json',
            success:function(data){
                var achievement = parseInt(data.result.achievement_status);
                if (achievement === 0) {
                    $('#gamificationProgressbar').fadeIn('slow');
                }
            },
            error:function(e){
                console.log(e)
            }
        })
    }

    checkProgress();
    function checkProgress() {
        $.ajax({
            url:"/achievement/progress/business-profile",
            dataType:'json',
            success:function(data){
                var complete = parseInt(data.result.complete);
                var incomplete = parseInt(data.result.incomplete);
                var calc = 100 * complete / (complete+incomplete);
                var progressBar = $('.gamification-progress-bar');
                var progressBarValue = $('.gamification-progress-bar-percent');
                $('#completeStep').text(complete);
                $('#totalStep').text(complete+incomplete);
                progressBar.css('width', calc + '%')

                @if(session()->get('userLang') == 'en')
                    var orderLabel = '';
                    switch ($('#completeStep').text()) {
                        case '0':
                            orderLabel = '';
                            break;
                        case '1':
                            orderLabel = 'st';
                            break;
                        case '2':
                            orderLabel = 'nd';
                            break;
                        case '3':
                            orderLabel = 'rd';
                            break;
                        default:
                            orderLabel = 'th';
                    }
                    $('#completeStep').text(complete + orderLabel)
                @endif

                progressBarValue.text(Math.floor(calc) + '%');
                if (calc > 48) {
                    progressBarValue.addClass('text-white');
                }
                
                if (incomplete == 0) {
                    $('#gamificationProgressbar').fadeOut('slow');
                    return true
                }
                var card = $('#gamificationModal .modal-content');
                card.block({
                    message: '<i class="icon-spinner9 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'none'
                    }
                });
                $.ajax({
                    url:"/achievement/progress/business-profile/incomplete",
                    dataType: 'json',
                    success: function(data){
                        stepComplete = data.result.current.slug;
                        companyStatus = data.result.ownership_status;

                        nextNotNull = data.result.next != null

                        @if(app()->getLocale() =='id')
                            currentCaption = data.result.current.title_id;
                            if(nextNotNull) {
                                nextCaption = data.result.next.title_id;
                            }
                        @else
                            currentCaption = data.result.current.title_en;
                            if(nextNotNull) {
                                nextCaption = data.result.next.title_en;
                            }
                        @endif

                        if(nextNotNull) {
                            nextSlug = data.result.next.slug;
                        } else {
                            nextSlug = 'finish';
                            nextCaption = '{!! trans('gamification.success.caption') !!}';
                        }
                        showStep(stepComplete, currentCaption);
                        card.unblock();
                    },
                    error: function(e){
                        console.log(e);
                    }
                })
            },
            error:function(e){
                console.log(e)
            }
        })
    }

    function showStep(slug, show_caption) {
        var step = $('.step'),
            caption = $('.gamification-caption'),
            header = $('#gamificationModal').find('.modal-title'),
            currentStep = $('#' + slug + '_gamification').find('.step');
        step.css('display', 'none');
        currentStep.css('display', 'block');
        caption.css('display', 'none');
        header.empty();
        if (slug === 'seo') {
            header.append(show_caption + '<br><a href="https://medium.com/gomodo/beginilah-caranya-agar-website-anda-lebih-optimal-pahami-kegunaan-fitur-seo-di-gomodo-9733ea053671" class="text-primary" target="_blank">{{ trans("gamification.seo.seo_benefit") }}</a>');
        } else {
            header.append(show_caption);
        }
        // KYC
        // if(n === 8){
        //     if(companyStatus === 'corporate') {
        //         var personalForm = $('#personal');
        //         personalForm.hide();
        //         personalForm.find('.required').removeClass('required')
        //     } else {
        //         $('#corporate').hide();
        //     }
        // }
    }

    $(document).on('click', '.btn-next-step', function(e){
        e.preventDefault();
        var stepIndex = $('.step').index($(this).closest('.step'));
        let url;
        switch (stepIndex) {
            case 0:
                url = '/achievement/progress/business-profile/update/company-type'
                break;
            case 1:
                url = '/achievement/progress/business-profile/update/about-company'
                break;
            case 2:
                url = '/achievement/progress/business-profile/update/company-address'
                break;
            case 3:
                url = '/achievement/progress/business-profile/update/company-contact'
                break;
            case 4:
                url = '/achievement/progress/business-profile/update/company-logo'
                break;
            case 5:
                url = '/achievement/progress/business-profile/update/company-banner'
                break;
            case 6:
                url = '/achievement/progress/business-profile/update/company-seo'
                break;
            case 7:
                url = '/achievement/progress/business-profile/update/company-bank'
                break;
            // case 8:
            //     url = '/achievement/progress/business-profile/update/company-kyc'
            //     break;
        }
        if(stepIndex != $('.step').length - 1){
            if(stepValidation(e, stepIndex)){
                let form = $(this).closest('form.gamification');
                let data = new FormData(form[0]);
                data.append('_token', '{{ csrf_token() }}');
                var card = $('#gamificationModal .modal-content');
                card.block({
                    message: '<i class="icon-spinner9 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'none'
                    }
                });
                form.find('.border-danger').removeClass('border-danger');
                form.find('span.text-danger').remove();
                $.ajax({
                    url:url,
                    dataType:'json',
                    type: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success:function(data){
                        showStep(nextSlug, nextCaption);
                        card.unblock();
                        checkProgress();
                    },
                    error:function(e){
                        console.log(e.responseJSON.errors);
                        if (e.status === 422) {
                            $.each(e.responseJSON.errors, function (i, e) {
                                let selector = $('#gamificationModal').find('input[name^="' + i +'"], select[name^="' + i +'"], textarea[name^="' + i +'"]');
                                selector.addClass('border-danger');
                                $('<span class="form-text text-danger">' + e[0] +'</span>').insertAfter(selector.parent());
                            })
                        }
                        card.unblock();
                    }
                })
            }
        }
    })

    function stepValidation(e, stepIndex) {
        tinymce.triggerSave()
        var valid = true;
        var stepSection = $('.gamification .step').eq(stepIndex);
        var inputReq = stepSection.find('input.required');
        var textAreaReq = stepSection.find(' textarea.required');
        var selectReq = stepSection.find(' select.required');
        stepSection.find('.required').removeClass('border-danger');
        stepSection.find('.text-danger').remove();
        if(inputReq.length > 0) {
            inputReq.each(function(i, e){
                if ($(e).val() == '') {
                    $(e).addClass('border-danger');
                    $('<span class="form-text text-danger">{!! trans("gamification.validation.required") !!}</span>').insertAfter($(e).parent());
                    // $(e).parent().append('<div class="form-control-feedback text-danger"><i class="icon-cancel-circle2"></i></div>');
                    valid = false;
                } else {
                    if ($(e).prop('type') == 'email') {
                        let mail = $(e).val();
                        let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        if (!re.test(mail)) {
                            $(e).addClass('border-danger');
                            $('<span class="form-text text-danger">{!! trans("gamification.validation.not-valid-email") !!}</span>').insertAfter($(e).parent());
                            valid = false;
                        }
                    }
                }
            })
        }
        if(textAreaReq.length > 0) {
            textAreaReq.each(function(i, e){
                if ($(e).val() == '') {
                    $(e).addClass('border-danger');
                    $('<span class="form-text text-danger">{!! trans("gamification.validation.required") !!}</span>').insertAfter($(e).parent());
                    valid = false;
                }
            })
        }
        if(selectReq.length > 0) {
            selectReq.each(function(i, e){
                if ($(e).val() == '' || $(e).val() == null) {
                    $(e).addClass('border-danger');
                    $('<span class="form-text text-danger">{!! trans("gamification.validation.required") !!}</span>').insertAfter($(e).parent());
                    valid = false;
                }
            })
        }
        return valid
    }

    $(document).on('keydown', 'input, textarea', function(){
        var it = $(this);
        if(it.hasClass('border-danger')) {
            it.removeClass('border-danger');
            it.closest('.form-group-feedback').next().remove();
        }
    })
    $(document).on('change', 'select', function(){
        var it = $(this);
        if(it.hasClass('border-danger')) {
            it.removeClass('border-danger');
            it.closest('.form-group-feedback').next().remove();
        }
    })

    /* jquery handle custom-default-logo-gamify */
    $('[name="default-logo-gamify"]').on('change', function (e) {
        $('#default-logo-gamify').attr('checked', true).prop('checked', true);
        $('[name="default-logo-gamify"]').parent().addClass('not-selected');
        $(this).parent().removeClass('not-selected');
    });

    $('#gamificationModal').on('shown.bs.modal', function(){
        showStep(stepComplete, currentCaption);
        select2Init(true);
        InputsBasic.init();
        DropzoneUploader.init();
        $('.dropify').dropify({
            messages: {
                'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
                'error': "{{ trans('kyc.error') }}"
            }
        });
        countryIndonesia();
        croperProductImage();
        // Map
        $('body').append('<input id="pac-input-gamification" class="col-sm-9" type="text" placeholder="' + $('#pac-input-gamification-translate').data('translate') + '">')
        // initGamificactionMap();
        textEditorInit();
        StartTooltipster();
        $('html').addClass('overflow-hidden');
    })

    $('#gamificationModal').on('hidden.bs.modal', function(){
        $('#pac-input-gamification').remove();
        $('html').removeClass('overflow-hidden');
    })

    select2Init();
    function select2Init(destroy) {
        if (destroy) {
            $('.select-max-10').select2('destroy');
            $('.select-multiple-comma-max-100').select2('destroy');
            $('.select2-general').select2('destroy');
        }

        $('.select-max-10').select2({
            maximumSelectionLength: 10
        });
        $('.select-multiple-comma-max-100').select2({
            tags: true,
            "language": {
                "noResults": function(){
                    return "Tidak ada hasil";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            tokenSeparators: [','],
            maximumSelectionLength: 100
        });
        $('.select2-general').select2();
    }

    function countryIndonesia() {
        var country = 102;
        $.getJSON('{{ route('location.states') }}', {id_country: country})
            .done(function (data) {
                $('#state_search_gamification option').remove();
                $('#city_search_gamification option').remove();
                $('#state_search_gamification').select2('destroy');
                $('#city_search_gamification').append(new Option($("#city_search_gamification").data("placeholder"),''))
                $('#state_search_gamification').append(new Option($("#state_search_gamification").data("placeholder"),''))
                @if(app()->getLocale() =='id')
                $.each(data, function (index, item) {
                    $('#state_search_gamification').append(new Option(item.state_name, item.id_state))
                })
                @else
                $.each(data, function (index, item) {
                    $('#state_search_gamification').append(new Option(item.state_name_en, item.id_state))
                })
                @endif
                //   $('#state_search_gamification').append(html);
                $('#state_search_gamification').select2();
                @if(auth()->user()->company->city)
                    $('#state_search_gamification').val('{{auth()->user()->company->city->state->id_state}}')
                    $('#state_search_gamification').trigger('change');
                @endif
            });
    }

    $('#state_search_gamification').on('change', function () {
        var state = $('#state_search_gamification').val();
        $('#city_search_gamification option').remove();
        $('#city_search_gamification').select2('destroy');
        $('#city_search_gamification').append(new Option($("#city_search_gamification").data("placeholder"),''))
        if (state !== ''){
            $.getJSON('{{ route('location.cities') }}', {id_state: state})
                .done(function (data) {

                    @if(app()->getLocale() =='id')
                    $.each(data, function (index, item) {
                        $('#city_search_gamification').append(new Option(item.city_name, item.id_city))
                        // html+='<option>'+item.city_name+'</option>';
                    })
                    @else
                    $.each(data, function (index, item) {
                        $('#city_search_gamification').append(new Option(item.city_name_en, item.id_city))
                        // html+='<option>'+item.city_name+'</option>';
                    })
                    @endif
                    // $('#city_search_gamification').append(html);
                    $('#city_search_gamification').select2();
                    @if(auth()->user()->company->city)
                        $('#city_search_gamification').val('{{auth()->user()->company->city->id_city}}')
                        $('#city_search_gamification').trigger('change');
                    @endif
                });
        } else {
            $('#city_search_gamification').select2();
        }
    });

    function textEditorInit() {
        tinymce.init({
            selector: '.tiny_mce_gamification',
            menubar: false,
            content_style: "p {font-size: 1rem; }",
            plugins: ['charactercount', 'paste'],
            paste_as_text: true,
            elementpath: false,
            max_chars: 300, // max. allowed chars
            setup: function (ed) {
                var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
                ed.on('keydown', function (e) {
                    if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                    if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    return true;
                });
                ed.on('keyup', function (e) {
                    tinymce_updateCharCounter(this, tinymce_getContentLength());
                });
            },
            init_instance_callback: function () { // initialize counter div
                $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                tinymce_updateCharCounter(this, tinymce_getContentLength());
            },
            paste_preprocess: function (plugin, args) {
                var editor = tinymce.get(tinymce.activeEditor.id);
                var len = editor.contentDocument.body.innerText.length;
                var OriginalString = args.content;
                var text = OriginalString.replace(/(<([^>]+)>)/ig, "");
                if (len + text.length > editor.settings.max_chars) {
                    alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
                    args.content = text.slice(0, editor.settings.max_chars);
                } else {
                    tinymce_updateCharCounter(editor, len + text.length);
                }
            }
        });
        tinymce.init({
            selector: '.tiny_mce_gamification_notoolbar',
            menubar: false,
            content_style: "p {font-size: 1rem; }",
            plugins: ['charactercount', 'paste'],
            paste_as_text: true,
            toolbar: false,
            height: 70,
            elementpath: false,
            max_chars: 300, // max. allowed chars
            setup: function (ed) {
                var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
                ed.on('keydown', function (e) {
                    if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                    if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    return true;
                });
                ed.on('keyup', function (e) {
                    tinymce_updateCharCounter(this, tinymce_getContentLength());
                });
            },
            init_instance_callback: function () { // initialize counter div
                $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                tinymce_updateCharCounter(this, tinymce_getContentLength());
            },
            paste_preprocess: function (plugin, args) {
                var editor = tinymce.get(tinymce.activeEditor.id);
                var len = editor.contentDocument.body.innerText.length;
                var OriginalString = args.content;
                var text = OriginalString.replace(/(<([^>]+)>)/ig, "");
                if (len + text.length > editor.settings.max_chars) {
                    alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
                    args.content = text.slice(0, editor.settings.max_chars);
                } else {
                    tinymce_updateCharCounter(editor, len + text.length);
                }
            }
        });
    }
    function tinymce_updateCharCounter(el, len) {
        // $('#' + el.id).prev().find('.char_count').text(len + '/' + el.settings.max_chars);
        $('#' + el.id).prev().find('.char_count').text('{!! trans('dashboard_provider.max_char') !!} ' + el.settings.max_chars);
    }

    function tinymce_getContentLength() {
        return tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
    }

    // For input and textarea wordcounter
    function inputWordCounter(e) {
        var el_length = e.val().length;
        var el_maxlength = e.attr('maxlength');
        var el_have_counter = e.next().hasClass('word-counter-container');
        var append_counter = '<div class="word-counter-container" style="float: right; font-size: 0.75rem;">' + el_length + ' / ' + el_maxlength + '</div>';

        if (el_maxlength === undefined) {
            console.log('There is no maxlength attribute in this element!')
        }
        if(el_have_counter) {
            e.next().remove()
        }
        e.after(append_counter);
    }
    // Gamification Modal End
</script>

@yield('additionalScript')
@stack('script')
</body>
</html>
