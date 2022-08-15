@forelse($blogs as $blog)
    <div class="post">
        <div class="post-content-wrapper">
            <figure class="image-container">
                @if ($blog->image_blog)
                    <a href="{{route('blog.detail',['slug'=>$blog->slug])}}">
                        <img src="{{ asset($blog->image_blog) }}" alt="{{ $blog->attribute }}"/>
                    </a>
                @else
                    <a href="{{route('blog.detail',['slug'=>$blog->slug])}}">
                        <img src="{{ asset('img/no-banner-image.png') }}" alt="{{ $blog->attribute }}"
                             style="width: 870px; height: 342px;">
                    </a>
                @endif
            </figure>
            <div class="details">

                <h2 class="entry-title">
                    @if (App::getLocale() ==='id')
                        <a href="{{route('blog.detail',['slug'=>$blog->slug])}}">{{ $blog->title_ind }}</a>
                    @else
                        <a href="{{route('blog.detail',['slug'=>$blog->slug])}}">{{ $blog->title_eng }}</a>
                    @endif
                </h2>
                <div class="excerpt-container">
                    @if (App::getLocale() ==='id')
                        <p>{!! (str_limit(strip_tags($blog->description_ind), 250)) !!}</p>
                    @else
                        <p>{!! (str_limit(strip_tags($blog->description_eng), 250)) !!}</p>
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
                        <span class="entry-tags"><i class="soap-icon-features"></i>
                            @if($blog->tagValue)
                                    @forelse($blog->tagValue as $tag)
                                        @if($tag->tagBlogPost)
                                            @if (App::getLocale() ==='id')
                                                <a href="#">{{$tag->tagBlogPost->tag_name_ind}}</a>,
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
        </div>
    </div>
@empty
    <div class="empty-result">
        {{  trans('product.search_empty') }}
    </div>

@endforelse
<div class="pagination-blog hide" data-total="{{$blogs->total()}}" data-current="{{$blogs->currentPage()}}"
     data-nextPage="{{$blogs->nextPageUrl()?$blogs->currentPage()+1:null}}">
</div>