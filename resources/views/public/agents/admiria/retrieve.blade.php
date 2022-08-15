@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
    <!-- Datepicker -->
    <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css"
          rel="stylesheet" media="screen,projection">

@endsection

@section('main_content')
    <section id="content">
        <div id="maincontent">
            <div class="divider"></div>
            <div class="container">
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{Session::get('error')}}
                    </div>
                @endif
                <form method="GET" action="{{ Route('memoria.retrieve_booking') }}">
                    <div class="row">
                        <div class="form-group col-12">
                            <h3>{{ trans('retrieve_booking.retrieve_booking') }}</h3>
                            <p>{{ trans('retrieve_booking.keep_track') }}<br>
                                {{ trans('retrieve_booking.you_can') }}</p>
                        </div>
                        <div class="form-group col-12">
                            <label for="invoice">{{ trans('retrieve_booking.invoice_no') }}</label>
                            <input type="text" name="invoice" class="form-control"/>
                        </div>
                        <div class="form-group col-12">
                            @if($company->font_color_company)
                                <button id="retrievenow" type="submit" class="btn form-control btn-lg"
                                        style="color: #{{$company->font_color_company}} !important;" data-toggle="modal"
                                        data-target=".bs-example-modal-sm">{{ trans('retrieve_booking.retrieve_my_booking') }}</button>
                            @else
                                <button id="retrievenow" type="submit" class="btn form-control btn-lg"
                                        style="color: white !important;" data-toggle="modal"
                                        data-target=".bs-example-modal-sm">{{ trans('retrieve_booking.retrieve_my_booking') }}</button>
                            @endif
                        </div>
                    </div>
            </div>
            </form>
        </div>
        </div>
    </section>
@endsection
@section('additionalScript')
@endsection
