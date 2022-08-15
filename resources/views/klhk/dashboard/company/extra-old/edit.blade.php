@extends('dashboard.company.order.order_base_layout')

@section('title', 'Edit Extra Item')

@section('additionalStyle')
  <!--dropify-->
  <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
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
        <h5 class="breadcrumbs-title">Edit New Extra Item</h5>
        <ol class="breadcrumbs amber-text">
            <li><a href="{{ Route('company.extra.index') }}">Extra</a></li>
            <li class="active">Edit New Extra</li>
            
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
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        <h4 class="header2"><b>Extra Item Information</b></h4>
        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.extra.update',$extra->id_extra) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="row">
              <div class="input-field col s6">
                <input name="extra_name" type="text" value={{ $extra->extra_name }}>
                <label for="extra_name">Extra Name</label>
              </div>

              <div class="input-field col s12 l6 ">
                <select name="extra_price_type">
                  <option value=''>-- Select Price Type --</option>
                  @foreach($extra->list_price_type() as $key=>$row)
                    <option value="{{ $key }}" {{ $extra->extra_price_type == $key? 'selected' : '' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="extra_price_type">Extra Price Type</label>
              </div>

            </div>

            <div class="row">
              <div class="input-field col s12">
                <textarea name="description" length="300" class="materialize-textarea" >{{ $extra->description }}</textarea>
                <label for="mark">Description<label>
              </div>

            </div>

            <div class="row">
              <div class="input-field col s12 l4">
                <select name="currency">
                  <option value=''>-- Select Currency --</option>
                  @foreach($extra->list_currency() as $key=>$row)
                    <option value="{{ $key }}" {{ $extra->currency == $key ? 'selected' : '' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="currency">Currency</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="amount" type="text" value="{{ $extra->amount }}">
                <label for="amount">Amount</label>
              </div>

              
            </div>
            <div class="input-field col s12 l4">&nbsp;</div>
            <div class="input-field col s12 l4">
                <input type="file" name="image" id="input-file-now" class="dropify" data-default-file="{{ $extra->image ? asset('uploads/extras/'.$extra->image) : '' }}" data-disable-upload="false" />
                <label><h5><b>Image</b></h5></label>
            </div>

            
            <div class="row clear">&nbsp;</div>
            <div class="">
              <a href="{{ Route('company.extra.index') }}" class="btn pink waves-effect waves-light" type="submit" name="action"><i class="material-icons left">arrow_back</i> Back</a>

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
  <!-- dropify -->
  <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
  <script>
    $(document).ready(function(){
      
      $('.dropify').dropify()
        .on('dropify.beforeClear', function(event, element){
            
            $.ajax({
                  url: "{{ Route('company.extra.delete_image',$extra->id_extra) }}",
                  type: "POST",
                  dataType :'json',
                  data:{_token:$('input[name="_token"]').eq(0).val()
                          ,image:element.filename
                          ,_method:'DELETE'
                        },
            
                  beforeSend: function() {
                

                },
                success: function (response) {
                    Materialize.toast(response.message,2000) 
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    
                }


             });

        }).on('dropify.afterClear', function(event, element){
            
            
        });

      form_ajax($('#form_ajax'),function(e){
        Materialize.toast(e.message, 4000);
      });


      

    });
  </script>
@endsection