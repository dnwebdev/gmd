@extends('dashboard.company.product.product_base_layout')

@section('title', __('sidebar_provider.product'))
@section('additionalStyle')
  <style type="text/css">
  
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #fff !important;
    border: none;
    background: #0094d1;
	}

  </style>

@endsection

@section('breadcrumb')
<div id="breadcrumbs-wrapper">
<div class="dashboard-header">
      <div class="container-fluid">
      @include('dashboard.company.partial.language')
        <div class="row">
          <div class="col">
            <div class="dashboard-title">
              <h1>Product</h1>
              <div class="dashboard-tools">
                <div class="breadcrumbs">
                  <ul>
                    <li><a href="{{ Route('company.dashboard') }}">{{ trans('sidebar_provider.dashboard') }}</a></li>
                    <li>{{ trans('sidebar_provider.product') }}</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
    
<!--breadcrumbs start
<div id="breadcrumbs-wrapper">
    Search for small screen 
    <div class="header-search-wrapper grey hide-on-large-only">
        <i class="mdi-action-search active"></i>
        <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
    </div>
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
        <h5 class="breadcrumbs-title">Inventory</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Inventory</li>
        </ol>
      </div>
    </div>
  </div>
</div>
breadcrumbs end-->
@endsection

@section('indicator_inventory')
  active
@endsection

@section('tab_content')
<div class="dashboard-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col">

            <div class="dashboard-cta">
              <a href="{{ URL('company/product/create') }}" class="btn btn-primary btn-cta">{{ trans('product_provider.new_product') }}</a>
            </div>

            <div class="widget" id="product">
              <div class="widget-header">
                <h3>{{ trans('product_provider.list_of_product') }}</h3>
                <div class="widget-tools tools-full">
                  <div class="widget-search" style="display: none">
                    <form action="#">
                      <button type="submit" class="btn-search"><span class="fa fa-search"></span></button>
                      <input type="search" id="q" name="q" class="form-control" placeholder="Search product.." />
                    </form>
                  </div>
                </div>
              </div>
              <div class="widget-content widget-full">
                <div class="widget-table">
                  <div class="responsive-table">
                    <table class="table-border-bottom tablesorter">
                      <thead>
                        <tr>
                          <th>{{ trans('product_provider.name') }}</th>
                          <th>{{ trans('product_provider.type') }}</th>
                          <th>{{ trans('product_provider.location') }}</th>
                          <th>{{ trans('product_provider.status') }}</th>
                        </tr>
                      </thead>
                      <tbody id="data_result" offset="{{ $product->first() ? $product->count() : 0 }}" load_more_url="{{Route('company.product.load_more')}}">
                      @foreach($product as $row)
                        <tr>
                            <td><a href="{{ Route('company.product.edit',$row->id_product) }}" class="grey-text text-darken-4">{{ $row->product_name }} </a></td>
                          <!--<td></td>-->
                          @if($row->id_product_type )
                          <td class="center" >{{$row->product_type->product_type_name}}</td>
                          @endif

                          @if($row->city)
                          <td class="right-align">{{ $row->city->city_name }} </td>
                          @else
                          <td class="right-align"></td>
                          @endif

                          @if($row->status == 0)
                          <td class="center">Not Active</td>
                          @else
                          <td class="center"> Active</td>
                          @endif
                        </tr>
                        @endforeach
                        
                      
                      </tbody>
                    </table>
                  </div>
                  <div class="pagination justify-content-end">
                    <ul>
                      {{$product->links()}}
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
@stop
<!--
	<div class="section row">
		<div class="col s6">
		<h4 class="header2"><b>Product List</b></h4>
		</div>
		<div class="col s6 right-align">
			<a href="{{ URL('company/product/create') }}" class="btn blue waves-effect">New Product</a>
		</div>

	</div>
	<div class="divider"></div>
	<div class="row mt-2" id="data_result" offset="{{ $product->first() ? $product->count() : 0 }}" load_more_url="{{Route('company.product.load_more')}}">


    @foreach($product as $row)

      <div class="item product col l3 s12">
        <div class="card hoverable">
          <div class="card-image waves-effect waves-block waves-light"> -->
            <!-- @if($row->mark )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">{{ $row->mark->mark }}</a>
            @endif --><!--
            @if($row->id_product_type == 1 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Other Activities</a>
            @endif
            @if($row->id_product_type == 2 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Day Tour</a>
            @endif
            @if($row->id_product_type == 3 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Cash Voucher</a>
            @endif
            @if($row->id_product_type == 4 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Custom Trip</a>
            @endif
            @if($row->id_product_type == 5 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Package Trip</a>
            @endif
            --><!-- @if($row->id_product_type == 6 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Other Activities</a>
            @endif -->
            <!--@if($row->id_product_type == 7 )
            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">Open Trip</a>
            @endif
            <a href="{{ Route('company.product.edit',$row->id_product) }}">
              <img {{ (!$row->status || !$row->publish) ? "class=disabled" :'' }} src="{{ asset('uploads/products/thumbnail/'.$row->main_image) }}" alt="item-img">
            </a>
          </div>
          <ul class="card-action-buttons">

            <li>
              <a class="btn-floating waves-effect waves-light pink">
                <i class="material-icons activator">info_outline</i>
              </a>
            </li>
          </ul>
          <div class="card-content">
            <div class="row">
              <div class="col s12">
                <p class="card-title grey-text text-darken-4 truncate"><a href="{{ Route('company.product.edit',$row->id_product) }}" class="grey-text text-darken-4">{{ $row->product_name }}</a>
                </p>
                <p class="right-align">
                  <a>{{ $row->currency }} {{ number_format($row->advertised_price,0) }}</a>
                </p>
              </div>
            </div>
          </div>
          <div class="card-reveal">
            <span class="card-title grey-text text-darken-4">
              <i class="material-icons right">close</i> {{ $row->product_name }}</span>
            <p>{{ $row->brief_description }}</p>
          </div>
        </div>
      </div>
    @endforeach

    <center>
      <div class="preloader-wrapper">
        <div class="spinner-layer spinner-blue">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
        <div class="spinner-layer spinner-red">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
        <div class="spinner-layer spinner-yellow">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
        <div class="spinner-layer spinner-green">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </center>



    </div>
-->



@section('additionalScript')
<script type="text/javascript" src="{{ asset('js/indexjs.js') }}"></script>
<script src="{{ asset('dest-operator/lib/js/jquery-slim.min.js') }}"></script>
<script src="{{ asset('dest-operator/lib/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dest-operator/js/operator.js') }}"></script>
<script src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>
<script>
jQuery('.tablesorter').tablesorter();
</script>
{{--<script>--}}
{{--	$(document).ready(function(){--}}
{{--		//$('#tab_menu li a').unbind('click');--}}
{{--	});--}}
{{--</script>--}}
@endsection
