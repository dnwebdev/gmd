@extends('back-office.layout.index')
@section('subheader')
<div class="d-flex align-items-center">
    <div class="mr-auto">
        <h3 class="m-subheader__title">Finance : {{$finance->company->company_name}}</h3><br>
        <label>Created : {{ $finance->created_at->format('d M Y h:i:s') }}</label>
    </div>

</div>
@stop
@section('content')
<div class="m-portlet">
    <div class="m-portlet__body  ">
        <div class="row">
            <div class="col-12">
                <div class="m-portlet m-portlet--tabs">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link  active show" data-toggle="tab" href="#m_tabs_6_1"
                                        role="tab" aria-selected="false">
                                        <i class="la la-shopping-cart"></i> Finance
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_2" role="tab"
                                        aria-selected="false">
                                        <i class="la la-cart-arrow-down"></i> Verification Finance
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_3" role="tab"
                                        aria-selected="true">
                                        <i class="la la-user"></i>Company
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane  active show" id="m_tabs_6_1" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-md-12 text-md-right">
                                                <span class="badge-pill pl-3 pr-3 pt-2 pb-2 badge-pill pl-3 pr-3 pt-2 pb-2 badge-warning">
                                                    {{ $finance->status }}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table">
                                                    <tr>
                                                        <th>Company Name :</th>
                                                        <td>{{ $finance->company->company_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Ownership Status :</th>
                                                        <td>{{ $finance->company->ownership_status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type Finance</th>
                                                        <td>{{ $finance->typeFinance->title_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time Finance</th>
                                                        <td>{{ $finance->timeFinance->name_time_id }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <table class="table">
                                            <tr>
                                                <th>Amount</th>
                                                <td class="text-right">{{format_priceID($finance->amount)}}</td>
                                            </tr>
                                            @if($finance->min_suku_bunga > 0)
                                            <tr>
                                                <th>Min Suku Bunga</th>
                                                <td class="text-right">1.5 * {{ $finance->timeFinance->duration_time }} = 
                                                    {{$finance->min_suku_bunga}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->max_suku_bunga > 0)
                                            <tr>
                                                <th>Max Suku Bunga</th>
                                                <td class="text-right">2 * {{ $finance->timeFinance->duration_time }} = 
                                                    {{$finance->max_suku_bunga}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->fee_provisi > 0)
                                            <tr>
                                                <th>Fee Provisi</th>
                                                <td class="text-right">
                                                    - {{format_priceID($finance->fee_provisi)}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->fee_penalty_delay > 0)
                                            <tr>
                                                <th>Fee Penalty Delay</th>
                                                <td class="text-right">
                                                    - {{format_priceID($finance->fee_penalty_delay)}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->fee_settled > 0)
                                            <tr>
                                                <th>Fee Settled</th>
                                                <td class="text-right">
                                                    - {{format_priceID($finance->fee_settled)}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->admin > 0)
                                            <tr>
                                                <th>Fee Admin</th>
                                                <td class="text-right">
                                                    - {{format_priceID($finance->admin)}}</td>
                                            </tr>
                                            @endif
                                            @if($finance->insurance > 0)
                                            <tr>
                                                <th>Fee Insurance</th>
                                                <td class="text-right">
                                                    - {{format_priceID($finance->insurance)}}</td>
                                            </tr>
                                            @endif
                                            {{-- <tr>
                                                <th>Grand Total</th>
                                                <th class="text-right bold">{{format_priceID($order->total_amount)}}
                                                </th>
                                            </tr> --}}
                                            <tr>
                                                <td>
                                                    <a class="btn btn-sm btn-primary"
                                                        href="{{ route('admin:master.finance.download-pdf', ['id' => $finance->id]) }}">
                                                        Download Data
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="m_tabs_6_2" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4 mb-5">
                                        <div class="card">
                                            <img class="card-img-top" @if(optional($finance->verification)->ktp)
                                            src="{{asset(optional($finance->verification)->ktp)}}"
                                            @else
                                            src="{{asset('img/image_placeholder_city.png')}}"
                                            @endif
                                            alt="image"
                                            style="width:100%">
                                            <div class="card-body">
                                                <h6 class="card-title">KTP</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5">
                                        <div class="card">
                                            <img class="card-img-top" @if(optional($finance->verification)->npwp)
                                            src="{{asset(optional($finance->verification)->npwp)}}"
                                            @else
                                            src="{{asset('img/image_placeholder_city.png')}}"
                                            @endif
                                            alt="image"
                                            style="width:100%">
                                            <div class="card-body">
                                                <h6 class="card-title">NPWP</h6>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($finance->company->ownership_status == 'corporate')
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->siup)
                                                src="{{asset(optional($finance->verification)->siup)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">SIUP</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->founding_deed)
                                                src="{{asset(optional($finance->verification)->founding_deed)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">Akta Pendirian</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->change_certificate)
                                                src="{{asset(optional($finance->verification)->change_certificate)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">Akta Perubahan (Jika ada)</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->sk_menteri)
                                                src="{{asset(optional($finance->verification)->sk_menteri)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">SK Menteri</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->company_signatures)
                                                src="{{asset(optional($finance->verification)->company_signatures)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">Tanda Daftar Perusahaan</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->report_statement)
                                                src="{{asset(optional($finance->verification)->report_statement)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">Laporan Keuangan</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->ktp_couples)
                                                src="{{asset(optional($finance->verification)->ktp_couples)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">KTP</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-5">
                                            <div class="card">
                                                <img class="card-img-top" @if(optional($finance->verification)->family_card)
                                                src="{{asset(optional($finance->verification)->family_card)}}"
                                                @else
                                                src="{{asset('img/image_placeholder_city.png')}}"
                                                @endif
                                                alt="image"
                                                style="width:100%">
                                                <div class="card-body">
                                                    <h6 class="card-title">NPWP</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4 mb-5">
                                        <div class="card">
                                            <img class="card-img-top" @if(optional($finance->verification)->document_bank)
                                            src="{{asset(optional($finance->verification)->document_bank)}}"
                                            @else
                                            src="{{asset('img/image_placeholder_city.png')}}"
                                            @endif
                                            alt="image"
                                            style="width:100%">
                                            <div class="card-body">
                                                <h6 class="card-title">Buku Rekening (3 Bulan terakhir)</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ route('admin:master.finance.download-all', ['id' => $finance->id]) }}">
                                            Download All File
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <tr>
                                                <th>Company Email</th>
                                                <td>{{ $finance->company->email_company }}</td>
                                            </tr>
                                            <tr>
                                                <th>Company Phone</th>
                                                <td>{{ $finance->company->phone_company }}</td>
                                            </tr>
                                        </table>
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
@stop

@section('scripts')

@stop