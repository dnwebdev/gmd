@extends('dashboard.company.product.product_base_layout')

@section('title', 'Tax')

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
        <h5 class="breadcrumbs-title">Tax</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Tax</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_tax')
  active
@endsection


@section('tab_content')

  <div class="section row">
    <div class="col s6">
    <h4 class="header2"><b>Categories List</b></h4>  
    </div>
    <div class="col s6 right-align">
      <a href="{{ Route('company.tax.create') }}" class="btn blue waves-effect">New Tax</a>    
    </div>
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
        <table class="striped">
          <thead>
            <tr>
              <th data-field="id">Tax Name</th>
              <th data-field="name">Tax Amount</th>
            </tr>
          </thead>
          <tbody>
            @if($tax->first())
              @foreach($tax as $row)
              <tr>
                <td><a href="{{ Route('company.tax.edit',$row->id_tax) }}">{{ $row->tax_name }}</a></td>
                <td>{{ $row->tax_amount }} {{ $row->tax_amount_type == 1? '%' : '' }}</td>
                
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="2" class="center">-- No Tax Yet --</td>
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