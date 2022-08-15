@extends('dashboard.company.order.order_base_layout')

@section('title', 'Extra Item')

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
        <h5 class="breadcrumbs-title">Extra</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.order.index') }}">Order</a></li>
            <li class="active">Extra</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_extras')
  active
@endsection

@section('tab_content')

  <div class="section row">
    <div class="col s6">
    <h4 class="header2"><b>Extra Item List</b></h4>  
    </div>
    <div class="col s6 right-align">
      <a href="{{ Route('company.extra.create') }}" class="btn blue waves-effect">New Extra</a>    
    </div>
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
        <table class="striped">
          <thead>
            <tr>
              <th data-field="name"></th>
              <th data-field="name">Extra Name</th>
              <th data-field="name">Price Type</th>
              <th data-field="name">Currency</th>
              <th data-field="name">Amount</th>
            </tr>
          </thead>
          <tbody>
            @if($extra->first())
              @foreach($extra as $row)
              <tr>
                <td>
                  @if($row->image)
                    <img height="50" src="{{ asset('uploads/extras/'.$row->image) }}">
                  @endif
                </td>
                
                <td>
                  <a href="{{ Route('company.extra.edit',$row->id_extra) }}">{{ $row->extra_name }}</a>
                </td>
                <td>{{ $row->priceTypeText }}</td>
                <td>{{ $row->currency }}</td>
                <td class="right-align">{{ number_format($row->amount,0) }}</td>
                
              </tr>
              @endforeach
            @else
              <tr>
                <td class="center" colspan="5">-- No Extra Yet --</td>
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