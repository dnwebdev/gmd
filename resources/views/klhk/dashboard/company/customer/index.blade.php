@extends('dashboard.company.order.order_base_layout')


@section('title', 'Customer List')

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
        <h5 class="breadcrumbs-title">Customer</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Customer</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_customer')
  active
@endsection

@section('tab_content')

  <div class="section row">
    <div class="col s6">
      <h4 class="header2"><b>Customer List</b></h4>  
    </div>
    
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
    <table class="striped">
      <thead>
        <tr>
          <th data-field="id">Join Date (GMT+7)</th>
          <th data-field="id">Name</th>
          <th data-field="id">Email</th>
          <th data-field="name">Phone</th>
          <th data-field="name">Status</th>
        </tr>
      </thead>
      <tbody id="data_result" offset="{{ $customer->first() ? $customer->count() : 0 }}" load_more_url="{{Route('company.customer.load_more')}}">
        @if($customer->first())
          @foreach($customer as $row)
          <tr>
            <td>{{ $row->created_at }} </td>
            <td>{{ $row->first_name }} {{ $row->last_name }}</td>
            <td>{{ $row->email }} {{ !$row->email_verified? '(Not Verified)' : ''}}</td>
            <td>{{ $row->phone }}</td>
            <td>{{ $row->status }}</td>
          </tr>
          @endforeach
        @else
          <tr><td colspan="5" class="center">-- No Customer Yet --</td></tr>
        @endif
        
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6" class="center">
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
          </td>
        </tr>
      </tfoot>
    </table>
        


    </div>


@endsection


@section('additionalScript')
<script type="text/javascript" src="{{ asset('js/indexjs.js') }}"></script>
<script>
  $(document).ready(function(){
    //$('#tab_menu li a').unbind('click');
  });
</script>
@endsection