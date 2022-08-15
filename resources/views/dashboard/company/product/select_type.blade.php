@extends('dashboard.company.product.product_base_layout')

@section('title', 'Select Product Type')

@section('breadcrumb')
<!--breadcrumbs start-->
<div id="breadcrumbs-wrapper">
    <!-- Search for small screen -->
    <div class="header-search-wrapper grey hide-on-large-only">
        <i class="mdi-action-search active"></i>
        <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
    </div>
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
        <h5 class="breadcrumbs-title">Select Product Type</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ URL('company.product.index') }}">Product</a></li>
            <li class="active"><a href="#" >Select Product Type</a></li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection


@section('indicator_inventory')
  active
@endsection

@section('tab_content')

<div class="row">
  
  
  <ul class="collection with-header">
    <li class="collection-header">
      <h5>Select Product Type</h5>
    </li>

    @foreach($product_type as $row)
    <li class="collection-item">
      <div>
        <a href="{{ url('company/product/create',['id'=>$row->id_tipe_product]) }}">
          {{ $row->product_type_name }}<span href="#!" class="secondary-content"><i class="mdi-navigation-arrow-forward"></i></span> <br><span class="black-text">{{ $row->product_type_description }}</span>
        </a>
      </div>
    </li>
    @endforeach

   
  </ul>

</div>
@endsection