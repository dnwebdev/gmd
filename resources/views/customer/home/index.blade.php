@extends('customer.master.index')

@section('additionalStyle')
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/jquery.dropdown.css')}}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

	
	
    <style>
        .owl-nav {
            display: none !important;
        }

        .owl-stage {
            display: flex;
        }

        #banner {
            height: auto;
            width: 100%;
        }

        #banner img {
            width: 100%;
            max-height: 37rem;
        }

        .pagination-product .pagination .disabled {
            background-color: white;
            color: #3099fb;
        }

        html {
            scroll-behavior: smooth
        }

        .col-top-product .card {
            height: 100%;
        }

        .col-top-product .card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .col-top-product .card span.product-tags {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .col-top-product a {
            color: #3a4555;
            text-decoration: none;
        }

        .col-top-product h3 {
            font-size: 20px;
            font-weight: 700;
        }

        .col-top-product .card:hover {
            -webkit-transform: scale(1.01);
            transform: scale(1.01);
            -webkit-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
        }

        #list-product .col-top-product {

        }

        #container-top-product {
            padding: 64px 0px;
            background-color: #fafafa;
            margin-bottom: 64px;
        }
        *[class^='Main__ItemsContainer']{
            display: none;
        }
		
		#katProduk{display:none}
		.search-field, span.dropdown-selected {
            font-size: 0.8rem;
        }
        .search-field, span.dropdown-list {
            font-size: 0.8rem;
        }

    </style>
    <link rel="stylesheet" href="{{asset('additional/google-review/index.css')}}">
@stop

@section('content')
    <div class="container-fluid foo-x6" id="banner">
        {{-- <img src="{{$company->banner_url}}" class="img-fluid" alt=""> --}}
        <img src="{{$company->banner_url}}" class="img-fluid" alt="">
    </div>

    <div class="intro container py-5" id="intro" style="padding-bottom: 1rem!important;">
        @if($company->associations->count()>0)
            <div class="badge-container">
                <div class="badge dropdown-badge">
                    <i class="fas fa-angle-down arrow-left"></i>
                    <h2>{{ trans('customer.home.verified') }} <br> {{ trans('customer.home.member') }} </h2> <i
                            class="fas fa-angle-down arrow-right"></i>
                    <div class="badge-dropdown-content">
                        @foreach($company->associations as $association)
                            <div class="relative row">
                        <span class="col-4 badge-dropdown-badge">
                            <img src="{{asset($association->association_logo)}}" alt="">
                            <img src="{{asset('img/check.svg')}}" alt="" class="checked">
                        </span>
                                <span class="col-8 badge-dropdown-desc">
                            {{ trans('customer.home.verified2') }} {{strtoupper($association->association_name)}} {{ trans('customer.home.member2') }}
                        </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="badge-white badge-tooltip">
                    @foreach($company->associations as $association)
                        <span class="relative">
                        <img src="{{asset($association->association_logo)}}" alt="">
                        <img src="{{asset('img/check.svg')}}" alt="" class="checked">
                        <span class="tooltiptext">
                            <span class="font-weight-bold">{{ trans('customer.home.verified2') }} <br> {{strtoupper($association->association_name)}} {{ trans('customer.home.member2') }}</span>
                        </span>
                    </span>
                    @endforeach

                </div>
            </div>
        @endif
            @if($company->google_review_widget && $company->google_review_widget->widget_script)
                <div class="container">
                    <div class="row my-5 row-flex ">
                        <div class="col">
                            {!! $company->google_review_widget->widget_script !!}
{{--                            <script defer src="{{asset('js/google-review.js')}}">--}}
{{--                            </script>--}}
                        </div>
                    </div>
                </div>
            @endif
        <div class="row">
            <div class="col-12 text-center">
                <p>{{$company->short_description}}</p>
            </div>
        </div>
    </div>
    <div class="container mb-5" id="search-section">
        <div class="row">
            <!--<div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('search',trans('customer.home.search')) !!}
					
                    {--!!Form::search('q',$urlKeyword,['class'=>'form-control search-field','placeholder'=>trans('customer.home.search_placeholder')]) 					!!--}
                </div>
            </div>-->
			
			<div class="col-md-2">
               <div class="form-group">
                    {!! Form::label('Kategori Produk',trans('customer.home.product_category')) !!}
					<div class="katProduk" >
					    
						 <select class="form-control search-field" placeholder="All" style="display:none!important;width:100%;"  name="katProduk" multiple>
					<?php
					 
						 if(empty($urlKat))
							$sela = 'selected';
						 else if(count($urlKat)==1 && $urlKat[0]=='')						 
							$sela = 'selected';
						 else
						    $sela ='';
						 
						// echo "<option value='' $sela>All</option>";
							
						
						
						foreach ($katProduk as $k){
							if(in_array($k->id, $urlKat))
								$sel='selected';
							else 
								$sel='';
						?>	
						
						<option value="{{ $k->id }}"  {{$sel}}>{{$k->name_ind}}</option>
						<?php
								
							}
						?>
							   

					  </select>
					</div>
                </div>
            </div>
			<!--<div class="col-md-2">
               <div class="form-group">
                    {!! Form::label('Tag Produk','Tag Produk') !!}
					<div class="katProduk" >
						 <select class="form-control search-field" style="display:none!important;width:100%;"  name="tagProduk" id="tagProduk" multiple>
						 
						<?php
						
						/* if(empty($urlTag))
							$sela = 'selected';
						 else if(count($urlTag)==1 && $urlTag[0]=='')						 
							$sela = 'selected';
						 else
						    $sela ='';
							
						 echo "<option value $sela>All</option>";
						 
						foreach ($tagProduk as $k){
							if(in_array($k->id, $urlTag))
								$sel='selected';
							else 
								$sel='';*/
						?>	
						
						<option value="{{ $k->id }}" {{$sel}} >{{$k->name_ind}}</option>
						<?php
								
						//	}
						?>
							   

					  </select>
					</div>
                </div>
            </div>-->
			<div class="col-md-3">
                <div class="form-group">
				
                    {!! Form::label('state',trans('customer.home.state')) !!}
                    @php
                         $items = \App\Models\City::whereHas('product', function ($product) use($company, $p) {
                            $product->where('id_company', $company->id_company)->where('publish', 1)->where('status', 1)->where('booking_type', 'online')->where('deleted_at', null)->whereNotIn('id_product', $p)->availableQuota();
                        })
                        ->get()
                        ->map(function($item) {
                            return [
                                'id_city'   => $item->id_city,
                                'city_name' => app()->getLocale() == 'en' ? $item->city_name_en : $item->city_name
                            ];
                        })
                        ->pluck('id_city');
						
						$prov = DB::table('tbl_state')
						->select('tbl_state.id_state', 'state_name')
						->join('tbl_city', 'tbl_city.id_state','=', 'tbl_state.id_state')
						->whereIn('tbl_city.id_city', $items)
						->get()
						
                        ->pluck('state_name','id_state')
                    @endphp
					
                    {!! Form::select('prov', $prov, null, ['class'=>'form-control search-field','placeholder'=>'All', 'onchange'=>'ambilKota(this.value)']) !!}
					
					
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
				
                    {!! Form::label('location',trans('customer.home.location')) !!}
					
                   @php
                       $items = \App\Models\City::whereHas('product', function ($product) use($company, $p) {
                            $product->where('id_company', $company->id_company)->where('publish', 1)->where('status', 1)->where('booking_type', 'online')->where('deleted_at', null)->whereNotIn('id_product', $p)->availableQuota();
                        })
						->where('id_state', $urlProv)
                        ->get()
                        ->map(function($item) {
                            return [
                                'id_city'   => $item->id_city,
                                'city_name' => app()->getLocale() == 'en' ? $item->city_name_en : $item->city_name
                            ];
                        })
                        ->pluck('city_name','id_city')
                    @endphp
					
					@if($urlCity!='')
						{!! Form::select('city', $items, $urlCity, ['class'=>'form-control search-field']) !!}
					@else
						{!! Form::select('city', array(), null, ['class'=>'form-control search-field','placeholder'=>'All']) !!}
					@endif
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('sort',trans('customer.home.sort_by')) !!}
                    {!! Form::select('sortBy',[
                        'newest'=>trans('customer.home.latest'),
                        'oldest'=>trans('customer.home.oldest'),
                        'cheapest'=>trans('customer.home.lowest'),
                        'most_expensive'=>trans('customer.home.highest'),
                        'name_asc'=>'Alphabet A - Z',
                        'name_desc'=>'Alphabet Z - A',

                    ],$urlSort,['class'=>'form-control search-field','placeholder'=>trans('customer.home.sort_by')]) !!}
                </div>
            </div>
			 <div class="col-md-1" style="padding-top:5px;position:relative; top:25px;">
				<button  type="button" id='btCari' onclick='kirim()' class="btn btn-info btn-md btn-xs-block">{!!trans('customer.home.search')!!}</button>
			</div>
			<div class="col-md-1 col-xs-12" style="padding-top:5px;position:relative; top:25px;">
				<button type="button" id='btReset' onclick='awal()'  class="btn btn-info btn-md btn-xs-block">Reset</button>
			</div>
        </div>
    </div>
    <div class="container-fluid" id="list-product">
        <div class="row">
            @if (count($products)>0 && $company->show_popular == '1')
                <div class="container-fluid" id="container-top-product">
                    <div class="row">
                        <div class="col-12">
                            <div class="container">
                                <div class="row row-top-product">
                                    @foreach($products as $product)
                                        <div class="col-lg-4 col-md-6 col-top-product">
                                            <a href="{{route('product.detail',['id_product'=>$product->unique_code])}}">
                                                <div class="card">
                                                    <div class="high-risk-activity">{!! trans('product.top_product') !!}</div>
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
                                                                        <span class="badge badge-warning bg-light-blue color-primary-blue">{!! trans('customer.home.until') !!}</span>
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
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
            <div class="container">

                <div class="row product-feed">

                </div>
            </div>
        </div>


    </div>
@stop

@section('scripts')

    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
	
    <script>
	var dropKat=''
	$(document).ready(function(){
	  dropKat = $('.katProduk').dropdown({
			
			searchable:false,
				
		});
		
		
	})
	var klikCari='false';

        function render(page = 1) {
            //let keyword = $('input[name=q]').val();
			let arrKategori = $('select[name=katProduk]').val();
			//let arrTag = $('select[name=tagProduk]').val();
			let prov = $('select[name=prov]').val();
            let city = $('select[name=city]').val();
            let sort = $('select[name=sortBy]').val();
			
			let kat = arrKategori.toString()
			//let tag = arrTag.toString()
			
			//Jika copy paste URL pertama kali dengan query string
			/*let qKategori = getUrlVar()["kategori"];
			let qTag = getUrlVar()["tag"];
			
			if(arrKategori=='' && typeof(qKategori)!='undefined'){
				kat = qKategori
				let arrayKat = qKategori.split(",")
				if(arrayKat.length==1)
					$('select[name=katProduk]').val(qKategori)
				else{
					  $('select[name=katProduk]').val(arrayKategori)
				}
			}
			
			if(arrTag=='' && typeof(qTag)!='undefined'){
				tag = qTag
				let arrayTag = qTag.split(",")
				if(arrayTag.length==1)
					$('select[name=tagProduk]').val(qTag)
				else{
					  $('select[name=tagProduk]').val(arrayTag)
				}
			}*/
			  
            let data = {
            //    keyword: keyword,
				kategori: kat,
			//	tag: tag,
			    prov:prov,
                city: city,
                sort: sort,
                page: page
            };
			
			
			
			
            loadingStart();

            $('.pagination-product').remove();
            $.ajax({
                url: "{{route('memoria.render.products')}}",
                data: data,
                dataType: 'html',
                success: function (data) {
					
                    localStorage.setItem('page_pagination', page);
                    $('#list-product .row.product-feed').html(data);
                    setTimeout(function () {
                        loadingFinish();
                       // if(keyword == ''){
                           // scrollTop();
                       // }
                    }, 1000);
					
			
			
					if (typeof (history.pushState) != "undefined" && klikCari==true) {
						
						
						
						let alamat = "{{route('memoria.home')}}?"+"kategori="+kat+"&prov="+prov+"&city="+city+"&sort="+sort
						
						var obj2 = { Title: 'title', Url:alamat}
						
						history.pushState(obj2, obj2.Title, obj2.Url);
					}
					
					
                }
            })
        }

        if (localStorage.getItem('page_pagination') === '') {
            //render(1);
        } else {
            render(localStorage.getItem('page_pagination'))
        }

        setTimeout(function () {
            localStorage.clear();
        }, 600000);

			function cekQueryString() {
				var currentQueryString = window.location.search;
				if (currentQueryString) {
					return true;
				} else {
				  return false;
				}
			}

        function getUrlVars(url) {
            let vars = {};
            url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
                vars[key] = value;
            });
            return vars;
        }

        $(document).on('click', '.pagination-product ul.pagination li', function (e) {
            e.preventDefault();

            let anchor = $(this).find('a');
            if (anchor.hasClass('disabled')) {

            } else {
                let page = getUrlVars(anchor.attr('href'))['page'];
                if (page !== undefined) {
                    render(page);
                }
            }
        });

      //  $(document).on('click', '.dropdown-main', function () {
          // let arrKategori = $('select[name=katProduk]').val();
		//   let kat = $("a.dropdown-display").attr('title')
		//	console.log(kat)
       // });
		
		function kirim(){
			klikCari=true;
			render(true)
		}
       /* $(document).on('keypress search input paste cut', '#search-section input', function (evt) {
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            clearTimeout($(this).data('timer'));
            if (charCode === 13) {
                render(true);
            } else {
                $(this).data('timer', setTimeout('render(true)', 500));
            }
        });*/
        // $(window).scroll(function () {
        //     if ($('.pagination-product').length > 0) {
        //         let hT = $(document).find('.pagination-product:last-child').offset().top,
        //             hH = $(document).find('.pagination-product:last-child').outerHeight(),
        //             wH = $(window).height(),
        //             wS = $(this).scrollTop();
        //         if (wS > (hT + hH - wH)) {
        //             render();
        //         }
        //     }
        // });

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('.dropdown-badge').on('click', function () {
                $('.badge-dropdown-content').toggle(200);
            })
        }

        // $(document).ready(function () {
        //     $("#banner").owlCarousel({
        //         items: 1,
        //         dots: false,
        //         nav: false,
        //     });
        // });

        function scrollTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
		
		function ambilKota(id_prov){
			
			let data = "id_state="+id_prov;
			if(id_prov==''){
				$('select[name=prov]').val('');
				$('select[name=city]').html("<option value>All</option>");
				return
				
			}
			 $.ajax({
			
                url: "{{route('memoria.ambilKota')}}",
			    data: data ,
                success: function (data) {
					let isi  ="<option value>All</option>"
								isi+=data
					$('select[name=city]').html(isi);
					
				}
			 })
		}
		
		function awal(){
			
			dropKat.data().dropdown.reset()
			
			$('select[name=prov]').val('');
			$('select[name=city]').html("<option value>All</option>");
			//let prov = $('select[name=prov]')

            $('select[name=sortBy]').val('cheapest')
			
			let alamat = "{{route('memoria.home')}}?"+"kategori=&prov=&city=&sort=cheapest"
						
			var obj2 = { Title: 'title', Url:alamat}
						
			history.pushState(obj2, obj2.Title, obj2.Url);
			
		}
		
		
		
    </script>
@stop
