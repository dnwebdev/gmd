@extends('dashboard.company.theme.website_base_layout')

@section('title', 'Mail Server Configuration')

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
            <h5 class="breadcrumbs-title">Mail Server Configuration</h5>
            <ol class="breadcrumbs">
                <li><a href="{{ Route('company.theme.index') }}">Website</a></li>
                <li class="active">Mail Server</li>
                
            </ol>
          </div>
        </div>
  </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('indicator_mail')
  active
@endsection

@section('verticaltab_content')
<!--Form Advance-->          

<div class="row">
  <div class="row">
    <div class="col s12 m12 l12">
      <div class="card-panel">
        
        <div class="row">
          <div class="col s8">
            <h4 class="header2"><b>Mail Server Configuration</b></h4>
          </div>
        </div>

        <div class="divider"></div>
        <div class="row">
          <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.mail_server.update') }}">
            {{ csrf_field() }}
            
            
            <div class="row">
              <div class="input-field col s12 l4">
                <input name="smtp_host" type="text" value="{{ $mail ? $mail->smtp_host : '' }}">
                <label for="smtp_host">SMTP Host</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="smtp_port" type="text" value="{{ $mail ? $mail->smtp_port : '' }}">
                <label for="body_bgcolor">SMTP Port</label>
              </div>


            </div>

            <div class="row">
              <div class="input-field col s12 l4">
                <input name="username" type="text" value="{{ $mail ? $mail->username : '' }}">
                <label for="username">Username</label>
              </div>

              <div class="input-field col s12 l4">
                <input name="password" type="password" value="{{ $mail ? $mail->password : '' }}">
                <label for="password">Password</label>
              </div>

            </div>
            
            
            <div class="row">
              <div class="input-field col s12 l4">
                <select name="status">
                  @foreach($list_status as $key=>$row)
                    <option value="{{ $key }}" {{ ($mail && $mail->status==$key) ? 'selected' :'' }}>{{ $row }}</option>
                  @endforeach
                </select>
                <label for="status">Mail Server Status</label>
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

<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script>
    $(document).ready(function(){
        
        form_ajax($('#form_ajax'),function(e){
          Materialize.toast(e.message, 4000);
        });
    });

</script>
@endsection