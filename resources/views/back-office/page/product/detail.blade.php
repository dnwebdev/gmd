@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Product</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::model($product) !!}
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <h3>Product Information</h3>--}}
{{--                </div>--}}
{{--                <div class="col-12">--}}
{{--                    --}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row ">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('product_name') !!}
                                {!! Form::text('product_name',null,['class'=>'form-control','readonly']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('country') !!}
                                {!! Form::select('country',$country,$product->city->state->id_country,['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('state') !!}
                                {!! Form::select('state',$state,$product->city->id_state,['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('city') !!}
                                {!! Form::select('city',$city,$product->id_city,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">

                            <div class="form-group">
                                {!! Form::label('brief_description') !!}
                                {!! Form::textarea('brief_description',null,['class'=>'form-control','readonly']) !!}
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script>
      $('select').select2();

      $(document).on('change', 'select[name=state]', function(){
        let id_state = $(this).val();
        let selectCity = $('select[name=city]');
        $.ajax({
          url: "{{route('admin:product.change-state')}}",
          data: { id: id_state },
          dataType: 'json',
          success: function(data){
            selectCity.select2('destroy');
            selectCity.find('option').remove();
            $.each(data.cities, function(i, e){
              selectCity.append('<option value=' + i + '>' + e + '</option>')
            })
            selectCity.select2()
          }
        })

      })
      $(document).on('change', 'select[name=country]', function(){
        let id_state = $(this).val();
        let selectCity = $('select[name=city]');
        let selectState = $('select[name=state]');
        $.ajax({
          url: "{{route('admin:product.change-country')}}",
          data: { id: id_state },
          dataType: 'json',
          success: function(data){
            selectState.select2('destroy');
            selectState.find('option').remove();
            $.each(data.states, function(i, e){
              selectState.append('<option value=' + i + '>' + e + '</option>')
            })
            selectState.select2();
            selectState.trigger('change');
          }
        })

      })
    </script>
@stop
