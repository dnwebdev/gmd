@extends('klhk.dashboard.company.base_layout')

@section('title', 'Dashboard')

@section('sidebar')
@stop

@section('additionalStyle')
    {{-- <link href="{{ asset('dest-operator/css/dashboard_provider.css') }}" rel="stylesheet"> --}}
@stop

@section('content')
    <!-- Page header -->
    <div data-template="main_content_header">
        <div class="page-header" style="margin-bottom: 0;">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title">
                    <h5>
                        {{-- <i class="icon-pushpin mr-2"></i> --}} {{ trans('dashboard_provider.dashboard') }}
                        {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                    </h5>
                </div>

                <div class="header-elements py-0">
                    <div class="breadcrumb">
                        <span class="breadcrumb-item active"><i class="icon-home2 mr-2"></i> {{ trans('dashboard_provider.dashboard') }}</span>
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
        <div data-template="banner-sugetion">@include('klhk.dashboard.company.add-on.banner-sugetion')</div>
        <!-- /banner Sugestion -->
        <div data-template="widget">


            <!-- tab  -->
            <div class="row">
                <!-- Insight -->
                <div class="col-xl-9">
                    <div class="card insight h-100">
                        <div class="card-header font-weight-bold">
                            <h2>{{ trans('dashboard_provider.insights') }}</h2>
                        </div>
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                                    <!-- content tab here -->
                                    <!-- widget 1/ icon-moon -->
                                    <div class="row">

                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-box icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 class="font-weight-semibold mb-0" id="number_of_order">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.total_orders') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-wallet icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 id="total_saldo" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.saldo') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-chart icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 id="value_of_order" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.incoming_orders_value') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- widget 2 -->
                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-coins icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 id="total_paid" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.total_income') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-screen3 icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 id="total_paid_online" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.online_orders_value') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-xl-4 mb-3">
                                            <div class="card card-body h-100">
                                                <div class="media">
                                                    <div class="mr-3 align-self-center">
                                                        <i class="icon-cash3 icon-3x text-dark"></i>
                                                    </div>

                                                    <div class="media-body text-right">
                                                        <h3 id="total_paid_internal" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted">{{ trans('dashboard_provider.offline_orders_value') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

                <!-- Balance -->
                <div class="col-xl-3">
                    <div class="card h-100">

                        <div class="card-header font-weight-bold">
                            <h2>{{ trans('dashboard_provider.saldo') }}</h2>
                        </div>

                        <div class="card-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                                    <!-- content tab here -->
                                    <!-- widget 1/ icon-moon -->
                                    <div class="row">

                                        <div class="col-lg-6 col-xl-12">
                                            <div class="card card-body">
                                                <div class="d-flex align-items-center mb-3 mb-sm-0">
                                                    <div class="align-self-center">
                                                        <i class="icon-wallet icon-3x text-dark"></i>
                                                    </div>
                                                    <div class="media-body text-right">
                                                        <h3 id="saldo" class="font-weight-semibold mb-0">{{ trans('general.loading') }}</h3>
                                                        <span class="text-uppercase font-size-sm text-muted tooltips"
                                                              title="{{ trans('dashboard_provider.saldo_tooltip') }}">
                                            {{ trans('dashboard_provider.saldo') }}
                                            <span class="badge rounded-circle badge-icon p-0">
                                                <i class="icon-info22 font-size-xs"></i>
                                            </span>
                                        </span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('company.withdraw.index') }}"
                                                   class="btn bg-green-klhk legitRipple withdrawBtn mt-2">
                                                    <i class="icon-cash4 mr-2 iconTopBtn"></i>{{ trans('dashboard_provider.withdraw') }}
                                                    <div class="legitRipple-ripple"></div>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="col-lg-6 col-xl-12">

                                            <div class="card card-body mb-0">
                                                <div class="media">
                                                    <div class="media-body text-right">
                                                        <h3 class="font-weight-semibold mb-0 font-size-sm tooltips"
                                                            title="{{ trans('dashboard_provider.incoming_reimbursement_tooltip') }}">{{ trans('dashboard_provider.incoming_reimbursement') }}
                                                            <span class="badge rounded-circle badge-icon p-0">
                                                <i class="icon-info22 font-size-xs"></i>
                                            </span>
                                                        </h3>
                                                        <span id="reimbursement"
                                                              class="text-uppercase font-size-lg text-muted">{{ trans('general.loading') }}</span>
                                                    </div>
                                                </div>
                                                <div class="media">
                                                    <div class="media-body text-right">
                                                        <h3 class="font-weight-semibold mb-0 font-size-sm tooltips"
                                                            title="{{ trans('dashboard_provider.incoming_settlement_tooltip') }}">{{ trans('dashboard_provider.incoming_settlement') }}
                                                            <span class="badge rounded-circle badge-icon p-0">
                                                <i class="icon-info22 font-size-xs"></i>
                                            </span>
                                                        </h3>
                                                        <span id="settlement"
                                                              class="text-uppercase font-size-lg text-muted">{{ trans('general.loading') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mt-3">
                {{-- Upcoming Guest --}}
                <div class="col-xl-3 order-xl-2">
                    <div class="card">

                        <div class="card-header font-weight-bold">
                            <h2 class="tooltips" title="{{ trans('dashboard_provider.incoming_total_guest') }}">
                                {{ trans('dashboard_provider.total_guest') }}
                                <i class="icon-info22 font-size-xs"></i>
                            </h2>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                                    <!-- content tab here -->
                                    <!-- widget 1/ icon-moon -->
                                    <div class="row">

                                        <div class="col-lg-6 col-xl-12">
                                            <div class="card card-body">
                                                <div class="d-flex align-items-center mb-3 mb-sm-0">
                                                    <div class="align-self-center">
                                                        <i class="icon-people icon-3x text-dark"></i>
                                                    </div>
                                                    <div class="media-body text-right">
                                                        <h3 id="total-guest" class="font-weight-semibold mb-0">
                                                            {{ trans('general.loading') }}</h3>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-9 order-xl-1">
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            <h2>{{ trans('dashboard_provider.best_seller') }}</h2>
                        </div>
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                                    <!-- content tab here -->
                                    <!-- widget 1/ icon-moon -->
                                    @if ($topViewed->isNotEmpty())
                                        <div class="row">

                                            <div class="table-responsive">
                                                <table class="table text-nowrap">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ trans('dashboard_provider.table_product_name') }}</th>
                                                        <th>{{ trans('dashboard_provider.table_location') }}</th>
                                                        <th>{{ trans('dashboard_provider.total_orders') }}</th>
                                                        <th class="text-center" style="width: 20px;"><i
                                                                    class="icon-arrow-down12 d-none"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($topViewed as $row)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <a href="{{route('company.product.edit',['id'=>$row->id_product])}}"
                                                                           class="text-green-klhk font-weight-semibold">{{$row->product_name}}</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @if($row->city)
                                                                <td>
                                                                    <span class="text-default"> {{$row->city->city_name}}</span>
                                                                </td>
                                                            @endif
                                                            <td>

                                                                <span class="badge bg-green-klhk"> {{$row->order_detail_count}} {{__('dashboard_provider.booked')}}</span>

                                                            </td>
                                                            <td class="text-center">
                                                                <div class="list-icons">
                                                                    <div class="list-icons-item dropdown">
                                                                        <a href="#"
                                                                           class="list-icons-item dropdown-toggle caret-0"
                                                                           data-toggle="dropdown" aria-expanded="false"><i
                                                                                    class="icon-more2"></i></a>
                                                                        <div class="dropdown-menu dropdown-menu-right"
                                                                             x-placement="bottom-end"
                                                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(16px, 16px, 0px);">
                                                                            <a href="{{route('company.product.edit',['id'=>$row->id_product])}}"
                                                                               class="dropdown-item"><i
                                                                                        class="icon-file-stats"></i>
                                                                                {{ trans('dashboard_provider.view_detail') }}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!-- widget 2 -->
                                    @else
                                    <!-- top product empty state -->
                                        <div class="order_empty_state">
                                            <div class="empty_state_content">
                                                <img src="{{ asset('klhk-asset/dest-operator/klhk-assets/img/empty_order.svg')}}"/>
                                                <p class="empty_state_copy">
                                                    {!! trans('dashboard_provider.empty_top_product') !!}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- table order list start here -->
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            <h2>{{ trans('dashboard_provider.upcoming_guest') }}</h2>
                        </div>
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                                    <!-- content tab here -->
                                    <!-- widget 1/ icon-moon -->
                                    @if ($upcoming_book->isNotEmpty())
                                        <div class="row">

                                            <div class="table-responsive">
                                                <table class="table text-nowrap">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ trans('dashboard_provider.table_product_name') }}</th>
                                                        <th>{{ trans('dashboard_provider.table_date') }}</th>
                                                        <th>{{ trans('dashboard_provider.table_location') }}</th>
                                                        <th>{{ trans('dashboard_provider.table_quantity') }}</th>
                                                        <th>{{ trans('dashboard_provider.table_status') }}</th>
                                                        <th class="text-center" style="width: 20px;"><i
                                                                    class="icon-arrow-down12"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($upcoming_book as $row)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <a href="{{route('company.order.edit',['id'=>$row->invoice_no])}}"
                                                                           class="text-green-klhk font-weight-semibold">{{$row->order_detail->product_name}}</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">{{\Carbon\Carbon::parse($row->order_detail->schedule_date)->format('d M Y')}}</span>
                                                            </td>
                                                            @if($row->order_detail->city)
                                                                <td>
                                                                    <span class="text-default"> {{$row->order_detail->city->city_name}}</span>
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <h6 class="font-weight-semibold mb-0">{{$row->order_detail->adult}} {{optional($row->order_detail->unit)->name}}</h6>
                                                            </td>
                                                            <td>
                                                                @if($row->is_read==0)
                                                                    <span class="badge bg-green-klhk">{{trans('dashboard_provider.unread')}}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="list-icons">
                                                                    <div class="list-icons-item dropdown">
                                                                        <a href="#"
                                                                           class="list-icons-item dropdown-toggle caret-0"
                                                                           data-toggle="dropdown" aria-expanded="false"><i
                                                                                    class="icon-more2"></i></a>
                                                                        <div class="dropdown-menu dropdown-menu-right"
                                                                             x-placement="bottom-end"
                                                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(16px, 16px, 0px);">
                                                                            <a href="{{route('company.order.edit',['id'=>$row->invoice_no])}}"
                                                                               class="dropdown-item"><i
                                                                                        class="icon-file-stats"></i>
                                                                                {{ trans('dashboard_provider.view_detail') }}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!-- widget 2 -->
                                    @else
                                    <!-- order empty state -->
                                        <div class="order_empty_state">
                                            <div class="empty_state_content">
                                                <img src="{{ asset('klhk-asset/dest-operator/klhk-assets/img/empty_guest.svg')}}"/>
                                                <p class="empty_state_copy">
                                                    {!! trans('dashboard_provider.empty_upcoming_guest') !!}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- table order list start here -->
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end main content -->
@endsection


@section('additionalScript')
    <script type="text/javascript">
        $(document).ready(function () {
            $.getJSON("{{ route('company.dashboard.report') }}", function (data, status) {
                if (data.status == 200) {
                    $('#number_of_order').html(data.data.number_of_order);
                    $('#value_of_order').html(data.data.value_of_order);
                    $('#total_paid').html(data.data.total_paid);
                    $('#total_discount').html(data.data.total_discount);
                    $('#total_balance').html(data.data.total_balance);
                    $('#total_saldo').html(data.data.total_saldo);
                    $('#saldo').html(data.data.total_saldo);
                    $('#reimbursement').html(data.data.total_incoming_reimbursement);
                    $('#settlement').html(data.data.total_incoming_settlement);

                    $('#total_paid_internal').html(data.data.total_paid_internal);
                    $('#total_paid_online').html(data.data.total_paid_online);
                    $('#total-guest').html(data.data.total_guest);
                }
            });
            //         getReport();

            $('.item-display').each(function (i) {
                $('.item-display').eq(i).click(function () {
                    $('#main_image').css('background-image', '');

                    $('#main_image').css('background-image', 'url(' + $('.main_image').eq(i).val() + ')');
                    $('#product_name').html($('.product_name').eq(i).val());
                    $('#created_at').html($('.created_at').eq(i).val());
                    $('#schedule_date').html($('.schedule_date').eq(i).val());
                    $('#adult').html($('.adult').eq(i).val() + ' Adult');
                    $('#children').html($('.children').eq(i).val() + ' Children');
                    $('#infant').html($('.infant').eq(i).val() + ' Infant');
                    $('#total_amount').html($('.total_amount').eq(i).val());
                });
            });


        });
        {{--function getReport() {--}}
        {{--$.ajax({--}}
        {{--type:'GET',--}}
        {{--dataType:'json',--}}
        {{--url:"{{route('company.dashboard.report')}}",--}}
        {{--success:function (data) {--}}
        {{--console.log(data)--}}
        {{--}--}}
        {{--})--}}
        {{--}--}}

        // INTRO JS CUSTOME POSITION
        // checkCookieIntro();
        // function startIntro() {
        //     var intro = introJs();
        //     intro.setOption('showBullets', false).setOption('keyboardNavigation', false).setOptions({
        //         steps: [
        //             {
        //                 intro: '{{ trans('intro.dashboard1') }} <b>mygomodo.com</b>. {{ trans('intro.dashboard2') }}'
        //             }
        //         ]
        //     });
        //     intro.setOption('doneLabel', '{{ trans('intro.setting_btn') }}').start().oncomplete(function () {
        //         window.location.href = 'profile?multipage=true';
        //     });
        //     // Auto scroll to top
        //     document.body.scrollTop = 0;
        //     document.documentElement.scrollTop = 0;
        //     // Disable scroll when intro js start
        //     $('body').css('overflow','hidden');
        //     intro.onexit(function() {
        //         $('body').css('overflow','auto');
        //     });
        // }

        // // Intro JS Next Page
        // if (RegExp('multipage', 'gi').test(window.location.search)) {
        //     startIntro();
        // }

        // // Intro JS Cookie Check
        // function checkCookieIntro(){
        //         var cookie=getCookie("mySite");
        //         setCookie("mySite", "1",90);
        //         if (cookie==null || cookie=="") {
        //         startIntro();
        //     }
        // }

        // //set the cookie when they first hit the site
        // function setCookie(c_name,value,exdays){
        //     var exdate=new Date();
        //     exdate.setDate(exdate.getDate() + exdays);
        //     var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
        //     document.cookie=c_name + "=" + c_value;
        // }

        // //check for the cookie when user first arrives, if cookie doesn't exist call the intro.
        // function getCookie(c_name){
        //     var c_value = document.cookie;
        //     var c_start = c_value.indexOf(" " + c_name + "=");
        //     if (c_start == -1){
        //             c_start = c_value.indexOf(c_name + "=");
        //     }
        //     if (c_start == -1){
        //             c_value = null;
        //     }
        //     else {
        //             c_start = c_value.indexOf("=", c_start) + 1;
        //             var c_end = c_value.indexOf(";", c_start);
        //             if (c_end == -1){
        //                     c_end = c_value.length;
        //             }
        //             c_value = unescape(c_value.substring(c_start,c_end));
        //     }
        // return c_value;
        // }
    </script>
@endsection
