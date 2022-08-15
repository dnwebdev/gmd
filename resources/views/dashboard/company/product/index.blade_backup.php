@extends('dashboard.company.base_layout')

@section('title', 'Inventory')

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
        <h5 class="breadcrumbs-title">Inventory</h5>
        <ol class="breadcrumbs">
            <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
            <li class="active">Inventory</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection


@section('content')

	<div class="row">

		<div class="col s12">
            <ul class="tabs tab-demo-active blue-grey lighten-4" id="tab_menu">
              <li class="tab col s3"><a class="black-text waves-effect waves-light" href="#tab1">Products</a>
              </li>
              <li class="tab col s3"><a class="black-text waves-effect waves-light" target="_self" href="{{ Route('category_index') }}">Categories</a>
              </li>
              <li class="tab col s3"><a class="black-text waves-effect waves-light" href="#tab3">Taxes & Fees</a>
              </li>
              <li class="tab col s3"><a class="black-text waves-effect waves-light" href="#tab4">Voucher</a>
              </li>
              <div class="indicator" style="left: 0px; right: 868px;"></div>
            </ul>
        </div>
        <div class="col s12">
            <div id="tab1" class="col s12  white darken-4">
            	
            	<div class="section row">
            		<div class="col s6">
            		<h4 class="header2"><b>Product List</b></h4>	
            		</div>
            		<div class="col s6 right-align">
            			<a href="{{ Route('product_select_type') }}" class="btn blue waves-effect">New Product</a>		
            		</div>
            		
            	</div>
            	<div class="divider"></div>
            	<div class="section row">
            		

            		
	              	
	              	<div class="product col s3">
	                    <div class="card">
	                        <div class="card-image waves-effect waves-block waves-light">
	                            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  pink accent-2">$189</a>
	                            

	                            <a href="#"><img src="{{ asset('materialize/images/img2.jpg') }}" alt="product-img">
	                            </a>
	                        </div>
	                        <ul class="card-action-buttons">
	                            <li><a class="btn-floating waves-effect waves-light green accent-4"><i class="mdi-av-repeat"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light red accent-2"><i class="mdi-action-favorite"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light light-blue"><i class="mdi-action-info activator"></i></a>
	                            </li>
	                        </ul>
	                        <div class="card-content">

	                            <div class="row">
	                                <div class="col s8">
	                                    <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Gili Trawangan</a>
	                                    </p>
	                                </div>
	                                <div class="col s4 no-padding">
	                                    <a href=""></a><img src="{{ asset('materialize/images/amazon.jpg') }}" alt="amazon" class="responsive-img">
	                                    </a>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="card-reveal">
	                            <span class="card-title grey-text text-darken-4"><i class="mdi-navigation-close right"></i> Gili Trawangan</span>
	                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
	                        </div>
	                    </div>
	                </div>

	                
                    <div class="product col s3">
	                    <div class="card">
	                        <div class="card-image waves-effect waves-block waves-light">
	                            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  pink accent-2">$189</a>
	                            

	                            <a href="#"><img src="{{ asset('materialize/images/img2.jpg') }}" alt="product-img">
	                            </a>
	                        </div>
	                        <ul class="card-action-buttons">
	                            <li><a class="btn-floating waves-effect waves-light green accent-4"><i class="mdi-av-repeat"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light red accent-2"><i class="mdi-action-favorite"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light light-blue"><i class="mdi-action-info activator"></i></a>
	                            </li>
	                        </ul>
	                        <div class="card-content">

	                            <div class="row">
	                                <div class="col s8">
	                                    <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Maratua Island</a>
	                                    </p>
	                                </div>
	                                <div class="col s4 no-padding">
	                                    <a href=""></a><img src="{{ asset('materialize/images/amazon.jpg') }}" alt="amazon" class="responsive-img">
	                                    </a>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="card-reveal">
	                            <span class="card-title grey-text text-darken-4"><i class="mdi-navigation-close right"></i> Maratua Island</span>
	                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
	                        </div>
	                    </div>
	                </div>

	                <div class="product col s3">
	                    <div class="card">
	                        <div class="card-image waves-effect waves-block waves-light">
	                            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  pink accent-2">$189</a>
	                            

	                            <a href="#"><img src="{{ asset('materialize/images/img2.jpg') }}" alt="product-img">
	                            </a>
	                        </div>
	                        <ul class="card-action-buttons">
	                            <li><a class="btn-floating waves-effect waves-light green accent-4"><i class="mdi-av-repeat"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light red accent-2"><i class="mdi-action-favorite"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light light-blue"><i class="mdi-action-info activator"></i></a>
	                            </li>
	                        </ul>
	                        <div class="card-content">

	                            <div class="row">
	                                <div class="col s8">
	                                    <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Scuba Diving</a>
	                                    </p>
	                                </div>
	                                <div class="col s4 no-padding">
	                                    <a href=""></a><img src="{{ asset('materialize/images/amazon.jpg') }}" alt="amazon" class="responsive-img">
	                                    </a>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="card-reveal">
	                            <span class="card-title grey-text text-darken-4"><i class="mdi-navigation-close right"></i> Scuba Diving</span>
	                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
	                        </div>
	                    </div>
	                </div>

	                <div class="product col s3">
	                    <div class="card">
	                        <div class="card-image waves-effect waves-block waves-light">
	                            <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light  pink accent-2">$189</a>
	                            

	                            <a href="#"><img src="{{ asset('materialize/images/img2.jpg') }}" alt="product-img">
	                            </a>
	                        </div>
	                        <ul class="card-action-buttons">
	                            <li><a class="btn-floating waves-effect waves-light green accent-4"><i class="mdi-av-repeat"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light red accent-2"><i class="mdi-action-favorite"></i></a>
	                            </li>
	                            <li><a class="btn-floating waves-effect waves-light light-blue"><i class="mdi-action-info activator"></i></a>
	                            </li>
	                        </ul>
	                        <div class="card-content">

	                            <div class="row">
	                                <div class="col s8">
	                                    <p class="card-title grey-text text-darken-4"><a href="#" class="grey-text text-darken-4">Jatim Park 2</a>
	                                    </p>
	                                </div>
	                                <div class="col s4 no-padding">
	                                    <a href=""></a><img src="{{ asset('materialize/images/amazon.jpg') }}" alt="amazon" class="responsive-img">
	                                    </a>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="card-reveal">
	                            <span class="card-title grey-text text-darken-4"><i class="mdi-navigation-close right"></i>Jatim Park 2</span>
	                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
	                        </div>
	                    </div>
	                </div>
	                


	            </div>


            </div>

            
        </div>


	    
  	</div>


@endsection

@section('additionalScript')
<script>
	$(document).ready(function(){
		//$('#tab_menu li a').unbind('click');
	});
</script>
@endsection