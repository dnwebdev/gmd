
@foreach($product as  $key=>$row)

<div class="item product col l3 s12 zoomIn animated">
  <div class="card hoverable">
    <div class="card-image waves-effect waves-block waves-light">
      @if($row->mark)
      <a href="#" class="btn-floating btn-large btn-price waves-effect waves-light pink accent-4">{{ $row->mark->mark }}</a>
      @endif
      <a href="{{ Route('company.product.edit',$row->id_product) }}" target="_blank">
        <img class="product_image" src="{{ asset('uploads/products/thumbnail/'.$row->main_image) }}" alt="item-img">
      </a>
    </div>
    <ul class="card-action-buttons">
      
      <li>
        <a class="btn-floating waves-effect waves-light teal">
          <i class="material-icons activator">info_outline</i>
        </a>
      </li>
    </ul>
    <div class="card-content">
      <div class="row">
        <div class="col s12">
          <p class="card-title grey-text text-darken-4 truncate"><a href="#" class="grey-text text-darken-4 product_name">{{ $row->product_name }}</a>
          </p>
          <p class="right-align">
            <a>{{ $row->currency }} {{ number_format($row->advertised_price,0) }}</a>
          </p>
          <input type="hidden" class="product_id" value="{{ $row->id_product }}"/>
          <input type="hidden" class="product_currency" value="{{ $row->currency }}"/>
          <input type="hidden" class="product_price" value="{{ number_format($row->advertised_price,0) }}"/>
          <input type="hidden" class="product_fee" value="{{ number_format($row->fee_amount,0) }}"/>

          <input type="hidden" class="product_discount" value="{{ number_format(($row->discount_amount_type == 1) ? $row->discount_amount/100 * $row->advertised_price : $row->discount_amount,0) }}"/>
          
          

        </div>
      </div>
    </div>
    <div class="card-reveal">
      <span class="card-title grey-text text-darken-4">
        <i class="material-icons right">close</i> {{ $row->product_name }}</span>
      <p>{{ $row->brief_description }}</p>
    </div>
  </div>

  <div class="product-detail hide col s9 animated zoomIn">
    <div class="row">
      <div class="right right-align col s1">
        <i class="close-detail-product material-icons pointer">keyboard_backspace</i>
      </div>

      <div class="input-field col s12 l6">
        <div class="row">
          <div class="col s4"><label><b>Select Period</b></label></div>
          <div class="col s8">

            <select class="range_schedule_select">
              @foreach($row->schedule as $k=>$sch)
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
                <option value="$sch->id_schedule" start_date="{{ date('m/d/Y',strtotime($sch->start_date)) }}" end_date="{{ date('m/d/Y',strtotime($sch->end_date)) }}"
                min_notice="{{ $row->minimum_notice }}"
                disable_day="{{ $disable_day }}">{{ date('M d, Y',strtotime($sch->start_date)).' - '.date('M d, Y',strtotime($sch->end_date)) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row">
          <div class="datepicker" data-date="" id="datepicker_{{$key}}"></div>
          <input type="hidden" class="schedule">        
        </div>
      </div>

      <div class="input-field col s12 l5">


        <div class="input-field col s12 row">
          <label for="adults" >Adults</label>
          &nbsp;
        </div>

        <div class="input-field col s12 row icon-adult">
          <input type="text" value="{{ $row->min_order ? $row->min_order : 1 }}" class="adt browser-default touchspin-input center" align="center" min_order="{{ $row->min_order }}">
        </div>

        <?php

          $chd = false;
          $inf = false;

          foreach($row->pricing as $price){
            if($price->price_type == 2){
              $chd = true;
            }
            else if($price->price_type == 3){
              $inf = true;
            }
          }


          
        ?>

        @if($chd)
        <div class="input-field col s12 row">
          <label for="chd">Childrens</label>
          &nbsp;
        </div>
        
        <div class="input-field col s12 row">
          <input type="text" value="0" class="chd browser-default touchspin-input center" align="center">
        </div>
        @endif

        @if($inf)
        <div class="input-field col s12 row">
          <label for="chd">Infants</label>
          &nbsp;
        </div>
        
        <div class="input-field col s12 row">
          <input type="text" value="0" class="inf browser-default touchspin-input center" align="center">
        </div>
        @endif

        <div class="input-field col s12 ">
          <div class="col s12 l6">
            <strong>Total Amount : </strong>
          </div>
          <div class="col s12 l6 right-align schedule_amount"></div>
        </div>  

        <div class="input-field col s12 row right-align">
          <button class="btn black select_product waves-effect waves-light">SELECT</button>
        </div>  
        
      </div>

      

    </div>

    
    
  </div>
  
  <div class="product-detail hide col s12 animated slideInRight">
    <h4 class="header2 ambe"><b>Information</b></h4>
    <div class="divider"></div>
    <div>
        {!!html_entity_decode($row->long_description)!!}
    </div>
  </div>
</div>
@endforeach
