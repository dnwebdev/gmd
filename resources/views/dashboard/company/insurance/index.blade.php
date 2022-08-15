@extends('dashboard.company.base_layout')
@section('additionalStyle')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('dest-operator/css/index.css') }}">
    <link href="{{ asset('dest-operator/reskin-assets/css/components.min.css') }}" rel="stylesheet">
    <style>
        .banner {
            background: none;
            box-shadow: none
        }
        .banner h3 {
            font-size: 1.4rem;
            line-height: 56px;
            color: #2a3140;
            font-weight: 600;
        }
        .dashboard-cta .whatsapp {
            width: 16px;
            margin-bottom: 4px;
        }
        .banner img {
            width: 600px
            max-width: 100%;
            display: block;
        }
        .banner img.left {
            margin-left: auto; 
        }
        @media screen and (max-width: 991px) {
            .banner img {
                margin: auto;
            }
        }
        @media screen and (max-width: 767px) {
            .banner {
                padding-right: 0;
                padding-left: 0;
            }
            .dashboard-cta button {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
@endsection
@section('title', ucfirst(trans('sidebar_provider.insurance')))

@section('breadcrumb')
@stop

@section('indicator_order')
    active
@stop

@section('content')
    <!-- Page header -->
    <div data-template="main_content_header">
        <div class="page-header" style="margin-bottom: 0;">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title">
                    <h5>
                        {{ucfirst(trans('sidebar_provider.insurance'))}}
                    </h5>
                </div>

                <div class="header-elements py-0">
                    <div class="breadcrumb">
                        <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                            <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                        </a>
                        <span class="breadcrumb-item active">{{ucfirst(trans('sidebar_provider.insurance'))}}</span>
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
        <div class="card card-body banner">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid text-center left"
                        src="{{ asset('dest-operator/reskin-assets/img/jagadiri.svg')}}" alt="">
                </div>
                <div class="col-md-6">
                    <img class="img-fluid text-center"
                        src="{{ asset('dest-operator/reskin-assets/img/astralife.svg')}}" alt="">
                </div>
            </div>
            <br>
            <h3 class="text-center">{!! trans('insurance.intro.title') !!}</h3>
            <p class="col-lg-10 text-center justify-content-center"
            style="margin: auto">{!! trans('insurance.intro.description') !!}</p>
            <a href="https://support.mygomodo.com/portal/kb/asuransi" target="_blank" class="btn btn-primary mt-3 mx-auto" style="width: fit-content">{!! trans('insurance.intro.button_text') !!}</a>
        </div>

        <form id="form_ajax_insurance" method="POST" action="{{ url('company/insurance/request') }}">
            {{ csrf_field() }}
            <div class="widget card">
              <div class="widget-header">
                <h3>{{ trans('insurance.form.title') }}</h3>
              </div>
      
              <div class="widget-content">
                <div class="form-group">
                  <div class="row pb-3">
                      @foreach (trans('insurance.form.input') as $input)
                        @if ($input['name'] !== 'message')
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                    <input name="{{ $input['name'] }}" class="form-control {{ $input['additional_class'] }}"
                                           @php
                                               switch ($input['name']){
                                                    case "name":
                                                        echo 'value="'.auth()->user()->company->company_name.'"';
                                                       break;
                                                       case "email":
                                                       echo 'value="'.auth()->user()->company->email_company.'"';
                                                       break;
                                                       case "phone":
                                                       echo 'value="'.auth()->user()->company->phone_company.'"';
                                                       break;
                                               }
                                           @endphp
                                           {{$input['name'] == 'name'?'readonly':''}}  type="text" value="" maxlength="50">
                                </div>
                            </div>      
                        @endif
                      @endforeach
                  </div>
                  <div class="row pb-3">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="{{ trans('insurance.form.input.3.name') }}">{{ trans('insurance.form.input.3.label') }} <span class="text-danger">*</span></label>
                            <textarea name="{{ trans('insurance.form.input.3.name') }}" class="form-control" type="textarea" rows="7" length="300"></textarea>
                        </div>
                    </div>      
                  </div>
                </div>
              </div>
            </div>
      
            <div class="dashboard-cta d-md-flex justify-content-between">
              <span>{{ trans('insurance.form.contact_us') }} <img class="whatsapp" src="{{ asset('img/static/payment/whatsapp.png') }}" alt="whatsapp" /> 0812-1111-9655</span>
              <button class="btn btn-primary" type="submit">{{ trans('insurance.form.submit') }}</button>
            </div>
        </form>
    </div>

@endsection
@section('additionalScript')
    <script src="{{ asset('dest-operator/reskin_global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dest-operator/reskin_global_assets/js/demo_pages/form_inputs.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
    <script>
        // Init -------------------------------------------------------------------------------------------
        $(document).ready(function(e){
            form_ajax($('#form_ajax_insurance'),function(e){
                loadingFinish();
                console.log('success', e.responseJSON)
                if (e.message == "Success" || e.status == 200) {
                    swal({
                        imageUrl: "{{ asset('dest-operator/reskin-assets/img/sent.svg')}}",
                        imageWidth: 300,
                        imageAlt: 'Custom image',
                        text: "{!! trans('insurance.form.swall.success') !!}",
                        confirmButtonText: "{!! trans('insurance.form.swall.ok') !!}",
                    }).then(function() {
                        window.location.reload()
                    });
                } else {
                    swal({
                        type: "error",
                        text: e.message,
                        confirmButtonText: "{!! trans('insurance.form.swall.ok') !!}",
                    })
                    console.log('error', e)
                }
            });
        })
    </script>
@endsection
