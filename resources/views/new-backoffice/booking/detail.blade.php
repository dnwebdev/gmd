@extends('new-backoffice.header')
@section('content')
  <div id="data-booking-online">
    <h3>Detail Pesanan</h3>
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs">
          <li class="nav-item"><a href="#tab-1" class="nav-link active" data-toggle="tab"><i class="icon-cart"></i> Pesanan</a></li>
          <li class="nav-item"><a href="#tab-2" class="nav-link" data-toggle="tab"><i class="icon-info3"></i> Detail</a></a></li>
          <li class="nav-item"><a href="#tab-3" class="nav-link" data-toggle="tab"><i class="icon-user"></i> Pelanggan</a></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-1">
            <div class="d-block">
              <div class=" d-block d-md-inline-flex content-tab-detail mb-5 mb-md-2" id="header">
                <h3>No Invoice : {{$order->invoice_no}}</h3>
                {!! renderStatusOrder($order, true) !!}
              </div>
            </div>
            <div class="d-block">
              <div class="d-flex col-md-3 justify-content-between px-0">
                <span>Harga</span>
                <span>{{format_priceID($order->amount)}}</span>
              </div>
            </div>
            @if($order->fee_credit_card > 0)
              <div class="d-block">
                <div class="d-flex col-md-3 justify-content-between px-0">
                  <span>Biaya Kartu Kredit</span>
                  <span>{{ format_priceID($order->fee_credit_card)}}</span>
                </div>
              </div>
            @endif
            @if($order->product_discount > 0)
              <div class="d-block">
                <div class="d-flex col-md-3 justify-content-between px-0">
                  <span>Potongan Diskon</span>
                  <span> - {{format_priceID($order->product_discount)}}</span>
                </div>
              </div>
            @endif
            @if($order->voucher_amount > 0)
              <div class="d-block">
                <div class="d-flex col-md-3 justify-content-between px-0">
                  <span>Potongan Voucher</span>
                  <span> - {{format_priceID($order->voucher_amount)}}</span>
                </div>
              </div>
            @endif
            <div class="d-block">
              <div class="d-flex col-md-3 justify-content-between px-0">
                <span><strong>Total Harga</strong></span>
                <span><strong>{{format_priceID($order->total_amount)}}</strong></span>
              </div>
            </div>
            @if ($order->payment)
            <div class="payment-method">
              <span class="d-block">Metode Pembayaran: </span>
              <span><strong>{{$order->payment->payment_gateway}}</strong></span>
            </div>
            @endif
          </div>

          <!--        TAB 2-->
          <div class="tab-pane fade" id="tab-2">
            @if($order->booking_type =='online')
                <table class="table datatable-responsive-column-controlled">
                    <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left">{{ optional($order->order_detail)->product_name}}</td>
                        <td class="text-right">{{optional($order->order_detail)->adult}}</td>
                        <td class="text-right">{{format_priceID(optional($order->order_detail)->adult_price)}}</td>
                        <td class="text-right">{{format_priceID(optional($order->order_detail)->adult_price* optional($order->order_detail)->adult)}}</td>
                    </tr>
                    </tbody>


                </table>
            @else
                <table class="table datatable-responsive-column-controlled">
                    <tr>
                        <th>Detail</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                    @foreach($order->invoice_detail as $detail)
                        <tr>
                            <td class="text-left">{{$detail['description']}}</td>
                            <td class="text-right">{{$detail['qty']}}</td>
                            <td class="text-right">{{format_priceID($detail['price'])}}</td>
                            <td class="text-right">{{format_priceID($detail['qty'] * $detail['price'])}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
          </div>

          <!--        TAB 3-->
          <div class="tab-pane fade" id="tab-3">
            <h4>Informasi Pelanggan</h4>
            <thead>
            <tr>
              <td></td>
              <td></td>
            </tr>
            </thead>
            <table class="table datatable-responsive-column-controlled">
              <tbody>
              <tr>
                <td>Nama Pelanggan</td>
                <td>: {{$order->customer_info->first_name}}</td>
              </tr>
              <tr>
                <td>Email Pelanggan</td>
                <td>: {{$order->customer_info->email}}</td>
              </tr>
              <tr>
                <td>No. Telp Pelanggan</td>
                <td>: {{$order->customer_info->phone_number}}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
