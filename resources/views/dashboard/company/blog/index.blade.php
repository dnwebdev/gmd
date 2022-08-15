@extends('dashboard.company.base_layout')

@section('title', trans('sidebar_provider.articles'))

@section('additionalStyle')
@stop

@section('indicator_order')
    active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.articles') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('sidebar_provider.articles') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->
<!-- main content -->
<div class="content pt-0" dashboard>
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <!-- Inner container -->
        <div class="d-flex align-items-start flex-column flex-md-row">

            <!-- Left content -->
            <div class="w-100 order-2 order-md-1" id="render_blog_list">


            </div>
            <!-- /left content -->


            <!-- Right sidebar component -->
            <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">

                <!-- Sidebar content -->
                <div class="sidebar-content">

                    <!-- Search -->
                    <div class="card">
                        {{-- <div class="card-header bg-transparent header-elements-inline">
                            <span class="card-title font-weight-semibold">{{ trans('article.search') }}</span>
                        </div> --}}

                        <div class="card-body">
                            <form action="#">
                                <div class="form-group-feedback form-group-feedback-right">
                                    <input type="search" class="form-control" placeholder="{{ trans('article.search_placeholder') }}" id="search_data_blog">
                                    <div class="form-control-feedback">
                                        <i class="icon-search4 font-size-base text-muted"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /search -->


                    <!-- Categories -->
                    <div class="card">
                        <div class="card-header bg-transparent header-elements-inline">
                            <span class="card-title font-weight-semibold">{{ trans('article.recent') }}</span>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="nav nav-sidebar my-2">
                                @foreach($recent_blogs as $recent)
                                <li class="nav-item">
                                    <a href="{{ route('company.blog.detail', ['slug'=>$recent->slug]) }}" class="nav-link link-to-blog-detail">
                                        <i class="icon-magazine mt-1"></i>
                                        {{ $recent->title }}
                                    </a>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- /categories -->

                    <!-- Categories -->
                    <div class="card">
                        <div class="card-header bg-transparent header-elements-inline">
                            <span class="card-title font-weight-semibold">{{ trans('article.popular') }}</span>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="nav nav-sidebar my-2">
                                @foreach($popular_blogs as $popular)
                                <li class="nav-item">
                                    <a href="{{ route('company.blog.detail', ['slug'=>$popular->slug]) }}" class="nav-link link-to-blog-detail">
                                        <i class="icon-magazine mt-1"></i>
                                        {{ $popular->title }}
                                    </a>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- /categories -->

                    <!-- Categories -->
                    <div class="card">
                        <div class="card-header bg-transparent header-elements-inline">
                            <span class="card-title font-weight-semibold">{{ trans('article.categories') }}</span>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="nav nav-sidebar my-2">
                                    @if ($blogCategories->count() > 1)
                                        <li class="nav-item blog-category-sidebar">
                                            <a href="{{ route('company.blog.index') }}" class="nav-link">
                                                {!! trans('bank_provider.all') !!}
                                            </a>
                                        </li>
                                    @endif
                                    @foreach($blogCategories as $category)
                                        <li class="nav-item blog-category-sidebar">
                                            <a href="#" class="nav-link" data-id="{{ $category->id }}">
                                                {{ $category->category_name }}
                                                <span class="text-muted font-size-sm font-weight-normal ml-auto">{{ $category->published_post_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- /categories -->

                </div>
                <!-- /sidebar content -->

            </div>
            <!-- /right sidebar component -->

        </div>
        <!-- /inner container -->
                
    </div>
</div>

@stop

@section('additionalScript')
<script type="text/javascript">
    // Data -------------------------------------------------------------------------------------------
    var search = {
        q : '',
        category : '',
        page : 1,
    };

    // Init -------------------------------------------------------------------------------------------
    const searchUrl = window.location.search
    if (searchUrl !== "") {
        linkToBlog(decodeURIComponent(searchUrl.slice(searchUrl.indexOf("slug=") + 5)))
    } else {
        render()
    }

    // Event -------------------------------------------------------------------------------------------

    $(document).on('click', '#b2b_blog_pagination ul.pagination li', function (e) {
        e.preventDefault();

        let anchor = $(this).find('a');
        if (anchor.hasClass('disabled')) {

        } else {
            let page = getUrlVars(anchor.attr('href'))['page'];
            let category = getUrlVars(anchor.attr('href'))['category'];
            let q = getUrlVars(anchor.attr('href'))['q'];
            if (page !== undefined) {
                search.page = page;
            }
            if (category !== undefined) {
                search.category = category;
            }
            if (q !== undefined) {
                search.q = q;
            }
            render();
        }
    });

    $(document).on('click', '.blog-category-sidebar a', function(e){
        e.preventDefault();
        var th = $(this);
        search.category = th.data('id');
        search.page = 1;
        render();
    })

    $(document).on('change', '#search_data_blog', function(){
        search.q = $(this).val();
        render();
    })
    $(document).on('click', '.link-to-blog-detail', function(e){
        e.preventDefault();
        let href = $(this).attr('href');
        linkToBlog(href.slice(href.indexOf('slug=') + 5));
    })

    $(document).on('click', '#backToBlog', function(){
        render();
        window.location.href = window.location.origin + window.location.pathname;
    })

    // Functions -------------------------------------------------------------------------------------------

    function render() {
        $('.loading').addClass('show');
        $.ajax({
            url: '{{ route('company.blog.search') }}',
            data: search,
            success: function(data){
                $('#render_blog_list').html(data);
                $('.loading').removeClass('show');
            },
            error: function(data){
                $('.loading').removeClass('show');
                console.log(data);
            }
        })
    }


    function getUrlVars(url) {
        let vars = {};
        url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });
        return vars;
    }

    function linkToBlog(slug) {
        history.pushState({}, "", "article?slug=" + encodeURIComponent(slug))
        $('.loading').addClass('show');
        $.ajax({
            url: window.location.pathname + '/detail?slug=' + slug,
            success: function(data){
                $('#render_blog_list').html(data);
                $('.loading').removeClass('show');
            },
            error: function(data){
                $('.loading').removeClass('show');
                console.log(data);
            }
        })
    }
</script>
@stop
