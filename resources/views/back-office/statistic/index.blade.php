<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Gomodo | Statistic</title>
    <meta name="description" content="General widget examples">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
      WebFont.load({
        google: { "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"] },
        active: function(){
          sessionStorage.fonts = true;
        }
      });
    </script>

    <link href="{{asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="../../assets/demo/default/media/img/logo/favicon.ico"/>
    <style>
        .f-3rem {
            font-size: 3rem !important;
        }

        @media (min-width: 1025px) {
            .m-aside-left--fixed .m-body {
                -webkit-transition: width 0.2s ease;
                transition: width 0.2s ease;
                padding-left: 0px;
            }

            .m-header--fixed .m-body {
                padding-top: 0px !important;
            }

        }
    </style>
</head>

<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<div class="m-grid m-grid--hor m-grid--root m-page">

    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <div class="m-content">
                <div class="calendar text-center f-3rem">
                    {{--{{\Carbon\Carbon::now()->toDateTimeString()}}--}}
                </div>
                <marquee>Last Update : <span data-livestamp="1551244228"></span></marquee>
                <h4>Total</h4>
                <div class="m-portlet ">

                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-brand" id="">
												<i class="la la-shopping-cart f-3rem"></i>
                                        </span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-brand" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-brand py-5" id="total_paid_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::Total Profit-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Users-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-success">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-success" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>

                                        <span class="m-widget24__stats m--font-success py-5" id="nominal_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Users-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Orders-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Today Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-danger">
													<i class="la la-shopping-cart f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-danger" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-danger py-5" id="today_order_count">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Orders-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Feedbacks-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV Today
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-info" id="">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;"
                                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-info py-5" id="today_order_nominal">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Feedbacks-->
                            </div>
                        </div>
                    </div>
                </div>
                <h4>Non COD</h4>
                <div class="m-portlet ">

                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-brand" id="">
												<i class="la la-shopping-cart f-3rem"></i>
                                        </span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-brand" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-brand py-5" id="total_paid_non_cod_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::Total Profit-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Users-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-success">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-success" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>

                                        <span class="m-widget24__stats m--font-success py-5" id="nominal_non_cod_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Users-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Orders-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Today Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-danger">
													<i class="la la-shopping-cart f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-danger" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-danger py-5" id="today_order_non_cod_count">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Orders-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Feedbacks-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV Today
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-info" id="">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;"
                                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-info py-5" id="today_order_non_cod_nominal">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Feedbacks-->
                            </div>
                        </div>
                    </div>
                </div>
                <h4>COD</h4>
                <div class="m-portlet ">

                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-brand" id="">
												<i class="la la-shopping-cart f-3rem"></i>
                                        </span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-brand" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-brand py-5" id="total_paid_cod_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::Total Profit-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Users-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-success">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-success" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>

                                        <span class="m-widget24__stats m--font-success py-5" id="nominal_cod_order">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Users-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Orders-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            Total Today Paid Orders
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-danger">
													<i class="la la-shopping-cart f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-danger" role="progressbar"
                                                 style="width: 100%;" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-danger py-5" id="today_order_cod_count">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Orders-->
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">

                                <!--begin::New Feedbacks-->
                                <div class="m-widget24">
                                    <div class="m-widget24__item">
                                        <h4 class="m-widget24__title">
                                            GMV Today
                                        </h4><br>
                                        <span class="m-widget24__desc">

												</span>
                                        <span class="m-widget24__stats m--font-info" id="">
													<i class="la la-money f-3rem"></i>
												</span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;"
                                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="m-widget24__stats m--font-info py-5" id="today_order_cod_nominal">
													-
												</span>
                                    </div>
                                </div>

                                <!--end::New Feedbacks-->
                            </div>
                        </div>
                    </div>
                </div>




                <div class="m-portlet">
                    <div class="m-portlet__body m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-12 col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Product</h3>
                                                <span class="m-widget1__desc">Products in Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-brand"
                                                      id="total_product">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Provider</h3>
                                                <span class="m-widget1__desc">Providers in Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-danger"
                                                      id="total_provider">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Withdrawal</h3>
                                                <span class="m-widget1__desc"> Withdrawals in Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"
                                                      id="total_withdrawal">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Nominal Withdrawal</h3>
                                                <span class="m-widget1__desc">Nominal withdrawal using Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-accent"
                                                      id="withdrawal_complete">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Created Product Today</h3>
                                                <span class="m-widget1__desc">Total created product using Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-info" id="today_created_product">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Registered Provider Today</h3>
                                                <span class="m-widget1__desc">Total Registered Provider on Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-warning"
                                                      id="today_registered_provider">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Success Withdrawal Today</h3>
                                                <span class="m-widget1__desc">Total Success Withdrawal using Gomodo</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"
                                                      id="total_today_withdrawal_complete">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Nominal Success Withdrawal Today</h3>
                                                <span class="m-widget1__desc">Nominal Success Withdrawal to Bank Account</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-primary"
                                                      id="today_withdrawal_complete">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Total Pending Transaction Today</h3>
                                                <span class="m-widget1__desc">Total Pending Transactions</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-danger"
                                                      id="today_order_pending_nominal">-</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Top Provider
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="top-provider">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Top Product
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="top-product">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Latest Registered Provider
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="latest-provider">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Latest Order
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="latest-order">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Latest Created Product
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="latest-created-product">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Latest Withdrawals
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="latest-withdrawal">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Monthly Active Providers
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget3" id="monthly-active-providers">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/app/js/dashboard.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/livestamp/1.1.2/livestamp.min.js"></script>
<script>
  let count = 0;
  setInterval(function(){
    $('.calendar').html(moment().format('LLLL'))
    if (count === 120) {
      count = 0;
    }
    if (count === 0) {
      $.ajax({
        url: "{{route('admin:statistic-data')}}",
        dataType: 'json',
        success: function(data){
          $('#nominal_order').html(data.nominal_order);
          $('#nominal_cod_order').html(data.nominal_cod_order);
          $('#nominal_non_cod_order').html(data.nominal_non_cod_order);
          $('#total_today_withdrawal_complete').html(data.total_today_withdrawal_complete);
          $('#withdrawal_complete').html(data.withdrawal_complete);
          $('#total_order').html(data.total_order);
          $('#total_paid_order').html(data.total_paid_order);
          $('#total_paid_cod_order').html(data.total_paid_cod_order);
          $('#total_paid_non_cod_order').html(data.total_paid_non_cod_order);
          $('#total_provider').html(data.total_provider);
          $('#total_product').html(data.total_product);
          $('#total_withdrawal').html(data.total_withdrawal);
          $('#today_order_nominal').html(data.today_order_nominal);
          $('#today_order_cod_nominal').html(data.today_order_cod_nominal);
          $('#today_order_non_cod_nominal').html(data.today_order_non_cod_nominal);
          $('#today_order_count').html(data.today_order_count);
          $('#today_order_cod_count').html(data.today_order_cod_count);
          $('#today_order_non_cod_count').html(data.today_order_non_cod_count);
          $('#today_created_product').html(data.today_created_product);
          $('#today_order_pending_nominal').html(data.today_order_pending_nominal);
          $('#today_withdrawal_complete').html(data.today_withdrawal_complete);
          $('#today_withdrawal').html(data.today_withdrawal);
          $('#today_registered_provider').html(data.today_registered_provider);
          $('marquee span').attr('data-livestamp', data.updated_at);
          let html = '';
          $.each(data.top_provider, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.company_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.domain_memoria +
                '">' + e.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.short_description +
                '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#top-provider').html(html)
          html = '';
          $.each(data.top_product, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.product_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.domain_memoria +
                '">' + e.company_name + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.brief_description +
                '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#top-product').html(html);
          html = '';
          $.each(data.latest_registered_provider, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.company_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.domain_memoria +
                '">' + e.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.created_at +
                '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#latest-provider').html(html)

          html = '';
          $.each(data.latest_order, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.company.company_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.company.domain_memoria +
                '">' + e.company.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.created_at +
                '</p>\n' +
                '<p class="m-widget3__text" style="font-size:24px">\n' + e.total_amount_text +
                '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#latest-order').html(html)

          html = '';
          $.each(data.latest_created_product, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.product_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.company.domain_memoria +
                '">' + e.company.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.created_at +
                '</p>\n' +
                // '<p class="m-widget3__text" style="font-size:24px">\n' + e.total_amount_text +
                // '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#latest-created-product').html(html)

          html = '';
          $.each(data.active_providers, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.company_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.domain_memoria +
                '">' + e.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.total +
                ' login day(s)</p>\n' +
                // '<p class="m-widget3__text" style="font-size:24px">\n' + e.total_amount_text +
                // '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#monthly-active-providers').html(html)

          html = '';
          $.each(data.latest_withdrawal_complete, function(i, e){
            html += '<div class="m-widget3__item">\n' +
                '<div class="m-widget3__header">\n' +
                '<div class="m-widget3__info pl-0">\n' +
                '<span class="m-widget3__username">\n' +
                e.company.company_name +
                '</span><br>' +
                '<span class="m-widget3__time">\n' +
                '<a href=" http://' + e.company.domain_memoria +
                '">' + e.company.domain_memoria + '</a></span>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="m-widget3__body">\n' +
                '<p class="m-widget3__text">\n' + e.created_at +
                '</p>\n' +
                '<p class="m-widget3__text" style="font-size:24px">\n' + e.amount_text +
                '</p>\n' +
                '</div>\n' +
                '</div>'
          })
          $('#latest-withdrawal').html(html)
        },
        error: function(e){
          console.log(e)
        }
      })
    }
    count++;

  }, 1000)
</script>

</body>

</html>
