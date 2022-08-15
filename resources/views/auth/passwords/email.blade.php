@extends('public.memoria.base_layout')

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-4 col-md-offset-4">
         <div class="panel panel-default">
            <div class="panel-body" style="padding-top: 90px;">
               <div>
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <p>You can reset your password here.</p>
                  <div class="panel-body">
                     @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                     @endif
                     @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                     <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                           <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                              <input id="email" name="email" value="{{ old('email') }}"" placeholder="email address" class="form-control"  type="email" required>
                           </div>
                        </div>
                        <div class="form-group">
                           <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                        </div>
                        <input type="hidden" class="hide" name="token" id="token" value=""> 
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>   
@endsection
