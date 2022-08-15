@extends('new-backoffice.header')
@section('addtionalStyle')
  <link rel="stylesheet" href="{{asset('select2/select2.min.css')}}">
  <style>
    .select2-search--dropdown:after{
      display: none;
    }
    textarea.form-control{
      height: 14rem!important;
      border: 1px solid #dddddd!important;
    }
  </style>
@endsection
@section('content')
  <h3 class="mb-3 mt-3">Edit Produk</h3>
  <div class="card" id="edit-product">
    <div class="card-body">
      <form class="row" method="post" action="{{ url()->current() }}">
        {{ csrf_field() }}
        <div class="col-6">
          <div class="form-group form-group-float">
            <label for="product_name" class="form-group-float-label animate is-visible">Nama Produk</label>
            <input type="text" class="form-control" name="product_name" placeholder="Nama Produk" value="{{ old('product_name', $product->product_name) }}" disabled>
            @if($errors->has('product_name'))
              <span class="form-text text-danger">{{ $errors->first('product_name') }}</span>
            @endif
          </div>
          <div class="mb-4">
            <label for="">Provinsi</label>
            <select data-placeholder="Pilih Provinsi" class=" js-example-basic-single js-states form-control select-2" name="state">
              @foreach (\App\Models\State::all() as $state)
                <option value="{{ $state->id_state }}" {{ old('state', $product->city->id_state) == $state->id_state ? 'selected' : '' }}>{{ $state->state_name }}</option>
              @endforeach
            </select>
            @if($errors->has('state'))
              <span class="form-text text-danger">{{ $errors->first('state') }}</span>
            @endif
          </div>

          <div class="mb-4">
            <label for="">Kota</label>
            <select data-placeholder="Pilih Kota" class=" js-example-basic-single js-states form-control select-2" name="city">
              @foreach (\App\Models\City::where('id_state', $product->city->id_state)->get() as $city)
                <option value="{{ $city->id_city }}">{{ $city->city_name }}</option>
              @endforeach
            </select>
            @if($errors->has('city'))
              <span class="form-text text-danger">{{ $errors->first('city') }}</span>
            @endif
          </div>
        </div>
        <div class="col-lg-6 col-sm-12">
          <div class="form-group form-group-float">
            <label for="brief_description" class="form-group-float-label animate is-visible">Deskripsi Singkat</label>
            <textarea type="text" class="form-control p-2" name="brief_description" placeholder="Deskripsi Singkat" disabled style="resize: none;">{{ old('brief_description', $product->brief_description) }}</textarea>
            @if($errors->has('brief_description'))
              <span class="form-text text-danger">{{ $errors->first('brief_description') }}</span>
            @endif
          </div>
        </div>
        <div class="col-12 text-center mt-3">
          <button class="btn btn-success" type="submit"><i class="icon-floppy-disk"></i> &nbsp;Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection
@section('additionalScript')
  <script src="{{asset('select2/select2.min.js')}}"></script>
  <script>
    window.$ = jQuery;

    $(document).ready(function () {
      $('.select-2').select2();
    });

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
  </script>
@endsection
