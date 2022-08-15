@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Popular State</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            {!! Form::token() !!}
            <div class="row">
                @forelse($top_cities as $top_city)
                    <div class="col-md-4 mb-5">
                        <div class="card">
                            <img class="card-img-top"
                                 @if($top_city->state_image)
                                 src="{{asset($top_city->state_image)}}"
                                 @else
                                 src="{{asset('img/image_placeholder_city.png')}}"
                                 @endif
                                 alt="image"
                                 style="width:100%">
                            <div class="card-body">
                                <h6 class="card-title">{{$top_city->state_name}}</h6>
                                <button class="btn btn-primary btn-change-image" data-id="{{$top_city->id_state}}">Change
                                    Image
                                </button>
                                <input type="file" name="city_image" style="visibility:hidden">
                            </div>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    {!! $top_cities->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('styles')
    <style>
        ul.pagination{
            justify-content: center;
        }
        ul.pagination>li{
            padding:8px 12px;
            margin: 0px 2px;
            background-color: #0a6aa1;
            border-radius: 5px;
            text-align: center;
        }
        ul.pagination>li>a{
            color:#fff
        }
        ul.pagination>li.disabled,ul.pagination>li.active{
            background-color: #fff;
        }

    </style>
@stop
@section('scripts')
    <script>
      $(document).on('click', '.btn-change-image', function(){
        $(this).parent().find('input[name=city_image]').trigger('click');
      });

      $(document).on('change', 'input[name=city_image]', function(e){
        let t = $(this);
        let id_city = t.parent().find('.btn-change-image').data('id');
        if (e.target.files.length > 0) {
          let fD = new FormData();
          fD.append('_token', $('input[name=_token]').val());
          fD.append('id_city', id_city);
          fD.append('city_image', e.target.files[0]);
          $.ajax({
            url: '',
            data: fD,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function(data){

              toastr.success(data.message, 'Congrats');
              setTimeout(function(){
                location.reload();
              }, 1000);
              loadingFinish();
            },
            error: function(e){
              loadingFinish();
              if (e.status === 422) {
                let dt = e.responseJSON;
                let errors = dt.errors;
                jQuery.each(errors, function(i, e){
                  $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                  $(document).find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                })
              }
              toastr.error(e.responseJSON.message, '{{__('general.whoops')}}');
            }
          });
        }
      });
    </script>

@stop
