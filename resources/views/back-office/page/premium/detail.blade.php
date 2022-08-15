@extends('back-office.layout.index')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
@stop
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Detail Ads</h3>
        </div>

    </div>
@stop
@section('content')
    <a href="{{ route('admin:premium.premium.index') }}" class="btn btn-sm btn-success" style="margin-bottom: 10px">Back</a>
    <div class="m-portlet">
        <div class="m-portlet__body ">
               {!! Form::model($find, ['id' => 'f-save-ad', 'files' => true]) !!}
            <div class="row ">
                <div class="col-6">
                    <div class="form-group">
                        {!! Form::label('category_ads','Category Ads') !!}
                        {!! Form::text('title', $find->order_ads->category_ads ,['class'=>'form-control','id'=>'category', 'placeholder'=> 'Category', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('title','Title'.($find->order_ads->category_ads == 'Google Ads' ? ' 1' : '')) !!}
                        {!! Form::text('title',null,['class'=>'form-control','id'=>'title', 'placeholder'=> 'Title English', 'disabled']) !!}
                    </div>
                    @if ($find->order_ads->category_ads == 'Google Ads')
                    <div class="form-group">
                        {!! Form::label('title2','Title 2') !!}
                        {!! Form::text('title2',null,['class'=>'form-control','id'=>'title2', 'placeholder'=> 'Title English', 'disabled']) !!}
                    </div>
                    @endif
                    <div class="form-group">
                        {!! Form::label('url','URL') !!}
                        {!! Form::text('url',null,['class'=>'form-control','id'=>'url', 'placeholder'=> 'URL', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('gender','Gender') !!}
                        {!! Form::text('gender',null,['class'=>'form-control','id'=>'gender', 'placeholder'=> 'Gender', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('age','Age') !!}
                        {!! Form::text('age',null,['class'=>'form-control','id'=>'age', 'placeholder'=> 'Age', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('start_date','Start Date') !!}
                        <input type="text" value="{{ date('d M Y', strtotime($find->start_date)) }}" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        {!! Form::label('end_date','End Date') !!}
                        <input type="text" value="{{ date('d M Y', strtotime($find->end_date)) }}" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description','Description') !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','id'=>'description', 'disabled']) !!}
                    </div>

                </div>
                <div class="col-6">
                    <div class="form-group">
                        {!! Form::label('sub_total','Sub Total') !!}
                        <input type="text" class="form-control" value="{{ format_priceID($find->order_ads->amount) }}" disabled>
                    </div>
                    <div class="form-group">
                        {!! Form::label('total','Service Fee') !!}
                        <input type="text" class="form-control" value="{{ format_priceID($find->service_fee) }}" disabled>
                    </div>
                    @if ($find->order_ads->promoAds)
                        <div class="form-group">
                            <label for="promo_code">Voucher By Gomodo {{ $find->order_ads->promoAds->code }}</label>
                            <input type="text" class="form-control" value="{{ format_priceID($find->order_ads->voucher) }}" disabled>
                        </div>
                    @endif
                    @if ($find->order_ads->voucherAds)
                        <div class="form-group">
                            <label for="voucher_cashback">Voucher</label>
                            <input type="text" class="form-control" value="{{ format_priceID($find->order_ads->voucherAds->nominal) }}" disabled>
                        </div>
                    @endif
                    @if ($find->order_ads->gxp_amount)
                        <div class="form-group">
                            {!! Form::label('total','Gxp Amount') !!}
                            <input type="text" class="form-control" value="{{ format_priceID($find->order_ads->gxp_amount) }}" disabled>
                        </div>
                    @endif
                    <div class="form-group">
                        {!! Form::label('total','Total') !!}
                        <input type="text" class="form-control" value="{{ format_priceID($find->order_ads->total_price) }}" disabled>
                    </div>
                    @if ($find->order_ads->category_ads != 'Google Ads')
                        <div class="form-group">
                            <div class="col-md-6 mb-7">
                                <div class="card">
                                    <img class="card-img-top"
                                         @if($find->document_ads)
                                         src="{{ $find->imageUrl }}"
                                         @else
                                         src="{{asset('img/no-product-image.png')}}"
                                         @endif
                                         alt="image"
                                         style="width:100%">
                                    <div class="card-body">
                                        <a href="{{ route('admin:premium.premium.premium-download', ['id' => $find->id]) }}" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('call_button','Tombol Ajakan') !!}
                            {!! Form::text('call_button', null ,['class'=>'form-control','id'=>'call_button', 'placeholder'=> 'Tombol Ajakan', 'disabled']) !!}
                        </div>
                    @else
                        <div class="form-group">
                            {!! Form::label('phone_number','Phone Number') !!}
                            {!! Form::text('phone_number', null ,['class'=>'form-control','id'=>'phone_number', 'placeholder'=> 'Phone Number', 'disabled']) !!}
                        </div>
                    @endif
                    @if (!is_null($find->language))
                        <div class="form-group">
                            @php
                                $lang = collect($find->language)->map(function ($val) {
                                    return \App\Models\Ads::$languages[$val][app()->getLocale()];
                                })->implode(', ');
                            @endphp
                            {!! Form::label('language','Language') !!}
                            {!! Form::text('language', $lang,['class'=>'form-control','id'=>'language', 'placeholder'=> 'Language', 'disabled']) !!}
                        </div>
                    @endif
                    @if (!empty($find->businessCategory->toArray()))
                        <div class="form-group">
                            @php
                                $business_category = $find->businessCategory->implode('business_category_name', ', ');
                            @endphp
                            {!! Form::label('business_category','Business Category') !!}
                            {!! Form::text('business_category', $business_category,['class'=>'form-control','id'=>'business-category', 'placeholder'=> 'Business Category', 'disabled']) !!}
                        </div>
                    @endif
                    @if (!empty($find->adsCity))
                        <div class="form-group">
                            @php
                                $city = $find->adsCity->map(function ($val) {
                                    return $val->city_name .', '.$val->state->state_name.', '.$val->state->country->country_name;
                                })->implode("\n");
                            @endphp
                            {!! Form::label('city','City') !!}
                            {!! Form::textarea('city', $city, ['class'=>'form-control','id'=>'city', 'placeholder'=> 'City', 'disabled']) !!}
                        </div>
                    @endif
                </div>
            </div>
                {!! Form::close() !!}
        </div>
    </div>

@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.multiple-select').select2();
        });
        $(".tinyMCE").summernote({height: 400});
        $('.tinyMCE').summernote('fontSize', 13);

        $(document).on('click', '.btn-change-image', function (e) {
            e.preventDefault();
            $(this).parent().find('input[name=image_blog]').trigger('click');
        });

        $(document).on('click','#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            $.ajax({
                url:'',
                type:"POST",
                data: formData,
                dataType:'json',
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success:function (data) {
                    loadingFinish();
                    toastr.success(data.message,"Yey");
                    setTimeout(function () {
                        window.location = data.result.redirect;
                    },1000);
                },
                error:function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                        })

                    }
                    loadingFinish();
                }
            })

        })
    </script>
@stop
