@extends('dashboard.company.product.product_base_layout')

@section('title', 'Create New Tax')

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
        <h5 class="breadcrumbs-title">Create New Tax</h5>
        <ol class="breadcrumbs amber-text">
            <li><a href="{{ Route('company.tax.index') }}">Tax</a></li>
            <li class="active">Create New Tax</li>
            
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
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        <h4 class="header2 ambe"><b>New Tax</b></h4>
        <div class="divider"></div>

        
        <div class="row">
          
          <form class="col s12" id="form_ajax" method="POST" action="{{ Route('company.tax.store') }}">
            {{ csrf_field() }}
            <div class="row">
              <div class="input-field col s12 l4">
                <input name="tax_name" type="text" value="{{ old('tax_name') }}" >
                <label for="tax_name">Tax Name</label>
                {!! $errors->first('tax_name','<span class="help-block red-text">:message</span>') !!}
              </div>

              <div class="input-field col s12 l4">
                <select name="tax_amount_type">
                  <option value="1">Percentage</option>
                  <option value="0">Fix Amount</option>
                </select>
                <label for="tax_amount">Tax Amount Type</label>

                
              </div>


              <div class="input-field col s12 l4">
                <input name="tax_amount" type="text" class="right-align" value="{{ old('tax_amount') }}">
                <label for="tax_amount">Tax Amount</label>
                {!! $errors->first('tax_amount','<span class="help-block danger red-text">:message</span>') !!}
                <i data-html="true" data-tooltip="Example : Fill <b>10</b> For adding 10% the price value,<br> or <b>-2</b> For reducing 2% of the price value" class="right tooltipped mdi-action-info icon blue-text text-darken-3 right-align"></i>
              </div>

            </div>


            
            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  <option value="1">Active</option>
                  <option value="0">Not Active</option>
                </select>
                <label for="status">Tax Status</label>
              </div>
            </div>


            <div class="row clear">&nbsp;</div>
            <div class="">
              <a href="{{ Route('company.tax.index') }}" class="btn pink waves-effect waves-light" type="submit" name="action"><i class="material-icons left">arrow_back</i> Back</a>

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
