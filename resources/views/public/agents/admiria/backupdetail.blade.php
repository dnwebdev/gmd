@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
<!-- Datepicker -->
<link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css" rel="stylesheet" media="screen,projection">

<!-- Touchspin -->
<link href="{{ asset('materialize/js/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />

<!-- Sweet Alert -->
<link href="{{ asset('themes/admiria/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('main_content')

<div class="divider"></div>
<div class="container">
    <div class="mt-4">
        <h1>{{ $product->product_name }}</h1>
    </div>
    <div class="">
        {{ $product->category->product_type->product_type_name }} - {{ $product->category->category_name}}
    </div>

    <div class="row mt-4">
        <div class="col-sm-12 col-lg-8">
            <div class="">
                <img id="img-main" class="img-responsive auto-height" src="{{ asset('uploads/products/'.$product->main_image) }}" />
            </div>

            <div class="row mt-4">
                @foreach($product->image as $row)
                    <div class="col-3 col-lg-2 img-thumb pointer">
                        <img class="form-control padding-0" src="{{ asset('uploads/products/thumbnail/'.$row->url) }}" actual-image="{{asset('uploads/products/'.$row->url) }}">
                    </div>
                @endforeach
            </div>

            <div class="row mt-4">
                <div class="col-sm-12">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">Description</a>
                        </li>
                        @if($product->itinerary)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Itinerary</a>
                        </li>
                        @endif
                        <!--@if($company->about)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">About</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">Reviews</a>
                        </li>
                        -->

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active p-3" id="tab1" role="tabpanel">
                            {!!html_entity_decode($product->long_description)!!}
                        </div>
                        @if($product->itinerary)
                        <div class="tab-pane p-3" id="tab2" role="tabpanel">
                            {!!html_entity_decode($product->itinerary)!!}
                        </div>
                        @endif
                        @if($company->about)
                        <div class="tab-pane p-3" id="tab3" role="tabpanel">
                            {!!html_entity_decode($company->about)!!}
                        </div>
                        @endif
                        <!--<div class="tab-pane p-3" id="tab3" role="tabpanel">
                            <p class="font-14 mb-0">
                                Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                farm-to-table readymade. Messenger bag gentrify pitchfork
                                tattooed craft beer, iphone skateboard locavore carles etsy
                                salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                mi whatever gluten-free, carles pitchfork biodiesel fixie etsy
                                retro mlkshk vice blog. Scenester cred you probably haven't
                                heard of them, vinyl craft beer blog stumptown. Pitchfork
                                sustainable tofu synth chambray yr.
                            </p>
                        </div>
                        -->

                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-4">
                <div class="text-center secondary-color p-v-20 container box-shadow">
                  <span style="font-size:0.7rem;">Starts from </span>
                    <div class="priceblock p-b-10 b-b">
                        {{ $product->currency }} {{ number_format($product->advertised_price,0) }}
                    </div>
                    <div>Duration: {{ $product->duration }} {{ $product->duration_type_text }}</div>
                </div>

                <div class="secondary-color mt-4 container p-v-20 box-shadow">
                    <form action="{{ Route('memoria.book') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="product" name="product" value="{{ $product->unique_code }}" />
                        <h4>Select Date(s)</h4>
                      @if(empty($product->schedule[1]))
                        <div class="form-group" style="display: none;">

                            <label>Select date range</label>
                            <select class="range_schedule_select form-control">
                              @foreach($product->schedule as $k=>$sch)
                                <?php
                                  $disable_day = '';
                                  if(!$sch->sun){
                                    $disable_day .= '0';
                                  }

                                  if(!$sch->mon){
                                    $disable_day .= '1';
                                  }

                                  if(!$sch->tue){
                                    $disable_day .= '2';
                                  }

                                  if(!$sch->wed){
                                    $disable_day .= '3';
                                  }

                                  if(!$sch->thu){
                                    $disable_day .= '4';
                                  }

                                  if(!$sch->fri){
                                    $disable_day .= '5';
                                  }

                                  if(!$sch->sat){
                                    $disable_day .= '6';
                                  }
                                ?>
                                <option value="$sch->id_schedule" start_date="{{ date('m/d/Y',strtotime($sch->start_date)) }}" end_date="{{ date('m/d/Y',strtotime($sch->end_date)) }}" disable_day="{{ $disable_day }}">{{ date('M d, Y',strtotime($sch->start_date)).' - '.date('M d, Y',strtotime($sch->end_date)) }}</option>
                              @endforeach

                            </select>
                        </div>
                        @else
                        <div class="form-group">

                            <label>Select date range</label>
                            <select class="range_schedule_select form-control">
                              @foreach($product->schedule as $k=>$sch)
                                <?php
                                  $disable_day = '';
                                  if(!$sch->sun){
                                    $disable_day .= '0';
                                  }

                                  if(!$sch->mon){
                                    $disable_day .= '1';
                                  }

                                  if(!$sch->tue){
                                    $disable_day .= '2';
                                  }

                                  if(!$sch->wed){
                                    $disable_day .= '3';
                                  }

                                  if(!$sch->thu){
                                    $disable_day .= '4';
                                  }

                                  if(!$sch->fri){
                                    $disable_day .= '5';
                                  }

                                  if(!$sch->sat){
                                    $disable_day .= '6';
                                  }
                                ?>
                                <option value="$sch->id_schedule" start_date="{{ date('m/d/Y',strtotime($sch->start_date)) }}" end_date="{{ date('m/d/Y',strtotime($sch->end_date)) }}" disable_day="{{ $disable_day }}">{{ date('M d, Y',strtotime($sch->start_date)).' - '.date('M d, Y',strtotime($sch->end_date)) }}</option>
                              @endforeach

                            </select>
                        </div>
                        @endif
                        <div class="warning" style="color:red;text-align:center;">Date not selected</div>
                        <div class="form-group">
                            <label>Select a date</label>
                            <div class="datepicker form-control" data-date="" id="datepicker" url="{{ Route('memoria.validate_schedule') }}"></div>
                            <input type="hidden" class="schedule" name="schedule" id="schedule" value="">
                            <div id="schedule_message"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 col-lg-4 align-middle" style="margin-top:0.5rem;margin-left:-1rem;text-align:center;">
                                <label for="adults" class="guest" style="font-size:1.1rem">Adults</label>
                            </div>
                            <div class="col-sm-12 col-lg-8">
                                <input type="text" name="adult" value="{{ $product->min_order }}" class="adt touchspin-input text-center form-control" align="center" autocomplete="off">
                            </div>
                        </div>

                        <?php

                          $chd = false;
                          $inf = false;

                          foreach($product->pricing as $price){
                            if($price->price_type == 2){
                              $chd = true;
                            }
                            else if($price->price_type == 3){
                              $inf = true;
                            }
                          }

                        ?>

                        @if($chd)
                            <div class="form-group row">
                                <div class="col-sm-12 col-lg-4">
                                    <label for="chd" class="guest" style="font-size:1.1rem">Childrens</label>
                                </div>
                                <div class="col-sm-12 col-lg-8">
                                    <input type="text" name="children" value="0" class="chd form-control touchspin-input text-center" align="center">
                                </div>

                            </div>
                        @endif

                        @if($inf)
                            <div class="form-group row">
                                <div class="col-sm-12 col-lg-4">
                                    <label for="chd" class="guest" style="font-size:1.1rem">Infants</label>
                                </div>
                                <div class="col-sm-12 col-lg-8">
                                    <input type="text" name="infant" value="0" class="inf form-control touchspin-input text-center" align="center">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                          <div class="col-sm-6">
                            <strong>Total Order </strong>
                          </div>
                          <div class="col-sm-6 text-right schedule_amount"></div>
                        </div>

                        <div class="mt-4">
                          <button class="btn select_product btn-success form-control" id="book-now">Book Now</button>
                        </div>

                    </form>
                </div>


        </div>


    </div>
    <!-- @if(count($similaritem) > 0)
    <div class="subheader mt-4">
        <div class="text-center">
            <h3>
               Similar Item</h3>
        </div>
      </div>

    <div class="row mt-4">
        @foreach($similaritem as $row)
        <div class="col-md-4 m-b-20">
            <div class="card products">
                <div class="p_image col-12 padding-0">
                    <a href="{{ Route('memoria.detail',[$row->unique_code.'-'.$row->permalink]) }}"><img class="img-responsive" src="{{ asset('uploads/products/thumbnail/'.$row->main_image) }}" alt="
                Card image cap"></a>
                    @if($row->mark)
                    <span class="mark">{{ $row->mark->mark}}</span>
                    @endif
                </div>
                <div class="card-block ">
                    <div class="row">
                        <div class="col-12 col-lg-7 product_name">
                            <a href="{{ Route('memoria.detail',[$row->unique_code.'-'.$row->permalink]) }}">{{ $row->product_name }}</a>
                        </div>
                        <div class="col-12 col-lg-5 text-right text-warning">
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                        </div>

                    </div>
                    <div class="row description">
                        <div class="col-12">
                        {{ $row->brief_description }}
                        </div>
                    </div>
                </div>
                <div class="card-block secondary-color">
                    <div class="text-center priceblock">{{ $row->currency }} {{ number_format($row->advertised_price,0) }}</div>
                    <div class="row">
                        <div class="col-6"><i class="icon-calender"></i>{{ $row->duration.' '.$row->duration_type_text }}</div>

                        @if($row->city)
                        <div class="col-6 text-right"><i class="dripicons-location"></i> {{ $row->city->city_name }}</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @endforeach


    </div>
@endif -->
</div>
<input type="hidden" id="min_notice" value="{{ $product->minimum_notice }}" />

@endsection

@section('additionalScript')
<!-- Datepicker -->
<script type="text/javascript" src="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<!-- Touchspin -->
<script src="{{ asset('materialize/js/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/public/product.js') }}" type="text/javascript"></script>

<!-- Sweet-Alert  -->
<script src="{{ asset('themes/admiria/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#book-now').click(function(){
      if($('#schedule').val() == ''){
        toastr.remove()
        swal({
          title: 'No date selected',
          type: "error",
          html:"Please select a date"
          });
      };
    })
  });
</script>
@endsection