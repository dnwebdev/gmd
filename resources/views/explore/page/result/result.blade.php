@extends('explore.layout')

@section('content')
    <div class="page-title-container">
        <div class="container">
            <div class="page-title pull-left">
                <h2 class="entry-title">{{ trans('explore-lang.header.explore') }} {{$title}}</h2>
            </div>
            <ul class="breadcrumbs pull-right">
                <li><a href="{{route('memoria.home')}}">{{trans('explore-lang.header.home')}}</a></li>
                <li class="active">{{$title}}</li>
            </ul>
        </div>
    </div>
    <section id="content">
        {!! Form::hidden('q',request('q')) !!}
        {!! Form::hidden('city',request('city')) !!}
        {!! Form::hidden('transport',$is_transport) !!}
        <div class="container all-activities">
            <div id="main">
                <div class="lds-dual-ring display-none"></div>
                <div class="row">
                    <div class="col-sm-4 col-md-3 sidebar">
                        <h4 class="search-results-title">
                            <i class="soap-icon-search"></i>
                            <div class="total-product-found-container">
                                <b id="total-product">0</b> {{trans('explore-lang.result.1')}}
                            </div>
                        </h4>
                        <div class="toggle-container filters-container">
                            <div class="panel style1 arrow-right">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#price-filter"
                                       class="collapsed">{{trans('explore-lang.result.2')}}</a>
                                </h4>
                                <div id="price-filter" class="panel-collapse collapse">
                                    <div class="panel-content">
                                        <div id="price-range"></div>
                                        <br/>
                                        <span class="min-price-label pull-left"></span>
                                        <span class="max-price-label pull-right"></span>
                                        <div class="clearer"></div>
                                    </div><!-- end content -->
                                </div>
                            </div>
                            @if(!$is_transport)
                                <div class="panel style1 arrow-right">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#accomodation-type-filter"
                                           class="collapsed">{{trans('explore-lang.result.3')}}</a>
                                    </h4>
                                    <div id="accomodation-type-filter" class="panel-collapse collapse">
                                        <div class="panel-content">
                                            <ul class="check-square filters-option" id="product-categories">
                                                @foreach($tags as $productType)
                                                    <li data-id="{{$productType->id}}"><a
                                                                href="#">{{app()->getLocale()=='id'?$productType->name_ind:$productType->name}}
                                                            @if(!$is_city)
                                                                <small>({{$productType->products->count()}})
                                                                </small>
                                                            @else
                                                                <small>({{$productType->products->count()}})
                                                                </small>
                                                            @endif

                                                        </a></li>
                                                @endforeach
                                                @if($nones>0)
                                                    <li data-id=""><a
                                                                href="#">
                                                            @if(app()->getLocale()=='id')
                                                                Non Kategori
                                                            @else
                                                                Uncategorized
                                                            @endif
                                                            <small>({{$nones}})
                                                            </small>
                                                        </a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($languages->count()>0)
                                <div class="panel style1 arrow-right">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#language-filter"
                                           class="collapsed">{{trans('explore-lang.result.4')}}</a>
                                    </h4>
                                    <div id="language-filter" class="panel-collapse collapse">
                                        <div class="panel-content">

                                            <ul class="check-square filters-option" id="guides">
                                                @foreach($languages as $language)
                                                    <li data-id="{{$language->id_language}}"><a
                                                                href="#">{{$language->language_name}}
                                                            @if(!$is_city)
                                                                <small>({{$language->products->count()}})
                                                                </small>
                                                            @else
                                                                <small>({{$language->products->count()}})
                                                                </small>
                                                            @endif

                                                        </a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <button class="btn-medium uppercase sky-blue1 full-width"
                                    id="apply-filter"
                                    data-lang="{{trans('explore-lang.result.5')}}">{{trans('explore-lang.result.5')}}
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-9">
                        <div class="activities_list">
                            <div class="lds-dual-ring display-none"></div>
                            <div class="row image-box hotel listing-style1">

                            </div>
                        </div>
                        <a id="load-more"
                           class="button uppercase full-width btn-large box sky-blue1 loading-button"
                           data-lang="{{ trans('explore-lang.search.more_listing') }}">{{ trans('explore-lang.search.more_listing') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
                @if(app()->getLocale() == 'en')
        let l = 'Loading..';
                @else
        let l = 'Menunggu..';
                @endif
        let page = 1;
        let price = null;
        let total = 0;

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        tjq("#price-range").slider({
            range: true,
            min: 0,
            max: 100000000,
            step: 100000,
            values: [0, 0],
            slide: function (event, ui) {
                if((ui.values[1] - ui.values[0]) <= 1000000){
                    return false;
                }
                tjq(".min-price-label").html("IDR " + formatNumber(ui.values[0]));
                tjq(".max-price-label").html("IDR " + formatNumber(ui.values[1]));
                price = ui.values[0] + '-' + ui.values[1];
            }
        });
        tjq('#price-range').draggable();
        tjq(".min-price-label").html("IDR " + formatNumber(tjq("#price-range").slider("values", 0)));
        tjq(".max-price-label").html("IDR " + formatNumber(tjq("#price-range").slider("values", 1)));
        price = tjq("#price-range").slider("values", 0) + '-' + tjq("#price-range").slider("values", 1);

        function render() {
            tjq(document).find('#content button').prop('disabled', true).html(l);
            if (page === 1) {
                loadingStart('#main');
                tjq('.listing-style1').empty();
            }
            let keyword = tjq('input[name=q]').val();
            let city = tjq('input[name=city]').val();
            let sort = tjq('input[name=sortBy]').val();
            let transport = tjq('input[name=transport]').val();
            let data = {
                q: keyword,
                city: city,
                sort: sort,
                price: price,
                page: page,
                transport: transport,
                guides: getGuides(),
                categories: getCategories()
            };

            if (page !== undefined || page !== '' || page !== null) {
                tjq.ajax({
                    url: "{{route('explore.render')}}",
                    type: "POST",
                    data: data,
                    dataType: 'html',
                    success: function (data) {
                        loadingEnd('#main','{{ trans('explore-lang.search.more_packages') }}');
                        tjq('#main .lds-dual-ring').show();
                        if (page === 1) {
                            tjq('.listing-style1').html(data)
                        } else {
                            tjq('.listing-style1').append(data);
                        }
                        if (tjq(document).find('.pagination-product').length > 0) {
                            tjq('#total-product').text(formatNumber(tjq(document).find('.pagination-product').data('total')));
                            if (tjq(document).find('.pagination-product').attr('data-nextpage') !== '') {
                                page = tjq(document).find('.pagination-product').attr('data-nextpage');
                            } else {
                                page = null
                            }

                        } else {
                            page = null

                        }
                        tjq('#main .lds-dual-ring').hide();
                        tjq(document).find('.pagination-product').remove();
                        if (page === null) {
                            tjq('#load-more').remove();
                        }
                        tjq(document).find('#content button').each(function () {
                            tjq(this).prop('disabled', false).html(tjq(this).attr('data-lang'));
                        })
                    },
                    error: function (e) {
                        console.log(e)
                        tjq(document).find('#content button').each(function () {
                            tjq(this).prop('disabled', false).html(tjq(this).attr('data-lang'));
                        })
                    }
                })
            } else {
                tjq('#load-more').remove();
                tjq(document).find('#content button').each(function () {
                    tjq(this).prop('disabled', false).html(tjq(this).attr('data-lang'));
                })
            }
        }

        render();

        tjq(document).on('click', '#load-more', function () {
            if (!tjq(this).prop('disabled')) {
                loadingStart('#main');
                render(page)
            }
        });
        tjq(document).on('click', '#apply-filter', function () {
            if (!tjq(this).prop('disabled')) {
                page = 1;
                render(page)
            }
        });

        function getGuides() {
            let guides = [];
            tjq(document).find('#guides li.active').each(function (i, data) {
                guides.push(tjq(data).data('id'));
            });
            return guides;
        }

        function getCategories() {
            let categories = [];
            tjq(document).find('#product-categories li.active').each(function (i, data) {
                categories.push(tjq(data).data('id'));
            });
            return categories;
        }
    </script>

@stop
