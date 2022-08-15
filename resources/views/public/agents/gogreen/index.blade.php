@extends('public.agents.gogreen.base_layout')

@section('main_content')
<!-- main content -->
<div class="col-sm-12 thumbnail">
    <div style=" position: absolute; top:calc(6vw + 6vh + 6vmin);"> <!-- border: 5px solid red; -->
         <!-- <span style="background-color:#0098e9; color: white; font-size: calc(2vw + 2vh + 1vmin)">&nbsp; With Dodyadventure Tours &nbsp;</span> -->

         <!-- <div id="div_12" > -->
                
                <span style="color: white; font-family: Verlag Book; font-size: calc(1vw + 1vh + 1vmin); ">&nbsp;<h3 style="margin-left: 110px; font-size: 1em">Find your special tour today</h3> <h1 style="margin-left: 110px; font-size: 2em"><b>With Dodyadventure Tours</b></h1> <h3 style="margin-left: 110px; font-size: 1em"> <a href="#" style="color: white;"> See More </a></h3>   &nbsp;</span>  
                
                
                                                    
        <!-- </div> -->
    </div>
    <div class="lang selectbottom"> <!-- bottom:  150px; border: 5px solid red; bottom: -270px--> 
        <div class="container">
            <div class="row" >
                <div class="col-lg-3" style="padding-bottom: 2px">
                    <select style="color: #033b32; width: 100%" >
                      <option selected>Product Type</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                </div>
                
                <div class="col-lg-3" style="padding-bottom: 2px">
                    <select style="color: #033b32; width: 100%">
                      <option selected>Destination</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                </div>
            
                <div class="col-lg-3" style="padding-bottom: 2px">
                    <select style="color: #033b32; width: 100%">
                      <option selected>Month</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                </div>

                <div class="col-lg-3" style="padding-bottom: 2px">
                    <button class="btn btn-info hr-item" style="background-color: #033b32; border-radius: 10px; font-family: Verlag Book;  font-size: 16px; line-height: 24px; text-align: center; width: 100%">Search Tour</button>
                </div>
            </div>
        </div>
    </div>

    <div id="banner" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          @foreach($banner as $key=>$row)
          <div class="carousel-item {{ $key==0 ? 'active' : '' }}">
            <img class="d-block w-100" src="{{ asset('uploads/banners/'.$row->image) }}" alt="First slide">
          </div>
          @endforeach
        </div>
        <a class="carousel-control-prev" href="#banner" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#banner" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
  </div>


    
</div>
<!-- end main content -->

<div class="divbottom"> <!-- padding-top: 230px | style="border: 5px solid red; "-->
    
    <div class="container">
      <div class="row">
          <div class="col" style="background-color:#cddddc; height: 48px; text-align: center; ">
              <img src="Asset/logo_new_product.png" height="42px" width="200px"/>
          </div>
      </div>
    </div>

    <div class="container">
        <div class="row">
            @foreach($newproduct as $row)
                <div id="div_1" name="div_1" class="col">
                    <div class="admin-box product-box" >
                      
                        <div class="col-sm-12 thumbnail text-center">
                            <img alt="" class="img-responsive" src="{{ asset('uploads/products/'.$row->main_image) }}">

                            <div class="caption text-left">
                                <span style="background-color:#0098e9; color: white">&nbsp; Open Trip &nbsp;</span>
                            </div>
                        </div>

                        <div class="product-content row" >
                          <div class="col-md-9">
                              <h6 style="margin-left: 15px"><b>{{ $row->product_name }}</b></h6>
                          </div>

                          <div class="col-md-3" >
                              <h3 class="product-price ta-right"><img src="Asset/Asset 14.png" alt="pic" style="height: 13px; width: 13px;"><img src="Asset/Asset 14.png" alt="pic" style="height: 13px; width: 13px;"><img src="Asset/Asset 14.png" alt="pic" style="height: 13px; width: 13px;"><img src="Asset/Asset 14.png" alt="pic" style="height: 13px; width: 13px;"></h3>
                          </div>

                          <div class="col-md-12">
                              <div >
                              
                                  <h6 align="left" style="margin-left: 15px;">&nbsp;
                                      <p>{{ $row->brief_description }}</p>
                                  </h6>
                              </div>
                          </div>

                        </div>
                      
                        <div style="background-color:#f6ce4e" class="application-title">
                          <br>
                          <h4 align="center"><b>{{ $row->currency }} {{ number_format($row->advertised_price,0) }}</b></h4>
                          
                          <table width="100%">
                              <tr>
                                  <td width="50%" align="center" >
                                      <table style="width: 80px">
                                          <tr>
                                              <td>
                                                  <div class="classImgCalender"></div>
                                              </td>
                                              <td>
                                                  <div><b>3 Days</b></div>
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                                  <td width="50%" align="center">
                                      <table style="width: 50px">
                                          <tr>
                                              <td>
                                                  <div class="classImgLocation"></div>
                                              </td>
                                              <td>
                                                  <div style="float: right; "><b>Riau</b></div>
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>
                          </table>
                          <br>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="container">
      <!-- <div class="row"> -->
          <div class="col" style="text-align: center; ">
              <a href="#" class="btn btn-info hr-item" style="background-color: #033b32">Lihat Selengkapnya</a>
          </div>
      <!-- </div> -->
    </div>

    <div class="container">
      <div class="row">
          <div class="col" style="background-color:#cddddc; height: 48px; text-align: center; ">
              <img src="Asset/logo_popular_destinations.png" height="42px" width="270px"/>
          </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 11.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>

          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 12.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>

          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 7.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>
      </div>
      <!-- <br> -->
      <div class="row">
          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 12.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>

          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 10.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>

          <div class="col-sm-4">
              <div class="admin-box product-box" >
              <div style="background-image: url('Asset/Asset 10.png'); height: 320px; width: 465px; display: table-cell; vertical-align: middle; text-align: center;">
                  <span style="color: white; font-family:Verlag Book;">&nbsp;<h3><b>The Little Africa</b> <br> Baluran</h3>  &nbsp;</span>                         
              </div>
              </div>
          </div>
      </div>
    </div>

    <div class="container">
      <!-- <div class="row"> -->
          <div class="col" style="text-align: center; ">
              <a href="#" class="btn btn-info hr-item" style="background-color: #033b32">Lihat Selengkapnya</a>
          </div>
      <!-- </div> -->
    </div>

</div>

@endsection

@section('additionalScript')
<script>
    $(document).ready(function(){
        //$('.carousel').carousel();
    });
</script>
@endsection