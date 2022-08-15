@extends('dashboard.company.profile.integration_base_layout')

@section('title', 'Config My Theme')

@section('additionalStyle')
  <!-- Colorpicker css -->
  <link href="{{ asset('materialize/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
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
        <h5 class="breadcrumbs-title">Theme Configuration</h5>
        <ol class="breadcrumbs amber-text">
            <li><a href="{{ Route('company.theme.index') }}">Website</a></li>
            <li class="active">Theme Configuration</li>
            
        </ol>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection



@section('indicator_website')
  active
@endsection


@section('tab_content')
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        
        <div class="row">
          <div class="col s8">
            <h4 class="header2"><b>Theme Configuration</b></h4>
          </div>
          <div class="col s4 right-align">
            <h4 class="header2"><b>{{ $theme->theme ? $theme->theme->theme_name : $theme->theme_name }}</b></h4>
          </div>
        </div>
        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.theme.update',$theme->id_theme) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            
            <div class="row"></div>         
            <h4 class="header2 amber-text"><b>Navigation Bar</b></h4>
            <div class="divider"></div>

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="navbar_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->navbar_bgcolor }}">
                <label for="navbar_bgcolor">Navbar Background Color</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="navbar_textcolor" type="text" class="colorpicker-large" value="{{ $theme->navbar_textcolor }}">
                <label for="navbar_textcolor">Navbar Text Color</label>
              </div>

            </div>

            <div class="row"></div>         
            <h4 class="header2 amber-text"><b>Background Color</b></h4>
            

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="header_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->header_bgcolor }}">
                <label for="header_bgcolor">Header Background Color</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="body_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->body_bgcolor }}">
                <label for="body_bgcolor">Body Background Color</label>
              </div>

            </div>
            <div class="row">
              <div class="input-field col s12 l4">
                <input name="body_secondary_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->body_secondary_bgcolor }}">
                <label for="header_bgcolor">Body Background Secondary Color</label>
              </div>

              

            </div>

            
            <div class="row"></div>         
            <h4 class="header2 amber-text"><b>Button Color</b></h4>
            <div class="divider"></div>

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="button_primary_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->button_primary_bgcolor }}">
                <label for="button_primary_bgcolor">Button Primary Color</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="button_primary_textcolor" type="text" class="colorpicker-large" value="{{ $theme->button_primary_textcolor}}">
                <label for="button_primary_textcolor">Text Button Primary Color</label>
              </div>

             
            </div>

            <div class="row">

              <div class="input-field col s12 l4">
                <input name="button_secondary_bgcolor" type="text" class="colorpicker-large" value="{{ $theme->button_secondary_bgcolor }}">
                <label for="button_secondary_bgcolor">Button Secondary Color</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="button_secondary_textcolor" type="text" class="colorpicker-large" value="{{ $theme->button_secondary_textcolor }}">
                <label for="button_secondary_textcolor">Text Button Secondary Color</label>
              </div>
             
            </div>



            <!--
            <div class="row"></div>         
            <h4 class="header2 amber-text"><b>System Email Confguration</b></h4>
            <div class="divider"></div>

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="smtp_host" type="text" value="{{ $theme->button_secondary_bgcolor }}>
                <label for="smtp_host">SMTP Host</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="body_bgcolor" type="text">
                <label for="body_bgcolor">SMTP Port</label>
              </div>


            </div>

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="email_username" type="text">
                <label for="email_username">Username</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="email_password" type="password">
                <label for="email_password">Password</label>
              </div>

            </div>
            -->
            
            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  @foreach($list_status as $key=>$row)
                    <option value="{{ $key }}" {{ ($theme->status==$key) ? 'selected' :'' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="status">Theme Status</label>
              </div>
            </div>

            <div class="row clear">&nbsp;</div>

            <div class="row">
              <a href="{{ Route('company.theme.index') }}" class="btn pink waves-effect waves-light col l2 s6" type="submit" name="action"><i class="left material-icons">arrow_back</i> Back</a>

              <button class="btn blue waves-effect waves-light right col l2 s6" type="submit" name="action">Submit
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
<script src="{{ asset('materialize/js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.colorpicker-large').colorpicker({
            customClass: 'colorpicker-2x',
            format:'hex',
            sliders: {
                saturation: {
                    maxLeft: 200,
                    maxTop: 200
                },
                hue: {
                    maxTop: 200
                },
                alpha: {
                    maxTop: 200
                }
            }
        });

        form_ajax($('#form_ajax'),function(e){
          Materialize.toast(e.message, 4000);
        });
    });

</script>
@endsection