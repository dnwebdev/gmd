@extends('customer.master.index')
@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                <li><a href="{{route('company.dashboard')}}">Home</a></li>
                <li><a href="{{route('company.premium.index','tab=my-premium')}}">{{ $orderAds->category_ads }}</a>
                </li>
                <li><a>Invoice</a></li>
            </ul>
        </div>
        <div id="payment-virtual-account" class="container pb-5">
            <div class="row">
                <div class="col-12 py-3 text-center">
                    <p class="fs-14">{!! trans('customer.invoice.payment_expired_in') !!} :</p>
                </div>
                <div class="col-12 text-center">
                    <div class="count-down">
                        <div class="number days">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.day') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number hours">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.hour') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number minutes">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.minute') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number seconds">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.second') !!}
                        </div>
                    </div>
                </div>
                {{--{{dd($res)}}--}}
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{format_priceID($res['amount'])}}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{$res['external_id']}}</span> | <span
                                class="bold">{{$res['payer_email']}}</span>
                    </div>
                    <div class="bold">
                        {{ $orderAds->category_ads }}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-lg-0 text-center text-lg-left">
                            <span class="caption">{!! trans('customer.invoice.select_bank_transfer_method') !!}</span>
                        </div>
                        <div class="col-md-6 text-lg-right order-0 order-lg-1  mb-lg-0 mb-3 text-center">
                            <a href="{{ route('company.premium.index') }}" class="backHome">{!! trans('customer.invoice.back_to') !!} Solusi Pemasaran</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                @foreach($res['available_banks'] as $available_bank)
                                    <div class="card mt-1 mb-1">
                                        <div class="card-header" id="headingOne{{$available_bank['bank_code']}}">
                                            <h5 class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapseOne{{$available_bank['bank_code']}}"
                                                aria-expanded="true"
                                                aria-controls="collapseOne{{$available_bank['bank_code']}}">
                                                {{$available_bank['bank_code']}}
                                            </h5>
                                        </div>
                                        <div id="collapseOne{{$available_bank['bank_code']}}" class="collapse"
                                             aria-labelledby="headingOne{{$available_bank['bank_code']}}"
                                             data-parent="#accordionExample">
                                            <div class="card-body bank-list">
                                                <div class="col-12 text-center virtual-account can-copy">
                                                    <h3>Virtual Account # <span
                                                                class="data-copied">{{$available_bank['bank_account_number']}}</span>
                                                    </h3>
                                                    <h3>{!! trans('customer.invoice.virtual_account_name') !!}
                                                        # {{$available_bank['account_holder_name']}}</h3>
                                                </div>
                                                @if($available_bank['bank_code'] ==='BNI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenu">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATM">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATM"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATM">
                                                                        ATM
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATM" class="collapse"
                                                                     aria-labelledby="headingTwoATM"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.bni.atm') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 4)
                                                                                        {!! __('customer.bank_transfer.bni.atm.4', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoMobilebanking">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBanking"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBanking">
                                                                        Mobile Banking BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBanking" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebanking"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-4a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.mbanking.step-4b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.mbanking.step-8') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoIbankPersonal">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonal"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonal">
                                                                        IBank Personal BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonal" class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonal"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.bni.ibanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 2)
                                                                                        {!! __('customer.bank_transfer.bni.ibanking.2', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoSMSbanking">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoSMSbanking"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoSMSbanking">
                                                                        SMS Banking
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoSMSbanking" class="collapse"
                                                                     aria-labelledby="headingTwoSMSbanking"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-4') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-8') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-10') !!} {{$available_bank['bank_account_number']}} {{$res['amount']}}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.smsbanking.step-11') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoTellerBNI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoTellerBNI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoTellerBNI">
                                                                        Teller BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoTellerBNI" class="collapse"
                                                                     aria-labelledby="headingTwoTellerBNI"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-3a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.teller.step-3b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.teller.step-7') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoAgen46">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoAgen46"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoAgen46">
                                                                        Agen 46
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoAgen46" class="collapse"
                                                                     aria-labelledby="headingTwoAgen46"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-3a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.agen46.step-3b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.agen46.step-7') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBersama">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBersama"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBersama">
                                                                        ATM Bersama
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBersama" class="collapse"
                                                                     aria-labelledby="headingTwoATMBersama"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-4') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.atmbersama.step-8') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBankLain">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBanklain"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBanklain">
                                                                        {!! trans('customer.bank_transfer.bni.otherbank.otherbank') !!}
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBanklain" class="collapse"
                                                                     aria-labelledby="headingTwoATMBankLain"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-3a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.otherbank.step-3b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.otherbank.step-7') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoOVO">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoOVO"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoOVO">
                                                                        OVO
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoOVO" class="collapse"
                                                                     aria-labelledby="headingTwoOVO"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-5a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.ovo.step-5b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-8') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-10') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($available_bank['bank_code'] ==='MANDIRI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuMandiri">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMMandiri">
                                                                        ATM
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMMandiri" class="collapse"
                                                                     aria-labelledby="headingTwoATMMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-5a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>, {!! trans('customer.bank_transfer.mandiri.atm.step-5b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-8') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-10') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.atm.step-11') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebankingMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBankingMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBankingMandiri">
                                                                        IBanking Mandiri
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBankingMandiri"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoMobilebankingMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-1') !!}
                                                                                    <a href="https://ib.bankmandiri.co.id">https://ib.bankmandiri.co.id</a>
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-8') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-10') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-11') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-12') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-13') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.ibanking.step-14') !!}</li>

                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonalMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonalMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonalMandiri">
                                                                        MBanking Mandiri
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonalMandiri"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonalMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-6a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.mandiri.mbanking.step-6b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-8') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-10') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.mandiri.mbanking.step-11') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @elseif($available_bank['bank_code'] ==='BRI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuBRI">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBRI">
                                                                        ATM BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBRI" class="collapse"
                                                                     aria-labelledby="headingTwoATMBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-4a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bri.atm.step-4b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-5') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.atm.step-6') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebankingBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBankingBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBankingBRI">
                                                                        IBanking BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBankingBRI" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebankingBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bri.ibanking.step-1a') !!}
                                                                                    <a href="https://ib.bri.co.id">https://ib.bri.co.id</a>{!! trans('customer.bank_transfer.bri.ibanking.step-1b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.ibanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.ibanking.step-3a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bri.ibanking.step-3b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.ibanking.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.ibanking.step-5') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonalBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonalBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonalBRI">
                                                                        MBanking BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonalBRI"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonalBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bri.mbanking.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.mbanking.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.mbanking.step-3a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bri.ibanking.step-3b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.mbanking.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bri.mbanking.step-5') !!}</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @elseif($available_bank['bank_code'] === 'PERMATA')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuPermata">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATM{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATM{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATM{{ $available_bank['bank_code'] }}">
                                                                        ATM ALTO
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATM{{ $available_bank['bank_code'] }}" class="collapse"
                                                                     aria-labelledby="headingTwoATM{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.atm') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 5)
                                                                                        {!! __('customer.bank_transfer.permata.atm.5', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebanking{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBanking{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBanking{{ $available_bank['bank_code'] }}">
                                                                        Internet Banking
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBanking{{ $available_bank['bank_code'] }}" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebanking{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.ibanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 4)
                                                                                        {!! __('customer.bank_transfer.permata.ibanking.4', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                        Permata Mobile
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.mbanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 4)
                                                                                        {!! __('customer.bank_transfer.permata.mbanking.4', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingThreeIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                        Permata Mobile X
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     class="collapse"
                                                                     aria-labelledby="headingThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.xbanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 3)
                                                                                        {!! __('customer.bank_transfer.permata.xbanking.3', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        let expired_in = parseInt("{{strtotime($res['expiry_date'])}}");
        let skrg = parseInt("{{strtotime(date('Y-m-d H:i:s'))}}");
        let countdownTimer;
        let seconds = (expired_in - skrg);
        if (expired_in > skrg) {
            countdownTimer = setInterval('timer()', 1000);
        }else{
            checkOrder();
        }

        function pad(n) {
            return (n < 10 ? "0" + n : n);
        }

        function timer() {
            let days = Math.floor(seconds / 24 / 60 / 60);
            let hoursLeft = Math.floor((seconds) - (days * 86400));
            let hours = Math.floor(hoursLeft / 3600);
            let minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
            let minutes = Math.floor(minutesLeft / 60);
            let remainingSeconds = seconds % 60;
            checkOrder();
            $('.days').html(pad(days));
            $('.hours').html(pad(hours));
            $('.minutes').html(pad(minutes));
            $('.seconds').html(pad(remainingSeconds));
            if (seconds === 0) {
                clearInterval(countdownTimer);
            } else {
                seconds--;
            }
        }

        function checkOrder() {
            let data= {
              'no_invoice':'{{$orderAds->no_invoice}}'
            };
            $.ajax({
                url:'{{route('invoice-ads.check-order')}}',
                data:data,
                success:function (data) {

                },
                error:function (e) {
                    if (e.responseJSON.result!==undefined){
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }
    </script>
@stop
