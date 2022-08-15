@foreach($products as $product)
    <div class="col-lg-4 col-md-6 col-product">
        <a href="{{route('product.detail',['id_product'=>$product->unique_code])}}">
            <div class="card">
                {{-- <div class="high-risk-activity">High Risk Activity</div> --}}
                <img class="card-img-top"
                     src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                     alt="Card image cap">
                @if($product->discount_amount>0)
                    <div class="special-price"><h2>{!! trans('product.special_price') !!}</h2></div>
                        <div class="bedge-discount"><h2 class="percent">
                            @if($product->discount_amount_type =='1')
                                {!! trans('product.discount') !!}
                                {{$product->discount_amount.'%'}}
                                {!! trans('product.off') !!}
                            @else
                                {!! trans('product.save') !!}
                                {{$product->currency}} {{format_priceID($product->discount_amount,'')}}
                            @endif
                            <span class="off">/
                                {{ optional($product->price->unit)->name }}
                            </span>
                        </h2>
                    </div>
                @endif
                <div class="card-body">
                    <div class="box-product-tags py-3">
                        @if($product->tagValue)
                            @forelse($product->tagValue->take(2) as $tag)
                                @if($tag->tag)
                                    @if (App::getLocale() ==='id')
                                        <span class="badge badge-warning product-tags">{{$tag->tag->name_ind}}</span>
                                    @else
                                        <span class="badge badge-warning product-tags">{{$tag->tag->name}}</span>
                                    @endif

                                @endif
                            @empty
                                <span class="badge badge-warning product-tags">Uncategorized</span>
                            @endforelse
                        @endif
                    </div>
                    <h3>
                        {{$product->product_name}}
                    </h3>
                    <p class="card-text">{!!  $product->brief_description !!}</p>

                    <table class="table-product">
                        <tr>
                            <td>
                                <img src="{{asset('img/pin.png')}}" alt="">
                            </td>
                            <td colspan="3">
                                @if(app()->getLocale() == 'id')
                                    {{ $product->city?$product->city->city_name:'-' }}
                                @else
                                    {{ $product->city?$product->city->city_name_en:'-' }}
                                @endif


                            </td>

                        </tr>
                        <tr>
                            <td>
                                <img src="{{asset('img/wall-clock.png')}}" alt="">
                            </td>
                            <td colspan="3">
                                @if($product->duration)
                                    {{ $product->duration }} 
                                    @if($product->duration_type_text == 'Hours')
                                        {!! trans('product.hours') !!}
                                    @else
                                        {!! trans('product.days') !!}
                                    @endif
                                @else
                                    {!! trans('product.info_later') !!} 
                                @endif
                            </td>

                        </tr>
                        @if($product->availability =='0')
                            <tr>
                                <td>
                                    <img src="{{asset('img/calendar.png')}}" alt="">
                                </td>
                                <td colspan="3">
                                    {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <img src="{{asset('img/calendar.png')}}" alt="">
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                </td>
                                <td>
                                    <span class="badge badge-warning">{!! trans('customer.home.until') !!}</span>
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                </td>
                            </tr>
                        @endif

                    </table>

                </div>
                <div class="bg-light-blue text-center card-footer">
                    <p>{!! trans('customer.home.start_from') !!}</p>
                    <p class="bold fs-22">
                        {{ $product->currency }} {{ number_format($product->pricing()->orderBy('price','asc')->first()->price,0) }}
                        /
                        {{ optional($product->price->unit)->name }}
                    </p>
                    <button class="btn btn-primary">{!! trans('customer.home.book_now') !!}</button>
                </div>
            </div>
        </a>
    </div>
@endforeach

{{--<div class="pagination-product hide" data-current="{{$products->currentPage()}}"--}}
{{--     data-nextPage="{{$products->nextPageUrl()?$products->currentPage()+1:null}}">--}}

{{--</div>--}}


<div class="col-12">
    <div class="pagination-product text-center">
        <div class="info">
            {{(($products->currentPage()-1)*$products->perPage()+1).' - '.$products->lastItem()}}

            of {{$products->total()}}
        </div>
        {!! $products->links() !!}
    </div>
</div>
