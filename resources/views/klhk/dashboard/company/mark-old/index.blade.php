@extends('dashboard.company.product.product_base_layout')

@section('title', 'Product Mark')

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
        <h5 class="breadcrumbs-title">Product Mark</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.product.index') }}">Product</a></li>
            <li class="active">Mark</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_mark')
  active
@endsection

@section('tab_content')

  <div class="section row">
    <div class="col s6">
    <h4 class="header2"><b>Product Mark List</b></h4>  
    </div>
    <div class="col s6 right-align">
      <a href="{{ Route('company.mark.create') }}" class="btn blue waves-effect">New Category</a>    
    </div>
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
        <table class="striped">
          <thead>
            <tr>
              <th data-field="name">Product Mark</th>
            </tr>
          </thead>
          <tbody>
            @if($mark->first())
              @foreach($mark as $row)
              <tr>
                <td>
                  <a href="{{ Route('company.mark.edit',$row->id_mark) }}">{{ $row->mark }}</a>
                </td>
                
              </tr>
              @endforeach
            @else
              <tr>
                <td class="center">-- No Product Mark Yet --</td>
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