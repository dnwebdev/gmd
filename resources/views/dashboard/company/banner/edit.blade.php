@extends('dashboard.company.theme.website_base_layout')

@section('title', 'Edit Banner')

@section('additionalStyle')
    <!--dropify-->
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
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
        <h5 class="breadcrumbs-title">Edit Banner</h5>
        <ol class="breadcrumbs amber-text">
            <li><a href="{{ Route('company.banner.index') }}">Banner</a></li>
            <li class="active">Edit Banner</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection



@section('indicator_banner')
  active
@endsection

@section('verticaltab_content')
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        <h4 class="header2"><b>Banner Info</b></h4>
        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.banner.store') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="" />
            <div class="row">
              <div class="input-field col s12 l12">
                <h6 class="">Image</h6>
                <input type="file" name="image" id="input-file-now" class="dropify" data-default-file="{{ $banner->image ? asset('uploads/banners/'.$banner->image) : ''}}" data-disable-remove="true"  />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 l6">
                <input name="link" type="text" value="{{ $banner->link }}">
                <label for="link">Link</label>
              </div>


            </div>

            <div class="row">
              <div class="input-field col s12 l12">
                <textarea name="description" type="text" length="300" class="materialize-textarea">{{ $banner->description }}</textarea>
                <label for="description">Description</label>
              </div>
            </div>


            
            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  @foreach($banner->list_status() as $key=>$row)
                    <option value="{{ $key }}" {{ ($banner->status==$key) ? 'selected' :'' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="status">Banner Status</label>
              </div>
            </div>


            <div class="row clear">&nbsp;</div>
            <div class="">
              <a href="{{ Route('company.banner.index') }}" class="btn pink waves-effect waves-light" type="submit" name="action"><i class="material-icons left">arrow_back</i> Back</a>

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
  <!-- dropify -->
  <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>

  <script>
    $(document).ready(function(){
      form_ajax($('#form_ajax'),function(e){
        Materialize.toast(e.message, 4000);
      });

      $('.dropify').dropify();

    });
  </script>
@endsection

@section('additionalScript')
