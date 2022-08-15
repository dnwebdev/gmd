<!-- new update alert -->
<div class="card card-body border-top-primary" id="gamificationProgressbar" style="display: none">
    <div class="text-center">
        <h6 class="mb-0 font-weight-semibold">{{ trans('gamification.progress-bar.caption') }}</h6>
        <p class="text-muted">{!! trans('gamification.progress-bar.desc') !!}</p>
    </div>
    
    <div class="row">
        <div class="col d-flex align-items-center mt-1">
            <div class="progress w-100">
                <div class="progress-bar gamification-progress-bar bg-primary" style="width: 0%">
                </div>
                <div class="gamification-progress-bar-percent">
                    0%
                </div>
            </div>
        </div>
        <div class="col-md-auto mt-1">
            <button id="btn-show-gamification" type="button" class="btn btn-primary legitRipple withdrawBtn w-100" data-toggle="modal" data-target="#gamificationModal">
                {!! trans('gamification.progress-bar.button') !!}
            </button>
        </div>
    </div>
</div>
{{-- @include('dashboard.company.product.modal_cropping_image') --}}
      
<!-- Gamification Modal -->
<div id="gamificationModal" class="modal fade show">
    <div class="modal-dialog modal-dialog-scrollable modal-sm modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header text-center">
            <h5 class="modal-title w-100 font-weight-bold"></h5>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
    
        <div class="modal-body">
                <!-- Business Category -->
                <form action="" class="gamification" id="business_type_gamification">
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.business-type.category') !!}</label>
                            <select class="form-control select-max-10 form-control-sm required" data-container-css-class="select-sm" multiple="multiple" name="business_category[]">
                                @if(app()->getLocale() == 'id')
                                    @foreach(\App\Models\BusinessCategory::orderBy('business_category_name_id')->get() as $business)
                                        <option 
                                        {{in_array($business->id, auth()->user()->company->categories()->pluck("id")->toArray()) ? "selected" : '' }}
                                        value="{{$business->id}}">{{$business->business_category_name_id}}</option>
                                    @endforeach
                                @else
                                    @foreach(\App\Models\BusinessCategory::orderBy('business_category_name')->get() as $business)
                                        <option 
                                        {{in_array($business->id, auth()->user()->company->categories()->pluck("id")->toArray()) ? "selected" : '' }}
                                        value="{{$business->id}}">{{$business->business_category_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.business-type.type') !!}</label>
                            <select class="form-control select2-general required" name="ownership_status">
                                <option value="corporate" {{auth()->user()->company->ownership_status == 'corporate'?'selected':''}}>Corporate</option>
                                <option value="personal" {{auth()->user()->company->ownership_status == 'personal'?'selected':''}}>Personal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn w-100 btn-primary btn-next-step mt-3">{!! trans('gamification.general.submit') !!}</label>
                    </div>
                </form>
        
                <!-- Descriptions -->
                <form action="" class="gamification" id="about_company_gamification">
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="slogan">{!! trans('gamification.about-my-bussiness.sort-desc') !!}</label>
                            <input name="short_description" type="text" class="form-control validation input-wordcounter required" placeholder="{!! trans('gamification.about-my-bussiness.sort-desc-placeholder') !!}" value="{{auth()->user()->company->short_description}}" maxlength="150" required>
                        </div>
                        
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label required" for="description">{!! trans('gamification.about-my-bussiness.description') !!}</label>
                            <textarea name="about" rows="3" cols="3" class="form-control validation required tiny_mce_gamification" placeholder="{!! trans('gamification.about-my-bussiness.description-placeholder') !!}" required>{!! auth()->user()->company->about !!}</textarea>
                        </div>
            
                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>
        
                <!-- Address -->
                <form action="" class="gamification" id="address_company_gamification">
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.bussiness-address.country') !!}</label>
                            <select class="form-control select2-general required" id="country_search_gamification" data-placeholder="{!! trans('gamification.bussiness-address.country-placeholder') !!}">
                                <option selected disabled>{!! trans('gamification.bussiness-address.country-placeholder') !!}</option>
                                @foreach (App\Models\Country::all() as $item)
                                    <option value="{{ $item->id_country }}">{{ $item->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.bussiness-address.province') !!}</label>
                            <select class="form-control select2-general required" id="state_search_gamification" data-placeholder="{!! trans('gamification.bussiness-address.province-placeholder') !!}">
                                <option selected disabled>{!! trans('gamification.bussiness-address.province-placeholder') !!}</option>
                            </select>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.bussiness-address.city') !!}</label>
                            <select class="form-control select2-general required" id="city_search_gamification" name="id_city" data-placeholder="{!! trans('gamification.bussiness-address.city-placeholder') !!}">
                                <option selected disabled>{!! trans('gamification.bussiness-address.city-placeholder') !!}</option>
                            </select>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label for="pmeeting">{{ trans('setting_provider.address_company') }}</label><br/>
                            <input type="hidden" name="long" id="longGamify" value="{{auth()->user()->company->long}}">
                            <input type="hidden" name="lat" id="latGamify" value="{{auth()->user()->company->lat}}">
                            <input type="hidden" name="google_place_id" id="google_place_id_gamification" value="{{auth()->user()->company->google_place_id}}">
                        </div>
                        <div id="mapGoogleGamification" style="width: 100%; height: 300px;"></div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="detail-address">{!! trans('gamification.bussiness-address.detail') !!}</label>
                            <textarea name="address_company" type="text" class="form-control validation required tiny_mce_gamification_notoolbar" placeholder="{!! trans('gamification.bussiness-address.detail-placeholder') !!}" required>{{auth()->user()->company->address_company}}</textarea>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="zip-code">{!! trans('gamification.bussiness-address.postal-code') !!}</label>
                            <input name="postal_code" type="text" class="form-control validation required number" placeholder="{!! trans('gamification.bussiness-address.postal-code-placeholder') !!}" value="{{auth()->user()->company->postal_code}}" maxlength="5" required>
                        </div>
            
                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>
        
                <!-- Contact -->
                <form action="" class="gamification" id="contact_us_gamification">
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="email">{!! trans('gamification.contact-us.email') !!}</label>
                            <input name="email_company" type="email" value="{{auth()->user()->company->email_company}}" class="form-control validation required" placeholder="{!! trans('gamification.contact-us.email-placeholder') !!}" required>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="phone">{!! trans('gamification.contact-us.phone') !!}</label>
                            <input name="phone_company" type="text" value="{{auth()->user()->company->phone_company??auth()->user()->phone}}" class="form-control validation required number" placeholder="{!! trans('gamification.contact-us.phone-placeholder') !!}" maxlength="13" required>
                        </div>
                        <h3 class="text-primary mt-3">{!! trans('gamification.contact-us.social-media') !!}</h3>
                        <div class="form-group-feedback form-group-feedback-left">
                            <input name="facebook_company" type="text" class="form-control form-control-lg" placeholder="Facebook" value="{{auth()->user()->company->facebook_company}}" maxlength="50">
                            <div class="form-control-feedback form-control-feedback-lg">
                                <i class="icon-facebook"></i>
                            </div>
                        </div>
                        <div class="form-group-feedback form-group-feedback-left">
                            <input type="text" class="form-control form-control-lg" placeholder="Instagram" name="instagram_company" value="{{auth()->user()->company->twitter_company}}" maxlength="50">
                            <div class="form-control-feedback form-control-feedback-lg">
                                <i class="icon-instagram"></i>
                            </div>
                        </div>
            
                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>
        
                <!-- Logo -->
                <form action="" class="gamification" id="company_logo_gamification">
                    <div class="step">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="custom-radio-control custom-radio">
                                        <input type="radio" id="custom-logo-gamification" name="operator-logo" value="custom" class="custom-control-input"
                                        {{!in_array(auth()->user()->company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png'])?'checked':null}}>
                                        <label class="custom-control-label" for="custom-logo-gamification">{!! trans('gamification.business-logo.custom-logo') !!}</label>
                                        <div class="custom-block is-open">
                                            <div class="custom-logo-block d-lg-flex align-items-end justify-content-center">
                                                <div class="custom-logo-image position-relative">
                                                    @if(in_array(auth()->user()->company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png']))
                                                        <label class="custom-input-cropping-image">
                                                            <input type="file" name="logo" class="cropping-image
                                                            custom-file-input" data-allowed-file-extensions="jpg jpeg png" accept="image/*"
                                                            data-disable-remove="true"
                                                            data-id="0"
                                                            data-ratio="1"
                                                            data-array="false"
                                                            data-type="logo"/>
                                                            <i class="fa fa-cloud-upload"></i>
                                                            <span>{!! trans('gamification.general.upload') !!}</span>
                                                        </label>
                                                        <div class="result-0 file-result-ratio-one">
                                                                
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="input-group mb-3 display-none group-input-image-0 input-group-cropping-image">
                                                                <input type="text" class="form-control label-data-name-file-0 overflow-hidden" readonly>
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-0" data-id="0">
                                                                        {{ trans('product_provider.remove_image') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if(count(explode('/', auth()->user()->company->logo)) > 1)
                                                            <label class="custom-input-cropping-image">
                                                                <input type="file" name="logo" class="cropping-image custom-file-input"
                                                                    data-allowed-file-extensions="jpg jpeg png"
                                                                    data-disable-remove="true"
                                                                    data-id="0"
                                                                    data-ratio="1"
                                                                    data-array="false"
                                                                    data-type="logo"/>
                                                                <i class="fa fa-cloud-upload"></i>
                                                                <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                            </label>
                                                            <div class="result-0 file-result-ratio-one">
                                                                <img src="{{ auth()->user()->company->logo ? asset(strpos(auth()->user()->company->logo, 'dest-operator') !== false ? auth()->user()->company->logo : 'uploads/company_logo/'.auth()->user()->company->logo ) : '' }}"
                                                                    alt="logo">
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="input-group mb-3 {{ empty(auth()->user()->company->logo) ? 'display-none' : '' }} group-input-image-0 input-group-cropping-image">
                                                                    <input type="text"
                                                                        class="form-control label-data-name-file-0 overflow-hidden"
                                                                        value="img-logo" readonly>
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                                class="btn btn-danger btn-remove-image-cropper remove-crop-image-0"
                                                                                data-id="0">
                                                                            {{ trans('product_provider.remove_image') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <label class="custom-input-cropping-image">
                                                                <input type="file" class="cropping-image custom-file-input" name="logo"
                                                                    data-allowed-file-extensions="jpg jpeg png"
                                                                    data-disable-remove="true"
                                                                    data-id="0"
                                                                    data-ratio="1"
                                                                    data-array="false"
                                                                    data-type="logo"/>
                                                                <i class="fa fa-cloud-upload"></i>
                                                                <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                            </label>
                                                            <div class="result-0 file-result-ratio-one">
                                                                <img src="{{ auth()->user()->company->logo ? asset(strpos(auth()->user()->company->logo, 'dest-operator') !== false ? auth()->user()->company->logo : 'uploads/company_logo/'.auth()->user()->company->logo ) : '' }}"
                                                                    alt="logo">
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="input-group mb-3 {{ empty(auth()->user()->company->logo) ? 'display-none' : '' }} group-input-image-0 input-group-cropping-image">
                                                                    <input type="text"
                                                                        class="form-control label-data-name-file-0 overflow-hidden"
                                                                        value="img-logo" readonly>
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                                class="btn btn-danger btn-remove-image-cropper remove-crop-image-0"
                                                                                data-id="0">
                                                                            {{ trans('product_provider.remove_image') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                </div>
                                                <div class="modal fade modal-"></div>
                                            </div>
                                            <div class="custom-logo-helper">
                                                <p>{!! trans('gamification.business-logo.optimal-resolution') !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">

                                    <div class="custom-radio-control custom-radio">
                                        <input type="radio" id="default-logo-gamification" name="operator-logo"
                                            value="default"
                                            class="custom-control-input"
                                            {{in_array(auth()->user()->company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png']) || empty(auth()->user()->company->logo) ?'checked':null}}>
                                        <label class="custom-control-label"
                                            for="default-logo-gamification">{{ trans('setting_provider.default_logo') }}</label>
                                        <div class="custom-block is-open">
                                            <div class="custom-default-logo">
                                                <label for="dlogo-1-gamification">
                                                    <input type="radio" id="dlogo-1-gamification"
                                                        value="dest-operator/img/logo1.png"
                                                        name="default_logo" 
                                                        {{auth()->user()->company->logo === "dest-operator/img/logo1.png" || empty(auth()->user()->company->logo) ? 'checked' : '' }}>
                                                    <img src="{{ asset('dest-operator/img/logo1.png') }}"
                                                        alt="CAMPING">
                                                </label>
                                                <label for="dlogo-2-gamification">
                                                    <input type="radio" id="dlogo-2-gamification"
                                                        value="dest-operator/img/logo2.png"
                                                        name="default_logo"
                                                        {{auth()->user()->company->logo === "dest-operator/img/logo2.png" ? 'checked' : '' }}>
                                                    <img src="{{ asset('dest-operator/img/logo2.png') }}"
                                                        alt="DIVER">
                                                </label>
                                                <label for="dlogo-3-gamification">
                                                    <input type="radio" id="dlogo-3-gamification"
                                                        value="dest-operator/img/logo3.png"
                                                        name="default_logo"
                                                        {{auth()->user()->company->logo === "dest-operator/img/logo3.png" ? 'checked' : '' }}>
                                                    <img src="{{ asset('dest-operator/img/logo3.png') }}"
                                                        alt="SURFING">
                                                </label>
                                                <label for="dlogo-4-gamification">
                                                    <input type="radio" id="dlogo-4-gamification"
                                                        value="dest-operator/img/logo4.png"
                                                        name="default_logo"
                                                        {{auth()->user()->company->logo === "dest-operator/img/logo4.png" ? 'checked' : '' }}>
                                                    <img src="{{ asset('dest-operator/img/logo4.png') }}"
                                                        alt="WATERSPORT">
                                                </label>
                                                <label for="dlogo-5-gamification">
                                                    <input type="radio" id="dlogo-5-gamification"
                                                        value="dest-operator/img/logo5.png"
                                                        name="default_logo"
                                                        {{auth()->user()->company->logo === "dest-operator/img/logo5.png" ? 'checked' : '' }}>
                                                    <img src="{{ asset('dest-operator/img/logo5.png') }}"
                                                        alt="HIKING">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

            
                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>
        
                <!-- Banner -->
                <form action="" class="gamification" id="company_banner_gamification">
                    <div class="step">
                        <div class="widget-form row">
                            <div class="col-12">
                                <div class="form-group">

                                    <div class="custom-radio-control custom-radio">
                                        <input type="radio" id="custom-banner-gamification" name="operator-banner"
                                            value="custom"
                                            class="custom-control-input"
                                            {{!in_array(auth()->user()->company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg'])?'checked':null}}>
                                        <label class="custom-control-label"
                                            for="custom-banner-gamification">{{ trans('setting_provider.custom_banner') }}</label>
                                        <div class="custom-block is-open">
                                            <div class="custom-banner-holder position-relative text-center">
                                                @if(in_array(auth()->user()->company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg']))
                                                    <label class="custom-input-cropping-image">
                                                        <input type="file" name="banner" name="banner" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png" data-id="1" data-ratio="1.78" data-type="banner" data-array="false" accept="image/*">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{!! trans('gamification.general.upload') !!}</span>
                                                    </label>
                                                    <div class="result-1 file-result">
                                                    </div>
                                                    <div class="text-center">
                                                        <div class="input-group mb-3 display-none group-input-image-1 input-group-cropping-image">
                                                            <input type="text" class="form-control label-data-name-file-1 overflow-hidden" readonly>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-1" data-id="1">
                                                                    {{ trans('product_provider.remove_image') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <label class="custom-input-cropping-image">
                                                        <input type="file" name="banner"
                                                                class="cropping-image custom-file-input"
                                                                data-allowed-file-extensions="jpg jpeg png"
                                                                data-id="1" data-ratio="1.78"
                                                                data-type="banner" data-array="false">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                    </label>
                                                    <div class="result-1 file-result">
                                                        @php
                                                            $count = count(explode('/', auth()->user()->company->banner));
                                                        @endphp
                                                        @if ($count > 1)
                                                            <img src="{{ auth()->user()->company->banner ? asset(strpos(auth()->user()->company->banner, 'dest-operator') !== false ? auth()->user()->company->banner : 'uploads/banners/'.auth()->user()->company->banner ) : '' }}"
                                                                    alt="">
                                                        @else
                                                            <img src="{{ auth()->user()->company->banner ? asset('uploads/banners/'.auth()->user()->company->banner) : '' }}"
                                                                    alt="Image">
                                                        @endif
                                                    </div>
                                                    <div class="text-center">
                                                        <div class="input-group mb-3 {{ empty(auth()->user()->company->banner) ? 'display-none' : '' }} group-input-image-1 input-group-cropping-image">
                                                            <input type="text"
                                                                    class="form-control label-data-name-file-1 overflow-hidden"
                                                                    value="image-banner" readonly>
                                                            <div class="input-group-append">
                                                                <button type="button"
                                                                        class="btn btn-danger btn-remove-image-cropper remove-crop-image-1"
                                                                        data-id="1">
                                                                    {{ trans('product_provider.remove_image') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="custom-logo-block d-lg-flex align-items-end">
                                                <div class="custom-logo-helper">
                                                    <p>{{ trans('setting_provider.image_term3') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">

                                    <div class="custom-radio-control custom-radio">
                                        <input type="radio" id="default-banner-gamification" name="operator-banner"
                                            value="default"
                                            class="custom-control-input"
                                            {{in_array(auth()->user()->company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg']) || empty(auth()->user()->company->banner) ?'checked':null}}>
                                        <label class="custom-control-label"
                                            for="default-banner-gamification">{{ trans('setting_provider.default_banner') }}</label>
                                        <div class="custom-block is-open">
                                            <div class="custom-default-banner">
                                                <label for="dbanner-1-gamification">
                                                    <input type="radio" id="dbanner-1-gamification"
                                                        value="dest-operator/img/banner1.jpg"
                                                        name="default_banner" {{auth()->user()->company->banner=='dest-operator/img/banner1.jpg' || empty(auth()->user()->company->banner) ?'checked':null}}>
                                                    <img src="{{ asset('dest-operator/img/banner1.jpg') }}"
                                                        alt="SURFING">
                                                </label>
                                                <label for="dbanner-2-gamification">
                                                    <input type="radio" id="dbanner-2-gamification"
                                                        value="dest-operator/img/banner2.jpg"
                                                        name="default_banner" {{auth()->user()->company->banner=='dest-operator/img/banner2.jpg'?'checked':null}}>
                                                    <img src="{{ asset('dest-operator/img/banner2.jpg') }}"
                                                        alt="SURFING">
                                                </label>
                                                <label for="dbanner-3-gamification">
                                                    <input type="radio" id="dbanner-3-gamification"
                                                        value="dest-operator/img/banner3.jpg"
                                                        name="default_banner" {{auth()->user()->company->banner=='dest-operator/img/banner3.jpg'?'checked':null}}>
                                                    <img src="{{ asset('dest-operator/img/banner3.jpg') }}"
                                                        alt="SURFING">
                                                </label>
                                                <label for="dbanner-4-gamification">
                                                    <input type="radio" id="dbanner-4-gamification"
                                                        value="dest-operator/img/banner4.jpg"
                                                        name="default_banner" {{auth()->user()->company->banner=='dest-operator/img/banner4.jpg'?'checked':null}}>
                                                    <img src="{{ asset('dest-operator/img/banner4.jpg') }}"
                                                        alt="SURFING">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>
        
                <!-- SEO -->
                <form action="" class="gamification" id="seo_gamification" >
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label for="title" class="tooltips"
                                title="{{ trans('setting_provider.site_title_tooltip') }}">{!! trans('gamification.seo.title') !!}
                            <span class="fa fa-info-circle"></span></label>
                            <input name="title" type="text" class="form-control validation required" placeholder="{!! trans('gamification.seo.title-placeholder') !!}" value="{{auth()->user()->company->title}}" maxlength="70" required>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label for="description" class="tooltips"
                                title="{{ trans('setting_provider.site_description_tooltip') }}">{!! trans('gamification.seo.desc') !!}
                            <span class="fa fa-info-circle"></span></label>
                            <textarea name="description" rows="3" cols="3" class="form-control validation input-wordcounter required" placeholder="{!! trans('gamification.seo.desc-placeholder') !!}" maxlength="150" required>{{auth()->user()->company->description}}</textarea>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label for="keywords" class="tooltips"
                                title="{{ trans('setting_provider.site_keyword_tooltip') }}">{!! trans('gamification.seo.keywords') !!}
                            <span class="fa fa-info-circle"></span></label>
                            <select multiple="multiple" class="form-control select-multiple-comma-max-100 required" data-fouc name="keywords[]">
                                @if (auth()->user()->company->keywords && count(explode(',', auth()->user()->company->keywords)) > 0)
                                    @foreach (explode(',', auth()->user()->company->keywords) as $item)
                                        <option value="{{trim($item)}}" selected>{{trim($item)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
            
                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>

                <!-- Bank Account -->
                <form action="" class="gamification" id="bank_account_gamification">
                    <div class="step">
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label>{!! trans('gamification.bank-account.bank-name') !!}</label>
                            <select class="form-control select2-general required" placeholder="{!! trans('gamification.bank-account.bank-name-placeholder') !!}" name="bank">
                                <option selected disabled>{{ trans('bank_provider.select_bank') }}</option>
                                @foreach (\App\Models\CompanyBank::$banks as $key => $value)
                                    <option {{auth()->user()->company->bank && auth()->user()->company->bank->bank ==$key ?'selected':''}} value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="account-owner">{!! trans('gamification.bank-account.name') !!}</label>
                            <input name="bank_account_name" value="{{auth()->user()->company->bank ? auth()->user()->company->bank->bank_account_name:''}}" type="text" class="form-control validation required" placeholder="{!! trans('gamification.bank-account.name-placeholder') !!}" maxlength="50" required>
                        </div>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            <label class="col-form-label" for="account-owner">{!! trans('gamification.bank-account.number') !!}</label>
                            <input name="bank_account_number" value="{{auth()->user()->company->bank ? auth()->user()->company->bank->bank_account_number:''}}" type="text" class="form-control validation required number" placeholder="{!! trans('gamification.bank-account.number-placeholder') !!}" maxlength="15" required>
                        </div>
                        <h3 class="text-primary mt-3 text-center">{!! trans('gamification.bank-account.upload') !!}</h3>
                        <div class="form-group-feedback form-group-feedback-right mt-3">
                            {{-- <div action="#" class="dropzone dz-clickable" id="dropzone_accepted_files"><div class="dz-default dz-message"><span>Klik disini untuk unggah foto buku rekening </span></div></div> --}}
                            <input name="bank_account_document"
                                    type="file"
                                    accept="image/x-png,image/jpg,image/jpeg"
                                    class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                    @if(auth('web')->user()->company->bank && auth('web')->user()->company->bank->bank_account_document)
                                    data-default-file="{{asset('uploads/bank_document/'.auth('web')->user()->company->bank->bank_account_document)}}"
                                    @endif
                                    data-allowed-file-extensions="png jpg jpeg"
                                    data-max-file-size="2M"
                                    data-show-remove="true">
                            <input type="hidden" name="deleted_files[]">
                            <span class="form-text text-muted">{!! trans('gamification.general.banner-upload-term') !!}</span>
                        </div>

                        <div class="row mt-3">
                            {{-- <button type="button" class="btn btn-link text-green-klhk legitRipple btn-prev-step col-5">Sebelumnya</button> --}}
                            <button type="submit" class="btn w-100 btn-primary btn-next-step col-7 margin-auto">{!! trans('gamification.general.submit') !!}</label>
                        </div>
                    </div>
                </form>

                <!-- Success -->
                <form action="" class="gamification" id="finish_gamification">
                    <div class="step">
                        <div class="text-center">
                            <img src="{{ asset('dest-operator/reskin-assets/img/gamify-success.svg') }}" alt="Success" class="img-fluid pt-3 pb-4">
                        </div>

                        <div class="row mt-3">
                            <button type="button" class="btn w-100 btn-primary col-7 margin-auto" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </form>
    
            <!-- <form action="/file-upload" class="dropzone">
            <div class="fallback">
                <input name="file" type="file" multiple />
            </div>
            </form> -->
            <!-- <form action="#" class="dropzone dz-clickable" id="dropzone_accepted_files"><div class="dz-default dz-message"><span>Drop files to upload <span>or CLICK</span></span></div></form> -->
            <!-- <form action="#" class="dropzone" id="dropzone_accepted_files"></form> -->
        </div>
        
    
        <div class="modal-footer justify-content-center mt-3 text-center d-block">
            {!! trans('gamification.general.footer') !!}
        </div>
        </div>
    </div>
</div>
<!-- /gamification Modal -->
@push('script')
    <script src="{{asset('js/gmap-gamification.js')}}"></script>

    <script >
        $(document).on('ready', function () {
            $(document).on('shown.bs.modal','#gamificationModal', function () {
               initGamificactionMap();
            });
            $(document).on('keyup', '.input-wordcounter', function(e){
                inputWordCounter($(this))
            })
        })
    </script>

@endpush
