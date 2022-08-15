@extends('klhk.dashboard.company.base_layout')

@section('title', __('sidebar_provider.updates'))

@section('additionalStyle')
    <link rel="stylesheet" href=" {{ asset('klhk-asset/css/update-info.css') }} "> 
    <style>
        iframe {
            width: 100% !important;
        }
    </style>
@stop

@section('breadcrumb')
@stop
@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{trans('sidebar_provider.updates')}}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{trans('sidebar_provider.updates')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->
<!-- main content -->
<div class="content pt-0" dashboard>
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('klhk.dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('klhk.dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <form action="#">
            <div class="widget card updates" id="updates">
                <div class="widget-content">
                    <div class="row tab">
                        <div class="col-md-3">
                            <ul class="nav nav-tabs" id="v-pills-tab" role="tablist">
{{--                                            <li class="nav-item col-12 tab-full">--}}
{{--                                                <a class="nav-link active" id="v-pills-info-promo-tab"--}}
{{--                                                   data-type="info_promo" data-toggle="tab" href="#v-pills-info-promo"--}}
{{--                                                   role="tab" aria-controls="v-pills-info-promo" aria-selected="true">{{trans('update.info_promo')}}<span class="badge count-info-promo hidden">4</span></a>--}}
{{--                                            </li>--}}
                                <li class="nav-item col-12 tab-full">
                                    <a class="nav-link active" id="v-pills-new-features-tab"
                                        data-type="new_features" data-toggle="tab"
                                        href="#v-pills-new-features" role="tab"
                                        aria-controls="v-pills-new-features" aria-selected="false">{{trans('update.new_features')}}<span class="badge count-new-features hidden badge-danger badge-pill float-right">6</span></a>
                                </li>
                                <li class="nav-item col-12 tab-full">
                                    <a class="nav-link" id="v-pills-patch-notes-tab" data-type="patch_notes"
                                        data-toggle="tab" href="#v-pills-patch-notes" role="tab"
                                        aria-controls="v-pills-patch-notes" aria-selected="false">{{trans('update.patch_notes')}}
                                        <span class="badge count-patch-notes hidden badge-danger badge-pill float-right">6</span></a>
                                </li>
                                <li class="nav-item col-12 tab-full">
                                    <a class="nav-link" id="v-pills-upcoming-features-tab"
                                        data-type="upcoming_features" data-toggle="tab"
                                        href="#v-pills-upcoming-features" role="tab"
                                        aria-controls="v-pills-upcoming-features" aria-selected="false">{{trans('update.upcoming_features')}}<span class="badge count-upcoming-features hidden badge-danger badge-pill float-right">6</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-9 bg-white no-padding">
                            <div class="tab-content" id="v-pills-tabContent">
{{--                                            <div class="tab-pane fade show active" id="v-pills-info-promo"--}}
{{--                                                 role="tabpanel" aria-labelledby="v-pills-info-promo-tab">--}}
{{--                                                <span class="cursor"><span--}}
{{--                                                            class="fa fa-caret-left back-btn"></span></span>--}}
{{--                                                <div class="head-inbox">--}}
{{--                                                    {{trans('update.info_promo')}}--}}
{{--                                                    <div class="float-right font-weight-normal col-md-auto unread">--}}
{{--                                                        <span class="count-info-promo "></span> {{trans('update.unread')}} <span class="total-notif"></span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="inbox-data">--}}

{{--                                                </div>--}}
{{--                                                <div class="detail-content">--}}

{{--                                                </div>--}}
{{--                                                <div class="footer-inbox">--}}

{{--                                                </div>--}}
{{--                                            </div>--}}
                                <div class="tab-pane fade show active" id="v-pills-new-features" role="tabpanel"
                                        aria-labelledby="v-pills-new-features-tab">
                                    <span class="cursor"><span class="fa fa-caret-left"></span></span>
                                    <div class="head-inbox">
                                        {{trans('update.new_features')}}
                                        <div class="float-right font-weight-normal unread">
                                            <span class="count-new-features "></span> {{trans('update.unread')}} <span class="total-notif"></span>
                                        </div>
                                    </div>
                                    <div class="inbox-data">

                                    </div>
                                    <div class="detail-content">

                                    </div>
                                    <div class="footer-inbox">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-patch-notes" role="tabpanel"
                                        aria-labelledby="v-pills-patch-notes-tab">
                                    <span class="cursor"><span class="fa fa-caret-left"></span></span>
                                    <div class="head-inbox">
                                        {{trans('update.patch_notes')}}
                                        <div class="float-right font-weight-normal unread">
                                            <span class="count-patch-notes"></span> {{trans('update.unread')}} <span class="total-notif"></span>
                                        </div>
                                    </div>
                                    <div class="inbox-data">

                                    </div>
                                    <div class="detail-content">

                                    </div>
                                    <div class="footer-inbox">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-upcoming-features" role="tabpanel"
                                        aria-labelledby="v-pills-upcoming-features-tab">
                                    <span class="cursor"><span class="fa fa-caret-left"></span></span>
                                    <div class="head-inbox">
                                        {{trans('update.upcoming_features')}}
                                        <div class="float-right font-weight-normal unread">
                                            <span class="count-upcoming-features "></span> {{trans('update.unread')}} <span class="total-notif"></span>
                                        </div>
                                    </div>
                                    <div class="inbox-data">

                                    </div>
                                    <div class="detail-content">

                                    </div>
                                    <div class="footer-inbox">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@stop
@section('additionalScript')
    <script>
        let page =1;
        $(document).ready(function () {
            $(document).on('click', '.cursor, .back-btn', function () {
                $(this).closest('.tab-pane').find('.detail-content').hide();
                $(this).closest('.tab-pane').find('.inbox-data').show(30);
                $(this).closest('.tab-pane').find('.footer-inbox').show(30);
                $('.cursor').hide();
            });
            $(document).on('click', '.list-inbox', function () {
                let t = $(this);
                $.ajax({
                    url: '{{route('company.update.data')}}',
                    dataType: 'json',
                    data: {id: t.data('id')},
                    success: function (data) {
                        let ht = '';
                        ht += '<div class="caption">' + data.result.title + '</div>' +

                            '<div>' + data.result.content + '</div>';
                        t.closest('.tab-pane').find('.detail-content').html(ht)
                        t.closest('.tab-pane').find('.detail-content').fadeIn('slow');
                        t.closest('.tab-pane').find('.inbox-data').hide();
                        t.closest('.tab-pane').find('.footer-inbox').hide();
                        $('.cursor').show();
                        checkCount(page);
                    }
                })
            });
        });
        checkCount(page);
        
        // CheckCount Function Here

        $(document).on('click', '.pagination a',function(event)
        {
            event.preventDefault();

            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            let p=$(this).attr('href').split('page=')[1];
            page = p;

            checkCount(p);
        });
        $(document).on('click', '.pagination li',function(event)
        {
            event.preventDefault();

            if (!$(this).hasClass('active') && !$(this).hasClass('disabled')){
                $(this).find('a').trigger('click');
            }

        });
        setInterval(function () {
            checkCount(page);
        }, 60000)

        $(document).on('shown.bs.tab', '#v-pills-tab', function () {

            $('.detail-content').hide();
            $('.cursor').hide();
            checkCount();
            $('.tab-pane.active .inbox-data,.tab-pane.active .footer-inbox').show(30)
        })
        $(document).on('click', '.nav-item', function(){
            $('.cursor').trigger('click');
        })
    </script>

@endsection
