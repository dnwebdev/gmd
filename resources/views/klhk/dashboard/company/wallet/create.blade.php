@extends('dashboard.company.theme.website_base_layout')

@section('title', 'Create New Bank Account')

@section('additionalStyle')
    
@endsection


@section('tab_breadcrumb')
<!--breadcrumbs start-->
<div id="breadcrumbs-wrapper">
    <!-- Search for small screen -->
    <!--
    <div class="header-search-wrapper grey hide-on-large-only">
        <i class="mdi-action-search active"></i>
        <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
    </div>
    -->

    <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Bank Account</h5>
            <ol class="breadcrumbs">
                <li><a href="{{ Route('company.bank.index') }}">List Bank Account</a></li>
                <li class="active">Create New Bank Account</li>
                
            </ol>
          </div>
        </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_bank')
  active
@endsection

@section('verticaltab_content')
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        <h4 class="header2"><b>New Bank Account</b></h4>
        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.bank.store') }}">
            {{ csrf_field() }}
            <div class="row">
              <div class="input-field col s12 l6">
                <input name="bank" type="text">
                <label for="bank">Bank Name</label>
              </div>

            </div>

            <div class="row">
              <div class="input-field col s12 l6">
                <input name="bank_account_name" type="text">
                <label for="bank_account_name">Bank Account Name</label>
              </div>

              <div class="input-field col s12 l6">
                <input name="bank_account_number" type="text">
                <label for="bank_account_number">Bank Account Number</label>
              </div>

            </div>

            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  <option value="1">Active</option>
                  <option value="0">InActive</option>
                </select>
                <label for="status">Bank Status</label>
              </div>
            </div>
            
            <div class="row clear">&nbsp;</div>
            <div class="">
              <a href="{{ Route('company.bank.index') }}" class="btn pink waves-effect waves-light" type="submit" name="action"><i class="material-icons left">arrow_back</i> Back</a>

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