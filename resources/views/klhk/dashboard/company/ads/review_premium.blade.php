<!-- RIVIEW PAGE -->
<div class="dashboard-ads riview-ordered-ads">
    <div class="container-fluid ">
        <span id="back-order-btn">Back to Order Form</span>
        <br>
        <br>
        <div class="row">
            <div class="col">
                <div class="widget available-adds">
                    <div class="container-fluid row">
                        <div class="col-12 col-sm-6 riview-ads stepPremium6">
                            <h2>{{ trans('premium.facebook.label.h2_review_premium') }}</h2>

                            <div class="card mt-4 container ">
                                <div class="row">
                                    <div class="col-2">
                                        <img id="image-company" src="{{asset('dest-operator/img/travel-img.jpg')}}">
                                    </div>
                                    <div class="col-10 display-grid">
                                        <span>Gomodo Technology</span>
                                        <small>Sponsored</small>
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

                            <div class="detail-data-riview">
                                <label class="static-data-riview">{{ trans('premium.facebook.label.name') }}</label>
                                <span class="dynamice-data-riview">{{ $company->agent->first_name }} @if ($company->agent->last_name)
                                        {{$company->agent->last_name}}
                                    @endif</span>
                                <label class="static-data-riview">Email</label>
                                <span class="dynamice-data-riview">{{ $company->agent->email }}</span>
                                <label class="static-data-riview">URL</label>
                                <span class="dynamice-data-riview headline-first overflow-wrap-break-word">(Default) {{ $company->domain_memoria }}</span>
                                <label class="static-data-riview">{{ trans('premium.facebook.label.order_date') }}</label>
                                <span class="dynamice-data-riview">{{ Carbon\Carbon::now()->format('d M Y') }}</span>
                                <label class="static-data-riview">{{ trans('premium.facebook.label.active_date') }}</label>
                                <span class="dynamice-data-riview startend"></span>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 riview-ads">
                            <h2>{{ trans('premium.facebook.label.h2_payment_details') }}</h2>
                            <div class="form-inline mt-4 payment-details stepPremium7">
                                <div class="col-12 col-sm-5 form-inline" id="balance-gxp">
                                    <img src="{{asset('dest-operator/img/gxp-img.svg')}}">
                                    <label id="gxp-total-balance">IDR {{ number_format($gxp_sum['gxp'],0) }}</label>
                                    <input id="gxp-total-balance-hidden" class="display-none" value="{{ number_format($gxp_sum['gxp'],0) }}">
                                </div>

                                <div class="col-12 col-sm-7 form-inline" id="use-gxp-group">
                                    <input type="hidden" name="gxp_balance">
                                    <label id="use-gxp-label">{{ trans('premium.facebook.label.use_gxp_slider') }}</label>
                                    <label class="switch-rounded">
                                        <input type="checkbox" id="use-gxp" name="using-gxp">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>

                            <br>
                            <div class="form-group 8 stepPremium8">
                                <select name="payment_method" id="" class="form-control">
                                    <option value="" selected disabled>Select payment method</option>
                                    <option value="virtual_account">Via Bank Transfer</option>
                                    <option value="credit_card">Via Credit Card</option>
                                </select>
                            </div>
                            <div class="stepPremium9">
                                <div id="text-code-promo">
                                    <span id="remove-promo">{{ trans('premium.facebook.label.remove_promocode') }}</span><br>
                                    <p id="reason-ads" class="float-none">{{ trans('premium.facebook.label.promo_code') }}</p>
                                    <span id="getvoucher" class="float-right use-the-voucher" onclick="introJs().exit()">{{ trans('premium.facebook.label.or_voucher') }}</span>
                                    <span id="remove-voucher" class="float-right">{{ trans('premium.facebook.label.remove_promo_code') }}</span>
                                </div>
                                <div class="row voucher-section">
                                    <div class="col-12 col-sm-8">
                                        <div>
                                            <div class="form-group">
                                                <span class="fa fa-check-circle display-none" id="check-circle-promo"></span>
                                                <input id="input_voucher" class="form-control" maxlength="15" placeholder="{{ trans('premium.facebook.label.p_promo_code') }}">
                                                <input type="hidden" name="code">
                                                <input type="hidden" name="gxp_value">
                                                <input type="hidden" name="gxp_amount">
                                                <input type="hidden" name="promo_amount">
                                                <input type="hidden" name="cashback_amount">
                                                <input type="hidden" name="grand_total">
                                                {{-- <input type="hidden" name="cash_back_id"> --}}
                                                {{-- <input type="hidden" name="promo_codeid"> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <button type="button" class="btn btn-primary" id="btn-promo-code" onclick="introJs().exit()">Apply</button>
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="row mt-4" id="dataPrice">
                              <div class="col-4">
                                <p class="static-data-price">Subtotal</p>
                                <p class="static-data-price">{{ trans('premium.facebook.label.service_fee') }}</p>
                                <p class="static-data-price">{{ trans('premium.facebook.label.gxp_credits') }}</p>
                                <p class="static-data-price">Voucher</p>
                              </div>
                              <div class="col-8">
                                <p class="dynamic-data-price priceSubTotal">IDR 0</p>
                                <p class="dynamic-data-price price-service-fee">IDR 0</p>
                                <p id="gxp-balance" class="dynamic-data-price">- IDR 0</p>
                                <p id="price-discount" class="dynamic-data-price">- IDR 0</p>
                              </div>
                            </div> --}}
                            <table class="table table-borderless" id="dataPrice">
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
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <th scope="row">{{ trans('premium.facebook.label.total_price') }}</th>
                                    <td class="dynamic-data-price price-total" id="totaly">IDR 0</td>
                                </tr>
                                </tbody>
                            </table>

                            <button type="button" class="btn btn-primary citysubmit stepPremium10" id="btnOrder" onclick="introJs().exit()">Order</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>