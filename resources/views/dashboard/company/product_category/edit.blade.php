@extends('dashboard.company.product.product_base_layout')

@section('title', 'Edit Product Category')

@section('additionalStyle')

@endsection

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
        <h5 class="breadcrumbs-title">Edit Product Category</h5>
        <ol class="breadcrumbs amber-text">
            <li><a href="{{ Route('company.product.category.index') }}">Product Categories</a></li>
            <li class="active">Edit Product Category</li>

        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection



@section('indicator_category')
  active
@endsection

@section('tab_content')
<!--Form Advance-->

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        <h4 class="header2"><b>Product Category Info</b></h4>
        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.product.category.update',$product_category->id_category) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="row">
              <div class="input-field col s12 l4 ">
                <select name="product_type">
                  <option value=''>-- Select Category Type --</option>
                  @foreach($product_type as $key=>$row)
                    <option value="{{ $row->id_tipe_product }}" {{ ($row->id_tipe_product==$product_category->id_product_type) ? 'selected' : '' }}>{{ $row->product_type_name }}</option>
                  @endforeach
                </select>
                <label for="product_type">Product Type</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="category_name" type="text" value="{{ $product_category->category_name }}">
                <label for="category_name">Category Name</label>
              </div>

            </div>



            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  @foreach($product_category->list_status() as $key=>$row)
                  <option value="{{ $key }}" {{$product_category->status==$key ? 'selected' : '' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="status">Category Status</label>
              </div>
            </div>


            <div class="row clear">&nbsp;</div>
            <div class="">
              <a href="{{ Route('company.product.category.index') }}" class="btn pink waves-effect waves-light" type="submit" name="action"><i class="material-icons left">arrow_back</i> Back</a>

              <button class="btn blue waves-effect waves-light right" type="submit" name="action">Submit
                <i class="material-icons right">send</i>
              </button>
            </div>



          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('additionalScript')
  <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
  <script>
    $(document).ready(function(){
      form_ajax($('#form_ajax'),function(e){
        Materialize.toast(e.message, 4000);
      });

    });
  </script>
@endsection
