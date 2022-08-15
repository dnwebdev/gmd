@extends('dashboard.company.wallet.base_layout')
@section('title', 'Gomodo Wallet')
@section('additionalStyle')
{{--  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
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
                <h5 class="breadcrumbs-title">Gomodo Wallet</h5>
                <ol class="breadcrumbs">
                    <li><a href="{{ Route('company.dashboard') }}">Dashboard</a></li>
                    <li class="active">Gomodo Wallet</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs end-->
@endsection

@section('tab_content')
<div class="section row">
    @if($wallet->first())
    <div class="card-address">
        @foreach($wallet as $row)
        <div class="card-body">
            <span class="balance">GXP {{$gxp/100}}</span>
            <span class="balance">XEM {{$xem/1000000}}</span>
        </div>
        <div class="card-bottom">
            <span class="address-title">Address</span>
            <span class="address-code">{{$row->address}}</span>
        </div>
        @endforeach
    </div>
    @else
    <div class="card-address">
        <button data-target="modal1" class="btn-large modal-trigger">Set Up Wallet</button>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
        <h4>Keep Private Key Safe</h4>
        <p>In order to use the Gomodo Wallet, you need to keep the private key safe. Never share your private key with anyone! Losing of your private key means lose access to your wallet.</p>
        </div>
        <div class="modal-footer">
        <form id="form_ajax" class="col s12" method="POST" action="{{ Route('company.wallet.store') }}">
        {{ csrf_field() }}
            <button class="btn blue waves-effect waves-light right" type="submit" name="action">Agree</button>   
        </form>
        </div>
    </div>
    @endif
</div>

<div class="section">
</div>
@endsection

@section('additionalScript')
<script>
    $(document).ready(function(){
        $('.modal').modal();
    });
</script>
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
  <script>
    $(document).ready(function(){
        form_ajax($('#form_ajax'),function(e){
            $('.modal').modal();
            if (e.status == 200) {
                toastr.remove()
                swal({
                title: "Success",
                text: e.message,
                type: "success",
                }).then(function() {
                location.reload()
                });
            } else {
                toastr.remove()
                swal({
                title: "{{trans('general.whoops')}}",
                text: e.message,
                type: "error",
                }).then(function() {
                });
            }
        });
    });
  </script>
  <script>
  function redirect(link) {
        window.location=link;
    }
  </script>
@endsection