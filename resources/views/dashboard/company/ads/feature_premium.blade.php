<div class="widget available-adds stepPremium1" id="fb-ig">
    <div class="row">
        <div class="col-12 col-lg-3 align-self-center text-center">
            <img class="img-fluid mr-3" src="{{asset('img/fb.png')}}" height="75" />
            <img class="img-fluid" src="{{asset('img/ig.png')}}" height="75" />
        </div>
        <div class="col-12 col-lg-6 text-title-ads">
            <h4><strong>{{ __('premium.facebook_ads') }}</strong></h4>
            <p>{{ trans('premium.banner.banner_facebook') }}</p>
        </div>
        <div class="col-12 col-lg-3 text-center justify-content-center">
            <button class="btn btn-primary btn-buy-now" href="facebook-ads.html" id="button-buy-now" type="button" style="margin-bottom:1rem;" data-ads="Facebook Ads">{{ trans('premium.banner.buy_now') }}</button>
            {{-- <button class="btn btn-primary btn-howbuy-now" href="facebook-ads.html" id="how-to-buy-now" type="button" style="margin-bottom:1rem; margin-top: 4rem;display: none;" onclick="introjsstart()">{{ trans('premium.facebook.label.order_tutorial')  }}</button> --}}
        </div>

        <form class="form-wizard form-fb-ig form-ads col" data-ads-type="fb-ig">
            {{ csrf_field() }}
            <input type="hidden" name="myvoucher_id">
            <h3>1</h3>
            <section class="text-center">
                <div class="container">
                    <h4 class="mb-4">{{ __('premium.fb_ig.steps.1.title') }}</h4>
                    <div class="mt-3 category-ads-selection">
                        <label class="category-ads p-2" data-type="fb">
                            <input class="d-none category-ads-choice" type="checkbox" value="Facebook Ads" name="category_ads[]" id="category-ads-fb">
                            <img src="{{ asset('dest-operator/img/facebook-ads-logo.png') }}" />
                        </label>
                        <label class="category-ads p-2" data-type="ig">
                            <input class="d-none category-ads-choice" type="checkbox" value="Instagram Ads" name="category_ads[]" id="category-ads-ig">
                            <img src="{{ asset('dest-operator/img/insta.png') }}" />
                        </label>
                    </div>
                </div>
            </section>
            <h3>2</h3>
            <section class="text-center w75">
                <div class="container">
                    <h4>{{ __('premium.fb_ig.steps.2.title') }}</h4>
                    <div class="mb-5">{{ __('premium.fb_ig.steps.2.subtitle') }}</div>

                    <div class="row mt-3 text-left">
                        @foreach (trans('premium.fb_ig.purpose.type') as $key => $value)
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col">
                                    <div>{{ $value['label'] }}</div>
                                    <div class="text-muted">
                                        {{ $value['description'] }}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="el-switch">
                                        <input type="radio" value="{{ $key }}" name="purpose" class="d-none" {{ $loop->index == 0 ? 'checked' : '' }}>
                                        <span class="el-switch-style"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <div class="row align-items-end">
                            <div class="col-md-5 d-none d-lg-block">
                                <img src="{{ asset('dest-operator/img/spg-marketing-solution.svg') }}" alt="SPG" width="313" />
                            </div>
                            <div class="col-lg-7">
                                <div class="bubble">
                                    <h6>{{ __('premium.fb_ig.purpose.recomendation') }}</h6>
                                    {!! __('premium.fb_ig.purpose.recomendations') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <h3>3</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.fb_ig.steps.3.title') }}</h4>
                    <div>{{ __('premium.fb_ig.steps.3.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.location.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.location.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select class="form-control js-select2" name="city[]" multiple="multiple" data-placeholder="{{ __('premium.fb_ig.form.location.placeholder') }}">
                        </select>
                        <input type="hidden" id="idcityname" name="cityname" class="form-control">
                    </div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.gender.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.gender.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select class="form-control" name="gender" required>
                            <option disabled selected>{{ __('premium.fb_ig.form.gender.label') }}</option>
                            @foreach (__('premium.fb_ig.form.gender.values') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.age.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.age.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select class="form-control" name="age" required>
                            <option disabled selected>{{ __('premium.fb_ig.form.age.label') }}</option>
                            @foreach (__('premium.fb_ig.form.age.values') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
            <h3>4</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.fb_ig.steps.4.title') }}</h4>
                    <div>{{ __('premium.fb_ig.steps.4.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.url.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.url.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="text" name="url" class="form-control fb-preview" id="url-ads" maxlength="150" placeholder="{{ __('premium.fb_ig.form.url.placeholder') }}" data-fb-preview="url" value="http://"/>
                    </div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.title.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.title.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="text" name="title" class="form-control fb-preview" placeholder="{{ __('premium.fb_ig.form.title.placeholder') }}" data-fb-preview="title" />
                    </div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.description.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.description.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <textarea class="form-control fb-preview" name="description" placeholder="{{ __('premium.fb_ig.form.description.placeholder') }}" rows="4" data-fb-preview="description"></textarea>
                    </div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.call_button.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.call_button.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select class="form-control fb-preview" name="call_button" required data-fb-preview="call_button">
                            <option disabled selected>{{ __('premium.fb_ig.form.call_button.placeholder') }}</option>
                            @foreach (__('premium.fb_ig.form.call_button.values') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
            <h3>5</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.fb_ig.steps.5.title') }}</h4>
                    <div>{{ __('premium.fb_ig.steps.5.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.fb_ig.form.document_ads.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.fb_ig.form.document_ads.tooltip') }}"></i></h5>
                    <div class="text-center mb-3">
                        <img src="https://placehold.it/300x173" width="300" class="step-5 img-fluid img-thumbnail"/>
                    </div>
                    <div class="input-group mb-3 text-left">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image-file-ads" name="document_ads" accept="image/x-png,image/gif,image/jpeg,image/jpeg" lang="{{ app()->getLocale() }}">
                            <label class="custom-file-label" for="image-file-ads"></label>
                        </div>
                    </div>

                    <div class="form-inline image-ads-condition">
                        <a data-toggle="modal" data-target="#modal-image-ads-agreement">
                            <i class="fa fa-picture-o"></i> {{ trans('premium.fb_ig.view_image_recomendation') }}
                        </a>
                        <div class="modal fade text-left" id="modal-image-ads-agreement" tabindex="-1" role="dialog" aria-labelledby="imageAdsAgreement" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-center">{!! trans('premium.facebook.label.modal_rev1') !!}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="terms-condition-image">
                                            <ul>
                                                <li class="w-100">{{ trans('premium.facebook.label.modal_rev2') }}</li>
                                                <li class="w-100">{{ trans('premium.facebook.label.modal_rev3') }}</li>
                                                <li class="w-100">{{ trans('premium.facebook.label.modal_rev4') }}</li>
                                                <label class="d-block">{{ trans('premium.facebook.label.modal_rev5') }} <a href="https://www.facebook.com/business/help/980593475366490" target="_blank">{{ trans('premium.facebook.label.modal_revLink') }}</a></label>
                                            </ul>
                                        </div>
                                        <p class="text-center">{{ trans('premium.facebook.label.modal_rev6') }}</p>
                                        <div class="image-example-ads text-center">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <p><i class="fa fa-check-circle" style="color: #6ac259"></i>{{ trans('premium.facebook.label.modal_rev7') }}</p>
                                                    <img src="{{asset('dest-operator/img/example-image-true@2x.jpg')}}">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <p><i class="fa fa-times-circle" style="color: #f05228"></i>{{ trans('premium.facebook.label.modal_rev8') }}</p>
                                                    <img src="{{asset('dest-operator/img/example-image-false@2x.jpg')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <h3>6</h3>
            <section class="text-center">
                <div class="container w-100">
                    <h4>{{ __('premium.fb_ig.steps.6.title') }}</h4>
                    <div class="mb-3">{{ __('premium.fb_ig.steps.6.subtitle') }}</div>
                    
                    <div class="row justify-content-md-center">
                        <div id="fb-ads-preview" class="col-xl-5 p-2" style="display: none;">
                            <div class="bubble-2">Facebook</div>
                            <div class="fb-ads-preview border bg-white mb-2 text-left">
                                <div class="media p-2">
                                    <img class="mr-3" src="{{ asset('explore-assets/images/logo.png') }}" alt="Gomodo" width="40">
                                    <div class="media-body">
                                        <h6 class="m-0"><a href="#">Gomodo</a></h6>
                                        <span class="text-muted fs-14 align-self-center">{{ __('premium.fb_ig.additional.sponsored') }} <span class="ml-2">&bull;</span> <i class="fa fa-globe fs-14"></i></span>
                                  </div>
                                </div>
                                <div class="preview-content px-2 mb-2">
                                    <span class="fb-preview-description">Wisata</span>
                                </div>
                                <div style="background: #f2f3f5">
                                    <img src="{{ asset('dest-operator/img/mask-group.png') }}" class="img-fluid img-facebook-ads" />
                                    <div class="px-2 py-1 preview-content">
                                        <span class="text-muted fb-preview-url">Yourwebsite.com</span>
                                        <div class="d-lg-flex">
                                            <div class="fb-content">
                                                <h5 class="my-1 text-dark fb-ads-title fb-preview-title">Judul</h5>
                                                <span class="text-muted fb-preview-description fb-description-2">Wisata</span>
                                            </div>
                                            <div class="ml-auto align-self-center text-right fb-call-button">
                                                <a class="border py-1 px-3 text-muted fb-preview-call_button" href="#">Pesan sekarang</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around border-top bg-white p-2 text-muted preview-content">
                                    <div>
                                        <img src="{{ asset('dest-operator/img/icons/fb_like.png') }}" width="24" /> {{ __('premium.fb_ig.additional.fb.like') }}
                                    </div>
                                    <div>
                                        <img src="{{ asset('dest-operator/img/icons/fb_comment.png') }}" width="24" /> {{ __('premium.fb_ig.additional.fb.comment') }}
                                    </div>
                                    <div>
                                        <img src="{{ asset('dest-operator/img/icons/fb_share.png') }}" width="24" /> {{ __('premium.fb_ig.additional.fb.share') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="ig-ads-preview" class="col-xl-5 p-2" style="display: none;">
                            <div class="bubble-2">Instagram</div>
                            <div class="fb-ads-preview border bg-white mb-2 text-left">
                                <div class="media p-2">
                                    <img class="mr-3 rounded" src="{{ asset('explore-assets/images/logo.png') }}" alt="Gomodo" width="40">
                                    <div class="media-body">
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="m-0"><a href="#" class="text-dark"><strong>gomodotech</strong></a></h6>
                                                <span class="fs-14 align-self-center">{{ __('premium.fb_ig.additional.sponsored') }}</span>
                                            </div>
                                            <div class="align-self-center ml-auto">
                                                <i class="fa fa-ellipsis-v" style="font-size: 1rem;"></i>
                                            </div>
                                        </div>
                                  </div>
                                </div>
                                <div class="border-top border-bottom py-2">
                                    <img src="{{ asset('dest-operator/img/mask-group.png') }}" class="img-fluid img-facebook-ads" />
                                </div>
                                <a class="d-flex py-2 px-3" href="#" style="background: #3b3b6d">
                                    <div><strong class="text-white fb-preview-call_button" href="javascript:void(0);">Pesan sekarang</strong></div>
                                    <div class="ml-auto"><i class="fa fa-chevron-right text-white fs-14"></i></div>
                                </a>
                                <div class="d-flex border-top bg-white px-2 pt-1 text-muted preview-content mb-0 pt-2">
                                    <div>
                                        <img src="{{ asset('dest-operator/img/icons/ig_like.png') }}" width="24" class="mr-3" />
                                        <img src="{{ asset('dest-operator/img/icons/ig_comment.png') }}" width="24" class="mr-3" />
                                        <img src="{{ asset('dest-operator/img/icons/ig_share.png') }}" width="24" />
                                    </div>
                                    <div class="ml-auto">
                                        <i class="fa fa-bookmark-o text-dark"></i>
                                    </div>
                                </div>
                                <div class="preview-content px-2 pt-1 pb-2">
                                    <strong>1,000,0000 {{ __('premium.fb_ig.additional.ig.views') }}</strong>
                                    <div>
                                        <a href="#" class="text-dark"><strong>gomodotech</strong></a>&nbsp;
                                        <span class="fb-preview-description">INi coba coba</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <h3>7</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.fb_ig.steps.4.title') }}</h4>
                    <div>{{ __('premium.fb_ig.steps.4.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.budget.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.budget.tooltip') }}"></i></h5>
                    <div class="input-group">
                        <div class="input-group-prepend mr-0">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" class="form-control price number google-min-budget" maxlength="11" placeholder="{{ __('premium.google.form.budget.placeholder') }}" />
                        <input type="hidden" name="min_budget" class="google-min-budget-h" />
                    </div>
                    <div class="row my-3">
                        <div class="col-12 col-sm-6">
                            <h5 class="mt-3">{{ __('premium.google.form.start_date.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.start_date.tooltip') }}"></i></h5>
                            <div class="input-group mb-3">
                                <input id="date_start" name="start_date" data-provide="datepicker" type="text" class="form-control date-picker bg-white" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly placeholder="{{ __('premium.google.form.start_date.placeholder') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text border-right">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h5 class="mt-3">{{ __('premium.google.form.end_date.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.end_date.tooltip') }}"></i></h5>
                            <div class="input-group mb-3">
                                <input id="date_final" name="end_date" data-provide="datepicker" type="text" class="form-control date-picker bg-white" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly placeholder="{{ __('premium.google.form.end_date.placeholder') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <table class="table table-borderless text-left">
                            <tbody>
                                <tr>
                                    <th scope="row">Subtotal</th>
                                    <td class="dynamic-data-price priceSubTotal">IDR 0</td>
                                    <input type="hidden" name="sub_total" class="sub_total">
                                </tr>
                                <tr>
                                    <th scope="row">{{ trans('premium.facebook.label.service_fee') }}</th>
                                    <td class="dynamic-data-price price-service-fee">IDR 0</td>
                                    <input type="hidden" name="service_fee" class="service_fee">
                                </tr>
                                <tr>
                                    <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
                                    <td class="dynamic-data-price price-total1">IDR 0</td>
                                    <input type="hidden" name="total_price" class="total_price">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-checkbox">
                        <input type="checkbox" name="term_conditions" value="1" class="form-check-input" id="fb-ig-sk">
                        <label class="form-check-label" for="fb-ig-sk">
                            {{ trans('premium.facebook.label.sk_disclaimer') }}
                            <a href="" data-toggle="modal" data-target=".modal-term-condition" require>{{ trans('premium.facebook.label.sk') }}</a>
                            {{ trans('premium.facebook.label.applied') }}
                            <a href="" data-toggle="modal" data-target=".modal-disclaimer-ads" require>{{ trans('premium.facebook.label.disclaimer') }}</a>
                        </label>
                    </div>
                    <!-- Modal S&K DAN DISCLAIMER -->
                    <div class="col-12 col-lg-8 offset-2 total-pay text-left">
                        <div class="modal fade modal-term-condition" tabindex="-1" role="dialog" aria-labelledby="termConditionModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="modal-agreement">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span id="close-btn-modal-ads-info" class="float-right" aria-hidden="true">&times;</span>
                                    </button>
                                    <h5>{{ trans('premium.facebook.modal_sk.sk') }}</h5>
                                    <hr />
                                    <ul class="bullet-list">
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat1') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat2') }} <a href="https://www.facebook.com/policies/ads" target="_blank"><span>{{ trans('premium.facebook.modal_sk.syarat_here') }}</span></a>
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat3') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat4') }} <a href="https://www.facebook.com/help/187316341316631?helpref=related" target="_blank"><span>{{ trans('premium.facebook.modal_sk.syarat_here') }}</span></a>
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat5') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat6') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat7') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat8') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat9') }}
                                        </li>
                                        @if (app()->getLocale() == 'id')
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat10') }}
                                        </li>
                                        @endif

                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_sk.syarat11') }}<strong> +62 812-1111-9655</strong> {{ trans('premium.facebook.modal_sk.syarat_or') }} <a href="mailto:info#gomodo.tech"><span>info@gomodo.tech</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL DISCLAIMER -->
                    <div class="col-12 col-lg-8 offset-2 total-pay text-left">
                        <div class="modal fade modal-disclaimer-ads" tabindex="-1" role="dialog" aria-labelledby="termConditionModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="modal-agreement">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span id="close-btn-modal-ads-info" class="float-right" aria-hidden="true">&times;</span>
                                    </button>
                                    <h5>Disclaimer</h5>
                                    <hr />
                                    <ul class="bullet-list">
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_disclaimer.disclaimer1') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_disclaimer.disclaimer2') }}
                                        </li>
                                        <li class="w-100">
                                            {{ trans('premium.facebook.modal_disclaimer.disclaimer3') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <h3>8</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.6.title') }}</h4>
                    <div>{{ __('premium.google.steps.6.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.payment_method.label') }}</h5>
                    <div class="form-group">
                        <select name="payment_method" id="" class="form-control">
                            <option value="" selected disabled>{{ __('premium.google.form.payment_method.placeholder') }}</option>
                            @foreach (__('premium.google.form.payment_method.values') as $method => $name)
                            <option value="{{ $method }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row text-left">
                        <div class="col-md-6">
                            <img src="{{asset('dest-operator/img/gxp-img.svg')}}">
                            <label id="gxp-total-balance">IDR {{ number_format($gxp_sum['gxp'], 0) }}</label>
                            <input id="gxp-total-balance-hidden" type="hidden" value="{{ number_format($gxp_sum['gxp'],0) }}">
                        </div>
                        <div class="col-md-6 text-right">
                            <label class="el-switch">
                                <span style="line-height: 1.6;vertical-align: top;">{{ trans('premium.facebook.label.use_gxp_slider') }}</span>
                                <input id="use-gxp" type="checkbox" value="true" name="using_gxp" class="d-none">
                                <span class="el-switch-style"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-2 promo-voucher-google">
                        <div class="text-left">
                            <a href="javascript:void(0);" class="promo-remove" style="display: none;">{{ trans('premium.facebook.label.remove_promocode') }}</a><br>
                            <p id="reason-ads" class="float-none d-inline">{{ trans('premium.facebook.label.promo_code') }}</p>
                            <span id="remove-voucher" class="float-right">{{ trans('premium.facebook.label.remove_promo_code') }}</span>
                        </div>
                        <div class="text-left">
                            <div class="input-group mb-1 mt-2 voucher-section">
                                <input id="input_voucher" type="text" class="form-control" placeholder="{{ trans('premium.facebook.label.p_promo_code') }}">
                                <input type="hidden" name="code">
                                <input type="hidden" name="gxp_value">
                                <input type="hidden" name="gxp_amount">
                                <input type="hidden" name="promo_amount">
                                <input type="hidden" name="cashback_amount">
                                <input type="hidden" name="grand_total">
                                <input type="hidden" name="gxp_balance">
                                <div class="input-group-append">
                                    <button id="btn-promo-code" class="btn btn-primary submit-voucher mb-1" type="button">{{ __('premium.google.form.apply_voucher') }}</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a id="getvoucher" href="javascript:void();" class="float-left fs-14 use-the-voucher" onclick="introJs().exit()">{{ __('premium.google.form.use_my_voucher') }}</a>
                        </div>
                    </div>

                    <table class="table table-borderless text-left" id="dataPrice">
                        <tbody>
                            <tr>
                                <th scope="row">Subtotal</th>
                                <td class="dynamic-data-price priceSubTotal">IDR 0</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ trans('premium.facebook.label.service_fee') }}</th>
                                <td class="dynamic-data-price price-service-fee">IDR 0</td>
                            </tr>
                            {{-- <tr>
                          <th scope="row">{{ trans('premium.facebook.label.gxp_credits') }}</th>
                            <td id="gxp-balance" class="dynamic-data-price">IDR 0</td>
                            </tr> --}}
                            {{-- <tr>
                          <th scope="row">Voucher</th>
                          <td id="price-discount" class="dynamic-data-price">IDR 0</td>
                        </tr> --}}
                        </tbody>
                    </table>

                    <hr>
                    <table class="table table-borderless text-left">
                        <tbody>
                            <tr>
                                <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
                                <td class="dynamic-data-price price-total" id="totaly2">IDR 0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </form>

            {{-- <div class="col-12 col-lg-6 form-ads">

            <p id="reason-ads">{{ trans('premium.facebook.label.url') }}<span style="color:red;"></span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_url') }}"></i></p>
            <div class="form-group stepPremium2">
                <input type="text" name="url" class="form-control" id="url-ads" maxlength="100" placeholder="{{ trans('premium.facebook.placeholder.url') }}" />
                <input type="hidden" name="category_ads">
            </div>

            <p id="reason-ads">{{ trans('premium.facebook.label.you_ads') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_you_ads') }}"></i></p>

            <div class="form-group">
                <select class="form-control" name="purpose">
                    <option disabled selected>{{ trans('premium.facebook.select.your_advertising1') }}</option>
                    <option value="Mendatangkan pengunjung ke website">{{ trans('premium.facebook.select.your_advertising2') }}</option>
                    <option value="Mempromosikan bisnis anda">{{ trans('premium.facebook.select.your_advertising3') }}</option>
                </select>
            </div>

            <p id="reason-ads">{{ trans('premium.facebook.label.headline_text') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_headline_text') }}"></i></p>
            <div class="form-group">
                <input type="text" name="title" class="form-control" id="title-ads" placeholder="{{ trans('premium.facebook.placeholder.headline_text') }}" maxlength="25" />
            </div>

            <p id="reason-ads">{{ trans('premium.facebook.label.description') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_description') }}"></i></p>
            <div class="form-group">
                <textarea class="form-control" name="description" id="description-ads" placeholder="{{ trans('premium.facebook.placeholder.description') }}" rows="8" maxlength="90" style="height:147px;"></textarea>
            </div>

            <p id="reason-ads">{{ trans('premium.facebook.label.call_to') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_target_audience') }}"></i></p>
            <div class="form-group">
                <select class="form-control" id="selectBtnValue" name="call_button">
                    <option value="" disabled selected>{{ trans('premium.facebook.select.call_to1') }}</option>
                    <option value="Hubungi Kami">{{ trans('premium.facebook.select.call_to2') }}</option>
                    <option value="Pelajari lebih lanjut">{{ trans('premium.facebook.select.call_to3') }}</option>
                    <option value="Lihat jadwal">{{ trans('premium.facebook.select.call_to4') }}</option>
                    <option value="Pesan sekarang">{{ trans('premium.facebook.select.call_to5') }}</option>
                    <option value="Daftar sekarang">{{ trans('premium.facebook.select.call_to6') }}</option>
                </select>
            </div>

            <p id="reason-ads">{{ trans('premium.facebook.label.upload_ad') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_upload_ad') }}"></i></p>

            <div class="form-group stepPremium3" style="margin-bottom: 0;">
                <div class="input-group">
                    <input type="text" id="file-name" class="form-control image-file-name-ads" placeholder="{{ trans('premium.facebook.placeholder.upload_ad') }}" aria-label="Recipient's username" readonly>
                    <div class="input-group-append">
                        <div class="btn btn-primary image-upload-btn">
                            <input type="file" id="image-file-ads" class="file upload-image-ads" name="document_ads" accept="image/x-png,image/gif,image/jpeg,image/jpeg" />
                            <p id="btn-upload-image-text">{{ trans('premium.facebook.label.upload') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <small id="file-size-image" style="color:red; display:none;"></small>

            <div class="form-inline image-ads-condition">
                <i class="fa fa-picture-o"></i>
                <label>{{ trans('premium.facebook.label.tc_image') }}</label>
                <a data-toggle="modal" data-target="#modal-image-ads-agreement">{{ trans('premium.facebook.label.see_detail_image') }}</a>
                <div class="modal fade" id="modal-image-ads-agreement" tabindex="-1" role="dialog" aria-labelledby="imageAdsAgreement" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-center">{{ trans('premium.facebook.label.modal_rev1') }} <strong>Facebook Ads</strong></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="terms-condition-image">
                                    <ul>
                                        <li>{{ trans('premium.facebook.label.modal_rev2') }}</li>
                                        <li>{{ trans('premium.facebook.label.modal_rev3') }}</li>
                                        <li>{{ trans('premium.facebook.label.modal_rev4') }}</li>
                                        <label class="d-block mt-2">{{ trans('premium.facebook.label.modal_rev5') }} <a href="https://www.facebook.com/business/help/980593475366490" target="_blank">{{ trans('premium.facebook.label.modal_revLink') }}</a></label>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-center">{{ trans('premium.facebook.label.modal_rev6') }}</p>
                            <div class="image-example-ads text-center">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <p><i class="fa fa-check-circle" style="color: #6ac259"></i>{{ trans('premium.facebook.label.modal_rev7') }}</p>
                                        <img src="{{asset('dest-operator/img/example-image-true@2x.jpg')}}">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <p><i class="fa fa-times-circle" style="color: #f05228"></i>{{ trans('premium.facebook.label.modal_rev8') }}</p>
                                        <img src="{{asset('dest-operator/img/example-image-false@2x.jpg')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 container">
                <div class="row">
                    <div class="col-2">
                        <img id="img-company" src="{{asset('dest-operator/img/travel-img.jpg')}}" style="height:40px;" />
                    </div>
                    <div class="col-8" style="display:grid;">
                        <span>Gomodo Technology</span>
                        <small>Sponsored</small>
                    </div>
                    <div class="col-2">
                        <!-- <img id="img-facebook-static" src="{{asset('dest-operator/img/facebook-like.png')}}" width="62px"/> -->
                    </div>
                    <div class="col">
                        <p class="headline-second">{{ trans('premium.facebook.label.description_here') }}</p>
                        <img class="img-facebook-ads" src="{{asset('dest-operator/img/mask-group.png')}}">
                        <p class="headline-first">{{ trans('premium.facebook.label.url') }}</p>
                        <p class="title-facebook-ads">{{ trans('premium.facebook.label.title_place_here') }}</p>
                        <div class="row">
                            <div class="offset-9 col-3 text-center">
                                <span class="button-more float-right">
                                    <p class="button-learn-more">{{ trans('premium.facebook.label.learn_more') }}</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="col-12 col-lg-6 form-ads">
        <p id="reason-ads">{{ trans('premium.facebook.label.target_audience') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_target_audience') }}"></i></p>

        <div class="form-group">
            <select class="form-control" name="gender" required>
                <option disabled selected>{{ trans('premium.facebook.select.gender1') }}</option>
                <option value="Semua gender">{{ trans('premium.facebook.select.gender2') }}</option>
                <option value="Laki-laki">{{ trans('premium.facebook.select.gender3') }}</option>
                <option value="Perempuan">{{ trans('premium.facebook.select.gender4') }}</option>
            </select>
        </div>


        <p id="reason-ads">{{ trans('premium.facebook.label.target_age') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_target_age') }}"></i></p>
        <div class="form-group">
            <select class="form-control" name="age" required>
                <option disabled selected>{{ trans('premium.facebook.select.age1') }}</option>
                <option value="12 - 20 Tahun">{{ trans('premium.facebook.select.age2') }}</option>
                <option value="21 - 40 Tahun">{{ trans('premium.facebook.select.age3') }}</option>
                <option value="40 - 65 Tahun">{{ trans('premium.facebook.select.age4') }}</option>
            </select>
        </div>
        <p id="reason-ads">{{ trans('premium.facebook.label.target_location') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_target_location') }}"></i></p>
        <div class="form-group">
            <select class="form-control js-select2" name="city[]" multiple="multiple" style="width: 100%;" data-placeholder="{{ trans('premium.facebook.placeholder.target_location') }}">
            </select>
            <input type="hidden" id="idcityname" name="cityname" class="form-control">
        </div>

        <p id="reason-ads">{{ trans('premium.facebook.label.daily_ads') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_daily_ads') }}"></i></p>

        <div class="form-group stepPremium4">
            <!-- <input type="number" name="min_budget" id="min-price" step="any" lang="de" class="form-control .number" maxlength="11" placeholder="{{ trans('premium.facebook.placeholder.daily_ads') }}" pattern="-?[0-9]+[\.]*[0-9]+"/> -->
            <input type="text" name="min_budget" id="min-price" maxlength="11" class="form-control price" placeholder="{{ trans('premium.facebook.placeholder.daily_ads') }}">
            <small id="small-min-price" style="color:red; "></small>
        </div>

        <span id='alert-price' style='display : none; color: red;'>{{ trans('premium.facebook.placeholder.alert_daily_ads') }}</span>

        <p id="reason-ads">{{ trans('premium.facebook.label.ads_active') }}<span style="color:red;">*</span><i class="fa fa-question-circle-o tooltips" title="{{ trans('premium.facebook.tooltip.input_ads_active') }}"></i></p>
        <div class="row">
            <div class="col-12 col-sm-5">
                <input id="date_start" name="start_date" data-provide="datepicker" type="text" class="form-control date-picker" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly>
            </div>
            <div class="col-12 col-sm-2 text-center">
                <p style="padding-top: 11px;">{{ trans('premium.facebook.label.to') }}</p>
            </div>
            <div class="col-12 col-sm-5">
                <input id="date_final" name="end_date" data-provide="datepicker" type="text" class="form-control date-picker" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly>
            </div>
        </div> --}}


        {{-- <div>
          <p id="reason-ads" style="display:inline">{{ trans('premium.facebook.label.promo_code') }}</p>
        <span id="getvoucher" class="float-right use-the-voucher" style="text-align:right; color: #0893cf;cursor: pointer;">{{ trans('premium.facebook.label.or_voucher') }}</span>
    </div>
    <div class="voucher-section">
        <span class="fa fa-check-circle"></span>
        <input id="input_voucher" class="form-control" maxlength="15" placeholder="{{ trans('premium.facebook.placeholder.voucher_code') }}" name="voucher_code">
    </div> --}}
    <!--
                    <div class="row mt-4">
                      <div class="col-5">
                        <p class="static-data-price">Subtotal</p>
                        <p class="static-data-price">{{ trans('premium.facebook.label.service_fee') }}</p>
                        {{-- <p class="static-data-price">Voucher(0%)</p> --}}
                <p class="static-data-price">{{ trans('premium.facebook.label.total_price') }}</p>
                      </div>
                      <div class="col-7">
                        {{-- sub total --}}
                <p class="dynamic-data-price price priceSubTotal">IDR 0</p>
                <input type="hidden" name="sub_total" id="sub_total">
{{-- service fee --}}
                <p class="dynamic-data-price price-service-fee">IDR 0</p>
                <input type="hidden" name="service_fee" id="service_fee">
{{-- discount --}}
        {{-- <p id="price-discount" class="dynamic-data-price">IDR 0</p> --}}
        {{-- total price --}}
                <input type="hidden" name="total_price" id="total_price">
                <p class="dynamic-data-price price-total" id="pricing-total">IDR 0</p>
              </div>
            </div>
-->
    {{-- <table class="table table-borderless">
                <tbody>
                <tr>
                    <th scope="row">Subtotal</th>
                    <td class="dynamic-data-price priceSubTotal">IDR 0</td>
                    <input type="hidden" name="sub_total" id="sub_total">
                </tr>
                <tr>
                    <th scope="row">{{ trans('premium.facebook.label.service_fee') }}</th>
    <td class="dynamic-data-price price-service-fee">IDR 0</td>
    <input type="hidden" name="service_fee" id="service_fee">
    </tr>
    <tr>
        <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
        <td class="dynamic-data-price" id="price-total1">IDR 0</td>
        <input type="hidden" name="total_price" id="total_price">
    </tr>
    </tbody>
    </table>
    <div class="form-group term-condition-group" style="margin: 0 auto; margin-bottom: 1rem;">
        <div class="col-12  total-pay">
            <input type="checkbox" name="term_conditions" value="1">
            <span style="margin-left: 11px;">{{ trans('premium.facebook.label.sk_disclaimer') }}
                <a href="" data-toggle="modal" data-target=".modal-term-condition" require>{{ trans('premium.facebook.label.sk') }}</a>
                {{ trans('premium.facebook.label.applied') }}
                <a href="" data-toggle="modal" data-target=".modal-disclaimer-ads" require>{{ trans('premium.facebook.label.disclaimer') }}</a>
            </span>
        </div>
    </div>
    <!-- Modal S&K DAN DISCLAIMER -->
    <div class="col-12 col-lg-8 offset-2 total-pay">
        <div class="modal fade modal-term-condition" tabindex="-1" role="dialog" aria-labelledby="termConditionModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="modal-agreement">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span id="close-btn-modal-ads-info" class="float-right" aria-hidden="true">&times;</span>
                    </button>
                    <h5>{{ trans('premium.facebook.modal_sk.sk') }}</h5>
                    <hr />
                    <ul class="bullet-list">
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat1') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat2') }} <a href="https://www.facebook.com/policies/ads" target="_blank"><span>{{ trans('premium.facebook.modal_sk.syarat_here') }}</span></a>
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat3') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat4') }} <a href="https://www.facebook.com/help/187316341316631?helpref=related" target="_blank"><span>{{ trans('premium.facebook.modal_sk.syarat_here') }}</span></a>
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat5') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat6') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat7') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat8') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat9') }}
                        </li>
                        @if (app()->getLocale() == 'id')
                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat10') }}
                        </li>
                        @endif

                        <li>
                            {{ trans('premium.facebook.modal_sk.syarat11') }}<strong>+62 812-1111-9655</strong> {{ trans('premium.facebook.modal_sk.syarat_or') }} <a href="mailto:info#gomodo.tech"><span>info@gomodo.tech</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DISCLAIMER -->
    <div class="col-12 col-lg-8 offset-2 total-pay">
        <div class="modal fade modal-disclaimer-ads" tabindex="-1" role="dialog" aria-labelledby="termConditionModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="modal-agreement">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span id="close-btn-modal-ads-info" class="float-right" aria-hidden="true">&times;</span>
                    </button>
                    <h5>Disclaimer</h5>
                    <hr />
                    <ul class="bullet-list">
                        <li>
                            {{ trans('premium.facebook.modal_disclaimer.disclaimer1') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_disclaimer.disclaimer2') }}
                        </li>
                        <li>
                            {{ trans('premium.facebook.modal_disclaimer.disclaimer3') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center total-pay">
        <button type="button" class="btn btn-primary citysubmit stepPremium5 intro-js-2 btn-review" onclick="introjsstart2()" style="display:none;">Review My Order</button>
        <button type="button" class="btn btn-primary citysubmit no-intro btn-review">Review My Order</button>
    </div>
</div>
--}}
</div>
{{--</form>--}}

</div>

<div class="widget available-adds" id="google">
    <div class="row">
        <div class="col-12 col-lg-3">
            <img id="img-ads" src="{{asset('dest-operator/img/google-ads@2x.jpg')}}" />
        </div>
        <div class="col-12 col-lg-6 text-title-ads">
            <h4><strong>{{ __('premium.google_ads') }}</strong></h4>
            <p>{{ trans('premium.banner.banner_google') }}</p>
        </div>
        <div class="col-12 col-lg-3 text-center justify-content-center">
            @if (env('PREMIUM_DISABLE_GOOGLE_ADS'))
            <button class="btn btn-primary btn-buy-now" id="btn-buy-now" type="button" style="background-color: #7F7F7F; border-color: #7F7F7F; margin-bottom:1rem;" disabled>{{ trans('premium.banner.coming_soon') }}</button>
            @else
            <button class="btn btn-primary btn-buy-now" type="button" style="margin-bottom:1rem;" data-ads="Google Ads">{{ trans('premium.banner.buy_now') }}</button>
            @endif
            {{-- <button class="btn btn-primary btn-howbuy-now" href="facebook-ads.html" type="button" style="margin-bottom:1rem; display: none;" onclick="introjsstart()">{{  trans('premium.facebook.label.order_tutorial')  }}</button> --}}
        </div>
        <form class="form-ads form-wizard form-google col" data-ads-type="google">
            {{ csrf_field() }}
            <input type="hidden" name="category_ads" value="Google Ads" />
            <input type="hidden" name="myvoucher_id">
            <h3>1</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.1.title') }}</h4>
                    <div>{{ __('premium.google.steps.1.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.url.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.url.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="url" class="form-control gpreview" name="url" value="http://" required data-gpreview="url" placeholder="{{ __('premium.google.form.url.placeholder') }}" />
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.business_category.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.business_category.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select name="business_category[]" class="form-control select2-ads select-business-category" required>
                            <option value="" disabled selected>{{ __('premium.google.form.business_category.placeholder') }}</option>
                            @foreach ($business_categories as $value)
                            <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
            <h3>2</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.2.title') }}</h4>
                    <div>{{ __('premium.google.steps.2.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.country.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.country.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select name="country" class="form-control select2-ads" required>
                            <option value="" disabled selected>{{ __('premium.google.form.country.placeholder') }}</option>
                            @foreach ($country as $value)
                            <option value="{{ $value->id_country }}">{{ $value->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.state.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.state.tooltip') }}"></i></h5>
                    <div class="form-group row align-items-center">
                        <div class="col-1 state-loading" style="display: none;"><i class="fa fa-spinner fa-spin"></i></div>
                        <div class="col">
                            <select name="state[]" class="form-control select2-ads select-state" required multiple data-placeholder="{{ __('premium.google.form.state.placeholder') }}" disabled>
                            </select>
                        </div>
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.city.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.city.tooltip') }}"></i></h5>
                    <div class="form-group row align-items-center">
                        <div class="col-1 city-loading" style="display: none;"><i class="fa fa-spinner fa-spin"></i></div>
                        <div class="col">
                            <select name="city[]" class="form-control select2-ads select-city" required multiple data-placeholder="{{ __('premium.google.form.city.placeholder') }}" disabled>
                            </select>
                        </div>
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.language.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.language.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <select name="language[]" class="form-control select2-ads select-language" required multiple data-placeholder="{{ __('premium.google.form.language.placeholder') }}">
                            @foreach (\App\Models\Ads::$languages as $key => $lang)
                            <option value="{{ $key }}">{{ $lang[app()->getLocale()] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
            <h3>3</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.3.title') }}</h4>
                    <div>{{ __('premium.google.steps.3.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.budget.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.budget.tooltip') }}"></i></h5>
                    <div class="input-group">
                        <div class="input-group-prepend mr-0">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" class="form-control price number google-min-budget" maxlength="11" placeholder="{{ __('premium.google.form.budget.placeholder') }}" />
                        <input type="hidden" name="min_budget" class="google-min-budget-h" />
                    </div>
                    <div class="row my-3">
                        <div class="col-12 col-sm-6">
                            <h5 class="mt-3">{{ __('premium.google.form.start_date.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.start_date.tooltip') }}"></i></h5>
                            <div class="input-group mb-3">
                                <input id="date_start" name="start_date" data-provide="datepicker" type="text" class="form-control date-picker bg-white" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly placeholder="{{ __('premium.google.form.start_date.placeholder') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text border-right">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h5 class="mt-3">{{ __('premium.google.form.end_date.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.end_date.tooltip') }}"></i></h5>
                            <div class="input-group mb-3">
                                <input id="date_final" name="end_date" data-provide="datepicker" type="text" class="form-control date-picker bg-white" value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" readonly placeholder="{{ __('premium.google.form.end_date.placeholder') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <table class="table table-borderless text-left">
                            <tbody>
                                <tr>
                                    <th scope="row">Subtotal</th>
                                    <td class="dynamic-data-price priceSubTotal">IDR 0</td>
                                    <input type="hidden" name="sub_total" class="sub_total">
                                </tr>
                                <tr>
                                    <th scope="row">{{ trans('premium.facebook.label.service_fee') }}</th>
                                    <td class="dynamic-data-price price-service-fee">IDR 0</td>
                                    <input type="hidden" name="service_fee" class="service_fee">
                                </tr>
                                <tr>
                                    <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
                                    <td class="dynamic-data-price price-total1">IDR 0</td>
                                    <input type="hidden" name="total_price" class="total_price">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <h3>4</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.4.title') }}</h4>
                    <div>{{ __('premium.google.steps.4.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.title1.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.title1.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="text" class="form-control gpreview" name="title1" placeholder="{{ __('premium.google.form.title1.placeholder') }}" data-gpreview="title1" />
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.title2.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.title2.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="text" class="form-control gpreview" name="title2" placeholder="{{ __('premium.google.form.title2.placeholder') }}" data-gpreview="title2" />
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.description.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.description.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <textarea name="description" class="form-control gpreview" placeholder="{{ __('premium.google.form.description.placeholder') }}" rows="3" data-gpreview="description"></textarea>
                    </div>
                    <h5 class="mt-3">{{ __('premium.google.form.phone_number.label') }} <i class="fa fa-question-circle-o fs-14" data-toggle="tooltip" title="{{ __('premium.google.form.phone_number.tooltip') }}"></i></h5>
                    <div class="form-group">
                        <input type="text" class="form-control number gpreview" name="phone_number" placeholder="{{ __('premium.google.form.phone_number.placeholder') }}" data-gpreview="phone_number" />
                    </div>
                </div>
            </section>
            <h3>5</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.5.title') }}</h4>
                    <div>{{ __('premium.google.steps.5.subtitle') }}</div>
                    <div class="mt-3 g-ads-preview">
                        <a href="#">
                            <span class="g-preview-title1">Judul 1</span> | <span class="g-preview-title2">Judul 2</span>
                        </a>
                        <br />
                        <span class="ad">{{ __('premium.google.ads') }}</span>
                        <span class="display-url"><span class="g-preview-url">http://mygomodo.com</span></span>
                        <span class="display-url text-muted ml-1"><span class="g-preview-phone_number">080000</span></span>
                        <br />
                        <span class="description">
                            <span class="g-preview-description">Deskripsi Iklan</span>
                        </span>
                    </div>
                </div>
            </section>
            <h3>6</h3>
            <section class="text-center">
                <div class="container">
                    <h4>{{ __('premium.google.steps.6.title') }}</h4>
                    <div>{{ __('premium.google.steps.6.subtitle') }}</div>
                    <h5 class="mt-3">{{ __('premium.google.form.payment_method.label') }}</h5>
                    <div class="form-group">
                        <select name="payment_method" id="" class="form-control">
                            <option value="" selected disabled>{{ __('premium.google.form.payment_method.placeholder') }}</option>
                            @foreach (__('premium.google.form.payment_method.values') as $method => $name)
                            <option value="{{ $method }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row text-left">
                        <div class="col-md-6">
                            <img src="{{asset('dest-operator/img/gxp-img.svg')}}">
                            <label id="gxp-total-balance">IDR {{ number_format($gxp_sum['gxp'], 0) }}</label>
                            <input id="gxp-total-balance-hidden" type="hidden" value="{{ number_format($gxp_sum['gxp'],0) }}">
                        </div>
                        <div class="col-md-6 text-right">
                            <label class="el-switch">
                                <span style="line-height: 1.6;vertical-align: top;">{{ trans('premium.facebook.label.use_gxp_slider') }}</span>
                                <input id="use-gxp2" type="checkbox" value="true" name="using_gxp" class="d-none">
                                <span class="el-switch-style"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-2 promo-voucher-google">
                        <div class="text-left">
                            <a href="javascript:void(0);" class="promo-remove" style="display: none;">{{ trans('premium.facebook.label.remove_promocode') }}</a><br>
                            <p id="reason-ads" class="float-none d-inline">{{ trans('premium.facebook.label.promo_code') }}</p>
                            <span id="remove-voucher" class="float-right" style="display: none;">{{ trans('premium.facebook.label.remove_promo_code') }}</span>
                        </div>
                        <div class="text-left">
                            <div class="input-group mb-1 mt-2 voucher-section">
                                <input type="text" class="form-control google-ads-voucher" placeholder="{{ trans('premium.facebook.label.p_promo_code') }}">
                                <input type="hidden" name="code">
                                <input type="hidden" name="gxp_value">
                                <input type="hidden" name="gxp_amount">
                                <input type="hidden" name="promo_amount">
                                <input type="hidden" name="cashback_amount">
                                <input type="hidden" name="grand_total">
                                <input type="hidden" name="gxp_balance">
                                <div class="input-group-append">
                                    <button id="btn-promo-code" class="btn btn-primary submit-voucher mb-1" type="button">{{ __('premium.google.form.apply_voucher') }}</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a id="getvoucher" href="javascript:void();" class="float-left fs-14 use-the-voucher" onclick="introJs().exit()">{{ __('premium.google.form.use_my_voucher') }}</a>
                        </div>
                    </div>

                    <table class="table table-borderless text-left" id="dataPrice">
                        <tbody>
                            <tr>
                                <th scope="row">Subtotal</th>
                                <td class="dynamic-data-price priceSubTotal">IDR 0</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ trans('premium.facebook.label.service_fee') }}</th>
                                <td class="dynamic-data-price price-service-fee">IDR 0</td>
                            </tr>
                            {{-- <tr>
                          <th scope="row">{{ trans('premium.facebook.label.gxp_credits') }}</th>
                            <td id="gxp-balance" class="dynamic-data-price">IDR 0</td>
                            </tr> --}}
                            {{-- <tr>
                          <th scope="row">Voucher</th>
                          <td id="price-discount" class="dynamic-data-price">IDR 0</td>
                        </tr> --}}
                        </tbody>
                    </table>

                    <hr>
                    <table class="table table-borderless text-left">
                        <tbody>
                            <tr>
                                <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
                                <td class="dynamic-data-price price-total" id="totaly2">IDR 0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </form>
    </div>
</div>

