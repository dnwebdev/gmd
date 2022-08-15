@extends('klhk.customer.master.index')

@section('additionalStyle')
    <link href="{{asset('additional/select2/css/typography.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/textfield.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2.min.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2-bootstrap.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/pmd-select2.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection">
    <link rel="stylesheet" href="{{asset('additional/default.css')}}">
    <link rel="stylesheet" href="{{asset('additional/default.date.css')}}">
    <link rel="stylesheet" href="{{asset('additional/insurance.css')}}">
    {{--    <link rel="stylesheet" href="{{ asset('css/custom-klhk.css') }}"> --}}{{-- Custom KLHK CSS --}}
@endsection

@section('content')
    {!! Form::open(['autocomplete'=>'off']) !!}
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                @if(request()->has('ref') && request('ref') ==='directory')
                    @php
                        if (request()->isSecure()){
                            $prefix = 'https://';
                        }else{
                            $prefix = 'http://';
                        }
                      $url = $prefix.env('APP_URL');
                        if (request()->has('ref-url')){
                            $ex = explode($prefix.env('APP_URL'),request('ref-url'));
                            if (count($ex)===2){
                                $url .= $ex[1];
                            }
                        }
                    @endphp
                    <li><a href="{{$url}}">Directory</a></li>
                @endif
                <li><a href="{{route('memoria.home')}}">{!! trans('customer.home.home') !!}</a></li>
                <li><a href="{{route('product.detail',['slug'=>$product->unique_code])}}">{{$product->product_name}}</a>
                </li>
                <li><a>{!! trans('customer.book.booking_form') !!}</a></li>
            </ul>
        </div>
        <div id="product-booking" class="container pb-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <img class="img-fluid w-100"
                                 src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                 alt="">
                        </div>
                        <div class="col-lg-10">
                            <h3>{{$product->product_name}}</h3>
                            <div class="box-product-tags py-3">
                                @if($product->tagValue)
                                    @forelse($product->tagValue->take(2) as $tag)
                                        @if($tag->tag)
                                            @if (App::getLocale() ==='id')
                                                <span class="badge badge-warning product-tags">{{$tag->tag->name_ind}}</span>
                                            @else
                                                <span class="badge badge-warning product-tags">{{$tag->tag->name}}</span>
                                            @endif

                                        @endif
                                    @empty
                                        <span class="badge badge-warning product-tags">Uncategorized</span>
                                    @endforelse
                                @endif
                            </div>
                            <div class="table-product">
                                <span>
                                     <img src="{{asset('img/pin.png')}}" alt="">
                                </span>
                                <span class="mr-2 fs-smaller">
                                    @if(app()->getLocale() == 'id')
                                        {{ $product->city?$product->city->city_name:'-' }}
                                    @else
                                        {{ $product->city?$product->city->city_name_en:'-' }}
                                    @endif


                                </span>
                                <span>
                                    <img src="{{asset('img/calendar.png')}}" alt="">
                                </span>
                                <span class="mr-2 fs-smaller">
                                     {{$departure_date_format}}
                                 </span>
                                <span>
                                    <img src="{{asset('img/person.png')}}" alt="">
                                </span>
                                <span class="mr-2 fs-smaller">
                                    {{ $pax}}
                                    {{ $pricing['price_display'] }}
                                </span>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card mt-3" id="booking-guest">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.guest') !!}</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            {!! Form::hidden('departure_date',$departure_date) !!}
                            {!! Form::hidden('pax',$pax) !!}
                            {!! Form::hidden('product',$product->unique_code) !!}
                            {!! Form::hidden('identity_number_type','ktp') !!}
                            <div class="md-form __parent_form">
                                <input type="text" id="full_name" class="form-control" name="full_name">
                                <label for="full_name">{!! trans('customer.book.full_name') !!} *</label>
                            </div>
                            <div class="md-form __parent_form">
                                <input type="email" id="email" class="form-control" name="email">
                                <label for="email">{!! trans('customer.book.email') !!} *</label>
                            </div>
                            {{-- Hide Temporary --}}
                            {{-- <div class="md-form __parent_form input-group mb-3">
                                <div class="input-group-prepend">
                                    <select name="identity_number_type" id="" class="mdb-select">
                                        <option value="ktp">KTP</option>
                                        <option value="passport">Passport</option>
                                    </select>
                                </div>
                                <input type="text" name="identity_number"
                                       class="form-control number md-form--with-placeholder"
                                       aria-label="Text input with dropdown button"
                                       placeholder="{{trans('customer.book.identity_number')}}">
                            </div>
                            <div class="md-form __parent_form pmd-textfield pmd-textfield-floating-label">
                                <select class="select-with-search pmd-select2" searchable="Search here.."
                                        name="country">
                                    <option value="" disabled
                                            selected>{!! trans('customer.book.choose_country') !!}</option>
                                    @foreach(\App\Models\Country::all() as $country)
                                        <option value="{{$country->id_country}}" {{$country->id_country==102?'selected':''}}>{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>--}}
                        </div>
                        <div class="col-lg-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="phone_number" class="form-control number" name="phone_number">
                                <label for="phone_number">{!! trans('customer.book.phone_number') !!} </label>
                            </div>
                            {{-- Hide Temporary --}}
                            {{-- <div class="md-form __parent_form">
                                <input type="text" id="address" class="form-control" name="address">
                                <label for="address">{!! trans('customer.book.address') !!} </label>
                            </div>
                            <div class="md-form __parent_form">
                                <input type="text" id="emergency_number" class="form-control number"
                                       name="emergency_number">
                                <label for="emergency_number">{{trans('customer.book.emergency_number')}} </label>
                            </div>
                            <div class="md-form __parent_form pmd-textfield pmd-textfield-floating-label">
                                <select class="select-with-search pmd-select2 " searchable="Search here.." name="city">
                                    <option value="" disabled
                                            selected>{!! trans('customer.book.choose_city') !!}</option>
                                </select>
                            </div> --}}
                        </div>
                    </div>
                    <h3 class="bold">{!! trans('customer.book.note_to_provider') !!}</h3>
                    <p class="bold">{!! trans('customer-klhk.book.you_could') !!}</p>
                    <div class="row">
                        <div class="col-12">
                            <div class="md-form __parent_form">
                                <textarea name="note" id="note" cols="30" rows="3"
                                          class="form-control md-textarea overflow-auto" maxlength="250"></textarea>
                                <label for="note">{!! trans('customer.book.your_message') !!}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @if($product->insurances()->active()->count()>0)
            <!--Assuransi -->
                <div class="card mt-3" id="insurance">
                    <div class="card-body">
                        <h3 class="bold mb-3">Asuransi</h3>
                        @foreach($product->insurances()->active()->get() as $insurance)
                            <div class="insurance-wrapper">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <img src="{{$insurance->insurance_logo_url}}" class="w-100 img-fluid"
                                                     alt="">
                                            </div>
                                            <div class="col-md-9 mt-3 mt-md-0">
                                                <h3>{{$insurance->insurance_name}}</h3>
                                                <p>{{$insurance->insurance_description}}</p>
                                                <div class="custom-control  custom-switch">
                                                    <input type="checkbox" class="custom-control-input klhk use-insurance"
                                                           id="customSwitch1"
                                                           value="{{$insurance->id}}"
                                                           name="use_insurance[{{$insurance->id}}]">
                                                    <label class="custom-control-label " for="customSwitch1">Gunakan
                                                        Asuransi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row insurance-content" style="display: none">
                                    <div class="col-12 mt-3">
                                        <h3>Masukkan Informasi Rombongan Anda</h3>
                                        <p class="small">Jumlah Rombongan Termasuk Anda: {{ $pax}}</p>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <h3>Pemesan</h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            @foreach($insurance->customer_forms as $customerForm)
                                                <div class="col-md-6">
                                                    <div class="md-form __parent_form">
                                                        @switch($customerForm->type)
                                                            @case('date')
                                                            <div class="md-form __parent_form">
                                                                <input type="text" name="insurances[{{$insurance->id}}][customer][{{$customerForm->name}}]"
                                                                       id="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}"
                                                                       class="{{$customerForm->class}}">
                                                                <label for="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}">{{$customerForm->label_id}}</label>
                                                            </div>
                                                            @break
                                                            @case('textarea')
                                                            <div class="md-form __parent_form">
                                                            <textarea name="insurances[{{$insurance->id}}][customer][{{$customerForm->name}}]"
                                                                      id="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}" cols="30"
                                                                      rows="3"
                                                                      class="{{$customerForm->class}}"
                                                                      maxlength="250"></textarea>
                                                            <label for="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}">{{$customerForm->label_id}}</label>
                                                            </div>
                                                            @break
                                                            @default
                                                            <div class="md-form __parent_form">
                                                                <input type="text" id="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}"
                                                                       class="{{$customerForm->class}}"
                                                                       name="insurances[{{$insurance->id}}][customer][{{$customerForm->name}}]">
                                                                <label for="insurances-{{$insurance->id}}-customer-{{$customerForm->name}}">{!! $customerForm->label_id !!} </label>
                                                            </div>
                                                        @endswitch
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3 col-participants">
                                        <div class="row">
                                            @for($i=1;$i<=$pax;$i++)

                                                <div class="col-md-6">
                                                    <h3>Peserta {{$i}}</h3>
                                                    @foreach($insurance->participant_forms as $customerForm)
                                                        @php
                                                            $name = "insurances[".$insurance->id."][participants][".$i."][".$customerForm->name."]";
                                                        @endphp
                                                        <div class="mb-3">
                                                            @switch($customerForm->type)
                                                                @case('text')
                                                                <div class="md-form __parent_form">
                                                                <input type="text" id="insurances-{{$insurance->id}}-participants-{{$i}}-{{$customerForm->name}}"
                                                                       class="{{$customerForm->class}}"
                                                                       name="{{$name}}">
                                                                <label for="insurances-{{$insurance->id}}-participants-{{$i}}-{{$customerForm->name}}">{!! $customerForm->label_id !!} </label>
                                                                </div>
                                                                @break
                                                                @case('date')
                                                                <div class="md-form __parent_form">
                                                                    <input type="text" name="{{$name}}"
                                                                           id="insurances-{{$insurance->id}}-participants-{{$i}}-{{$customerForm->name}}"
                                                                           class="{{$customerForm->class}}">
                                                                    <label for="insurances-{{$insurance->id}}-participants-{{$i}}-{{$customerForm->name}}">{{$customerForm->label_id}}</label>
                                                                </div>
                                                                @break
                                                                @case('textarea')
                                                                <div class="md-form __parent_form">
                                                                <textarea name="{{$name}}"
                                                                          id="insurances-{{$insurance->id}}-participants-{{$i}}-{{$customerForm->name}}" cols="30"
                                                                          rows="3"
                                                                          class="{{$customerForm->class}}"
                                                                          maxlength="250"></textarea>
                                                                <label for="insurances-{{$insurance->id}}-participants-{{$i}}.{{$customerForm->name}}">{{$customerForm->label_id}}</label>
                                                                </div>
                                                                @break
                                                            @endswitch
                                                        </div>
                                                    @endforeach
                                                </div>


                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- End Assuransi -->
        @endif
        <!-- Custom Information -->
            @if ($product->customSchema->isNotEmpty())
                @php
                    $custom_all_participant = $pax == 1 ? false : $custom_all_participant;
                    $total_tab = $custom_all_participant ? $pax : 1;
                @endphp
                <div class="card mt-3" id="additional-information">
                    <div class="card-header h-auto bg-style-1 py-2 px-3 additional-info-2" role="tablist"
                         style="display: none;">
                        <ul class="nav nav-tabs card-header-tabs">
                            @for ($i = 0; $i < $total_tab; $i++)
                                <li class="nav-item">
                                    <a class="nav-link rounded-0{{ $i == 0 ? ' active' : '' }}"
                                       id="add{{ $i }}-tab" data-toggle="tab" href="#add{{ $i }}" role="tab"
                                       aria-controls="add{{ $i }}"
                                       aria-selected="{{ $i == 0 ? 'true' : 'false' }}">
                                        {{ $i == 0 ? trans('customer.book.customer'): trans('customer.book.participant').' '.$i }}
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="additional-info-1"
                             style="display: {{ $custom_all_participant ? 'block' : 'none' }};">
                            <h3 class="bold">
                                {{ trans('customer.book.additional_info') }}
                            </h3>
                            <div>
                                <div class="mb-2">{{ trans('customer.book.additional_info_all_participant') }}</div>
                                <button id="additional-info-toggle" class="btn btn-primary"
                                        type="button">{{ trans('customer.book.additional_info_fill') }}</button> {{ trans('customer.book.additional_info_status_empty') }}
                            </div>
                        </div>
                        @if (!$custom_all_participant)
                            <div>
                                <h3 class="bold">
                                    {{ trans('customer.book.additional_info') }}
                                </h3>
                                <div>
                                    <div class="mb-2">{{ trans('customer.book.additional_info_customer') }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="additional-info-2"
                             style="display: {{ $custom_all_participant ? 'none' : 'block' }};">
                            <div class="tab-content p-0" id="nav-tabContent">
                                @for ($i = 0; $i < $total_tab; $i++)
                                    <div class="tab-pane fade{{ $i == 0 ? ' show active' : '' }}"
                                         id="add{{ $i }}" role="tabpanel" aria-labelledby="add{{ $i }}-tab">
                                        <div class="row">
                                            @php
                                                $schemas = $i > 0 ? $product->customSchema->where('fill_type', 'all participant') : $product->customSchema;
                                            @endphp
                                            @foreach ($schemas as $custom)
                                                <div class="col-md-{{ $custom->type_custom == 'textarea' ? '12' : '6' }} mb-2">
                                                    <div class="{{ !in_array($custom->type_custom, ['dropdown', 'country', 'state', 'city']) ? 'md-form __parent_form' : '' }}">
                                                        @switch($custom->type_custom)
                                                            @case('choices')
                                                            @case('checkbox')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}"
                                                                 class="d-block">
                                                                <strong>{{ $custom->label_name }}</strong></div>
                                                            @foreach ($custom->value as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input"
                                                                           type="{{ $custom->type_custom == 'checkbox' ? 'checkbox' : 'radio' }}"
                                                                           id="custom-{{ $i }}-{{ $custom->id }}-{{ $loop->index }}"
                                                                           name="custom[{{ $i }}][{{ $custom->id }}]{{ $custom->type_custom == 'checkbox' ? '[]' : '' }}"
                                                                           value="{{ $value }}">
                                                                    <label class="form-check-label"
                                                                           for="custom-{{ $i }}-{{ $custom->id }}-{{ $loop->index }}">{{ $value }}</label>
                                                                </div>
                                                            @endforeach
                                                            <div class="text-muted mt-3 mx-1">
                                                                <small>{{ $custom->description }}</small></div>
                                                            @break
                                                            @case('dropdown')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}"
                                                                 class="form-group pmd-textfield pmd-textfield-floating-label">
                                                                <label>{{ $custom->label_name }}</label>
                                                                <select name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                        class="select-with-search pmd-select2 form-control">
                                                                    <option selected disabled value=""></option>
                                                                    @foreach ($custom->value as $value)
                                                                        <option value="{{ $value }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="text-muted mt-3 mx-1">
                                                                <small>{{ $custom->description }}</small></div>
                                                            @break
                                                            @case('document')
                                                            <div class="mb-1">
                                                                <strong>{{ $custom->label_name }}</strong></div>
                                                            <div class="mb-1">
                                                                <small>{{ $custom->description }}</small></div>
                                                            <input type="file"
                                                                   id="custom-{{ $i }}-{{ $custom->id }}"
                                                                   class="form-control border-0"
                                                                   name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                   accept="application/pdf,text/plain">
                                                            <div class="text-muted mt-1">
                                                                <small>{{ trans('customer.book.additional_info_desc_document') }}</small>
                                                            </div>
                                                            @break
                                                            @case('photo')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}"
                                                                 class="mb-1">
                                                                <strong>{{ $custom->label_name }}</strong></div>
                                                            <div class="mb-1">
                                                                <small>{{ $custom->description }}</small></div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <input type="file" class="dropify"
                                                                           name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                           data-max-file-size="2M"
                                                                           data-allowed-file-extensions="png jpeg jpg"/>
                                                                </div>
                                                                <div class="col text-muted">
                                                                    <small>{{ trans('customer.book.additional_info_desc_photo') }}</small>
                                                                </div>
                                                            </div>
                                                            @break
                                                            @case('textarea')
                                                            <textarea id="custom-{{ $i }}-{{ $custom->id }}"
                                                                      class="form-control md-textarea"
                                                                      name="custom[{{ $i }}][{{ $custom->id }}]"></textarea>
                                                            <label for="custom-{{ $i }}-{{ $custom->id }}">{{ $custom->label_name }}</label>
                                                            <div class="text-muted">
                                                                <small>{{ $custom->description }}</small></div>
                                                            @break
                                                            @case('country')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}">
                                                                <div class="pmd-textfield pmd-textfield-floating-label">
                                                                    <label>{{ $custom->label_name }}</label>
                                                                    <select name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                            class="select-with-search pmd-select2"
                                                                            searchable="Search here..">
                                                                        <option selected disabled
                                                                                value=""></option>
                                                                        @foreach ($country as $value)
                                                                            <option value="{{ $value->id_country }}">{{ $value->country_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="text-muted mt-3 mx-1">
                                                                    <small>{{ $custom->description }}</small>
                                                                </div>
                                                            </div>
                                                            @break
                                                            @case('state')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        {{ $custom->label_name }}
                                                                    </div>
                                                                    <div class="col">
                                                                        <div id="country-{{ $i }}-{{ $custom->id }}"
                                                                             class="pmd-textfield pmd-textfield-floating-label">
                                                                            <label>{{ __('product_provider.country') }}</label>
                                                                            <select class="select-with-search pmd-select2 select-country"
                                                                                    data-id="{{ $i }}-{{ $custom->id }}">
                                                                                <option selected disabled
                                                                                        value=""></option>
                                                                                @foreach ($country as $value)
                                                                                    <option value="{{ $value->id_country }}">{{ $value->country_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div id="state-{{ $i }}-{{ $custom->id }}"
                                                                             class="pmd-textfield pmd-textfield-floating-label">
                                                                            <label>{{ __('product_provider.state') }}</label>
                                                                            <select name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                                    class="select-with-search pmd-select2"
                                                                                    data-id="{{ $i }}-{{ $custom->id }}">
                                                                                <option selected disabled
                                                                                        value=""></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted mt-3 mx-1">
                                                                    <small>{{ $custom->description }}</small>
                                                                </div>
                                                            </div>
                                                            @break
                                                            @case('city')
                                                            <div id="custom-{{ $i }}-{{ $custom->id }}">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        {{ $custom->label_name }}
                                                                    </div>
                                                                    <div class="col">
                                                                        <div id="country-{{ $i }}-{{ $custom->id }}"
                                                                             class="pmd-textfield pmd-textfield-floating-label">
                                                                            <label>{{ __('product_provider.country') }}</label>
                                                                            <select class="select-with-search pmd-select2 select-country"
                                                                                    data-id="{{ $i }}-{{ $custom->id }}">
                                                                                <option selected disabled
                                                                                        value=""></option>
                                                                                @foreach ($country as $value)
                                                                                    <option value="{{ $value->id_country }}">{{ $value->country_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div id="state-{{ $i }}-{{ $custom->id }}"
                                                                             class="pmd-textfield pmd-textfield-floating-label">
                                                                            <label>{{ __('product_provider.state') }}</label>
                                                                            <select class="select-with-search pmd-select2 select-state"
                                                                                    data-id="{{ $i }}-{{ $custom->id }}">
                                                                                <option selected disabled
                                                                                        value=""></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div id="city-{{ $i }}-{{ $custom->id }}"
                                                                             class="pmd-textfield pmd-textfield-floating-label">
                                                                            <label>{{ __('product_provider.city') }}</label>
                                                                            <select name="custom[{{ $i }}][{{ $custom->id }}]"
                                                                                    class="select-with-search pmd-select2">
                                                                                <option selected disabled
                                                                                        value=""></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted mt-3 mx-1">
                                                                    <small>{{ $custom->description }}</small>
                                                                </div>
                                                            </div>
                                                            @break
                                                            @default
                                                            <input type="text"
                                                                   id="custom-{{ $i }}-{{ $custom->id }}"
                                                                   class="form-control{{ $custom->type_custom == 'number' ? ' number' : '' }}"
                                                                   name="custom[{{ $i }}][{{ $custom->id }}]">
                                                            <label for="custom-{{ $i }}-{{ $custom->id }}">{{ $custom->label_name }}</label>
                                                            <div class="text-muted">
                                                                <small>{{ $custom->description }}</small></div>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
        @endif
        <!-- END Custom Information -->
            <div class="card mt-3" id="promotion-code">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.having_promotion_code') !!}</h3>
                    <div class="row">
                        <div class="col">
                            <div class="md-form __parent_form">
                                <input type="text" id="voucher_code" class="form-control">
                                {!! Form::hidden('voucher_code') !!}
                                <label for="voucher_code">{!! trans('customer.book.promotion_code') !!}</label>
                            </div>
                        </div>
                        <div class="col-sm-auto mt-md-0 mt-3 voucher-button">
                            <div class="mt-3">
                                <button class="btn btn-primary btn-block" type="button" id="btn-apply-voucher">
                                    {!! trans('customer.book.apply') !!}
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-auto mt-md-0 mt-3 voucher-button">
                            <div class="mt-3">
                                <button class="btn btn-danger btn-block" type="button" id="btn-delete-voucher">
                                    {!! trans('customer.book.delete') !!}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3" id="booking-price">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.price_details') !!}</h3>
                    <table class="table table-borderless mt-5 tbl-no-padding">
                        <tr>
                            <td>
                                {{$pax}}
                                {{ $pricing['price_display'] }}
                            </td>
                            <td class="text-right bold nowrap">
                                {{$pricing['total_price_text']}}
                            </td>
                        </tr>
                    </table>
                    @if($pricing['company_discount_name'] && $pricing['company_discount_price']>0)
                        <table class="table table-borderless tbl-no-padding">
                            <tr>
                                <td>
                                    {{$pricing['company_discount_name']}}
                                </td>
                                <td class="text-right bold nowrap">
                                    {{$product->currency}} {{format_priceID($pricing['company_discount_price'],'-')}}
                                </td>
                            </tr>
                        </table>
                    @endif
                    @if ($product->vat)
                        <table class="table table-borderless tbl-no-padding" id="vat">
                            <tbody>
                                <tr>
                                    <td>@lang('booking.vat')</td>
                                    <td class="text-right bold">
                                        {{ $product->currency }}
                                        <span id="vat-value" data-value="{{ $pricing['grand_total'] * 10 / 100 }}">{{ number_format($pricing['grand_total'] * 10 / 100, 0, '', '.') }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <table class="table table-borderless tbl-no-padding">
                        <tr>
                            <td>
                                <h3 class="bold">{!! trans('customer.book.grand_total') !!}</h3>
                            </td>
                            <td class="text-right bold fs-20" id="grandTotal">
                                {{$pricing['grand_total_text']}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3" id="choose-payment">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.payment_method') !!}</h3>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="md-form __parent_form" id="data">
                                <input type="hidden" name="payment_list" value="" class="form-control">
                                <input type="hidden" name="payment_method" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    @foreach ($payment_list as $item2)
                        @foreach($item2->listPayments->when($product->booking_confirmation == 0, function($q){
                           return $q->where('code_payment', 'cod');
                        }, function($p){
                            return $p->reject(function($c){
                                return $c->code_payment == 'cod';
                            });
                        })->when($company->domain_memoria !=='basecampadventureindonesia.gomodo.id' && (optional($company->kyc)->status !=='approved'), function($j){
                            return $j->reject(function($o){
                                return $o->code_payment == 'credit_card';
                            });
                        })->when((optional($company->transfer_manual)->status !=='approved'), function($a){
                            return $a->reject(function($b){
                                return $b->code_payment == 'bca_manual';
                            });
                        }) as $item)
                            <div class="custom-control custom-radio">
                                @if($product->booking_confirmation =='0' && $item->code_payment == 'cod')
                                    <input type="radio"
                                           class="custom-control-input {{ $item->categoryPayment->name_third_party_eng }}"
                                           id="{{ $item->code_payment }}" name="pricing_primary"
                                           value="{{ $item->pricing_primary }}">
                                    <label class="custom-control-label" for="{{ $item->code_payment }}">
                                        @if(app()->getLocale() == 'id')
                                            {{ $item->name_payment }}
                                        @else
                                            {{ $item->name_payment_eng }}
                                        @endif
                                    </label>
                                    <img src="{{ asset($item->image_payment) }}"
                                         class="img-fluid list_payment midtrans-img" alt="">
                                @else
                                    <input type="radio"
                                           class="choose_payment_method custom-control-input {{ $item->categoryPayment->name_third_party_eng }}"
                                           id="{{ $item->code_payment }}" name="pricing_primary"
                                           @if ($item->code_payment == 'kredivo' && ($pricing['grand_total'] < 10000 || $pricing['grand_total'] > 30000000))
                                           disabled
                                           @endif
                                           value="{{ $item->pricing_primary }}">
                                    <label class="custom-control-label" for="{{ $item->code_payment }}">
                                        @if(app()->getLocale() == 'id')
                                            {{ $item->name_payment }}
                                        @else
                                            {{ $item->name_payment_eng }}
                                        @endif
                                    </label>
                                    <img src="{{ asset($item->image_payment) }}"
                                         class="img-fluid list_payment midtrans-img"
                                         alt="">
                                    @if($item->code_payment === 'gopay')
                                        <span class="badge badge-pill orange" data-toggle="tooltip"
                                              data-placement="top"
                                              title="{{ \trans('booking.tooltip_gopay') }}"><i
                                                    class="fas fa-exclamation" aria-hidden="true"></i></span>
                                    @endif
                                @endif
                            </div>
                            <hr class="list-row">
                        @endforeach
                    @endforeach
                    {{--                    <div class="custom-control custom-radio">--}}
                    {{--                        <input type="radio" class="custom-control-input midtrans" id="indomaret" name="inlineDefaultRadiosExample">--}}
                    {{--                        <label class="custom-control-label" for="indomaret">Indomaret</label>--}}
                    {{--                        <img src="{{ asset('img/midtrans.png') }}" class="img-fluid list_payment midtrans-img" alt="">--}}
                    {{--                    </div> <hr class="list-row">--}}
                    {{--                    <div class="custom-control custom-radio">--}}
                    {{--                        <input type="radio" class="custom-control-input xendit" id="virtual_account" name="inlineDefaultRadiosExample">--}}
                    {{--                        <label class="custom-control-label" for="virtual_account">Bank Transfer</label>--}}
                    {{--                        <img src="{{ asset('img/xendit.svg') }}" class="img-fluid list_payment">--}}
                    {{--                    </div> <hr class="list-row">--}}
                    {{--                    <div class="custom-control custom-radio">--}}
                    {{--                        <input type="radio" class="custom-control-input xendit" id="ovo" name="inlineDefaultRadiosExample">--}}
                    {{--                        <label class="custom-control-label" for="ovo">OVO</label>--}}
                    {{--                        <img src="{{ asset('img/xendit.svg') }}" class="img-fluid list_payment">--}}
                    {{--                    </div> <hr class="list-row">--}}
                    {{--                    <div class="custom-control custom-radio">--}}
                    {{--                        <input type="radio" class="custom-control-input xendit" id="alfamart" name="inlineDefaultRadiosExample">--}}
                    {{--                        <label class="custom-control-label" for="alfamart">Alfamart</label>--}}
                    {{--                        <img src="{{ asset('img/xendit.svg') }}" class="img-fluid list_payment">--}}
                    {{--                    </div> <hr class="list-row">--}}
                    {{--                    @if ($company->domain_memoria ==='basecampadventureindonesia.gomodo.id' || ($company->kyc && $company->kyc->status==='approved'))--}}
                    {{--                        <div class="custom-control custom-radio">--}}
                    {{--                            <input type="radio" class="custom-control-input xendit" id="credit_card" name="inlineDefaultRadiosExample">--}}
                    {{--                            <label class="custom-control-label" for="credit_card">Credit Card</label>--}}
                    {{--                            <img src="{{ asset('img/xendit.svg') }}" class="img-fluid list_payment">--}}
                    {{--                        </div> <hr class="list-row">--}}
                    {{--                    @endif--}}
                    {{--                    <div class="custom-control custom-radio">--}}
                    {{--                        <input type="radio" class="custom-control-input cod" id="cod" name="inlineDefaultRadiosExample">--}}
                    {{--                        <label class="custom-control-label cod" for="cod">--}}
                    {{--                            <img src="{{ asset('img/cod2.png') }}" class="img-fluid list_payment cod-img">--}}
                    {{--                            {{ trans('product_provider.cash') }}--}}
                    {{--                        </label>--}}
                    {{--                    </div> <hr class="list-row">--}}
                    {{--                    @php--}}
                    {{--                        if ($product->booking_confirmation =='0'){--}}
                    {{--                          $methods = [--}}
                    {{--                          'cod'=> \trans('product_provider.cash'),--}}
                    {{--                          ];--}}
                    {{--                        }else{--}}
                    {{--                          $methods = [--}}
                    {{--                            'virtual_account'=>'Bank Transfer',--}}
                    {{--                            'alfamart'=>'Alfamart',--}}
                    {{--                            'ovo'=>'OVO'--}}
                    {{--                            ];--}}
                    {{--                            //if (app()->environment()=='production'){--}}
                    {{--                            //$methods['ovo'] = 'OVO';--}}
                    {{--                            //}--}}
                    {{--                            if ($company->domain_memoria ==='basecampadventureindonesia.gomodo.id' || ($company->kyc && $company->kyc->status==='approved')){--}}
                    {{--                            $methods['credit_card']='Credit Card';--}}
                    {{--                            }--}}
                    {{--                        }--}}
                    {{--                    @endphp--}}
                    {{--                    {!! Form::select('payment_method',$methods,null,['class'=>'select-simple pmd-select2']) !!}--}}
                </div>
            </div>
            <div class="card mt-3" id="kredivo_form" style="display: none;">
                <div class="card-body">
                    <img src="{{ asset('img/kredivo.png') }}" alt="Kredivo" width="150" />
                    <h3 class="mt-4">@lang('customer.kredivo.booking.personal_data_title')</h3>
                    <h6 class="mt-2">@lang('customer.kredivo.booking.personal_data_subtitle')</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_first_name" class="form-control" name="kredivo_first_name">
                                <label for="kredivo_first_name">@lang('customer.kredivo.form.first_name') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_last_name" class="form-control" name="kredivo_last_name">
                                <label for="kredivo_last_name">@lang('customer.kredivo.form.last_name') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_phone_number" class="form-control" name="kredivo_phone_number">
                                <label for="kredivo_phone_number">@lang('customer.kredivo.form.phone_number') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="email" id="kredivo_email" class="form-control" name="kredivo_email">
                                <label for="kredivo_email">@lang('customer.kredivo.form.email') *</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="md-form __parent_form">
                                <textarea name="kredivo_address" id="kredivo_address" cols="30" rows="2"
                                          class="form-control md-textarea" maxlength="300"></textarea>
                                <label for="kredivo_address">@lang('customer.kredivo.form.address') *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pmd-textfield pmd-textfield-floating-label">
                                <label for="kredivo_state">@lang('customer.kredivo.form.state') *</label>
                                <select id="kredivo_state" name="kredivo_state" class="select-with-search pmd-select2">
                                    <option selected disabled value=""></option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state['id_state'] }}">{{ $state['state_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pmd-textfield pmd-textfield-floating-label">
                                <label for="kredivo_city">@lang('customer.kredivo.form.city') *</label>
                                <select id="kredivo_city" name="kredivo_city" class="select-with-search pmd-select2">
                                    <option selected disabled value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_postal_code" class="form-control number" name="kredivo_postal_code" maxlength="5">
                                <label for="kredivo_postal_code">@lang('customer.kredivo.form.postal_code') *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="mdb-select md-form" name="kredivo_installment_duration">
                                <option value="" disabled selected>@lang('customer.kredivo.form.installment_duration') *</option>
                                @foreach (\App\Models\PaymentKredivo::$durations as $duration => $label)
                                    <option value="{{ $duration }}" {{ ($pricing['grand_total'] < 1000000 && $duration != '30_days') || ($pricing['grand_total'] > 3000000 && $duration == '30_days') ? 'disabled' : '' }}>{{  preg_replace_callback('/\{\{(.*)\}\}/', function($matches) {return trans('duration.'.$matches[1]);}, $label) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white">
        <div class="container py-3">
            <div class="row">
                <div class="col-12 text-md-right text-center">
                    <button class="btn btn-primary" type="button" id="booking-pay-now">
                        @if($product->booking_confirmation =='0')
                            {!! trans('customer.book.booking_now') !!}
                        @else
                            {!! trans('customer.book.pay_now') !!}

                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
@section('scripts')
    <script src="{{asset('additional/select2/js/global.js')}}"></script>
    <script src="{{asset('dest-operator/reskin_global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{asset('dest-customer/lib/js/momentjs-id.js')}}"></script>
    <script src="{{asset('additional/select2/js/select2.full.js')}}"></script>
    <script src="{{asset('additional/select2/js/pmd-select2.js')}}"></script>
    <script src="{{asset('additional/picker.js')}}"></script>
    <script src="{{asset('additional/picker.date.js')}}"></script>

    <script type="text/javascript"
            src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>

    <script>
        $('.datepicker').pickadate({
            weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            showMonthsShort: true,
            selectMonths: true,
            selectYears: 100,
            today: false,
            clear: false,
            close: false,
            formatSubmit: 'yyyy-mm-dd',
            max: moment(),
        })
        var change = {
            additional: 0,
            voucher: 0,
            gopay: 0,
            insurance:{
                use:false,
                total:0
            }
        };

        var dataSecondary = 0;
        var dataCard = 0;
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function formatMoney(n, c, d, t) {
            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };

        $(document).on("keydown", ".number", function (e) {
            // Allow: backspace, delete, tab, escape, enter and .(190)
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 187 && (e.shiftKey === true || e.metaKey === true)) ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 189 && (e.shiftKey === false || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode === 106 || e.keyCode === 110)) {
                e.preventDefault();
            }
        });
        {{--$(document).on('change', 'select[name=city]', function () {--}}
        {{--    let t = $(this);--}}
        {{--    let c = $('select[name=country]');--}}
        {{--    loadingStart();--}}
        {{--    c.find('option').remove();--}}
        {{--    $.ajax({--}}
        {{--        url: "{{route('city.byCountry')}}",--}}
        {{--        data: {id: t.val()},--}}
        {{--        dataType: 'json',--}}
        {{--        success: function (data) {--}}
        {{--            let html = '<option disabled selected>Choose your city</option>';--}}
        {{--            $.each(data, function (i, e) {--}}
        {{--                html += '<option value="' + e.id_city + '">' + e.city_name + '</option>';--}}
        {{--            })--}}
        {{--            c.append(html);--}}
        {{--            loadingFinish();--}}
        {{--        },--}}
        {{--        error:function (e) {--}}
        {{--            console.log(e);--}}
        {{--            loadingFinish();--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}

        $('select[name=city]').select2({
            theme: "bootstrap",
            ajax: {
                url: "{{route('city.byCountry')}}",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function (params) {
                    return {
                        country_id: $('select[name=country]').val(),
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },

            minimumInputLength: 3,
            templateResult: formatRepo2,
            templateSelection: formatRepoSelection2
        })

        $('.select-with-search').select2({
            theme: 'bootstrap'
        });

        $('.select-country').on('change', function () {
            let data_id = $(this).data('id');
            $.ajax({
                url: "{{ route('location.states') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id_country: $(this).val()
                },
                success: function (response) {
                    $('#state-' + data_id + ' select option:not(:first)').remove();
                    let select = $('#state-' + data_id + ' select');
                    select.val('').change();
                    select.parent().removeClass('pmd-textfield-floating-label-completed');
                    $.each(response, function (key, value) {
                        let state_name = value.state_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                        $('#state-' + data_id + ' select').append('<option value=' + value.id_state + '>' + state_name + '</option>');
                    });
                }
            });
        });

        $('.select-state').on('change', function () {
            let data_id = $(this).data('id');
            $.ajax({
                url: "{{ route('location.cities') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id_state: $(this).val()
                },
                success: function (response) {
                    $('#city-' + data_id + ' select option:not(:first)').remove();
                    let select = $('#city-' + data_id + ' select');
                    select.val('').change();
                    select.parent().removeClass('pmd-textfield-floating-label-completed');
                    $.each(response, function (key, value) {
                        let city_name = value.city_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                        $('#city-' + data_id + ' select').append('<option value=' + value.id_city + '>' + city_name + '</option>');
                    });
                }
            });
        });

        function formatRepo2(repo) {
            if (repo.loading) {
                return 'Looking for cities';
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>City : " + repo.text + "</div>" +
                "<div class='select2-result-repository__title'>State :" + repo.state + "</div>" +
                "</div>";

            return markup;
        }

        function formatRepoSelection2(repo) {
            if (repo.city_name) {
                return repo.city_name
            } else {
                return repo.text;
            }

        }

        $(document).on('change', 'select[name=identity_number_type]', function () {
            let t = $(this);
            $('input[name=identity_number]').removeClass('number');
            if (t.val() === 'ktp') {
                $('input[name=identity_number]').addClass('number')
            }
        });
        let submit = false;
        $(document).on('click', '#booking-pay-now', function () {
            var text_area_note = $('textarea[id=note]');
            if (!submit) {
                loadingStart();
                text_area_note.val(text_area_note.val().replace(/\r\n|\r|\n/g, "<br/>"));
                let f = $(this).closest('form');
                let form_data = new FormData(f[0]);
                $('label.error').remove();
                submit = true;
                $.ajax({
                    url: "{{route('customer.pay')}}",
                    type: 'POST',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        // window.location.href = window.location.origin + '/invoice/make_payment/' + data.data.invoice
                        window.location.href = data.data.url
                        loadingFinish();
                        submit = false;
                    },
                    error: function (e, textstatus) {
                        text_area_note.val(text_area_note.val().replace(/<br\s*[\/]?>/gi, "\n"));
                        if (textstatus == 'timeout') {
                            submit = false;
                            return toastr.error('Timeout', '{{__('general.whoops')}}')
                        }

                        if (e.status === 422) {
                            $.each(e.responseJSON.errors, function (i, e) {
                                let selector = '[name=' + i + ']';
                                if (i.indexOf('.') != -1) {
                                    selector = '#' + i.replace(/\./g, '-');
                                }
                                $(document).find('div#' + i.replace(/\./g, '-')).append('<label class="error text-danger small">' + e[0] + '</label>');
                                $(document).find('input' + selector + ', textarea' + selector + ', div' + selector).closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                                $(document).find('select' + selector).closest('.select-wrapper.md-form').append('<label class="error">' + e[0] + '</label>');
                                $(document).find('select' + selector).closest('.pmd-textfield-floating-label').append('<label class="error">' + e[0] + '</label>');
                            })
                        }
                        loadingFinish();
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);

                        toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                        submit = false;
                    }
                })
            }
        });
        $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
            $(this).closest('.__parent_form').find('label.error').remove();
        });

        const list_data = $('input[name=payment_method]');
        $(document).on('click', '#choose-payment input[type=radio]', function () {
            list_data.val($(this).attr('id'));
            const payment_list = $('input[name=payment_list]');
            if ($(this).hasClass('Xendit Payment')) {
                payment_list.val('Xendit Payment')
            } else if ($(this).hasClass('Midtrans Payment')) {
                payment_list.val('Midtrans Payment')
            } else if ($(this).hasClass('Manual Transfer')) {
                payment_list.val('Manual Transfer')
            } else {
                payment_list.val('COD')
            }
            $('#credit_card_charge').remove();
            $('#gopay_charge').remove();
            $('#indomaret_charge').remove();
            $('#dana_charge').remove();
            $('#linkaja_charge').remove();
            $('#bca_va_charge').remove();
            $('#ovo_live_charge').remove();
            $('#akulaku_charge').remove();
            change.additional = 0;
            calculatePricing();
        });

        // {{--$(document).on('click', '#credit_card', function () {--}}
        // {{--    let select = $(this);--}}
        // {{--    list_data.val('credit_card');--}}
        // {{--    // $('input[name=payment_method]').val('credit_card');--}}
        // {{--    // if (list_data === 'credit_card') {--}}
        // {{--    --}}{{--let current = parseFloat("{{$pricing['grand_total']}}");--}}
        // {{--    --}}{{--let grand = (current - change.voucher);--}}
        // {{--    --}}{{--change.additional = Math.ceil(((100/97.1) * grand) - grand);--}}
        // {{--    calculatePricing();--}}
        // {{--    let tableBooking = $('#booking-price .card-body');--}}
        // {{--    tableBooking.find('#creditcard_charge').remove();--}}
        // {{--    let html = ' <table class="table table-borderless tbl-no-padding" id="creditcard_charge">\n' +--}}
        // {{--        '                        <tr>\n' +--}}
        // {{--        '                            <td>\n' +--}}
        // {{--        '                                Credit Card Charge (2.9 %)' +--}}
        // {{--        '                            </td>\n' +--}}
        // {{--        '                            <td class="text-right bold" id="voucher_value">\n' +--}}
        // {{--        '{{$product->currency}}' + ' ' + formatMoney(change.additional, 0, '.', '.')--}}
        // {{--    '                            </td>\n' +--}}
        // {{--    '                        </tr>\n' +--}}
        // {{--    '                    </table>';--}}
        // {{--    tableBooking.append(html);--}}
        // {{--});--}}

        // {{--$(document).on('click', '#gopay', function () {--}}
        // {{--    let select = $(this);--}}
        // {{--    list_data.val('gopay');--}}
        // {{--    calculatePricing();--}}
        // {{--    let tableBooking = $('#booking-price .card-body');--}}
        // {{--    tableBooking.find('#gopay_charge').remove();--}}
        // {{--    let html = ' <table class="table table-borderless tbl-no-padding" id="gopay_charge">\n' +--}}
        // {{--        '                        <tr>\n' +--}}
        // {{--        '                            <td>\n' +--}}
        // {{--        '                                Gopay Charge (2 %)' +--}}
        // {{--        '                            </td>\n' +--}}
        // {{--        '                            <td class="text-right bold" id="gopay_value">\n' +--}}
        // {{--        '{{$product->currency}}' + ' ' + formatMoney(change.additional, 0, '.', '.')--}}
        // {{--    '                            </td>\n' +--}}
        // {{--    '                        </tr>\n' +--}}
        // {{--    '                    </table>';--}}
        // {{--    tableBooking.append(html);--}}
        // {{--});--}}

        @foreach ($payment_list as $item2)
            @foreach($item2->listPayments as $item)
                @php
                    $companyPayment = $item->companies()->where('id_company', $company->id_company)->first();
                @endphp
                @if($item->pricing_primary > 0 || $item->pricing_secondary > 0)
                    @if($companyPayment->pivot->charge_to === 1)
                        $(document).on('click', '#{{ $item->code_payment }}', function () {
                            let select = $(this);
                            list_data.val('{{ $item->code_payment }}');
                            let tableBooking = $('#booking-price .card-body');
                            let tableHtml2 = $('#choose-payment #data');
                            tableBooking.find('#{{ $item->code_payment }}_charge').remove();
                            tableHtml2.find('#pricing').remove();
                            calculatePricing();

                            let html2 = '<div id="pricing">' +
                                '<input type="hidden" name="pricing_secondary" value="{{ $item->pricing_secondary }}" id="{{ $item->type_secondary }}">' +
                                '<input type="hidden" name="pricing_primary" value="{{ $item->pricing_primary }}" id="{{ $item->type }}">' +
                                '<input type="hidden" name="charge_to" value="{{ $companyPayment->pivot->charge_to }}">' +
                                '</div>';
                            tableHtml2.append(html2);
                            calculatePricing();
                            let html = ' <table class="table table-borderless tbl-no-padding" id="{{ $item->code_payment }}_charge">\n' +
                                '                        <tr>\n' +
                                '                            <td>\n' +
                                '                                {{ $item->name_payment }} {{ trans('booking.charge') }} ({{ $item->type == "percentage" ? $item->pricing_primary ."%" : "IDR ". number_format($item->pricing_primary,0,'','.') }}{{ $item->pricing_secondary > 0 ? ' + ' . ($item->secondary_type == "percentage" ? $item->pricing_secondary ."%" : "IDR ". number_format($item->pricing_secondary,0,'','.')) : ''}})' +
                                '                            </td>\n' +
                                '                            <td class="text-right bold" id="gopay_value">\n' +
                                '{{$product->currency}}' + ' ' + formatMoney(change.additional, 0, '.', '.') +
                                '                            </td>\n' +
                                '                        </tr>\n' +
                                '                    </table>';
                            @if ($product->vat)
                                $(html).insertBefore($('#vat'));
                            @else
                                tableBooking.append(html);
                            @endif
                        });
                    @endif
                @endif
            @endforeach
        @endforeach


        function calculatePricing() {
            let current = parseFloat("{{$pricing['grand_total']}}");
            let grand = (current + change.insurance.total - change.voucher);
            // let dataCard = $('input[name=pricing_primary]:checked').val();
            let charge_to = $('input[name=charge_to]').val();
            dataCard = $('input[name=pricing_primary]');
            dataSecondary = $('input[name=pricing_secondary]');
            // let list = ['credit_card','gopay','indomaret','dana','linkaja','bca_va'];
            // if (list.includes(list_data.val())) {
            // } else {
            //     change.additional = 0;
            // }
            if(charge_to === '1'){
                if(dataCard.attr('id') === 'percentage'){
                    change.additional = Math.ceil(((100 / (100 - dataCard.val())) * grand) - grand);
                    // change.additional += parseInt(dataSecondary.val());
                } else {
                    // change.additional = parseInt(dataCard.val()) + parseInt(dataSecondary.val());
                    change.additional = parseInt(dataCard.val());
                }

                if (dataSecondary.attr('id') == 'percentage') {
                    change.additional += Math.ceil(((100 / (100 - dataSecondary.val())) * grand) - grand);
                } else {
                    change.additional += parseInt(dataSecondary.val());
                }
            } else {
                change.additional = 0;
            }
            grand = grand + change.additional;

            @if ($product->vat)
                @if (env('VAT_AFTER_PAYMENT_FEE', false))
                    let newVat = grand * 10 / 100;
                    $('#vat-value').data('value', newVat).text(formatMoney(newVat, 0, '.', '.'))
                @endif

                grand += parseInt($('#vat-value').data('value'));
            @endif

            $('#grandTotal').html('{{$product->currency}}' + ' ' + formatMoney(grand, 0, '.', '.'))
        }

        $(document).on('click', '#btn-apply-voucher', function () {
            loadingStart();
            let el_voucher_code = $('#voucher_code')
            let tableBooking = $('#booking-price .card-body');
            tableBooking.find('#discount_voucher').remove();
            el_voucher_code.closest('.md-form').find('label.error').remove();
            $('#grandTotal').html('{{$pricing['grand_total_text']}}');
            $.ajax({
                url: '{{route('customer.check.voucher')}}',
                data: {
                    id_product: "{{$product->id_product}}",
                    voucher_code: el_voucher_code.val(),
                    amount: "{{$pricing['grand_total']}}",
                    pax: "{{ $pax }}"
                },
                success: function (data) {
                    let html = ' <table class="table table-borderless tbl-no-padding" id="discount_voucher">\n' +
                        '                        <tr>\n' +
                        '                            <td>\n' +
                        '                                Voucher Discount' +
                        '                            </td>\n' +
                        '                            <td class="text-right bold" id="voucher_value">\n' +
                        '                                ' + data.discount_text
                    '                            </td>\n' +
                    '                        </tr>\n' +
                    '                    </table>';
                    tableBooking.append(html);
                    $('input[name=voucher_code]').val(el_voucher_code.val())
                    el_voucher_code.closest('.md-form').removeClass('failed').addClass('checked');
                    change.voucher = data.discount;
                    showDeleteVoucher();
                    calculatePricing();
                    if (('.choose_payment_method:checked').length>0){
                        $('.choose_payment_method:checked').trigger('click');
                    }
                    loadingFinish();

                },
                error: function (e) {
                    tableBooking.find('#discount_voucher').remove();
                    change.voucher = 0;
                    calculatePricing();
                    el_voucher_code.closest('.md-form').removeClass('success').addClass('failed');
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.select-wrapper.md-form').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                    // showDeleteVoucher();

                    loadingFinish();
                }
            })
        });
        $(document).ready(function () {
            $("select[name=country]").select2({
                theme: "bootstrap",
            });
            $(".select-simple").select2({
                theme: "bootstrap",
                minimumResultsForSearch: Infinity,
            });
        })
        $('.mdb-select').materialSelect();

        // Delete Voucher Button
        $(document).on('click', '#btn-delete-voucher', function () {
            var el_voucher_code = $('#voucher_code');
            el_voucher_code.val('');
            $('input[name=voucher_code]').val('');
            el_voucher_code.closest('.md-form').find('label.error').remove();
            el_voucher_code.closest('.md-form').removeClass('failed');
            el_voucher_code.closest('.md-form').removeClass('checked');
            $('#booking-price .card-body').find('#discount_voucher').remove();
            change.voucher = 0;
            calculatePricing();
            hideDeleteVoucher();
        })
        // Additional Information
        $(document).on('click', '#additional-info-toggle', function () {
            $('.additional-info-1').hide(200);
            $('.additional-info-2').show(200);
        });

        $('.dropify').dropify();

        function fillKredivoJagadiriName(full_name) {
            if (full_name.length > 0) {
                const split_name = full_name.split(' ', 2);
                $('#kredivo_first_name').val(split_name[0]).change();
                $('#insurances-1-customer-insurance_customer_name').val(full_name).change();
                if (split_name[1]) {
                    $('#kredivo_last_name').val(split_name.slice(1).join(' ')).change();
                } else {
                    $('#kredivo_last_name').val('');
                }
            }
        }

        function fillKredivoJagadiriPhone(phone_number) {
            if (phone_number.length > 0) {
                $('#kredivo_phone_number').val(phone_number).change();
                $('#insurances-1-customer-insurance_customer_phone').val(phone_number).change();
            }
        }

        function fillKredivoEmail(email) {
            if (email.length > 0) {
                $('#kredivo_email').val(email).change();
            }
        }

        $(document).on('change', '#choose-payment input[type=radio]', function() {
            const kredivo_form = $('#kredivo_form')
            if ($(this).attr('id') == 'kredivo') {
                let full_name = $('#full_name').val();
                let phone_number = $('#phone_number').val();
                let email = $('#email').val();

                fillKredivoJagadiriName(full_name);
                fillKredivoJagadiriPhone(phone_number);
                fillKredivoEmail(email);

                kredivo_form.show();
            } else {
                kredivo_form.hide();
            }
        });

        $('#full_name').on('input', function () {
            fillKredivoJagadiriName($(this).val());
        });

        $('#phone_number').on('input', function () {
            fillKredivoJagadiriPhone($(this).val());
        });

        $('#email').on('input', function () {
            fillKredivoEmail($(this).val());
        });

        $('#kredivo_state').on('change', function() {
            $.ajax({
                url : "{{ route('location.cities') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id_state: $(this).val()
                },
                success: function(response) {
                    $('#kredivo_city option:not(:first)').remove();
                    let select = $('#kredivo_city');
                    select.val('').change();
                    select.parent().removeClass('pmd-textfield-floating-label-completed');
                    $.each(response, function(key, value) {
                        let city_name = value.city_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                        select.append('<option value=' + value.id_city + '>' + city_name + '</option>');
                    });
                 }
            });
        });
        if (window.location !== window.parent.location) {
            let kredivoParent = $('input#kredivo').parent();
            kredivoParent.find('+ hr').remove();
            kredivoParent.remove();
        }

        $(document).on('change', '.use-insurance', function () {
            let t = $(this);
            change.insurance.use = false;
            change.insurance.total = false;
            $(document).find('.insurance_table').remove();
            let wrapper = t.closest('.insurance-wrapper')
            if (t.is(':checked')) {
                checkInsurance(t.val(),'{{$product->unique_code}}', $(this))
                wrapper.find('.insurance-content').stop().slideDown('slow');
            } else {
                calculatePricing();
                wrapper.find('.insurance-content').stop().slideUp('slow');
            }
        });

        function checkInsurance(id, sku, _this){
            _this.attr('disabled', true)
            $.ajax({
                url:"{{route('customer.check.insurance')}}",
                data:{id:id,sku:sku,pax:"{{$pax}}"},
                success:function (data) {
                    change.insurance.use = true;
                    change.insurance.total = data.result.total;
                    let tableBooking = $('#booking-price .card-body');
                    let html = ' <table class="table table-borderless tbl-no-padding insurance_table">\n' +
                        '                        <tr>\n' +
                        '                            <td>\n' + data.result.label+
                        '                            </td>\n' +
                        '                            <td class="text-right bold">\n' +
                        '{{$product->currency}}' + ' ' + formatMoney(data.result.total, 0, '.', '.') +
                        '                            </td>\n' +
                        '                        </tr>\n' +
                        '                    </table>';
                    tableBooking.append(html);

                    if (('.choose_payment_method:checked').length>0){
                        $('.choose_payment_method:checked').trigger('click');
                    }
                    calculatePricing();
                    _this.attr('disabled', false)

                },
                error: function (error) {
                    console.log(error)

                }
            })
        }
    </script>
@stop
