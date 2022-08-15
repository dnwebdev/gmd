@php
    if (request()->isSecure()){
        $prefix = 'https://';
    }else{
        $prefix = 'http://';
    }
@endphp
@forelse($products as $product)
    <div class="col-sm-6 col-md-4">
        <a class="card_product_theme" href="{{
        $product->company->domain_memoria?
        $prefix.$product->company->domain_memoria.'/product/detail/'.$product->unique_code.'?ref=directory&ref-url='.$referer:
        null

        }}" target="_blank">
            <article class="box detail_box">
                <figure>
                    <img class="img-product"
                         src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                         alt="">
                </figure>
                <div class="details">
                    <h4 class="box-title">{{ str_limit($product->product_name,30) }}</h4>
                    <hr>
                    <div class="card_short_desc">{!! str_limit($product->brief_description,50) !!}
                    </div>
                    <ul class="features check">
                        @if(app()->getLocale() == 'id')
                            <li>{{$product->city?$product->city->city_name:'-'}}</li>
                        @else
                            <li>{{$product->city?$product->city->city_name_en:''}}</li>
                        @endif

                        <li> @if($product->duration)
                                {{ $product->duration }} {{$product->duration_type_text }}
                            @else
                                {!! trans('product.info_later') !!}
                            @endif</li>
                        @if ($product->schedule->count()>0)
                            @if($product->availability =='0')
                                <li> {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}</li>
                            @else
                                <li>
                                    {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                    - {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                </li>
                            @endif
                        @else
                            <li>
                                {!! trans('product.info_later') !!}
                            </li>
                        @endif
                    </ul>
                    <hr>
                    <span>{{trans('booking.start_from')}}: </span>
                    @if($product->pricing->count()>0)

                        <div class="price"> {{ $product->currency }} {{ str_limit(number_format($product->pricing()->orderBy('price','asc')->first()->price,0),15) }}</div>
                    @else
                        <div class="price"> -</div>
                    @endif
                </div>
            </article>
        </a>
    </div>
@empty
    <div class="empty-result">
        {{  trans('product.search_empty') }}
    </div>
@endforelse
<div class="pagination-product hide" data-total="{{$products->total()}}" data-current="{{$products->currentPage()}}"
     data-nextPage="{{$products->nextPageUrl()?$products->currentPage()+1:null}}">
</div>
