@extends('dashboard.company.order.order_base_layout')


@section('title', 'Company Balance')

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
        <h5 class="breadcrumbs-title">Company Balance</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Company Balance</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_balance')
  active
@endsection

@section('tab_content')

  <div class="section row">
    <div class="col s3">
      <div class="">
        <h4 class="header2"><b>Balance</b></h4>  
      </div>
      
      
    </div>

    <div class="col s9">
      
      <div class="row">
        <div class="input-field col s3 right">
          <input type="text" name="end_date[]" class="datepicker end_date" value="{{ date('m/1/Y') }}" />
          <label for="end_date">End Date</label>
        </div>

        <div class="input-field col s3 right">
          <input type="text" name="start_date[]" class="datepicker start_date" value="{{ date('m/t/Y') }}"/>
          <label for="start_date">Start Date</label>
        </div>

        <div class="input-field col s6 right">
          <select name="transaction_type" multiple>
              <option value="1" selected="">Pemasukan</option>
              <option value="2" selected="">Pengeluaran</option>
            </select>
            <label for="end_date">Jenis transaksi</label>
        </div>
      </div>
      

    </div>
    
  </div>
  <div class="divider"></div>
  <div class="section">
        
        <table class="striped">
          <thead>
            <tr>
              <th data-field="id">Date</th>
              <th data-field="id">Doc. Number</th>
              <th data-field="id">Description</th>
              <th data-field="id">Currency</th>
              <th data-field="id">Rates</th>
              <th data-field="id">Base</th>
              <th data-field="id">Debit</th>
              <th data-field="name">Credit</th>
              <th data-field="name">Balance</th>
              <th data-field="name" class="center-align">Status</th>
            </tr>
          </thead>
          <tbody>

            @if($balance->first())
              @php ($bal = 0)
              @foreach($balance as $row)
                @php ($bal = $bal + (($row->credit - $row->debet)*$row->rate) )
              <tr>
                <td>{{ date('M d, Y',strtotime($row->created_at)) }} </td>
                <td>{{ $row->journal_code }}</td>
                <td>{{ $row->description }} </td>
                <td class="center-align">{{ $row->currency }}</td>
                <td class="right-align">{{ number_format($row->rate,0) }}</td>
                <td class="right-align">{{ number_format($row->credit + $row->debet,0) }}</td>
                <td class="right-align pink-text">{{ number_format(($row->debet*$row->rate),0) }}</td>
                <td class="right-align indigo-text">{{ number_format(($row->credit*$row->rate),0) }}</td>
                <td class="right-align">{{ number_format($bal,0) }}</td>
                <td class="center-align">{{ $row->status_text }}</td>
              </tr>
              @endforeach
            @else
              <tr><td colspan="10" class="center">-- No Transaction Yet --</td></tr>
            @endif
            
          </tbody>
        </table>
        


    </div>


    

@endsection

@section('additionalScript')


<script>
  $(document).ready(function(){
    

  });
</script>
@endsection