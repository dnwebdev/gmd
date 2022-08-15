@extends('dashboard.company.order.order_base_layout')


@section('title', 'Order List')

@section('breadcrumb')
<div class="dashboard-header">
  <div class="container-fluid">
  @include('dashboard.company.partial.language')
    <div class="row">
      <div class="col">
        <div class="dashboard-title">
          <h1>{{ trans('sidebar_provider.order') }}</h1>
          <div class="dashboard-tools">
            <div class="breadcrumbs">
              <ul>
                <li><a href="{{ Route('company.dashboard') }}">{{ trans('sidebar_provider.dashboard') }}</a></li>
                <li>{{ trans('sidebar_provider.order') }}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('indicator_order')
  active
@endsection

@section('tab_content') 
<div class="dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col">

        <div class="dashboard-cta display-none">
          <a href="{{ Route('company.order.create') }}" class="btn btn-primary btn-cta">New Order</a>
        </div>

        <div class="widget" id="order">
          <div class="widget-header">
            <h3>{{ trans('order_provider.list_of_order') }}</h3>
            <div class="widget-tools tools-full">
              <div class="widget-search display-none">
                <form action="#">
                  <button type="submit" class="btn-search"><span class="fa fa-search"></span></button>
                  <input type="search" id="q" name="q" class="form-control" placeholder="Search guests or order.." />
                </form>
              </div>
            </div>
          </div>
          <div class="widget-content widget-full">
            <div class="widget-table">
              <div class="responsive-table">
                <table class="table-border-bottom tablesorter" id="order-table">
                  <thead>
                    <tr>
                      <th>{{ trans('order_provider.invoice_no') }}</th>
                      <!--<th>Name</th>-->
                      <th>{{ trans('order_provider.product') }}</th>
                      <th>{{ trans('order_provider.total_price') }}</th>
                      <th>{{ trans('order_provider.date') }}</th>
                      <th>{{ trans('order_provider.status') }}</th>
                    </tr>
                  </thead>
                  <tbody >
                    @if($order->first())
                    @foreach($order as $row)
                    <tr>
                      <td><a href="{{Route('company.order.edit',$row->invoice_no)}}">{{ $row->invoice_no }}</a> </td>
                      <!--<td></td>-->
                      <td><a href="{{Route('company.product.edit',$row->order_detail->product->id_product)}}" target="_blank">{{ $row->order_detail->product->product_name }}</a></td>
                      <td class="right-align">{{ $row->order_detail->product->currency }} {{ number_format($row->total_amount,0) }}</td>
                      <td class="right-align">{{ $row->created_at }}</td>
                      <td class="center">{{ $row->status_text }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr><td colspan="6" class="center">-- No Transaction Yet --</td></tr>
                    @endif
        
                  </tbody>
                </table>
              </div>
              <div class="pagination justify-content-end">
                <ul>
                  {{$order->links()}}
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('additionalScript')
<!-- Plugin -->
<script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>

<!-- Custom  -->
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/product_company.js') }}"></script>
<script type="text/javascript" src="{{ asset('dest-operator/js/operator.js') }}"></script>

<script>
jQuery('.tablesorter').tablesorter();
</script>
@endsection