@extends('dashboard.company.product.product_base_layout')

@section('title', 'Product Category')

@section('tab_breadcrumb')
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
        <h5 class="breadcrumbs-title">Category</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Category</li>
            
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

  <div class="section row">
    <div class="col s6">
    <h4 class="header2"><b>Categories List</b></h4>  
    </div>
    <div class="col s6 right-align">
      <a href="{{ Route('company.product.category.create') }}" class="btn blue waves-effect">New Category</a>    
    </div>
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
        <table class="striped">
          <thead>
            <tr>
              <th data-field="id">Category Name</th>
              <th data-field="name">Product Type</th>
            </tr>
          </thead>
          <tbody>
            @if($product_category->first())
              @foreach($product_category as $row)
              <tr>
                <td><a href="{{ Route('company.product.category.edit',$row->id_category) }}">{{ $row->category_name }}</a></td>
                <td>{{ $row->product_type->product_type_name }}</td>
                
              </tr>
              @endforeach
            @else
              <tr>
                <td class="center" colspan="2">-- No Product Categoy Yet --</td>
              </tr>
            @endif
            
          </tbody>
        </table>
        


    </div>


@endsection

@section('additionalScript')
<script>
  $(document).ready(function(){
    //$('#tab_menu li a').unbind('click');
  });
</script>
@endsection