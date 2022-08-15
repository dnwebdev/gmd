@extends('dashboard.company.theme.website_base_layout')

@section('title', 'Banner')

@section('additionalStyle')
    
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
                <li><a href="{{ Route('company.theme.index') }}">Website</a></li>
                <li class="active">Banner</li>
                
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

<div class="section row">
    <div class="col s6">
    <h4 class="header2"><b>Website Banner</b></h4>   
    </div>
    <div class="col s6 right-align">
        <a href="{{ Route('company.banner.create') }}" class="btn blue waves-effect modal-trigger">New Banner</a>        
    </div>
    
</div>
<div class="divider"></div>

<div class="section row">
    
    <table class="striped">
        <thead>
            <tr>
              <th data-field="status">Status</th>
              <th data-field="id">Image</th>
              <th data-field="link">Link</th>
              <th data-field="description">Description</th>
            </tr>
        </thead>
        <tbody>
        @if($banner->first())
          @foreach($banner as $row)
          <tr>
            <td class="center text-mute {{ $row->status ? 'green-text' : 'grey-text' }}">{{ $row->status_text }}</td>
            <td class="center">
              <a href="{{ Route('company.banner.edit',$row->id) }}">
                @if($row->image)
                <img {{ !$row->status ? "class=disabled" :'' }} src="{{ asset('uploads/banners/'.$row->image) }}" height="50" />
                @else
                No image
                @endif
              </a>
              <center>
              
              </center>
            </td>
            <td>
                @if($row->link)
                    <a target="_blank" href="{{ $row->link }}">{{ $row->link }}</a>
                @endif
            </td>
            <td>{{ $row->description }}</td>
          </tr>
          @endforeach
        @else
          <tr><td colspan="4" class="center">-- No Banner Yet --</td></tr>
        @endif
        
        </tbody>
    </table>
    
    <!--
    @if($banner->first())
      <div class="carousel">
        @foreach($banner as $row)
          <div class="carousel-item center">
            <img src="{{ asset('uploads/banners/'.$row->image) }}">
            <p class="truncate"><b>{{ $row->description }}</b></p>
            <a href="{{ Route('company.banner.edit',$row->id) }}">Detail</a>
          </div>
        @endforeach
      </div>
    @else
      <div class="center">-- No Banner Yet --</div>
    @endif
    -->

</div>
@endsection


@section('additionalScript')
  <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
  
  <script>
    $(document).ready(function(){
      /*
      $('.carousel').carousel({
        indicators:true,
        //fullWidth:true,
      });*/
    });
  </script>
@endsection

