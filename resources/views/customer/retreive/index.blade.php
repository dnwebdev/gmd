@extends('customer.master.index')

@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                @if(request()->has('ref') && request('ref') ==='directory')
                    @php
                        if (request()->isSecure()){
                            $prefix = 'https://';
                        }else{
                            $prefix = 'http://';
                        }
                      $url = $prefix.env('APP_URL');
                        if (request()->has('ref-url')){
                            $ex = explode($prefix.env('APP_URL'),request('ref-url'));
                            if (count($ex)===2){
                                $url .= $ex[1];
                            }
                        }
                    @endphp
                    <li><a href="{{$url}}">Directory</a></li>
                @endif
                <li><a href="{{route('memoria.home')}}">{!! trans('customer.home.home') !!}</a></li>
                <li><a>{!! trans('retrieve_booking.retrieve_booking') !!}</a></li>
            </ul>
        </div>
        <div id="retrieve" class="container pb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body card-retrieve">
                            <div class="row">
                                <div class="col-12">
                                    <h3>{!! trans('retrieve_booking.retrieve_booking') !!}</h3>
                                    <p>
                                        {!! trans('customer.retrieve.keep_track') !!}
                                    </p>
                                </div>

                                <div class="col-12 mt-5">
                                    {!! Form::open(['method'=>'GET','url'=>route('memoria.retrieve.data')]) !!}
                                    <div class="row">
                                        <div class="col">
                                            <div class="md-form __parent_form">
                                                <input type="text" id="no_invoice" class="form-control"
                                                       name="no_invoice" placeholder="{!! trans('retrieve_booking.invoice_no') !!}">
                                                {{--<label for="no_invoice">No Invoice *</label>--}}
                                            </div>
                                            @if($errors->has('no_invoice'))
                                                <div class="">
                                                    <label class="text-danger small" for="">{{$errors->first('no_invoice')}}</label>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="mt-3">
                                                <button
{{--                                                        type="button" --}}
                                                        id="btn-retrieve"
                                                        class="btn btn-primary btn-block">
                                                    {!! trans('retrieve_booking.retrieve_my_booking') !!}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
      $(document).on('click', '#btn-retrieve', function () {
        let form = $(this).closest('form').serialize();
        let id = $('#no_invoice').val();
        $.ajax({
          url: "{{}}"
        })
      });
    </script>

@stop
