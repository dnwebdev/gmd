@extends('dashboard.company.base_layout')


@section('title', 'Order Information')

@section('additionalStyle')
<!--dropify-->
<link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css"
    rel="stylesheet" media="screen,projection">
{{--    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
<link href="{{ asset('materialize/js/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}"
    rel="stylesheet" />
<link href="{{ asset('css/order_edit.css') }}" rel="stylesheet">
<style>
    label.error {
        position: absolute
    }
</style>
@endsection


@section('breadcrumb')
@endsection

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
                    {{-- <i class="icon-pushpin mr-2"></i> --}} {{ trans('order_provider.order_detail') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ route('company.order.index') }}" class="breadcrumb-item">
                        {{ trans('sidebar_provider.order') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('order_provider.order_detail') }}</span>
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
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        @if(!$order->is_void)
        {{--                        <form id="form_ajax" method="POST"/>/--}}
        {{--                              action="{{ Route('company.order.update',$order->invoice_no) }}">--}}
        @endif
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="invoice_no" value="{{ $order->invoice_no }}" />
        <input type="hidden" name="product" value="{{ $order->order_detail->id_product }}" />
        <input type="hidden" name="schedule"
            value="{{ date('m/d/Y',strtotime($order->order_detail->schedule_date)) }}" />

        @if($order->order_detail->children > 0)
        <input type="hidden" name="children" value="{{ $order->order_detail->children }}" />
        @endif
        @if($order->order_detail->infant > 0)
        <input type="hidden" name="infant" value="{{ $order->order_detail->infant }}" />
        @endif
        <div class="row">
            <div class="col">
                <div class="widget card" id="order-profile">
                    <div class="widget-content ">

                        <div class="order-profile">
                            <div class="row pb-2">
                                <div class="col-12 text-right">
                                    @if (isset($order->customer_manual_transfer) && $order->status == 0)
                                        <span class="text-danger font-weight-bold text-uppercase badge-status">{{ $order->listManualTransfer()[$order->customer_manual_transfer->status] }}</span>
                                    @else
                                        @if($order->status =='1')
                                        <span
                                            class="text-success font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @elseif($order->status =='0')
                                        <span
                                            class="text-secondary font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @else
                                        <span
                                            class="text-danger font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @endif

                                    @endif
                                </div>
                            </div>
                            <div class="order-profile-header">
                                <div class="order-user">
                                    <div class="order-avatar display-none">
                                        <img src="../img/profile-picture.png" alt="ORDER USER" />
                                    </div>
                                    <div class="order-user-info">
                                        <h3 class="mb-half">{{$order->customer_info->first_name}}
                                            @if($order->customer_info->last_name){{$order->customer_info->last_name}}@endif
                                        </h3>
                                        @if($order->customer_info->city)
                                        <h6>{{$order->customer_info->city->city_name}}
                                            , {{$order->customer_info->city->state->country->country_name}}</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="order-status">
                                    <!-- <div class="input-field status pending">
        @if(!$order->is_void)
                                    <select name="status" id="status">
@if($order->status == 1)
                                        <option value="1" selected="selected">Paid</option>
                                        <option value="99">Void</option>
@else
                                        @foreach($order->list_status() as $key=>$row)
                                            <option value="{{$key}}" {{ ($key == $order->status) ? 'selected' : '' }}>{{$row}}</option>
            @endforeach
                                    @endif
                                            </select>
@else
                                    <span class="pink-text">Void</span>
@endif
                                        </div>
@if(!$order->is_void && $order->status == 1)
                                    <div id="void_reason" style="display: none">
                                        <input type="text" name="void_reason" class="required form-control">
                                        <label for="status">Void Reason</label>
                                    </div>
@elseif($order->is_void)
                                    <div id="void_reason">
{{ $order->void_reason }}
                                            <label for="status" class="active">Void Reason</label>
                                            </div>
@endif -->
                                    <h5>{{$order->invoice_no}}</h5>
                                </div>
                            </div>

                            <div class="order-profile-content">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-3">
                                        <h6>{{ trans('order_provider.package_information') }}</h6>
                                        <h4>{{$order->order_detail->product_name}}</h4>
                                        <h6>{{ trans('order_provider.guest') }}</h6>
                                        <h4>
                                            {{$order->order_detail->adult}}
                                            {{ optional($order->order_detail->unit)->name }}
                                            {{-- @if($order->order_detail->children)
                                            , {{$order->order_detail->children}} Children
                                            @endif --}}
                                        </h4>
                                        @if ($order->id_voucher)
                                        <h6>{{ trans('order_provider.applied_voucher') }}</h6>
                                        <h4>{{ $order->voucher_code }}</h4>
                                        <h6>{{ trans('order_provider.voucher_nominal') }}</h6>
                                        <h4 class="font-weight-bold">{{$order->order_detail->currency}}
                                            {{ number_format($order->voucher_amount,0) }}</h4>
                                        @endif

                                        @if ($order->product_discount > 0)
                                        <h6>{{ trans('order_provider.product_discount') }}</h6>
                                        <h4 class="font-weight-bold">{{$order->order_detail->currency}}
                                            {{ number_format($order->product_discount,0) }}</h4>
                                        @endif
                                        @if($order->fee > 0)
                                        @if($payment->pivot->charge === 0)
                                        <h6>Fee</h6>
                                        <h4 class="font-weight-bold">{{ number_format($order->fee) }}</h4>
                                        @endif
                                        @endif
                                        @if($order->fee_credit_card > 0)
                                        <h6>Fee Credit Card</h6>
                                        <h4 class="font-weight-bold">{{ number_format($order->fee_credit_card) }}</h4>
                                        @endif
                                        <h6>{{ trans('order_provider.amount') }}</h6>
                                        <h3>{{$order->order_detail->currency}}
                                            {{ number_format(($order->total_amount-$order->fee_credit_card)-$order->fee,0) }}
                                        </h3>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-9">
                                        <div class="order-contact">
                                            <h5>
                                                <span class="fa fa-envelope"></span>{{ $order->customer_info->email }}
                                            </h5>
                                            @if($order->customer_info->phone)
                                            <h5>
                                                <span class="fa fa-phone"></span>{{ $order->customer_info->phone }}
                                            </h5>
                                            @endif
                                            <h5>
                                                <span
                                                    class="fa fa-calendar"></span>{{ date('d M Y',strtotime($order->order_detail->schedule_date)) }}
                                            </h5>
                                            <h5>
                                                <span
                                                    class="fa fa-clock-o"></span>{{ $order->order_detail->product->schedule[0]->start_time }}
                                                - {{ $order->order_detail->product->schedule[0]->end_time }}
                                            </h5>
                                            @if($order->important_notes!=='' && $order->important_notes!==null)
                                            <h5>
                                                <span class="fa fa-pencil"></span>{{ $order->important_notes }}
                                            </h5>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="widget card" id="order-guest">
                    <div class="widget-header widget-collapse" id="header-order-guest" data-toggle="collapse"
                            data-target="#content-order-guest">
                        <h3>{{ trans('order_provider.guest_detail') }}</h3>
                <i class="float-right fa fa-chevron-down rotate text-white" id="header-order-guest-chevron"></i>
            </div>
            <div class="widget-content collapse" id="content-order-guest">
                <div class="form-group-no-margin">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">{{ trans('order_provider.booker_email') }}</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ $order->customer_info->email }}" readonly />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name">{{ trans('order_provider.booker_first_name') }}</label>
                                <input type="text" class="form-control" value="{{ $order->customer_info->first_name }}"
                                    readonly />

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">{{ trans('order_provider.booker_phone') }}</label>
                                <input type="text" class="form-control" name="phone" id="phone"
                                    value="{{ $order->customer_info->phone }}" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        @if($order->customer_info->identity_number)
                        <div class="col-md-4">
                            <label for="">{{ trans('order_provider.id_type') }}</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                value="{{ $order->customer_info->identity_number_type }}" readonly />
                        </div>
                        <div class="col-md-4">
                            <label for="">{{ trans('order_provider.id_number') }}</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                value="{{ $order->customer_info->identity_number }}" readonly />
                        </div>
                        @endif
                        @if($order->customer_info->emergency_number )
                        <div class="col-md-4">
                            <label for="">{{ trans('order_provider.emergency_number') }}</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                value="{{ $order->customer_info->emergency_number }}" readonly />
                        </div>
                        @endif
                    </div>
                    @if($order->customer_info->address || $order->customer_info->city)
                    <hr class="full-hr">
                    <div class="row">
                        <div class="col-md-6">
                            @if($order->customer_info->address)
                            <div class="form-group">
                                <label for="address">{{ trans('order_provider.address') }}</label>
                                <textarea class="form-control resize-none" name="address" id="address" rows="8"
                                    readonly>{{ $order->customer_info->address }}</textarea>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @if($order->customer_info->city)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="country">{{ trans('order_provider.country') }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $order->customer_info->city->state->country->country_name }}"
                                            readonly />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="state">{{ trans('order_provider.state') }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $order->customer_info->city->state->state_name }}" readonly />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="city">{{ trans('order_provider.city') }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $order->customer_info->city->city_name }}" readonly />
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div> --}}

        @if (!empty($order->customer_manual_transfer))
        <div class="widget card" id="customer_manual">
            <div class="widget-header widget-collapse" id="header-customer_manual" data-toggle="collapse"
                data-target="#content-customer-manual" aria-expanded="true">
                <h3>Info Manual Transfer</h3>
                <i class="chevron"></i>
            </div>
            <div class="widget-content collapse show pt-0" id="content-customer-manual">
                <div class="form-group-no-margin">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            {{-- {!! Form::open(['url'=>route('company.order.status_manual_transfer')]) !!} --}}
                            <form method="POST">
                                {{ csrf_field() }}
                            <div class="form-group mt-2">
                                <label>{!! trans('customer.bca_manual.sender_account_name') !!}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled
                                        value="{{ optional($order->customer_manual_transfer)->bank_name }}">
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label>{!! trans('customer.bca_manual.account_number') !!}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled
                                        value="{{ optional($order->customer_manual_transfer)->no_rekening }}">
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label>{!! trans('customer.bca_manual.proof_payment') !!}</label>
                                <div class="input-group">
                                    @if (!empty($order->customer_manual_transfer->upload_document))
                                    <img src="{{ asset($order->customer_manual_transfer->upload_document) }}" alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label>{!! trans('customer.bca_manual.note_to_customer') !!}</label>
                                <div class="input-group">
                                    <textarea name="note_customer" class="form-control" id="" cols="30" rows="5"
                                        {{ $order->customer_manual_transfer->status != 'need_confirmed' && $order->customer_manual_transfer->status != 'customer_reupload'  ? 'disabled' : '' }}
                                        >{!! $order->customer_manual_transfer->note_customer ? $order->customer_manual_transfer->note_customer : ''  !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label>Status</label>
                                <div class="input-group">
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected>- {!! trans('customer.bca_manual.select_status') !!} -</option>
                                        @foreach ($order->changeManualTransfer() as $key => $item)
                                            <option value="{{ $key }}" {{ $key == $order->customer_manual_transfer->status ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (in_array($order->customer_manual_transfer->status, ['need_confirmed','customer_reupload']))
                                <div class="form-group mt-4">
                                    <button type="button" id="btn-manual-transfer"
                                    class="btn btn-sm btn-primary">{!! trans('booking.submit') !!}</button>
                                </div>
                            @endif
                            {{-- {!! Form::close() !!} --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Custom Information -->
        @if ($order->order_detail->customDetail->isNotEmpty())
        <div class="widget card" id="custom_info">
            <div class="widget-header widget-collapse" id="header-order-notes" data-toggle="collapse"
                data-target="#content-custom-info">
                <h3>{{ trans('customer.book.additional_info') }}</h3>
                {{-- <i class="float-right fa fa-chevron-down rotate text-white"
                                id="header-order-notes-chevron"></i> --}}
                <i class="chevron"></i>
            </div>
            <div class="widget-content collapse pt-0" id="content-custom-info">
                <div class="form-group-no-margin">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            @if ($order->order_detail->customDetail->unique('participant')->count() > 1)
                            <ul class="nav nav-pills">
                                @foreach ($order->order_detail->customDetail->unique('participant') as $p)
                                <li class="nav-item">
                                    <a class="nav-link {{ $p->participant == 0 ? ' active' : '' }}"
                                        id="add{{ $p->participant }}-tab" data-toggle="tab"
                                        href="#add{{ $p->participant }}" role="tab"
                                        aria-controls="add{{ $p->participant }}"
                                        aria-selected="{{ $p->participant == 0 ? 'true' : 'false' }}">
                                        {{ $p->participant == 0 ? trans('customer.book.customer'): trans('customer.book.participant').' '.$loop->index }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <div class="tab-content pt-2" id="nav-tabContent">
                                @foreach ($order->order_detail->customDetail->groupBy('participant') as $key => $value)
                                <div class="tab-pane fade{{ $key == 0 ? ' show active' : '' }}" id="add{{ $key }}"
                                    role="tabpanel" aria-labelledby="add{{ $key }}-tab">
                                    <div class="row">
                                        @foreach ($value as $input)
                                        <div class="col-md-6">
                                            @switch($input->type_custom)
                                            @case('photo')
                                            @case('document')
                                            <div class="form-group">
                                                <label>{{ $input->label_name }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control border-left" disabled
                                                        value="{{ ucfirst($input->type_custom) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text border-right">
                                                            <a
                                                                href="{{ $input->value }}">{{ trans('product_provider.preview') }}</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                            @case('checkbox')
                                            <div class="mt-2"><strong
                                                    class="text-muted">{{ $input->label_name }}</strong></div>
                                            @foreach($input->value as $v)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" checked disabled>
                                                <label class="form-check-label">{{ $v }}</label>
                                            </div>
                                            @endforeach
                                            @break
                                            @case('textarea')
                                            <div class="form-group">
                                                <label>{{ $input->label_name }}</label>
                                                <textarea class="form-control" disabled>{{ $input->value }}</textarea>
                                            </div>
                                            @break
                                            @case('city')
                                            @case('state')
                                            @case('country')
                                            <div class="form-group">
                                                <label>{{ $input->label_name }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $location[$input->type_custom][(int) $input->value] }}"
                                                    disabled>
                                            </div>
                                            @break
                                            @default
                                            <div class="form-group">
                                                <label>{{ $input->label_name }}</label>
                                                <input type="text" class="form-control" value="{{ $input->value }}"
                                                    disabled>
                                            </div>
                                            @break
                                            @endswitch
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a class="btn bg-success text-white border-0"
                        href="{{ route('company.order.export_custom_detail', ['invoice_no' => $order->invoice_no]) }}">
                        {{ trans('product_provider.download_excel') }}
                    </a>
                    @if ($order->order_detail->customDetail->filter(function ($value) {
                    return in_array($value->type_custom, ['photo', 'document']);
                    })->count() > 0)
                    <a class="btn bg-success text-white"
                        href="{{ route('company.order.download_custom_detail', ['invoice_no' => $order->invoice_no]) }}">
                        {{ trans('product_provider.download_attachment') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        <!-- END Custom Information -->

        <div class="widget card" id="order-notes">
            <div class="widget-header widget-collapse" id="header-order-notes" data-toggle="collapse"
                data-target="#content-order-notes">
                <h3>{{ trans('order_provider.order_notes') }}</h3>
                {{-- <i class="float-right fa fa-chevron-down rotate text-white"
                            id="header-order-notes-chevron"></i> --}}
                <i class="chevron"></i>
            </div>
            <div class="widget-content collapse" id="content-order-notes">
                <div class="form-group-no-margin">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="country">{{ trans('order_provider.notes_from_the_customer') }}</label>
                                <textarea name="external_notes" class="form-control" style="max-height:10rem;"
                                    disabled>{{ str_replace('<br/>', "\n", $order->external_notes) }}</textarea>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="form-group">
        <label for="country">Internal Notes</label>
        <textarea name="internal_notes" class="form-control">{{ $order->internal_notes }}</textarea>
        </div>
    </div> -->
                    </div>
                </div>
            </div>
        </div>
        @if(in_array($order->status,[1]))
        <div class="widget card" id="order-guide">
            <div class="widget-header">
                <h3>{{ trans('order_provider.guide_information') }}</h3>
            </div>
            {!! Form::open() !!}
            <div class="widget-content">
                <div class="form-group-no-margin">
                    <div class="row mb-3">
                        <div class="col-md col-sm-12 mt-lg-0 mt-4">
                            {!! Form::label('guide_name',trans('order_provider.guide_name')) !!}
                            {!! Form::text('guide_name',null,['class'=>'form-control', 'maxlength'=>'50']) !!}
                        </div>
                        <div class="col-md col-sm-12 mt-lg-0 mt-4">
                            {!! Form::label('guide_phone_number',trans('order_provider.guide_phone')) !!}
                            {!! Form::text('guide_phone_number',null,['class'=>'form-control number',
                            'maxlength'=>'15']) !!}
                        </div>
                        <div class="col-md-auto md-and-center mt-lg-auto mt-3">
                            <button type="button" class="btn btn-block btn-primary"
                                id="btn-add-guide">{{ trans('order_provider.more_guide') }}
                            </button>
                        </div>
                    </div>
                    @foreach($order->guides()->orderBy('created_at')->get() as $guide)

                    <div class="row mb-3">
                        <div class="col-md col-sm-12 mt-lg-0 mt-4">
                            {!! Form::label('guide_name[]',trans('order_provider.guide_name')) !!}
                            {!! Form::text('guide_name[]',$guide->guide_name,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-md col-sm-12 mt-lg-0 mt-4">
                            {!! Form::label('guide_phone_number[]',trans('order_provider.guide_phone')) !!}
                            {!! Form::text('guide_phone_number[]',$guide->guide_phone_number,['class'=>'form-control
                            number','readonly','maxlength'=>'15']) !!}
                        </div>
                        <div class="col-md-auto md-and-center mt-lg-auto mt-3">
                            <button type="button" data-id="{{$guide->id}}"
                                class="btn btn-danger btn-delete-guide guide-btn w-100">{{ trans('order_provider.guide_less') }}
                            </button>
                        </div>
                    </div>
                    @endforeach

                </div>
                @if($order->guides()->orderBy('created_at')->count()>0)
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn col-lg-auto btn-primary float-right mt-3" id="btn-send"
                            data-resend="{{ trans('order_provider.resend_to_customer') }}"
                            data-send="{{ trans('order_provider.send_to_customer') }}">
                        </button>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn col-lg-auto btn-primary float-right mt-3" id="btn-send"
                            data-resend="{{ trans('order_provider.resend_to_customer') }}"
                            data-send="{{ trans('order_provider.send_to_customer') }}">
                        </button>
                    </div>
                </div>
                @endif

            </div>
            {!! Form::close() !!}
        </div>
        @endif
        <div class="widget display-none" id="order-payment">
            <div class="widget-header">
                <h3>Payment & Invoice</h3>
            </div>
            <div class="widget-content">
                <div class="form-group-no-margin">
                    <div class="row">
                        @if($order->status == 8 || $order->status == 0 )
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="allow_payment">Allow online payment</label>
                                <select name="allow_payment" class="form-control">
                                    <option value="1" {{ $order->allow_payment ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0" {{ !$order->allow_payment ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="send_invoice">Resend Invoice?</label>
                                <select name="send_invoice" class="form-control">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- @if(!$order->is_void)
                <button class="btn" type="submit" name="action" style="color: #fff">Update</button>
@endif -->
    </div>
</div>
@if(!$order->is_void)
{{--                        </form>--}}
@endif

@if($order->payment->payment_gateway=='Cash On Delivery')
{!! Form::open(['url'=>route('company.order.edit-status',['id'=>$order->invoice_no])]) !!}
<div class="row">
    <div class="col-md-6">
        <div class="from-group">
            {!! Form::label('status','Status') !!}
            {!! Form::select('status',[
            0=>\trans('order_provider.not_paid'),
            1=>\trans('order_provider.paid'),
            6=>\trans('order_provider.cancel_vendor')
            ],$order->getAttributes()['status']
            ,['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        {!! Form::label('','&nbsp;') !!}
        <button class="btn btn-primary btn-block">{{trans('order_provider.submit')}}</button>
    </div>
</div>

{!! Form::close() !!}
@endif
</div>
</div>
@stop

@section('additionalScript')
<!-- Autocomplete -->
<script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>

<!-- Datepicker -->
<script type="text/javascript"
    src="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('materialize/js/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"
    type="text/javascript"></script>
{{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}

<!-- Form Ajax -->
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/order_company.js') }}"></script>

<script>
    //   let submit = false;
      $(document).ready(function () {
        $(document).on('click', '#btn-add-guide', function (e) {
          e.preventDefault();
          if (!submit) {
            submit = true;
            loadingStart();
            let t = $(this);
            t.closest('.row').find('label.error').remove();
            let data = {
              'guide_name': t.closest('.row').find('input[name=guide_name]').val(),
              'invoice_no': "{{$order->invoice_no}}",
              'guide_phone_number': t.closest('.row').find('input[name=guide_phone_number]').val(),
              '_token': "{{csrf_token()}}"
            };
            $.ajax({
              url: "{{route('company.order.saveGuide')}}",
              type: 'POST',
              data: data,
              success: function (data) {
                submit = false;
                let html = '';
                html += '<div class="row">\n' +
                    '                                                        <div class="col-md col-sm-12 mt-lg-0 mt-4">\n' +
                    '                                                            <label for="guide_name[]" class="active">{{trans('order_provider.guide_name')}}</label>\n' +
                    '                                                            <input class="form-control" readonly="" name="guide_name[]" type="text" value="' + data.result.guide_name + '" id="guide_name[]">\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="col-md col-sm-12 mt-lg-0 mt-4">\n' +
                    '                                                            <label for="guide_phone_number[]" class="active">{{trans('order_provider.guide_phone')}}</label>\n' +
                    '                                                            <input class="form-control" readonly="" name="guide_phone_number[]" type="number" value="' + data.result.guide_phone_number + '" id="guide_phone_number[]">\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="col-md-auto md-and-center mt-lg-auto mt-3">\n' +
                    '                                                            <button data-id="' + data.result.id + '" type="button" style="\n' +
                    '                                                            width: 100%; margin: auto; background-color: #dc3545;\n' +
                                    '                                                background: #f44336;\n' +
                    '" class="btn btn-danger btn-delete-guide">{{ trans('order_provider.guide_less') }}\n' +
                    '                                                            </button>\n' +
                    '                                                        </div>\n' +
                    '                                                    </div>';
                t.closest('.form-group-no-margin').append(html);
                t.closest('div.row').find('input').val('');
                $('#btn-send').show();
                loadingFinish();
                sendButton(1);
              },
              error: function (e) {
                submit = false;
                loadingFinish();
                if (e.status === 422) {
                  let errors = e.responseJSON.errors

                  $.each(errors, function (i, e) {
                    $('input[name=' + i + ']').closest('div').append('<label class="error">' + e[0] + '</label>');
                  })
                } else {
                  toastr.error(e.responseJSON.message, '{{__('general.whoops')}}');
                }
              }
            })
          }
        });
        $(document).on('click', '.btn-delete-guide', function (e) {
          e.preventDefault();
          loadingStart();
          let t = $(this);
          $.ajax({
            url: "{{route('company.order.deleteGuide')}}",
            type: "POST",
            data: {
              id: t.data('id'),
              '_token': "{{csrf_token()}}"
            },
            success: function (data) {
              t.closest('.row').remove();
              hideButtonSend();
              loadingFinish();
              toastr.success('Success', data.message);
              sendButton(1);
            },
            error: function (e) {
              loadingFinish();
              toastr.error( e.responseJSON.message,'{{__('general.whoops')}}')
            }
          })
        });
        form_ajax($('#form_ajax'), function (e) {
          if (e.status == 200) {
            toastr.remove()
            swal({
              title: "Success",
              text: e.message,
              type: "success",
            }).then(function () {
              location.reload()
            });
          } else {
            toastr.remove()
            swal({
              title: "{{trans('general.whoops')}}",
              text: e.message,
              type: "error",
            }).then(function () {
            });
          }
        });
      });

    hideButtonSend();
    function hideButtonSend(){
        if ($('input[name="guide_name[]"]').length === 0) {
            $('#btn-send').hide();
        }
    }

    @if($order->has_sent_guide_email == '1')
        sendButton(2);
    @else
        sendButton(1);
    @endif
    function sendButton(e){
        var send_button = $('#btn-send');
        if(e == 1){
            send_button.text(send_button.attr('data-send'));
            send_button.removeClass('btn-warning').addClass('btn-primary');
        } else {
            send_button.text(send_button.attr('data-resend'));
            send_button.removeClass('btn-primary').addClass('btn-warning');
            // #ffc107
        }
    }
      $(document).on('click', '#btn-send', function (e) {
        e.preventDefault();
        loadingStart();
        $.ajax({
          url: '{{route('company.order.sendGuide')}}',
          type: 'post',
          data: {invoice_no: '{{$order->invoice_no}}', '_token': "{{csrf_token()}}"},
          success: function (data) {
            loadingFinish();
            toastr.success(data.message)
            sendButton(2);
          },
          error: function (e) {
            loadingFinish();
            toastr.error( e.responseJSON.message,'{{__('general.whoops')}}')
          }
        })
      })

    @if (!empty($order->customer_manual_transfer))
        $(document).on('click','#btn-manual-transfer', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('span.error').remove();
            $.ajax({
                url: '{{ route('company.order.status_manual_transfer', ['invoice_no' => $order->invoice_no]) }}',
                type:'post',
                dataType:'json',
                data:t.closest('form').serialize(),
                success:function (data) {
                    loadingFinish();
                    toastr.success(data.message,"Yey");
                    swal({
                        title: "Success",
                        text: data.message,
                        type: "success",
                    }).then(function () {
                        location.reload()
                    });
                },
                error:function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                            t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                            t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                        })
                    }
                    if (e.responseJSON.result !== undefined) {
                        swal({
                            title: '{{__('general.whoops')}}',
                            text: e.responseJSON.message,
                            type: "error",
                        }).then(function() {
                            location.reload()
                        })
                    }
                    loadingFinish();
                }
            })
        })
    @endif
</script>
@endsection