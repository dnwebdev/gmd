@extends('dashboard.company.theme.website_base_layout')

@section('title', 'My Themes')

@section('additionalStyle')
    <link href="{{ asset('materialize/css/plugins/media-hover-effects.css') }}" type="text/css" rel="stylesheet" media="screen,projection">

    
@endsection



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
            <h5 class="breadcrumbs-title">Website</h5>
            <ol class="breadcrumbs">
                <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
                <li class="active">Website</li>
                
            </ol>
          </div>
        </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_theme')
  active
@endsection

@section('verticaltab_content')
<div class="section row">
    <div class="col s6">
      <h4 class="header2"><b>My Themes</b></h4>   
    </div>
    <!--
    <div class="col s6 right-align">
        <a href="#modal1" class="btn blue waves-effect modal-trigger">New Themes</a>        
    </div>
    -->
    
</div>
<div class="divider"></div>
<div class="section row">
    
    
  <div class="item product col l4 s12">
    <div class="card hoverable">
        <div class="card-image waves-effect waves-block waves-light">
          
          <a href="{{ Route('company.theme.edit',1) }}"><img src="{{ asset('uploads/products/img2.jpg') }}" alt="product-img">
                </a>
        </div>
        
        <ul class="card-action-buttons">
          <li>
            <a class="btn-floating waves-effect waves-light cyan">
              <i class="material-icons">add_shopping_cart</i>
            </a>
          </li>
          <li>
            <a class="btn-floating waves-effect waves-light red accent-2">
              <i class="material-icons">favorite</i>
            </a>
          </li>
          <li>
            <a class="btn-floating waves-effect waves-light teal">
              <i class="material-icons activator">info_outline</i>
            </a>
          </li>
        </ul>
        
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Green Lantern Themes</a>
              </p>
              
            </div>
          </div>
        </div>

        <div class="card-reveal">
          <span class="card-title grey-text text-darken-4">
            <i class="material-icons right">close</i> Green Lantern Themes</span>
          <p>Elegants end Clean</p>
        </div>
    </div>
  </div>


   


</div>

<div id="modal1" class="modal modal-fixed-footer">
  <div class="modal-content">
    
    <div class="item product col s12">
        <div class="card hoverable">
            <div class="card-image waves-effect waves-block waves-light">
                
                <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  green accent-4 disabled tooltipped" data-position="right" data-tooltip="Activate"><i class="material-icons">check</i></a>
                <a href="#"><img class="disabled" src="{{ asset('uploads/products/img2.jpg') }}" alt="product-img">
                </a>
            </div>
            <ul class="card-action-buttons">
              <li>
                <a class="btn-floating waves-effect waves-light cyan">
                  <i class="material-icons">add_shopping_cart</i>
                </a>
              </li>
              <li>
                <a class="btn-floating waves-effect waves-light red accent-2">
                  <i class="material-icons">favorite</i>
                </a>
              </li>
              <li>
                <a class="btn-floating waves-effect waves-light teal">
                  <i class="material-icons activator">info_outline</i>
                </a>
              </li>
            </ul>
            
            <div class="card-content">
              <div class="row">
                <div class="col s12">
                  <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Titanium Themes</a>
                  </p>
                  
                </div>
              </div>
            </div>
            
            <div class="card-reveal">
              <span class="card-title grey-text text-darken-4">
                <i class="material-icons right">close</i> Titanium Themes</span>
              <p>Elegants end Clean</p>
            </div>
        </div>
    </div>



    <div class="item product col l4 s12">
        <div class="card hoverable">
            <div class="card-image waves-effect waves-block waves-light">
                
                <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  green accent-4 disabled tooltipped" data-position="right" data-tooltip="Activate"><i class="material-icons">check</i></a>
                <a href="#"><img class="disabled" src="{{ asset('uploads/products/img2.jpg') }}" alt="product-img">
                </a>
            </div>
            <ul class="card-action-buttons">
              <li>
                <a class="btn-floating waves-effect waves-light cyan">
                  <i class="material-icons">add_shopping_cart</i>
                </a>
              </li>
              <li>
                <a class="btn-floating waves-effect waves-light red accent-2">
                  <i class="material-icons">favorite</i>
                </a>
              </li>
              <li>
                <a class="btn-floating waves-effect waves-light teal">
                  <i class="material-icons activator">info_outline</i>
                </a>
              </li>
            </ul>
            
            <div class="card-content">
              <div class="row">
                <div class="col s12">
                  <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Titanium Themes</a>
                  </p>
                  
                </div>
              </div>
            </div>
            
            <div class="card-reveal">
              <span class="card-title grey-text text-darken-4">
                <i class="material-icons right">close</i> Titanium Themes</span>
              <p>Elegants end Clean</p>
            </div>
        </div>
    </div>

    
  </div>
  <div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Close</a>
    <a href="#" class="waves-effect waves-green btn-flat modal-action modal-close">Select</a>
  </div>
</div>

                

@endsection

@section('additionalScript')

<script>
	$(document).ready(function(){
		//$('#tab-website').show().tabs();

    
	});
</script>
@endsection