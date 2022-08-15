@extends('explore.layout')

@section('content')
    <section id="content">
        <div class="container">
            <div class="row">
                @include('explore.page.blog.sidebar-blog')
                <div id="main" class="col-sm-8 col-md-9">
                    <div class="page">
                        <span style="display: none;" class="entry-title page-title">Blog Left Sidebar</span>
                        <span style="display: none;" class="vcard"><span class="fn"><a rel="author"
                                                                                       title="Posts by admin" href="#">admin</a></span></span>
                        <span style="display:none;" class="updated">2014-06-20T13:35:34+00:00</span>
                        <div class="post-content">
                            <div class="list blog-infinite" style="display: none;"></div>
                            <div class="detail blog-infinite">
                                <div class="post">
                                    <figure class="image-container">
                                        @if ($blog->image_blog)
                                            <a href="{{route('blog.detail',['slug'=>$blog->slug])}}"><img
                                                        src="{{ asset($blog->image_blog) }}" alt=""/></a>
                                        @else
                                            <a href="{{route('blog.detail',['slug'=>$blog->slug])}}"><img
                                                        src="{{ asset('img/no-banner-image.png') }}" alt=""
                                                        style="width: 870px; height: 342px;"></a>
                                        @endif
                                    </figure>
                                    <div class="details">
                                        @if (App::getLocale() ==='id')
                                            <h1 class="entry-title">{!! $blog->title_ind !!}</h1>
                                        @else
                                            <h1 class="entry-title">{!! $blog->title_eng !!}</h1>
                                        @endif
                                        <div class="post-content">
                                            @if (App::getLocale() ==='id')
                                                {!! $blog->description_ind !!}
                                            @else
                                                {!! $blog->description_eng !!}
                                            @endif
                                        </div>
                                        <div class="post-meta">
                                            <div class="entry-date">
                                                <label class="date">{{ $blog->created_at->format('d') }}</label>
                                                <label class="month">{{ $blog->created_at->format('M') }}</label>
                                            </div>
                                            <div class="entry-author fn">
                                                <i class="icon soap-icon-user"></i> Posted By:
                                                <a href="#" class="author">{{ $blog->author->admin_name }}</a>
                                            </div>
                                            <div class="entry-action">
                                                <span class="entry-tags"><i class="soap-icon-features"></i><a>
                                                    @if($blog->tagValue)
                                                            @forelse($blog->tagValue as $tag)
                                                                @if($tag->tagBlogPost)
                                                                    @if (App::getLocale() ==='id')
                                                                        <a href="#">{{$tag->tagBlogPost->tag_name_ind}}</a>
                                                                        ,
                                                                    @else
                                                                        <a href="#">{{$tag->tagBlogPost->tag_name_eng}}</a>
                                                                    @endif

                                                                @endif
                                                            @empty
                                                                <a href="#">Uncategorized</a>
                                                        @endforelse
                                                    @endif
                                                  </span>
                                                <span class="entry-tags">
                                                    @if (App::getLocale() ==='id')
                                                        {{ $blog->categoryBlog->category_name_ind }}
                                                    @else
                                                        {{ $blog->categoryBlog->category_name_eng }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-navigation block">
                                        <div class="row">
                                            @if ($previous)
                                                <div class="col-xs-6"><a
                                                            href="{{ route('blog.detail',$previous->slug) }}" rel="prev"
                                                            id="previous-post"
                                                            class="button btn-large sky-blue1 prev full-width"><i
                                                                class="soap-icon-longarrow-left"></i><span>Previous Post</span></a>
                                                </div>
                                            @endif
                                            @if ($next)
                                                <div class="col-xs-6"><a href="{{ route('blog.detail',$next->slug) }}"
                                                                         rel="next" id="next-post"
                                                                         class="button btn-large sky-blue1 next full-width"><span>Next Post</span><i
                                                                class="soap-icon-longarrow-right"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="about-author block">
                                        <h2>About Author</h2>
                                        <div class="about-author-container">
                                            <div class="about-author-content">
                                                <div class="avatar">
                                                    @if (isset($blog->author->admin_avatar))
                                                        <img src="{{ asset($blog->author->admin_avatar) }}" width="96"
                                                             height="96" alt="">
                                                    @else
                                                        <img src="{{ asset('img/no-product-image.png') }}" width="96"
                                                             height="96" alt="">
                                                    @endif
                                                </div>
                                                <div class="description">
                                                    <h4><a href="#">{{ $blog->author->admin_name }}</a></h4>
                                                    <p>Gomodo Technologies</p>
                                                </div>
                                            </div>
                                            <div class="about-author-meta clearfix">
                                                {{--                                                <ul class="social-icons">--}}
                                                {{--                                                    <li><a href="#"><i class="soap-icon-twitter"></i></a></li>--}}
                                                {{--                                                    <li><a href="#"><i class="soap-icon-googleplus"></i></a></li>--}}
                                                {{--                                                    <li><a href="#"><i class="soap-icon-facebook"></i></a></li>--}}
                                                {{--                                                    <li><a href="#"><i class="soap-icon-linkedin"></i></a></li>--}}
                                                {{--                                                </ul>--}}
                                                <a href="#" class="wrote-posts-count"><i
                                                            class="soap-icon-slider"></i><span><b>{{ $countBlog }}</b> Posts</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')

    <script>
        let page = 1;
        let clicked = false;

        function render() {
            let keyword = tjq('input[name=q]').val();
            let data = {
                q: keyword,
                page: page,
            };
            if (page !== undefined || page !== '' || page !== null) {
                tjq.ajax({
                    url: "{{route('blog.render')}}",
                    type: "POST",
                    data: data,
                    dataType: 'html',
                    success: function (data) {
                        if (page === 1) {

                            if (!clicked) {
                                tjq('.list').hide();
                                tjq('.detail').show();
                            } else {
                                tjq('.detail').hide();
                                tjq('.list').show();
                                tjq('.list').html(data);
                            }

                            // Empty Result
                            if (tjq(document).find('.pagination-blog').attr('data-total') === 0) {
                                tjq('.list').append('<div class="empty-result"> {{  trans("explore-lang.blog.search_empty") }} </div>');
                            }
                        } else {
                            tjq('.blog-infinite').append(data);
                        }
                        if (tjq(document).find('.pagination-blog').length > 0) {
                            // tjq('#total-blog').text(formatNumber(tjq(document).find('.pagination-blog').data('total')));
                            if (tjq(document).find('.pagination-blog').attr('data-nextpage') !== '') {
                                page = tjq(document).find('.pagination-blog').attr('data-nextpage');
                            } else {
                                page = null
                            }

                        } else {
                            page = null

                        }
                        tjq(document).find('.pagination-blog').remove();
                        if (page === null) {
                            tjq('#load-more').remove();
                        }

                    }
                })
            } else {
                tjq('#load-more').remove();
            }
        }

        render();

        tjq(document).on('click', '#load-more', function () {
            render(page)
        });

        tjq(document).on('submit', '#search-blog', function (e) {
            e.preventDefault();
            page = 1;
            clicked = tjq('input[name=q]').val().length >= 1;
            render(page);
        });

    </script>
@stop