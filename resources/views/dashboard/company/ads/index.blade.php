@extends('dashboard.company.base_layout')
@section('additionalStyle')
    <link rel="stylesheet" href="{{ asset('dest-operator/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/steps.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">
    <link href="{{ url('css/component-custom-switch.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/dataTablesEdit.css')}}">
    <style>
        .error {
            color: red !important
        }
        .select2-container {
            text-align: left;
        }
        .form-ads .select2-container {
            width: 100%!important;
        }
        /* .select2-selection--multiple .select2-search__field {
            width: auto !important;
        } */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-top: 5px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: none;
            border-bottom: 1px solid #ced4da;
        }
        .select2-search__field{
            width: 600px !important;
        }
       .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: none;
            border-bottom: 1px solid #d0d0d0 !important;
            border-radius: 0 !important;
            height: 33px !important;
            padding-left: 0.28rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444 !important;
            line-height: 28px !important;
            /* margin-top: 10px !important; */
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 10px !important;
            position: absolute !important;
            top: 12px !important;
            right: 12px !important;
            width: 20px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            width: 100% !important;
        }
        .form-ads .select2-selection--multiple {
            padding-left: 0.28rem;
        }
        .dashboard-ads .available-adds .table-status-ads .btn-status{
            font-weight: 400;
        }
        @-moz-document url-prefix() {
            #btn-upload-image-text{
                margin-top: -3rem!important;
            }
        }

        .term-condition-group .error{
            margin-left: 1.5rem;
        }

        /* Bug Fix */
        .dashboard-ads {
            overflow-x: hidden;
        }
        .dashboard-ads .available-adds .card-my-voucher {
            -webkit-box-shadow: 0 3px 8px 1px rgba(38,153,251,.48)!important;
            box-shadow: 0 3px 8px 1px rgba(38,153,251,.48)!important;
            padding-bottom: 1rem;
        }
        .nav-tabs .nav-link {
            border-right: 1px solid #e1e0e1;
            border-top: 4px solid #e1e0e1;
            border-left: none;
        }
        .nav-tabs .nav-link:hover {
            border-color: #e2e0e2;
            border-bottom: white;
        }

        /* Intro JS City Debugging */
        .select2-container--open .select2-dropdown {
            z-index: 9999999!important;
        }

        .fb-call-button {
            margin-top: 0.7rem;
            margin-bottom: 0.7rem;
        }

        @media screen and (min-width: 800px) {
            .form-wizard section .container {
                width: 65%;
            }
            .form-wizard section.w75 .container {
                width: 75%;
            }
            .fb-content {
                max-width: 55%;
            }
            .fb-call-button {
                margin-bottom: 0;
                margin-top: 0;
            }
        }

        @media screen and (max-width: 820px){
            .dashboard-header .dashboard-language button {
                width: 100%;
            }
            .dashboard-header .dashboard-language .dropdown-menu {
                width: 100%;
            }
        }

        @media screen and (max-width: 480px){
            .introjs-tooltip.introjs-floating{
                top: -67px!important;
            }
        }
        .g-ads-preview {
            text-align: left;
            padding: 1rem;
            border: 1px solid #eee;
            background: #fff;
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            line-height: 1.15
        }
        .g-ads-preview a {
            font-size: 18px;
            color: #1a0dab;
            font-weight: normal;
        }
        .g-ads-preview a:hover {
            text-decoration: underline;
        }
        .g-ads-preview .ad {
            background-color: #fff;
            border-radius: 3px;
            color: #006621;
            display: inline-block;
            font-size: 11px;
            border: 1px solid #006621;
            padding: 1px 3px 0 2px;
            line-height: 11px;
            vertical-align: baseline;
        }
        .g-ads-preview .display-url {
            color: #006621;
            font-size: 14px;
            margin-left: 2px;
        }
        .g-ads-preview .description {
            font-size: small;
            color: #545454;
        }
        .error-help-block {
            font-size: 80%;
            color: #dc3545 !important;
        }
        #getvoucher {
            cursor: pointer;
        }
        .category-ads {
            display: inline-block;
            max-width: 47%;
        }

        .category-ads input[type=checkbox] { 
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        .category-ads input[type=checkbox] + img {
            cursor: pointer;
            border: 1px solid #555;
            border-radius: 10px;
            padding: 0.2rem 0.8rem;
        }

        /* CHECKED STYLES */
        .category-ads input[type=checkbox]:checked + img {
            background: #ddf0fc;
            border-color: #ebe9e9;
        }
        .bubble, .bubble-2 {
            text-align: left;
            background: #94a3e3;
            color: #fff;
            padding: 1rem;
        }
        .bubble-2 {
            background: #47baf4;
            padding: 0.8rem;
            margin-bottom: 1.3em;
            border-radius: 5px;
            text-align: center;
        }
        .bubble::before {
            content: ' ';
            position: absolute;
            width: 0;
            height: 0;
            left: -21px;
            right: auto;
            top: 0px;
            bottom: auto;
            border: 33px solid;
            border-top-color: currentcolor;
            border-right-color: currentcolor;
            border-bottom-color: currentcolor;
            border-left-color: currentcolor;
            border-color:  #94a3e3 transparent transparent transparent;
        }
        .bubble-2:after {
            content: ' ';
            position: absolute;
            width: 0;
            height: 0;
            left: 45%;
            right: auto;
            top: 50px;
            border-style: solid;
            border-color: #47baf4 transparent transparent transparent;
            border-width: 15px 17px 0 17px;
        }
        .fb-ads-preview .preview-content {
            font-size: 90%;
            color: #494949;
            word-wrap: break-word;
        }
        .fb-ads-preview a {
            color: #385898;
            font-weight: bold;
        }
        .fb-description-2 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            display: block;
        }

        .custom-file-input:lang({{ app()->getLocale() }}) ~ .custom-file-label::after {
            content: "{{ __('premium.fb_ig.form.document_ads.placeholder') }}";
            background: #4cb050;
            color: white;
            padding: 10px;
            border-radius: 3px;
            line-height: 1.3;
        }
        .tab-content {
            width: 100%;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
        font-family: "FontAwesome";
        content: "\f0fe";
        border: 1px solid #555;
        color: #555;
        box-shadow: none;
        box-sizing: no;
        background: #fff;
        font-size: 14px;
        top: 16px;
        height: 10px;
        width: 10px;
        line-height: auto;
        border: none;
      }
      @media screen and (min-width: 800px) {
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
          top: 17px;
        }
      }
      table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th:first-child:before {
        content: "\f146";
      }
      .btn-status {
        font-weight: 400 !important;
      }
    </style>
@endsection
@section('title', __('sidebar_provider.premium_store'))

@section('breadcrumb')
@endsection

@section('indicator_order')
    active
@endsection

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{trans('premium.title')}}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{trans('premium.title')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- main content -->
<div class="content pt-0" dashboard> 
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <div id="buy-premium">
            @include('dashboard.company.ads.buy_premium')
        </div>
        <div id="riview-buy-ads" style="display : none;">
            <form id="review-fb">
                @include('dashboard.company.ads.review_premium')
            </form>
        </div>
        <div class="d-none" id="data-error-form" data-validation="{{ trans('premium.blank_form') }}"></div>
        <!-- Modal -->
        {{-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="data-tableModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="modal-title" id="data-tableModalCenterTitle">Proses Menunggu Pesanan</h5>
                <img height="50" src="{{ asset('themes/admiria/images/preloader.gif') }}"/>
            </div>
            </div>
        </div>
        </div> --}}

    </div>
</div>

@endsection
@section('additionalScript')
{{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
    <script src="{{ asset('js/jquery.steps.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.js"></script>
    {{-- <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.min.js') }}"></script>
    <!-- INTRO JS FOR PREMIUM -->
    <script>
        function introjsstart(){
            introJs().setOption('keyboardNavigation', false).setOptions({
                steps: [
                    {
                        element : document.querySelector('.stepPremium1'),
                        intro: "<p>{{ trans('premium.facebook.intro.intro1') }}</p>",
                        position: 'top'
                    },{
                        element : document.querySelector('.stepPremium2'),
                        intro   : '<p>{{ trans('premium.facebook.intro.intro2') }}</p>',
                        position: 'top'
                    },
                    {
                        element : document.querySelector('.stepPremium3'),
                        intro : '<p>{{ trans('premium.facebook.intro.intro4') }} <a href="https://www.facebook.com/business/help/980593475366490" target="_blank">{{ trans('premium.facebook.intro.introLink') }}</a></p></p>',
                        position : 'top'
                    },{
                        element : document.querySelector('.stepPremium4'),
                        intro : '<p>{{ trans('premium.facebook.intro.intro3') }}'
                    },{
                        element : document.querySelector('.stepPremium5'),
                        intro : '<p>{{ trans('premium.facebook.intro.intro5') }}</p>'
                    }
                ]
            }).start()
        }

        function introjsstart2(){
            setTimeout(() => {
                introJs().setOption('keyboardNavigation', false).setOptions({
                    steps : [
                        {
                            element : document.querySelector('.stepPremium6'),
                            intro: "<p>{{ trans('premium.facebook.intro.intro6') }}</p>",
                            position: 'top'
                        },
                        {
                            element : document.querySelector('.stepPremium7'),
                            intro: "<p>{{ trans('premium.facebook.intro.intro7') }}</p>",
                            position: 'top'
                        },
                        {
                            element : document.querySelector('.stepPremium8'),
                            intro: "<p>{{ trans('premium.facebook.intro.intro8') }}</p>",
                            position: 'top'
                        },
                        {
                            element : document.querySelector('.stepPremium9'),
                            intro: "<p>{{ trans('premium.facebook.intro.intro9') }}</p>",
                            position: 'top'
                        },{
                            element : document.querySelector('.stepPremium10'),
                            intro: "<p>{{ trans('premium.facebook.intro.intro10') }}</p>",
                            position: 'top'
                        }
                    ]
                }).start()
            }, 1000);
        }
    </script>
    <!-- DATE FUNCTION -->
    <script>
        $(document).find('#test-only').on('click', function(){
            $(document).find('#buy-premium').hide();
            $(document).find('#riview-buy-ads').show();
        })
    </script>
    <script>
        window.$ = jQuery;
        $(document).ready(function() {

            $(document).find('input', 'textarea').attr('autocomplete', 'off');
            // $(document).find('#myModal').modal({
            //   show: false,
            //   backdrop: 'static',
            //   keyboard: false
            // });


            function loadingStart() {
                jQuery('.loading').addClass('show');
            }

            function loadingFinish() {
                jQuery('.loading').removeClass('show');
            }

            $(document).on('click', '#getvoucher', function () {
                // $(document).find('#nav-feauture').hide()
                $(document).find('#buy-premium').show();
                $(document).find('#nav-myvoucher').tab('show')
                $(document).find('#nav-my-voucher').tab('show')
                // $(document).find('#riview-buy-ads').hide();
                $(document).find('.promo-remove').hide();
                $(document).find('#remove-voucher').hide();

                $(document).find('#input_voucher, .google-ads-voucher').val('')
                $(document).find('input[name=code]').val('')

                $(document).find('label.error').remove();
                // scroll top
                if (navigator.userAgent.match(/(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini)/)) {
                    window.scrollTo(0,400)
                } else {
                    window.scrollTo(0,200)
                }
            })

            $(document).on('click', '.btn-buy-now', function(){
                clearInputAds();
            })

            // $(document).on('click', '#nav-my-voucher', function () {
            //   $(document).find('#nav-myvoucher').show()
            // })
            // $(document).on('click', '#nav-available-feauture', function () {
            //   $(document).find('#nav-feauture').show()
            // })
            // let totalTemporary = priceTotalComa;

            $(document).on('click', '#btn-promo-code', function(){
                loadingStart()
                let a = $(this)
                let vcr = a.closest('.voucher-section')
                let total = priceTotalComa;
                let tablePromo = $(document).find('#dataPrice tbody');
                tablePromo.find('#v-remove-promo').remove();


                $(document).find('label.error').remove();
                // $(document).find('input[name=grand_total]').val(priceTotalComa);

                $.ajax({
                    url: '{{ route('company.check.promocode') }}',
                    data: {
                        code: $(document).find('#input_voucher').val(),
                        total_price: total,
                        cash_back_id: $(document).find('input[name=cash_back_id]').val(),
                        gxp_value: $(document).find('input[name=gxp_value]').val(),
                        payment_method: $(document).find('select[name=payment_method]').val(),
                        grand_total: $(document).find('input[name=grand_total]').val(),
                    },
                    success: function(data){
                        $(document).find('#check-circle-promo').show();
                        // $(document).find('a').prop('disabled', true)
                        $(document).find('#input_voucher, .google-ads-voucher').prop('disabled', true);

                        // toastr.success(data.message)
                        if(data.gxpValue){

                            let html = '<tr id="v-remove-promo">'+
                                '<th scope="row">Voucher</th>'+
                                '<td id="price-discount-promo" class="dynamic-data-price" style="color:green">- IDR '+ data.promo_text+'</td>'+
                                '</tr>';
                            tablePromo.append(html);

                            $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(data.gxp_total))
                            $(document).find('#credit_amount').html('IDR '+ addCommas(data.credit_card))
                            $(document).find('input[name=grand_total]').val(data.gxp_total);

                            loadingFinish()
                        }else{

                            let html = '<tr id="v-remove-promo">'+
                                '<th scope="row">Voucher</th>'+
                                '<td id="price-discount-promo" class="dynamic-data-price" style="color:green">- IDR '+ data.promo_text+'</td>'+
                                '</tr>';
                            tablePromo.append(html);

                            change.codePromo = data.promo_amount;
                            change.additional = data.credit_card;
                            calculatePromo()
                            loadingFinish()
                        }
                        let inputPromo = '<input type="hidden" name="promo_codeid" value="'+data.promo_codeid+'">'
                        tablePromo.append(inputPromo);
                        $(document).find('input[name=code]').val($(document).find('#input_voucher').val())
                        $(document).find('input[name=promo_amount]').val(data.promo_amount)

                        $(document).find('#getvoucher').hide()
                        $(document).find('#nav-my-voucher').hide()
                        $(document).find('#nav-myvoucher').hide()
                        $(document).find('#remove-promo, .promo-remove').show()
                    },
                    error: function(e){
                        if(e.status == 422){
                            $.each(e.responseJSON.errors, function (i, e) {
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                toastr.error(e,'{{__('general.sorry')}}')
                            })
                            $(document).find('#v-remove-promo').remove()
                            $(document).find('input[name=promo_codeid]').remove()
                            // checked GXP false
                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#v-remove-gxp').remove()

                            $(document).find('input[name=code]').val('')
                            $(document).find('input[name=promo_amount]').val('')
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')
                            // $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(priceTotalComa))

                            let grand = 0;
                            let creditCard = 0;
                            let calcuGrand = 0;
                            if($(document).find('#credit_amount').data()){
                                grand = (priceTotalComa - change.codePromo)
                                creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                                calcuGrand = grand + creditCard;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }else{
                                calcuGrand = calcuGrand + change.additional;
                                calcuGrand = (priceTotalComa - change.codePromo);

                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }
                        }
                        if(e.status == 403){
                            $.each(e.responseJSON.errors, function (i, e) {
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                toastr.error(e,'{{__('general.sorry')}}')
                            })
                            // checked GXP false
                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))
                            $(document).find('input[name=gxp_balance]').val(balanceGxp)

                            $(document).find('input[name=code]').val('')
                            $(document).find('input[name=promo_amount]').val('')
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')
                            // $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(priceTotalComa))

                            let grand = 0;
                            let creditCard = 0;
                            let calcuGrand = 0;
                            if($(document).find('#credit_amount').data()){
                                grand = (priceTotalComa - change.codePromo)
                                creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                                calcuGrand = grand + creditCard;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }else{
                                calcuGrand = (priceTotalComa);
                                calcuGrand = calcuGrand + change.additional;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }
                        }
                        loadingFinish()
                    }

                });
            });

            // Use my voucher
            $(document).on('click', '#btn-use-voucher', function () {
                loadingStart()
                let t= $(this);
                let card = t.closest('.card.card-my-voucher')
                let id_voucher = card.find('input[name=id]').val();
                let total = priceTotalComa;
                let tableCashBack = $(document).find('#dataPrice tbody');
                tableCashBack.find('#v-remove-cashback').remove();
                // $(document).find('#totaly, #totaly2').html('IDR ' +addCommas(total));
                // $(document).find('input[name=grand_total]').val(total);
                $.ajax({
                    url: '{{ route('company.check.myvoucher') }}',
                    data: {
                        id: id_voucher,
                        total_price: total,
                        promo_codeid: $(document).find('input[name=promo_codeid]').val(),
                        grand_total: $(document).find('input[name=grand_total]').val(),
                        gxp_value: $(document).find('input[name=gxp_value]').val(),
                        payment_method: $(document).find('select[name=payment_method]').val(),
                    },
                    success: function(data){
                        if(data.gxpValue){
                            let html = '<tr id="v-remove-cashback">'+
                                '<th scope="row">Voucher</th>'+
                                '<td id="price-discount-cashback" class="dynamic-data-price" style="color:green">- '+ data.currency+ ' '+ data.myvoucher_text+'</td>'+
                                '</tr>';
                            tableCashBack.append(html);
                            $(document).find('#totaly, #totaly2').html('IDR '+addCommas(data.gxp_total));
                            $(document).find('input[name=grand_total]').val(data.gxp_total);
                            $(document).find('#credit_amount').html('IDR '+ addCommas(data.credit_card))
                            loadingFinish()
                        }else{
                            let html = '<tr id="v-remove-cashback">'+
                                '<th scope="row">Voucher</th>'+
                                '<td id="price-discount-cashback" class="dynamic-data-price" style="color:green">- '+ data.currency+ ' '+ data.myvoucher_text+'</td>'+
                                '</tr>';
                            tableCashBack.append(html);

                            change.cashBack = data.nominal;
                            change.additional = data.credit_card;
                            calculateCashBack()
                            loadingFinish()
                        }

                        // $(document).find('input[name=cash_back_id]').val(data.myvoucher_id);
                        $(document).find('#buy-premium').show();
                        $(document).find('#nav-available-feauture').tab('show');
                        // $(document).find('#riview-buy-ads').show()
                        $(document).find('.promo-remove').show();
                        $(document).find('#remove-voucher').show()
                        $(document).find('#getvoucher').hide()
                        $(document).find('input[name=myvoucher_id]').val(data.myvoucher_id)
                        $(document).find('input[name=cashback_amount]').val(data.nominal)

                        let inputCashBack = '<input type="hidden" name="cash_back_id" value="'+data.myvoucher_id+'">'
                        tableCashBack.append(inputCashBack);
                        $(document).find('#input_voucher, .google-ads-voucher').prop("disabled", true)
                        $(document).find('#btn-promo-code').prop("disabled", true)
                        // scroll top
                        if (navigator.userAgent.match(/(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini)/)) {
                            window.scrollTo(0,2000)
                        } else {
                            $(document).find('html,body').animate({
                                scrollTop: 200,
                                scrollLeft: 0
                            }, 1000, function(){
                                $(document).find('html,body').clearQueue();
                            });
                        }

                    },
                    error: function(e){
                        // toastr.error(e.responseJSON.message)
                        if (e.status === 422) {
                            $.each(e.responseJSON.errors, function (i, e) {
                                toastr.error(e,'{{__('general.sorry')}}')
                            })
                            // $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(priceTotalComa))
                            $(document).find('#v-remove-cashback').remove()
                            // checked GXP false
                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')
                            $(document).find('#gxp-total-balance').html('IDR '+addCommas(balanceGxp))
                            $(document).find('input[name=gxp_balance]').val(balanceGxp)

                            let grand = 0;
                            let creditCard = 0;
                            let calcuGrand = 0;
                            if($(document).find('#credit_amount').data()){
                                grand = (priceTotalComa - change.codePromo)
                                creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                                calcuGrand = grand + creditCard;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }else{
                                calcuGrand = (priceTotalComa - change.codePromo);
                                calcuGrand = calcuGrand + change.additional;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }
                        }
                        if(e.status === 403){
                            toastr.error(e.responseJSON.message, '{{__('general.sorry')}}')
                            // checked GXP false
                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')

                            $(document).find('#gxp-total-balance').html('IDR '+addCommas(balanceGxp))

                            let grand = 0;
                            let creditCard = 0;
                            let calcuGrand = 0;
                            if($(document).find('#credit_amount').data()){
                                grand = (priceTotalComa - change.codePromo)
                                creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                                calcuGrand = grand + creditCard;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }else{
                                calcuGrand = (priceTotalComa - change.codePromo);
                                calcuGrand = calcuGrand + change.additional;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }
                        }
                        if(e.status === 500){
                            toastr.error('Isikan budget anda','{{__('general.whoops')}}')
                            // checked GXP false
                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')

                            $(document).find('#gxp-total-balance').html('IDR '+addCommas(balanceGxp))
                            $(document).find('input[name=gxp_balance]').val(balanceGxp)

                            let grand = 0;
                            let creditCard = 0;
                            let calcuGrand = 0;
                            if($(document).find('#credit_amount').data()){
                                grand = (priceTotalComa - change.codePromo)
                                creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                                calcuGrand = grand + creditCard;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }else{
                                calcuGrand = (priceTotalComa - change.codePromo);
                                calcuGrand = calcuGrand + change.additional;
                                $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                                $(document).find('input[name=grand_total]').val(calcuGrand)
                            }
                        }
                        loadingFinish()
                    }
                })
            })

            // Payment method
            $(document).on('change', 'select[name=payment_method]', function(){
                let select = $(this)
                let promo = $(document).find('input[name=code]').val();
                $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                let promo_amount= $(document).find('input[name=promo_amount]').val()
                let cashback_amount= $(document).find('input[name=cashback_amount]').val()
                let grand_total= $(document).find('input[name=grand_total]').val()
                if(select.val() === 'credit_card'){
                    let tablePayment = $(document).find('#dataPrice tbody');
                    tablePayment.find('.creaditcard_changed').remove();

                    $.ajax({
                        url: '{{ route('company.check.creditcard') }}',
                        data: {
                            select: select.val(),
                            total_price: priceTotalComa,
                            code: $(document).find('input[name=code]').val(),
                            cash_back_id: $(document).find('input[name=cash_back_id]').val(),
                            grand_total: $(document).find('input[name=grand_total]').val(),
                            gxp_value: 0,
                            gxp_amount: $(document).find('input[name=gxp_amount]').val(),
                        },
                        success: function(data){
                            let html = '<tr class="creaditcard_changed">'+
                                '<th scope="row">Credit Card Charge (2.9 %)</th>'+
                                '<td id="credit_amount" class="dynamic-data-price">IDR '+ addCommas(data.credit_card) + '</td>'+
                                '<input type="hidden" id="credit" name="credit_card_amount" val="'+data.credit_card+'">'+
                                '</tr>';
                            tablePayment.append(html);
                            $(document).find('#gxp-balance').text('- IDR '+ addCommas(data.gxp_total))

                            $(document).find('input[name=credit_card_amount]').val(data.credit_card)

                            $(document).find('#totaly, #totaly2').html('IDR '+addCommas(data.gxp_total))
                            // $(document).find('#totaly, #totaly2').html('IDR 0')

                            $(document).find('input[name=grand_total]').val(data.gxp_total)
                            $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('#btn-promo-code').prop('disabled', false)
                            $(document).find('#input_voucher').prop('disabled', false)
                            // $(document).find('#getvoucher').show()
                            $(document).find('#nav-my-voucher').show()
                            $(document).find('#nav-myvoucher').show()


                        },
                        error: function(e){
                            toastr.error(e, '{{__('general.whoops')}}')
                        }
                    })

                }else{
                    $(document).find('.creaditcard_changed').remove()

                    $.ajax({
                        url: '{{ route('company.check.creditcard') }}',
                        data: {
                            select: select.val(),
                            total_price: priceTotalComa,
                            code: $(document).find('input[name=code]').val(),
                            cash_back_id: $(document).find('input[name=cash_back_id]').val(),
                            grand_total: $(document).find('input[name=grand_total]').val(),
                            gxp_value: $(document).find('input[name=gxp_value]').val(),
                            gxp_amount: $(document).find('input[name=gxp_amount]').val(),
                        },
                        success: function(data){

                            $(document).find('#totaly, #totaly2').html('IDR '+addCommas(data.gxp_total))
                            $(document).find('input[name=grand_total]').val(data.gxp_total)

                            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                            $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))
                            $(document).find('input[name=gxp_amount]').val('')
                            $(document).find('input[name=gxp_value]').val('')
                            $(document).find('#v-remove-gxp').remove()
                            $(document).find('#btn-promo-code').prop('disabled', false)
                            $(document).find('#input_voucher').prop('disabled', false)
                            let cashback = $(document).find('#price-discount-cashback').text();

                            if (cashback) {
                                $(document).find('#getvoucher').hide()
                                $(document).find('#nav-my-voucher').hide()
                                $(document).find('#nav-myvoucher').hide()
                            }
                            // $(document).find('#getvoucher').show()
                            // $(document).find('#nav-my-voucher').show()
                            // $(document).find('#nav-myvoucher').show()
                        },
                        error: function(e){
                            toastr.error(e, '{{__('general.whoops')}}')
                        }
                    })
                }
            })

            $(document).on('change', '#use-gxp, #use-gxp2', function(){
                let tableGxp = $(document).find('#dataPrice tbody');
                let switchStatus = false;
                let total = priceTotalComa;
                let promo_amount= $(document).find('input[name=promo_amount]').val()
                let cashback_amount= $(document).find('input[name=cashback_amount]').val()
                let grand_total= $(document).find('input[name=grand_total]').val()
                tableGxp.find('#v-remove-gxp').remove();
                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(total))
                // $(document).find('input[name=grand_total]').val(total)
                // $(document).find('#gxp-balance').html('- IDR ' + addCommas(balanceGxp))
                if($(this).is(':checked')){
                    switchStatus = $(this).is(':checked')
                    $(document).find('input[name=gxp_value]').val(1)
                    loadingStart()
                    $.ajax({
                        url: '{{ route('company.check.gxp') }}',
                        data: {
                            total_price: priceTotalComa,
                            promo_amount: promo_amount,
                            cashback_amount: $(document).find('input[name=cashback_amount]').val(),
                            grand_total: $(document).find('input[name=grand_total]').val(),
                            gxp_value : $(document).find('input[name=gxp_value]').val(),
                            payment_method: $(document).find('select[name=payment_method]').val(),
                        },
                        success: function(data){

                            if(data.gxp_amount >= data.grandTotal){
                                $(document).find('#gxp-total-balance').html('IDR '+ addCommas(data.gxp_total))
                                $(document).find('input[name=gxp_balance]').val(data.gxp_total)
                                $(document).find('#totaly, #totaly2').html('IDR '+ 0)
                                let dataTotal = $(document).find('#totaly, #totaly2').html('IDR '+ 0)
                                $(document).find('input[name=grand_total]').val(0)
                                // let calcuTotal = data.grandTotal - promo_amount;
                                // let calcuBalance = data.gxp_total - promo_amount;
                                // console.log(data.gxp_total)
                                let html = '<tr id="v-remove-gxp">'+
                                    '<th scope="row">Gxp Credits</th>'+
                                    '<td id="gxp-balance" class="dynamic-data-price" style="color: green;">- IDR '+addCommas(data.grandTotal)+ '</td>'+
                                    '</tr>';
                                tableGxp.append(html);
                                tableGxp.find('.creaditcard_changed').hide();

                                $(document).find('input[name=gxp_amount]').val(data.grandTotal)

                                if(promo_amount){
                                    // $(document).find('#input_voucher').prop("disabled", false)
                                    // $(document).find('#btn-promo-code').prop("disabled", false)
                                    $(document).find('#getvoucher').hide()
                                    $(document).find('#nav-my-voucher').hide()
                                    $(document).find('#nav-myvoucher').hide()
                                }else{
                                    // $(document).find('#input_voucher').prop("disabled", true)
                                    // $(document).find('#btn-promo-code').prop("disabled", true)
                                    // $(document).find('#getvoucher').hide()
                                    // $(document).find('#nav-my-voucher').hide()
                                    // $(document).find('#nav-myvoucher').hide()
                                }

                                if (dataTotal){
                                    $(document).find('#getvoucher').hide()
                                    $(document).find('#nav-my-voucher').hide()
                                    $(document).find('#nav-myvoucher').hide()
                                    $(document).find('.promo-remove').click();
                                    $(document).find('#input_voucher, .google-ads-voucher').prop("disabled", true)
                                    $(document).find('#btn-promo-code').prop("disabled", true)
                                    // $(document).find('select[name=payment_method]').prop('disabled', true)
                                }

                            }else{

                                $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(data.gxp_total))
                                $(document).find('#credit_amount').html('IDR '+ addCommas(data.credit_card))
                                $(document).find('#gxp-total-balance').html('IDR '+ 0)
                                $(document).find('input[name=gxp_balance]').val(0)
                                $(document).find('input[name=grand_total]').val(data.gxp_total)


                                let html = '<tr id="v-remove-gxp">'+
                                    '<th scope="row">Gxp Credits</th>'+
                                    '<td id="gxp-balance" class="dynamic-data-price" style="color: green;">- IDR '+addCommas(data.gxp_amount)+ '</td>'+
                                    '</tr>';
                                tableGxp.append(html);
                                $(document).find('input[name=gxp_amount]').val(data.gxp_amount)
                            }

                            $(document).find('input[name=gxp_value]').val(1)
                            // if(data.gxp_promo){
                            //   change.gxpTotal = data.gxp_promo;
                            // }else if(data.gxp_cashback){
                            //   change.gxpTotal = data.gxp_cashback;
                            // }else{
                            //   change.gxpTotal = data.gxp_total;
                            // }
                            // console.log(change.gxpTotal)
                            // calculateGxp()
                            loadingFinish()
                        },
                        error: function(e){
                            if(e.status == 403){
                                toastr.error(e.responseJSON.message,'{{__('general.whoops')}}')
                                $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
                                $(document).find('#check-circle-promo').hide();
                                $(document).find('#remove-promo, .promo-remove').hide()
                                $(document).find('#getvoucher').show()
                                $(document).find('.creaditcard_changed').remove()
                                $(document).find('#v-remove-promo').remove()
                                $(document).find('select[name=payment_method]').val('')
                                $(document).find('input[name=code]').val('')
                                $(document).find('input[name=promo_amount]').val('')
                                $(document).find('#input_voucher, .google-ads-voucher').prop('disabled', false);
                                $(document).find('#input_voucher, .google-ads-voucher').val('')
                                $(document).find('input[name=grand_total]').val(priceTotalComa)
                            }
                            loadingFinish()
                        }
                    })
                }else{
                    switchStatus = $(this).is(':checked')
                    tableGxp.find('.creaditcard_changed').show()
                    $(document).find('input[name=gxp_value]').val(0)
                    $(document).find('#btn-promo-code').prop('disabled', false)
                    $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))
                    $(document).find('input[name=gxp_balance]').val(balanceGxp)
                    $(document).find('input[name=gxp_value]').val('')
                    $(document).find('input[name=gxp_amount]').val('')
                    let promo = $(document).find('#price-discount-promo').text();
                    let cashback = $(document).find('#price-discount-cashback').text();
                    let gxpb = $(document).find('#gxp-balance').text();
                    let credit = $(document).find('#credit_amount').text();
                    let paymentAmount = $(document).find('input[name=credit_card_amount]').val();

                    if(promo){
                        let calcuGrand = (total - promo_amount);
                        if(credit){
                            let creditCard = Math.ceil(((100/97.1) * calcuGrand) -  calcuGrand);
                            calcuGrand = calcuGrand + creditCard;
                            $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                            $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                            $(document).find('input[name=grand_total]').val(calcuGrand)
                        }else{
                            $(document).find('#getvoucher').hide()
                            $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                            $(document).find('input[name=grand_total]').val(calcuGrand)
                        }
                    }else if(cashback){
                        $(document).find('#input_voucher, .google-ads-voucher').prop("disabled", true)
                        $(document).find('#btn-promo-code').prop("disabled", true)
                        $(document).find('#getvoucher').hide()
                        let calcuGrand = (total - cashback_amount);
                        if(credit){
                            let creditCard = Math.ceil(((100/97.1) * calcuGrand) -  calcuGrand);
                            calcuGrand = calcuGrand + creditCard;
                            $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                            $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                            $(document).find('input[name=grand_total]').val(calcuGrand)
                            // $(document).find('select[name=payment_method]').prop('disabled', false)
                        }else{
                            $(document).find('#getvoucher').hide()
                            $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                            $(document).find('input[name=grand_total]').val(calcuGrand)
                            // $(document).find('select[name=payment_method]').prop('disabled', false)
                        }
                    }else if(credit){
                        let creditCard = Math.ceil(((100/97.1) * total) -  total);
                        calcuGrand = total + creditCard;
                        $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                        $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                        $(document).find('input[name=grand_total]').val(calcuGrand)
                        $(document).find('#input_voucher, .google-ads-voucher').prop("disabled", false)
                        $(document).find('#btn-promo-code').prop("disabled", false)
                        $(document).find('#getvoucher').show()
                        $(document).find('#nav-my-voucher').show()
                        $(document).find('#nav-myvoucher').show()
                        // $(document).find('select[name=payment_method]').prop('disabled', false)
                    }else{
                        $(document).find('input[name=grand_total]').val(total)
                        $(document).find('#getvoucher').show()
                        $(document).find('#nav-my-voucher').show()
                        $(document).find('#input_voucher, .google-ads-voucher').prop("disabled", false)
                        $(document).find('#btn-promo-code').prop("disabled", false)
                        // $(document).find('select[name=payment_method]').prop('disabled', false)
                    }


                    // // let calcuGxp = balanceGxp - data.gxp_amount
                    // $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))


                    // change.gxpTotal = priceTotalComa;
                    // calculateGxp()
                    // calculatePromo()
                    // calculateCashBack()
                }

            });

            // TOGGLE SWITCH GXP BALANCE
            // $(document).find('#gxp-total-balance').html('IDR ' + addCommas(balanceGxp))
            // let switchStatus = false;
            // $(document).on('change', '#use-gxp', function(){
            //   // FUNCTION FOR TOGLE SWITCH
            //   let tableGxp = $(document).find('#dataPrice tbody');
            //   tableGxp.find('#v-remove-gxp').remove();
            //   $(document).find('#gxp-balance').html('- IDR ' + addCommas(balanceGxp))

            //   if($(this).is(':checked')){
            //     switchStatus = $(this).is(':checked')
            //     // priceTotal = balanceGxp - withGxpBalanceTotal
            //     if(balanceGxp >= priceTotalComa){
            //       // $(document).find('.price-total').html('IDR 0')
            //       // let balanceGxpRemains = balanceGxp - withGxpBalanceTotal
            //       let calcuGxp = balanceGxp - priceTotalComa;
            //       let html = '<tr id="v-remove-gxp">'+
            //                     '<th scope="row">Gxp Credits</th>'+
            //                     '<td id="gxp-balance" class="dynamic-data-price" style="color: green;">- IDR '+addCommas(priceTotalComa)+ '</td>'+
            //                   '</tr>';
            //       tableGxp.append(html);

            //       $(document).find('input[name=gxp_value]').val(1)
            //       $(document).find('#input_voucher').prop("disabled", true)
            //       $(document).find('#btn-promo-code').prop("disabled", true)
            //       $(document).find('#getvoucher').hide()
            //       $(document).find('#nav-my-voucher').hide()
            //       $(document).find('#nav-myvoucher').hide()

            //       $(document).find('#gxp-total-balance').html('IDR '+ addCommas(calcuGxp))
            //       $(document).find('#totaly, #totaly2').html('IDR '+ 0)

            //       change.gxpCredit = balanceGxp;
            //       if ($(document).find('input[name=cash_back_id').val()) {
            //         calculateCashBack()
            //       } else {
            //         calculatePromo()
            //       }

            //       // console.log(balanceGxpRemains)
            //     }else{
            //       let calculateGxp = 0;
            //       /* if(change.cashBack){
            //         calculateGxp = (priceTotalComa - change.cashBack) - balanceGxp;
            //       }else if(change.codePromo){
            //         calculateGxp = (priceTotalComa - change.codePromo) - balanceGxp;
            //       }else{
            //         calculateGxp = (priceTotalComa -balanceGxp);
            //       }
            //       console.log(calculateGxp) */
            //       // let calculateGxp = priceTotalComa - balanceGxp;
            //       let html = '<tr id="v-remove-gxp">'+
            //                     '<th scope="row">Gxp Credits</th>'+
            //                     '<td id="gxp-balance" class="dynamic-data-price" style="color: green;">- IDR '+addCommas(balanceGxp)+ '</td>'+
            //                   '</tr>';
            //       tableGxp.append(html);

            //       $(document).find('input[name=gxp_value]').val(1)
            //       $(document).find('#gxp-total-balance').html('IDR '+ 0)

            //       change.gxpCredit = balanceGxp;
            //       if ($(document).find('input[name=cash_back_id').val()) {
            //         calculateCashBack()
            //       } else {
            //         calculatePromo()
            //       }
            //       // if(priceTotal < 0){
            //       //   let priceTotalMinus = priceTotalComa - balanceGxp
            //       //   $(document).find('#gxp-balance').html('- IDR ' + addCommas(balanceGxp))
            //       //   $(document).find('.price-total').html('IDR ' + addCommas(priceTotalMinus))
            //       //   console.log(priceTotalMinus)
            //       // }else{
            //       //   $(document).find('.price-total').html('IDR ' + addCommas(priceTotal))
            //       // }
            //     }

            //     return true;
            //   }else{
            //     switchStatus = $(this).is(':checked')
            //     $(document).find('#gxp-balance').remove()
            //     // let priceTotalPlus = priceTotalComa
            //     $(document).find('input[name=gxp_value]').val('')

            //     $(document).find('#input_voucher').prop("disabled", false)
            //     $(document).find('#btn-promo-code').prop("disabled", false)
            //     if($(document).find('input[name=code').val()){
            //       $(document).find('#getvoucher').hide()
            //     }else{
            //       $(document).find('#getvoucher').show()
            //     }
            //     $(document).find('#nav-my-voucher').show()
            //     $(document).find('#nav-myvoucher').show()

            //     $(document).find('#gxp-total-balance').html('IDR '+ addCommas(balanceGxp))
            //     change.gxpCredit = 0;
            //     if ($(document).find('input[name=cash_back_id').val()) {
            //         calculateCashBack()
            //       } else {
            //         calculatePromo()
            //       }
            //     // $(document).find('.price-total').html('IDR ' + addCommas(priceTotalPlus))
            //   }
            // })

            $(document).on('click', '#remove-voucher, .promo-remove', function(){
                $(this).hide()
                $(document).find('#getvoucher').show();

                $(document).find('#v-remove-cashback').remove()
                // $(document).find('.price-total').html('IDR '+ addCommas(priceTotalComa))
                $(document).find('input[name=cash_back_id]').remove()
                $(document).find('#btn-promo-code').prop("disabled", false)
                $(document).find('#input_voucher, .google-ads-voucher').val('').prop("disabled", false)
                $(document).find('input[name=cashback_amount]').val('')
                $(document).find('input[name=grand_total]').val('')

                let gxp_amount = $(document).find('input[name=gxp_amount]').val()
                if($(document).find('#gxp-balance').data()){
                    let calcuGrand = 0;
                    let creditCard = 0;
                    if($(document).find('#credit_amount').data()){
                        calcuGrand = priceTotalComa - gxp_amount;
                        creditCard = Math.ceil(((100/97.1) * calcuGrand) -  calcuGrand);
                        calcuGrand = calcuGrand + creditCard;
                        $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    }else{
                        calcuGrand = priceTotalComa - gxp_amount;
                    }

                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)

                }else{
                    let calcuGrand = 0;
                    let creditCard = 0;
                    if($(document).find('#credit_amount').data()){
                        creditCard = Math.ceil(((100/97.1) * priceTotalComa) -  priceTotalComa);
                        calcuGrand = priceTotalComa + creditCard;
                        $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                        $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                        $(document).find('input[name=grand_total]').val(calcuGrand)
                    }else{
                        $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(priceTotalComa))
                        $(document).find('input[name=grand_total]').val(priceTotalComa)
                    }

                }

                // change.cashBack = 0
                // calculateCashBack()
            })

            $(document).on('click', '#remove-promo, .promo-remove', function(e){
                e.preventDefault()
                $(document).find('#check-circle-promo').hide();
                $(document).find('#input_voucher').removeAttr('disabled')
                $(this).hide()
                $(document).find('#getvoucher').show()
                $(document).find('#nav-my-voucher').show()
                $(document).find('#nav-myvoucher').show()
                $(document).find('#btn-promo-code').prop('disabled', false)
                $(document).find('label.error').remove();
                $(document).find('#v-remove-promo').remove()
                // $(document).find('.price-total').html('IDR '+ addCommas(priceTotalComa))
                $(document).find('input[name=promo_codeid]').remove()

                $(document).find('#input_voucher').val('')
                $(document).find('input[name=code]').val('')
                $(document).find('input[name=promo_amount]').val('')

                let gxp_amount = $(document).find('input[name=gxp_amount]').val()
                if($(document).find('#gxp-balance').data()){
                    $(document).find('#input_voucher').prop('disable', false)
                    let calcuGrand = 0;
                    let creditCard = 0;
                    if($(document).find('#credit_amount').data()){
                        $(document).find('#input_voucher').prop('disable', false)
                        calcuGrand = priceTotalComa - gxp_amount;
                        creditCard = Math.ceil(((100/97.1) * calcuGrand) -  calcuGrand);
                        calcuGrand = calcuGrand + creditCard;
                        $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    }else{
                        calcuGrand = priceTotalComa - gxp_amount;
                        // console.log(calcuGrand)
                    }

                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)

                }else{
                    let calcuGrand = 0;
                    let creditCard = 0;
                    if($(document).find('#credit_amount').data()){
                        creditCard = Math.ceil(((100/97.1) * priceTotalComa) -  priceTotalComa);
                        calcuGrand = priceTotalComa + creditCard;
                        $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                        $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                        $(document).find('input[name=grand_total]').val(calcuGrand)
                    }else{
                        $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(priceTotalComa))
                        $(document).find('input[name=grand_total]').val(priceTotalComa)
                    }

                }
                // change.codePromo = 0
                // calculatePromo()
            })

            //   formajax(jQuery('#form_ajax'), function (e) {
            //     if (e.status == 200) {
            //         swal({
            //             title: "Success",
            //             html: e.message,
            //             type: "success",
            //         }).then(function () {
            //             window.location = window.location+'?tab=my-premium'
            //         });
            //     } else {
            //         swal({
            //             title: "Oops...",
            //             text: e.message,
            //             type: "error",
            //         }).then(function () {
            //         });
            //     }
            // });

        });

        $(document).on('keyup paste change', 'input, select, textarea', function () {
            $(document).find(this).closest('.form-group').find('label.error').remove()
        });

        Number.prototype.formatMoney = function (places, symbol, thousand, decimal, front) {
            places = !isNaN(places = Math.abs(places)) ? places : 2;
            symbol = symbol !== undefined ? symbol : "$";
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
        $(".number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .(190)
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode == 187 && (e.shiftKey === true || e.metaKey === true)) ||
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode == 189 && (e.shiftKey === false || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode == 106 || e.keyCode == 110)) {
                e.preventDefault();
            }
        });
        $(document).on('blur', '.price', function () {
            let t  =$(this);
            let thisValue = t.val();
            let p = parseFloat(t.val().replace(/[^0-9-]/g, ''));
            if (t.hasClass('nullable')){
                if (isNaN(p)){
                    t.val('')
                }else{
                    t.val(p.formatMoney(0, '', ',', '', 1))
                }

            }else{
                t.val(p.formatMoney(0, '', ',', '', 1))
            }
        });
        $(document).ready(function () {
            $(".price").each(function (i) {
                let t  =$(this);
                let p = parseFloat(t.val().replace(/[^0-9-]/g, ''));
                if (t.hasClass('nullable')){
                    if (isNaN(p)){
                        t.val('')
                    }else{
                        t.val(p.formatMoney(0, '', ',', '', 1))
                    }

                }else{
                    t.val(p.formatMoney(0, '', ',', '', 1))
                }
            });
        });


        $(document).find('.btn-buy-now').click(function(){
            $(document).find('.carousel').hide();
            // Other ads
            $(document).find('.btn-howbuy-now').hide();
            $(document).find('.btn-buy-now').show();
            $(document).find('.button-submit-ads').hide();
            $(document).find('.form-ads').hide()
            // current ads
            let parent = $(this).closest('.available-adds');
            $(this).toggleClass('faded')
            parent.find('.btn-howbuy-now').show();
            parent.find('.btn-buy-now').hide();
            parent.find('.button-submit-ads').show()
            parent.find('.form-ads').show()
            $(document).find('#available-feature').css('margin-top', '2.5rem')
        });

        $(document).find('#how-to-buy-now').on('click', function(){
            $(document).find('#how-to-buy-now').attr('disabled', true)
            $(document).find('.intro-js-2').show()
            $(document).find('.no-intro').hide()
        })

        $(document).find('.intro-js-2').click(function(){
            introJs().exit()
            $(document).find('.intro-js-2').hide()
            $(document).find('.no-intro').show()
        })

        function readURL(input){
            if(input.files && input.files[0]){
                let reader = new FileReader();

                reader.onload = function(e){
                    $(document).find('.img-facebook-ads').attr('src', e.target.result);
                    $(document).find('img.step-5').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change', '#image-file-ads', function(){
            readURL(this);
        });

        $(document).on('keyup paste change', '#title-ads',function(){
            let ads = $(document).find("#title-ads").val();
            $(".title-facebook-ads").html(ads);
        });

        $(document).find('#url-ads').keypress(function(e){
            let keyCode = e.charCode || e.keyCode;
            if(keyCode === 32){
                return false;
            }
        });

        $(document).on('keyup paste change', '#url-ads',function(e){
            let ads = $(document).find("#url-ads").val();
            if (e.keyCode === 32)return false
            if(ads){
                $(".headline-first").html(ads);
            }else{
                $(".headline-first").html('(Default) '+'{{ $company->domain_memoria }}');
            }
        });



        $(document).on('keyup paste change', '#description-ads', function(){
            let descriptionAds = jQuery("#description-ads").val();
            $(".headline-second").html(descriptionAds.replace(/\n/g, "<br/>"));
        });

        $(document).on('keyup paste change', '#description-ads',function(){
            let descriptionAds = jQuery("#description-ads").val();
            $(".headline-second").html(descriptionAds.replace(/\n/g, "<br/>"));
        })

        $(document).find('#selectBtnValue').change(function(){
            let selectedText = $(this).children('option:selected').text();
            $(document).find('.button-learn-more').html(selectedText);
        })






        // TOTAL PRICE
        let date_diff_indays = function(date1, date2) {
            dt1 = new Date(date1);
            dt2 = new Date(date2);
            return Math.floor(((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24)) + 1);
        }

        let totalPrice = '';
        var date1 = '';
        var date2 = '';
        var totalDate = 1;
        let datePrice = '';
        let totalPriceDay = '';
        let finalPrice ='';
        let priceComa = '';
        let priceTotalComa = '';
        let discount = '';
        let serviceFee = '';
        let serviceFeeComa = '';
        let defaultServiceFee = 17500;
        let serviceFeeTotal = '';
        let gxpBalance = '';
        let change = {
            codePromo : 0,
            cashBack : 0,
            gxpCredit : 0,
            gxpTotal : 0,
            additional: 0
        };

        $(document).ready(function inOutDate(){
            @if(app()->getLocale() == 'id')
            $(document).find('.date-picker').datepicker({
                startDate : '+2d',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight : true,
                language: 'id'
            });
            @else
            $(document).find('.date-picker').datepicker({
                startDate : '+2d',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight : true,
                language: 'en'
            });
            @endif
            $(document).find('#date_start').on('changeDate', function(e){
                let endDate = $(document).find('#date_final')
                let newDate = new Date(e.date.valueOf())
                let date = ("0" + newDate.getDate()).slice(-2)
                let month = ("0" + (newDate.getMonth() + 1)).slice(-2)
                let year = newDate.getFullYear()
                let newDateValue = month  + '/' + date + '/' + year
                endDate.removeAttr('disabled')
                endDate.attr('readonly', true);

                endDate.datepicker('setStartDate', new Date(e.date.valueOf()));

                let start_date = $(document).find('input[name="start_date"]').val();
                let end_date = $(document).find('input[name="end_date"]').val();
                if(start_date > end_date){
                    endDate.val(newDateValue);

                    // console.log(true);
                }else {
                    // console.log(false);
                }

                // $(document).find('#final_date').datepicker('');
                // let totalDate = date_diff_indays(start_date, end_date);
                // console.log(totalDate)
                return;
            })

            let start_date = $(document).find('input[name="start_date"]').val();
            let end_date = $(document).find('input[name="end_date"]').val();

            $(document).find('.startend').html(start_date+ ' - '+ end_date + ' ('+ totalDate + 'Hari)')
        })

        $(document).ready(function(){
            let d = new Date();

            let monthNowOrder = d.getMonth()+1
            let dayNowOrder = d.getDate();
            let dateOrderToday = monthNowOrder + '/' + dayNowOrder + '/' + d.getFullYear()
            $(document).find('#date-order-today').html(dateOrderToday)
        })

        let balanceGxp = '{{ $gxp_sum['gxp'] }}';

        function calculateCashBack(){
            let calcuGrand = 0;
            let creditCard = 0;
            let grand = 0;

            if(change.cashBack || change.cashBack === 0) {
                let grand = (priceTotalComa - change.cashBack);
                grand = grand + change.additional;
                $(document).find('#totaly, #totaly2').html('IDR ' + addCommas(grand))
                $(document).find('input[name=grand_total]').val(grand)

                if($(document).find('#credit_amount').data()){
                    grand = (priceTotalComa - change.cashBack)
                    creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                    calcuGrand = grand + creditCard;
                    $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)
                }else{
                    calcuGrand = (priceTotalComa - change.cashBack);
                    calcuGrand = calcuGrand + change.additional;
                    $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)
                }
            }
        }
        function calculatePromo(){
            let calcuGrand = 0;
            let creditCard = 0;
            let grand = 0;

            if(change.codePromo || change.codePromo === 0){
                if($(document).find('#credit_amount').data()){
                    grand = (priceTotalComa - change.codePromo)
                    creditCard = Math.ceil(((100/97.1) * grand) -  grand);
                    calcuGrand = grand + creditCard;
                    $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)
                }else{
                    calcuGrand = (priceTotalComa - change.codePromo);
                    calcuGrand = calcuGrand + change.additional;
                    $(document).find('#credit_amount').html('IDR '+ addCommas(creditCard))
                    $(document).find('#totaly, #totaly2').html('IDR '+ addCommas(calcuGrand))
                    $(document).find('input[name=grand_total]').val(calcuGrand)
                }
            }
            // else{
            //   let grandC = (totalTemporary - change.cashBack) - change.gxpCredit;
            //   $(document).find('#totaly, #totaly2').html('IDR ' + addCommas(grandC))
            // }

        }

        // function calculateGxp(){
        //   $(document).find('#totaly, #totaly2').html('IDR ' + addCommas(change.gxpTotal))
        //   if(change.codePromo){
        //     let grand = change.gxpTotal - change.codePromo;
        //     // let gxpBalance = change.codePromo - change.gxpTotal;
        //     $(document).find('#totaly, #totaly2').html('IDR ' + addCommas(grand))
        //     $(document).find('input[name=grand_total]').val(grand)
        //     if(grand < 0){
        //       $(document).find('#totaly, #totaly2').html('IDR ' + 0)
        //       // $(document).find('#gxp-total-balance').html('IDR ' + addCommas(gxpBalance))
        //       // $(document).find('#gxp-balance').html('IDR ' + addCommas(change.gxp))


        //     }

        //   }else{
        //     let grand = change.gxpTotal - change.cashBack;
        //     $(document).find('#totaly, #totaly2').html('IDR ' + addCommas(grand))
        //     $(document).find('input[name=grand_total]').val(grand)
        //     // console.log(grand)
        //     if(grand < 0){
        //       $(document).find('#gxp-balance').html('IDR ' + addCommas(change.gxp))
        //       $(document).find('#totaly, #totaly2').html('IDR ' + 0)
        //     }
        //   }
        // }

        //$(document).find('input[name="start_date"]').on('changeDate', function(){
        $(document).on('changeDate', 'input[name=start_date]', function () {
            date1 = $(this).val();
            if(date1 > date2){
                date2 = $(this).val();
            }
        });

        //$(document).find('input[name="end_date"]').on('changeDate', function(){
        $(document).on('changeDate', 'input[name=end_date]', function () {
            date1 = $(document).find('input[name="start_date"]').val();
            date2 = $(this).val();
        });

        // WARN IF DISCOUNT EMPTY THE ANSWER WILL EMPTY
        //$(document).find('.date-picker').on('changeDate', function(){
        $(document).on('changeDate', '.date-picker', function () {

            totalDate = date_diff_indays(date1, date2);
            // console.log(totalDate);
            $(document).find('.startend').html(date1+ ' - ' + date2 + ' (' + totalDate + ' Hari)')
            datePrice = totalDate * totalPrice;
            // FIXED PRICE
            priceComa = addCommas(datePrice);
            $(document).find('.priceSubTotal').html('IDR ' + priceComa);
            // SERVICE FEE
            serviceFee = datePrice * 0.05;
            serviceFeeComa = addCommas(serviceFee);

            if(serviceFee === 0){
                $(document).find('.price-service-fee').html('IDR 0');
            }else if(serviceFee < 17500){
                $(document).find('.price-service-fee').html('IDR '+ addCommas(defaultServiceFee))
                priceTotalComa = (datePrice-discount)+defaultServiceFee;

                $(document).find('#service_fee, .service_fee').val(addCommas(defaultServiceFee))
            }else{
                $(document).find('.price-service-fee').html('IDR ' + serviceFeeComa);
                priceTotalComa = (datePrice-discount)+serviceFee;
                $(document).find('#service_fee, .service_fee').val(serviceFeeComa)
            }
            // DISCOUNT
            $(document).find('#price-discount').text('IDR ' + 0);
            if(datePrice === 0){
                $(document).find('#price-total1, .price-total1').text('IDR ' + 0);
            } else{
                $(document).find('#price-total1, .price-total1').text('IDR ' + addCommas(priceTotalComa));

            }
            // $(document).find('#price-total2').html('IDR ' + priceTotalComa);

            // Get value data backend
            $(document).find('#sub_total, .sub_total').val(priceComa)
            $(document).find('#total_price, .total_price').val(addCommas(priceTotalComa))

            $(document).find('#getvoucher').show()
            $(document).find('#remove-voucher').hide()
            $(document).find('#v-remove-promo').remove()
            $(document).find('#v-remove-cashback').remove()
            $(document).find('label.error').remove();

            $(document).find('input[name=cash_back_id]').remove()
            $(document).find('input[name=gxp_value]').val('')
            $(document).find('input[name=code]').val('')
            $(document).find('input[name=promo_amount]').val('')
            $(document).find('input[name=cashback_amount]').val('')
            $(document).find('#remove-promo, .promo-remove').hide()
            $(document).find('#input_voucher').val('')
            $(document).find('#input_voucher').prop('disabled', false)
            $(document).find('#btn-promo-code').prop("disabled", false)
            $(document).find('#gxp-total-balance').html('IDR ' +addCommas(balanceGxp))
            $(document).find('input[name=gxp_balance]').val(balanceGxp)

            // checked GXP false
            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
            $(document).find('#v-remove-gxp').remove()

            change.cashBack = 0
            change.codePromo = 0
            change.gxpCredit = 0
            calculateCashBack()
            calculatePromo()
            // ALERT IF PRICE BIGGER THAN 100.000.000
            if(totalPriceDay > 100000000){
                $(document).find('#alert-price').show()
            }else{
                $(document).find('#alert-price').hide()
            }
        })

        $(document).on('keyup paste change input', '#min-price, .google-min-budget',function(){
            totalPrice = $(this).val().replace(/,/g, '');
            datePrice = totalDate * totalPrice;

            // RESTRICT ALPHABET
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf(',')) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
            // FIXED PRICE
            priceComa = addCommas(datePrice);
            $(document).find('.priceSubTotal').html('IDR ' + priceComa );
            // SERVICE FEE
            serviceFee = datePrice * 0.05;
            serviceFeeComa = addCommas(serviceFee);
            if(serviceFee <= 0){
                $(document).find('.price-service-fee').html('IDR 0')
                $(document).find('#service_fee, .service_fee').val(0)
                priceTotalComa = 0
            }else if(serviceFee < 17500){
                $(document).find('.price-service-fee').html('IDR ' + addCommas(defaultServiceFee))
                priceTotalComa = (datePrice-discount)+defaultServiceFee;

                $(document).find('#service_fee, .service_fee').val(addCommas(defaultServiceFee))
            }else{
                $(document).find('.price-service-fee').html('IDR ' + serviceFeeComa)
                priceTotalComa = (datePrice-discount)+serviceFee;

                $(document).find('#service_fee, .service_fee').val(serviceFeeComa)
            }
            // DISCOUNT
            $(document).find('#price-discount').text('IDR ' + 0);
            $(document).find('#price-total1, .price-total1').text('IDR ' + addCommas(priceTotalComa));

            // Get value data backend
            $(document).find('#sub_total, .sub_total').val(priceComa)
            $(document).find('#total_price, .total_price').val(addCommas(priceTotalComa))

            $(document).find('#getvoucher').show()
            $(document).find('#nav-my-voucher').show()
            $(document).find('#nav-myvoucher').show()
            $(document).find('#remove-voucher').hide()
            $(document).find('#v-remove-promo').remove()
            $(document).find('#v-remove-cashback').remove()
            $(document).find('label.error').remove();

            $(document).find('input[name=cash_back_id]').remove()
            $(document).find('input[name=gxp_value]').val('')
            $(document).find('input[name=gxp_amount]').val('')
            $(document).find('input[name=code]').val('')
            $(document).find('input[name=promo_amount]').val('')
            $(document).find('input[name=cashback_amount]').val('')
            $(document).find('#remove-promo, .promo-remove').hide()
            $(document).find('#input_voucher').val('')
            $(document).find('#input_voucher').prop('disabled', false)
            $(document).find('#btn-promo-code').prop('disabled', false)
            $(document).find('#gxp-total-balance').html('IDR ' +addCommas(balanceGxp))

            // checked GXP false
            $(document).find('#use-gxp, #use-gxp2').prop('checked', false)
            $(document).find('#v-remove-gxp').remove()

            change.cashBack = 0
            change.codePromo = 0
            change.gxpCredit = 0
            calculateCashBack()
            calculatePromo()

            if(totalPrice < 17500){
                $(document).find('#small-min-price').show()
                $(document).find('#small-min-price').html('Min anggaran Rp. 17.500')
            }else{
                $(document).find('#small-min-price').hide()
                let a = totalPrice % 17500;
                let b = totalPrice - a;
                totalPriceDay = b / 17500;
            }
            return true;
        })


        // function convertToMoney(angka){
        //   let rupiah = '';
        //   let angkarev = angka.toString().split('').reverse().join('');
        //   for(let i = 0; i < angkarev.length; i++)
        //   if(i%3 == 0) rupiah += angkarev.substr(i,3)+',';
        //   return 'Rp. ' + rupiah.split('', rupiah.length-1).reverse().join('');
        // }

        function RemoveRougeChar(convertString){
            if(convertString.substring(0,1)== ","){
                return convertString.substring(1, convertString.length)
            }
            return convertString;
        }

        function addCommas(nStr) {
            nStr += '';
            let x = nStr.split('.');
            let x1 = x[0];
            let x2 = x.length < 1 ? '.' + x[1] : '';
            let rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }


        // $(document).find('#input_voucher').keypress(function(e){
        //   let regex = new RegExp("^[a-zA-Z0-9]");
        //   let str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        //   if(regex.test(str)){
        //     return true;
        //   }

        //   e.preventDefault();
        //   return false;

        //   // let voucherKey = 'ABCD123';
        //   // let value = $(this).val()
        //   // if(value === voucherKey){
        //   //   discount = 10000;
        //   //   console.log
        //   // }
        // }).keyup(function(){
        //   let valueVoucher = $(this).val()
        //   if(valueVoucher === discountVoucherCode){
        //     console.log('voucher code true')
        //   }else{
        //     console.log('voucher code false')
        //   }
        // })

        // GET FILE NAME

        $(document).ready(function changeFileName(){
            $(document).find('input[type="file"]').change(function(e){
                let fileName = e.target.files[0].name;
                $(document).find('#file-name').val(fileName);
            })
        })

        $(document).on('change', '.custom-file-input', function(e) {
            let file_name = e.target.files[0].name;
            console.log(file_name);
            $(document).find("label[for='" + $(this).attr('id') + "']").text(file_name);
        });
        {{-- // IMAGE VALIDATION
        function detailImage(files){
            let reader = new FileReader();
            let img = new Image();

            reader.onload = function(e){
                img.src = e.target.result;
                fileSize = Math.round(files.size / 1024);
                if(fileSize > 2048){
                    $(document).find('#file-size-image').show();
                    $(document).find('#file-size-image').html('File size must be less than 2 MB.')
                }else{
                    $(document).find('#file-size-image').hide();
                }
            }
            reader.readAsDataURL(files);
        }

        $(document).on('change', '#image-file-ads', function(){
            let file = this.files[0];
            detailImage(file);
        }) --}}

    </script>

    <script>
        window.$ = jQuery;
        $(document).ready(function() {
            $(document).find('[data-toggle="tooltip"]').tooltip();
            $(".js-select2").select2({
                ajax: {
                    url: "{{ route('select2.city') }}",
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function (params) {
                        return {
                            keyword: params.term, // search term
                            // country: 102,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        let items = data.items.map(function(item) {
                            return {
                                id: item.id_city,
                                text: item.state.state_name+ ' - ' +item.city_name
                            }
                        })

                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                multiple: true,
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 3,
                maximumSelectionLength: 3,
                //   templateResult: formatRepo,
                //   templateSelection: formatRepoSelection
                language: {
                    searching: function () {
                        $('.select2-dropdown').css('display', 'block');
                        return '{{ trans("general.select2.searching") }}';
                    },
                    noResults: function () {
                        return '{{ trans("general.select2.no_result") }}';
                    },
                    inputTooShort: function(e) {
                        // var t = e.minimum - e.input.length;
                        // return "Masukkan " + t + " huruf lagi";
                        $('.select2-dropdown').css('display', 'none');
                        return '{{ trans("general.select2.searching") }}'
                    },
                    errorLoading: function() {
                        return '{{ trans("general.select2.no_result") }}';
                    },
                }
            });

            function formatRepo (repo) {
                if (repo.loading) {
                    return repo.text;
                }

                let markup = "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'>" + repo.city_name + "</div></div></div>";

                return markup;
            }
            function formatRepoSelection (repo) {
                return repo.id_city || repo.text;
            }


            $(".citysubmit").click(function(){
                let city = [];
                $.each($(".js-select2 option:selected"), function(){
                    city.push($(this).text());
                });
                $(document).find("#idcityname").val(city.join(", "));
                // console.log(count)

                // for(i = 0; i<=2; i++){
                //   console.log('citysubmit')


                // }
                // return false
                // if($(this).click() === true){
                //   totalClick +1
                //   return
                // }
                // let totalClick = 0;
                // console.log(totalClick)
                // console.log(parseInt($(this))totalClick+1)

            });


            $(document).on('click', '.btn-review', function(){ 
                $(document).find('#buy-premium').hide();
                $(document).find('#riview-buy-ads').show();

                // DISABLED GXP WHEN TOTAL PRICE IS 0
                let totalPriceRiview = (datePrice-discount)+serviceFee;
                let valueGxp = $(document).find('#gxp-total-balance-hidden').val();
                if(totalPriceRiview == 0 || valueGxp == 0){
                    $(document).find('#use-gxp, #use-gxp2').prop("disabled", true)
                }else{
                    $(document).find('#use-gxp, #use-gxp2').prop("disabled", false)
                }
                // scroll top
                if (navigator.userAgent.match(/(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini)/)) {
                    window.scrollTo(0,200)
                } else {
                    $(document).find('html,body').animate({
                        scrollTop: 0,
                    }, 1000, function(){
                        $(document).find('html,body').clearQueue();
                    });
                }
            })

            $(document).find('#back-order-btn').on('click', function(){
                $(document).find('#buy-premium').show();
                $(document).find('#nav-available-feauture').tab('show');
                $(document).find('#nav-feauture').tab('show');
                $(document).find('#riview-buy-ads').hide();
            })
        });

        $(document).find('.form-google, .form-fb-ig').steps({
            headerTag: 'h3',
            bodyTag: 'section',
            autoFocus: true,
            transitionEffect: 'slideLeft',
            titleTemplate: '#title#',
            labels: {
                next: "{{ __('general.next') }}",
                previous: "{{ __('general.back') }}",
                finish: "{{ __('customer.book.pay_now') }}"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (currentIndex > newIndex) {
                    return true;
                }
                let ads_type = $(this).data('ads-type');
                let ignore = ':disabled,:hidden';

                if (ads_type == 'google') {
                    ignore = ignore + (currentIndex == 2 ? ':not(.google-min-budget-h)' : '');
                } else {
                    switch (currentIndex) {
                        case 0:
                            ignore = ignore + ':not(.form-fb-ig .category-ads input)';
                            break;
                        case 6:
                            ignore = ignore + ':not(.google-min-budget-h)';
                            break;
                    }
                }

                $(this).validate().settings.ignore = ignore;
                return $(this).valid();
            },
            onFinishing: function (event, currentIndex) {
                $(this).validate().settings.ignore = ':disabled';
                return $(this).valid();
            },
            onFinished: function (event, currentIndex) {
                $(document).find('.loading').addClass('show');
                let url = '{{ route('company.premium.store') }}'
                let ads_type = $(this).data('ads-type');
                let enctype = 'application/x-www-form-urlencoded';

                if (ads_type == 'google') {
                    url = "{{ route('company.premium.store.google') }}"
                } else {
                    enctype = 'multipart/form-data';
                }

                $.ajax({
                    url : url,
                    type: 'POST',
                    dataType: 'json',
                    data: new FormData($(this)[0]),
                    enctype: enctype,
                    processData: false,
                    contentType: false,
                    success: function(e) {
                         if (e.status == 200) {
                            swal({
                                title: e.success,
                                text: e.message,
                                type: "success",
                            }).then(function() {
                                window.location.href = e.data.url
                            });
                        } else {
                            swal({
                                title: e.oops,
                                text: e.message,
                                type: "error",
                            })
                        }

                        $(document).find('.loading').removeClass('show');
                    },
                    error: function (e) {
                        if (e.status === 422) {
                            $.each(e.responseJSON.errors, function (i, e) {
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                $(document).find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
                                $(document).find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                            })
                            $(document).find('a#steps-uid-0-t-0').click();
                        }
                        if(e.status === 403){
                            toastr.warning(e.responseJSON.message, '!')
                        }

                        $(document).find('.loading').removeClass('show');
                    }
                });
            }
        })
    </script>
    <script type="text/javascript">
        window.$ = window.jQuery;
        let language_url = '{{ asset("json/datatables_english.json") }}';
        @if(app()->getLocale()=='id')
            language_url = '{{ asset("json/datatables_indonesia.json") }}';

                @endif
        var dt = $(document).find('#data-table').dataTable(
            {
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "responsive": true,
                // "order": [ 2, 'asc' ],
                "language": {
                    "url": language_url
                },
                "ajax": {
                    url: "{{URL::current()}}",
                    type: "GET",
                    // data: function (d) {
                    //     d.status = $(document).find('select#status').val();
                    //     d.type = $(document).find('select#type').val()
                    // }
                },
                "columns": [
                    {
                        "data": "created_at",
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }
                    },
                    {
                        "data": "no_invoice", "orderable": false, "searchable": false

                    },
                    {
                        "data": "category", "orderable": false, "searchable": false

                    },
                    {
                        "data": "date", "orderable": false, "searchable": false

                    },
                    {
                        "data": "duration", "orderable": false, "searchable": false

                    },
                    {
                        "data": "action", "orderable": false, "searchable": false
                    },
                ],
                order: [[0, "desc"]],
            }
            );
    </script>

    <script>
        $(document).on('click','.unpaid', function () {
            let a = $(this);
            let modal = $(document).find('#modal-unpaid');
            modal.find('#id').text(a.data('id'))
            modal.find('#company').text(a.data('company'))
            modal.find('#username').text(a.data('username'))
            modal.find('#company_email').text(a.data('company_email'))
            modal.find('#phone_company').text(a.data('phone_company'))
            modal.find('.no_invoice').text(a.data('no_invoice'))
            modal.find('#category_ads').text(a.data('category_ads'))
            modal.find('#created_at').text(a.data('created_at'))
            modal.find('#method_payment').text(a.data('method_payment'))
            modal.find('#date').text(a.data('date'))
            modal.find('#end_date').text(a.data('end_date'))
            modal.find('#min_budget').text('IDR ' + a.data('min_budget'))
            modal.find('#amount').text('IDR ' + a.data('amount'))
            modal.find('#url').text(a.data('url'))
            modal.find('#service_fee, .service_fee').text('IDR ' + a.data('service_fee'))
            modal.find('#promo_voucher').text('- IDR ' + a.data('promo_voucher'))
            modal.find('#gxp_amount').text('- IDR ' + a.data('gxp_amount'))
            modal.find('#fee_credit_card').text('- IDR ' + a.data('fee_credit_card'))
            modal.find('.total_price').text('IDR ' + a.data('total_price'))
            modal.find('#payNow').attr('href', '{{ route('invoice-ads.virtual.account') }}' + '/'+a.data('no_invoice'))
            modal.modal();
        });

        $(document).on('click','#paid', function () {
            let a = $(this);
            let modal = $(document).find('#modal-paid');
            modal.find('#id').text(a.data('id'))
            modal.find('#company').text(a.data('company'))
            modal.find('#username').text(a.data('username'))
            modal.find('#company_email').text(a.data('company_email'))
            modal.find('#phone_company').text(a.data('phone_company'))
            modal.find('#no_invoice').text(a.data('no_invoice'))
            modal.find('#category_ads').text(a.data('category_ads'))
            modal.find('#created_at').text(a.data('created_at'))
            modal.find('#method_payment').text(a.data('method_payment'))
            modal.find('#date').text(a.data('date'))
            modal.find('#end_date').text(a.data('end_date'))
            modal.find('#min_budget').text('IDR ' + a.data('min_budget'))
            modal.find('#amount').text('IDR ' + a.data('amount'))
            modal.find('#url').text(a.data('url'))
            modal.find('#service_fee, .service_fee').text('IDR ' + a.data('service_fee'))
            modal.find('#promo_voucher').text('- IDR ' + a.data('promo_voucher'))
            modal.find('#gxp_amount').text('- IDR ' + a.data('gxp_amount'))
            modal.find('.total_price').text('IDR ' + a.data('total_price'))
            modal.modal();
        })

        $(document).on('click','#active', function () {
            let a = $(this);
            let modal = $(document).find('#modal-active');
            modal.find('#id').text(a.data('id'))
            modal.find('#company').text(a.data('company'))
            modal.find('#username').text(a.data('username'))
            modal.find('#company_email').text(a.data('company_email'))
            modal.find('#phone_company').text(a.data('phone_company'))
            modal.find('#no_invoice').text(a.data('no_invoice'))
            modal.find('#category_ads').text(a.data('category_ads'))
            modal.find('#created_at').text(a.data('created_at'))
            modal.find('#method_payment').text(a.data('method_payment'))
            modal.find('#date').text(a.data('date'))
            modal.find('#end_date').text(a.data('end_date'))
            modal.find('#min_budget').text('IDR ' + a.data('min_budget'))
            modal.find('#amount').text('IDR ' + a.data('amount'))
            modal.find('#url').text(a.data('url'))
            modal.find('#service_fee, .service_fee').text('IDR ' + a.data('service_fee'))
            modal.find('#promo_voucher').text('- IDR ' + a.data('promo_voucher'))
            modal.find('#gxp_amount').text('- IDR ' + a.data('gxp_amount'))
            modal.find('.total_price').text('IDR ' + a.data('total_price'))
            modal.modal();
        })

        $(document).on('click','#inactive', function () {
            let a = $(this);
            let modal = $(document).find('#modal-inactive');
            modal.find('#id').text(a.data('id'))
            modal.find('#company').text(a.data('company'))
            modal.find('#username').text(a.data('username'))
            modal.find('#company_email').text(a.data('company_email'))
            modal.find('#phone_company').text(a.data('phone_company'))
            modal.find('#no_invoice').text(a.data('no_invoice'))
            modal.find('#category_ads').text(a.data('category_ads'))
            modal.find('#created_at').text(a.data('created_at'))
            modal.find('#method_payment').text(a.data('method_payment'))
            modal.find('#date').text(a.data('date'))
            modal.find('#end_date').text(a.data('end_date'))
            modal.find('#min_budget').text('IDR ' + a.data('min_budget'))
            modal.find('#amount').text('IDR ' + a.data('amount'))
            modal.find('#url').text(a.data('url'))
            modal.find('#service_fee, .service_fee').text('IDR ' + a.data('service_fee'))
            modal.find('#promo_voucher').text('- IDR ' + a.data('promo_voucher'))
            modal.find('#gxp_amount').text('- IDR ' + a.data('gxp_amount'))
            modal.find('.total_price').text('IDR ' + a.data('total_price'))
            modal.modal();
        })

        // Google Ads
        $(document).find('.select2-ads').select2({
            width: '70%',
            maximumSelectionLength: 3
        });
        $(document).find('select[name=country]').on('change', function() {
            $(document).find('.state-loading').show();
            if ($(this).val() != null) {
                $.ajax({
                    url :"{{ route('location.states') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id_country: $(this).val()
                    },
                    success: function(response) {
                        $(document).find('.state-loading').hide();
                        $(document).find('.select-state option, .select-city option').remove();
                        $(document).find('.select-state, .select-city').val([]);
                        $(document).find('.select-state').prop('disabled', false);
                        $(document).find('.select-city').prop('disabled', true);
                        $.each(response, function(key, value) {
                            let state_name = value.state_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                            $(document).find('.select-state').append('<option value=' + value.id_state + '>' + state_name + '</option>');
                        });
                     }
                });
            }
        });
        $(document).find('.select-state').on('change', function() {
            $(document).find('.city-loading').show();
            if ($(this).val() != null) {
                $.ajax({
                    url :"{{ route('location.cities') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id_state: $(this).val()
                    },
                    success: function(response) {
                        $(document).find('.city-loading').hide();
                        $(document).find('.select-city').prop('disabled', false);
                        let selected_city = $(document).find('.select-city').select2('val');
                        $(document).find('.select-city option').remove();
                        $.each(response, function(key, value) {
                            let city_name = value.city_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                            $(document).find('.select-city').append('<option value=' + value.id_city + '>' + city_name + '</option>');
                        });

                        /** buang semua city terpilih jika tidak berada array, kondisi diperlukan jika state dibuang  */
                        // pertama tama ambil semua id city
                        let ids = $.map(response, function (value) {
                            return parseInt(value.id_city)
                        });
                        // lalu pilah pilih
                        if (selected_city != null) {
                            let exists = selected_city.filter(function(i) {
                                return ids.indexOf(parseInt(i)) !== -1;
                            });
                            $(document).find('.select-city').val(exists);
                        }
                     }
                });
            } else {
                $(document).find('.city-loading').hide();
                $(document).find('.select-city').val([]);
                $(document).find('.select-city').prop('disabled', true);
            }
        });
        $(document).find('.google-min-budget').on('change', function () {
            let value = $(this).val().replace(/\,/g, '').trim();
            $(document).find('.google-min-budget-h').val(value);
        });
        $(document).find('.gpreview').on('change', function () {
            $(document).find('.g-preview-' + $(this).data('gpreview')).text($(this).val());
        });
        $(document).find('.category-ads').on('click', function () {
            let el = $(document).find('#' + $(this).data('type') + '-ads-preview')
            if ($(this).children('input').is(':checked')) {
                el.show();
            } else {
                el.hide();
            }
        })
        $(document).find('.fb-preview').on('change', function () {
            let text;
            if ($(this).is('select')) {
                text = $(this).children('option:selected').text()
            } else {
                text = $(this).val();
            }

            $(document).find('.fb-preview-' + $(this).data('fb-preview')).text(text);
        });
        $(document).find('.google-ads-voucher').on('change', function () {
            $(document).find('#input_voucher').val($(this).val());
        });
        $(document).find('.promo-remove').on('click', function () {
            $(document).find('#remove-voucher').click();
            $(this).hide();
        });
        // Samakan
        $(document).find('select[name=payment_method]').on('change', function () {
            $(document).find('select[name=payment_method]').val($(this).val());
        });

        $(document).find('.form-google input[name=start_date]').on('changeDate', function (i) {
            let end_input = $(document).find('.form-google input[name=end_date]');

            let start_date = new Date(i.date.valueOf());
            $(document).find('#date_start').datepicker('update', start_date);
            if (end_input.val().length == 0) {
                return end_input.val($(this).val()).change();
            }
            let e = end_input.val().split('/');
            let end_date = new Date(e[2], parseInt(e[0]) - 1, e[1]);

            end_input.datepicker('setStartDate', start_date);

            if (start_date > end_date) {
                end_input.datepicker('update', start_date);
            }
        });

        $(document).find('.form-google input[name=end_date]').on('changeDate', function (i) {
            let end_date = new Date(i.date.valueOf());
            $(document).find('#date_final').datepicker('update', end_date);
        });

        function clearInputAds() {
            // Google
            $(document).find('input[name=url]').val('').change();
            $(document).find('.select-business-category').val('').change();
            $(document).find('select[name=country]').val('').change();
            $(document).find('.select-city').val([]).change();
            $(document).find('.select-language').val([]).change();
            $(document).find('.google-min-budget').val(0).change();
            $(document).find('select[name=title1]').val('').change();
            $(document).find('select[name=title2]').val('').change();
            $(document).find('select[name=description]').val('').change();
            $(document).find('select[name=phone_number]').val('').change();
            $(document).find('.promo-remove').click();
            $(document).find('select[name=payment_method]').val('').change();
            $(document).find('#use-gxp, #use-gxp2').prop('checked', false);
            $(document).find('.form-google')[0].reset();
            $(document).find('.steps ul li').removeClass('error').removeClass('done');
            $(document).find('.wizard .content li').removeClass('current').addClass('disabled');
            $(document).find('.wizard .content li.first').addClass('current').removeClass('disabled');
            $(document).find('li[role=tab]:not(.first)').addClass('disabled');
            $(document).find('a#steps-uid-0-t-0').click();
            date1 = '';
            date2 = '';
            totalDate = 1;
            $(document).find('.date-picker').change();
            $(document).find('.city-loading, .state-loading').hide();

            // Facebook
            $(document).find('.form-fb-ig')[0].reset();
            $(document).find('form small, .error-help-block, label.error').remove();
        }

        $(document).on('click', '#fb-ig .btn-buy-now', function(){
            $(document).find('html, body').animate({
                scrollTop: $(document).find('#fb-ig .steps').offset().top
            }, 300);
        })

        $(document).on('click', '#google .btn-buy-now', function(){
            $(document).find('html, body').animate({
                scrollTop: $(document).find('#google .steps').offset().top
            }, 300);
        })

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
         if ($(e.target).attr('id') === 'nav-my-premium') {
            dt.api().responsive.recalc();
         }
        });
    </script>
    {!! JsValidator::formRequest('App\Http\Requests\GoogleAdsRequest', '.form-google') !!}
    {!! JsValidator::formRequest('App\Http\Requests\AdsRequest', '.form-fb-ig') !!}
@endsection
